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
use Cake\ORM\Locator\TableLocator;
use Cake\Utility\Inflector;
/**
 * Customers controller
 *
 * @property \App\Model\Table\CustomerNotificationsTable $CustomerNotifications
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class NotificationsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('CustomerNotifications');
    }

    /**
     * list all notification
     */
    public function index()
    {

        $data = $this->CustomerNotifications->find()
            ->where([
                'customer_id' => $this->Authenticate->getId()
            ]);


        $data->orderDesc('CustomerNotifications.id');
        $data = $this->paginate($data, [
            'limit' => (int) $this->request->getQuery('limit', 5)
        ])
        ->map(function (\App\Model\Entity\CustomerNotification $row) {

            if ($row->model && $row->foreign_key) {
                $model = $row->model;

                $table = (new TableLocator())->get($row->model);
                $data = $table->find()
                    ->where([
                        $row->model . '.id' => $row->foreign_key
                    ])
                    ->first();

                $row->{$model} = $data;

            }

            return $row;
        });

        $count = $this->CustomerNotifications->find()
            ->where([
                'customer_id' => $this->Authenticate->getId(),
                'is_read' => 0
            ])->count();

        
        $this->set(compact('data', 'count'));
    }
}
