<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use monken\Ci4RouteAttributes\Route;
use monken\Ci4RouteAttributes\RouteGroup;
use monken\Ci4RouteAttributes\RouteRESTful;

#[RouteGroup('/api/v1')]
#[RouteRESTful('user', 'resource')]
class Restapi extends ResourceController
{
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

    #[Route('user/special', ['get', 'cli'])]
    public function special()
    {
        return $this->respond([
            "msg" => "special"
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
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        return $this->respond([
            "method" => "new"
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
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        return $this->respond([
            "method" => "edit",
            "id" => $id
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
