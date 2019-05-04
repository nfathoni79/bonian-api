<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 04/05/2019
 * Time: 2:23
 */

namespace App\Lib\SocialConnect;

use Hybridauth\Exception\RuntimeException;
use Cake\Cache\Cache;
use SocialConnect\Provider\Session\SessionInterface;


class File implements SessionInterface
{
    protected $key = 'default';

    public function __construct($key = null)
    {
        if ($key) {
            $this->key = $key;
        }
    }

    public function set($key, $value)
    {
        $cache = Cache::read($this->key, 'oauth');
        $cache[$key] = $value;

        Cache::write($this->key, $cache, 'oauth');

    }

    public function get($key)
    {
        $cache = Cache::read($this->key, 'oauth');
        return isset($cache[$key]) ? $cache[$key] : null;
    }


    public function delete($key)
    {
        $cache = Cache::read($this->key, 'oauth');
        unset($cache[$key]);
        Cache::write($this->key, $cache, 'oauth');
    }

}