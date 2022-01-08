<?php

namespace App\Controllers;

use monken\Ci4AttributeRoute\Route;

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    #[Route(path: '/routeTest/(:any)/(:any)/(:any)', methods: ["get"])]
    #[Route(path: '/api/v1/test/(:any)/(:any)/(:any)', methods: ["get"])]
    public function hello($a, $b, $c)
    {
        echo $a . '<br>';
        echo $b . '<br>';
        echo $c . '<br>';
        return "PHP8Attributes";
    }
}
