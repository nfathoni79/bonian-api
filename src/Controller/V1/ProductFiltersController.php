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
use Cake\I18n\Time;

/**
 * Class ProductFiltersController
 * @property \App\Model\Table\ProductsTable $Products
 * @property \App\Model\Table\ProductCategoriesTable $ProductCategories
 * @property \App\Model\Table\ProductOptionValueListsTable $ProductOptionValueLists
 * @package App\Controller\V1
 */

class ProductFiltersController extends Controller
{
    protected $is_new_rules = -30; //in days

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Products');
        $this->loadModel('ProductCategories');
        $this->loadModel('ProductOptionValueLists');
    }


    protected function getQuery($key, $default = null, $clean = true)
    {
        $text = $this->request->getQuery($key, $default);
        if ($clean && $text) {
            if (is_array($text)) {
                array_walk_recursive($text, function(&$val, $key) {
                    if (!is_array($val)) {
                        $val = filter_var($val, FILTER_SANITIZE_STRING);
                    }
                });
            } else {
                $text = filter_var($text, FILTER_SANITIZE_STRING);
            }
        }

        return $text;
    }


    protected function walk_recursive(&$object, $product_category_total, $selected, $expand = [])
    {
        foreach($object as $key => &$item) {
            if ($item instanceof \App\Model\Entity\ProductCategory) {

                if (count($selected) > 0 && !isset($selected[$item['id']])) {
                    unset($object[$key]);
                } else if (count($selected) == 0) {
                    unset($object[$key]);
                }

                $item['text'] = $item['name'];
                $item['total'] = 0;

                if (isset($selected[$item['id']])) {
                    $item['total'] = $selected[$item['id']];
                }

                $item['state'] = [
                    'checked' => count($expand) > 0 && $item['id'] == $expand[count($expand) - 1], //in_array($item['id'], $expand),
                    'expanded' => in_array($item['id'], $expand),
                    'selected' => count($expand) > 0 && $item['id'] == $expand[count($expand) - 1]
                ];
                $item['nodes'] = $item['children'];
                unset($item['path']);
                unset($item['name']);
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

        $keywords = $this->getQuery('q');
        $category_id = $this->getQuery('category_id');
        $min_price = $this->getQuery('min_price', '0');
        $max_price = $this->getQuery('max_price');
        $variants = $this->getQuery('variants');
        $brands = $this->getQuery('brands');


        $validator = $this->_validator();

        $error = $validator->errors($this->request->getQueryParams());

        if (!$error) {
            $categories = $this->ProductCategories->find('threaded');

            $categories = $categories
                ->select(['id','parent_id','name', 'slug','path'])
                ->map(function (\App\Model\Entity\ProductCategory $row) {
                    return $row;
                })
                ->toArray();


            $subquery = null;
            if (is_array($variants)) {
                $variants = array_values($variants);
                $subquery = $this->ProductOptionValueLists->find()
                    ->select([
                        'Product.id'
                    ])
                    ->where([
                        'ProductOptionValueLists.option_value_id IN' => $variants,
                        'Products.id = Product.id'
                    ])
                    ->leftJoin(['ProductOptionPrices' => 'product_option_prices'], [
                        'ProductOptionValueLists.product_option_price_id = ProductOptionPrices.id'
                    ])
                    ->leftJoin(['Product' => 'products'], [
                        'ProductOptionPrices.product_id = Product.id'
                    ])
                    ->group('Product.id');

            }

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
                        'MATCH (Products.name, Products.highlight_text) AGAINST (:search IN BOOLEAN MODE)'
                    ])
                    ->bind(':search', $keywords, 'string');
            }

            if ($min_price >= 0 && $max_price) {
                $hasProducts->where(function(QueryExpression $exp) use ($min_price, $max_price) {
                    return $exp->between('price_sale', $min_price, $max_price);
                });
            }

            if ($subquery instanceof \Cake\ORM\Query) {
                $hasProducts->where(function (QueryExpression $exp) use ($subquery) {
                    return $exp->exists($subquery);
                });
            }

            if ($brands && is_array($brands)) {
                $brands = array_values($brands);
                $hasProducts->where([
                    'Products.brand_id IN' => $brands
                ]);
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


            $this->walk_recursive($categories, $product_category_total, $selected, $expandable)
                ->reindex($categories);
        } else {
            $this->setResponse($this->response->withStatus(406, 'Request failed'));
        }




        $this->set(compact('categories'));
    }

    public function brand()
    {
        $search = $this->getQuery('q');
        $category_id = $this->getQuery('category_id');
        $min_price = $this->getQuery('min_price', '0');
        $max_price = $this->getQuery('max_price');
        $brands = $this->getQuery('brands');
        $source = $this->getQuery('source');

        $validator = $this->_validator();

        $error = $validator->errors($this->request->getQueryParams());

        if (!$error) {

            $data = $this->Products->find();

            $data = $data
                ->select([
                    'total' => $data->func()->count('Products.brand_id'),
                    'Brands.id',
                    'Brands.name',

                ])
                ->leftJoinWith('ProductToCategories')
                ->contain([
                    'Brands'
                ])
                ->where([
                    'Products.product_status_id' => 1,
                    'Products.brand_id >' => 0
                ]);

            switch($source) {
                case 'top-brand':
                    if (is_array($brands) && count($brands) > 0) {
                        $data->where([
                            'Brands.id' => $brands[0]
                        ]);
                    }

                    break;
            }

            if ($search) {
                $data->where([
                    'MATCH (Products.name, Products.highlight_text) AGAINST (:search IN BOOLEAN MODE)'
                ])
                    ->bind(':search', $search, 'string');
            }

            if ($min_price >= 0 && $max_price) {
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
                ->group('Products.brand_id')
                ->map(function(\App\Model\Entity\Product $row) {
                    $row->brand_id = $row->brand ? $row->brand->id : null;
                    $row->name = $row->brand ? $row->brand->name : null;

                    unset($row->brand);
                    return $row;
                })
                ->toArray();

            //debug($data);exit;
        } else {
            $this->setResponse($this->response->withStatus(406, 'Request failed'));
        }

        $this->set(compact('data', 'error'));
    }

    public function variant()
    {
        $search = $this->getQuery('q');
        $category_id = $this->getQuery('category_id');
        $brands = $this->getQuery('brands');
        $min_price = $this->getQuery('min_price', '0');
        $max_price = $this->getQuery('max_price');

        $validator = $this->_validator();

        $error = $validator->errors($this->request->getQueryParams());

        if (!$error) {

            $is_filter_products = false;

            $subquery = $this->Products->find()
                ->select([
                    'Products.id'
                ])
                ->where([
                    'product_status_id' => 1,
                    'Product.id = Products.id'
                ])
                ->group([
                    'Products.id'
                ]);

            if ($search) {
                $is_filter_products = true;
                $subquery->where([
                    'MATCH (Products.name, Products.highlight_text) AGAINST (:search IN BOOLEAN MODE)'
                ])
                    ->bind(':search', $search, 'string');
            }

            if ($min_price >= 0 && $max_price) {
                $is_filter_products = true;
                $subquery->where(function(QueryExpression $exp) use ($min_price, $max_price) {
                    return $exp->between('price_sale', $min_price, $max_price);
                });
            }

            if ($category_id) {
                $subquery->leftJoin(['ProductToCategories' => 'product_to_categories'], [
                    'Products.id = ProductToCategories.product_id'
                ]);
                $is_filter_products = true;
                $descendants = $this->ProductCategories->find('children', ['for' => $category_id])
                    ->toArray();
                $children = Hash::extract($descendants, '{n}.id');
                if ($children) {
                    $subquery->where([
                        'ProductToCategories.product_category_id IN' => $children
                    ]);
                } else {
                    $subquery->where([
                        'ProductToCategories.product_category_id' => $category_id
                    ]);
                }
            }

            if ($brands && is_array($brands)) {
                $is_filter_products = true;
                $brands = array_values($brands);
                $subquery->where([
                    'Products.brand_id IN' => $brands
                ]);
            }




            $data = $this->ProductOptionValueLists->find();

            $data = $data
                ->select([
                    //'option_values' => "GROUP_CONCAT(ProductOptionValueLists.option_value_id)",
                    'Options.id',
                    'Options.name',
                    'OptionValues.id',
                    'OptionValues.name',
                    'total' => $data->func()->count('ProductOptionPrices.product_id')
                ])
                ->leftJoin(['ProductOptionPrices' => 'product_option_prices'], [
                    'ProductOptionValueLists.product_option_price_id = ProductOptionPrices.id',
                ])
                ->leftJoin(['Product' => 'products'], [
                    'ProductOptionPrices.product_id = Product.id'
                ])
                ->leftJoin(['Options' => 'options'], [
                    'ProductOptionValueLists.option_id = Options.id'
                ])
                ->leftJoin(['OptionValues' => 'option_values'], [
                    'ProductOptionValueLists.option_value_id = OptionValues.id'
                ])
                ->group([
                    //'ProductOptionPrices.product_id',
                    //'ProductOptionValueLists.option_id',
                    'ProductOptionValueLists.option_value_id',

                ])
                ->orderAsc('Options.id')
                ->orderDesc('total');


            if ($subquery instanceof \Cake\ORM\Query) {
                $data->where(function (QueryExpression $exp) use ($subquery) {
                    return $exp->exists($subquery);
                });
            }

            $data = $data
                ->map(function(\App\Model\Entity\ProductOptionValueList $row) {
                    /*$values = array_unique(explode(',', $row->option_values));
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
                    unset($row->option_values);*/
                    if ($row->OptionValues) {
                        $row->OptionValues['option_value_id'] = $row->OptionValues['id'];
                        unset($row->OptionValues['id']);
                    }
                    return $row;
                })
                ->toArray();
            $collections = [];
            foreach($data as $key => $val) {
                if(!array_key_exists($val['Options']['id'], $collections)) {
                    $collections[$val['Options']['id']] = [
                        'Options' => $val['Options'],
                        'values' => [
                            array_merge($val['OptionValues'], ['total' => $val['total']])
                        ]
                    ];
                } else {
                    $collections[$val['Options']['id']]['values'][] = array_merge(
                        $val['OptionValues'],
                        ['total' => $val['total']]
                    );
                }
            }

            $data = array_values($collections);
            //debug($data);exit;
        } else {
            $this->setResponse($this->response->withStatus(406, 'Request failed'));
        }




        $this->set(compact('data', 'error'));
    }



    public function priceRange()
    {
        $search = $this->getQuery('q');
        $category_id = $this->getQuery('category_id');

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
                'MATCH (Products.name, Products.highlight_text) AGAINST (:search IN BOOLEAN MODE)'
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
            ->lessThanOrEqualToField('min_price', 'max_price', 'harga maksimal harus lebih besar dari harga minimal');

        return $validator;
    }

    public function index()
    {
        $search = $this->getQuery('q');
        $category_id = $this->getQuery('category_id');
        $min_price = $this->getQuery('min_price', '0');
        $max_price = $this->getQuery('max_price');
        $variants = $this->getQuery('variants');
        $brands = $this->getQuery('brands');
        $sortBy = $this->getQuery('sortBy');
        $order = $this->getQuery('order');




        $validator = $this->_validator();
        $error = $validator->errors($this->request->getQueryParams());

        if (!$error) {
            $subquery = null;
            if (is_array($variants)) {
                $variants = array_values($variants);
                $subquery = $this->ProductOptionValueLists->find()
                    ->select([
                        'Product.id'
                    ])
                    ->where([
                        'ProductOptionValueLists.option_value_id IN' => $variants,
                        'Products.id = Product.id'
                    ])
                    ->leftJoin(['ProductOptionPrices' => 'product_option_prices'], [
                        'ProductOptionValueLists.product_option_price_id = ProductOptionPrices.id'
                    ])
                    ->leftJoin(['Product' => 'products'], [
                        'ProductOptionPrices.product_id = Product.id'
                    ])
                    ->group('Product.id');

            }

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
                    //'score' => "MATCH(Products.name, Products.highlight_text) AGAINST(:searchField IN BOOLEAN MODE)",
                    'created'
                ])
                ->leftJoinWith('ProductToCategories')
                ->where([
                   'Products.product_status_id' => 1
                ]);


            if ($search) {
                $data->where([
                    'MATCH (Products.name, Products.highlight_text) AGAINST (:search IN BOOLEAN MODE)'
                ])
                ->bind(':search', $search, 'string');
            }

            $data
                ->contain([
                    'ProductImages' => [
                        'fields' => [
                            'name',
                            'product_id',
                            'idx',
                        ],
                        'sort' => ['ProductImages.primary' => 'DESC']
                    ],

                ]);

            if ($subquery instanceof \Cake\ORM\Query) {
                $data->where(function (QueryExpression $exp) use ($subquery) {
                    return $exp->exists($subquery);
                });
            }



            if ($min_price >= 0 && $max_price) {
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

            if ($brands && is_array($brands)) {
                $brands = array_values($brands);
                $data->where([
                    'Products.brand_id IN' => $brands
                ]);
            }



            $pagination_options = [
                'limit' => (int) $this->getQuery('limit', 5)
            ];

            if (!empty($sortBy) && !empty($order) && in_array($sortBy, ['price', 'rating']) && in_array($order, ['asc', 'desc'])) {
                $sort_keys = [
                    'price' => 'price_sale',
                    'rating' => 'rating',
                ];
                $pagination_options['order'] = [$sort_keys[$sortBy] => $order];
            } else {
                //$data->orderDesc('score');
            }




            $data = $this->paginate($data, $pagination_options)->map(function(\App\Model\Entity\Product $row) {
                $images = [];
                foreach($row->get('product_images') as $vl){
                    $images[] = $vl['name'];
                }
                $row->images = $images;
                $row->is_new = (Time::parse($row->created))->gte((Time::now())->addDay($this->is_new_rules));
                return $row;
            });
        } else {
            $this->setResponse($this->response->withStatus(406, 'Request failed'));
        }

        $this->set(compact('data', 'error'));
    }
}