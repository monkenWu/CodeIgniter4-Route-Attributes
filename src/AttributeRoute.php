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

    public static function handler()
    {
        static::reflectionControllerClasses();
        static::registerRoutes();
    }

    protected static function reflectionControllerClasses()
    {
        $classes = ClassFinder::getClassesInNamespace('App\Controllers');
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
