<?php
namespace App\Controller\V1;

use App\Controller\V1\AppController as Controller;
use Cake\Utility\Hash;
use Cake\I18n\Time;
use Cake\ORM\Query;
/**
 * Categories Controller
 *
 * @property \App\Model\Table\VouchersTable $Vouchers
 * @property \App\Model\Table\VoucherDetailsTable $VoucherDetails
 * @property \App\Model\Table\ProductDealDetailsTable $ProductDealDetails
 * @method \App\Model\Entity\Product[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PointRedeemController extends Controller
{
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Vouchers');
    }

    public function index()
    {
        $find = $this->Vouchers->find()
            ->select([
                'id',
                'name',
                'code_voucher',
                'point',
                'percent',
                'value',
            ])
            ->where(['Vouchers.status' => '1', 'Vouchers.type' => '1' , ])
            ->all();


        $data = $find;

        $this->set(compact('data'));
    }

}