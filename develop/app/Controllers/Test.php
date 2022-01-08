<?php

namespace App\Controllers;

use monken\Ci4RouteAttributes\Route;
use monken\Ci4RouteAttributes\RouteGroup;

#[RouteGroup('api/v1/test')]
class Test extends BaseController
{

    #[Route(path:'getindex', methods:['get'])]
    public function index()
    {
        return "hi";
    }

    #[Route(path:'getyes', methods:['get', 'post'])]
    public function msdfoipjkeior()
    {
        return "yesyyes";
    }

}
