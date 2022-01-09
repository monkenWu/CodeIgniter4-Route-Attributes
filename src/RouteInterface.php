<?php

namespace monken\Ci4RouteAttributes;

use CodeIgniter\Router\RouteCollection;

interface RouteInterface
{
    public function bind(string $className): RouteInterface;
    public function register(?RouteCollection $routes = null): RouteInterface;
}
