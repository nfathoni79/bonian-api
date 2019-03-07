<?php
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;

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
                    'ProductOptionStocks'
                ]
            ])
            ->map(function (\App\Model\Entity\Product $row) {
                $row->set('created', $row->created->timestamp);
                $row->variant = $row->get('product_option_prices');
                unset($row->product_option_prices);
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
