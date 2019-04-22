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
    protected $provider = [
        'Provider' => [
            'Telkomsel' => [
                'prefix' => [
                    1 => '0811',
                    2 => '0812',
                    3 => '0813',
                    4 => '0821',
                    5 => '0822',
                    6 => '0823',
                    7 => '0852',
                    8 => '0853',
                ],
                'logo' => 'simpati_2.png'
            ],
            'Indosat' => [
                'prefix' => [
                    1 => '0814',
                    2 => '0815',
                    3 => '0816',
                    4 => '0855',
                    5 => '0858',
                    6 => '0856',
                    7 => '0857',
                ],
                'logo' => 'mentari_2.png'
            ],
            'XL' => [
                'prefix' => [
                    1 => '0817',
                    2 => '0818',
                    3 => '0819',
                    4 => '0877',
                    5 => '0878',
                    6 => '0879',
                ],
                'logo' => 'xl_3.png'
            ],
            'Smartfren' => [
                'prefix' => [
                    1 => '0881',
                    2 => '0882',
                    3 => '0883',
                    4 => '0884',
                    5 => '0887',
                    6 => '0888',
                    7 => '0889',
                ],
                'logo' => 'smartfren_3.png'
            ],
            'Tri' => [
                'prefix' => [
                    1 => '0896',
                    2 => '0897',
                    3 => '0898',
                    4 => '0899',
                ],
                'logo' => 'tri_2.png'
            ],
            'Axis' => [
                'prefix' => [
                    1 => '0831',
                    2 => '0838',
                ],
                'logo' => 'axis_2.png'
            ]
        ]
    ];

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Digitals');
        $this->loadModel('DigitalDetails');
    }

    private function searchProvider($prefix){
        foreach ($this->provider['Provider'] as $key => $val) {
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
                        $row->point = '300';
                        unset($row->digital_id);
                        unset($row->status);
                        unset($row->id);
                        return $row;
                    })
                    ->toArray();

                $data = ['provider' => $provider, 'logo' => $this->provider['Provider'][$provider]['logo'],'options' => $pulsa ];
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