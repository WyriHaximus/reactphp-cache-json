<?php declare(strict_types=1);

namespace WyriHaximus\Tests\React\Cache;

use ApiClients\Tools\TestUtilities\TestCase;
use React\Cache\CacheInterface;
use WyriHaximus\React\Cache\Json;
use function React\Promise\resolve;

final class JsonTest extends TestCase
{
    public function testGet()
    {
        $key = 'sleutel';
        $string = '{"foo":"bar"}';
        $json = [
            'foo' => 'bar',
        ];

        $cache = $this->prophesize(CacheInterface::class);
        $cache->get($key)->shouldBeCalled()->willReturn(resolve($string));

        $jsonCache = new Json($cache->reveal());
        self::assertSame($json, $this->await($jsonCache->get($key)));
    }

    public function testSet()
    {
        $key = 'sleutel';
        $string = '{"foo":"bar"}';
        $json = [
            'foo' => 'bar',
        ];

        $cache = $this->prophesize(CacheInterface::class);
        $cache->set($key, $string)->shouldBeCalled();

        $jsonCache = new Json($cache->reveal());
        $jsonCache->set($key, $json);
    }

    public function testRemove()
    {
        $key = 'sleutel';

        $cache = $this->prophesize(CacheInterface::class);
        $cache->remove($key)->shouldBeCalled();

        $jsonCache = new Json($cache->reveal());
        $jsonCache->remove($key);
    }
}
