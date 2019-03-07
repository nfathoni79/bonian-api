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
        $categories = $this->ProductCategories->find('threaded')->toArray();
        debug($categories);
        exit;
        $this->set(compact('categories'));
    }

}
