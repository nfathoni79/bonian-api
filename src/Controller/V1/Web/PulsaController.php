<?php
namespace App\Controller\V1\Web;

use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\Validation\Validator;
/**
 * Categories Controller
 *
 * @property \App\Model\Table\DigitalsTable $Digitals
 * @property \App\Model\Table\DigitalDetailsTable $DigitalDetails
 * @property \App\Model\Table\CustomerDigitalInquiryTable $CustomerDigitalInquiry
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PulsaController extends AppController
{
    protected $provider = [];

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Digitals');
        $this->loadModel('DigitalDetails');
        $this->loadModel('CustomerDigitalInquiry');
    }

    public function createInquiry()
    {
        $this->request->allowMethod('post');

        $validator = new Validator();

        $validator->requirePresence('customer_number')
            ->requirePresence('code');

        $error = $validator->errors($this->request->getData());

        if (!$error) {

            $dataEntity = $this->CustomerDigitalInquiry->find()
                ->where([
                    'customer_id' => $this->Authenticate->getId(),
                    'customer_number' => $this->request->getData('customer_number'),
                    'status' => 0
                ])
                ->orderDesc('CustomerDigitalInquiry.id')
                ->first();

            if ($dataEntity) {
                $entity = $this->CustomerDigitalInquiry->patchEntity($dataEntity, $this->request->getData(), [
                    'fieldList' => [
                        'customer_number',
                        'code'
                    ]
                ]);
            } else {
                $entity = $this->CustomerDigitalInquiry->newEntity([
                    'customer_id' => $this->Authenticate->getId(),
                    'customer_number' => $this->request->getData('customer_number'),
                    'code' => $this->request->getData('code'),
                    'raw_request' => json_encode($this->request->getData()),
                    'status' => 0
                ]);
            }



            if (!$this->CustomerDigitalInquiry->save($entity)) {
                $error = $entity->getErrors();
            } else {
                $data = [
                    'id' => $entity->get('id')
                ];
            }
        }

        $this->set(compact('data', 'error'));
    }

}