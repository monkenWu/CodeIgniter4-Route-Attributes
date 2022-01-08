<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class RouteAttributes extends BaseConfig
{

    /**
     * autoscan namespaces
     *
     * @var array<string>
     */
    public array $controllerNamespaces = [
        "App\Controllers"
    ];

}
