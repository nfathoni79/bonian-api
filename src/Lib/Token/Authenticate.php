<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 15/01/2019
 * Time: 20:28
 */

namespace App\Lib\Token;

use App\Exception\InvalidTokenException;
use App\Exception\MissingTokenException;
use App\Exception\InvalidTokenFormatException;
use Cake\Utility\Security;
use Cake\Core\Configure;


class Authenticate
{
    protected $id, $email, $token;

    /**
     * Authenticate constructor.
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function __construct(\Psr\Http\Message\ServerRequestInterface &$request)
    {
        $bearer = $request->getHeader('Authorization');
        if (count($bearer) > 0) {
            $bearer = $bearer[0];
            $parts = preg_split('/\s+/i', $bearer);

            if (count($parts) < 2 || empty($parts[0]) || !preg_match('/^Bearer$/i', $parts[0])) {
                throw new InvalidTokenFormatException();
            } else if (count($parts) >= 3) {
                throw new InvalidTokenFormatException();
            }

            $decrypt = Security::decrypt(base64_decode($parts[1]), Configure::read('Encrypt.salt'));
            if ($decrypt) {
                $decrypt = json_decode($decrypt, true);
                if ($decrypt) {
                    $this->id = $decrypt['id'];
                    $this->email = $decrypt['email'];
                    $this->token = $decrypt['token'];
                    $request = $request->withAttribute('authorization', $this);
                }

            } else {
                throw new InvalidTokenException();
            }

        } else {
            throw new MissingTokenException();
        }

    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getToken()
    {
        return $this->token;
    }
}