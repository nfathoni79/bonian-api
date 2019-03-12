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
 * @property \App\Model\Table\ProvincesTable $Provinces
 * @property \App\Model\Table\CitiesTable $Cities
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class AddressesController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->loadModel('Provinces');
        $this->loadModel('Cities');
    }

    /**
     * list all address
     */
    public function index()
    {

        $addresses = $this->Customers->CustomerAddreses->find()
            ->contain([
                'Provinces',
                'Cities',
                'Subdistricts',
            ])
            ->where([
                'customer_id' => $this->Auth->user('id')
            ])
            ->map(function (\App\Model\Entity\CustomerAddrese $row) {
                $row->created = $row->created instanceof \Cake\I18n\FrozenTime  ? $row->created->timestamp : 0;
                $row->modified = $row->modified instanceof \Cake\I18n\FrozenTime  ? $row->modified->timestamp : 0;

                return $row;
            });

        $this->set(compact('addresses'));
    }

    /**
     * process add address
     */
    public function add()
    {
        $this->request->allowMethod(['post', 'put']);
        $content_type = $this->request->getHeader('content-type');

        //process add address here


        $allData = $this->request->getData();

        $entity = $this->Customers->CustomerAddreses->newEntity();

        $this->Customers->CustomerAddreses->patchEntity($entity, $allData, [
            'fields' => [
                'province_id',
                'city_id',
                'subdistrict_id',
                'is_primary',
                'title',
                'recipient_name',
                'recipient_phone',
                'latitude',
                'longitude',
                'address',
            ]
        ]);

        $entity->set('customer_id', $this->Auth->user('id'));

        if ($this->Customers->CustomerAddreses->save($entity)) {
            //success
            if ($entity->get('is_primary') == 1) {
                $this->Customers->CustomerAddreses->query()
                    ->update()
                    ->set([
                        'is_primary' => 0
                    ])
                    ->where([
                        'customer_id' => $entity->get('customer_id')
                    ])
                    ->where(function(\Cake\Database\Expression\QueryExpression $exp) use($entity) {
                        return $exp->notEq('CustomerAddreses.id', $entity->get('id'));
                    });
            }
        } else {
            $this->setResponse($this->response->withStatus(406, 'Failed to add address'));
            $errors = $entity->getErrors();
        }

        $this->set(compact('errors'));

    }

    /**
     * delete address given address_id
     */
    public function delete()
    {
        $this->request->allowMethod(['post', 'put']);
        if ($address_id = $this->request->getData('address_id')) {
            $addressEntity = $this->Customers->CustomerAddreses->find()
                ->where([
                    'customer_id' => $this->Auth->user('id'),
                    'id' => $address_id
                ])
                ->first();

            if ($addressEntity) {
                if (!$this->Customers->CustomerAddreses->delete($addressEntity)) {
                    $this->setResponse($this->response->withStatus(406, 'Failed to delete address'));
                }
            }

        }
    }

    /**
     * @param $address_id
     */
    public function view($address_id)
    {
        $this->request->allowMethod('get');
        if ($address_id) {
            $address = $this->Customers->CustomerAddreses->find()
                ->where([
                    'customer_id' => $this->Auth->user('id'),
                    'CustomerAddreses.id' => $address_id
                ])
                ->contain([
                    'Provinces',
                    'Cities',
                    'Subdistricts',
                ])
                ->map(function (\App\Model\Entity\CustomerAddrese $row) {
                    unset($row->customer_id);
                    $row->created = $row->created instanceof \Cake\I18n\FrozenTime  ? $row->created->timestamp : 0;
                    $row->modified = $row->modified instanceof \Cake\I18n\FrozenTime  ? $row->modified->timestamp : 0;

                    return $row;
                })
                ->first();
        }

        $this->set(compact('address'));
    }

    /**
     * @param $address_id
     */
    public function update($address_id)
    {
        $this->request->allowMethod(['post', 'put']);
        if ($address_id) {
            $address = $this->Customers->CustomerAddreses->find()
                ->where([
                    'customer_id' => $this->Auth->user('id'),
                    'CustomerAddreses.id' => $address_id
                ])
                ->first();

            if ($address) {
                $this->Customers->CustomerAddreses->patchEntity($address, $this->request->getData(), [
                    'fields' => [
                        'province_id',
                        'city_id',
                        'subdistrict_id',
                        'is_primary',
                        'title',
                        'recipient_name',
                        'recipient_phone',
                        'latitude',
                        'longitude',
                        'address',
                    ]
                ]);
                if (!$this->Customers->CustomerAddreses->save($address)) {
                    $this->setResponse($this->response->withStatus(406, 'Fail to save address'));
                } else {
                    if ($address->get('is_primary') == 1) {
                        $this->Customers->CustomerAddreses->query()
                            ->update()
                            ->set([
                                'is_primary' => 0
                            ])
                            ->where([
                                'customer_id' => $address->get('customer_id')
                            ])
                            ->where(function(\Cake\Database\Expression\QueryExpression $exp) use($address) {
                                return $exp->notEq('CustomerAddreses.id', $address->get('id'));
                            });
                    }
                }
            } else {
                $this->setResponse($this->response->withStatus(406, 'Address not found'));
            }

        } else {
            $this->setResponse($this->response->withStatus(406, 'Invalid address_id'));
        }
    }

    /**
     * get province
     */
    public function getProvince()
    {
        $province = $this->Provinces->find('list')->toArray();
        $this->set(compact('province'));
    }

    /**
     * @param null $province_id
     */
    public function getCity($province_id = null)
    {
        $city = $this->Cities->find('list');

        if ($province_id) {
            $city->where([
                'province_id' => $province_id
            ]);
        }

        $city = $city->toArray();

        $this->set(compact('city'));
    }

    public function getDistrict($city_id)
    {
        $district = $this->Cities->Subdistricts->find('list')
            ->where([
                'city_id' => $city_id
            ])
            ->toArray();

        $this->set(compact('district'));
    }


}
