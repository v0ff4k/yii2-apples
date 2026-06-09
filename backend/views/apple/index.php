<?php

declare(strict_types=1);

use common\models\Apple;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Яблоки';
?>

<h1>Управление яблоками</h1>

<!-- Flash messages -->
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success"><?= Yii::$app->session->getFlash('success') ?></div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger"><?= Yii::$app->session->getFlash('error') ?></div>
<?php endif; ?>

<!-- Генерация яблок -->
<form method="get" action="<?= Url::to(['apple/generate']) ?>" style="margin: 0 0 20px 0;">
    <label>
        Количество:
        <input type="number" name="count" min="1" max="20" value="5" required style="width: 80px;">
    </label>
    <button type="submit" class="btn btn-primary">Сгенерировать яблоки</button>
</form>

<!-- Список яблок -->
<table class="table table-bordered" id="apples-table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Цвет</th>
        <th>Состояние</th>
        <th>Съедено, %</th>
        <th>Таймер</th>
        <th>Действия</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($apples as $apple): ?>
        <?php
        $status = match ($apple->getStatus()) {
            Apple::STATUS_ON_TREE => 'Висит на дереве',
            Apple::STATUS_ON_GROUND => 'Упало',
            Apple::STATUS_ROTTEN => 'Гнилое',
            default => '—',
        };
        
        $statusClass = match ($apple->getStatus()) {
            Apple::STATUS_ON_TREE => 'status-tree',
            Apple::STATUS_ON_GROUND => 'status-ground',
            Apple::STATUS_ROTTEN => 'status-rotten',
            default => '',
        };
        
        $remainingPercent = 100 - $apple->eaten_percent;
        $timerData = $apple->fell_at ? 'data-fell-at="' . $apple->fell_at . '"' : '';
        ?>
        <tr class="apple-row <?= $statusClass ?>" data-id="<?= $apple->id ?>">
            <td><?= $apple->id ?></td>
            <td>
                <span class="apple-color apple-color-<?= Html::encode($apple->color) ?>"></span>
                <?= Html::encode($apple->color) ?>
            </td>
            <td class="status-cell"><?= Html::encode($status) ?></td>
            <td class="eaten-cell"><?= number_format($apple->eaten_percent, 2) ?></td>
            <td class="timer-cell" <?= $timerData ?>>
                <?php if ($apple->getStatus() === Apple::STATUS_ON_GROUND): ?>
                    <span class="ripening-timer"></span>
                <?php elseif ($apple->getStatus() === Apple::STATUS_ROTTEN): ?>
                    <span class="text-muted">—</span>
                <?php else: ?>
                    <span class="text-muted">—</span>
                <?php endif; ?>
            </td>
            <td class="actions-cell">
                <?php if ($apple->getStatus() === Apple::STATUS_ON_TREE): ?>
                    <a href="<?= Url::to(['apple/fall', 'id' => $apple->id]) ?>"
                       class="btn btn-warning btn-sm fall-btn">Уронить</a>
                <?php endif; ?>

                <?php if ($apple->getStatus() === Apple::STATUS_ON_GROUND): ?>
                    <form method="post" action="<?= Url::to(['apple/eat']) ?>"
                          class="eat-form" style="display: inline-block; margin-top: 4px;">
                        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
                        <input type="hidden" name="id" value="<?= $apple->id ?>">
                        <input type="number" name="percent"
                               min="1" max="<?= $remainingPercent ?>"
                               value="10" required
                               class="percent-input"
                               data-max="<?= $remainingPercent ?>"
                               style="width: 60px; font-size: 0.9em;">
                        <button type="submit" class="btn btn-success btn-sm eat-btn">Съесть</button>
                    </form>
                <?php endif; ?>

                <?php if ($apple->getStatus() !== Apple::STATUS_ROTTEN && $apple->eaten_percent < 100): ?>
                    <a href="<?= Url::to(['apple/delete', 'id' => $apple->id]) ?>"
                       class="btn btn-danger btn-sm"
                       data-confirm="Удалить это яблоко?">Удалить</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<style>
.apple-color {
    display: inline-block;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    margin-right: 5px;
    vertical-align: middle;
    border: 1px solid #ccc;
}
.apple-color-red { background-color: #e74c3c; }
.apple-color-green { background-color: #27ae60; }
.apple-color-yellow { background-color: #f1c40f; }

.apple-row {
    transition: all 0.3s ease;
}

/* Fall animation */
.apple-row.falling {
    animation: fallDown 0.5s ease-in forwards;
}

@keyframes fallDown {
    0% { transform: translateY(-20px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
}

/* Eat animation */
.apple-row.eating .eaten-cell {
    animation: eatPulse 0.3s ease;
}

@keyframes eatPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); background-color: #d4edda; }
}

/* Status colors */
.status-tree { color: #27ae60; font-weight: bold; }
.status-ground { color: #e67e22; }
.status-rotten { color: #95a5a6; font-style: italic; }

/* Timer styles */
.ripening-timer {
    font-family: monospace;
    font-size: 0.9em;
    color: #e67e22;
}
.ripening-timer.warning {
    color: #e74c3c;
    font-weight: bold;
}
</style>

<?php
$script = <<<JS
// Ripe timer - countdown to rot (5 hours after fall)
function updateTimers() {
    const now = Math.floor(Date.now() / 1000);
    const ROT_TIME = 5 * 3600; // 5 hours

    document.querySelectorAll('.timer-cell').forEach(cell => {
        const fellAt = cell.dataset.fellAt;
        if (!fellAt) return;

        const remaining = ROT_TIME - (now - fellAt);
        const timerSpan = cell.querySelector('.ripening-timer');
        if (!timerSpan) return;

        if (remaining <= 0) {
            timerSpan.textContent = 'Сгнило!';
            timerSpan.classList.add('warning');
            // Reload page to update status
            setTimeout(() => location.reload(), 2000);
            return;
        }

        const hours = Math.floor(remaining / 3600);
        const minutes = Math.floor((remaining % 3600) / 60);
        const seconds = remaining % 60;

        timerSpan.textContent = \`\${hours}:\${String(minutes).padStart(2, '0')}:\${String(seconds).padStart(2, '0')}\`;

        if (remaining < 600) { // Less than 10 minutes
            timerSpan.classList.add('warning');
        }
    });
}

// Fall animation
document.querySelectorAll('.fall-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        const row = this.closest('.apple-row');
        row.classList.add('falling');
    });
});

// Eat form validation
document.querySelectorAll('.eat-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const input = this.querySelector('.percent-input');
        const max = parseFloat(input.dataset.max);
        const value = parseFloat(input.value);

        if (value < 1 || value > max) {
            e.preventDefault();
            alert('Введите значение от 1 до ' + max);
            return false;
        }

        // Add eating animation
        const row = this.closest('.apple-row');
        row.classList.add('eating');
    });
});

// Initial timer update and set interval
updateTimers();
setInterval(updateTimers, 1000);
JS;
$this->registerJs($script);
?>