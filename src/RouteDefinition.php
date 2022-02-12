<?php

namespace monken\Ci4RouteAttributes;

use monken\Ci4RouteAttributes\RouteEnvironment;
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
    protected ?RouteEnvironment $routeEnvironment = null;
    protected ?RouteGroup $routeGroup = null;
    protected ?RouteRESTful $routeRESTful = null;

    public function setRouteEnvironment(RouteEnvironment $routeEnvironment): RouteDefinition
    {
        $this->routeEnvironment = $routeEnvironment;
        return $this;
    }

    public function setRouteGroup(RouteGroup $routeGroup): RouteDefinition
    {
        $this->routeGroup = $routeGroup;
        return $this;
    }

    public function addRoute(Route $route): RouteDefinition
    {
        $this->routes[] = $route;
        return $this;
    }

    public function setRouteRESTful(RouteRESTful $routeRESTful): RouteDefinition
    {
        $this->routeRESTful = $routeRESTful;
        return $this;
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

    public function registerRouteSetting()
    {
        if (is_null($this->routeEnvironment)) {
            foreach ($this->routes as $route) {
                $route->register();
            }
            if (!is_null($this->routeRESTful)) {
                $this->routeRESTful->register();
            }
            if (!is_null($this->routeGroup)) {
                $this->routeGroup->registerRoutes();
            }
        } else {
            $this->routeEnvironment->bindRoutes($this->routes);
            if (!is_null($this->routeRESTful)) {
                $this->routeEnvironment->bindRoute($this->routeRESTful);
            }
            if (!is_null($this->routeGroup)) {
                $this->routeEnvironment->bindRouteGroup($this->routeGroup);
            }
            $this->routeEnvironment->registerRoutes();
        }
    }
}
