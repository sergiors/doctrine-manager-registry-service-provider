Doctrine Manager Service Provider
---------------------------------

Install
-------
```bash
composer require sergiors/doctrine-manager-registry-service-provider "dev-master"
```

```php
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Sergiors\Silex\Provider\DoctrineCacheServiceProvider;
use Sergiors\Silex\Provider\DoctrineOrmServiceProvider;
use Sergiors\Silex\Provider\DoctrineManagerRegistryServiceProvider;

$app->register(new ValidatorServiceProvider());
$app->register(new DoctrineServiceProvider());
$app->register(new DoctrineCacheServiceProvider());
$app->register(new DoctrineOrmServiceProvider());
$app->register(new DoctrineManagerRegistryServiceProvider());
```

License
-------
MIT
