<?php declare(strict_types=1);

namespace WyriHaximus\Tests\React\Cache;

use ApiClients\Tools\TestUtilities\TestCase;
use React\Cache\CacheInterface;
use function React\Promise\resolve;
use WyriHaximus\React\Cache\Json;

/**
 * @internal
 */
final class JsonTest extends TestCase
{
    public function testGet(): void
    {
        $key = 'sleutel';
        $string = '{"foo":"bar"}';
        $json = [
            'foo' => 'bar',
        ];

        $cache = $this->prophesize(CacheInterface::class);
        $cache->get($key, null)->shouldBeCalled()->willReturn(resolve($string));

        $jsonCache = new Json($cache->reveal());
        self::assertSame($json, $this->await($jsonCache->get($key)));
    }

    public function testGetNullShouldBeIgnored(): void
    {
        $key = 'sleutel';

        $cache = $this->prophesize(CacheInterface::class);
        $cache->get($key, null)->shouldBeCalled()->willReturn(resolve(null));

        $jsonCache = new Json($cache->reveal());
        self::assertNull($this->await($jsonCache->get($key)));
    }

    public function testSet(): void
    {
        $key = 'sleutel';
        $string = '{"foo":"bar"}';
        $json = [
            'foo' => 'bar',
        ];

        $cache = $this->prophesize(CacheInterface::class);
        $cache->set($key, $string, null)->shouldBeCalled();

        $jsonCache = new Json($cache->reveal());
        $jsonCache->set($key, $json);
    }

    public function testDelete(): void
    {
        $key = 'sleutel';

        $cache = $this->prophesize(CacheInterface::class);
        $cache->delete($key)->shouldBeCalled();

        $jsonCache = new Json($cache->reveal());
        $jsonCache->delete($key);
    }
}
