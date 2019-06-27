<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller\V1\Web;

use Cake\I18n\Time;
use Cake\Validation\Validator;
use Cake\I18n\FrozenTime;
/**
 * Customers controller
 *
 * @property \App\Model\Table\VouchersTable $Vouchers
 * @property \App\Model\Table\CustomerBalancesTable $CustomerBalances
 * @property \App\Model\Table\CustomerVouchersTable $CustomerVouchers
 * @property \App\Model\Table\CustomerCartsTable CustomerCarts
 * @property \App\Model\Table\CustomerCartDetailsTable CustomerCartDetails
 * @property \App\Model\Table\ProductCategoriesTable ProductCategories
 * @property \App\Model\Table\CustomerAuthenticatesTable $CustomerAuthenticates
 * @property \App\Model\Table\CustomerMutationPointsTable $CustomerMutationPoints
 * @property \App\Model\Table\IpLocationsTable $IpLocations
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class VouchersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Vouchers');
        $this->loadModel('CustomerAuthenticates');
        $this->loadModel('CustomerMutationPoints');
        $this->loadModel('CustomerBalances');
        $this->loadModel('CustomerVouchers');
        $this->loadModel('CustomerCarts');
        $this->loadModel('CustomerCartDetails');
        $this->loadModel('ProductCategories');
    }

    public function index(){

        $timeJsonFormat = 'yyyy-MM-dd HH:mm';

        FrozenTime::setJsonEncodeFormat($timeJsonFormat);
        FrozenTime::setToStringFormat($timeJsonFormat);
        $voucher = $this->CustomerVouchers->find()
            ->contain([
                'Vouchers' => [
                    'VoucherDetails' => [
                        'ProductCategories'
                    ]
                ]
            ])
            ->where([
                'CustomerVouchers.customer_id' => $this->Authenticate->getId(),
                'CustomerVouchers.status' => $this->request->getQuery('type', '1'),
            ]);

        $voucher->orderDesc('CustomerVouchers.id');

        $data = $this->paginate($voucher, [
            'limit' => (int) $this->request->getQuery('limit', 5)
        ])->map(function (\App\Model\Entity\CustomerVoucher $row) {

            $category = [];
            $categoryName = [];
            // if($row->voucher->type == '2'){

                // $row->active = false;
                // $categoryIn = [];
                // foreach($row->voucher->voucher_details as $k => $v){
                    // $category[] = $v['product_category_id'];
                    // $categoryIn[] = $v['product_category_id'];

                    // $categoryPath = $this->ProductCategories->find('path',['fields' => ['id','name', 'slug'],'for' => $v['product_category_id']])->toArray();
                    // $categoryName[] = $categoryPath[0]['name'].' '.$v['product_category']['name'];
                // }


                // $query = $this->CustomerCarts->find()
                    // ->contain(['CustomerCartDetails'])
                    // ->where(['CustomerCarts.customer_id' => $this->Authenticate->getId(), 'CustomerCarts.status' => 1])
                    // ->first()
                    // ->toArray();
                // foreach($query['customer_cart_details'] as $vals){
                    // if(in_array($vals['status'], [1,5])){
                        // if(in_array($vals['product_category_id'],$categoryIn )){
                            // $row->active = true;
                            // break;
                        // }
                    // }
                // }
            // }else{
                $row->active = true;
            // }

            $row->category = $category;
            $row->category_name = $categoryName;
            unset($row->voucher_id);
            unset($row->voucher->id);
            unset($row->voucher->slug);
            unset($row->voucher->date_start);
            unset($row->voucher->date_end);
            unset($row->voucher->qty);
            unset($row->voucher->stock);
//            unset($row->voucher->type);
            unset($row->voucher->point);
            unset($row->voucher->status);
            return $row;
        });
        $this->set(compact('data'));

    }

}