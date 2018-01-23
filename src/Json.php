<?php declare(strict_types=1);

namespace WyriHaximus\React\Cache;

use React\Cache\CacheInterface;
use React\Promise\PromiseInterface;
use function ExceptionalJSON\decode;
use function ExceptionalJSON\encode;

final class Json implements CacheInterface
{
    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param  string           $key
     * @return PromiseInterface
     */
    public function get($key)
    {
        return $this->cache->get($key)->then(function ($result) {
            return decode($result, true);
        });
    }

    /**
     * @param  string           $key
     * @param  mixed            $value
     * @return PromiseInterface
     */
    public function set($key, $value)
    {
        return $this->cache->set($key, encode($value));
    }

    /**
     * @param  string           $key
     * @return PromiseInterface
     */
    public function remove($key)
    {
        return $this->cache->remove($key);
    }
}
