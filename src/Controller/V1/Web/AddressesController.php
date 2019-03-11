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
/**
 * Customers controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class AddressesController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
    }

    /**
     * list all address
     */
    public function index()
    {

        $addresses = $this->Customers->CustomerAddreses->find()
            ->contain([
                'Provinces',
                'Cities',
                'Subdistricts',
            ])
            ->where([
                'customer_id' => $this->Auth->user('id')
            ])
            ->map(function (\App\Model\Entity\CustomerAddrese $row) {
                $row->created = $row->created instanceof \Cake\I18n\FrozenTime  ? $row->created->timestamp : 0;
                $row->modified = $row->modified instanceof \Cake\I18n\FrozenTime  ? $row->modified->timestamp : 0;

                return $row;
            });

        $this->set(compact('addresses'));
    }


    public function add()
    {
        $this->request->allowMethod(['post', 'put']);

        //process add address here
    }


}
