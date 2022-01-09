<?php

namespace monken\Ci4RouteAttributes;

use HaydenPierce\ClassFinder\ClassFinder;

class RouteAttributes
{

    public static function runHandler()
    {
        /**
         * @var \Config\RouteAttributes
         */
        $config = config("RouteAttributes");
        foreach ($config->controllerNamespaces as $namespace) {
            static::reflectionControllerClasses($namespace);
        }
    }

    protected static function reflectionControllerClasses(string $namespace)
    {
        $classes = ClassFinder::getClassesInNamespace($namespace);
        foreach ($classes as $class) {

            $controller = new \ReflectionClass($class);

            $groupAttributes = $controller->getAttributes(RouteGroup::class);
            $group = null;
            if (count($groupAttributes) === 1) {
                $group = $groupAttributes[0]->newInstance();
            }

            $RESTfulpAttributes = $controller->getAttributes(RouteRESTful::class);
            if (count($RESTfulpAttributes) === 1) {
                $RESTfulRoute = $RESTfulpAttributes[0]->newInstance()->bind($class);
                if ($group) {
                    $group->bindRoute($RESTfulRoute);
                } else {
                    $RESTfulRoute->register();
                }
            }

            $methods = $controller->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $method) {
                $attributes = $method->getAttributes(Route::class);
                foreach ($attributes as $attribute) {

                    $route = $attribute->newInstance()->bind(
                        $class,
                        $method->name,
                        count($method->getParameters())
                    );

                    if ($group) {
                        $group->bindRoute($route);
                    } else {
                        $route->register();
                    }
                }
            }

            if ($group) $group->registerRoutes();
        }
    }

}
