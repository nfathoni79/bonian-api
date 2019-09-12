<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\Locator\TableLocator;

/**
 * Tools component
 */
class ToolsComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * @var \App\Model\Table\IpLocationsTable $IpLocations
     */
    protected $IpLocations = null;

    public function initialize(array $config)
    {

    }

    public function maskPhone($number)
    {
        if (!empty($number) && is_string($number) && ($len = strlen($number)) > 9) {
            return substr($number, 0, 6) .
                str_repeat('*', $len - (6 + 3)) .
                substr($number, -3);
        }

        return $number;
    }

    public function decrypt($hex)
    {
        return openssl_decrypt(pack('H*', $hex), 'aes-256-ctr', '123a56f890abcaef1234567d9eabcd5f', OPENSSL_RAW_DATA, '1a3f5d78e0ab4def');
    }

    public function encrypt($text)
    {
        return bin2hex(openssl_encrypt($text, 'aes-256-ctr', '123a56f890abcaef1234567d9eabcd5f', OPENSSL_RAW_DATA, '1a3f5d78e0ab4def'));
    }


    public function itemSoldCount(\App\Model\Table\OrderDetailProductsTable $OrderDetailProducts, $product_id)
    {
        $d = $OrderDetailProducts->find();
        $d = $d
            ->select([
                'sold' => $d->func()->sum('OrderDetailProducts.qty')
            ])
            ->contain([
                'OrderDetails'
            ])
            ->where([
                'OrderDetails.order_status_id IN' => ['2','3','4'],
                'OrderDetailProducts.product_id' => $product_id,
            ])
            ->first();

        if ($d) {
            return (int) $d->get('sold');
        }
        return 0;
    }


    public function reffcode($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'){
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            try {
                $pieces []= $keyspace[random_int(0, $max)];
            } catch(\Exception $e) {}
        }
        return implode('', $pieces);
    }

    public function getIpLocation($ip)
    {
        return  json_decode(file_get_contents(
            'https://api.ipdata.co/'.$ip.'?api-key=d3941e87e91ccde61c9a9d0a488f3ceee2cead61fabfaa2de8087e64'
        ), true);
    }

    public function initialTableIpLocation()
    {
        $this->IpLocations = (new TableLocator ())->get('IpLocations');
        return $this;
    }

    public function saveIpLocation($ip)
    {

        if ($ip && !in_array($ip, ['::1', '127.0.0.1']) && $this->IpLocations instanceof \Cake\ORM\Table) {
            $exists = $this->IpLocations->find()
                ->where([
                    'ip' => $ip
                ])
                ->count();

            if ($exists == 0) {
                $ipEntity = $this->getIpLocation($ip);
                if ($ip && isset($ipEntity['ip'])) {
                    if (is_array($ipEntity['asn'])) {
                        $asn = $ipEntity['asn'];
                        $ipEntity['asn'] = isset($asn['asn']) ? $asn['asn'] : null ;
                        $ipEntity['organisation'] = isset($asn['name']) ? $asn['name'] : null ;
                    }
                    $ipEntity = $this->IpLocations->newEntity($ipEntity);
                    return $this->IpLocations->save($ipEntity);
                }
            }
        }
    }



}
