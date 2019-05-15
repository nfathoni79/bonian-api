<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\Locator\TableLocator;

/**
 * Notification component
 * @property \App\Model\Table\CustomerNotificationsTable $CustomerNotifications
 * @property \App\Controller\Component\PusherComponent $Pusher
 */
class NotificationComponent extends Component
{
    public $components = ['Pusher'];

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];
    protected $CustomerNotifications = null;

    /*
     * initialize component
     */
    public function initialize(array $config)
    {
        $this->CustomerNotifications = (new TableLocator ())->get('CustomerNotifications');
    }

    /**
     * @return \App\Model\Table\CustomerNotificationsTable
     */
    public function getTable()
    {
        return $this->CustomerNotifications;
    }

    /**
     * @param $customer_id
     * @param $reffcode
     * @param string $prefix
     */
    public function triggerCount($customer_id, $reffcode, $prefix = 'my-event-')
    {
        $pusher = $this->Pusher->Pusher();
        $total = $this->CustomerNotifications->find()
            ->where([
                'customer_id' => $customer_id,
                'is_read' => 0
            ])->count();

        try {
            $pusher->trigger(
                'private-notification',
                $prefix . $reffcode,
                ['total' => $total]
            );
        } catch (\Exception $e) {

        }
    }

    /**
     * @param $customer_id
     * @param $type_id 1: text, 2: html
     * @param $title
     * @param $message
     * @param null $model
     * @param null $foreign_key
     * @param null $controller
     * @param null $action
     * @param null $template
     * @return \App\Model\Entity\CustomerNotification|bool
     */
    public function create($customer_id, $type_id, $title, $message, $model = null, $foreign_key = null, $controller = null, $action = null, $template = null)
    {
        $entity = $this->CustomerNotifications->newEntity([
           'customer_id' => $customer_id,
           'customer_notification_type_id' => $type_id,
            'title' => $title,
            'message' => $message,
            'model' => $model,
            'foreign_key' => $foreign_key,
            'controller' => $controller,
            'action' => $action,
            'template' => $template
        ]);

        return $this->CustomerNotifications->save($entity);
    }
}
