<?php
namespace App\Controller\V1\Web;


use Cake\Utility\Hash;
use Cake\I18n\Time;
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

        $entity = $this->ProductRatings->newEntity();

        $entity->set('customer_id', $this->Auth->user('id'));

        $this->ProductRatings->patchEntity($entity, $this->request->getData(), [
           'fields' => [
               'product_id',
               'rating',
               'comment'
           ]
        ]);

        if ($this->ProductRatings->save($entity)) {
            //save logic
        } else {
            $this->setResponse($this->response->withStatus(406, 'Failed to add rating'));
            $error = $entity->getErrors();
        }

        $this->set(compact('error'));

    }

}
