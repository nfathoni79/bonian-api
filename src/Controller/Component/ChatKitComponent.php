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



}
