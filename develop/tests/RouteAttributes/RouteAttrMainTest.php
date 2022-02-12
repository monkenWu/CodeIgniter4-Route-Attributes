<?php

namespace monken\Ci4RouteAttributes\Tests\Attributes;

use \Tests\Support\RouteAttributsTest;
use monken\Ci4RouteAttributes\RouteAttributes;

class RouteAttrMainTest extends RouteAttributsTest
{

    public function setUp(): void
    {
        parent::setUp();
        RouteAttributes::initInstances();
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
        $className = "\Tests\Support\Controllers\RouteRESTful\RouteRESTfulTest";
        $groupClassName = "\Tests\Support\Controllers\RouteRESTful\RouteRESTfulGroupTest";

        $restSource = "user";
        $group = "api/v1";
        $config = new \Config\RouteAttributes();
        $config->controllerNamespaces = ["Tests\Support\Controllers\RouteRESTful"];
        RouteAttributes::runHandler($config);

        //get
        $this->assertCi4RouteExist(method: "get", path: "{$restSource}", targetClassName: "{$className}", targetClassMethod: 'index');
        $this->assertCi4RouteExist(method: "get", path: "{$restSource}/([0-9]+)", targetClassName: $className, targetClassMethod: 'show', parameter: '/$1');
        $this->assertCi4RouteExist(method: "get", path: "user/special", targetClassName: "{$className}", targetClassMethod: 'special');
        //get group
        $this->assertCi4RouteExist(method: "get", path: "{$group}/{$restSource}", targetClassName: $groupClassName, targetClassMethod: 'index');
        $this->assertCi4RouteExist(method: "get", path: "{$group}/{$restSource}/([0-9]+)", targetClassName: $groupClassName, targetClassMethod: 'show', parameter: '/$1');
        $this->assertCi4RouteExist(method: "get", path: "{$group}/user/special", targetClassName: $groupClassName, targetClassMethod: 'special');

        //post
        $this->assertCi4RouteExist(method: "post", path: "{$restSource}", targetClassName: $className, targetClassMethod: 'create');
        //put
        $this->assertCi4RouteExist(method: "put", path: "{$restSource}/([0-9]+)", targetClassName: $className, targetClassMethod: 'update', parameter: '/$1');
        //delete
        $this->assertCi4RouteExist(method: "delete", path: "{$restSource}/([0-9]+)", targetClassName: $className, targetClassMethod: 'delete', parameter: '/$1');
    }

    public function testRouteRESTfulGroupAttributes()
    {
        $groupClassName = "\Tests\Support\Controllers\RouteRESTful\RouteRESTfulGroupTest";
        $restSource = "user";
        $group = "api/v1";
        $config = new \Config\RouteAttributes();
        $config->controllerNamespaces = ["Tests\Support\Controllers\RouteRESTful"];
        RouteAttributes::runHandler($config);

        //get group
        $this->assertCi4RouteExist(method: "get", path: "{$group}/{$restSource}", targetClassName: $groupClassName, targetClassMethod: 'index');
        $this->assertCi4RouteExist(method: "get", path: "{$group}/{$restSource}/([0-9]+)", targetClassName: $groupClassName, targetClassMethod: 'show', parameter: '/$1');
        $this->assertCi4RouteExist(method: "get", path: "{$group}/user/special", targetClassName: $groupClassName, targetClassMethod: 'special');

        //post
        $this->assertCi4RouteExist(method: "post", path: "{$group}/{$restSource}", targetClassName: $groupClassName, targetClassMethod: 'create');
        //put
        $this->assertCi4RouteExist(method: "put", path: "{$group}/{$restSource}/([0-9]+)", targetClassName: $groupClassName, targetClassMethod: 'update', parameter: '/$1');
        //delete
        $this->assertCi4RouteExist(method: "delete", path: "{$group}/{$restSource}/([0-9]+)", targetClassName: $groupClassName, targetClassMethod: 'delete', parameter: '/$1');
    }


    public function testRouteEnvNotExistTestAttributes()
    {
        $existClassName = "\Tests\Support\Controllers\RouteEnv\RouteEnvExistTest";
        $config = new \Config\RouteAttributes();
        $config->controllerNamespaces = ["Tests\Support\Controllers\RouteEnv"];
        RouteAttributes::runHandler($config);

        //post
        $this->assertCi4RouteNotExist(method: "post", path: "envtest");
        //put
        $this->assertCi4RouteNotExist(method: "put", path: "envtest");

        //post
        $this->assertCi4RouteExist(method: "post", path: "env/exist", targetClassName: $existClassName, targetClassMethod: 'post');
        //put
        $this->assertCi4RouteExist(method: "put", path: "env/exist", targetClassName: $existClassName, targetClassMethod: 'put');
    }

    public function testMultiNamespaceTest()
    {
        $routeClassName = "\Tests\Support\Controllers\Route\RouteTest";
        $RouteGroupClassName = "\Tests\Support\Controllers\RouteGroup\RouteGroupTest";
        $groupPath = "route/testgroup";
        $config = new \Config\RouteAttributes();
        $config->controllerNamespaces = [
            "Tests\Support\Controllers\RouteGroup",
            "Tests\Support\Controllers\Route"
        ];
        RouteAttributes::runHandler($config);

        //route class
        //get
        $this->assertCi4RouteExist(method: "get", path: "routeTest/([^/]+)/([^/]+)/([^/]+)", targetClassName: $routeClassName, targetClassMethod: 'get', parameter: '/$1/$2/$3');
        $this->assertCi4RouteExist(method: "get", path: "api/v1/test/([^/]+)/([^/]+)/([^/]+)", targetClassName: $routeClassName, targetClassMethod: 'get', parameter: '/$1/$2/$3');
        //post
        $this->assertCi4RouteExist(method: "post", path: "routeTest", targetClassName: $routeClassName, targetClassMethod: 'post');
        //put
        $this->assertCi4RouteExist(method: "put", path: "routeTest", targetClassName: $routeClassName, targetClassMethod: 'put');
        //delete
        $this->assertCi4RouteExist(method: "delete", path: "routeTest", targetClassName: $routeClassName, targetClassMethod: 'delete');
        //cli
        $this->assertCi4RouteExist(method: "cli", path: "routeTest", targetClassName: $routeClassName, targetClassMethod: 'cli');
        
        //route group class
        //get
        $this->assertCi4RouteExist(method: "get", path: "{$groupPath}/getindex", targetClassName: $RouteGroupClassName, targetClassMethod: 'index');
        $this->assertCi4RouteExist(method: "get", path: "{$groupPath}/get/something", targetClassName: $RouteGroupClassName, targetClassMethod: 'someMethod');
        $this->assertCi4RouteExist(method: "get", path: "get/ignore", targetClassName: $RouteGroupClassName, targetClassMethod: 'ignoreMethod');
        //post
        $this->assertCi4RouteExist(method: "post", path: "{$groupPath}/get/something", targetClassName: $RouteGroupClassName, targetClassMethod: 'someMethod');
        
    }

}
