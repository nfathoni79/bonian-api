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
/**
 * Customers controller
 *
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\CustomerAuthenticatesTable $CustomerAuthenticates
 * @property \App\Model\Table\IpLocationsTable $IpLocations
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class CustomersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->loadModel('CustomerAuthenticates');
        $this->loadModel('IpLocations');
    }


    public function loginHistory()
    {
        $data = $this->CustomerAuthenticates->find()
            ->select([
                'browser',
                'ip',
                'IpLocations.city',
                'IpLocations.country_name',
                'IpLocations.country_code',
                'IpLocations.latitude',
                'IpLocations.longitude',
                'IpLocations.asn',
                'IpLocations.organisation',
            ])
            ->where([
                'customer_id' => $this->Authenticate->getId()
            ])
            ->leftJoin(['IpLocations' => 'ip_locations'], [
                'IpLocations.ip = CustomerAuthenticates.ip'
            ])
            ->map(function($row) {
                $ua = parse_user_agent($row->browser);
                $row->device = $ua;
                return $row;
            });

        $this->set(compact('data'));
    }

    public function saveBrowser()
    {
        $this->request->allowMethod('post');
        $auth = $this->CustomerAuthenticates->find()
            ->where([
                'customer_id' => $this->Authenticate->getId(),
                'token' => $this->Authenticate->getToken()
            ])
            ->first();



        if ($auth) {
            $this->CustomerAuthenticates->patchEntity($auth, $this->request->getData(), [
                'fields' => [
                    'browser',
                    'ip'
                ]
            ]);
            if ($this->CustomerAuthenticates->save($auth)) {
                if (!in_array($auth->get('ip'), ['::1', '127.0.0.1'])) {
                    $ip = json_decode(file_get_contents(
                        'https://api.ipdata.co/'.$auth->get('ip').'?api-key=d3941e87e91ccde61c9a9d0a488f3ceee2cead61fabfaa2de8087e64'
                    ), true);
                    if ($ip && isset($ip['ip'])) {
                        $exists = $this->IpLocations->find()
                            ->where([
                                'ip' => $ip['ip']
                            ])
                            ->count();
                        if ($exists == 0) {
                            $entity = $this->IpLocations->newEntity($ip);
                            $this->IpLocations->save($entity);
                        }

                    }
                }
            }
        }
    }

    /**
     * get balance
     */
    public function getBalance()
    {

        $data = $this->Customers->CustomerBalances->find()
            ->select([
                'customer_id',
                'balance',
                'point',
            ])
            ->where([
                'customer_id' => $this->Authenticate->getId()
            ])
            ->first();

        $this->set(compact('data'));
    }

    /**
     * get detail of customer
     */
    public function detail()
    {
        $data = $this->Customers->find()
            ->select([
                'id',
                'reffcode',
                'email',
                'username',
                'first_name',
                'last_name',
                'phone',
                'dob',
                'gender',
                'is_verified',
                'platforrm',
                'created',
            ])
            ->contain([
                'CustomerAddreses' => [
                    'Provinces',
                    'Cities',
                    'Subdistricts',
                ],
                'ReferralCustomer'
            ])
            ->where([
                'Customers.id' => $this->Authenticate->getId()
            ])
            ->enableAutoFields(true)
            ->map(function (\App\Model\Entity\Customer $row) {
                $row->created = $row->created instanceof \Cake\I18n\FrozenTime  ? $row->created->timestamp : (Time::now())->timestamp;
                foreach($row->customer_addreses as $key => &$val) {
                    $val->created = $val->created instanceof \Cake\I18n\FrozenTime  ? $val->created->timestamp : 0;
                    $val->modified = $val->modified instanceof \Cake\I18n\FrozenTime  ? $val->modified->timestamp : 0;
                    unset($val['customer_id']);
                }
                return $row;
            })
            ->first();

        $this->set(compact('data'));
    }
}
