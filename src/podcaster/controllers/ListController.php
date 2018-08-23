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

use webkadabra\podcaster\models\Podcast;
use webkadabra\podcaster\models\PodcastEpisode;
use webkadabra\podcaster\models\PodcastStatListen;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class ListController  extends Controller
{
    /**
     * List podcasts
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Podcast::find()->where([
                'web_list_yn' => 1,
                'published_yn' => 1,
            ])
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * List podcasts
     */
    public function actionEpisodes($id)
    {
        $query = PodcastEpisode::find()->availableForPublic();
        if (is_numeric($id)) {
            $podcastRecord = Podcast::find()->where(['id' => $id])->one();
            if (!$podcastRecord) {
                throw new NotFoundHttpException();
            }
            $query->andWhere(['podcast_id' => $id]);
        } else {
            $podcastRecord = Podcast::find()->where(['alias' => $id])->one();
            if (!$podcastRecord) {
                throw new NotFoundHttpException();
            }
            $query->andWhere(['podcast_id' => $podcastRecord->id]);
        }
        if (!$podcastRecord->published_yn) {
            throw new NotFoundHttpException();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        return $this->render('episodes', [
            'dataProvider' => $dataProvider,
            'podcast' => $podcastRecord,
        ]);
    }

    /**
     * List podcasts
     */
    public function actionEpisode($id)
    {
        $episode = PodcastEpisode::findOne($id);
        if (!$episode) {
            throw new NotFoundHttpException();
        }
        if ($episode->status != $episode::STATUS_PUBLIC) {
            throw new NotFoundHttpException();
        }
        if (!$episode->podcast->published_yn) {
            throw new NotFoundHttpException();
        }

        return $this->render('episode', ['model' => $episode]);
    }
}