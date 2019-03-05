<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ProductCategories Controller
 *
 * @property \App\Model\Table\ProductCategoriesTable $ProductCategories
 *
 * @method \App\Model\Entity\ProductCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProductCategoriesController extends AppController
{

    public function index()
    {
        $this->autoRender = false;
        $response =$this->ProductCategories
            ->find('threaded')
        ;
        echo json_encode($response);
        exit;
//        $this->setResponse($this->response->withStatus(200));
//        $this->set(compact('response'));
    }

}
