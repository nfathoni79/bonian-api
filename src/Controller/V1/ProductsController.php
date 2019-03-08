<?php
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;
use Cake\Utility\Hash;
/**
 * Categories Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductsController extends Controller
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Products');
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
            ->where(['Products.slug' => $slug])
            ->contain([
                'ProductImages' => [
                    'fields' => [
                        'name',
                        'product_id',
                    ]
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
                    $stocks = [];
                    foreach($val->product_option_stocks as $i => $stock) {
                        $stocks[] = [
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

        if ($product) {
            $product->set('view', $product->get('view') + 1);
            $this->Products->save($product);
            unset($product->modified);
        } else {
            //if product not found set response code to 404
            $this->setResponse($this->response->withStatus(404, 'Product not found'));
        }

        $this->set(compact('product'));

    }

}
