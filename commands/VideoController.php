<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use yii\console\Exception;
use yii\helpers\Console;
use app\models\VideoViewsCounter;
use app\models\VideoAddedAtCounter;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class VideoController extends Controller
{
    /**
     * @var string the default command action.
     */
    public $defaultAction = 'refresh-counter';

    /**
     * This command echoes what you have entered as the message.
     * @param string $counter the counter to refresh: 'views' or 'added_at'. Leave empty to refresh both.
     * @param bool $concurrently make refresh concurrently.
     * @return int Exit code
     */
    public function actionRefreshCounter($counter = '', $concurrently = true)
    {
        if (!in_array($counter, ['views', 'added_at', ''])){
            throw new Exception(sprintf('unknown counter: %s', $counter));
        }

        if (in_array($counter, ['added_at', ''])){
            $start = microtime(true);
            VideoAddedAtCounter::makeRefreshView($concurrently);
            $duration = microtime(true) - $start;
            Console::output(sprintf("'%s' refreshed in %f ms", VideoAddedAtCounter::tableName(), $duration));
        }
        if (in_array($counter, ['views', ''])){
            $start = microtime(true);
            VideoViewsCounter::makeRefreshView($concurrently);
            $duration = microtime(true) - $start;
            Console::output(sprintf("'%s' refreshed in %f ms", VideoViewsCounter::tableName(), $duration));
        }

        return ExitCode::OK;
    }
}
