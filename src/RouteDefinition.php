<?php

namespace monken\Ci4RouteAttributes;

use monken\Ci4RouteAttributes\RouteGroup;
use monken\Ci4RouteAttributes\RouteRESTful;
use monken\Ci4RouteAttributes\Route;

class RouteDefinition
{
    /**
     * Route Instance Array
     *
     * @var array<Route>
     */
    protected array $routes = [];
    protected ?RouteGroup $routeGroup = null;
    protected ?RouteRESTful $routeRESTful = null;

    public function setRouteGroup(RouteGroup $routeGroup)
    {
        $this->routeGroup = $routeGroup;
    }

    public function addRoute(Route $route)
    {
        $this->routes[] = $route;
    }

    public function setRouteRESTful(RouteRESTful $routeRESTful)
    {
        $this->routeRESTful = $routeRESTful;
    }

    public function getRouteGroup(): ?RouteGroup
    {
        return $this->routeGroup;
    }

    /**
     * get route instance array
     *
     * @return array<Route>
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getRouteRESTful(): ?RouteRESTful
    {
        return $this->routeRESTful;
    }

    public function registerRouteSettiong()
    {
        foreach ($this->routes as $route) {
            $route->register();
        }
        if(!is_null($this->routeRESTful)){
            $this->routeRESTful->register();
        }
        if(!is_null($this->routeGroup)){
            $this->routeGroup->registerRoutes();
        }
    }
}
