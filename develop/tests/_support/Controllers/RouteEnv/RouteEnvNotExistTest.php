<?php

namespace Tests\Support\Controllers\RouteEnv;

use monken\Ci4RouteAttributes\Route;
use monken\Ci4RouteAttributes\RouteEnvironment;
use CodeIgniter\RESTful\ResourceController;

#[RouteEnvironment(type: "development")]
class RouteEnvNotExistTest extends ResourceController
{

    #[Route(path: '/envtest', methods: ["post"])]
    public function post()
    {
        return "PHP8Attributes";
    }

    #[Route(path: '/envtest', methods: ["put"])]
    public function put()
    {
        return "PHP8Attributes";
    }

}
