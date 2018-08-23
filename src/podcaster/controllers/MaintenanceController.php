<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 *
 * THIS IS NOT A FREE SOFTWARE. PLEASE, VISIT <https://webkadabra.github.io/yii2-podcast-engine/> FOR DETAILS
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
