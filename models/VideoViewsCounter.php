<?php

namespace app\models;

use Yii;

/**
 * This is the model class for materialized view "videos_views_counter".
 *
 * @property string|null $mode
 * @property int|null $views
 * @property float|null $real_amount
 * @property int|null $amount
 */
class VideoViewsCounter extends AbstractCounter
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'videos_views_counter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mode'], 'string'],
            [['views', 'amount'], 'default', 'value' => null],
            [['views', 'amount'], 'integer'],
            [['real_amount'], 'number'],
            [['mode', 'views'], 'unique', 'targetAttribute' => ['mode', 'views']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'mode' => 'Mode',
            'views' => 'Views',
            'real_amount' => 'Real Amount',
            'amount' => 'Amount',
        ];
    }
}
