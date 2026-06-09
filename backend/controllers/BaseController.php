<?php

declare(strict_types=1);

namespace backend\controllers;

use common\components\HttpBasicUser;
use yii\filters\auth\HttpBasicAuth;
use yii\web\Application;
use yii\web\Controller;

class BaseController extends Controller
{
    public function behaviors(): array
    {
        return [
            'basicAuth' => [
                'class' => HttpBasicAuth::class,
                'auth' => function (string $username, string $password): ?HttpBasicUser {
                    if ($username === 'admin' && $password === 'admin123') {
                        return new HttpBasicUser();
                    }

                    return null;
                },
            ],
        ];
    }

    /**
     * Returns the typed web application instance.
     * Replaces Yii::$app which PHPStan sees as console|web|null union.
     */
    protected function app(): Application
    {
        /** @var Application $app */
        $app = \Yii::$app;

        return $app;
    }
}
