<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
/**
 * Mailer component
 * @property \AdminPanel\Model\Table\Customers $Customers
 * @property \Cake\Mailer\Email $Email
 */
class MailerComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = ['transport' => 'mailgun'];

    protected $params = [];

    protected $Customers = null;

    protected $Email;

    protected $plugin = 'AdminPanel';

    public function initialize(array $config)
    {
        $this->_defaultConfig = array_replace($this->_defaultConfig, $config);
        parent::initialize($config);
    }

    /**
     * @param $plugin
     * @return $this
     */
    public function setPlugin($plugin)
    {
        $this->plugin = $plugin;
        return $this;
    }

    public function setVar(array $params)
    {
        $this->params += $params;
        return $this;
    }

    /**
     * @param $destination
     * @param $subject
     * @param $template
     * @param bool $send_later
     * @return \Cake\Mailer\Email
     */
    public function send($destination, $subject, $template, $send_later = false)
    {
        if (!filter_var($destination, FILTER_VALIDATE_EMAIL)) {
            $this->Customers = TableRegistry::get('AdminPanel.Customers');
            $data = $this->Customers->find()
                ->select(['email', 'username'])
                ->where(['id' => $destination])
                ->first();
            if ($data) {
                $destination = $data->get('email');
                $this->params['name'] = $data->get('username');
            }
        }
        $this->Email = new Email($this->_defaultConfig['transport']);
        $email = $this->Email->setFrom(['noreply@zolaku.com' => 'Zolaku'])
            ->setTo($destination)
            ->setViewVars($this->params)
            //->setLayout('default') //deprecated
            ->setTemplate($template)
            ->setEmailFormat('html')
            ->setSubject($subject);

        $email->viewBuilder()->setLayout('default');
        if (!$send_later) {
            if ($this->Email->send()) {
                $this->Email = null; //set null if success
            }
        }
        return $this->Email;
    }


    /**
     * execute email
     * @return void
     */
    public function execute()
    {
        if ($this->Email instanceof Email) {
            if ($this->Email->send()) {
                $this->Email = null; //set null if success
            }
        }
    }
}
