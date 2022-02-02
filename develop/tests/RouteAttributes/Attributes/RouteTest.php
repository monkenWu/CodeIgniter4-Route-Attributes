<?php

namespace monken\Ci4RouteAttributes\Tests\Attributes;

use \Tests\Support\RouteAttributsTest;
use monken\Ci4RouteAttributes\Route;
use monken\Ci4RouteAttributes\Exception\RouteException;

class RouteTest extends RouteAttributsTest
{

    public function testInitRoute()
    {
        $path = '/test';
        $methods = ['get', 'post', 'put', 'delete'];
        $className = 'App\Controllers\Home';
        $methodName = 'index';
        $route = new Route(
            path: $path,
            methods: $methods,
            options: []
        );
        $route->bind($className, $methodName, 3);
        $this->assertEquals($path, $this->accessProtected($route, "path"));
    }

    public function testException()
    {
        try {
            new Route(path: '/test', methods:['test']);
        } catch (\Exception $e) {
            $this->assertInstanceOf(RouteException::class, $e);
        }
    }
}