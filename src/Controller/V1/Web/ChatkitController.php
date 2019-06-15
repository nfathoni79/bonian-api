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
use Cake\Utility\Hash;
/**
 * Customers controller
 * @property \App\Model\Table\CustomersTable $Customers
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class ChatkitController extends AppController
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

        $this->set(compact('data'));
    }


    public function deleteRoom($roomId)
    {
        $this->request->allowMethod('post');

        /**
         * @var \App\Model\Entity\Customer $customerEntity
         */
        $customerEntity = $this->Customers->find()
            ->select([
                'id',
                'username'
            ])
            ->where([
                'id' => $this->Authenticate->getId()
            ])
            ->first();

        try {
            $room = $this->ChatKit->getInstance()->getRoom([ 'id' => $roomId ]);
            if (isset($room['body']['created_by_id']) && $room['body']['created_by_id'] == $customerEntity->username) {
                try {
                    $room = $this->ChatKit->getInstance()->deleteRoom([ 'id' => $roomId ]);
                }catch(\Exception $e) {
                    $this->response = $this->response->withStatus(406, $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            $this->response = $this->response->withStatus(406, $e->getMessage());
        }

        $this->set(compact('room'));
    }


}
