### yii2(yiisoft/yii2-app-advanced) + apples add-on

#### Installation guide
1. set sub element `user` and `db` of components in  `backend/config/main-local.php`
as in common/config/main-local.php
2. Create DATABASE in your MySQL (PRIVILEGES is optionsl)
```mysql
CREATE DATABASE yii2_apples CHARACTER SET utf8 COLLATE utf8mb4_general_ci;;
CREATE USER 'yiiuser'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON yii2_apples.* TO 'yiiuser'@'localhost';
FLUSH PRIVILEGES;
```
3. Check  via cli : `./yii help`
4. Run migration: `./yii migrate`
5. Run Apple test:  `./yii test/run`
6. Run (if your project located in /var/www/html/yii2-apple): `cd /var/www/html/yii2-apple/backend/web` 
`php8.1 -S 127.0.0.1:8080`
open in browser `http://127.0.0.1:8080/apple/index` enjoy ;)
7. Auth  as `admin` with `admin123`
8. Frontend:  jQuery JavaScript Library v3.7.1 + Bootstrap v5.3.8 

#### Tests
1. Create **test** table: `CREATE DATABASE yii2_apples_test CHARACTER SET utf8 COLLATE utf8mb4_general_ci;`
2. Set DB(dsn) config for Database `yii2_apples_test` in `common/config/test-local.php`
3. Unit test: `vendor/bin/codecept run unit common/tests/unit/models/AppleTest.php`(because common has "correct" unit test)
4. Testing : ` vendor/bin/codecept run unit common/tests/unit/models/AppleTest.php  -c common` "config" as common/codeconception.yml

#### TODO
1.    Ripe timer (JavaScript or on page refresh), Fall/Eaten animation (CSS)
2.    Work with Exceptions (in controller — try/catch, show Yii::$app->session->setFlash('error', $e->getMessage()))
3.    Form validation: % from 1 to (100 - eaten_percent)
4.    API for SPA
5.    Logging (who, when what did)

---
<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Advanced Project Template</h1>
    <br>
</p>

Yii 2 Advanced Project Template is a skeleton [Yii 2](https://www.yiiframework.com/) application best for
developing complex Web applications with multiple tiers.

The template includes three tiers: front end, back end, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![build](https://github.com/yiisoft/yii2-app-advanced/workflows/build/badge.svg)](https://github.com/yiisoft/yii2-app-advanced/actions?query=workflow%3Abuild)

DIRECTORY STRUCTURE(contains)
-------------------

```
common
    config/              shared configurations
    mail/                view files for e-mails
    models/              model classes used in both backend and frontend
    tests/               tests for common classes    
console
    config/              console configurations
    controllers/         console controllers (commands)
    migrations/          database migrations
    models/              console-specific model classes
    runtime/             files generated during runtime
backend
    assets/              application assets such as JavaScript and CSS
    config/              backend configurations
    controllers/         Web controller classes
    models/              backend-specific model classes
    runtime/             files generated during runtime
    tests/               tests for backend application    
    views/               view files for the Web application
    web/                 the entry script and Web resources
frontend
    assets/              application assets such as JavaScript and CSS
    config/              frontend configurations
    controllers/         Web controller classes
    models/              frontend-specific model classes
    runtime/             files generated during runtime
    tests/               tests for frontend application
    views/               view files for the Web application
    web/                 the entry script and Web resources
    widgets/             frontend widgets
vendor/                  dependent 3rd-party packages
environments/            environment-based overrides
```
