<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 14/03/2019
 * Time: 15:31
 */

namespace App\Lib\MidTrans;

use Cake\Log\Log;

class CreditCardTokenResponse
{
    public $status_code = null;
    public $status_message = null;
    public $validation_messages = null;
    public $redirect_url = null;
    public $token_id = null;
    /**
     * The 3DS status indicator 05
     * @var null
     */
    public $eci = null;

    public function __construct(\Psr\Http\Message\ResponseInterface $object)
    {

        if ($object->getStatusCode() == 200) {
            $content = $object->getBody()->getContents();
            Log::info($content, ['scope' => ['midtrans']]);
            $json = json_decode($content, true);
            if ($json) {
                foreach($json as $key => $val) {
                    if ($key == 'status_code') {
                        $val = (int) $val;
                    }
                    $this->{$key} = $val;
                }
            }
        }
    }

    public function toArray()
    {
        return array_filter(get_object_vars($this));
    }
}