<?php

declare(strict_types=1);

namespace WyriHaximus\React\Cache;

use React\Cache\CacheInterface;
use React\Promise\PromiseInterface;

use function ExceptionalJSON\decode;
use function ExceptionalJSON\encode;

final class Json implements CacheInterface
{
    private CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
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
         * @psalm-suppress TooManyTemplateParams
         * @phpstan-ignore-next-line
         */
        return $this->cache->get($key, $default)->then(static function (?string $result) use ($default) {
            if ($result === null || $result === $default) {
                return $result;
            }

            return decode($result, true);
        });
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function set($key, $value, $ttl = null): PromiseInterface
    {
        /**
         * @psalm-suppress TooManyTemplateParams
         */
        return $this->cache->set($key, encode($value), $ttl);
    }

    /**
     * @inheritDoc
     */
    public function delete($key): PromiseInterface
    {
        /**
         * @psalm-suppress TooManyTemplateParams
         */
        return $this->cache->delete($key);
    }

    /**
     * @inheritDoc
     * @phpstan-ignore-next-line
     */
    public function getMultiple(array $keys, $default = null)
    {
        /**
         * @psalm-suppress TooManyTemplateParams
         */
        return $this->cache->getMultiple($keys, $default)->then(static function (array $results) use ($default): array {
            foreach ($results as $key => $result) {
                if ($result === null || $result === $default) {
                    continue;
                }

                $results[$key] = decode($result, true);
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

        /**
         * @psalm-suppress TooManyTemplateParams
         */
        return $this->cache->setMultiple($values, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function deleteMultiple(array $keys)
    {
        /**
         * @psalm-suppress TooManyTemplateParams
         */
        return $this->cache->deleteMultiple($keys);
    }

    /**
     * @inheritDoc
     */
    public function clear()
    {
        /**
         * @psalm-suppress TooManyTemplateParams
         */
        return $this->cache->clear();
    }

    /**
     * @inheritDoc
     */
    public function has($key)
    {
        /**
         * @psalm-suppress TooManyTemplateParams
         */
        return $this->cache->has($key);
    }
}
