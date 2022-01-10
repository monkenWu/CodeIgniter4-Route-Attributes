# CodeIgniter4-Route-Attribute

[![Latest Stable Version](http://poser.pugx.org/monken/ci4-route-attributes/v)](https://packagist.org/packages/monken/ci4-route-attributes) [![Total Downloads](http://poser.pugx.org/monken/ci4-route-attributes/downloads)](https://packagist.org/packages/monken/ci4-route-attributes) [![Latest Unstable Version](http://poser.pugx.org/monken/ci4-route-attributes/v/unstable)](https://packagist.org/packages/monken/ci4-route-attributes) [![License](http://poser.pugx.org/monken/ci4-route-attributes/license)](https://packagist.org/packages/monken/ci4-route-attributes) [![PHP Version Require](http://poser.pugx.org/monken/ci4-route-attributes/require/php)](https://packagist.org/packages/monken/ci4-route-attributes)

你可以使用這個程式庫讓 CodeIgniter4 能夠以註解來定義控制器的路由設定。

## 快速演示

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

透過 `#[Route(path: 'attr/route', methods: ["get"])]` 的定義，這等同於你在路由的組態設定檔案做了這件事：

```php
$routes->get('attr/route', 'App\Controllers\Ci4Controller::hello');
```

這個程式庫會聰明地替你自動繫結控制器與路由的關係，這代表你可以透過 `/attr/route` 路徑存取 `Ci4Controller` 的 `hello` 方法。

## 安裝指引

### 需求

1. CodeIgniter Framework 4
2. Composer
3. PHP8↑

### Composer 安裝

於專案根目錄下，使用 Composer 下載程式庫與其所需之依賴。

```
composer require monken/ci4-route-attributes
```

使用程式庫提供的內建指令初始化所需的檔案。

```
php spark route-attr:init
```

上述指令會讓專案產生兩個改變。

1. `app/Config` 將會出現 `RouteAttributes.php` 組態設定檔案，你能夠透過該檔案調整程式庫的執行設定。這個檔案長得會像這個樣子：
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
2. 自動將程式庫所需的事件寫入 `app/Config/Events.php` 末端，這個事件用於在 CodeIgniter4 初始化時自動註冊路由。指令將自動寫入以下內容：
    ```php
    Events::on('pre_system', function(){
        \monken\Ci4RouteAttributes\RouteAttributes::runHandler();
    });
    ```

## 使用說明

簡而言之，這個程式庫是 [CodeIgniter4 Router](https://codeigniter.tw/user_guide/incoming/routing.html) 在 PHP8 Attributes 特性下的一種呈現方式，它僅提供某些 CodeIgniter4 Router 方法的映射與封裝，除此之外並沒有其他的額外功能。

它藉由自動掃描控制器中的註解，自動將路由與方法進行繫結，使你能夠直觀的撰寫路由規則，並便利地維護控制器與路由間的關係。

### Route

你可以這麼註冊你的路由：

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

在這個範例下，`Path` 代表的是實際呼叫這個控制器方法所需要的路徑，而 `method` 則預期你會傳入一個由字串組成的陣列，這些字串代表著的是能夠存取這個控制器方法的 HTTP 動詞。

依照 CodeIgniter4 的 [Router 全域選項](https://codeigniter.tw/user_guide/incoming/routing.html#id15)，你可以使用下列動詞： `add` 、 `get` 、 `post` 、 `put` 、 `head` 、 `options` 、 `delete` 、 `patch` ，以及 `cli` 。你可以在陣列中宣告多個動詞，以達到在相同的 `path` 下切換不同的 `method` 存取相同的控制器的效果。

#### options

你可以透過傳入 options 陣列來針對路由做特殊的調整，程式庫並不會對你所傳入的 options 做任何的處理或判斷，這意味著你必須參考 CodeIgniter4 的說明書撰寫正確的 options。通常，這個參數使用起來會像這個樣子。

```php
#[Route(path: 'attr/route', methods: ["get"], options:[
    'filter' => 'auth',
    'hostname' => 'accounts.example.com'
])]
```

#### ignoreGroup

若你有使用 `RouteGroup` 來統一設定同一控制器類別下的路由，但某個路由你希望獨立開來，不沿用 `RouteGroup` 。這時你就可以將這個參數設定為 `true` ，它會讓路由以完全獨立的方式進行設定：

```php=
#[Route(path: 'attr/route', methods: ["get"], ignoreGroup: true)]
```

#### 置換符號

你只需要關注 `path` 中的置換符號設定，程式庫會自動地判斷控制器方法的參數數量，並正確設定路由。

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

這等同於：

```php
$route->get('test/(:segment)/(:segment)/(:segment)', 'App\Controllers\Ci4Controller::hello/$1/$2/$3');
```

#### 單一 Method 宣告多個路由

若是你需要，你也可以針對單一 Method 繫結多個路由設定。

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

透過上述設定，不論是存取 `attr/route` 還是 `hello/msg` 都會指向同一個 Ci4Controller 的 hello 方法。

### RouteRESTful

CodeIgniter4 提供了很方便地 [RESTful 模式](https://codeigniter.tw/user_guide/incoming/restful.html)，你可以透過繼承相關類別來快速達成 RESTful 設計模式。在路由的設定上本程式庫也提供了相關的模式使你快速將控制器變為 RESTful 路由。

#### 資源路由

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

這等同於：

```php
$routes->resource('api/user', [
    "controller" => 'App\Controllers\UserApi'
]);
```

`name` 代表的是資源名稱，也可以當成是 path 進行宣告，`type` 則有兩個可用選項 `resource` 與 `presenter` 。

#### 表現層路由

你可以透過調整 `type` 的數值，來使 RouteRESTful 切換成表現層路由的模式。

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


這等同於：

```php
$routes->presenter('user', [
    "controller" => 'App\Controllers\User'
]);
```

你可以依據你的需求調整 RouteRESTful 的 `type` ，藉此設定[資源路由](https://codeigniter.tw/user_guide/incoming/restful.html#id2)或[表現層路由](https://codeigniter.tw/user_guide/incoming/restful.html#id7) 。

#### websafe

這個選項只有 `type` 採用 `resource` 時才會生效。它會在路由的 `options` 中加入 `websafe => 1` 來使路由能夠被 HTML 表單所使用。

```php
#[RouteRESTful(name: 'api/user', websafe: true)]
```

#### only

你可以使用 `only` 選項控制路由的產生，僅產生你所提到的路由。這個參數僅接受一個由方法名稱組成的陣列。

```php
#[RouteRESTful(name: 'api/user', only: [
    'index', 'show'
])]
```

可接受的方法名稱請參考[使用手冊](https://codeigniter.tw/user_guide/incoming/restful.html#id5)。

#### except

你可以使用 `except` 選項來排除某些路由的產生，這個參數僅接受一個由方法名稱組成的陣列。

```php
#[RouteRESTful(name: 'api/user', except: [
    'new', 'edit'
])]
```

可接受的方法名稱請參考[使用手冊](https://codeigniter.tw/user_guide/incoming/restful.html#id5)。

#### placeholder

若 API 需要資源 ID 時，預設會使用 `(:segment)` 置換符號。但你也可以透過傳遞 `placeholder` 參數來改變它:

```php
#[RouteRESTful(name: 'api/user', placeholder: ':(num)')]
```

#### options

你可以透過傳入 options 陣列來針對 RESTful 路由做特殊的調整，程式庫並不會對你所傳入的 options 做任何的判斷。

特別需要注意的是，若你使用了 `websafe` 、 `only` 、 `except` ，與 `placeholder` 這些參數，那麼程式庫將自動把這些內容與你所傳入的 `options` 陣列組合起來。如果參數與 options 有重複宣告的內容，則以參數為主。

你必須參考 CodeIgniter4 的說明書撰寫正確的 options。通常，這個參數使用起來會像這個樣子。

```php
#[RouteRESTful(name: 'api/user', placeholder: ':(num)', options: [
    'filter' => 'auth'
])]
```

#### ignoreGroup

若你有使用 `RouteGroup` 來統一設定同一控制器類別下的路由，但你希望獨立地設定 `RouteRESTful` ，不沿用 `RouteGroup` 的相關設定。這時你就可以將這個參數設定為 `true` ：

```php
#[RouteRESTful(name: 'api/user', ignoreGroup: true)]
```

### RouteGroup

通常，你會希望某些重複的 `path` 不需要重複設定，比如說 `/api/v1` 。這時你就可以利用 `RouteGroup` 來統一讓類別下所有的路由設定套用統一的 `path` 或是 `options` 。

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

上述設定將等同於：

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
