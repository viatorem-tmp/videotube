<?php

namespace app\components\widgets\videoGrid;

use app\models\SearchQuery;
use yii\base\Widget;
use app\models\Video;

class VideoGridWidget extends Widget
{
    /**
     * @var SearchQuery
     */
    public $searchQuery;

    public function init()
    {
        parent::init();
        if ($this->searchQuery === null) {
            $this->searchQuery = new SearchQuery();
        }
    }


    public function run()
    {
        $videoCount = Video::count();
        $pageSize = $this->searchQuery->getPageSize();
        $pageCount = ceil($videoCount / $pageSize);
        $pageNumber = $this->searchQuery->getPageNumber();
        $mode = $this->searchQuery->getOrderColumn();
        $direction = $this->searchQuery->getIsAsc() ? 'asc' : 'desc';

        VideoGridWidgetAsset::register($this->getView());
        return $this->render('video-grid', [
            'pageCount' => $pageCount,
            'page' => $pageNumber,
            'mode' => $mode,
            'direction' => $direction,
            'pageSize' => $pageSize
        ]);
    }
}