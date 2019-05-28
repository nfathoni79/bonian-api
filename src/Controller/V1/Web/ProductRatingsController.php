<?php
namespace App\Controller\V1\Web;


use Cake\Utility\Hash;
use Cake\I18n\Time;
use Cake\Http\Client;
use Cake\Core\Configure;
use Cake\Http\Client\FormData;
use Cake\Validation\Validator;
use function PhpParser\filesInDir;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 * @property \App\Model\Table\ProductRatingsTable $ProductRatings
 * @property \App\Model\Table\OrdersTable $Orders
 * @method \App\Model\Entity\ProductRating[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductRatingsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Products');
        $this->loadModel('ProductRatings');
        $this->loadModel('Orders');
    }


    public function index()
    {
        $status_payment = [
//            'semua' => '0',
//            'pending' => '1',
            'success' => '2',
//            'failed' => '3',
//            'expired' => '4',
//            'refunde' => '5',
//            'cancel' => '6',
        ];

        $orders = $this->Orders->find()
            ->contain([
                'Transactions' => [
                    'fields' => [
                        'order_id',
                        'transaction_time',
                        'transaction_status',
                        'fraud_status',
                        'gross_amount',
                        'currency',
                        'payment_type',
                        'va_number',
                        'masked_card',
                        'card_type',
                        'created',
                        'modified'
                    ]
                ],
                'ProductRatings' => [
                    'Products' => [
                        'fields' => [
                            'id',
                            'name',
                            'slug'
                        ],
                        'ProductImages' => [
                            'fields' => [
                                'name',
                                'product_id',
                            ],
                            'sort' => ['ProductImages.primary' => 'DESC','ProductImages.created' => 'ASC']
                        ]
                    ]
                ],
                'Provinces',
                'Cities',
                'Subdistricts'

            ])
            ->where([
                'Orders.customer_id' => $this->Authenticate->getId(),
                'Orders.payment_status != ' => 4,
                'Orders.order_type' => 1,
            ]);


        $orders
            ->orderDesc('Orders.id')
        ;

        if(!empty($this->request->getQuery('search'))){
            $orders->where(['Orders.invoice' => $this->request->getQuery('search')]);
        }

        if(($this->request->getQuery('status') != 'semua') && ($this->request->getQuery('status'))){
            $orders->where([
                'Orders.payment_status' => $status_payment[$this->request->getQuery('status')]
            ]);
        }

        if(!empty($this->request->getQuery('start')) && !empty($this->request->getQuery('end'))){
            $orders->where(function (\Cake\Database\Expression\QueryExpression $exp) {
                return $exp->gte('Orders.created', date("Y-m-d", strtotime($this->request->getQuery('start'))).' 00:00:00')
                    ->lte('Orders.created', date("Y-m-d", strtotime($this->request->getQuery('end'))).' 23:59:59');
            });
        }


        $data = $this->paginate($orders, [
            'limit' => (int) $this->request->getQuery('limit', 5)
        ])
            ->map(function (\App\Model\Entity\Order $row) {
                foreach ($row['product_ratings'] as $key => $vals){
                    $row->product_ratings[$key]->product->images = Hash::extract($row->product_ratings[$key]->product->product_images, '{n}.name');
                    unset($row->product_ratings[$key]->product->product_images);
                }
                unset($row->customer_id);
                return $row;
            });
        $this->set(compact('data'));
    }

    public function viewList()
    {
        $status_payment = [
//            'semua' => '0',
//            'pending' => '1',
            'success' => '2',
//            'failed' => '3',
//            'expired' => '4',
//            'refunde' => '5',
//            'cancel' => '6',
        ];

        $orders = $this->Orders->find()
            ->contain([
                'Transactions' => [
                    'fields' => [
                        'order_id',
                        'transaction_time',
                        'transaction_status',
                        'fraud_status',
                        'gross_amount',
                        'currency',
                        'payment_type',
                        'va_number',
                        'masked_card',
                        'card_type',
                        'created',
                        'modified'
                    ]
                ],
                'ProductRatings' => [
                    'Products' => [
                        'fields' => [
                            'id',
                            'name',
                            'slug'
                        ],
                        'ProductImages' => [
                            'fields' => [
                                'name',
                                'product_id',
                            ],
                            'sort' => ['ProductImages.primary' => 'DESC','ProductImages.created' => 'ASC']
                        ]
                    ]
                ],
                'Provinces',
                'Cities',
                'Subdistricts'

            ])
            ->where([
                'Orders.customer_id' => $this->Authenticate->getId(),
                'Orders.payment_status != ' => 4,
                'Orders.order_type' => 1,
            ]);


        $orders
            ->orderDesc('Orders.id')
        ;

        if(!empty($this->request->getQuery('search'))){
            $orders->where(['Orders.invoice' => $this->request->getQuery('search')]);
        }

        if(($this->request->getQuery('status') != 'semua') && ($this->request->getQuery('status'))){
            $orders->where([
                'Orders.payment_status' => $status_payment[$this->request->getQuery('status')]
            ]);
        }

        if(!empty($this->request->getQuery('start')) && !empty($this->request->getQuery('end'))){
            $orders->where(function (\Cake\Database\Expression\QueryExpression $exp) {
                return $exp->gte('Orders.created', date("Y-m-d", strtotime($this->request->getQuery('start'))).' 00:00:00')
                    ->lte('Orders.created', date("Y-m-d", strtotime($this->request->getQuery('end'))).' 23:59:59');
            });
        }

        $data = $this->paginate($orders, [
            'limit' => (int) $this->request->getQuery('limit', 5)
        ])
            ->map(function (\App\Model\Entity\Order $row) {
                foreach ($row['product_ratings'] as $key => $vals){
                    $row->product_ratings[$key]->product->images = Hash::extract($row->product_ratings[$key]->product->product_images, '{n}.name');
                    unset($row->product_ratings[$key]->product->product_images);
                }
                unset($row->customer_id);
                return $row;
            });
        $this->set(compact('data'));
    }


    public function add()
    {
        $this->request->allowMethod('post');


        $validator = new Validator();
        $validator
            ->requirePresence('order_id')
            ->notBlank('order_id');
        $validator
            ->requirePresence('product_id')
            ->notBlank('product_id');

        $error = $validator->errors($this->request->getData());
        if (empty($error)) {
            $findProductRating = $this->ProductRatings->find()
                ->where([
                    'ProductRatings.order_id' => $this->request->getData('order_id'),
                    'ProductRatings.product_id' => $this->request->getData('product_id'),
                    'ProductRatings.customer_id' => $this->Authenticate->getId(),
                    'ProductRatings.status' => 0,
                ])
                ->first();
            if($findProductRating){

                $productRating = $this->ProductRatings->get($findProductRating->get('id'));
                $this->ProductRatings->patchEntity($productRating, $this->request->getData(), [
                    'fields' => [
                        'order_detail_product_id',
                        'rating',
                        'comment'
                    ]
                ]);
                $productRating->set('status', 1);
                if ($this->ProductRatings->save($productRating)) {


                    $id = $productRating->get('id');
                    $http = new Client();
                    $data = new FormData();
                    $data->add('product_rating_id', $id);

                    foreach($this->request->getData('images') as $k => $vals){
                        $file = $data->addFile('name['.$k.']', fopen($this->request->getData('images.'.$k.'.tmp_name'), 'r'));
                        $file->filename($this->request->getData('images.'.$k.'.name'));
                    }

                    $response = $http->post(Configure::read('postImage').'/ratings', (string)$data,['headers' => ['Content-Type' => $data->contentType()]]);
                    $result = json_decode($response->getBody()->getContents());
                    if($result->is_success){

                    }else{
                        $this->setResponse($this->response->withStatus(406, 'Unable to upload images'));
                    }

                    //save logic
                } else {
                    $this->setResponse($this->response->withStatus(406, 'Failed to add rating'));
                    $error = $productRating->getErrors();
                }

            }else{
                $this->setResponse($this->response->withStatus(406, 'Anda sudah pernah melakukan review.'));
            }

        }
        $this->set(compact('error'));



    }

    public function view(){
        $data = $this->ProductRatings->find()
            ->contain([
                'Orders',
                'Products' => [
                    'fields' => [
                        'id',
                        'name',
                        'slug'
                    ],
                    'ProductImages' => [
                        'fields' => [
                            'name',
                            'product_id',
                        ],
                        'sort' => ['ProductImages.primary' => 'DESC','ProductImages.created' => 'ASC']
                    ]
                ],
                'ProductRatingImages'
            ])
            ->where([
                'ProductRatings.order_id' => $this->request->getData('order_id'),
                'ProductRatings.customer_id' => $this->Authenticate->getId(),
                'ProductRatings.id' => $this->request->getData('id'),
            ]) ->map(function (\App\Model\Entity\ProductRating $row) {
                $row->product->images = Hash::extract($row->product->product_images, '{n}.name');
                unset($row->product->product_images);
                return $row;
            })->first();
        $this->set(compact('data'));

    }

}
