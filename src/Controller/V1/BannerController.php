<?php
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;
use Cake\Utility\Hash;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\ProductsTable $Products
 * @property \App\Model\Table\BannersTable $Banners
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BannerController extends Controller
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Banners');

    }


    public function top(){
        $banner = $this->Banners->find()
            ->select([
                'image' => 'Banners.name',
                'url' => 'Banners.url',
            ])
            ->where(['Banners.status' => 1, 'Banners.position' => 'Home Top'])
            ->all();
        $this->set(compact('banner'));
    }

    public function bleft(){
        $banner = $this->Banners->find()
            ->select([
                'image' => 'Banners.name',
                'url' => 'Banners.url',
            ])
            ->where(['Banners.status' => 1, 'Banners.position' => 'Home Bottom Left'])
            ->order('Banners.created DESC')
            ->first();
        $this->set(compact('banner'));
    }

    public function bright(){
        $banner = $this->Banners->find()
            ->select([
                'image' => 'Banners.name',
                'url' => 'Banners.url',
            ])
            ->where(['Banners.status' => 1, 'Banners.position' => 'Home Bottom Right'])
            ->order('Banners.created DESC')
            ->first();
        $this->set(compact('banner'));
    }

    public function promotion(){
        $banner = $this->Banners->find()
            ->select([
                'image' => 'Banners.name',
                'url' => 'Banners.url',
            ])
            ->where(['Banners.status' => 1, 'Banners.url LIKE ' => '%'.$this->request->getData('slug').'%'])
            ->order('Banners.created DESC')
            ->first();
        $this->set(compact('banner'));
    }


}