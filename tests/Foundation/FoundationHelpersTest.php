<?php

use Mockery as m;
use Illuminate\Foundation\Application;

class FoundationHelpersTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testCache()
    {
        $app = new Application;
        $app['cache'] = $cache = m::mock('StdClass');

        // 1. cache()
        $this->assertInstanceOf('StdClass', cache());

        // 2. cache(['foo' => 'bar'], 1);
        $cache->shouldReceive('put')->once()->with('foo', 'bar', 1);
        cache(['foo' => 'bar'], 1);

        // 3. cache('foo');
        $cache->shouldReceive('get')->once()->andReturn('bar');
        $this->assertEquals('bar', cache('foo'));

        // 4. cache('baz', 'default');
        $cache->shouldReceive('get')->once()->with('baz', 'default')->andReturn('default');
        $this->assertEquals('default', cache('baz', 'default'));
    }

    public function testCacheThrowsAnExceptionIfAnExpirationIsNotProvided()
    {
        $this->setExpectedException('Exception');

        cache(['foo' => 'bar']);
    }
}
