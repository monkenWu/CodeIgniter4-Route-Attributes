<?php

namespace App\Controllers;

use monken\Ci4RouteAttributes\Route;
use monken\Ci4RouteAttributes\RouteGroup;
use monken\Ci4RouteAttributes\RouteEnvironment;

#[RouteEnvironment(type: "development")]
#[RouteGroup('/route/testgroup')]
class Group extends BaseController
{

    #[Route(path:'getindex', methods:['get'])]
    public function index()
    {
        return "hi";
    }

    #[Route(path:'get/something', methods:['get', 'post'])]
    public function somefunction()
    {
        return "something";
    }

    #[Route(path:'get/ignore', methods:['get'], ignoreGroup: true)]
    public function test()
    {
        return "ignore";
    }

}
