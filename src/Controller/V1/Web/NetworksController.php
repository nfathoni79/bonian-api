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
//                'Generations.refferal_id' => $this->Authenticate->getId()
            ]);


        $generations
            ->orderAsc('Generations.created');

        $data = $this->paginate($generations);

        $this->set(compact('data'));

    }

}