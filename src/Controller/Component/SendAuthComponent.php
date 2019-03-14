<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\Query;
use Cake\Mailer\Email;

/**
 * SendAuth component
 * @property \AdminPanel\Model\Table\AuthCodesTable $AuthCodes
 */
class SendAuthComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];
    protected $AuthCodes = null;
    protected $name;
    protected $phone;
    protected $_id = null;
    protected $_viewVars = [];

    public function initialize(array $config)
    {
        $this->AuthCodes = TableRegistry::get('AdminPanel.AuthCodes');
    }

    public function register($name, $phone)
    {
        $this->name = $name;
        $this->phone = $phone;
        return $this;
    }

    function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            try {
                $pieces []= $keyspace[random_int(0, $max)];
            } catch(\Exception $e) {}
        }
        return implode('', $pieces);
    }

    public function generate($key = '0123456789')
    {
        try {
            $entity = $this->AuthCodes->newEntity([
                'phone' => $this->phone,
                'name' => $this->name,
                'code' => $this->random_str(6, $key),
                'expired' => (new \DateTime())->add(new \DateInterval('PT15M'))->format('Y-m-d H:i:s')
            ]);
            if ($this->AuthCodes->save($entity)) {
                $this->_id = $entity->get('id');
            }

        } catch (\Exception $e) {}

        return $this;
    }

    public function generates($key = '0123456789')
    {
        try {


            $find =  $this->AuthCodes->find()
                ->select(['AuthCodes.id'])
                ->where([
                    'AuthCodes.phone' => $this->phone,
                    'AuthCodes.name' => $this->name,
                    'AuthCodes.used' => 0
                ])->toArray();

            if(empty($find)){
                $entity = $this->AuthCodes->newEntity([
                    'phone' => $this->phone,
                    'name' => $this->name,
                    'code' => $this->random_str(6, $key),
                    'expired' => (new \DateTime())->add(new \DateInterval('PT15M'))->format('Y-m-d H:i:s')
                ]);
                if ($this->AuthCodes->save($entity)) {
                    $this->_id = $entity->get('id');
                }
                return $entity->get('code');
            }else{

            }

        } catch (\Exception $e) {}


    }

    /**
     * @param $code
     * @return bool
     */
    public function isValid($code)
    {
        $find =  $this->AuthCodes->find()
            ->select(['AuthCodes.id'])
            ->where([
                'AuthCodes.phone' => $this->phone,
                'AuthCodes.name' => $this->name,
                'AuthCodes.used' => 0,
                'AuthCodes.code' => $code
            ])
            ->andwhere(function (QueryExpression $exp, Query $query) {
                return $exp->gte('expired', date('Y-m-d H:i:s'));
            });
        if (!$find->isEmpty()) {
            $get = $find->first()->toArray();
            $this->_id = $get['id'];
            return true;
        }
        return false;
    }

    public function setUsed($code = null)
    {
        if (!$code && $this->_id) {
            $this->AuthCodes->query()
                ->update()
                ->set(['used' => 1])
                ->where(['id' => $this->_id])
                ->execute();
            return;
        }
        if ($this->isValid($code)) {
            $this->AuthCodes->query()
                ->update()
                ->set(['used' => 1])
                ->where(['id' => $this->_id])
                ->execute();
        }
    }

    /**
     * check data on table auth_codes
     * @return bool
     */
    public function exists()
    {
        $b = $this->AuthCodes->find()
            ->where([
                'AuthCodes.phone' => $this->phone,
                'AuthCodes.name' => $this->name,
                'AuthCodes.used' => 0
            ])
            ->andwhere(function (QueryExpression $exp, Query $query) {
                return $exp->gte('expired', date('Y-m-d H:i:s'));
            })
            ->count();
        return $b;
    }

    public function send($template = 'send_auth', $transport = 'mailgun')
    {
//        $params = $this->_viewVars =  $this->AuthCodes->get($this->_id, [
//            'contain' => ['Clients']
//        ])->toArray();
//
//        $email = new Email($transport);
//        $email->setFrom(['noreply@nevix.net' => 'Nevix'])
//            ->setTo($params['client']['email'])
//            ->setViewVars($params)
//            ->setLayout('default')
//            ->setTemplate('MemberPanel.' . $template)
//            ->setEmailFormat('html')
//            ->setSubject('Request authorization for ' . $this->name)
//            ->send();
//        return true;
    }

    public function sendsms($text = null)
    {
//        send sms to this phone number $this->phone with $text

        return true;
    }

    public function getData()
    {
        return $this->_viewVars;
    }
}
