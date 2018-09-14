<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */
namespace webkadabra\podcaster\controllers;
use webkadabra\podcaster\models\PodcastStatListen;
use yii;

class MaintenanceController extends \webkadabra\podcaster\components\Controller
{
    public function actionIndex()
    {
        if (Yii::$app->request->isPost) {
            $removed = PodcastStatListen::deleteAll(['ip_address' => Yii::$app->request->userIP]);
            Yii::$app->session->addFlash('success', 'Removed '.$removed.' records');
            return $this->redirect(['stats/index']);
        }
        $count = PodcastStatListen::find()->where(['ip_address' => Yii::$app->request->userIP])->count();
        return $this->render('index', [
            'count' => $count
        ]);
    }
}
