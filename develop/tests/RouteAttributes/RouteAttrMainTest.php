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
        $config = new \Config\RouteAttributes();
        $config->controllerNamespaces = ["Tests\Support\Controllers\Route"];

        RouteAttributes::runHandler($config);
    }


}
