<?php

/* @var $this yii\web\View */

use yii\bootstrap\Html;
use yii\helpers\Url;

$this->title = 'Videotube';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>
        <p class="lead">You have successfully established the site.</p>
        <p>
            <a class="btn btn-lg btn-success" href="<?= Url::to(['video/index']) ?>">Go to <strong>VIDEO</strong>s</a>
        </p>
    </div>
</div>
