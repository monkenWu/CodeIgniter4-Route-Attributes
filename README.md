# CodeIgniter4-Route-Attribute

You can use this library to make CodeIgniter4 able to define routing settings of controllers through comments.

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
    ```
2. Automatically write the library needed events into the endpoint of file `app/Config/Events.php`, the event will be used when CodeIgniter4 initializes, automatically registering routes. The command will write the contents in as below:
    ```php
    Events::on('pre_system', function(){
        \monken\Ci4RouteAttributes\RouteAttributes::runHandler();
    });
    ```

## Instructions

In short, this library is a [CodeIgniter4 Router](https://codeigniter.tw/user_guide/incoming/routing.html) presentative way under the PHP8 Attributes feature, it merely provides litte mapping and encapsulation for some CodeIgniter4 Router methods. Other than that, there's no other extra functionalities.

By means of scanning the comments automatically inside the Controller, routes and methods will be connected, enables you to write routing rules straightforwardly, and maintain the relationship between Controllers and Routes in a convenient way.

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

According to the [Router Global Options](https://codeigniter.tw/user_guide/incoming/routing.html#id15) of the CodeIgniter4, you can use the following verbs: `add`, `get`, `post`, `put`, `head`, `options`, `delete`, `path`, and `cli`.
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

CodeIgniter4 offers convenient [RESTful patterns](https://codeigniter.tw/user_guide/incoming/restful.html) for you to inherit related class to quickly achieve RESTful design pattern. 
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

You can adjust RouteRESTful `type` base on your need, through setting up your [ResourceRoute](https://codeigniter.tw/user_guide/incoming/restful.html#id2) or [PresenterRoute](https://codeigniter.tw/user_guide/incoming/restful.html#id7).

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

For acceptable method names, please refer to our [documentation](https://codeigniter.tw/user_guide/incoming/restful.html#id5).

#### except

You can use `except` to remove production of some routes, this parameter only accepts one array, composed of method names.

```php
#[RouteRESTful(name: 'api/user', except: [
    'new', 'edit'
])]
```

For acceptable method names, please refer to our [documentation](https://codeigniter.tw/user_guide/incoming/restful.html#id5).

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
