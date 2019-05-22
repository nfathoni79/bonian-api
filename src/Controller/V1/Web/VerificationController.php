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
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class VerificationController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
    }

    /**
     * list all address
     */
    public function email()
    {
        $customers = $this->Customers->find()
            ->select(['id', 'email','is_email_verified'])
            ->where(['email' => $this->request->getData('email'), 'is_email_verified' => 0])
            ->first();
        if($customers){

            $this->Mailer
                ->setVar([
                    'code' => $customers->get('activation'),
                    'name' => $customers->get('username'),
                    'email' => $customers->get('email'),
                    'activation_url' => $this->request->getData('activation_url', '')
                ])
                ->send(
                    $customers->get('id'),
                    'Verifikasi Alamat Email Kamu Di Zolaku',
                    'verification', 
                );

        }else{
            $this->setResponse($this->response->withStatus(406, 'Email telah diferivikasi'));
        }
    }

}
