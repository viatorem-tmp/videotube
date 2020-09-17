<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\bootstrap\Button;

/**
 * This is abstract class for counter-like materialized views.
 *
 * @property string|null $mode
 * @property float|null $real_amount
 * @property int|null $amount
 */
abstract class AbstractCounter extends \yii\db\ActiveRecord
{
    const MODE_ASC = 'asc';
    const MODE_DESC = 'desc';

    /**
     * @param string $mode
     * @param int $pos
     * @return array|self|null
     */
    public static function findByModeAndPosition(string $mode, int $pos)
    {
        return static::find()
            ->where([
                'mode' => $mode,
            ])->andWhere([
                '>=', 'amount', $pos,
            ])->orderBy([
                'amount' => SORT_ASC
            ])->one();
    }

    public static function total(): ?int
    {
        $row = static::find()
            ->where([
                'mode' => self::MODE_DESC,
            ])->orderBy([
                'amount' => SORT_DESC
            ])->one();
        if (empty($row)){
            return null;
        }
        return (int) $row->real_amount;
    }

    public static function makeRefreshView($concurrently = true): bool
    {
        $connection = Yii::$app->getDb();
        $mode = $concurrently ? "concurrently" : "";
        $sql = sprintf("refresh materialized view %s {{%s}}", $mode, static::tableName());
        $connection->createCommand($sql)->execute();
        return true;
    }
}
