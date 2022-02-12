<?php

namespace Tests\Support\Controllers\RouteRESTful;

use monken\Ci4RouteAttributes\Route;
use CodeIgniter\RESTful\ResourceController;
use monken\Ci4RouteAttributes\RouteRESTful;
use monken\Ci4RouteAttributes\RouteGroup;



#[RouteGroup(name: 'api/v1')]
#[RouteRESTful(name: 'user', type: 'resource', only: ['index', 'show', 'create', 'update', 'delete'] ,placeholder: '(:num)')]
class RouteRESTfulGroupTest extends ResourceController
{

    #[Route(path: 'user/special', methods: ['get'])]
    public function special()
    {
        return $this->respond([
            "msg" => "special"
        ]);
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        return $this->respond([
            "method" => "index"
        ]);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        return $this->respond([
            "method" => "show",
            "id" => $id
        ]);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        return $this->respond([
            "method" => "create"
        ]);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        return $this->respond([
            "method" => "update",
            "id" => $id
        ]);
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        return $this->respond([
            "method" => "delete",
            "id" => $id
        ]);
    }
}
