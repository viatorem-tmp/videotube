<?php

namespace app\assets;

use yii\web\AssetBundle;

class HandlebarsAsset extends AssetBundle
{
    public $js = [
        'handlebars.js'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . "/../components/handlebars";
        parent::init();
    }
}
