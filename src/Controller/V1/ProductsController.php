<?php
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;
use Cake\Database\Expression\QueryExpression;
use Cake\Database\Query;
use Cake\Utility\Hash;
use Cake\I18n\Time;
use Cake\Utility\Text;
use Cake\Validation\Validator;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 * @property \App\Model\Table\ProductDealDetailsTable $ProductDealDetails
 * @property \App\Model\Table\OrderDetailProductsTable $OrderDetailProducts
 * @property \App\Model\Table\SearchTermsTable $SearchTerms
 * @property \App\Model\Table\SearchCategoriesTable $SearchCategories
 * @property \App\Model\Table\BrowsersTable $Browsers
 * @property \App\Model\Table\ProductCategoriesTable $ProductCategories
 * @property \App\Model\Table\ProductToCategoriesTable $ProductToCategories
 * @property \App\Model\Table\CustomerAuthenticatesTable $CustomerAuthenticates
 * @property \App\Model\Table\ShareStatisticsTable $ShareStatistics
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductsController extends Controller
{

    protected $is_new_rules = -30; //in days
    protected $wish_customer_id = null;

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Products');
        $this->loadModel('ProductDealDetails');
        $this->loadModel('OrderDetailProducts');
        $this->loadModel('SearchTerms');
        $this->loadModel('SearchStats');
        $this->loadModel('SearchCategories');
        $this->loadModel('Browsers');
        $this->loadModel('CustomerAuthenticates');
        $this->loadModel('ProductCategories');
        $this->loadModel('ProductToCategories');
        $this->loadModel('ShareStatistics');


        if ($customer_id = $this->request->getHeader('customer-id')) {
            if (count($customer_id) > 0) {
                $this->wish_customer_id = $customer_id[0];
            }
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index($slug = null)
    {
        $product = $this->Products->find()
            ->select([
                'id',
                'name',
                'slug',
                'model',
                'video_url',
                'price',
                'price_sale',
                'highlight',
                'highlight_text',
                'profile',
                'view',
                'sku',
                'point',
                'rating',
                'rating_count',
                'created'
            ])
            ->where([
                'Products.slug' => $slug,
                'Products.product_status_id' => 1,
                'Products.product_stock_status_id' => 1
            ])
            ->contain([
                'ProductToCategories',
                'ProductCoupons' => [
                    'queryBuilder' => function ($q) {
                        return $q
                            ->where(['status' => 1])
                            ->order(['ProductCoupons.id' => 'DESC'])
                            ->limit($this->request->getQuery('limit', 5));
                    }
                ],
                'ProductImages' => [
                    'fields' => [
                        'name',
                        'product_id',
                        'idx',
                    ],
                    'sort' => ['ProductImages.primary' => 'DESC','ProductImages.created' => 'ASC']
                ],
                'ProductTags' => [
                    'Tags'
                ],

                'ProductAttributes' => [
                    'Attributes',
                    'AttributeNames'
                ],
                'ProductOptionPrices' => [
                    'fields' => [
                        'id',
                        'product_id',
                        'sku',
                        'expired',
                        'price',
                        'idx'
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
            ->map(function (\App\Model\Entity\Product $row) {
                $row->set('created', $row->created->timestamp);
                $row->variant = $row->get('product_option_prices');

                $images = [];
                foreach($row->get('product_images') as $vl){
                    if($vl['idx'] == 0){
                        $images[] = $vl['name'];
                    }
                }
                $category = $this->ProductCategories->find('path',['fields' => ['id','name', 'slug'],'for' => $row->product_to_categories[0]->product_category_id])->toArray();
                if(!empty($row->get('product_coupons'))){
                    $row->use_coupon = true;
                    $row->coupon_price = $row->product_coupons[0]->price;
                }else{
                    $row->use_coupon = false;
                }
                /* discount percent */
                $percent = ( $row->price - $row->price_sale) / $row->price * 100;
                $row->percent = round($percent);
                $optionsVariant = [];
                $spesificVariantCounter = [];
                foreach($row->variant as $key => $val) {
                    $image = [];
                    foreach($row->get('product_images') as $vl){
                        if($vl['idx'] == $val['idx']){
                            $image[] = $vl['name'];
                        }
                    }


                    $row->variant[$key]['price_id'] = $row->variant[$key]['id'];
                    $stocks = [];
                    $stocksVariant = 0;
                    foreach($val->product_option_stocks as $i => $stock) {
                        $stocks[] = [
                            'stock_id' => $stock['id'],
                            'branch_id' => $stock['branch_id'],
                            'branch_name' => $stock['branch']['name'],
                            'stock' => $stock['stock'],
                            'weight' => $stock['weight'],
                            'width' => $stock['width'],
                            'length' => $stock['length'],
                            'height' => $stock['height'],
                        ];
                        $stocksVariant += $stock['stock'];
                    }
                    unset($row->variant[$key]['product_option_stocks']);
                    $row->variant[$key]->stocks = $stocks;

                    $options = [];
                    $optionSpesific = [];
                    foreach($val->product_option_value_lists as $i => $list) {
                        if (!isset($options[$list->option->name])) {
                            $options[$list->option->name] = [];
                        }

                        if (!in_array($list->option_value->name, $options[$list->option->name])) {
                            $options[$list->option->name] = $list->option_value->name;
                        }

                        if (!isset($optionsVariant[$list->option->name])) {
                            $optionsVariant[$list->option->name] = [];
                        }
                        if (!in_array($list->option_value->name, $optionsVariant[$list->option->name])) {
                            $optionsVariant[$list->option->name][] = $list->option_value->name;
                        }
                        $optionSpesific[$key][] = $list->option_value->name;
                    }

                    $spesificVariantCounter[$key] = [implode(',',$optionSpesific[$key]) => $stocksVariant];

                    unset($row->variant[$key]['product_option_value_lists']);
                    unset($row->variant[$key]['product_id']);
                    unset($row->variant[$key]['id']);
                    $row->variant[$key]->options = $options;
                    $row->variant[$key]->images = $image;
                }

                $row->options = $optionsVariant;
                $row->spesific = $spesificVariantCounter;
                foreach($row->product_tags as $key => &$val) {
                    $val->name = $val->tag ? $val->tag->name : null;
                    unset($val->id);
                    unset($val->product_id);
                    unset($val->tag);
                }

                $attributes = [];
                foreach($row->product_attributes as $key => $vals){
                    $attributes[$vals['attribute_name']['name']][] = $vals['attribute']['name'];
                }

                $list_attribute = [];

                foreach($attributes as $key => $vals){
                    $list_attribute[$key] = implode(',',$vals);
                }
                $row->attributes = $list_attribute;
                unset($row->product_attributes);
                
                $row->categories = $category;
                $row->tags = $row->product_tags;
                unset($row->product_coupons);
                unset($row->product_tags);
                unset($row->product_to_categories);

                $row->images = Hash::extract($row->get('product_images'), '{n}.name');
                $row->wishlist_id = $this->getWishList($row->id);
                $row->wishlist_count = $this->getWishCount($row->id);
//                $row->images = $images;

                unset($row->product_option_prices, $row->product_images);
                return $row;
            })
            ->first();



        if ($product && $this->request->getQuery('counter') != '0') {
            $saveProduct = clone $product;
            $saveProduct->set('view', $saveProduct->get('view') + 1);
            $this->Products->save($saveProduct);
            //unset($product->modified);
        } else {
            //if product not found set response code to 404
            $this->setResponse($this->response->withStatus(404, 'Product not found'));
        }

        $data = $product;

        /**
         * note price di timpa jika ada flash sale. ambil dari flash sale harga nya
         */
        if ($product) {
            $product_deals = $this->ProductDealDetails->find()
                ->where([
                    'ProductDeals.status' => 1,
                    'ProductDealDetails.product_id' => $product->get('id'),
                ])
                ->where(function(\Cake\Database\Expression\QueryExpression $exp) {
                    $exp->lte('date_start', (Time::now())->format('Y-m-d H:i:s'));
                    $exp->gte('date_end', (Time::now())->format('Y-m-d H:i:s'));
                    return $exp;
                })
                ->contain([
                    'ProductDeals'
                ])
                ->first();

            if ($product_deals) {
                $product->set('price_sale', $product_deals->get('price_sale'));
                $product->set('is_flash_sale', true);
            } else {
                $product->set('is_flash_sale', false);
            }
        }

        $this->set(compact('data'));

    }

    public function infoImage($product_id)
    {
        $data = $this->Products->find()
            ->select([
                'id',
                'name',
                'slug',
                'model',
                'created'
            ])
            ->where([
                'Products.id' => $product_id,
                'Products.product_status_id' => 1
            ])
            ->contain([
                'ProductImages' => [
                    'fields' => [
                        'name',
                        'product_id',
                    ],
                    'sort' => ['ProductImages.primary' => 'DESC','ProductImages.created' => 'ASC']
                ]
            ])
            ->first();

        $this->set(compact('data'));
    }


    public function newArrivals()
    {

        $data = $this->Products->find()
            ->select([
                'id',
                'name',
                'slug',
                'price',
                'price_sale',
                'point',
                'rating',
                'created'
            ])
            ->where(function(\Cake\Database\Expression\QueryExpression $exp) {
                return $exp->gte('created', (Time::now())->addDays($this->is_new_rules)->format('Y-m-d H:i:s'));
            })
            ->where([
                'Products.product_status_id' => 1,
                'Products.product_stock_status_id' => 1
            ])
            ->contain([
                'ProductImages' => [
                    'fields' => [
                        'name',
                        'product_id',
                    ],
                    'sort' => ['ProductImages.primary' => 'DESC','ProductImages.created' => 'ASC']
                ]
            ])
            ->orderDesc('Products.created')
            ->limit(15)
            ->map(function(\App\Model\Entity\Product $row) {
                $row->created = $row->created instanceof \Cake\I18n\FrozenTime  ? $row->created->timestamp : (Time::now())->timestamp;
                $row->is_new = (Time::parse($row->created))->gte((Time::now())->addDay($this->is_new_rules));
                $row->images = Hash::extract($row->get('product_images'), '{n}.name');
                $row->wishlist_id = $this->getWishList($row->id);
                unset($row->product_images);
                return $row;
            });

        $this->set(compact('data'));
    }

    protected function getWishList($product_id)
    {

        if ($this->wish_customer_id) {
            $wish = $this->Products->CustomerWishes->find()
                    ->select([
                        'CustomerWishes.id'
                    ])
                    ->where([
                        'product_id' => $product_id,
                        'customer_id' => $this->wish_customer_id
                    ])->first();
            if ($wish) {
                return $wish->id;
            }
        }

        return null;
    }

    public function getWishCount($product_id)
    {
        return $this->Products->CustomerWishes->find()
            ->where([
                'product_id' => $product_id
            ])->count();
    }

    public function popularProducts()
    {


        $data = $this->Products->find()
            ->select([
                'id',
                'name',
                'slug',
                'price',
                'price_sale',
                'point',
                'rating',
                'created'
            ])
            /*->where(function(\Cake\Database\Expression\QueryExpression $exp) {
                return $exp->gte('created', (Time::now())->addDays(-20)->format('Y-m-d H:i:s'));
            })*/
            ->where([
                'Products.product_status_id' => 1,
                'Products.product_stock_status_id' => 1
            ])
            ->contain([
                'ProductImages' => [
                    'fields' => [
                        'name',
                        'product_id',
                    ],
                    'sort' => ['ProductImages.primary' => 'DESC','ProductImages.created' => 'ASC']
                ]
            ])
            ->orderDesc('Products.view')
            ->limit(15)
            ->map(function(\App\Model\Entity\Product $row) {
                $row->created = $row->created instanceof \Cake\I18n\FrozenTime  ? $row->created->timestamp : (Time::now())->timestamp;
                $row->is_new = (Time::parse($row->created))->gte((Time::now())->addDay($this->is_new_rules));
                $row->images = Hash::extract($row->get('product_images'), '{n}.name');
                unset($row->product_images);
                $row->wishlist_id = $this->getWishList($row->id);
                return $row;
            });

        $this->set(compact('data'));
    }


    public function bestSellers()
    {
        $data = $this->OrderDetailProducts->find();

        $data = $data
            ->select([
                'total_count' => $data->func()->count('OrderDetailProducts.product_id')
            ])
            ->leftJoinWith('Products')
            ->leftJoinWith('OrderDetails')
            ->leftJoinWith('OrderDetails.Orders')
            ->where([
                'Orders.payment_status' => 2,
                'Products.product_status_id' => 1,
                'Products.product_stock_status_id' => 1
            ]);



        $data = $data
            ->contain([
                'Products' => [
                    'fields' => [
                        'Products.id',
                        'Products.name',
                        'Products.slug',
                        'Products.price',
                        'Products.price_sale',
                        'Products.point',
                        'Products.rating',
                        'Products.created',
                    ],
                    'ProductImages' => [
                        'fields' => [
                            'name',
                            'product_id',
                        ],
                        'sort' => ['ProductImages.primary' => 'DESC','ProductImages.created' => 'ASC']
                    ]
                ]
            ])
            ->enableAutoFields(true)
            ->group([
                'OrderDetailProducts.product_id'
            ])
            ->orderDesc('total_count')
            ->limit(15)
            ->map(function(\App\Model\Entity\OrderDetailProduct $row) {
                $row->created = $row->created instanceof \Cake\I18n\FrozenTime  ? $row->created->timestamp : (Time::now())->timestamp;
                $row->product->images = Hash::extract($row->product->get('product_images'), '{n}.name');
                unset($row->product->product_images);
                $row->product->is_new = (Time::parse($row->product->created))->gte((Time::now())->addDay($this->is_new_rules));
                $row->product->wishlist_id = $this->getWishList($row->product->id);

                $new_rows = clone $row;
                unset($row);
                return $new_rows->product;
            });
        $this->set(compact('data'));
    }

    public function releted($category_id = null)
    {
        $data = $this->ProductToCategories->find()
            ->contain([
                'Products' => [
                    'fields' => [
                        'Products.id',
                        'Products.name',
                        'Products.slug',
                        'Products.price',
                        'Products.price_sale',
                        'Products.point',
                        'Products.rating',
                        'Products.created'
                    ],
                    'ProductImages' => [
                        'fields' => [
                            'name',
                            'product_id',
                        ],
                        'sort' => ['ProductImages.primary' => 'DESC','ProductImages.created' => 'ASC']
                    ]
                ]
            ])
            ->where([
                'ProductToCategories.product_category_id' => $category_id,
                'Products.product_status_id' => 1,
                'Products.product_stock_status_id' => 1
            ]);

        if (($except_product_id = $this->request->getQuery('except_product_id')) && is_string($except_product_id)) {
            $data->where([
                'Products.id !=' => $except_product_id
            ]);
        }

        $data = $data
            ->limit(10)
            ->map(function(\App\Model\Entity\ProductToCategory $row) {
                $row->product->images = Hash::extract($row->product->get('product_images'), '{n}.name');
                unset($row->product->product_images);

                $percent = ( $row->product->price - $row->product->price_sale) / $row->product->price * 100;
                $row->product->percent = round($percent);

                $row->product->is_new = (Time::parse($row->product->created))->gte((Time::now())->addDay($this->is_new_rules));
                return $row;
            });
        $this->set(compact('data'));
    }


    public function share()
    {
        $this->request->allowMethod('post');

        $validator = new Validator();
        $validator->requirePresence('product_id')
            ->notBlank('product_id')
            ->requirePresence('media_type')
            ->notBlank('media_type')
            ->requirePresence('reffcode')
            ->notBlank('reffcode');

        $error = $validator->errors($this->request->getData());

        if (empty($error)) {
            $customerEntity = $this->CustomerAuthenticates->Customers->find()
                ->select([
                    'Customers.id'
                ])
                ->where([
                    'reffcode' => $this->request->getData('reffcode')
                ])
                ->first();

            $shareStatisticEntity = $this->ShareStatistics->newEntity([
                'product_id' => $this->request->getData('product_id'),
                'media_type' => $this->request->getData('media_type'),
                'customer_id' => $customerEntity->id,
                'clicked' => $this->request->getData('clicked') ? true : false
            ]);

            if ($this->ShareStatistics->save($shareStatisticEntity)) {
                $data = $this->Tools->encrypt(json_encode([
                    'customer_id' => $customerEntity->id,
                    'product_id' => $shareStatisticEntity->product_id
                ]));
            }

        } else {
            $this->setResponse($this->response->withStatus(406));
        }

        $this->set(compact('data', 'error'));
    }


    protected function highlight($text, $words)
    {
        preg_match_all('~\w+~', $words, $m);
        if(!$m)
            return $text;
        $re = '~\\b(' . implode('|', $m[0]) . ')\\b~i';
        return preg_replace($re, '<span class="search-highlight">$0</span>', $text);
    }

    public function searchHistory()
    {
        $bid = $this->request->getHeader('bid');
        if(count($bid) > 0) {
            $bid = $bid[0];
        } else {
            $bid = null;
        }

        if ($bid) {
            $data = $this->SearchTerms->SearchCategories->find()
                ->contain([
                    'SearchTerms',
                    'Browsers',
                    'ProductCategories'
                ])
                ->where([
                    'Browsers.bid' => $bid
                ])
                ->group('search_term_id')
                ->orderDesc('SearchCategories.created')
                ->limit(5)
                ->map(function (\App\Model\Entity\SearchCategory $row) {
                    unset($row->browser, $row->browser_id);
                    return $row;
                });

            $this->set(compact('data'));
        }
    }

    public function productLists()
    {
        
    }


    public function deleteHistory()
    {
        $this->request->allowMethod('post');
        if ($term_id = $this->request->getData('term_id')) {
            $bid = $this->request->getHeader('bid');
            if(count($bid) > 0) {
                $bid = $bid[0];
            } else {
                $bid = null;
            }

            $browserEntity = $this->Browsers->find()
                ->where([
                    'bid' => $bid
                ])
                ->first();

            if ($browserEntity) {
                $browser_id = $browserEntity->get('id');
                $entity = $this->SearchCategories->find()
                    ->where([
                        'search_term_id' => $term_id,
                        'browser_id' => $browser_id
                    ])
                    ->first();
                if ($entity) {
                    $this->SearchCategories->delete($entity);
                }

                $entity = $this->SearchTerms->SearchStats->find()
                    ->where([
                        'search_term_id' => $term_id,
                        'browser_id' => $browser_id
                    ])
                    ->first();
                if ($entity) {
                    $this->SearchTerms->SearchStats->delete($entity);
                }
            }

        }
    }

    public function saveSearch()
    {

        $this->request->allowMethod('post');

        if ($keyword = $this->request->getData('keyword')) {
            $keyword = filter_var($keyword, FILTER_SANITIZE_STRING);
            $category_id = $this->request->getData('category_id', null);
            $productRelated = $this->searchByKeyword($keyword, 5);
            $bid = $this->request->getHeader('bid');

            $browser_id = null;
            $customer_id = null;

            if(count($bid) > 0) {
                $bid = $bid[0];
            } else {
                $bid = null;
            }
			

            $browserEntity = $this->Browsers->find()
                ->where([
                    'bid' => $bid
                ])
                ->first();

            if ($browserEntity) {
                $browser_id = $browserEntity->get('id');
            }
			

            $searchTermEntity = $this->SearchTerms->find()
                ->where(function(\Cake\Database\Expression\QueryExpression $exp) use($keyword) {
                    return $exp->like('words', '%' . $keyword . '%');
                })
                ->orderDesc('hits')
                ->limit(1);

            if($searchTermEntity->isEmpty()) {
                $searchTermEntity = $this->SearchTerms->newEntity([
                    'words' => $keyword,
                    'hits' => 1,
                    'match' => $productRelated ? true : false
                ]);

                if ($this->SearchTerms->save($searchTermEntity)) {
                    $searchStatEntity = $this->SearchTerms->SearchStats->newEntity([
                        'search_term_id' => $searchTermEntity->get('id'),
                        'browser_id' => $browser_id,
                        'total' => $this->searchByKeywordCount($keyword, $category_id),
                        'customer_id' => $customer_id
                    ]);

                    $this->SearchTerms->SearchStats->save($searchStatEntity);

                    if ($productRelated) {
                        foreach($productRelated as $related) {
                            if (strtolower($keyword) == $related['fill_text']) {
                                $searchCategoryEntity = $this->SearchCategories->newEntity([
                                    'search_term_id' => $searchTermEntity->get('id'),
                                    'product_category_id' => $category_id ? $category_id : $related->product_category->id,
                                    'browser_id' => $browser_id
                                ]);
                                $this->SearchCategories->save($searchCategoryEntity);
                                break;
                            }
                        }
                    }
                }
            } else {
                /**
                 * @var \App\Model\Entity\SearchTerm[] $searchTermEntity
                 */
                foreach($searchTermEntity as $term) {
                    $term->set('hits', $term->get('hits') + 1);
                    if ($productRelated) {
                        $term->set('match', 1);
                    }
                    if ($this->SearchTerms->save($term)) {
                        $searchStatEntity = $this->SearchTerms->SearchStats->newEntity([
                            'search_term_id' => $term->get('id'),
                            'browser_id' => $browser_id,
                            'total' => $this->searchByKeywordCount($keyword, $category_id),
                            'customer_id' => $customer_id
                        ]);

                        $this->SearchTerms->SearchStats->save($searchStatEntity);

                        if ($productRelated) {
                            foreach($productRelated as $related) {
                                if (strtolower($keyword) == $related['fill_text']) {
                                    $searchCategoryEntity = $this->SearchCategories->newEntity([
                                        'search_term_id' => $term->get('id'),
                                        'product_category_id' => $category_id,
                                        'browser_id' => $browser_id
                                    ]);
                                    $this->SearchCategories->save($searchCategoryEntity);
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }


    }

    protected function searchByKeywordCount($keywords, $category_id = null)
    {
        $total = $this->Products->find();

        $total = $total
            ->select([
                'count' => $total->func()->count('*'),
            ])
            ->where([
                'MATCH (Products.name, Products.highlight_text) AGAINST (:search IN BOOLEAN MODE)',
                'Products.product_status_id' => 1,
                'Products.product_stock_status_id' => 1
            ])
            ->bind(':search', $keywords, 'string');

        if ($category_id) {
            $total
                ->leftJoin(['ProductToCategories' => 'product_to_categories'], [
                'ProductToCategories.product_id = Products.id'
            ])
                ->where([
                    'ProductToCategories.product_category_id' => $category_id
                ]);
        }


        $total = $total->first();

        if ($total) {
            return $total->get('count');
        }
        return 0;
    }


    protected function searchByKeyword($keywords, $limit = 5)
    {
        /* pencarian kata kunci*/
        $kataKunci = $this->Products->ProductToCategories->find();

        $kataKunci = $kataKunci
            ->select([
                'cnt' => "GROUP_CONCAT(product_id)",
                'score' => "(MATCH(Products.name, Products.highlight_text) AGAINST(:search IN BOOLEAN MODE))"
            ])
            ->contain([
                'Products' => [
                    'fields' => [
                        'id',
                        'name',
                        'slug'
                    ]
                ],
                'ProductCategories'
            ])
            ->where([
                'MATCH (Products.name, Products.highlight_text) AGAINST (:search IN BOOLEAN MODE)',
                'Products.product_status_id' => 1,
                'Products.product_stock_status_id' => 1
            ])
            ->enableAutoFields(true)
            ->group('product_category_id')
            ->bind(':search', $keywords, 'string')
            ->orderDesc('score')
            ->limit($limit)
            ->map(function(\App\Model\Entity\ProductToCategory $row) use ($keywords) {


                $row->id = $row->product->id;
                $row->name = $row->product->name;
                $row->slug = $row->product->slug;

                //get related keyword by categories
                $searchTerm = $this->SearchCategories->find();
                $searchTerm = $searchTerm
                    ->select([
                        'SearchTerms.words',
                        'total' => $searchTerm->func()->count('search_term_id')
                    ])
                    ->contain([
                        'SearchTerms'
                    ])
                    ->where([
                        'product_category_id' => $row->product_category->id,
                        'MATCH (words) AGAINST (:search)'
                    ])
                    ->bind(':search', $keywords, 'string')
                    ->group('search_term_id')
                    ->orderDesc('total')
                    ->first();

                if ($searchTerm) {
                    $primary = $searchTerm->get('search_term')->get('words');
                }

                $row->primary = isset($primary) ? $primary : $keywords;
                $row->secondary = $row->name;
                //$row->image = false;
                $row->onclick = false;
                $row->fill_text = strtolower($keywords);

                unset($row->id,
                    $row->name,
                    $row->slug,
                    $row->product,
                    $row->product_category->parent_id,
                    $row->product_category->lft,
                    $row->product_category->rght
                );
                return $row;
            })

            ->toArray();
        return $kataKunci;
    }

    public function search()
    {

        $keywords = trim($this->request->getQuery('keywords'));
        $bid = $this->request->getHeader('bid');
        $limit = $this->request->getQuery('limit', 5);
        $limit = $limit > 100 ? 100 : $limit;

        $browser_id = null;
        $customer_id = null;

        if(count($bid) > 0) {
            $bid = $bid[0];
        } else {
            $bid = null;
        }


        $browserEntity = $this->Browsers->find()
            ->where([
                'bid' => $bid
            ])
            ->first();

        if ($browserEntity) {
            $browser_id = $browserEntity->get('id');
            $authTable = $this->CustomerAuthenticates->find()
                ->where([
                    'browser_id' => $browser_id
                ])
                ->where(function(\Cake\Database\Expression\QueryExpression $exp) {
                    return $exp->gte('expired', (Time::now())->format('Y-m-d H:i:s'));
                })
                ->orderDesc('id')
                ->first();
            if ($authTable) {
                $customer_id = $authTable->get('customer_id');
            }
        }


        /* PENCARIAN POPULER */
        $pencarianPopuler = $this->SearchTerms->find()
            ->where(function (QueryExpression $exp, Query $q) use($keywords) {
                return $exp->like('SearchTerms.words', '%' . $keywords . '%');
            })
            ->where([
                'SearchTerms.match' => 1,
            ])
            ->orderDesc('SearchTerms.hits')
            ->limit('4');



        $kataKunci = $this->searchByKeyword($keywords, $limit);



        $pencarianPopuler = $this->SearchTerms->find()
            ->where([
                'SearchTerms.match' => 1,
                'MATCH (words) AGAINST (:search)'
            ])
            ->bind(':search', $keywords, 'string')
            ->orderDesc('SearchTerms.hits')
            ->limit('4')
            ->map(function (\App\Model\Entity\SearchTerm $row) use($keywords) {
                $row->primary = $row->words;
                $row->onclick = ''; // custom url
                $row->fill_text = strtolower($keywords);
                unset($row->id);
                unset($row->hits);
                unset($row->match);
                unset($row->words);
                return $row;
            })
            ->toArray();


        $populerProduct = $this->Products->find()
            ->select([
                'Products.id',
                'Products.name',
                'Products.slug',
                'Products.price',
                'Products.price_sale',
            ])
            ->where([
                'Products.product_status_id' => 1,
                'Products.product_stock_status_id' => 1,
                'MATCH (Products.name, Products.highlight_text) AGAINST (:search)'
            ])
            ->contain([
                'ProductImages' => [
                    'fields' => [
                        'name',
                        'product_id',
                    ],
                    'sort' => ['ProductImages.primary' => 'DESC','ProductImages.created' => 'ASC']
                ]
            ])
            ->bind(':search', $keywords, 'string')
            ->orderDesc('Products.view')
            ->limit($limit)
            ->map(function (\App\Model\Entity\Product $row) use($keywords) {
                $row->primary = $row->name;
                $row->secondary = $row->name;
                $row->onclick = false; // custom url
                $row->image = false;
                $row->fill_text = strtolower($keywords);

                $product_deals = $this->ProductDealDetails->find()
                    ->where([
                        'ProductDeals.status' => 1,
                        'ProductDealDetails.product_id' => $row->id
                    ])
                    ->where(function(\Cake\Database\Expression\QueryExpression $exp) {
                        $exp->lte('date_start', (Time::now())->format('Y-m-d H:i:s'));
                        $exp->gte('date_end', (Time::now())->format('Y-m-d H:i:s'));
                        return $exp;
                    })
                    ->contain([
                        'ProductDeals'
                    ])
                    ->first();

                if ($product_deals) {
                    $row->is_flash_sale = true;
                    $row->price_sale = $product_deals->get('price_sale');
                } else {
                    $row->is_flash_sale = false;
                }

                $row->price_sale_format = \Cake\I18n\Number::format($row->price_sale);



                return $row;
            })
            ->toArray();


        $data = [
            [
                'header' => [
                    'title' => 'Pencarian Populer',
                    'slug' => 'pencarian-populer',
                    'image' => false,
                    'limit' => 4,
                ],
                'data' => $pencarianPopuler
            ],
            [
                'header' => [
                    'title' => 'Kata Kunci',
                    'slug' => 'kata-kunci',
                    'image' => false,
                    'limit' => 4
                ],
                'data' => $kataKunci
            ],
            [
                'header' => [
                    'title' => 'Produk Terpopuler',
                    'slug' => 'produk-terpopuler',
                    'limit' => 4
                ],
                'data' => $populerProduct
            ],
        ];

        $this->set(compact('data'));
    }

}
