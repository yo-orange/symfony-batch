# Symfony Batch Sample Application

## Installation
```bash
$ BRANCH_NAME=task/init
$ php -r "copy('https://github.com/yo-orange/symfony-batch/archive/$BRANCH_NAME.zip', 'a.zip');"; unzip a.zip;
$ cd symfony-batch
$ docker compose up -d
$ docker compose exec batch /bin/bash
$ symfony composer install
$ symfony console doctrine:migrations:migrate
```

## Usage
```bash
$ symfony console list
$ symfony console export:product -vvv
$ symfony console import:product -vvv
$ symfony console HelloWorld -vvv
$ symfony console import:sftp -vvv
```

## Tests
```bash
$ ./bin/phpunit
```
