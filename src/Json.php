<?php

declare(strict_types=1);

namespace WyriHaximus\React\Cache;

use React\Cache\CacheInterface;
use React\Promise\PromiseInterface;

use function ExceptionalJSON\decode;
use function ExceptionalJSON\encode;

final readonly class Json implements CacheInterface
{
    public function __construct(private CacheInterface $cache)
    {
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function get($key, $default = null): PromiseInterface
    {
        /**
         * @return ?mixed
         *
         * @phpstan-ignore-next-line
         */
        return $this->cache->get($key, $default)->then(static function (string|null $result) use ($default) {
            if ($result === null || $result === $default) {
                return $result;
            }

            /** @phpstan-ignore shipmonk.checkedExceptionInCallable */
            return decode($result, true);
        });
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function set($key, $value, $ttl = null): PromiseInterface
    {
        return $this->cache->set($key, encode($value), $ttl);
    }

    /** @inheritDoc */
    public function delete($key): PromiseInterface
    {
        return $this->cache->delete($key);
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function getMultiple(array $keys, $default = null)
    {
        return $this->cache->getMultiple($keys, $default)->then(static function (array $results) use ($default): array {
            foreach ($results as $key => $result) {
                if ($result === null || $result === $default) {
                    continue;
                }

                $results[$key] = decode($result, true); /** @phpstan-ignore argument.type */
            }

            return $results;
        });
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function setMultiple(array $values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            $values[$key] = encode($value);
        }

        return $this->cache->setMultiple($values, $ttl);
    }

    /** @inheritDoc */
    public function deleteMultiple(array $keys)
    {
        return $this->cache->deleteMultiple($keys);
    }

    /** @inheritDoc */
    public function clear()
    {
        return $this->cache->clear();
    }

    /** @inheritDoc */
    public function has($key)
    {
        return $this->cache->has($key);
    }
}
