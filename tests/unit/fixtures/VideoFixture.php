<?php

namespace tests\unit\fixtures;

use Yii;
use yii\test\ActiveFixture;
use yii\helpers\Console;
use yii\console\Exception;
use app\models\VideoViewsCounter;
use app\models\VideoAddedAtCounter;


class VideoFixture extends ActiveFixture{
    public $modelClass = 'app\models\Video';

    protected function getData()
    {
        $number = 10000;
        Console::output("\nThe video fixtures will be generated with randomized strings.");
        $input = Console::input( sprintf("How many rows to add [%d]:", $number));

        Console::output("\nAfter adding videos, the video counter should be refreshed.");
        Console::output("Normally it should be done by cron with command:");
        Console::output(Console::renderColoredString("\t%g yii video/refresh-counter %n"));
        $refresh = Console::confirm("\nRefresh the counter after the generation of fixtures?", true);

        if ($input !== ''){
            if (!ctype_digit($input)){
                throw new Exception(sprintf("Wrong input: '%s'. Expected integer", $input));
            }
            $number = (int) $input;
        }
        $rows = [];

        $begin = 946684800;

        $current = $begin;
        for ($i = 0; $i < $number ; $i++){
            $slug = bin2hex(random_bytes(8));

            $rows[] = [
                'title' => ucfirst($slug),
                'slug' => $slug,
                'views' => round((sin(rand(0, 1000)) + 1) * (rand(0, $number * 5))) + rand(1, 100),
                'duration' => rand(0, 7200),
                'added_at' => date(DATE_RFC3339, $current)
            ];
            $current += rand(0, 1000);
        }

        if ($refresh) {
            Yii::$app->on(\yii\base\Application::EVENT_AFTER_REQUEST, function ($event) {
                Console::output("refreshing....");
                $start = microtime(true);
                VideoAddedAtCounter::makeRefreshView(false);
                $duration = microtime(true) - $start;
                Console::output(sprintf("'%s' refreshed in %f ms", VideoAddedAtCounter::tableName(), $duration));
                $start = microtime(true);
                VideoViewsCounter::makeRefreshView(false);
                $duration = microtime(true) - $start;
                Console::output(sprintf("'%s' refreshed in %f ms", VideoViewsCounter::tableName(), $duration));
            });
        }

        return $rows;
    }
}