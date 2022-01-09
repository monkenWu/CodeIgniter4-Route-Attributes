<?php

namespace App\Controllers;

use monken\Ci4RouteAttributes\Route;
use monken\Ci4RouteAttributes\RouteGroup;

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

}
