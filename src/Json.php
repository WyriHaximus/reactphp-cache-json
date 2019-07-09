<?php declare(strict_types=1);

namespace WyriHaximus\React\Cache;

use function ExceptionalJSON\decode;
use function ExceptionalJSON\encode;
use React\Cache\CacheInterface;
use React\Promise\PromiseInterface;

final class Json implements CacheInterface
{
    /** @var CacheInterface */
    private $cache;

    /**
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param  string           $key
     * @param  null             $default
     * @return PromiseInterface
     */
    public function get($key, $default = null)
    {
        return $this->cache->get($key, $default)->then(function ($result) use ($default) {
            if ($result === null || $result === $default) {
                return $result;
            }

            return decode($result, true);
        });
    }

    /**
     * @param  string           $key
     * @param  mixed            $value
     * @param  null             $ttl
     * @return PromiseInterface
     */
    public function set($key, $value, $ttl = null)
    {
        return $this->cache->set($key, encode($value), $ttl);
    }

    /**
     * @param  string           $key
     * @return PromiseInterface
     */
    public function delete($key)
    {
        return $this->cache->delete($key);
    }

    public function getMultiple(array $keys, $default = null)
    {
        return $this->cache->getMultiple($keys, $default)->then(function ($results) use ($default) {
            foreach ($results as $key => $result) {
                if ($result === null || $result === $default) {
                    continue;
                }

                $results[$key] = decode($result, true);
            }

            return $results;
        });
    }

    public function setMultiple(array $values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            $values[$key] = encode($value);
        }

        return $this->cache->setMultiple($values, $ttl);
    }

    public function deleteMultiple(array $keys)
    {
        return $this->cache->deleteMultiple($keys);
    }

    public function clear()
    {
        return $this->cache->clear();
    }

    public function has($key)
    {
        return $this->cache->has($key);
    }
}
