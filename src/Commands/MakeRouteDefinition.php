<?php

namespace monken\Ci4RouteAttributes\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use monken\Ci4RouteAttributes\RouteAttributes;

class MakeRouteDefinition extends BaseCommand
{
    protected $group       = 'route-attr';
    protected $name        = 'route-attr:make';
    protected $description = 'Generate production environment route definition file path.';

    public function run(array $params)
    {
        CLI::write(
            CLI::color("Generate route definition file......\n", "blue")
        );

        if(RouteAttributes::generateRouteDefinition(config("RouteAttributes"))){
            CLI::write(
                CLI::color("Generated successfully!\n", 'green')
            );    
        }else{
            CLI::write(
                CLI::color("Generated faild, please read the log file.\n", 'red')
            );    
        }
    }
}
