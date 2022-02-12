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
    protected Route $route1;
    protected Route $route2;
    protected Route $route3;
    protected Route $route4;
    protected RouteGroup $routeGroup;
    protected RouteEnvironment $routeEnv;
    protected RouteRESTful $routeRESTful;

    public function setUp(): void
    {
        parent::setUp();
        $this->route1 = (new Route(path: 'user', methods: ['get']))->bind($this->className, 'index');
        $this->route2 = (new Route(path: 'user', methods: ['post']))->bind($this->className, 'create');
        $this->route3 = (new Route(path: 'user/(:num)', methods: ['post']))->bind($this->className, 'update', 1);
        $this->route4 = (new Route(path: 'user/(:num)', methods: ['delete']))->bind($this->className, 'delete', 1);
        $this->routeGroup = (new RouteGroup(name: $this->groupName))
            ->bindRoute($this->route1)
            ->bindRoute($this->route2)
            ->bindRoute($this->route3);
        $this->routeEnv = (new RouteEnvironment('testing'))
            ->bindRouteGroup($this->routeGroup)
            ->bindRoutes([$this->route1, $this->route2]);
        $this->routeRESTful = (new RouteRESTful(
            name: "user",
            type: "resource"
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

    public function testRegisterRouteSettiongHaveEnv()
    {

    }

}
