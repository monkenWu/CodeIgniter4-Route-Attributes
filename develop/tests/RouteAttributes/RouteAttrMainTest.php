<?php

namespace monken\Ci4RouteAttributes\Tests\Attributes;

use \Tests\Support\RouteAttributsTest;
use monken\Ci4RouteAttributes\RouteAttributes;

class RouteAttrMainTest extends RouteAttributsTest
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testRouteAttributes()
    {
        $className = "\Tests\Support\Controllers\Route\RouteTest";
        $config = new \Config\RouteAttributes();
        $config->controllerNamespaces = ["Tests\Support\Controllers\Route"];
        RouteAttributes::runHandler($config);

        //get
        $this->assertCi4RouteExist(method: "get", path: "routeTest/([^/]+)/([^/]+)/([^/]+)", targetClassName: $className, targetClassMethod: 'get', parameter: '/$1/$2/$3');
        $this->assertCi4RouteExist(method: "get", path: "api/v1/test/([^/]+)/([^/]+)/([^/]+)", targetClassName: $className, targetClassMethod: 'get', parameter: '/$1/$2/$3');
        //post
        $this->assertCi4RouteExist(method: "post", path: "routeTest", targetClassName: $className, targetClassMethod: 'post');
        //put
        $this->assertCi4RouteExist(method: "put", path: "routeTest", targetClassName: $className, targetClassMethod: 'put');
        //delete
        $this->assertCi4RouteExist(method: "delete", path: "routeTest", targetClassName: $className, targetClassMethod: 'delete');
        //cli
        $this->assertCi4RouteExist(method: "cli", path: "routeTest", targetClassName: $className, targetClassMethod: 'cli');
    }

    public function testRouteGroupAttributes()
    {
        $className = "\Tests\Support\Controllers\RouteGroup\RouteGroupTest";
        $groupPath = "route/testgroup";
        $config = new \Config\RouteAttributes();
        $config->controllerNamespaces = ["Tests\Support\Controllers\RouteGroup"];
        RouteAttributes::runHandler($config);

        //get
        $this->assertCi4RouteExist(method: "get", path: "{$groupPath}/getindex", targetClassName: $className, targetClassMethod: 'index');
        $this->assertCi4RouteExist(method: "get", path: "{$groupPath}/get/something", targetClassName: $className, targetClassMethod: 'someMethod');
        $this->assertCi4RouteExist(method: "get", path: "get/ignore", targetClassName: $className, targetClassMethod: 'ignoreMethod');
        //post
        $this->assertCi4RouteExist(method: "post", path: "{$groupPath}/get/something", targetClassName: $className, targetClassMethod: 'someMethod');
    }

    public function testRouteRESTfulAttributes()
    {

    }

}
