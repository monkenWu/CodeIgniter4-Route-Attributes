<?php

namespace monken\Ci4AttributeRoute;

use HaydenPierce\ClassFinder\ClassFinder;

class AttributeRoute
{
    /**
     * Route Instance Array
     *
     * @var array<\monken\Ci4AttributeRoute\Route>
     */
    protected static array $routeInstances = [];

    public static function runHandler()
    {
        /**
         * @var \Config\AttributeRoute
         */
        $config = config("AttributeRoute");
        foreach ($config->controllerNamespaces as $namespace) {
            static::reflectionControllerClasses($namespace);
        }
        if (ENVIRONMENT === 'production' && $config->productionCache) {
        }
        static::registerRoutes();
    }

    protected static function reflectionControllerClasses(string $namespace)
    {
        $classes = ClassFinder::getClassesInNamespace($namespace);
        foreach ($classes as $class) {
            $controller = new \ReflectionClass($class);
            $methods = $controller->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $method) {
                $attributes = $method->getAttributes(Route::class);
                foreach ($attributes as $attribute) {
                    static::$routeInstances[] = $attribute->newInstance()->bindMethod(
                        $class,
                        $method->name,
                        count($method->getParameters())
                    );
                }
            }
        }
    }

    protected static function registerRoutes()
    {
        foreach (static::$routeInstances as $route) {
            $route->register();
        }
    }

}
