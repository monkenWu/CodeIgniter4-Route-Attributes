<?php

namespace Tests\Support\Controllers\RouteEnv;

use monken\Ci4RouteAttributes\Route;
use monken\Ci4RouteAttributes\RouteEnvironment;
use CodeIgniter\RESTful\ResourceController;

#[RouteEnvironment(type: "testing")]
class RouteEnvExistTest extends ResourceController
{

    #[Route(path: '/env/exist', methods: ["post"])]
    public function post()
    {
        return "PHP8Attributes";
    }

    #[Route(path: '/env/exist', methods: ["put"])]
    public function put()
    {
        return "PHP8Attributes";
    }

}
