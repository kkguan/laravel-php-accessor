快速入门
-----------
### 安装
```console
composer require free2one/laravel-php-accessor
```
### 发布配置
```console
php artisan vendor:publish --tag=php-accessor
```
项目`composer.json` 文件中配置以下信息信息
```json
{
  "scripts":{
    "php-accessor": "@php vendor/bin/php-accessor generate"
  }
}
```


### 通过`#[Data]`注解原始类
```php
<?php
namespace App;

use PhpAccessor\Attribute\Data;

#[Data]
class Entity
{
    private int $id;

    private int $sex;
}
```
更多注解的使用说明,详见<a href="https://github.com/kkguan/php-accessor">PHP Accessor</a>.

配置项说明
-----------
### `is_dev_mode`
设置为true时,将在每次请求时重新生成访问器代理类,否则将在第一次请求时生成访问器代理类,后续请求将直接使用代理类.

### `scan_directories`
需要扫描的目录,默认为`app`目录.

### `proxy_root_directory`
访问器代理类存放目录,默认为`.php-accessor`目录.

### `gen_meta`
是否生成访问器元数据,生产环境可设置为`no`.

### `gen_proxy`
是否生成访问器代理类

### `log_level`
日志级别,默认为`debug`.

注意事项
-----------

### 跳转 or 查找不生效
需要确保`APP_ENV`在本地环境的设置为`local`,否则请自行修改配置文件`php-accessor.php`中的`genMeta`判断.

```php
<?php
declare(strict_types=1);

use Psr\Log\LogLevel;

return [
    'is_dev_mode' => false,
    'scan_directories' => [
        'app',
    ],
    'proxy_root_directory' => '.php-accessor',
    'gen_meta' => env('APP_ENV') == 'local' ? 'yes' : 'no',
    'gen_proxy' => 'yes',
    'log_level' => LogLevel::DEBUG,
];
```

## 相关资源

#### <a href="https://github.com/kkguan/php-accessor">PHP Accessor</a>: 生成类访问器（Getter & Setter）
#### <a href="https://github.com/kkguan/hyperf-php-accessor">Hyperf PHP Accessor</a>: Hyperf框架SDK,服务启动时将自动生成访问器代理类,同时对原始类进行替换.
#### <a href="https://github.com/kkguan/php-accessor-idea-plugin">PHP Accessor IDEA Plugin</a>: Phpstorm辅助插件,文件保存时自动生成访问器.支持访问器的跳转,代码提示,查找及类字段重构等.



