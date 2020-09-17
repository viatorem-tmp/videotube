<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ExpressionInterface;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "videos".
 *
 * @property int $id Id
 * @property string $slug Slug
 * @property string $title Title
 * @property string $thumbnail Thumbnail
 * @property int $duration Duration
 * @property int $views Views
 * @property \DateTime $added_at AddedAt
 */
class Video extends ActiveRecord
{
    const ORDER_COLUMN_VIEWS = 'views';
    const ORDER_COLUMN_ADDED_AT = 'added_at';

//    private $added_at;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'videos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'title', 'duration'], 'required'],
            [['slug', 'title', 'thumbnail'], 'string'],
            [['duration', 'views'], 'default', 'value' => 0],
            [['thumbnail'], 'default', 'value' => null],
            [['duration', 'views'], 'integer'],
            [['added_at'], 'safe'],
            ['added_at', 'date', 'format' => 'php:Y-m-d']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'slug' => 'Slug',
            'title' => 'Title',
            'thumbnail' => 'Thumbnail',
            'duration' => 'Duration',
            'views' => 'Views',
            'added_at' => 'AddedAt',
        ];
    }

    public static function count(): int
    {
        $total = VideoAddedAtCounter::total();
        if ($total !== null){
            return $total;
        }
        $total = VideoViewsCounter::total();
        if ($total !== null){
            return $total;
        }
        return (int) static::find()->count();
    }

    public static function findBySearchQuery(VideoSearchQueryInterface $searchQuery, $condition = [])
    {
        $orderColumn = $searchQuery->getOrderColumn();
        $isAsc = $searchQuery->getIsAsc();
        $pageNumber = $searchQuery->getPageNumber();
        $offsetId = $searchQuery->getOffsetId();
        $pageSize = $searchQuery->getPageSize();

        $query = static::find();

        $orderDirection = SORT_DESC;
        $sign = '<';
        if ($isAsc){
            $orderDirection = SORT_ASC;
            $sign = '>';
        }

        $offsetCondition = [];
        $offset = 0;

        if ($offsetId !== 0) {
            $orderQuery = static::find()
                ->select($orderColumn)
                ->orderBy([$orderColumn => $orderDirection])
                ->where(['id' => $offsetId])
                ->andWhere($condition)
                ->limit(1);

            $offsetQuerySql = $orderQuery->createCommand()->getRawSql();
            $offsetCondition = sprintf("[[%s]] %s (%s)", $orderColumn, $sign, $offsetQuerySql);

            $offsetCondition = [
                'and',
                $offsetCondition,
                ['!=', 'id', $offsetId]
            ];
        }elseif ($pageNumber !== 0) {
            $counterPage = Yii::$app->params['counterPage'];
            $pos = ($pageNumber + 1) * $pageSize;

            switch ($orderColumn){
                case self::ORDER_COLUMN_VIEWS:
                    $counterMode = $isAsc ? VideoViewsCounter::MODE_ASC : VideoViewsCounter::MODE_DESC;
                    $counter = VideoViewsCounter::findByModeAndPosition($counterMode, $pos);

                    if (empty($counter)){
                        //fallback: make long query
                        $offset = $pageNumber * $pageSize;
                        break;
                    }
                    $offset = ceil(($pos - ($counter->amount - $counterPage)) / $pageSize);
                    $offsetCondition = [
                        $sign, $orderColumn, $counter->views
                    ];
                    break;
                case self::ORDER_COLUMN_ADDED_AT:
                    $counterMode = $isAsc ? VideoAddedAtCounter::MODE_ASC : VideoAddedAtCounter::MODE_DESC;
                    $counter = VideoAddedAtCounter::findByModeAndPosition($counterMode, $pos);

                    if (empty($counter)){
                        //fallback: make long query
                        $offset = $pageNumber * $pageSize;
                        break;
                    }
                    $offset = ceil(($pos - ($counter->amount - $counterPage)) / $pageSize);
                    $offsetCondition = [
                        $sign, $orderColumn, $counter->added_at
                    ];
                    break;
            }
        }

        if (!empty($offsetCondition)){
            $query->where($offsetCondition);
        }

        $query
            ->andWhere($condition)
            ->orderBy([
                $orderColumn => $orderDirection,
                'id' => SORT_DESC
            ])
            ->limit($pageSize)
            ->offset($offset);

//        $sql = $query->createCommand()->getRawSql();
//        print_r($sql);
//        die();

        return $query;
    }
}
