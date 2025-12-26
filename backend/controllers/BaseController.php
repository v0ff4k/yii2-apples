<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\auth\HttpBasicAuth;
use common\components\HttpBasicUser;

class BaseController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'basicAuth' => [
                'class' => HttpBasicAuth::class,
                'auth' => function ($username, $password) {
                    // TODO MOVE TO .ENV or safer
                    if ($username === 'admin' && $password === 'admin123') {
                        return new HttpBasicUser();
                    }
                    return null;
                },
            ],
        ];
    }
}