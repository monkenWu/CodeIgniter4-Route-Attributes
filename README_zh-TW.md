# CodeIgniter4-Route-Attribute

你可以使用這個程式庫讓 CodeIgniter4 能夠使用註解來定義控制器的路由設定。

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

這個程式庫會聰明地替你自動綁定控制器與路由的關係，這代表你可以透過 `/attr/route` 路徑存取 `Ci4Controller` 的 `hello` 方法。

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

簡而言之，這個程式庫是 CodeIgniter4 Router 在 PHP8 Attributes 特性下的一種呈現方式。它藉由自動掃描控制器中的註解，自動將路由與方法進行綁定，使你能夠直觀的撰寫與維護你的控制器。

### Route

### RouteRESTful

### RouteGroup