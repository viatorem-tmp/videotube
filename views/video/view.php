<?php

use yii\helpers\Url;
use app\models\Video;

/* @var $this yii\web\View */
/* @var $video Video */


$this->title = $video->title;
$this->params['breadcrumbs'][] = ['label' => 'Video Gallery', 'url' => Url::to(['video/index'])];
$this->params['breadcrumbs'][] = $video->title;
?>
<div class="video-view container">
    <div class="row">
        <div class="col-sm-6">
            <span class="text-muted">
                Added:
            </span>
            <span class="text-primary">
                <?= date('M d \'y', strtotime($video->added_at));?>
            </span>
        </div>
        <div class="col-sm-6">
            <span class="text-muted pull-right">
                <span class="glyphicon glyphicon glyphicon-eye-open"></span> <?=$video->views;?>
            </span>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <img src="/images/video.svg" />
        </div>
    </div>
</div>


