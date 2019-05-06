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
 * @method \App\Model\Entity\ProductRating[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductRatingsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Products');
        $this->loadModel('ProductRatings');
    }


    public function add()
    {
        $this->request->allowMethod('post');


        $validator = new Validator();
        $validator
            ->requirePresence('order_detail_product_id')
            ->notBlank('order_detail_product_id');

        $error = $validator->errors($this->request->getData());
        if (empty($error)) {

            $findProductRating = $this->ProductRatings->find()
                ->where([
                    'ProductRatings.order_detail_product_id' => $this->request->getData('order_detail_product_id'),
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

}
