<?php
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;
use Cake\Database\Expression\QueryExpression;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\BrandsTable $Brands
 * @property \App\Model\Table\ProductsTable $Products
 * @property \App\Model\Table\OrderDetailProductsTable $OrderDetailProducts
 * @method \App\Model\Entity\Brand[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BrandsController extends Controller
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Brands');
        $this->loadModel('Products');
        $this->loadModel('OrderDetailProducts');
    }

    /**
     * Top Brand index
     */
    public function index()
    {

        $data = $this->OrderDetailProducts->find();

        $data = $data
            ->select([
                'total_count' => $data->func()->count('OrderDetailProducts.product_id'),
            ])
            ->leftJoinWith('Products')
            ->leftJoinWith('Products.Brands')
            ->leftJoinWith('OrderDetails')
            ->leftJoinWith('OrderDetails.Orders')
            ->where([
                'Orders.payment_status' => 2,
                'Products.product_status_id' => 1,
            ])
            ->where(function (QueryExpression $exp) {
                return $exp->isNotNull('Brands.id');
            })
            ->contain([
                'Products' => [
                    'Brands' => [
                        'fields' => [
                            'Brands.id',
                            'Brands.name',
                        ]
                    ]
                ]
            ])
            ->enableAutoFields(true)
            ->group([
                'OrderDetailProducts.product_id'
            ])
            ->orderDesc('total_count')
            ->limit(10)
            ->map(function(\App\Model\Entity\OrderDetailProduct $row) {

                if ($row->product) {
                    $brand = $row->product->brand;
                    $brand->total = $row->total_count;
                    return $brand;
                }

            });

        $this->set(compact('data'));

    }



}
