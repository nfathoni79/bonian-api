<?php
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;
use Cake\Utility\Hash;
use Cake\I18n\Time;
use Cake\ORM\Query;
/**
 * Categories Controller
 *
 * @property \App\Model\Table\VouchersTable $Vouchers
 * @property \App\Model\Table\VoucherDetailsTable $VoucherDetails
 * @property \App\Model\Table\ProductDealDetailsTable $ProductDealDetails
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PromotionsController extends Controller
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Vouchers');
        $this->loadModel('VoucherDetails');
        $this->loadModel('ProductDealDetails');
        $this->loadModel('ProductToCategories');
    }

    public function index($slug = null)
    {
        if($this->request->getQuery('limit')){
            $limit = $this->request->getQuery('limit');
        }else{
            $limit = 10;
        }
        $find = $this->Vouchers->find()
            ->contain([
                'VoucherDetails' => [
                    'ProductCategories'  
                ]
            ])
            ->where(['Vouchers.slug' => $slug, 'Vouchers.status' => '1', 'Vouchers.type' => '2' , ])
            ->map(function (\App\Model\Entity\Voucher $row) use($limit) {
                foreach($row->voucher_details as $k => $vals){
                    unset($vals->id);
                    unset($vals->voucher_id);
                    unset($vals->product_category_id);
                    unset($vals->created); 
                    unset($vals->product_category->parent_id);
                    unset($vals->product_category->lft);
                    unset($vals->product_category->rght);
                    unset($vals->product_category->slug);
                    unset($vals->product_category->description);
                    unset($vals->product_category->path);
                    unset($vals->product_category->counter_view);
					
					
                    $query = $this->ProductToCategories->find()
						->contain([
							'Products' => [
                                'fields' => [
                                    'id',
                                    'name',
                                    'slug',
                                    'point',
                                    'price',
                                    'price_sale',

                                ],
                                'queryBuilder' => function (Query $q) {
                                    return $q->where(['name !=' => '']); // Full conditions for filtering
                                },
                                'ProductImages' => [
                                    'fields' => [
                                        'name',
                                        'product_id',
                                    ]
                                ],
							]
						])
						->where(['product_category_id' => $vals->product_category->id])->limit($limit)->toArray();
					$vals->product_category->products = 	$query; 
                    foreach($vals->product_category->products as $kk => $val){  
                        $val->product->images = Hash::extract( $val->product->product_images, '{n}.name');
                        unset($val->product->product_images);
                    }
                }
                return $row;
            })
            ->first();

//        if ($find) {
//            $product_deals = $this->ProductDealDetails->find()
//                ->where([
//                    'ProductDeals.status' => 1,
//                    'ProductDealDetails.product_id' => $product->get('id'),
//                ])
//                ->where(function(\Cake\Database\Expression\QueryExpression $exp) {
//                    $exp->lte('date_start', (Time::now())->format('Y-m-d H:i:s'));
//                    $exp->gte('date_end', (Time::now())->format('Y-m-d H:i:s'));
//                    return $exp;
//                })
//                ->contain([
//                    'ProductDeals'
//                ])
//                ->first();
//
//            if ($product_deals) {
//                $product->set('price_sale', $product_deals->get('price_sale'));
//                $product->set('is_flash_sale', true);
//            } else {
//                $product->set('is_flash_sale', false);
//            }
//        }

        $data = $find;

        $this->set(compact('data'));
       // print_r($find);
       // exit;
    }

}