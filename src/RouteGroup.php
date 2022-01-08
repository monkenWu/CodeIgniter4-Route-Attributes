<?php

namespace monken\Ci4RouteAttributes;

use Config\Services;
use CodeIgniter\Router\RouteCollection;

#[\Attribute(\Attribute::TARGET_CLASS)]
class RouteGroup
{
    /**
     * route instance array
     *
     * @var array<Route>
     */
    protected array $routes = [];

    public function __construct(
        protected string $name = '',
        protected array $options = []
    ) {
    }

    public function bindRoute(
        Route $route
    ): RouteGroup {
        $this->routes[] = $route;
        return $this;
    }

    public function registerRoutes()
    {
        $routes = $this->routes;
        $ciRoutes = Services::routes();
        $ciRoutes->group(
            $this->name,
            $this->options,
            function (RouteCollection $groupRoutes) use ($routes) {
                foreach ($routes as $route) {
                    $route->register($groupRoutes);
                }
            }
        );
    }
}
