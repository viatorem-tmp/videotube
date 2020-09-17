<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\bootstrap\Button;

/**
 * This is the model class for materialized view "videos_added_at_counter".
 *
 * @property string|null $mode
 * @property string|null $added_at
 * @property float|null $real_amount
 * @property int|null $amount
 */
class VideoAddedAtCounter extends AbstractCounter
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'videos_added_at_counter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mode'], 'string'],
            [['added_at'], 'safe'],
            [['real_amount'], 'number'],
            [['amount'], 'default', 'value' => null],
            [['amount'], 'integer'],
            [['mode', 'added_at'], 'unique', 'targetAttribute' => ['mode', 'added_at']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mode' => 'Mode',
            'added_at' => 'Added At',
            'real_amount' => 'Real Amount',
            'amount' => 'Amount',
        ];
    }
}
