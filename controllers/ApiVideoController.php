<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Request;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\Video;
use app\models\SearchQuery;

class ApiVideoController extends Controller
{
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        Yii::$app->response->format = 'json';
    }

    /**
     * @param Request $request
     * @return Response
     * @throws BadRequestHttpException
     */
    public function actionIndex(Request $request)
    {
        $searchQuery = new SearchQuery();
        if (!$searchQuery->buildByApiRequest($request)){
            $message = implode("; ", $searchQuery->getErrors());
            throw new BadRequestHttpException($message);
        }
        $videos = Video::findBySearchQuery($searchQuery)->all();
        return $this->asJson($videos);
    }
}
