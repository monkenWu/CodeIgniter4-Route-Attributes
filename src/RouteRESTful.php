<?php

namespace monken\Ci4RouteAttributes;

use Config\Services;
use CodeIgniter\Router\RouteCollection;
use monken\Ci4RouteAttributes\Exception\RouteRESTfulException;

#[\Attribute(\Attribute::TARGET_CLASS)]
class RouteRESTful implements RouteInterface
{
    protected $allowType = [
        "resource", "presenter"
    ];
    protected $className;

    /**
     * Register RESTful routes
     *
     * @param string $name resource/presenter name or route path
     * @param string $type Available types: resource, presenter
     * @param boolean|null $websafe
     * @param array|null $only
     * @param array|null $except
     * @param string|null $placeholder
     */
    public function __construct(
        protected string $name,
        protected string $type,
        protected ?bool $websafe = null,
        protected ?array $only = null,
        protected ?array $except = null,
        protected ?string $placeholder = null
    ) {
        if (!in_array($type, $this->allowType)) {
            throw RouteRESTfulException::forAllowType($type, $name);
        }
    }

    public function bind(
        string $className,
    ): RouteInterface {
        $this->className = $className;
        return $this;
    }

    public function register(?RouteCollection $routes = null): RouteInterface
    {
        if (is_null($routes)) {
            $routes = Services::routes();
        }
        $options = ["controller" => $this->className];
        if ($this->websafe === true && $this->type === 'resource') $options['websafe'] = true;
        if (is_array($this->only))  $options['only'] = $this->only;
        if (is_array($this->except))  $options['except'] = $this->except;
        if (is_string($this->placeholder))  $options['placeholder'] = $this->placeholder;

        $routes->{$this->type}(
            $this->name,
            $options
        );

        return $this;
    }
}
