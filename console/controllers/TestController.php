<?php

declare(strict_types=1);

namespace console\controllers;

use common\models\Apple;
use yii\console\Controller;

/**
 * Class TestController.
 */
class TestController extends Controller
{
    /**
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionRun(): void
    {
        $apple = Apple::createRandom();
        echo "Создано яблоко: цвет = {$apple->color}\n";

        try {
            $apple->eat(10);
        } catch (\Exception $e) {
            echo 'Ожидаемая ошибка: ' . $e->getMessage() . "\n";
        }

        $apple->fallToGround();
        echo "Яблоко упало\n";

        $apple->eat(30);
        echo 'Съедено 30%, осталось: ' . (100 - $apple->eaten_percent) . "%\n";

        $apple->eat(70);
        echo "Яблоко полностью съедено и удалено\n";

        $exists = Apple::findOne($apple->id);
        echo $exists ? "Ошибка: яблоко осталось\n" : "OK: яблоко удалено\n";
    }
}
