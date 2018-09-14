<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */
namespace webkadabra\podcaster\controllers;

use webkadabra\podcaster\models\Podcast;
use webkadabra\podcaster\models\PodcastEpisode;
use webkadabra\podcaster\models\PodcastSearch;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;

class PodcastController extends \webkadabra\podcaster\components\Controller
{
    public function actionIndex($filter = false) {
        $searchModel = new PodcastSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), [], true);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Podcast();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id, 'gettingStarted' => true]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionView($id) {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionEpisode($id) {
        $episode = PodcastEpisode::findOne($id);
        $notesDataProvider = new ArrayDataProvider([
            'pagination' => false,
            'models' => $episode->shownotesReversed,
        ]);
        return $this->render('episode', [
            'model' => $episode,
            'notesDataProvider' => $notesDataProvider,
        ]);
    }

    /**
     * Updates an existing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $method = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->addFlash('success', Yii::t('app', 'Changes saved'));
            if (Yii::$app->request->isAjax) {
                $app = Yii::$app;
                $app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'refreshPage' => true
                ];
            } else {
                return $this->refresh();
            }
        } else {
            if (Yii::$app->request->isAjax) {
                $view = 'update_popup';
            } else {
                $view = 'update';
            }
            return $this->{$method}($view, [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        Yii::$app->session->addFlash('warning',
            'Podcasts can not be deleted - to remove podcast from public access, simply deactivate it');
        return $this->redirect(['update', 'id' => $id]);
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Podcast the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public static function findModel($id)
    {
        if (($model = Podcast::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
