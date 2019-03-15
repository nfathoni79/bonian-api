<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;

/**
 * GenerationsTree component
 * @property \App\Model\Table\CustomersTable $Customers
 * @property \App\Model\Table\GenerationsTable $Generations
 */
class GenerationsTreeComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];


    public function initialize(array $config)
    {
        $this->loadModel('Customers');
        $this->loadModel('Generations');
    }

    private function loadModel($model) {
        $this->$model = TableRegistry::get($model);
    }

    public function save($reffNewUser,$reffSponsor) {

        $sponsor = $this->Customers->find()->where(['reffcode' => $reffSponsor])->first();
        $customers = $this->Customers->find()->where(['reffcode' => $reffNewUser])->first();
        if($customers){

            $findSponsorParent = $this->Generations
                ->find()
                ->where(['customer_id' => $sponsor->get('id')])
                ->order(['id' => 'ASC'])
                ->first();
            if($findSponsorParent){
                $parentIds = $findSponsorParent->get('id');
            }else{
                $parentIds = 'NULL';
            }
            $customerId = $customers->get('id');
            $sponsorId = $sponsor->get('id');

            $entity = $this->Generations->newEntity([
                'parent_id' => $parentIds,
                'customer_id' => $customerId,
                'refferal_id' => $sponsorId,
                'level' => 1
            ]);
            if($this->Generations->save($entity)) {

                $customers->set('refferal_customer_id', $sponsorId);
                if($this->Customers->save($customers)){

                    $findSponsor = $this->Generations
                        ->find()
                        ->select(['refferal_id' , 'level','parent_id','id'])
                        ->where(['customer_id' => $sponsorId])
                        ->all()
                        ->toArray();

                    foreach ($findSponsor as $vals){
                        $lvl = intval($vals['level']) + 1;
                        $reffClientId = $vals['refferal_id'];
                        $parentId = $vals['id'];

                        if ($lvl <= 10) {
                            $newEntity = $this->Generations->newEntity([
                                'parent_id' => $parentId,
                                'customer_id' => $customerId,
                                'refferal_id' => $reffClientId,
                                'level' => $lvl
                            ]);
                            $this->Generations->save($newEntity);
                        }
                    }
                }

            }
        }

    }
}
