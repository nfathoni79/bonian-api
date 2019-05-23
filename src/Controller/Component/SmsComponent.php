<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\Utility\Xml;
use SimpleXMLElement;
use Cake\Log\Log;

/**
 * Sms component
 */
class SmsComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function initialize(array $config)
    {
        $config = Configure::read('SmsApi');
        $this->url = $config['url'];
        $this->user_key = $config['userkey'];
        $this->pass_key = $config['passkey'];

    }


    public function send($phone, $message){
        $http = new Client();

        $response = $http->post($this->url, [
            'userkey' => $this->user_key,
            'passkey' => $this->pass_key,
            'nohp' => $phone,
            'pesan' => $message,
        ]);


        Log::info($response->getBody()->getContents(), ['scope' => ['sms']]);

        /*
            0 Success
            1 Nomor tujuan tidak valid.
            5 Userkey / Passkey salah.
            6 Konten SMS rejected.
            89 Pengiriman SMS berulang-ulang ke satu nomor dalam satu waktu.
            99 Credit tidak mencukupi.
        */

//        $status = [
//            '0' => 'Success',
//            '1' => 'Nomor tujuan tidak valid',
//            '5' => 'Not valid',
//            '6' => 'Konten SMS rejected',
//            '89' => 'Not valid',
//            '99' => 'Not valid',
//        ];

//        $result = $response->getBody()->getContents();
//        $XMLdata = new SimpleXMLElement($result);

    }


}
