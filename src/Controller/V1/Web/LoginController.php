<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 13/02/2019
 * Time: 12:45
 */

namespace App\Controller\V1\Web;

/**
 * Class LoginController
 * @package App\Controller\V1
 * @property \App\Model\Table\CustomersTable $Customers
 */
class LoginController extends AppController
{

    /**
     * initialize
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->Auth->allow('index');
    }

    /**
     * index login
     */
    public function index()
    {
        $this->request->allowMethod('post');

        $user = $this->Auth->identify();
        if ($user) {
            $this->Auth->setUser($user);
        } else {
            //Username or password is incorrect
            $this->setResponse($this->response->withStatus(406, 'Username or password is incorrect'));
        }


    }
}