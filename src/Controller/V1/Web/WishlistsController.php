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
 * @property \App\Model\Table\CustomerWishesTable $CustomerWishes
 * @link https://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class WishlistsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('CustomerWishes');
    }

    /**
     * list all address
     */
    public function index()
    {

        $data = $this->CustomerWishes->find()
            ->contain([
                'Products' => [
                    'fields' => [
                        'id',
                        'name',
                        'slug',
                        'price',
                        'price_sale',
                        'point',
                        'rating',
                        'rating_count',
                    ],
                    'ProductImages' => [
                        'fields' => [
                            'name',
                            'product_id',
                        ],
                        'sort' => ['ProductImages.primary' => 'DESC']
                    ]
                ]
            ])
            ->where([
                'customer_id' => $this->Authenticate->getId()
            ]);

        $data = $this->paginate($data, [
            'limit' => (int) $this->request->getQuery('limit', 5)
        ])
            ->map(function (\App\Model\Entity\CustomerWish $row) {
                unset($row->customer_id);
                $row->product->images = Hash::extract($row->product->get('product_images'), '{n}.name');
                //$row->created = $row->created instanceof \Cake\I18n\FrozenTime  ? $row->created->timestamp : 0;
                //$row->modified = $row->modified instanceof \Cake\I18n\FrozenTime  ? $row->modified->timestamp : 0;

                unset($row->product->product_images);

                return $row;
            });

        $this->set(compact('data'));
    }


    public function add()
    {
        $this->request->allowMethod(['post', 'put']);
        $content_type = $this->request->getHeader('content-type');

        //process add address here

        $entity = $this->CustomerWishes->newEntity();
        $entity->set('customer_id', $this->Authenticate->getId());

        $this->CustomerWishes->patchEntity($entity, $this->request->getData(), [
            'fields' => [
                'product_id'
            ]
        ]);

        try {
            $product = $this->CustomerWishes->Products->get($entity->get('product_id'));
            if ($product) {
                $entity->set('price', $product->get('price_sale'));
            }
        } catch(\Exception $e) {}



        if ($this->CustomerWishes->save($entity)) {
            //success

        } else {
            $this->setResponse($this->response->withStatus(406, 'Failed to add wishlists'));
            $error = $entity->getErrors();
        }

        $this->set(compact('error'));

    }

    public function delete()
    {
        $this->request->allowMethod(['post', 'put']);
        if ($wishlist_id = $this->request->getData('wishlist_id')) {
            $entity = $this->CustomerWishes->find()
                ->where([
                    'customer_id' => $this->Authenticate->getId(),
                    'id' => $wishlist_id
                ])
                ->first();
            if ($entity) {
                if (!$this->CustomerWishes->delete($entity)) {
                    $this->setResponse($this->response->withStatus(406, 'Failed to delete wishlist'));
                    $error = $entity->getErrors();
                }
            } else {
                $this->setResponse($this->response->withStatus(406, 'Wishlist not found'));
            }
        }


        $this->set(compact('error'));
    }


}
