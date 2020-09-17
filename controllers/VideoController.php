<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;
use yii\filters\VerbFilter;



class VideoController extends Controller
{

    /**
     * Displays video grid.
     *
     * @return string
     */
    public function actionIndex()
    {
        $videos = [];
        for ($i = 0; $i < 24; $i++){
            $videos[] = [
                'id' => $i
            ];
        }
        return $this->render(
            'index',
            [
                'videos' => $videos,
            ]
        );
    }

    /**
     * Displays video page.
     *
     * @return Response|string
     */
    public function actionView()
    {
        return $this->render('view', [
        ]);
    }
}