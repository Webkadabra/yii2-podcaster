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

use webkadabra\podcaster\models\PodcastEpisode;
use webkadabra\podcaster\models\PodcastNote;
use webkadabra\podcaster\models\PodcastNoteSearch;
use Yii;
use yii\db\Expression;
use yii\web\NotFoundHttpException;

class NoteController extends \webkadabra\podcaster\components\Controller
{
    public function actionIndex($filter = false) {
        $searchModel = new PodcastNoteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), [], true);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($date = null, $episode= null, $podcast= null)
    {
        $model = new PodcastNote();
        if (!Yii::$app->request->isPost) {
            if ($date) $model->event_date = $date;
            if ($episode) $model->episode_id = $episode;
//            if ($podcast) $model->podcast_id = $podcast;
        }
        $model->podcast_id = $this->module->podcast->id;
        if ($model->load(Yii::$app->request->post())) {
            $model->owner_user_id = Yii::$app->user->id;
            if ($model->save()) {
                $model->note = '';
                $order = PodcastEpisode::findOne($model->episode_id);
                if (Yii::$app->request->isPjax)
                    return $this->render('_form', [
                        'model' => $model,
                        'order' => $order,
                        'ok' => 1,
                    ]);
                else {

                }
            }
        }

        if (Yii::$app->request->isPjax)
            return $this->redirect(['/'.$this->module->id.'/shownote/episode', 'id' => $model->episode_id]);
        else
            return $this->render('create', [
                'model' => $model,
            ]);
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('update', [
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
        $model = $this->findModel($id);
        $model->deleted = 1;
        $model->deleted_on = new Expression('NOW()');
        $model->save();
        return $this->redirect(['note/index']);
        return $this->redirect(['/'.$this->module->id.'/shownote/episode', 'id' => $model->episode_id]);

    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PodcastNote the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PodcastNote::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
