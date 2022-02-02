<?php

namespace monken\Ci4RouteAttributes;

use Config\Services;
use CodeIgniter\Router\RouteCollection;
use monken\Ci4RouteAttributes\RouteGroup;
use monken\Ci4RouteAttributes\Exception\RouteEnvironmentException;

#[\Attribute(\Attribute::TARGET_CLASS)]
class RouteEnvironment
{
    protected $allowType = [
        "production", "development", "testing"
    ];

    /**
     * route instance array
     *
     * @var array<RouteInterface>
     */
    protected array $routes = [];

    protected ?RouteGroup $routeGroup = null;

    /**
     * Environment Restrictions
     *
     * @param string $type allow type: production, development and testing
     */
    public function __construct(
        protected string $type = ''
    ) {
        if(!in_array($type, $this->allowMethod)){
            throw RouteEnvironmentException::forAllowType($type);
        }
    }

    public function bindRouteGroup(
        RouteGroup $routeGroup
    ): RouteEnvironment {
        $this->routeGroup = $routeGroup;
        return $this;
    }

    public function bindRoute(
        RouteInterface $route
    ): RouteEnvironment {
        $this->routes[] = $route;
        return $this;
    }

    public function registerRoutes()
    {
        $ciRoutes = Services::routes();
        
        if (!is_null($this->routeGroup)) {
            $routeGroup = $this->routeGroup;
            $groupCallback = function (RouteCollection $envRoute) use ($routeGroup) {
                $routeGroup->registerRoutes($envRoute);
            };       
            $ciRoutes->environment($this->type, $groupCallback);     
        }

        $routes = $this->routes;
        $routeCallback = function (RouteCollection $envRoute) use ($routes) {
            foreach ($routes as $route) {
                $route->register($envRoute);
            }
        };
        $ciRoutes->environment($this->type, $routeCallback);
    }
}
