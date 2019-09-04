<?php


namespace App\Lib\SocialConnect;

use SocialConnect\Provider\AccessTokenInterface;

class AccessToken implements AccessTokenInterface
{

    /**
     * @var string
     */
    protected $token;

    /**
     * @var int|null
     */
    protected $expires;

    /**
     * @var integer|null
     */
    protected $uid;

    public function __construct($provider, $access_token)
    {

        switch ($provider) {
            case 'google':
                $data = @file_get_contents('https://www.googleapis.com/oauth2/v3/tokeninfo?access_token=' . $access_token);
                if ($data) {
                    $data = json_decode($data, true);
                    if ($data && isset($data['sub'])) {
                        $this->token = $access_token;
                        $this->uid = $data['sub'];
                        $this->expires = $data['exp'];
                    }
                }
            break;
            case 'facebook':
                $data = @file_get_contents('https://graph.facebook.com/me?access_token=' . $access_token);
                if ($data) {
                    $data = json_decode($data, true);
                    if ($data && isset($data['id'])) {
                        $this->token = $access_token;
                        $this->uid = $data['id'];
                        $this->expires = null;
                    }

                }

                break;
        }
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return integer
     */
    public function getUserId()
    {
        return $this->uid;
    }

    /**
     * @return int|null
     */
    public function getExpires()
    {
        return $this->expires;
    }
}