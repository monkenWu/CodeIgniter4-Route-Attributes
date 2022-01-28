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

}
