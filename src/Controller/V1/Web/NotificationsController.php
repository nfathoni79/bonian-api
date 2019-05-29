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
 * @property \App\Model\Table\CustomerNotificationTypesTable $CustomerNotificationTypes
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class NotificationsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('CustomerNotifications');
        $this->loadModel('CustomerNotificationTypes');
    }

    public function categories()
    {
        $data = $this->CustomerNotifications->find()
            ->toArray();

        $this->set(compact('data'));
    }



    /**
     * list all notification
     * @param null $type
     */
    public function index($type = null)
    {

        $data = $this->CustomerNotifications->find()
            ->contain([
                'CustomerNotificationTypes'
            ])
            ->where([
                'customer_id' => $this->Authenticate->getId()
            ]);

        if ($type) {
            $data->where([
                'customer_notification_type_id' => $type
            ]);
        }


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

            $row->time_ago = $row->created instanceof \Cake\I18n\FrozenTime ? $row->created->timeAgoInWords([
                'end' => '+10 year',
                'format' => 'F jS, Y',
                'accuracy' => array('second' => 'second')
            ]) : $row->created;

            return $row;
        });

        $count = $this->CustomerNotifications->find()
            ->where([
                'customer_id' => $this->Authenticate->getId(),
                'is_read' => 0
            ])->count();

        $categories = $this->CustomerNotificationTypes->find()
            ->toArray();

        $title = null;
        foreach($categories as $val) {
            if ($val['id'] == $type) {
                $title = $val['name'];
                break;
            }
        }


        $this->set(compact('data', 'count', 'categories', 'title'));
    }

    public function count()
    {
        $count = $this->CustomerNotifications->find()
            ->where([
                'customer_id' => $this->Authenticate->getId(),
                'is_read' => 0
            ])->count();
        $this->set(compact('count'));
    }

    public function mark()
    {
        $this->request->allowMethod('post');
        if ($notification_id = $this->request->getData('notification_id')) {

            $this->CustomerNotifications->query()
                ->update()
                ->set('is_read', 1)
                ->where([
                    'customer_id' => $this->Authenticate->getId(),
                    'id' => $notification_id
                ])
                ->execute();

            $total = $this->CustomerNotifications->find()
                ->where([
                    'customer_id' => $this->Authenticate->getId(),
                    'is_read' => 0
                ])->count();

            $this->set(compact('total'));
        }
    }

    public function head()
    {
        $count = $this->CustomerNotifications->find()
            ->where([
                'customer_id' => $this->Authenticate->getId(),
                'is_read' => 0
            ])->count();

        $data = $this->CustomerNotifications->find()
            ->where([
                'customer_id' => $this->Authenticate->getId()
            ]);


        $data = $data->order([
            'CustomerNotifications.is_read' => 'desc',
            'CustomerNotifications.id' => 'desc'
        ])
            ->limit(15)
            ->map(function (\App\Model\Entity\CustomerNotification $row) {

                unset($row->customer_id);
                unset($row->model);
                unset($row->foreign_key);
                unset($row->controller);
                unset($row->action);

                $row->time_ago = $row->created instanceof \Cake\I18n\FrozenTime ? $row->created->timeAgoInWords([
                    'end' => '+10 year',
                    'format' => 'F jS, Y',
                    'accuracy' => array('second' => 'second')
                ]) : $row->created;

                return $row;
            });


        $this->set(compact('data', 'count'));
    }
}
