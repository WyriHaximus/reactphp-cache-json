<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\React\Cache;

use Mockery;
use PHPUnit\Framework\Attributes\Test;
use React\Cache\CacheInterface;
use WyriHaximus\AsyncTestUtilities\AsyncTestCase;
use WyriHaximus\React\Cache\Json;

use function current;
use function React\Async\await;
use function React\Promise\resolve;

final class JsonTest extends AsyncTestCase
{
    #[Test]
    public function get(): void
    {
        $key    = 'sleutel';
        $string = '{"foo":"bar"}';
        $json   = ['foo' => 'bar'];

        $cache = Mockery::mock(CacheInterface::class);
        $cache->expects('get')->with($key, null)->atLeast()->once()->andReturn(resolve($string));

        $jsonCache = new Json($cache);
        self::assertSame($json, await($jsonCache->get($key)));
    }

    #[Test]
    public function getNullShouldBeIgnored(): void
    {
        $key = 'sleutel';

        $cache = Mockery::mock(CacheInterface::class);
        $cache->expects('get')->with($key, null)->atLeast()->once()->andReturn(resolve(null));

        $jsonCache = new Json($cache);
        self::assertNull(await($jsonCache->get($key)));
    }

    #[Test]
    public function set(): void
    {
        $key    = 'sleutel';
        $string = '{"foo":"bar"}';
        $json   = ['foo' => 'bar'];

        $cache = Mockery::mock(CacheInterface::class);
        $cache->expects('set')->with($key, $string, null)->atLeast()->once()->andReturn(resolve(true));

        $jsonCache = new Json($cache);
        $jsonCache->set($key, $json);
    }

    #[Test]
    public function delete(): void
    {
        $key = 'sleutel';

        $cache = Mockery::mock(CacheInterface::class);
        $cache->expects('delete')->with($key)->atLeast()->once()->andReturn(resolve(true));

        $jsonCache = new Json($cache);
        $jsonCache->delete($key);
    }

    #[Test]
    public function getMultiple(): void
    {
        $key    = 'sleutel';
        $string = '{"foo":"bar"}';
        $json   = ['foo' => 'bar'];

        $cache = Mockery::mock(CacheInterface::class);
        $cache->expects('getMultiple')->with([$key], null)->atLeast()->once()->andReturn(resolve([$key => $string]));

        $jsonCache = new Json($cache);
        self::assertSame([$key => $json], await($jsonCache->getMultiple([$key])));
    }

    #[Test]
    public function getMultipleNullShouldBeIgnored(): void
    {
        $key = 'sleutel';

        $cache = Mockery::mock(CacheInterface::class);
        $cache->expects('getMultiple')->with([$key], null)->atLeast()->once()->andReturn(resolve([$key => null]));

        $jsonCache = new Json($cache);
        self::assertNull(current(await($jsonCache->getMultiple([$key]))));
    }

    #[Test]
    public function setMultiple(): void
    {
        $key    = 'sleutel';
        $string = '{"foo":"bar"}';
        $json   = ['foo' => 'bar'];

        $cache = Mockery::mock(CacheInterface::class);
        $cache->expects('setMultiple')->with([$key => $string], null)->atLeast()->once();

        $jsonCache = new Json($cache);
        $jsonCache->setMultiple([$key => $json]);
    }

    #[Test]
    public function deleteMultiple(): void
    {
        $key   = 'sleutel';
        $cache = Mockery::mock(CacheInterface::class);
        $cache->expects('deleteMultiple')->with([$key])->atLeast()->once();

        $jsonCache = new Json($cache);
        $jsonCache->deleteMultiple([$key]);
    }

    #[Test]
    public function clear(): void
    {
        $cache = Mockery::mock(CacheInterface::class);
        $cache->expects('clear')->atLeast()->once();

        $jsonCache = new Json($cache);
        $jsonCache->clear();
    }

    #[Test]
    public function has(): void
    {
        $key   = 'sleutel';
        $cache = Mockery::mock(CacheInterface::class);
        $cache->expects('has')->with($key)->atLeast()->once();

        $jsonCache = new Json($cache);
        $jsonCache->has($key);
    }
}
