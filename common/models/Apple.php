<?php

declare(strict_types=1);

namespace common\models;

use DomainException;
use InvalidArgumentException;
use RuntimeException;
use yii\db\ActiveRecord;

/**
 * Class Apple.
 *
 * @property int|null $id
 * @property string   $color
 * @property int      $created_at
 * @property int|null $fell_at
 * @property float    $eaten_percent
 */
class Apple extends ActiveRecord
{
    public const STATUS_ON_TREE = 'on_tree';

    public const STATUS_ON_GROUND = 'on_ground';

    public const STATUS_ROTTEN = 'rotten';

    public static function tableName(): string
    {
        return '{{%apple}}';
    }

    /**
     * @throws RuntimeException
     */
    public static function createRandom(): self
    {
        $colors = ['red', 'green', 'yellow'];

        $apple = new self();
        $apple->color = $colors[array_rand($colors)];
        $apple->created_at = time() - random_int(0, 86400);

        if (!$apple->save()) {
            throw new RuntimeException('Не удалось создать яблоко');
        }

        return $apple;
    }

    public function rules(): array
    {
        return [
            [['color', 'created_at'], 'required'],
            [['created_at', 'fell_at'], 'integer'],
            [['eaten_percent'], 'number', 'min' => 0, 'max' => 100],
            [['color'], 'string', 'max' => 255],
        ];
    }

    public function isRotten(): bool
    {
        return $this->getStatus() === self::STATUS_ROTTEN;
    }

    public function getStatus(): string
    {
        if ($this->fell_at === null) {
            return self::STATUS_ON_TREE;
        }

        if (time() - $this->fell_at > 5 * 3600) {
            return self::STATUS_ROTTEN;
        }

        return self::STATUS_ON_GROUND;
    }

    /**
     * @throws RuntimeException
     */
    public function fallToGround(): void
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
     * @throws DomainException
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function eat(float $percent): void
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

    public function canEat(): bool
    {
        return $this->getStatus() === self::STATUS_ON_GROUND;
    }
}
