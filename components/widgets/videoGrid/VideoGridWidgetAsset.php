<?php

namespace app\components\widgets\videoGrid;

use yii\web\AssetBundle;

class VideoGridWidgetAsset extends AssetBundle
{
//    public $publishOptions = [
//        'forceCopy' => true
//    ];

    public $js = [
        'js/videogrid.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'app\assets\HandlebarsAsset',
        'app\assets\MomentAsset'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . "/assets";
        parent::init();
    }
}