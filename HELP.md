# create projects
- https://symfony.com/doc/current/best_practices.html
```
symfony new symfony-batch
```

# check
- https://github.com/webmozarts/console-parallelization

# vscode plugin
```
code --install-extension bmewburn.vscode-intelephense-client
code --install-extension neilbrayfield.php-docblocker
code --install-extension formulahendry.vscode-mysql
code --install-extension damianbal.vs-phpclassgen

code --install-extension grapecity.gc-excelviewer
code --install-extension redhat.vscode-yaml
code --install-extension ms-vscode-remote.remote-containers

```

# composer require

## link
- [symfony](https://symfony.com/doc/current/the-fast-track/ja/index.html)
- [doctrine](https://symfony.com/doc/current/doctrine.html)
- [logging](https://symfony.com/doc/current/logging.html)
  - [monolog](https://symfony.com/doc/current/reference/configuration/monolog.html)
- [phpunit](https://symfony.com/doc/current/components/phpunit_bridge.html)
- [string](https://symfony.com/doc/current/components/string.html)
- [SymfonyMakerBundle](https://symfony.com/doc/current/bundles/SymfonyMakerBundle/index.html)
- [gd](https://qiita.com/Soh1121/items/17dcdc815a1d22f31788)

## command
```
composer require symfony/orm-pack
composer require --dev symfony/maker-bundle
composer require symfony/monolog-bundle
composer require symfony/string

composer require phpoffice/phpspreadsheet

composer require --dev symfony/phpunit-bridge
php bin/phpunit
```

vscode modify settings.json
```
    "intelephense.environment.includePaths": [
        "bin/.phpunit/phpunit"
    ]
```

# create Command
```
php bin/console list make
php bin/console make:command
> HelloWorld
php bin/console HelloWorld -vvv
```

# create subscriber
- https://symfony.com/doc/current/event_dispatcher.html#creating-an-event-subscriber
```
php bin/console make:subscriber
> LoggingSubscriber
> console.command
```

# setting monolog formatter
- https://symfony.com/doc/5.0/logging.html
- https://symfony.com/doc/5.0/logging/formatter.html
- https://symfony.com/doc/2.0/cookbook/logging/monolog.html

services.yaml
```
    monolog.formatter.default:
        class: Monolog\Formatter\LineFormatter
        arguments:
            - "[%%datetime%%][%%level_name%%][%%channel%%] %%message%%\n"
```
monolog.yaml
```
    formatter: monolog.formatter.default
```

# create database
```
php bin/console make:docker:database
docker-compose.exe up -d
# docker-compose.exe up -d
```

# make:migration
```
    Symfony\Bundle\MakerBundle\MakerBundle::class => ['dev' => true],
↓
    Symfony\Bundle\MakerBundle\MakerBundle::class => ['dev' => true, 'local' => true],
```

# orm
- https://www.doctrine-project.org/projects/doctrine-orm/en/2.8/reference/basic-mapping.html#basic-mapping
- https://www.doctrine-project.org/projects/doctrine-orm/en/2.8/cookbook/custom-mapping-types.html

# doctrine migrations
- https://qiita.com/tomcky/items/e30a08861fd2e7530a0d
```
php bin/console list doctrine
php bin/console doctrine:migrations:generate
php bin/console doctrine:migrations:migrate
php bin/console doctrine:migrations:execute --up   'DoctrineMigrations\Version20210511135414'
php bin/console doctrine:migrations:execute --down 'DoctrineMigrations\Version20210511135414'
php bin/console doctrine:migrations:execute --up   'DoctrineMigrations\Version20210515103420'
php bin/console doctrine:migrations:execute --down 'DoctrineMigrations\Version20210515103420'
php bin/console doctrine:migrations:version 20210511135414 --delete -e local
php bin/console doctrine:migrations:status -e local
```

# docker dev
```
docker-compose exec batch /bin/bash
php ./bin/console HelloWorld -e dev -vvv
```

# phpunit
```
php bin/console make:unit-test
> Command\HelloWorldCommand
```

monolog
```
monolog:
    handlers:
        main:
            type:   test
            level:  debug
```

# entity
```
php bin/console make:entity
```

# spread sheet
- https://github.com/PHPOffice/PhpSpreadsheet/blob/master/samples/Reader/01_Simple_file_reader_using_IOFactory.php


# sftp
- https://pecl.php.net/package/ssh2/1.2/windows
- https://github.com/bmewburn/vscode-intelephense/issues/1405


# two connection
- https://symfony.com/doc/current/reference/configuration/doctrine.html
