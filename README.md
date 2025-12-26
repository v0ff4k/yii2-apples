### yii2(yiisoft/yii2-app-advanced) + apples add-on

#### Installation guide
1. set sub element `user` and `db` of components in  `backend/config/main-local.php`
as in common/config/main-local.php
2. Create DATABASE in your MySQL (PRIVILEGES is optionsl)
```mysql
CREATE DATABASE yii2_apples;
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

DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```
