<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class RouteAttributes extends BaseConfig
{

    /**
     * Routes are automatically registered only if this is set to `true`
     *
     * @var boolean
     */
    public bool $enabled = true;

    /** 
     * autoscan namespaces
     *
     * @var array<string>
     */
    public array $controllerNamespaces = [
        "App\Controllers"
    ];

    /**
     * Generate production environment route definition file path
     *
     * @var string
     */
    public string $routeDefinitionFilePath = WRITEPATH . 'cache';

    /**
     * Whether to use pre-generated definition files in production.
     * Note that when this option is set to `true`, controller files will not be automatically
     * scanned in production environment. You must use `route-attr:make` command to generate
     * route definition files to improve performance in production environment.
     *
     * @var boolean
     */
    public bool $productionUseDefinitionFile = true;
}
