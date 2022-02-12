<?php

namespace Tests\Support\Controllers\Route;

use CodeIgniter\Controller;
use monken\Ci4RouteAttributes\Route;

class RouteTest extends Controller
{

    #[Route(path: '/routeTest/(:any)/(:any)/(:any)', methods: ["get"])]
    #[Route(path: '/api/v1/test/(:any)/(:any)/(:any)', methods: ["get"])]
    public function get($a, $b, $c)
    {
        echo $a . '<br>';
        echo $b . '<br>';
        echo $c . '<br>';
        return "PHP8Attributes";
    }

    #[Route(path: '/routeTest', methods: ["post"])]
    public function post()
    {
        return "PHP8Attributes";
    }

    #[Route(path: '/routeTest', methods: ["put"])]
    public function put()
    {
        return "PHP8Attributes";
    }

    #[Route(path: '/routeTest', methods: ["delete"])]
    public function delete()
    {
        return "PHP8Attributes";
    }

    #[Route(path: '/routeTest', methods: ["cli"])]
    public function cli()
    {
        return "PHP8Attributes";
    }

}
