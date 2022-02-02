<?php

namespace Tests\Support;

use CodeIgniter\Test\CIUnitTestCase;

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
}
