<?php

namespace monken\Ci4RouteAttributes;

use HaydenPierce\ClassFinder\ClassFinder;
use monken\Ci4RouteAttributes\RouteDefinition;
use monken\Ci4RouteAttributes\RouteEnvironment;

class RouteAttributes
{

    /**
     * reflectionClass instance array
     *
     * @var array<string,\ReflectionClass>
     */
    protected static array $reflectionClassInstances = [];

    /**
     * RouteDefinition instance array
     *
     * @var array<RouteDefinition>
     */
    protected static array $routeDefinitionInstances = [];

    public static function runHandler(?\Config\RouteAttributes $config = null)
    {
        if(is_null($config)){
            $config = config("RouteAttributes");
        }

        if ($config->enabled === false) return;

        if (
            ENVIRONMENT === 'production' &&
            $config->productionUseDefinitionFile === true
        ) {
            static::readRouteDefinitionFile($config);
        } else {
            static::init($config);
        }

        //register route
        foreach (self::$routeDefinitionInstances as $routeDefinition) {
            $routeDefinition->registerRouteSetting();
        }
    }

    public static function generateRouteDefinition(\Config\RouteAttributes $config): bool
    {
        if (empty(self::$reflectionClassInstances)) self::init($config);
        $definitionString = serialize(self::$routeDefinitionInstances);
        try {
            file_put_contents(
                $config->routeDefinitionFilePath . DIRECTORY_SEPARATOR . 'RouteAttributesDefinition',
                $definitionString
            );
        } catch (\Throwable $th) {
            log_message('error', $th->getMessage());
            return false;
        }
        return true;
    }

    protected static function readRouteDefinitionFile(\Config\RouteAttributes $config)
    {
        $fileName = $config->routeDefinitionFilePath . DIRECTORY_SEPARATOR . 'RouteAttributesDefinition';
        if (!file_exists($fileName)) self::generateRouteDefinition($config);
        $routeDefinitionString = file_get_contents($fileName);
        self::$routeDefinitionInstances = unserialize($routeDefinitionString);
    }

    protected static function init(\Config\RouteAttributes $config)
    {
        self::$reflectionClassInstances = [];
        self::$routeDefinitionInstances = [];
        //reflection controller
        foreach ($config->controllerNamespaces as $namespace) {
            static::reflectionControllerClasses($namespace);
        }
        //handle attributes
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

        $envAttributes = $controller->getAttributes(RouteEnvironment::class);
        if (count($envAttributes) === 1) {
            $routeDefinition->setRouteEnvironment($envAttributes[0]->newInstance());
        }

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

        self::$routeDefinitionInstances[] = $routeDefinition;
    }
}
