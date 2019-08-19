<?php
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\ProductCategoriesTable $ProductCategories
 * @method \App\Model\Entity\Category[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesController extends Controller
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('ProductCategories');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $categories = $this->ProductCategories->find('threaded')
            ->select(['id','parent_id','name', 'slug','path'])
            ->map(function (\App\Model\Entity\ProductCategory $row) {
                if(is_array($row['children'])){
                    foreach ($row['children'] as $key => $vals){
//                        unset($row->children[$key]->id);
                        unset($row->children[$key]->parent_id);
//                        unset($row->children[$key]->path);
                        if(is_array($vals['children'])){
                            foreach ($vals['children'] as $k => $v){
//                                unset($row->children[$key]->children[$k]->id);
                                unset($row->children[$key]->children[$k]->parent_id);
                                unset($row->children[$key]->children[$k]->children);
                                unset($row->children[$key]->children[$k]->path);
                            }
                        }
                    }
                }
                //unset($row->id);
                unset($row->parent_id);
                return $row;
            })
        ;
        $this->set(compact('categories'));
    }

    public function view($parent_id = null)
    {
        $categories = $this->ProductCategories->find()
            ->select(['id','parent_id','name', 'slug','path'])
            ->where(function(\Cake\Database\Expression\QueryExpression $exp) use($parent_id) {
                return !$parent_id ?$exp->isNull('parent_id') : $exp->eq('parent_id', $parent_id);
            });

        $this->set(compact('categories'));
    }

}
