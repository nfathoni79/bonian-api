<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 23/04/2019
 * Time: 13:34
 */

namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;
use Cake\Database\Expression\QueryExpression;
use Cake\Utility\Hash;
use Cake\Validation\Validator;

/**
 * Class ProductFiltersController
 * @property \App\Model\Table\ProductsTable $Products
 * @property \App\Model\Table\ProductCategoriesTable $ProductCategories
 * @property \App\Model\Table\ProductOptionValueListsTable $ProductOptionValueLists
 * @package App\Controller\V1
 */

class ProductFiltersController extends Controller
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Products');
        $this->loadModel('ProductCategories');
        $this->loadModel('ProductOptionValueLists');
    }


    protected function walk_recursive(&$object, $product_category_total, $selected, $expand = [])
    {
        foreach($object as $key => &$item) {
            if ($item instanceof \App\Model\Entity\ProductCategory) {

                if (count($selected) > 0 && !isset($selected[$item['id']])) {
                    unset($object[$key]);
                }

                $item['text'] = $item['name'];
                $item['total'] = 0;

                if (isset($selected[$item['id']])) {
                    $item['total'] = $selected[$item['id']];
                }

                $item['state'] = [
                    'checked' => in_array($item['id'], $expand),
                    'expanded' => in_array($item['id'], $expand),
                    'selected' => count($expand) > 0 && $item['id'] == $expand[count($expand) - 1]
                ];
                $item['nodes'] = $item['children'];
                unset($item['path']);
                unset($item['name']);
                //unset($item['parent_id']);
                unset($item['children']);
                if ($item['nodes']) {
                    $this->walk_recursive($item['nodes'], $product_category_total, $selected, $expand);
                } else {
                    unset($item['nodes']);
                }


            }
        }
        return $this;
    }

    protected function reindex(&$array)
    {

        $array = array_values($array);
        foreach ($array as $key => &$value) {
           if(isset($value['nodes'])) {
               $value['nodes'] = array_values($value['nodes']);
               $this->reindex($array[$key]['nodes']);
           }
        }
        return $this;
    }

    public function categories()
    {

        $keywords = $this->request->getQuery('q');
        $category_id = $this->request->getQuery('category_id');

        $categories = $this->ProductCategories->find('threaded');

        $categories = $categories
            ->select(['id','parent_id','name', 'slug','path'])
            ->map(function (\App\Model\Entity\ProductCategory $row) {
                return $row;
            })
            ->toArray();

        $hasProducts = $this->Products->ProductToCategories->find();

        $hasProducts = $hasProducts
            ->select([
                'total_products' => $hasProducts->func()->count('product_id'),
                'product_category_id'
            ])
            ->contain([
                'Products' => [
                    'fields' => [
                        'id',
                        'name'
                    ]
                ]
            ])
            ->where([
                'Products.product_status_id' => 1
            ]);

        if ($keywords) {
            $hasProducts
                ->where([
                    'MATCH (Products.name, Products.highlight) AGAINST (:search IN BOOLEAN MODE)'
                ])
                ->bind(':search', $keywords, 'string');
        }


        $hasProducts = $hasProducts
            ->enableAutoFields(true)
            ->group('product_category_id')
            ->toArray();

        $expandable = [];
        if ($category_id) {
            $path = $this->ProductCategories->find('path', ['for' => $category_id])->toArray();
            foreach($path as $vals) {
                if (!in_array($vals['id'], $expandable)) {
                    array_push($expandable, $vals['id']);
                }
            }
        }




        $product_category_total = [];
        $selected = [];
        foreach($hasProducts as $val) {
            $product_category_total[$val['product_category_id']] = $val['total_products'];
            $path = $this->ProductCategories->find('path', ['for' => $val['product_category_id']])->toArray();
            foreach($path as $vals) {
                if (!isset($selected[$vals['id']])) {
                    //array_push($selected, $vals['id']);
                    $selected[$vals['id']] = $val['total_products'];
                } else {
                    $selected[$vals['id']] += $val['total_products'];
                }
            }
        }

        //debug($expandable);exit;


        $this->walk_recursive($categories, $product_category_total, $selected, $expandable)
            ->reindex($categories);



        $this->set(compact('categories'));
    }

    public function variant()
    {
        $search = $this->request->getQuery('q');
        $category_id = $this->request->getQuery('category_id');
        $min_price = $this->request->getQuery('min_price');
        $max_price = $this->request->getQuery('max_price');

        $validator = $this->_validator();

        $error = $validator->errors($this->request->getQueryParams());

        if (!$error) {
            $data = $this->ProductOptionValueLists->find();

            $data = $data
                ->select([
                    'option_values' => "GROUP_CONCAT(ProductOptionValueLists.option_value_id)",
                    'Options.id',
                    'Options.name'
                ])
                ->leftJoin(['ProductOptionPrices' => 'product_option_prices'], [
                    'ProductOptionValueLists.product_option_price_id = ProductOptionPrices.id'
                ])
                ->leftJoin(['Products' => 'products'], [
                    'ProductOptionPrices.product_id = Products.id'
                ])
                ->leftJoin(['ProductToCategories' => 'product_to_categories'], [
                    'ProductOptionPrices.product_id = ProductToCategories.product_id'
                ])
                ->leftJoin(['Options' => 'options'], [
                    'ProductOptionValueLists.option_id = Options.id'
                ])
                ->leftJoin(['OptionValues' => 'option_values'], [
                    'ProductOptionValueLists.option_value_id = OptionValues.id'
                ])
                ->group([
                    'ProductOptionValueLists.option_id'
                ])
                ->where([]);

            if ($search) {
                $data->where([
                    'MATCH (Products.name, Products.highlight) AGAINST (:search IN BOOLEAN MODE)'
                ])
                    ->bind(':search', $search, 'string');
            }

            if ($min_price && $max_price) {
                $data->where(function(QueryExpression $exp) use ($min_price, $max_price) {
                    return $exp->between('price_sale', $min_price, $max_price);
                });
            }

            if ($category_id) {
                $descendants = $this->ProductCategories->find('children', ['for' => $category_id])
                    ->toArray();
                $children = Hash::extract($descendants, '{n}.id');
                if ($children) {
                    $data->where([
                        'ProductToCategories.product_category_id IN' => $children
                    ]);
                } else {
                    $data->where([
                        'ProductToCategories.product_category_id' => $category_id
                    ]);
                }
            }

            $data = $data
                ->map(function(\App\Model\Entity\ProductOptionValueList $row) {
                    $values = array_unique(explode(',', $row->option_values));
                    $value_lists = [];
                    if (is_array($values)) {
                        $valueEntities = $this->ProductOptionValueLists->OptionValues->find()
                            ->where([
                                'id IN' => $values
                            ])
                            ->toArray();
                        foreach($valueEntities as $val) {
                            array_push($value_lists, [
                                //'option_id' => (int) $row->get('Options')['id'],
                                'option_value_id' => $val->get('id'),
                                'name' => $val->get('name')
                            ]);
                        }

                    }

                    $row->values = $value_lists;
                    unset($row->option_values);
                    return $row;
                })
                ->toArray();
        }




        $this->set(compact('data', 'error'));
    }

    public function priceRange()
    {
        $search = $this->request->getQuery('q');
        $category_id = $this->request->getQuery('category_id');

        $data = $this->Products->find();

        $data = $data
            ->select([
                'min_price' => $data->func()->min('price_sale'),
                'max_price' => $data->func()->max('price_sale')
            ])
            ->leftJoinWith('ProductToCategories')
            ->where([
                'Products.product_status_id' => 1
            ]);

        if ($search) {
            $data->where([
                'MATCH (Products.name, Products.highlight) AGAINST (:search IN BOOLEAN MODE)'
            ])
                ->bind(':search', $search, 'string');
        }

        if ($category_id) {
            $descendants = $this->ProductCategories->find('children', ['for' => $category_id])
                ->toArray();
            $children = Hash::extract($descendants, '{n}.id');
            if ($children) {
                $data->where([
                    'ProductToCategories.product_category_id IN' => $children
                ]);
            } else {
                $data->where([
                    'ProductToCategories.product_category_id' => $category_id
                ]);
            }

        }

        $data = $data->first();

        $this->set(compact('data'));
    }


    /**
     * @return Validator
     */
    protected function _validator()
    {
        $validator = new Validator();

        $validator->numeric('min_price')
            ->numeric('max_price')
            ->range('min_price', [0, 100000000], 'harga minimal 0 - 100.000.000')
            ->range('max_price', [1, 100000000], 'harga maxsimal 1 - 100.000.000')
            ->greaterThanOrEqualToField('max_price', 'min_price', 'harga maksimal harus lebih besar dari harga minimal');

        return $validator;
    }

    public function index()
    {
        $search = $this->request->getQuery('q');
        $category_id = $this->request->getQuery('category_id');
        $min_price = $this->request->getQuery('min_price');
        $max_price = $this->request->getQuery('max_price');


        $validator = $this->_validator();
        $error = $validator->errors($this->request->getQueryParams());

        if (!$error) {
            $data = $this->Products->find()
                ->select([
                    'id',
                    'name',
                    'slug',
                    'model',
                    'video_url',
                    'price',
                    'price_sale',
                    'highlight',
                    'profile',
                    'view',
                    'point',
                    'rating',
                    'rating_count',
                    'score' => "(MATCH(Products.name, Products.highlight) AGAINST(:search IN BOOLEAN MODE))",
                    'created'
                ])
                ->leftJoinWith('ProductToCategories')
                ->where([
                    'Products.product_status_id' => 1
                ]);

            if ($search) {
                $data->where([
                    'MATCH (Products.name, Products.highlight) AGAINST (:search IN BOOLEAN MODE)'
                ]);
            }

            if ($min_price && $max_price) {
                $data->where(function(QueryExpression $exp) use ($min_price, $max_price) {
                    return $exp->between('price_sale', $min_price, $max_price);
                });
            }

            if ($category_id) {
                $descendants = $this->ProductCategories->find('children', ['for' => $category_id])
                    ->toArray();
                $children = Hash::extract($descendants, '{n}.id');
                if ($children) {
                    $data->where([
                        'ProductToCategories.product_category_id IN' => $children
                    ]);
                } else {
                    $data->where([
                        'ProductToCategories.product_category_id' => $category_id
                    ]);
                }

            }


            $data = $data
                ->bind(':search', $search, 'string')
                ->contain([
                    'ProductImages' => [
                        'fields' => [
                            'name',
                            'product_id',
                            'idx',
                        ],
                        'sort' => ['ProductImages.primary' => 'DESC']
                    ],
                    'ProductTags' => [
                        'Tags'
                    ],
                    'ProductOptionPrices' => [
                        'fields' => [
                            'id',
                            'product_id',
                            'sku',
                            'expired',
                            'price'
                        ],
                        'ProductOptionValueLists' => [
                            'Options' => [
                                'fields' => ['id','name']
                            ],
                            'OptionValues' => [
                                'fields' => ['id','name']
                            ]
                        ],
                        'ProductOptionStocks' => [
                            'Branches' => [
                                'fields' => [
                                    'id', 'name'
                                ]
                            ]
                        ]
                    ]
                ])
                ->orderDesc('score');

            $data = $this->paginate($data, [
                'limit' => (int) $this->request->getQuery('limit', 5)
            ])->map(function(\App\Model\Entity\Product $row) {
                $images = [];
                foreach($row->get('product_images') as $vl){

                    $images[] = $vl['name'];
                }
                $row->images = $images;
                //unset($row->product_images);
                return $row;
            });
        }

        $this->set(compact('data', 'error'));
    }
}