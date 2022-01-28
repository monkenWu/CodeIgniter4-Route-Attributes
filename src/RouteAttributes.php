<?php

namespace monken\Ci4RouteAttributes;

use HaydenPierce\ClassFinder\ClassFinder;
use monken\Ci4RouteAttributes\RouteDefinition;

class RouteAttributes
{

    /**
     * Undocumented variable
     *
     * @var array<string,\ReflectionClass>
     */
    protected static array $reflectionClassInstances = [];

    public static function runHandler()
    {
        /**
         * @var \Config\RouteAttributes
         */
        $config = config("RouteAttributes");
        if ($config->enabled) {
            foreach ($config->controllerNamespaces as $namespace) {
                static::reflectionControllerClasses($namespace);
            }
        }

        foreach (self::$reflectionClassInstances as $className => $controller) {
            self::handleClassAttributes($className, $controller);
        }
    }

    /**
     * Automatically scan the route-attributes under the namespace.
     *
     * @param string $namespace
     * @return void
     */
    protected static function reflectionControllerClasses(string $namespace)
    {
        $classes = ClassFinder::getClassesInNamespace($namespace);
        foreach ($classes as $class) {
            self::$reflectionClassInstances[$class] = new \ReflectionClass($class);
        }
    }

    protected static function handleClassAttributes(
        string $className,
        \ReflectionClass $controller
    ) {
        $routeDefinition = new RouteDefinition();

        $groupAttributes = $controller->getAttributes(RouteGroup::class);
        if (count($groupAttributes) === 1) {
            $routeDefinition->setRouteGroup($groupAttributes[0]->newInstance());
        }

        $RESTfulpAttributes = $controller->getAttributes(RouteRESTful::class);
        if (count($RESTfulpAttributes) === 1) {
            $RESTfulRoute = $RESTfulpAttributes[0]->newInstance()->bind($className);
            if ($routeDefinition->getRouteGroup() && $RESTfulRoute->ignoreGroup === false) {
                $routeDefinition->getRouteGroup()->bindRoute($RESTfulRoute);
            } else {
                $routeDefinition->setRouteRESTful($RESTfulRoute);
            }
        }

        $methods = $controller->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            $attributes = $method->getAttributes(Route::class);
            foreach ($attributes as $attribute) {

                $route = $attribute->newInstance()->bind(
                    $className,
                    $method->name,
                    count($method->getParameters())
                );

                if ($routeDefinition->getRouteGroup() && $route->ignoreGroup === false) {
                    $routeDefinition->getRouteGroup()->bindRoute($route);
                } else {
                    $routeDefinition->addRoute($route);
                }
            }
        }

        // if ($group) $group->registerRoutes();
    }

    // protected static function handleClassAttributes(
    //     string $className,
    //     \ReflectionClass $controller
    // ) {
    //     $groupAttributes = $controller->getAttributes(RouteGroup::class);
    //     $group = null;
    //     if (count($groupAttributes) === 1) {
    //         $group = $groupAttributes[0]->newInstance();
    //     }

    //     $RESTfulpAttributes = $controller->getAttributes(RouteRESTful::class);
    //     if (count($RESTfulpAttributes) === 1) {
    //         $RESTfulRoute = $RESTfulpAttributes[0]->newInstance()->bind($className);
    //         if ($group && $RESTfulRoute->ignoreGroup === false) {
    //             $group->bindRoute($RESTfulRoute);
    //         } else {
    //             $RESTfulRoute->register();
    //         }
    //     }

    //     $methods = $controller->getMethods(\ReflectionMethod::IS_PUBLIC);
    //     foreach ($methods as $method) {
    //         $attributes = $method->getAttributes(Route::class);
    //         foreach ($attributes as $attribute) {

    //             $route = $attribute->newInstance()->bind(
    //                 $className,
    //                 $method->name,
    //                 count($method->getParameters())
    //             );

    //             if ($group && $route->ignoreGroup === false) {
    //                 $group->bindRoute($route);
    //             } else {
    //                 $route->register();
    //             }
    //         }
    //     }

    //     if ($group) $group->registerRoutes();
    // }
}
