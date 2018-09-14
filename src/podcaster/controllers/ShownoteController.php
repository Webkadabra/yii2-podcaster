<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */
namespace webkadabra\podcaster\controllers;

use webkadabra\podcaster\models\PodcastEpisode;
use webkadabra\podcaster\models\PodcastShownote;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\NotFoundHttpException;

class ShownoteController extends \webkadabra\podcaster\components\Controller
{
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
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PodcastShownote();
        if ($model->load(Yii::$app->request->post())) {
            $model->owner_user_id = Yii::$app->user->id;
            if ($model->save()) {
                $model->note = '';
                $order = PodcastEpisode::findOne($model->episode_id);
                return $this->render('_form', [
                    'model' => $model,
                    'order' => $order,
                    'ok' => 1,
                ]);
            }
        }
        return $this->redirect(['/'.$this->module->id.'/shownote/episode', 'id' => $model->episode_id]);
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
        $model->delete();

        return $this->redirect(['/'.$this->module->id.'/shownote/episode', 'id' => $model->episode_id]);
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PodcastShownote the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PodcastShownote::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
