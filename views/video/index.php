<?php

use app\models\SearchQuery;
use app\components\widgets\videoGrid\VideoGridWidget;

/* @var $this yii\web\View */
/* @var $searchQuery SearchQuery */

$this->title = 'Video Gallery';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= VideoGridWidget::widget([
    'searchQuery' => $searchQuery
]) ?>
