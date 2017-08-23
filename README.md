# fastd seeder

### 安装
```
composer require runner/fastd-seeder
```

### 配置服务提供者
*config/app.php*
```php
[
    'services' => [
        ... ...
        \Runner\FastdSeeder\SeederServiceProvider::class,
    ],
]
```

### 执行
```
php bin/console seed:dataset {connection} [{tables}] [--excepts={except_tables}] [--force]
```