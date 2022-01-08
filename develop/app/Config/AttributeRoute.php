<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class AttributeRoute extends BaseConfig
{

    /**
     * auto scan attribute namespaces
     *
     * @var array<string>
     */
    public array $controllerNamespaces = [
        "App\Controllers"
    ];

}
