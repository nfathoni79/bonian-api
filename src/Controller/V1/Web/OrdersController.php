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

use App\Lib\MidTrans\CreditCardToken;
use Cake\I18n\Time;
use Cake\Validation\Validator;

/**
 * Customers controller
 *
 * @property \App\Model\Table\OrdersTable $Orders
 * @property \App\Model\Table\TransactionsTable $Transactions
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class OrdersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Orders');
        $this->loadModel('Transactions');
    }

    /**
     * list all address
     */
    public function index()
    {

        $orders = $this->Orders->find()
            ->contain([
                'Transactions' => [
                    'fields' => [
                        'order_id',
                        'transaction_time',
                        'transaction_status',
                        'fraud_status',
                        'gross_amount',
                        'currency',
                        'payment_type',
                        'va_number',
                        'masked_card',
                        'card_type',
                    ]
                ],
                'Vouchers' => [
                    'fields' => [
                        'id',
                        'code_voucher'
                    ]
                ],
                'Provinces',
                'Cities',
                'Subdistricts'

            ])
            ->where([
                'Orders.customer_id' => $this->Auth->user('id')
            ]);


        $orders
            ->orderDesc('Orders.id')
            ;

        $data = $this->paginate($orders)
            ->map(function (\App\Model\Entity\Order $row) {
                $row->created = $row->created instanceof \Cake\I18n\FrozenTime  ? $row->created->timestamp : 0;

                unset($row->customer_id);

                return $row;
            });

        $this->set(compact('data'));
    }






}
