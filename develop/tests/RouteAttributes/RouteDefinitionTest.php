<?php

namespace monken\Ci4RouteAttributes\Tests\Attributes;

use CodeIgniter\Config\Services;
use \Tests\Support\RouteAttributsTest;
use monken\Ci4RouteAttributes\RouteGroup;
use monken\Ci4RouteAttributes\Route;
use monken\Ci4RouteAttributes\RouteEnvironment;
use monken\Ci4RouteAttributes\RouteDefinition;
use monken\Ci4RouteAttributes\RouteRESTful;

class RouteDefinitionTest extends RouteAttributsTest
{

    protected string $className = '\App\Controllers\V1\User';
    protected string $groupName = 'api/v1';
    protected string $restName = 'rest/user';
    protected Route $route1;
    protected Route $route2;
    protected Route $route3;
    protected RouteGroup $routeGroup;
    protected RouteEnvironment $routeEnv;
    protected RouteRESTful $routeRESTful;

    public function setUp(): void
    {
        parent::setUp();
        $this->route1 = (new Route(path: 'user', methods: ['get']))->bind($this->className, 'index');
        $this->route2 = (new Route(path: 'user', methods: ['post']))->bind($this->className, 'create');
        $this->route3 = (new Route(path: 'user/(:num)', methods: ['post']))->bind($this->className, 'update', 1);
        $this->routeGroup = (new RouteGroup(name: $this->groupName))
            ->bindRoute($this->route1)
            ->bindRoute($this->route2)
            ->bindRoute($this->route3);
        $this->routeEnv = new RouteEnvironment('testing');
        $this->routeRESTful = (new RouteRESTful(
            name: $this->restName,
            type: "resource",
            only: ['index', 'show', 'update', 'delete'],
            placeholder: '(:num)'
        ))->bind($this->className);
        Services::reset();
    }

    public function testRouteEnvironmentSetter()
    {
        $routeDefi = new RouteDefinition();
        $routeDefi->setRouteEnvironment($this->routeEnv);
        $this->assertEquals($this->routeEnv, $this->accessProtected($routeDefi, "routeEnvironment"));
    }

    public function testRouteGroupSetterAndGetter()
    {
        $routeDefi = new RouteDefinition();
        $routeDefi->setRouteGroup($this->routeGroup);
        $this->assertEquals($this->routeGroup, $routeDefi->getRouteGroup());
    }

    public function testRouteRESTfulSetterAndGetter()
    {
        $routeDefi = new RouteDefinition();
        $routeDefi->setRouteRESTful($this->routeRESTful);
        $this->assertEquals($this->routeRESTful, $routeDefi->getRouteRESTful());
    }

    public function testRouteSetterAndGetter()
    {
        $routeDefi = new RouteDefinition();
        $routeDefi->addRoute($this->route1)
            ->addRoute($this->route2)
            ->addRoute($this->route3);
        $this->assertEquals([$this->route1, $this->route2, $this->route3], $routeDefi->getRoutes());
    }

    public function testRegisterRouteSettiong()
    {
        $routeDefi = new RouteDefinition();
        $routeDefi->addRoute($this->route1)
            ->addRoute($this->route2)
            ->addRoute($this->route3);
        $routeDefi->setRouteRESTful($this->routeRESTful);
        $routeDefi->setRouteGroup($this->routeGroup);
        $routeDefi->registerRouteSetting();
        $this->assertCi4Route();
    }

    public function testRegisterRouteSettiongHaveEnv()
    {
        $routeDefi = new RouteDefinition();
        $routeDefi->setRouteEnvironment($this->routeEnv);
        $routeDefi->addRoute($this->route1)
            ->addRoute($this->route2)
            ->addRoute($this->route3);
        $routeDefi->setRouteRESTful($this->routeRESTful);
        $routeDefi->setRouteGroup($this->routeGroup);
        $routeDefi->registerRouteSetting();
        $this->assertCi4Route();
    }

    protected function assertCi4Route()
    {
        $getRoutes = Services::routes()->getRoutes('get');
        //Route assert
        $this->assertArrayHasKey("user", $getRoutes);
        $this->assertEquals("{$this->className}::index", $getRoutes["user"] ?? null);
        //group assert
        $this->assertArrayHasKey("{$this->groupName}/user", $getRoutes);
        $this->assertEquals("{$this->className}::index", $getRoutes["{$this->groupName}/user"] ?? null);
        //rest assert
        $this->assertArrayHasKey($this->restName, $getRoutes);
        $this->assertEquals("{$this->className}::index", $getRoutes[$this->restName] ?? null);
        $this->assertArrayHasKey("{$this->restName}/([0-9]+)", $getRoutes);
        $this->assertEquals("{$this->className}::show/$1", $getRoutes["{$this->restName}/([0-9]+)"] ?? null);

        $postRoutes = Services::routes()->getRoutes('post');
        //Route assert
        $this->assertArrayHasKey("user", $postRoutes);
        $this->assertEquals("{$this->className}::create", $postRoutes["user"] ?? null);
        $this->assertArrayHasKey("user/([0-9]+)", $postRoutes);
        $this->assertEquals("{$this->className}::update/$1", $postRoutes["user/([0-9]+)"] ?? null);
        //group assert
        $this->assertArrayHasKey("{$this->groupName}/user", $postRoutes);
        $this->assertEquals("{$this->className}::create", $postRoutes["{$this->groupName}/user"] ?? null);
        $this->assertArrayHasKey("{$this->groupName}/user/([0-9]+)", $postRoutes);
        $this->assertEquals("{$this->className}::update/$1", $postRoutes["{$this->groupName}/user/([0-9]+)"] ?? null);
        
        $putRoutes = Services::routes()->getRoutes('put');
        //rest assert
        $this->assertArrayHasKey("{$this->restName}/([0-9]+)", $putRoutes);
        $this->assertEquals("{$this->className}::update/$1", $putRoutes["{$this->restName}/([0-9]+)"] ?? null);

        $deleteRoutes = Services::routes()->getRoutes('delete');
        //rest assert
        $this->assertArrayHasKey("{$this->restName}/([0-9]+)", $deleteRoutes);
        $this->assertEquals("{$this->className}::delete/$1", $deleteRoutes["{$this->restName}/([0-9]+)"] ?? null);
    }

}
