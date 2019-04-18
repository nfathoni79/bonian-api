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
use Cake\I18n\FrozenTime;

/**
 * Networks controller
 *
 * @property \App\Model\Table\GenerationsTable $Generations
 * @property \App\Model\Table\CustomersTable $Customers
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */

class NetworksController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->loadModel('Generations');
    }

    /**
     * list all address
     */
    public function index()
    {
        $this->request->allowMethod('get');

        $timeJsonFormat = 'yyyy-MM-dd HH:mm';

        FrozenTime::setJsonEncodeFormat($timeJsonFormat);
        FrozenTime::setToStringFormat($timeJsonFormat);
        $generations = $this->Generations->find('threaded')
            ->contain([
                'Customers',
                'Refferals'
            ])
            ->where([
               'Generations.refferal_id' => $this->Authenticate->getId()
            ]);


        $generations
            ->orderAsc('Generations.created');

        $data = $this->paginate($generations)
            ->map(function (\App\Model\Entity\Generation $row) {
                unset($row->refferal->id);
                unset($row->refferal->refferal_customer_id);
                unset($row->refferal->email);
                unset($row->refferal->first_name);
                unset($row->refferal->last_name);
                unset($row->refferal->dob);
                unset($row->refferal->gender);
                unset($row->refferal->avatar);
                unset($row->refferal->customer_group_id);
                unset($row->refferal->customer_status_id);
                unset($row->refferal->is_verified);
                unset($row->refferal->activation);
                unset($row->refferal->platforrm);
                unset($row->refferal->created);
                unset($row->refferal->modified);
                unset($row->customer->id);
                unset($row->customer->refferal_customer_id);
                unset($row->customer->email);
                unset($row->customer->first_name);
                unset($row->customer->last_name);
                unset($row->customer->dob);
                unset($row->customer->gender);
                unset($row->customer->avatar);
                unset($row->customer->customer_group_id);
                unset($row->customer->customer_status_id);
                unset($row->customer->is_verified);
                unset($row->customer->activation);
                unset($row->customer->platforrm);
                unset($row->customer->created);
                unset($row->customer->modified);
//                debug($row);
                return $row;
            });

        $this->set(compact('data'));

    }

}