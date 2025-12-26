<?php
namespace common\models;

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
}