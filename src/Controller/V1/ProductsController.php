<?php
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductsController extends Controller
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Products');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index($slug = null)
    {
        $product = $this->Products->find()
            ->select()
            ->where(['Products.slug' => $slug]);
        $this->set(compact('product'));
    }

}
