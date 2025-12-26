<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Яблоки';
?>

<h1>Управление яблоками</h1>

<!-- Генерация яблок -->
<form method="get" action="<?= Url::to(['apple/generate']) ?>" style="margin: 0 0 20px 0;">
    <label>
        Количество:
        <input type="number" name="count" min="1" max="20" value="5" required style="width: 80px;">
    </label>
    <button type="submit" class="btn btn-primary">Сгенерировать яблоки</button>
</form>

<!-- Сообщения об ошибках -->
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger">
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>

<!-- Список яблок -->
<table class="table table-bordered">
    <thead>
    <tr>
        <th>Цвет</th>
        <th>Состояние</th>
        <th>Съедено, %</th>
        <th>Действия</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($apples as $apple): ?>
        <?php
        switch ($apple->getStatus()) {
            case \backend\models\Apple::STATUS_ON_TREE:
                $status = 'Висит на дереве';
                break;
            case \backend\models\Apple::STATUS_ON_GROUND:
                $status = 'Упало';
                break;
            case \backend\models\Apple::STATUS_ROTTEN:
                $status = 'Гнилое';
                break;
            default:
                $status = '—';
        }
        ?>
        <tr>
            <td><?= Html::encode($apple->color) ?></td>
            <td><?= Html::encode($status) ?></td>
            <td><?= number_format($apple->eaten_percent, 2) ?></td>
            <td>
                <?php if ($apple->getStatus() === \backend\models\Apple::STATUS_ON_TREE): ?>
                    <a href="<?= Url::to(['apple/fall', 'id' => $apple->id]) ?>"
                       class="btn btn-warning btn-sm">Уронить</a>
                <?php endif; ?>

                <?php if ($apple->getStatus() === \backend\models\Apple::STATUS_ON_GROUND): ?>
                    <form method="get" action="<?= Url::to(['apple/eat']) ?>"
                          style="display: inline-block; margin-top: 4px;">
                        <input type="hidden" name="id" value="<?= $apple->id ?>">
                        <input type="number" name="percent"
                               min="1" max="<?= 100 - $apple->eaten_percent ?>"
                               value="10" required style="width: 60px; font-size: 0.9em;">
                        <button type="submit" class="btn btn-success btn-sm">Съесть</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>