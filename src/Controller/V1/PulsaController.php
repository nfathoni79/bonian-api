<?php
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;
use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\Validation\Validator;
/**
 * Categories Controller
 *
 * @property \App\Model\Table\DigitalsTable $Digitals
 * @property \App\Model\Table\DigitalDetailsTable $DigitalDetails
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PulsaController extends Controller
{
    protected $provider = [];

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Digitals');
        $this->loadModel('DigitalDetails');
        $this->provider = Configure::read('Provider');
    }

    private function searchProvider($prefix){
        foreach ($this->provider as $key => $val) {
            foreach($val['prefix'] as $k => $v){
                if(preg_match('/^'.$v.'\d{6,11}/i', $prefix, $output_array)){
                    return $key;
                }
            }
        }
        return null;
    }

    public function provider()
    {
        $this->request->allowMethod('post');
        $validator = new Validator();
        $validator
            ->requirePresence('phone')
            ->notEmpty('phone', 'Nomor telpon tidak boleh kosong')
            ->numeric('phone','Nomor hanya boleh mengandung angka')
            ->maxLength('phone', '14','Nomor terlalu panjang, maksimal 14 karakter')
            ->minLength('phone','10','Nomor terlalu pendek, minimal 10 karakter')
            ->regex('phone', '/^08\d{8,11}/i', 'Nomor hanya boleh mengandung angka');

        $errors = $validator->errors($this->request->getData());
        if (empty($errors)) {
            $provider = $this->searchProvider($this->request->getData('phone'));
            if(!empty($provider)){

                $pulsa = $this->DigitalDetails->find()
                    ->where(['operator' => $provider,'status' => 1])
                    ->all()
                    ->map(function (\App\Model\Entity\DigitalDetail $row){
                        unset($row->digital_id);
                        unset($row->status);
                        unset($row->id);
                        return $row;
                    })
                    ->toArray();

                $data = ['provider' => $provider, 'logo' => $this->provider[$provider]['logo'],'options' => $pulsa ];
                $this->set(compact('data'));
            }else{
                $this->setResponse($this->response->withStatus(406, 'Provider tidak terdaftar'));
                $error = $errors;
            }
        }else {
            $this->setResponse($this->response->withStatus(406, 'Format nomor salah'));
            $error = $errors;
        }

        $this->set(compact('error'));

    }

}