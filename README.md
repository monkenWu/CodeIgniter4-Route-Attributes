# CodeIgniter4-Route-Attribute

[![Latest Stable Version](http://poser.pugx.org/monken/ci4-route-attributes/v)](https://packagist.org/packages/monken/ci4-route-attributes) [![Total Downloads](http://poser.pugx.org/monken/ci4-route-attributes/downloads)](https://packagist.org/packages/monken/ci4-route-attributes) [![Latest Unstable Version](http://poser.pugx.org/monken/ci4-route-attributes/v/unstable)](https://packagist.org/packages/monken/ci4-route-attributes) [![License](http://poser.pugx.org/monken/ci4-route-attributes/license)](https://packagist.org/packages/monken/ci4-route-attributes) [![PHP Version Require](http://poser.pugx.org/monken/ci4-route-attributes/require/php)](https://packagist.org/packages/monken/ci4-route-attributes)

You can use this library to make CodeIgniter4 able to define routing settings of controllers through comments.

[中文手冊](README_zh-TW.md)

<!-- TOC -->

- [CodeIgniter4-Route-Attribute](#codeigniter4-route-attribute)
    - [Quick demo](#quick-demo)
    - [Installation Guide](#installation-guide)
        - [Requirements](#requirements)
        - [Composer Install](#composer-install)
    - [Instructions](#instructions)
        - [Production and Development Environment](#production-and-development-environment)
            - [Configuration File](#configuration-file)
            - [Generate Route Attribute Definition File](#generate-route-attribute-definition-file)
            - [Update Route Attribute Definition File](#update-route-attribute-definition-file)
        - [Route](#route)
            - [options](#options)
            - [ignoreGroup](#ignoregroup)
            - [Placeholder](#placeholder)
            - [Single Method to declare multiple Routes](#single-method-to-declare-multiple-routes)
        - [RouteRESTful](#routerestful)
            - [Resource Route](#resource-route)
            - [Presenter Route](#presenter-route)
            - [websafe](#websafe)
            - [only](#only)
            - [except](#except)
            - [placeholder](#placeholder)
            - [options](#options-1)
            - [ignoreGroup](#ignoregroup-1)
        - [RouteGroup](#routegroup)
        - [RouteEnvironment](#routeenvironment)

<!-- /TOC -->

## Quick demo 

```php
namespace App\Controllers;

use monken\Ci4RouteAttributes\Route;

class Ci4Controller extends BaseController
{
    #[Route(path: 'attr/route', methods: ["get"])]
    public function hello()
    {
        return "PHP8Attributes";
    }
}

```

Use the definition of `#[Route(path: 'attr/route', methods: ["get"])]`, means the same settings were done in your routing configuration:

```php
$routes->get('attr/route', 'App\Controllers\Ci4Controller::hello');
```

This library will smartly connect your controller and routing automatically, which means you can access to the `hello` method in `Ci4Controller` through the path of `/attr/route`.

## Installation Guide

### Requirements

1. CodeIgniter Framework 4
2. Composer
3. PHP8↑

### Composer Install

Use Composer to download the library needed dependency under the project root.

```
composer require monken/ci4-route-attributes
```

Use the library built-in command to initialize the needed files.

```
php spark route-attr:init
```

The upper command will make to changes on our project.

1. `app/Config` will have a `RouteAttributes.php` configuration file, you can adjust the library's execution setting through this file. And it looks like this:
    ```php
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
        * Whether to use pre-generated route definition files in production.
        * Note that when this option is set to `true`, controller files will not be automatically
        * scanned in production environment. You must use `route-attr:make` command to generate
        * route definition files to improve performance in production environment.
        *
        * @var boolean
        */
        public bool $productionUseDefinitionFile = true;
    }
    ```
2. Automatically write the library needed events into the endpoint of file `app/Config/Events.php`, the event will be used when CodeIgniter4 initializes, automatically registering routes. The command will write the contents in as below:
    ```php
    Events::on('pre_system', function(){
        \monken\Ci4RouteAttributes\RouteAttributes::runHandler();
    });
    ```

## Instructions

In short, this library is a [CodeIgniter4 Router](https://codeigniter.com/user_guide/incoming/routing.html) presentative way under the PHP8 Attributes feature, it merely provides litte mapping and encapsulation for some CodeIgniter4 Router methods. Other than that, there's no other extra functionalities.

By means of scanning the comments automatically inside the Controller, routes and methods will be connected, enables you to write routing rules straightforwardly, and maintain the relationship between Controllers and Routes in a convenient way.

### Production and Development Environment

When you are using this library in Development environment under CodeIgnitere4 framework, it will re-analyze all Controllers classes everytime when a request should occur, and meanwhile handle with the correspond Route Attribures. This strategy can bring maximum convenience to developing, changes of Route Attributes will take effect immediately. However, in production environment, this strategy will cause considerale performance loss. Therefore our library provides a cache-like method to lower the performance loss aiming at production environment.

#### Configuration File

You can find the two adjustable variables, `routeDefinitionFilePath` and `productionUseDefinitionFile`, in `app/Config/RouteAttributes.php`.

You can use `routeDefinitionFilePath` to define storage location of your configuration file for production environment, it will be placed at `project_root/writable/cache` as default.

You can change `productionUseDefinitionFile` as `true` or `false` to define whether to activate Route Attributes definition file in production environment or not to achieve the best performance. If it's `false`, then on every request of production environment, they will be re-scanned and Route Attributes inside Controllers will be handled.

#### Generate Route Attribute Definition File

You can generate your Route Attribute Definition File using the command below, to lower the performance loss: 

```
php spark route-attr:make
```

Upper mentioned command will generate a `RouteAttributesDefinition` file under the path defined in `routeDefinitionFilePath`.

#### Update Route Attribute Definition File

There are two ways to update the Route Attributes Definition File for your Production environment

1. Run `php spark route-attr:make` again, new contents will directly cover the old ones.
2. Delete `RouteAttributesDefinition` file, if the library couldn't find the file, it will scan and generate a Route Attribute Definition File automatically.

### Route

You can register your routes like this:

```php
namespace App\Controllers;

use monken\Ci4RouteAttributes\Route;

class Ci4Controller extends BaseController
{
    #[Route(path: 'attr/route', methods: ["get"])]
    public function hello()
    {
        return "PHP8Attributes";
    }
}
```

In this example, `Path` represents the acutal path to call this Controller method, and `method` will expect you to pass a String array, including the HTTP verbs to access to this Controller method.

According to the [Router Global Options](https://codeigniter.com/user_guide/incoming/routing.html#global-options) of the CodeIgniter4, you can use the following verbs: `add`, `get`, `post`, `put`, `head`, `options`, `delete`, `path`, and `cli`.
You can declare several verbs to achieve the effect of switching between different `method` under the same `path` with access to the identical Controller effects.

#### options

You can pass in options array to make special adjustments to routes, the library won't do any processing or judging to your options, this means you must consult the CodeIgniter4 documentation to write the correct options. Usually, this parameter will be like this:

```php
#[Route(path: 'attr/route', methods: ["get"], options:[
    'filter' => 'auth',
    'hostname' => 'accounts.example.com'
])]
```

#### ignoreGroup

If you are using `RouteGroup` to configure routes under the same controller uniformly, but wanting to set one of them apart without extending `RouteGroup`. You can set this parameter to `true`, making routing an independent job like this:

```php=
#[Route(path: 'attr/route', methods: ["get"], ignoreGroup: true)]
```

#### Placeholder

You only need to concentrate on the placeholder configuration in your `path`, the library will determine the parameter amount of your controller and finish the correct route settings.

```php
<?php

namespace App\Controllers;

use monken\Ci4RouteAttributes\Route;

class Ci4Controller extends BaseController
{
    #[Route(path: 'test/(:segment)/(:segment)/(:segment)', methods: ["get"])]
    public function hello($a, $b, $c)
    {
        echo $a . '<br>';
        echo $b . '<br>';
        echo $c . '<br>';
    }

}
```

Equals to:

```php
$route->get('test/(:segment)/(:segment)/(:segment)', 'App\Controllers\Ci4Controller::hello/$1/$2/$3');
```

#### Single Method to declare multiple Routes


If you need, you can also tie several route settings to a single Method.

```php
namespace App\Controllers;

use monken\Ci4RouteAttributes\Route;

class Ci4Controller extends BaseController
{
    #[Route(path: 'attr/route', methods: ["get"])]
    #[Route(path: 'hello/msg', methods: ["get"])]
    public function hello()
    {
        return "PHP8Attributes";
    }
}
```

Through the upper settings, no matter accessing to `attr/route` or `hello/msg`, they are all pointing at the same Ci4Controller's hello method.

### RouteRESTful

CodeIgniter4 offers convenient [RESTful patterns](https://codeigniter.com/user_guide/incoming/restful.html) for you to inherit related class to quickly achieve RESTful design pattern. 
This library also provides related patterns for you to transform your controller into RESTful routes rapidly.

#### Resource Route

```php
<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use monken\Ci4RouteAttributes\RouteRESTful;

#[RouteRESTful(name: 'api/user', type: 'resource')]
class UserApi extends ResourceController
{
    //...
}
```

Equals to:

```php
$routes->resource('api/user', [
    "controller" => 'App\Controllers\UserApi'
]);
```

`name` means the resource name, can also be declared as a path.
There are two available options on `type`, naming `resource` and `presenter`.

#### Presenter Route

You can adjust the value of `type` to switch RouteRESTful into Presenter Route pattern.

```php
<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourcePresenter;
use monken\Ci4RouteAttributes\RouteRESTful;

#[RouteRESTful(name: 'user', type: 'presenter')]
class User extends ResourcePresenter
{
    //...
}
```


Equals to:

```php
$routes->presenter('user', [
    "controller" => 'App\Controllers\User'
]);
```

You can adjust RouteRESTful `type` base on your need, through setting up your [ResourceRoute](https://codeigniter.com/user_guide/incoming/restful.html#resource-routes) or [PresenterRoute](https://codeigniter.com/user_guide/incoming/restful.html#presenter-routes).

#### websafe

This option will be activated only when `type` adopts `resource`. It will add `websafe =>1` into the route's `options` to make it available for HTML forms.

```php
#[RouteRESTful(name: 'api/user', websafe: true)]
```

#### only

You can use `only` option to restrict only generate the route you've mentioned. This parameter only accept one array, composed of method names.

```php
#[RouteRESTful(name: 'api/user', only: [
    'index', 'show'
])]
```

For acceptable method names, please refer to our [documentation](https://codeigniter.com/user_guide/incoming/restful.html#limit-the-routes-made).

#### except

You can use `except` to remove production of some routes, this parameter only accepts one array, composed of method names.

```php
#[RouteRESTful(name: 'api/user', except: [
    'new', 'edit'
])]
```

For acceptable method names, please refer to our [documentation](https://codeigniter.com/user_guide/incoming/restful.html#limit-the-routes-made).

#### placeholder

If your API needs the resource ID, `(:segment)` placeholder will be used as default. But you can also pass `placeholder` parameter to make changes to it:

```php
#[RouteRESTful(name: 'api/user', placeholder: ':(num)')]
```

#### options

Through passing in the options array to do particular revision aiming at the RESTful routes, the library won't do any judgement to your passed options. 


One thing should pay extra attention, if parameters like `websafe`, `only`, `except`, or `placeholder` were used, then the library will automatically compose the contents you've passed in with the `options` array. If there's replicated declarations being made, the parameters' content will be focused.

You must refer to the CodeIgniter4 documentation to write the correct options. Usually, the usage if this parameter will look loke this:

```php
#[RouteRESTful(name: 'api/user', placeholder: ':(num)', options: [
    'filter' => 'auth'
])]
```

#### ignoreGroup

If you are using `RouteGroup` to configure routes under the same controller uniformly, but wanting to set one of them apart without extending `RouteGroup`. You can set this parameter to `true`:

```php
#[RouteRESTful(name: 'api/user', ignoreGroup: true)]
```

### RouteGroup

Usually, you will wish not to re-configure the duplicated `path`, such as `/api/v1`. Hence you can make use of `RouteGroup` to uniformly apply the same `path` or `options` to all routing settings under the class.

```php
<?php

namespace App\Controllers;

use monken\Ci4RouteAttributes\Route;
use monken\Ci4RouteAttributes\RouteGroup;

#[RouteGroup(name: '/route/testgroup', options: ['filter' => 'auth'])]
class Group extends BaseController
{

    #[Route(path: 'getindex', methods: ['get'])]
    public function index()
    {
        return "hi";
    }

    #[Route(path: 'get/something', methods: ['get'])]
    public function somefunction()
    {
        return "something";
    }
}

```

Upper settings equals to:

```php
$routes->group(
    '/route/testgroup',
    ['filter' => 'auth'],
    function ($routes) {
        $routes->get('getindex', 'App\Controllers\Group ::index');
        $routes->get('get/something', 'App\Controllers\Group ::somefunction');
    }
);
```
### RouteEnvironment

You can create special routes for specified environment, for instance, routes for development will be unavailable in production and staging environment. This requirement can be done through declaring `RouteEnvironment` in your class.

```php
<?php

namespace App\Controllers;

use monken\Ci4RouteAttributes\Route;

#[RouteEnvironment(type: "development")]
class EnvRoute extends BaseController
{

    #[Route(path:'dev/tool', methods:['cli'])]
    public function devToolMethod()
    {
        return "tool msg";
    }

    #[Route(path:'dev/page', methods:['get'])]
    public function devPageMethod()
    {
        return "page msg";
    }

```

Upper setting equals to:

```php
$routes->environment('development', function ($routes) {
    $routes->cli('dev/tool', 'App\Controllers\EnvRoute::devToolMethod');
    $routes->get('dev/page', 'App\Controllers\EnvRoute::devPageMethod');
});
```

If you need, `RouteEnvironment` can also work with `RouteGroup`:

```php
<?php

namespace App\Controllers;

use monken\Ci4RouteAttributes\Route;
use monken\Ci4RouteAttributes\RouteGroup;

#[RouteEnvironment(type: "development")]
#[RouteGroup('/dev')]
class EnvRoute extends BaseController
{

    #[Route(path:'tool', methods:['cli'])]
    public function devToolMethod()
    {
        return "tool msg";
    }

    #[Route(path:'page', methods:['get'])]
    public function devPageMethod()
    {
        return "page msg";
    }

```

Upper setting equals to:

```php
$routes->environment('development', function ($routes) {
    $routes->group(
        '/dev',
        function ($routes) {
            $routes->cli('tool', 'App\Controllers\EnvRoute::devToolMethod');
            $routes->get('page', 'App\Controllers\EnvRoute::devPageMethod');
        }
    );
});
```