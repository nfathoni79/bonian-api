<?php
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;
use Cake\Utility\Hash;
use Cake\I18n\Time;
/**
 * Categories Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 * @property \App\Model\Table\ProductDealDetailsTable $ProductDealDetails
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductsController extends Controller
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Products');
        $this->loadModel('ProductDealDetails');
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
                'profile',
                'view',
                'point',
                'created'
            ])
            ->where([
                'Products.slug' => $slug,
                'Products.product_status_id' => 1
            ])
            ->contain([
                'ProductImages' => [
                    'fields' => [
                        'name',
                        'product_id',
                    ],
                    'sort' => ['ProductImages.primary' => 'DESC']
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
                            'fields' => ['name']
                        ],
                        'OptionValues' => [
                            'fields' => ['name']
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

                foreach($row->variant as $key => $val) {
                    $row->variant[$key]['price_id'] = $row->variant[$key]['id'];
                    $stocks = [];
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
                    }
                    unset($row->variant[$key]['product_option_stocks']);
                    $row->variant[$key]->stocks = $stocks;

                    $options = [];
                    foreach($val->product_option_value_lists as $i => $list) {
                        if (!isset($options[$list->option->name])) {
                            $options[$list->option->name] = [];
                        }

                        if (!in_array($list->option_value->name, $options[$list->option->name])) {
                            $options[$list->option->name][] = $list->option_value->name;
                        }
                    }


                    unset($row->variant[$key]['product_option_value_lists']);
                    unset($row->variant[$key]['product_id']);
                    unset($row->variant[$key]['id']);
                    $row->variant[$key]->options = $options;

                }

                $row->images = Hash::extract($row->get('product_images'), '{n}.name');

                unset($row->product_option_prices, $row->product_images);
                return $row;
            })
            ->first();

        /**
         * note price di timpa jika ada flash sale. ambil dari flash sale harga nya
         */
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

        if ($product) {
            $saveProduct = clone $product;
            $saveProduct->set('view', $saveProduct->get('view') + 1);
            $this->Products->save($saveProduct);
            //unset($product->modified);
        } else {
            //if product not found set response code to 404
            $this->setResponse($this->response->withStatus(404, 'Product not found'));
        }

        $this->set(compact('product'));

    }


    public function newArrivals()
    {

        $product = $this->Products->find()
            ->select([
                'id',
                'name',
                'slug',
                'price',
                'price_sale',
                'point',
                'created'
            ])
            ->where(function(\Cake\Database\Expression\QueryExpression $exp) {
                return $exp->gte('created', (Time::now())->addDays(-20)->format('Y-m-d H:i:s'));
            })
            ->where([
                'product_status_id' => 1
            ])
            ->contain([
                'ProductImages' => [
                    'fields' => [
                        'name',
                        'product_id',
                    ],
                    'sort' => ['ProductImages.primary' => 'DESC']
                ]
            ])
            ->map(function(\App\Model\Entity\Product $row) {
                $row->created = $row->created instanceof \Cake\I18n\FrozenTime  ? $row->created->timestamp : (Time::now())->timestamp;
                $row->images = Hash::extract($row->get('product_images'), '{n}.name');
                unset($row->product_images);
                return $row;
            });

        $this->set(compact('product'));
    }

}
