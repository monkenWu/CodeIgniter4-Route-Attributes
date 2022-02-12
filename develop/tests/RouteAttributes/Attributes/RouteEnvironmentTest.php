<?php

namespace monken\Ci4RouteAttributes\Tests\Attributes;

use CodeIgniter\Config\Services;
use \Tests\Support\RouteAttributsTest;
use monken\Ci4RouteAttributes\RouteGroup;
use monken\Ci4RouteAttributes\Route;
use monken\Ci4RouteAttributes\RouteEnvironment;
use monken\Ci4RouteAttributes\Exception\RouteEnvironmentException;

class RouteEnvironmentTest extends RouteAttributsTest
{

    protected string $className = '\App\Controllers\V1\User';
    protected string $groupName = 'api/v1';
    protected Route $route1;
    protected Route $route2;
    protected Route $route3;
    protected Route $route4;
    protected RouteGroup $routeGroup;

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
        Services::reset();
    }

    public function testInitRouteEnvironment()
    {
        $routeEnv = new RouteEnvironment('testing');
        $routeEnv->bindRouteGroup($this->routeGroup);
        $routeEnv->bindRoute($this->route3)->bindRoute($this->route4);
        $this->assertEquals($this->routeGroup, $this->accessProtected($routeEnv, "routeGroup"));
        $this->assertEquals([$this->route3, $this->route4], $this->accessProtected($routeEnv, "routes"));
    }

    public function testRouteEnvGroupRegister()
    {
        $routeEnv = new RouteEnvironment('testing');
        $routeEnv->bindRouteGroup($this->routeGroup)->registerRoutes();

        $getRoutes = Services::routes()->getRoutes('get');
        $this->assertArrayHasKey("{$this->groupName}/user", $getRoutes);
        $this->assertEquals("{$this->className}::index", $getRoutes["{$this->groupName}/user"] ?? null);

        $postRoutes = Services::routes()->getRoutes('post');
        $this->assertArrayHasKey("{$this->groupName}/user", $postRoutes);
        $this->assertEquals("{$this->className}::create", $postRoutes["{$this->groupName}/user"] ?? null);
        $this->assertArrayHasKey("{$this->groupName}/user/([0-9]+)", $postRoutes);
        $this->assertEquals("{$this->className}::update/$1", $postRoutes["{$this->groupName}/user/([0-9]+)"] ?? null);
    }

    public function testRouteEnvRoutesRegister()
    {
        $routeEnv = new RouteEnvironment('testing');
        $routeEnv->bindRoute($this->route1)->bindRoute($this->route2)->registerRoutes();

        $getRoutes = Services::routes()->getRoutes('get');
        $this->assertArrayHasKey("user", $getRoutes);
        $this->assertEquals("{$this->className}::index", $getRoutes["user"] ?? null);

        $postRoutes = Services::routes()->getRoutes('post');
        $this->assertArrayHasKey("user", $postRoutes);
        $this->assertEquals("{$this->className}::create", $postRoutes["user"] ?? null);
    }

    public function testRouteEnvRoutesArrayRegister()
    {
        $routeEnv = new RouteEnvironment('testing');
        $routeEnv->bindRoutes([$this->route1, $this->route2])->registerRoutes();

        $getRoutes = Services::routes()->getRoutes('get');
        $this->assertArrayHasKey("user", $getRoutes);
        $this->assertEquals("{$this->className}::index", $getRoutes["user"] ?? null);

        $postRoutes = Services::routes()->getRoutes('post');
        $this->assertArrayHasKey("user", $postRoutes);
        $this->assertEquals("{$this->className}::create", $postRoutes["user"] ?? null);
    }

    public function testException()
    {
        try {
            new RouteEnvironment(type: 'errorType');
        } catch (\Exception $e) {
            $this->assertInstanceOf(RouteEnvironmentException::class, $e);
        }
    }
}
