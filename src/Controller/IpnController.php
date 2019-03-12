<?php
namespace App\Controller;

use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Log\Log;


/**
 * Static content controller
 *
 * This controller will render views from Template/Ipn/
 * 
 */
class IpnController extends AppController
{

    public function initialize()
    { 
        $config = Configure::read('Midtrans');
        $this->merchant_id = $config['merchantid'];
        $this->client_key = $config['clientKey'];
        $this->server_key = $config['serverKey'];
    }

	public function index(){
		
        if ($this->request->is('post')) { 
            Log::info($this->request->getBody()->getContents(), ['scope' => ['midtrans']]);
		}
	}
	
	
	 

}
