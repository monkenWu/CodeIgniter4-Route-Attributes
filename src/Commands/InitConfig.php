<?php

namespace monken\Ci4RouteAttributes\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class InitConfig extends BaseCommand
{
    protected $group       = 'route-attr';
    protected $name        = 'route-attr:init';
    protected $description = 'Initialize Ci4-Route-Attributes required files.';

    public function run(array $params)
    {
        CLI::write(
            CLI::color("Copying  Ci4-Route-Attributes configuration file ......\n", "blue")
        );
        copy(
            dirname(__FILE__) . DIRECTORY_SEPARATOR . "files" . DIRECTORY_SEPARATOR . "RouteAttributes",
            APPPATH . 'Config' . DIRECTORY_SEPARATOR . "RouteAttributes.php"
        );

        CLI::write(
            CLI::color("Writing Ci4-Route-Attributes events to event configuration file ......\n", "blue")
        );
        file_put_contents(
            APPPATH . 'Config' . DIRECTORY_SEPARATOR . "Events.php",
            file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . "files" . DIRECTORY_SEPARATOR . "Events"),
            FILE_APPEND
        );

        CLI::write(
            CLI::color("Initialization successful!\n", 'green')
        );
    }
}
