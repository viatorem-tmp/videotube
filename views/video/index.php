<?php

/* @var $this yii\web\View */
/* @var $videos app\models\Video[] */



use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = 'Video Gallery';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-index">'
    <div class="row">
        <?php if(!empty($videos)): ?>
            <div class="item-container">
                <?php foreach ($videos as $video): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                        <div style="padding: 5px">
                            <img src="/images/video.svg" />
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
        <?php else:; ?>
            <p>
                No videos yet...
            </p>
        <?php endif; ?>

    </div>
</div>

