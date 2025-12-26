<?php

namespace common\models;

use DomainException;
use InvalidArgumentException;
use RuntimeException;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class Apple
 * @package common\models
 */
class Apple extends ActiveRecord
{
    const STATUS_ON_TREE = 'on_tree';
    const STATUS_ON_GROUND = 'on_ground';
    const STATUS_ROTTEN = 'rotten';

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%apple}}';
    }

    /**
     * @return \common\models\Apple
     * @throws \yii\db\Exception
     */
    public static function createRandom()
    {
        $colors = ['red', 'green', 'yellow'];

        $apple = new self();
        $apple->color = $colors[array_rand($colors)];
        // случайная дата за последние 24 часа
        $apple->created_at = time() - mt_rand(0, 86400);

        if (!$apple->save()) {
            throw new RuntimeException('Не удалось создать яблоко');
        }

        return $apple;
    }

    /**
     * @return array|array[]
     */
    public function rules()
    {
        return [
            [['color', 'created_at'], 'required'],
            [['created_at', 'fell_at'], 'integer'],
            [['eaten_percent'], 'number', 'min' => 0, 'max' => 100],
            [['color'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return bool
     */
    public function isRotten()
    {
        return $this->getStatus() === self::STATUS_ROTTEN;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        if ($this->fell_at === null) {
            return self::STATUS_ON_TREE;
        }

        // Проверка: прошло ли более 5 часов с момента падения
        if (time() - $this->fell_at > 5 * 3600) {
            return self::STATUS_ROTTEN;
        }

        return self::STATUS_ON_GROUND;
    }

    /**
     * @throws \yii\db\Exception
     */
    public function fallToGround()
    {
        if ($this->getStatus() !== self::STATUS_ON_TREE) {
            throw new DomainException('Яблоко уже не на дереве');
        }
        $this->fell_at = time();
        if (!$this->save()) {
            throw new RuntimeException('Не удалось сохранить яблоко');
        }
    }

    /**
     * @param $percent
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function eat($percent)
    {
        if (!$this->canEat()) {
            if ($this->getStatus() === self::STATUS_ON_TREE) {
                throw new DomainException('Съесть нельзя, яблоко на дереве');
            }
            throw new DomainException('Яблоко испорчено, есть нельзя');
        }

        if ($percent <= 0) {
            throw new InvalidArgumentException('Процент должен быть больше 0');
        }

        if ($this->eaten_percent + $percent > 100) {
            throw new InvalidArgumentException('Нельзя съесть больше оставшегося');
        }

        $this->eaten_percent += $percent;
        if (!$this->save()) {
            throw new RuntimeException('Не удалось сохранить яблоко');
        }

        if ($this->eaten_percent >= 100) {
            $this->delete();
        }
    }

    /**
     * @return bool
     */
    public function canEat()
    {
        return $this->getStatus() === self::STATUS_ON_GROUND;
    }
}