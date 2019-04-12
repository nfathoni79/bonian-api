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
use Cake\Validation\Validator;

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

        $data = $this->Customers->CustomerAddreses->find()
            ->contain([
                'Provinces',
                'Cities',
                'Subdistricts',
            ])
            ->where([
                'customer_id' => $this->Authenticate->getId()
            ])
            ->orderDesc('is_primary')
            ->map(function (\App\Model\Entity\CustomerAddrese $row) {
                $row->created = $row->created instanceof \Cake\I18n\FrozenTime  ? $row->created->timestamp : 0;
                $row->modified = $row->modified instanceof \Cake\I18n\FrozenTime  ? $row->modified->timestamp : 0;

                return $row;
            });

        $this->set(compact('data'));
    }

    public function setPrimary($address_id)
    {
        $this->request->allowMethod(['post', 'put']);

        $this->Customers->CustomerAddreses->query()
            ->update()
            ->set([
                'is_primary' => 0
            ])
            ->where([
                'customer_id' => $this->Authenticate->getId()
            ])
            ->where(function(\Cake\Database\Expression\QueryExpression $exp) use($address_id) {
                return $exp->notEq('CustomerAddreses.id', $address_id);
            });

        $this->Customers->CustomerAddreses->query()
            ->update()
            ->set([
                'is_primary' => 1
            ])
            ->where([
                'customer_id' => $this->Authenticate->getId(),
                'CustomerAddreses.id' => $address_id
            ]);

    }

    public function setCoordinate($address_id)
    {
        $this->request->allowMethod(['post', 'put']);

        $addressEntity = $this->Customers->CustomerAddreses->find()
            ->where([
                'CustomerAddreses.id' => $address_id,
                'customer_id' => $this->Authenticate->getId()
            ])
            ->first();

        if (!empty($addressEntity)) {
            $validator = new Validator();
            $validator->requirePresence('latitude')
                ->numeric('latitude', 'format latitude salah')
                ->requirePresence('longitude')
                ->numeric('longitude','Format longitude salah');

            $error = $validator->errors($this->request->getData());
            if (empty($error)) {
                $this->Customers->CustomerAddreses->patchEntity($addressEntity, $this->request->getData(), [
                    'fields' => [
                        'latitude',
                        'longitude',
                    ]
                ]);
                if (!$this->Customers->CustomerAddreses->save($addressEntity)) {
                    $this->setResponse($this->response->withStatus(406, 'Gagal update latitude dan longitude'));
                    $error = $addressEntity->getErrors();
                }
            }
        } else {
            $this->setResponse($this->response->withStatus(404, 'Address id tidak ditemukan'));
        }



        $this->set(compact('error'));
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
                'postal_code',
                'address',
            ]
        ]);

        $entity->set('customer_id', $this->Authenticate->getId());

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
            $error = $entity->getErrors();
        }

        $this->set(compact('error'));

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
                    'customer_id' => $this->Authenticate->getId(),
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
            $data = $this->Customers->CustomerAddreses->find()
                ->where([
                    'customer_id' => $this->Authenticate->getId(),
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

        $this->set(compact('data'));
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
                    'customer_id' => $this->Authenticate->getId(),
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
                        'postal_code',
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
        $data = $this->Provinces->find('list')->toArray();
        $this->set(compact('data'));
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

        $data = $city->toArray();

        $this->set(compact('data'));
    }

    public function getDistrict($city_id)
    {
        $data = $this->Cities->Subdistricts->find('list')
            ->where([
                'city_id' => $city_id
            ])
            ->toArray();

        $this->set(compact('data'));
    }


}
