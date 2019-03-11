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
/**
 * Customers controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\CustomerCartsTable $CustomerCarts
 * @property \App\Model\Table\CustomerCartDetailsTable $CustomerCartDetails
 * @property \App\Model\Table\ProductOptionPricesTable $ProductOptionPrices
 * @property \App\Model\Table\ProductDealDetailsTable $ProductDealDetails
 * @property \App\Model\Table\ProductGroupDetailsTable $ProductGroupDetails
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
    }

    public function add(){
        $this->request->allowMethod(['post', 'put']);
        $customerId = $this->Auth->user('id');

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

                $newEntityDetails->set('total', (($newEntityDetails['price'] + $newEntityDetails['add_price']) * $newEntityDetails['qty']));
                $newEntityDetails->set('status', 1);



                if ($this->CustomerCartDetails->save($newEntityDetails)) {

                } else {
                    $this->setResponse($this->response->withStatus(406, 'Failed to add cart'));
                    $errors = $newEntityDetails->getErrors();
                }
            }else{
                $this->setResponse($this->response->withStatus(406, 'Failed to add cart'));
                $errors = $newEntityDetails->getErrors();
            }

            $this->set(compact('errors'));

        }

    }

}