<?php
namespace App\Controller\Component;

use Cake\Controller\Component;


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
        return openssl_decrypt( pack('H*', $hex), 'aes-256-ctr', '123a56f890abcaef1234567d9eabcd5f', OPENSSL_RAW_DATA, '1a3f5d78e0ab4def');
    }

    public function encrypt($text)
    {
        return bin2hex(openssl_encrypt($text, 'aes-256-ctr', '123a56f890abcaef1234567d9eabcd5f', OPENSSL_RAW_DATA, '1a3f5d78e0ab4def'));
    }



}
