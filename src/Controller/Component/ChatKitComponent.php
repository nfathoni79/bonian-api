<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;

/**
 * ChatKit component
 * @property \Chatkit\Chatkit $chatkit
 */
class ChatKitComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    protected $chatkit = null;

    public function initialize(array $config)
    {
        parent::initialize($config);

        $config = Configure::read('ChatKit');

        if (isset($config['instance_locator']) && isset($config['key'])) {
            $this->chatkit = new \Chatkit\Chatkit([
                'instance_locator' => $config['instance_locator'],
                'key' => $config['key']
            ]);
        }
    }

    /**
     * @return \Chatkit\Chatkit
     */
    public function getInstance()
    {
        return $this->chatkit;
    }

    public function test()
    {
        return 'testing oke';
    }


    /**
     * @param $user_id
     * @param null $name
     * @param null $avatar
     * @return array|null
     */
    public function createUser($user_id, $name = null, $avatar = null)
    {
        $user = null;
        try {
            $user = $this->getInstance()->getUser([ 'id' => $user_id ]);
        } catch(\Exception $e) {
            try {

                $entity = [
                    'id' => $user_id,
                    'name' => $name
                ];

                if ($avatar) {
                    $entity['avatar_url'] = rtrim(Configure::read('mainSite'), '/') .
                        '/files/Customers/avatar/thumbnail-' . $avatar;
                }

                $user = $this->getInstance()->createUser($entity);
            } catch(\Exception $e) {

            }

        }
        return $user;
    }
}
