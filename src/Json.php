<?php

declare(strict_types=1);

namespace WyriHaximus\React\Cache;

use React\Cache\CacheInterface;
use React\Promise\PromiseInterface;

use function ExceptionalJSON\decode;
use function ExceptionalJSON\encode;

/** @api */
final readonly class Json implements CacheInterface
{
    public function __construct(private CacheInterface $cache)
    {
    }

    /**
     * @inheritDoc
     * @phpstan-ignore ergebnis.noParameterWithNullDefaultValue
     */
    public function get($key, $default = null): PromiseInterface
    {
        /** @return ?mixed */
        return $this->cache->get($key, $default)->then(static function (mixed $result) use ($default): mixed {
            if ($result === null || $result === $default) {
                return $result;
            }

            /** @phpstan-ignore argument.type */
            return decode($result, true);
        });
    }

    /**
     * @inheritDoc
     * @phpstan-ignore ergebnis.noParameterWithNullDefaultValue
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
     * @param array<string> $keys
     *
     * @inheritDoc
     * @phpstan-ignore typeCoverage.returnTypeCoverage,ergebnis.noParameterWithNullDefaultValue,missingType.iterableValue,shipmonk.missingNativeReturnTypehint
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
     * @param array<mixed, mixed> $values
     *
     * @inheritDoc
     * @phpstan-ignore typeCoverage.returnTypeCoverage,ergebnis.noParameterWithNullDefaultValue,shipmonk.missingNativeReturnTypehint
     */
    public function setMultiple(array $values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            $values[$key] = encode($value);
        }

        return $this->cache->setMultiple($values, $ttl);
    }

    /**
     * @inheritDoc
     * @phpstan-ignore typeCoverage.returnTypeCoverage,shipmonk.missingNativeReturnTypehint
     */
    public function deleteMultiple(array $keys)
    {
        return $this->cache->deleteMultiple($keys);
    }

    /**
     * @inheritDoc
     * @phpstan-ignore typeCoverage.returnTypeCoverage,shipmonk.missingNativeReturnTypehint
     */
    public function clear()
    {
        return $this->cache->clear();
    }

    /**
     * @inheritDoc
     * @phpstan-ignore typeCoverage.returnTypeCoverage,shipmonk.missingNativeReturnTypehint
     */
    public function has($key)
    {
        return $this->cache->has($key);
    }
}
