<?php

namespace monken\Ci4RouteAttributes\Tests\Attributes;

use CodeIgniter\Config\Services;
use \Tests\Support\RouteAttributsTest;
use monken\Ci4RouteAttributes\RouteRESTful;
use monken\Ci4RouteAttributes\Exception\RouteRESTfulException;

class RouteRESTfulTest extends RouteAttributsTest
{

    public function setUp(): void
    {
        parent::setUp();
        Services::reset();
    }

    public function testInitRESTfulRoute()
    {
        $name = '/test';
        $type = 'resource';
        $websafe = true;
        $only = ['index', 'show'];
        $except = ['new', 'edit'];
        $placeholder = '(:num)';
        $options = ['filter' => 'api-auth'];
        $ignoreGroup = true;
        $className = '\App\Controllers\RESTFulController';
        $resourceRoute = new RouteRESTful(
            name: $name,
            type: $type,
            websafe: $websafe,
            only: $only,
            except: $except,
            placeholder: $placeholder,
            options: $options,
            ignoreGroup: $ignoreGroup
        );
        $resourceRoute->bind($className);
        $this->assertEquals($name, $this->accessProtected($resourceRoute, "name"));
        $this->assertEquals($type, $this->accessProtected($resourceRoute, "type"));
        $this->assertEquals($websafe, $this->accessProtected($resourceRoute, "websafe"));
        $this->assertEquals($only, $this->accessProtected($resourceRoute, "only"));
        $this->assertEquals($except, $this->accessProtected($resourceRoute, "except"));
        $this->assertEquals($placeholder, $this->accessProtected($resourceRoute, "placeholder"));
        $this->assertEquals($options, $this->accessProtected($resourceRoute, "options"));
        $this->assertEquals($ignoreGroup, $this->accessProtected($resourceRoute, "ignoreGroup"));
        $this->assertEquals($className, $this->accessProtected($resourceRoute, "className"));
    }

    public function testResourceRESTful()
    {
        $name = 'test';
        $type = 'resource';
        $only = ['index', 'show', 'update', 'delete'];
        $placeholder = '(:num)';
        $className = '\App\Controllers\RESTFulController';
        $resourceRoute = new RouteRESTful(
            name: $name,
            type: $type,
            websafe: true,
            only: $only,
            placeholder: $placeholder
        );
        $resourceRoute->bind($className)->register();

        $getRoutes = Services::routes()->getRoutes('get');
        $this->assertArrayHasKey($name, $getRoutes);
        $this->assertEquals("{$className}::index", $getRoutes[$name] ?? null);
        $this->assertArrayHasKey("{$name}/([0-9]+)", $getRoutes);
        $this->assertEquals("{$className}::show/$1", $getRoutes["{$name}/([0-9]+)"] ?? null);

        $postRoutes = Services::routes()->getRoutes('post');
        $this->assertArrayHasKey("{$name}/([0-9]+)", $postRoutes);
        $this->assertEquals("{$className}::update/$1", $postRoutes["{$name}/([0-9]+)"] ?? null);
        $this->assertArrayHasKey("{$name}/([0-9]+)/delete", $postRoutes);
        $this->assertEquals("{$className}::delete/$1", $postRoutes["{$name}/([0-9]+)/delete"] ?? null);


        $putRoutes = Services::routes()->getRoutes('put');
        $this->assertArrayHasKey("{$name}/([0-9]+)", $putRoutes);
        $this->assertEquals("{$className}::update/$1", $putRoutes["{$name}/([0-9]+)"] ?? null);

        $deleteRoutes = Services::routes()->getRoutes('delete');
        $this->assertArrayHasKey("{$name}/([0-9]+)", $deleteRoutes);
        $this->assertEquals("{$className}::delete/$1", $deleteRoutes["{$name}/([0-9]+)"] ?? null);
    }

    public function testPresenterRESTful()
    {
        $name = 'test';
        $type = 'presenter';
        $only = ['index', 'show', 'create'];
        $placeholder = '(:num)';
        $className = '\App\Controllers\RESTFulController';
        $resourceRoute = new RouteRESTful(
            name: $name,
            type: $type,
            websafe: true,
            only: $only,
            placeholder: $placeholder
        );
        $resourceRoute->bind($className)->register();

        $getRoutes = Services::routes()->getRoutes('get');
        $this->assertArrayHasKey($name, $getRoutes);
        $this->assertEquals("{$className}::index", $getRoutes[$name] ?? null);
        $this->assertArrayHasKey("{$name}/([0-9]+)", $getRoutes);
        $this->assertEquals("{$className}::show/$1", $getRoutes["{$name}/([0-9]+)"] ?? null);
        $this->assertArrayHasKey("{$name}/show/([0-9]+)", $getRoutes);
        $this->assertEquals("{$className}::show/$1", $getRoutes["{$name}/show/([0-9]+)"] ?? null);

        $postRoutes = Services::routes()->getRoutes('post');
        $this->assertArrayHasKey($name, $postRoutes);
        $this->assertArrayHasKey("{$name}", $postRoutes);
        $this->assertArrayHasKey("{$name}/create", $postRoutes);
        $this->assertEquals("{$className}::create", $postRoutes[$name] ?? null);
        $this->assertEquals("{$className}::create", $postRoutes["{$name}/create"] ?? null);
    }

    public function testException()
    {
        try {
            new RouteRESTful(name: "test", type: "errorType");
        } catch (\Exception $e) {
            $this->assertInstanceOf(RouteRESTfulException::class, $e);
        }
    }
}
