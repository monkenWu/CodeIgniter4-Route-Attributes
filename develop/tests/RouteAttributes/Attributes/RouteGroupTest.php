<?php

namespace monken\Ci4RouteAttributes\Tests\Attributes;

use CodeIgniter\Config\Services;
use \Tests\Support\RouteAttributsTest;
use monken\Ci4RouteAttributes\RouteGroup;
use monken\Ci4RouteAttributes\Route;

class RouteGroupTest extends RouteAttributsTest
{

    protected string $className = '\App\Controllers\V1\User';
    protected Route $route1;
    protected Route $route2;
    protected Route $route3;

    public function setUp(): void
    {
        parent::setUp();
        $this->route1 = (new Route(path: 'user', methods: ['get']))->bind($this->className, 'index');
        $this->route2 = (new Route(path: 'user', methods: ['post']))->bind($this->className, 'create');
        $this->route3 = (new Route(path: 'user/(:num)', methods: ['post']))->bind($this->className, 'update', 1);
        Services::reset();
    }

    public function testInitRouteGroup()
    {
        $name = 'api/v1';
        $options = ['filter' => 'api-auth'];
        $routeGroup = new RouteGroup(
            name: $name,
            options: $options
        );
        $routeGroup->bindRoute($this->route1)
            ->bindRoute($this->route2)
            ->bindRoute($this->route3);

        $this->assertEquals($name, $this->accessProtected($routeGroup, "name"));
        $this->assertEquals($options, $this->accessProtected($routeGroup, "options"));
        $this->assertEquals([$this->route1, $this->route2, $this->route3], $this->accessProtected($routeGroup, "routes"));
    }

    public function testSingleRouteRegister()
    {
        $name = 'api/v1';
        $options = ['filter' => 'api-auth'];
        $routeGroup = new RouteGroup(
            name: $name,
            options: $options
        );
        $routeGroup->bindRoute($this->route1)->registerRoutes();

        $getRoutes = Services::routes()->getRoutes('get');
        $this->assertArrayHasKey("{$name}/user", $getRoutes);
        $this->assertEquals("{$this->className}::index", $getRoutes["{$name}/user"] ?? null);
    }

    public function testMultipleRouteRegister()
    {
        $name = 'api/v1';
        $options = ['filter' => 'api-auth'];
        $routeGroup = new RouteGroup(
            name: $name,
            options: $options
        );
        $routeGroup->bindRoute($this->route1)
            ->bindRoute($this->route2)
            ->bindRoute($this->route3)
            ->registerRoutes();

        $getRoutes = Services::routes()->getRoutes('get');
        $this->assertArrayHasKey("{$name}/user", $getRoutes);
        $this->assertEquals("{$this->className}::index", $getRoutes["{$name}/user"] ?? null);

        $postRoutes = Services::routes()->getRoutes('post');
        $this->assertArrayHasKey("{$name}/user", $postRoutes);
        $this->assertEquals("{$this->className}::create", $postRoutes["{$name}/user"] ?? null);
        $this->assertArrayHasKey("{$name}/user/([0-9]+)", $postRoutes);
        $this->assertEquals("{$this->className}::update/$1", $postRoutes["{$name}/user/([0-9]+)"] ?? null);
    }
}
