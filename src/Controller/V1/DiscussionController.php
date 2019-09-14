<?php
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;
use Cake\Utility\Hash;
use function PHPSTORM_META\map;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\ProductDiscussionsTable $ProductDiscussions
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class DiscussionController extends Controller
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('ProductDiscussions');

    }

    public function index(){

        /*$data = $this->ProductDiscussions->find('threaded')
            ->contain([
                'Customers' => ['fields' => ['avatar','first_name','last_name','email']],
                'Users' => ['fields' => ['first_name']]
            ])
            ->where(['ProductDiscussions.product_id' => $this->request->getData('product_id')]);
        $data = $data->orderAsc('ProductDiscussions.id');*/

        $data = $this->ProductDiscussions->find()
            ->contain([
                'Customers' => ['fields' => ['avatar','first_name','last_name','email']],
                'Users' => ['fields' => ['first_name']]
            ])
            ->where(function(\Cake\Database\Expression\QueryExpression $q) {
                return $q->isNull('parent_id');
            })
            ->where(['ProductDiscussions.product_id' => $this->request->getData('product_id')]);
        $data = $data->orderAsc('ProductDiscussions.id');

        $data = $this->paginate($data, [
            'limit' => (int) $this->request->getQuery('limit', 5)
        ])
        ->map(function(\App\Model\Entity\ProductDiscussion $row) {
            $row->children = $this->ProductDiscussions->find()
                ->contain([
                    'Customers' => ['fields' => ['avatar','first_name','last_name','email']],
                    'Users' => ['fields' => ['first_name']]
                ])
                ->where([
                    'ProductDiscussions.parent_id' => $row->id
                ])
                ->map(function(\App\Model\Entity\ProductDiscussion $row) {
                    $row->children = $this->ProductDiscussions->find()
                        ->contain([
                            'Customers' => ['fields' => ['avatar','first_name','last_name','email']],
                            'Users' => ['fields' => ['first_name']]
                        ])
                        ->where([
                            'ProductDiscussions.parent_id' => $row->id
                        ])
                        ->toArray();

                    return $row;
                })
                ->toArray();

            return $row;
        });


        $this->set(compact('data'));
    }

}