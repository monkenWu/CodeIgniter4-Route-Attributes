<?php

namespace Tests\Support\Controllers\RouteGroup;

use CodeIgniter\Controller;
use monken\Ci4RouteAttributes\Route;
use monken\Ci4RouteAttributes\RouteGroup;

#[RouteGroup('/route/testgroup')]
class RouteGroupTest extends Controller
{

    #[Route(path:'getindex', methods:['get'])]
    public function index()
    {
        return "hi";
    }

    #[Route(path:'get/something', methods:['get', 'post'])]
    public function someMethod()
    {
        return "something";
    }

    #[Route(path:'get/ignore', methods:['get'], ignoreGroup: true)]
    public function ignoreMethod()
    {
        return "PHP8Attributes";
    }
}
