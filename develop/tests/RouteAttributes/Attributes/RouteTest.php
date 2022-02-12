<?php

namespace monken\Ci4RouteAttributes\Tests\Attributes;

use CodeIgniter\Config\Services;
use \Tests\Support\RouteAttributsTest;
use monken\Ci4RouteAttributes\Route;
use monken\Ci4RouteAttributes\Exception\RouteException;

class RouteTest extends RouteAttributsTest
{

    public function setUp(): void
    {
        parent::setUp();
        Services::reset();
    }

    public function testInitRoute()
    {
        $path = '/test';
        $methods = ['get', 'post', 'put', 'delete'];
        $className = '\App\Controllers\Home';
        $methodName = 'index';
        $route = new Route(
            path: $path,
            methods: $methods,
            options: []
        );
        $route->bind($className, $methodName, 3);
        $this->assertEquals($path, $this->accessProtected($route, "path"));
        $this->assertEquals($methods, $this->accessProtected($route, "methods"));
        $this->assertEquals($className, $this->accessProtected($route, "className"));
        $this->assertEquals($methodName, $this->accessProtected($route, "methodName"));
        $this->assertEquals('/$1/$2/$3', $this->accessProtected($route, "parametersString"));        
    }

    public function testSingleMethodRegister()
    {
        $path = 'test';
        $methods = ['get'];
        $className = '\App\Controllers\Home';
        $methodName = 'index';
        $route = new Route(
            path: $path,
            methods: $methods,
            options: []
        );
        $route->bind($className, $methodName, 3)->register();
        $getRoutes = Services::routes()->getRoutes('get');
        $this->assertArrayHasKey($path, $getRoutes);
        $this->assertEquals("{$className}::{$methodName}/$1/$2/$3", $getRoutes[$path]??null);
    }

    public function testMultipleMethodRegister()
    {
        $path = 'test';
        $methods = ['get', 'post', 'put', 'delete', 'cli'];
        $className = '\App\Controllers\Home';
        $methodName = 'index';
        $route = new Route(
            path: $path,
            methods: $methods,
            options: []
        );
        $route->bind($className, $methodName, 3)->register();
        foreach ($methods as $method) {
            $routes = Services::routes()->getRoutes($method);
            $this->assertArrayHasKey($path, $routes);
            $this->assertEquals("{$className}::{$methodName}/$1/$2/$3", $routes[$path]??null);    
        }
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