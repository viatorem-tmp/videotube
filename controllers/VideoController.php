<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Video;
use app\models\SearchQuery;

class VideoController extends Controller
{

    /**
     * Displays video grid.
     *
     * @return string
     */
    public function actionIndex(Request $request)
    {
        $searchQuery = new SearchQuery();
        $searchQuery->buildByHttpRequest($request);

        return $this->render(
            'index',
            [
                'searchQuery' => $searchQuery
            ]
        );
    }

    /**
     * Displays video page.
     *
     * @return Response|string
     */
    public function actionView($slug)
    {
        $video = Video::findOne([
            'slug' => $slug
        ]);
        if (empty($video)){
            throw new NotFoundHttpException();
        }
        return $this->render('view', [
            'video' => $video
        ]);
    }
}
