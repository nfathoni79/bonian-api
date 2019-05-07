<?php
namespace App\Controller;

use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Log\Log;


/**
 * Sepulsa callback url controller
 *  @property \App\Model\Table\OrdersTable $Orders
 *  @property \App\Model\Table\TransactionsTable $Transactions
 * This controller will render views from Template/Ipn/
 * 
 */
class SepulsaController extends AppController
{

    public function initialize()
    {
        $this->loadModel('Orders');
        $this->loadModel('Transactions');
    }


    /**
     * sepulsa callback URL
     */
	public function index()
    {
		$this->disableAutoRender();
        Log::info(json_encode($this->request->getData()), ['scope' => ['sepulsa']]);
	}
	

}
