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


/**
 * Customers controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class CustomersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
    }

    public function getBalance()
    {

        $balance = $this->Customers->CustomerBalances->find()
            ->select([
                'customer_id',
                'balance',
                'point',
            ])
            ->where([
                'customer_id' => $this->Auth->user('id')
            ])
            ->first();

        $this->set(compact('balance'));
    }
}
