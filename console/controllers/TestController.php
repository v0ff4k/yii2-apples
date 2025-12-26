<?php

namespace console\controllers;

use yii\console\Controller;
use backend\models\Apple;

/**
 * Class TestController
 * @package console\controllers
 */
class TestController extends Controller
{
    /**
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionRun()
    {
        // Создаём яблоко
        $apple = Apple::createRandom();
        echo "Создано яблоко: цвет = {$apple->color}\n";

        // Пытаемся съесть — должно быть исключение
        try {
            $apple->eat(10);
        } catch (\Exception $e) {
            echo "Ожидаемая ошибка: ".$e->getMessage()."\n";
        }

        // Уронить
        $apple->fallToGround();
        echo "Яблоко упало\n";

        // Съесть часть
        $apple->eat(30);
        echo "Съедено 30%, осталось: ".(100 - $apple->eaten_percent)."%\n";

        // Съесть остальное
        $apple->eat(70);
        echo "Яблоко полностью съедено и удалено\n";

        // Проверим, что его нет в БД
        $exists = Apple::findOne($apple->id);
        echo $exists ? "Ошибка: яблоко осталось" : "OK: яблоко удалено\n";
    }
}