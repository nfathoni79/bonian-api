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
use Cake\Utility\Hash;
/**
 * Customers controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\CustomerCartsTable $CustomerCarts
 * @property \App\Model\Table\CustomerCartDetailsTable $CustomerCartDetails
 * @property \App\Model\Table\ProductOptionPricesTable $ProductOptionPrices
 * @property \App\Model\Table\ProductDealDetailsTable $ProductDealDetails
 * @property \App\Model\Table\ProductGroupDetailsTable $ProductGroupDetails
 * @property \App\Model\Table\CustomerWishesTable $CustomerWishes
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class CartController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->loadModel('CustomerCarts');
        $this->loadModel('CustomerCartDetails');
        $this->loadModel('Products');
        $this->loadModel('ProductOptionPrices');
        $this->loadModel('ProductDealDetails');
        $this->loadModel('ProductGroupDetails');
        $this->loadModel('CustomerWishes');
    }

    public function add(){
        $this->request->allowMethod(['post', 'put']);
        $customerId = $this->Authenticate->getId();

        if($this->request->getData('qty') <= 0){
            $this->setResponse($this->response->withStatus(406, 'Silahkan lengkapi quantity'));
        }else{


            $customerCart = $this->CustomerCarts->find()
                ->where(['CustomerCarts.customer_id' => $customerId,'CustomerCarts.status' => 1])
                ->first();
            if($customerCart){
                $cartId = $customerCart->get('id');
            }else{
                /* Create New Cart */
                $newEntity = $this->CustomerCarts->newEntity();
                $data = ['customer_id' => $customerId, 'status' => 1];
                $this->CustomerCarts->patchEntity($newEntity, $data);
                if($this->CustomerCarts->save($newEntity)){
                    $cartId = $newEntity->get('id');
                }
            }

            /* Return Cart ID*/
            if($cartId){

                /* CHECK QUERY */
                $findPriceAndStock = $this->ProductOptionPrices->ProductOptionStocks->find()
                    ->where([
                        'ProductOptionStocks.product_option_price_id' => $this->request->getData('price_id'),
                        'ProductOptionStocks.id' => $this->request->getData('stock_id')
                    ])
                    ->first();
                $newEntityDetails = $this->CustomerCartDetails->newEntity();
                if($findPriceAndStock){

                    /* Cek dalam table cart detail kalau sudah ada ubah tipe menjadi penambahan */

                    $findCart = $this->CustomerCartDetails->find()
                        ->contain(['CustomerCarts'])
                        ->where([
                            'CustomerCarts.customer_id' => $customerId,
                            'CustomerCartDetails.product_id' => $this->request->getData('product_id'),
                            'CustomerCartDetails.product_option_price_id' => $this->request->getData('price_id'),
                            'CustomerCartDetails.product_option_stock_id' => $this->request->getData('stock_id'),
                            'CustomerCartDetails.status' => 1,
                        ])
                        ->first();
                    if($findCart){
                        $update = $this->updateCart(
                            $customerId,
                            $this->request->getData('qty'),
                            $this->request->getData('product_id'),
                            $this->request->getData('price_id'),
                            $this->request->getData('stock_id'),
                            $this->request->getData('comment'),
                            $this->request->getData('type')

                        );

                        if(!$update){
                            $this->setResponse($this->response->withStatus(406, 'Maaf, Stok tidak cukup.'));
                            $errors = $newEntityDetails->getErrors();
                        }
                    }else{

                        $setEntity = [
                            'customer_cart_id' => $cartId,
                            'qty' => $this->request->getData('qty'),
                            'product_id' => $this->request->getData('product_id'),
                            'product_option_price_id' => $this->request->getData('price_id'),
                            'product_option_stock_id' => $this->request->getData('stock_id'),
                        ];
                        $this->CustomerCartDetails->patchEntity($newEntityDetails, $setEntity);


                        $getAddPrice = $this->ProductOptionPrices->find()
                            ->contain(['Products'])
                            ->where(['ProductOptionPrices.id' => $this->request->getData('price_id')])
                            ->first();
                        $newEntityDetails->set('add_price', $getAddPrice->get('price'));
                        $checkFlashSale = $this->ProductDealDetails->checkStatusProduct($this->request->getData('product_id'));
                        $checkGroupSale = $this->ProductGroupDetails->checkStatusProduct($this->request->getData('product_id'));

                        if($checkFlashSale){
                            $newEntityDetails->set('price',$this->ProductDealDetails->getPrices($this->request->getData('product_id')));
                            $newEntityDetails->set('in_flashsale', true);
                            $newEntityDetails->set('in_groupsale', false);
                        }else if($checkGroupSale){
                            $newEntityDetails->set('price',$this->ProductGroupDetails->getPrices($this->request->getData('product_id')));
                            $newEntityDetails->set('in_flashsale', false);
                            $newEntityDetails->set('in_groupsale', true);
                        }else{
                            $newEntityDetails->set('price',$getAddPrice['product']['price_sale']);
                            $newEntityDetails->set('in_flashsale', false);
                            $newEntityDetails->set('in_groupsale', false);
                        }

                        $newEntityDetails->set('point', $getAddPrice['product']['point']);
                        $newEntityDetails->set('total', (($newEntityDetails['price'] + $newEntityDetails['add_price']) * $newEntityDetails['qty']));
                        $newEntityDetails->set('totalpoint', ($getAddPrice['product']['point'] * $newEntityDetails['qty']));
                        $newEntityDetails->set('status', 1);



                        if ($this->CustomerCartDetails->save($newEntityDetails)) {

                           $data = $newEntityDetails->get('id');
                            $this->set(compact('data'));
                        } else {
                            $this->setResponse($this->response->withStatus(406, 'Stok tidak cukup'));
                            $errors = $newEntityDetails->getErrors();
                        }
                    }

                }else{
                    $this->setResponse($this->response->withStatus(406, 'Silahkan lengkapi pilihan'));
                    $errors = $newEntityDetails->getErrors();
                }


            }
        }
        $this->set(compact('errors'));

    }


    private function updateCart($customerId, $qty, $productId, $priceId, $stockId, $comment, $type = null ){
        $findCart = $this->CustomerCartDetails->find()
            ->contain(['CustomerCarts'])
            ->where([
                'CustomerCarts.customer_id' => $customerId,
                'CustomerCartDetails.product_id' => $productId,
                'CustomerCartDetails.product_option_price_id' => $priceId,
                'CustomerCartDetails.product_option_stock_id' => $stockId,
                'CustomerCartDetails.status' => 1,
            ])
            ->first();
        if($findCart){
            if($type == 'force'){
                $newQty = $qty;
            }else{
                $oldQty = $findCart->get('qty');
                $newQty = $qty + $oldQty;
            }
            
            $id = $findCart->get('id');

            $cartDetails = $this->CustomerCartDetails->get($id, [
                'contain' => []
            ]);

            $setEntity = [
                'qty' => $newQty,
                'total' => (($cartDetails->get('price') + $cartDetails->get('add_price')) * $newQty ),
                'totalpoint' => ($cartDetails->get('point')  * $newQty ),
                'product_option_price_id' => $cartDetails->get('product_option_price_id'),
                'product_option_stock_id' => $cartDetails->get('product_option_stock_id'),
                'comment' => $comment,
            ];

            $this->CustomerCartDetails->patchEntity($cartDetails,$setEntity);
            if($this->CustomerCartDetails->save($cartDetails)){
                return true;
            }else{
                return false;
            }
        }


    }


    public function view(){
        $this->request->allowMethod(['get']);
        $customerId = $this->Authenticate->getId();

        $cart = $this->CustomerCarts->find()
            ->contain(
                'CustomerCartDetails', function (\Cake\ORM\Query $q) {
                return $q
                    ->where(['CustomerCartDetails.status IN ' => [1, 2, 3]]);
                }
            )
            ->where(['CustomerCarts.customer_id' => $customerId,'CustomerCarts.status' => 1 ])
            ->first();

        if($cart){
            $cartList = $this->CustomerCartDetails->find()
                ->contain([
                    'Products' => [
                        'fields' => [
                            'id',
                            'name',
                            'slug',
                            'price',
                        ],
                        'ProductImages' => [
                            'fields' => [
                                'name',
                                'product_id',
                            ]
                        ],
                    ],
                    'ProductOptionPrices' => [
                        'ProductOptionValueLists' => [
                            'Options',
                            'OptionValues'
                        ],
                    ],
                    'ProductOptionStocks' => [
                        'Branches'
                    ]
                ])
                ->where(['CustomerCartDetails.customer_cart_id' => $cart->get('id'),'CustomerCartDetails.status IN ' => [1, 2, 3]]);
            $cartList->orderDesc('CustomerCartDetails.id');
            $data = $this->paginate($cartList, [
                'limit' => (int) $this->request->getQuery('limit',100)
            ])->map(function (\App\Model\Entity\CustomerCartDetail $row) {
                $status = [
                    1 => 'available',
                    2 => 'expired',
                    3 => 'outoff stock',
                    4 => 'deleted',
                    5 => 'move to whislist'
                ];

                $row->cartid = $row->id;
                $row->status_text = $status[$row->status];
                $row->name = $row->product->name;
                $row->slug = $row->product->slug;
                $row->regular_price = $row->product->price;
                $row->price = $row->price;



                $row->sku = $row->product_option_price->sku;
                $row->origin = $row->product_option_stock->branch->name;

                $variant = [];
                foreach($row->product_option_price['product_option_value_lists'] as $val){
                    $variant[] = $val['option']['name'] .' : '. $val['option_value']['name'];
                }

                $row->variant = implode(', ', $variant);
                $row->price_id = $row->product_option_price_id;
                $row->stock_id = $row->product_option_stock_id;
                $row->images = Hash::extract($row->product->product_images, '{n}.name');



                unset($row->created);
                unset($row->modified);
                unset($row->id);
                unset($row->customer_id);
                unset($row->created);
                unset($row->modified);
                return $row;
            });
            $this->set(compact('data'));
        }

    }

    public function delete(){
        $this->request->allowMethod(['post', 'put']);

        $customerId = $this->Authenticate->getId();
        $find =  $this->CustomerCartDetails->find()
            ->contain(['CustomerCarts'])
            ->where([
                'CustomerCarts.customer_id' => $customerId,
                'CustomerCartDetails.id' => $this->request->getData('cartid'),
//                'CustomerCartDetails.status IN ' => 1
            ])
            ->first();
        if($find){
            $entity = $this->CustomerCartDetails->get($this->request->getData('cartid'));
            $entity->set('status', 4);
            if ($this->CustomerCartDetails->save($entity)) {
                //success

            } else {
                $this->setResponse($this->response->withStatus(406, 'Failed to delete cart'));
                $errors = $entity->getErrors();
            }
        }else{
            $this->setResponse($this->response->withStatus(406, 'Failed to delete cart'));
        }
        $this->set(compact('errors'));

    }

    public function moveWishlist(){
        $this->request->allowMethod(['post', 'put']);

        $customerId = $this->Authenticate->getId();
        $find =  $this->CustomerCartDetails->find()
            ->contain(['CustomerCarts'])
            ->where([
                'CustomerCarts.customer_id' => $customerId,
                'CustomerCartDetails.id' => $this->request->getData('cartid'),
                'CustomerCartDetails.status' => 1
            ])
            ->first();
        if($find){
            $entity = $this->CustomerCartDetails->get($this->request->getData('cartid'));
            $entity->set('status', 5);
            if ($this->CustomerCartDetails->save($entity)) {
                //success

                $entityWhishlist = $this->CustomerWishes->newEntity();
                $entityWhishlist->set('customer_id', $customerId);
                $entityWhishlist->set('product_id', $entity->get('product_id'));
                $entityWhishlist->set('price', $entity->get('price'));

                if ($this->CustomerWishes->save($entityWhishlist)) {
                    //success

                } else {
                    $this->setResponse($this->response->withStatus(406, 'Failed to add wishlists'));
                    $errors = $entityWhishlist->getErrors();
                }

            } else {
                $this->setResponse($this->response->withStatus(406, 'Failed move to whishlist'));
                $errors = $entity->getErrors();
            }
        }else{
            $this->setResponse($this->response->withStatus(406, 'Failed move to whishlist'));
        }
        $this->set(compact('errors'));

    }

}