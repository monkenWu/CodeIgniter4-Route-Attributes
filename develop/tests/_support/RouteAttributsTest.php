<?php

namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Config\Services;

class RouteAttributsTest extends CIUnitTestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function accessProtected($obj, $prop)
    {
        $reflection = new \ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
    }

    public function assertCi4RouteExist(
        string $method,
        string $path,
        string $targetClassName,
        string $targetClassMethod,
        string $parameter = ''
    )
    {
        $routesArray = Services::routes()->getRoutes($method);
        $this->assertArrayHasKey($path, $routesArray);
        $this->assertEquals("{$targetClassName}::{$targetClassMethod}{$parameter}", $routesArray[$path] ?? null);
    }

    public function assertCi4RouteNotExist(string $method, string $path)
    {
        $routesArray = Services::routes()->getRoutes($method);
        $this->assertArrayNotHasKey($path, $routesArray);
    }
}
