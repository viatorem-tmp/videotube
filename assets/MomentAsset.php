<?php

namespace app\assets;

use yii\web\AssetBundle;

class MomentAsset extends AssetBundle
{
    public $js = [
        'moment.min.js'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . "/../components/moment/min";
        parent::init();
    }
}
