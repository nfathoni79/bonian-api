<?php
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;
use Cake\Utility\Hash;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\ProductDealsTable $ProductDeals
 * @property \App\Model\Table\ProductDealDetailsTable $ProductDealDetails
 * @property \App\Model\Table\ProductOptionStocksTable $ProductOptionStocks
 * @property \App\Model\Table\OrderDetailProductsTable $OrderDetailProducts
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FlashSaleController extends Controller
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('ProductDeals');
        $this->loadModel('ProductDealDetails');
        $this->loadModel('ProductOptionStocks');
        $this->loadModel('OrderDetailProducts');

    }

    public function index(){
        $flashsale = $this->ProductDeals->find()
            ->select([
                'start' => 'ProductDeals.date_start',
                'end' => 'ProductDeals.date_end',
                'id'
            ])
            ->contain([
                'ProductDealDetails' => [
                    'fields' => [
                        'product_deal_id',
                        'product_id',
                        'price_sale',
                        'start_stock',
                    ],
                    'Products' => [
                        'fields' => [
                            'id',
                            'name',
                            'slug',
                            'point',
                            'price',
                            'price_sale',

                        ],
                        'ProductImages' => [
                            'fields' => [
                                'name',
                                'product_id',
                            ],
                            'sort' => ['ProductImages.primary' => 'DESC']
                        ],
                        'ProductToCategories' => [
                            'fields' => [
                                'product_id',
                                'product_category_id',
                            ],
                            'ProductCategories' => [
                                'fields' => [
                                    'name'
                                ]
                            ]
                        ],
                    ]
                ]
            ])
            ->where(['ProductDeals.status' => 1])
            ->map(function (\App\Model\Entity\ProductDeal $row) {
                foreach ($row['product_deal_details'] as $key => $vals){

                    /* replace harga */
                    $row->product_deal_details[$key]->product->price_sale = $row->product_deal_details[$key]->price_sale;

                    /* discount percent */
                    $percent = ( $row->product_deal_details[$key]->product->price - $row->product_deal_details[$key]->product->price_sale) / $row->product_deal_details[$key]->product->price * 100;
                    $row->product_deal_details[$key]->product->percent = round($percent);

                    /* SUM sisa Stock*/
                    $query = $this->ProductOptionStocks->find();
                    $sisa = $query
                        ->select(['sum' => $query->func()->sum('ProductOptionStocks.stock')])
                        ->where(['ProductOptionStocks.product_id' => $row->product_deal_details[$key]->product_id])->toArray();
                    $row->product_deal_details[$key]->product->stockavailable = $sisa[0]['sum'];
                    $row->product_deal_details[$key]->product->startstock = $row->product_deal_details[$key]->start_stock;

                    /* cari jumlah penjualan */
                    $queryOrder = $this->OrderDetailProducts->find();
                    $terjual = $queryOrder
                        ->contain([
                            'OrderDetails'
                        ])
                        ->select(['sum' => $queryOrder->func()->sum('OrderDetailProducts.qty')])
                        ->where([
                            'OrderDetails.order_status_id IN' => ['2','3','4'],
                            'OrderDetailProducts.product_id' => $row->product_deal_details[$key]->product_id,
                        ])
                        ->where(function(\Cake\Database\Expression\QueryExpression $exp) use($row){
                            return $exp->between('OrderDetailProducts.created', $row->start, $row->end, 'datetime');
                        })
                        ->toArray();
                    $row->product_deal_details[$key]->product->salestock = $terjual[0]['sum'] ? $terjual[0]['sum'] : 0;
                    $barangTerjual = $row->product_deal_details[$key]->product->startstock - $row->product_deal_details[$key]->product->salestock;

                    if($barangTerjual <= 10){
                        $row->product_deal_details[$key]->product->noted = 'Segera Habis';
                    }else{
                        $row->product_deal_details[$key]->product->noted = $row->product_deal_details[$key]->product->salestock.' Barang Terjual';
                    }

                    /* Images*/
                    $row->product_deal_details[$key]->product->images = Hash::extract($row->product_deal_details[$key]->product->product_images, '{n}.name');
                    $row->product_deal_details[$key]->product->categories = $row->product_deal_details[$key]->product->product_to_categories[0]->product_category->name;

                    unset($row->product_deal_details[$key]->product->product_to_categories);
                    unset($row->product_deal_details[$key]->product->product_images);
                    //unset($row->product_deal_details[$key]->product->id);
                    unset($row->product_deal_details[$key]->product_id);
                    unset($row->product_deal_details[$key]->product_deal_id);
                    unset($row->product_deal_details[$key]->price_sale);
                    unset($row->product_deal_details[$key]->start_stock);
                    unset($row->id);
                }

                //$row->set('start', $row->end->timestamp);
                //$row->set('end', $row->end->timestamp);
                return $row;
            })
            ->first();

        $this->set(compact('flashsale'));
    }

}