<?php

namespace monken\Ci4AttributeRoute;

use Config\Services;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Route
{
    protected $className;
    protected $methodName;
    protected $parametersString;

    public function __construct(
        protected string $path = '',
        protected array $methods = [],
        protected ?array $options = null
    ) {
    }

    public function bindMethod(
        string $className,
        string $methodName,
        int $parametersCount
    ):Route {
        $this->className = $className;
        $this->methodName = $methodName;
        $this->parametersString = $this->getParametersString($parametersCount);
        return $this;
    }

    public function register()
    {
        $routes = Services::routes();
        foreach ($this->methods as $method) {
            $routes->{$method}(
                $this->path,
                "{$this->className}::{$this->methodName}{$this->parametersString}",
                $this->options
            );
        }
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
