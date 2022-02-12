<?php

namespace monken\Ci4RouteAttributes;

use Config\Services;
use CodeIgniter\Router\RouteCollection;
use monken\Ci4RouteAttributes\Exception\RouteException;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Route implements RouteInterface
{
    protected $allowMethod = [
        "add", "get", "post", "put", "head", "options", "delete", "patch", "cli"
    ];
    protected $className;
    protected $methodName;
    protected $parametersString;

    public function __construct(
        protected string $path = '',
        protected array $methods = [],
        protected ?array $options = null,
        public bool $ignoreGroup = false
    ) {
        foreach ($methods as $method) {
            if (!in_array($method, $this->allowMethod)) {
                throw RouteException::forAllowMethod($path, $method);
            }
        }
    }

    public function bind(
        string $className,
        ?string $methodName = null,
        ?int $parametersCount = null
    ): Route {
        $this->className = strpos($className, '\\') === 0 ? $className : "\\{$className}";
        $this->methodName = $methodName;
        $this->parametersString = $this->getParametersString($parametersCount ?? 0);
        return $this;
    }

    public function register(?RouteCollection $routes = null): RouteInterface
    {
        if (is_null($routes)) {
            $routes = Services::routes();
        }
        foreach ($this->methods as $method) {
            $routes->{$method}(
                $this->path,
                "{$this->className}::{$this->methodName}{$this->parametersString}",
                $this->options
            );
        }
        return $this;
    }

    protected function getParametersString(int $count): string
    {
        $parametersString = "";
        if ($count > 0) {
            $parametersString = "/";
            $tags = [];
            for ($i = 1; $i <= $count; $i++) {
                $tags[] = '$' . $i;
            }
            $parametersString .= implode("/", $tags);
        }
        return $parametersString;
    }
}
