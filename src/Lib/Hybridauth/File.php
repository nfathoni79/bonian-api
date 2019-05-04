<?php
/**
 * Created by PhpStorm.
 * User: ridwan
 * Date: 04/05/2019
 * Time: 2:23
 */

namespace App\Lib\Hybridauth;

use Hybridauth\Exception\RuntimeException;
use Hybridauth\Storage\StorageInterface;
use Cake\Cache\Cache;


class File implements StorageInterface
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

    public function clear()
    {

        //Cache::clear(true, 'oauth');
    }

    public function delete($key)
    {
        $cache = Cache::read($this->key, 'oauth');
        unset($cache[$key]);
        Cache::write($this->key, $cache, 'oauth');
    }

    public function deleteMatch($key)
    {
        $cache = Cache::read($this->key, 'oauth');
        foreach($cache as $k => $v) {
            if (preg_match('/' . preg_quote($key) . '/i', $k)) {
                unset($cache[$v]);
            }
        }

        Cache::write($this->key, $cache, 'oauth');
    }
}