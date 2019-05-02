<?php
namespace App\Controller\V1\Web;

use Cake\Utility\Hash;
use Cake\I18n\Time;
use Cake\I18n\FrozenTime;
use Cake\Validation\Validator;

/**
 * Discussion Controller
 *
 * @property \App\Model\Table\ProductDiscussionsTable $ProductDiscussions
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DiscussionController extends AppController{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('ProductDiscussions');
    }


    public function add(){

        $validator = new Validator();

        $validator->requirePresence('comment')
            ->notBlank('comment', 'Pertanyaan diskusi tidak boleh kosong');
        $validator->requirePresence('product_id')
            ->notBlank('product_id', 'produk tidak ditemukan') ;

        $error = $validator->errors($this->request->getData());
        if (empty($error)) {

            $allData = $this->request->getData();
            $entity = $this->ProductDiscussions->newEntity();
            $this->ProductDiscussions->patchEntity($entity, $allData, [
                'fields' => [
                    'parent_id',
                    'product_id',
                    'comment'
                ]
            ]);
            $entity->set('customer_id', $this->Authenticate->getId());
            if(!$this->ProductDiscussions->save($entity)) {
                $this->setResponse($this->response->withStatus(406, 'Failed to add address'));
                $error = $entity->getErrors();
            }

        }
        if ($error) {
            $this->setResponse($this->response->withStatus(406, 'Failed to add address'));
        }

        $this->set(compact('error'));
    }

    public function delete(){
 
        $this->request->allowMethod(['post', 'put']);
        if ($discuss_id = $this->request->getData('id')) {
            $entity = $this->ProductDiscussions->find()
                ->where([
                    'customer_id' => $this->Authenticate->getId(),
                    'id' => $discuss_id
                ])
                ->first();

            if ($entity) {
                if (!$this->ProductDiscussions->delete($entity)) {
                    $this->setResponse($this->response->withStatus(406, 'Gagal menghapus komentar'));
                }
            }

        }

    }

}