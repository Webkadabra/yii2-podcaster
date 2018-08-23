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
use webkadabra\podcaster\models\PodcastEpisodeSearch;
use webkadabra\podcaster\models\PodcastFile;
use Yii;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

class EpisodeController extends \webkadabra\podcaster\components\Controller
{
    public function actionIndex($filter = false) {
        $searchModel = new PodcastEpisodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), [], true);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id) {
        if (($model = PodcastEpisode::findOne($id)) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionSummaryMd($id) {
        if (($model = PodcastEpisode::findOne($id)) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $app = Yii::$app;
        $app->response->format = \yii\web\Response::FORMAT_RAW;
        $app->response->sendContentAsFile($this->renderPartial('summary-md', [
            'model' => $model,
        ]), ($model->slug ? $model->slug : $model->id). '.md');
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function actionCreate($id=null)
    {
        if (!Podcast::defaultPodcast()) {
            Yii::$app->session->addFlash('warning', Yii::t('app', 'You must create at least one podcast before you can add episodes'));
            return $this->redirect(['podcast/create']);
        }
        if ($id) {
            $model = $this->findModel($id);
            if ($model->status !== 'draft') {
                throw new Exception('Item is already published');
            }
        } else {
            $model = new PodcastEpisode();
            $model->applyDefaults();
        }

        $method = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['upload', 'id' => $model->id]);
        } else {
            return $this->$method('create', [
                    'model' => $model,
                ]);
        }
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function actionUpload($id)
    {
        $model = $this->findModel($id);
        if ($model->file) {
            $fileModel = $model->file;
        } else {
            $fileModel = new PodcastFile();
            $fileModel->file_folder = Yii::getAlias($this->module->uploadPath) . $model->slug.'.mp3';
            $fileModel->file_name = $model->slug;
        }
        $method = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
        if ($fileModel->load(Yii::$app->request->post()) && $fileModel->save()) {
            $model->file_id = $fileModel->id;
            $model->save(false);
            return $this->redirect(['publish', 'id' => $model->id]);
        } else {
            return $this->$method('upload', [
                    'model' => $model,
                    'fileModel' => $fileModel,
                ]);
        }
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function actionPublish($id)
    {
        $model = $this->findModel($id);
        if ($model->status !== 'draft') {
            Yii::$app->session->addFlash('error', 'Item is already published');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $method = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
        if (isset($_POST['publish'])) {

            $model->status = 'public';
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->$method('publish', [
                    'model' => $model,
                ]);
        }
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $method = Yii::$app->request->isAjax ? 'renderAjax' : 'render';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $app = Yii::$app;
            $app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'refreshPage' => true
            ];
        } else {
            return '<div id="root">'.$this->$method('_form_update', [
                    'model' => $model,
                ]).'</div>';
        }
    }

    public function actionAutocomplete($podcast=null)
    {
        $podcastModel = PodcastController::findModel($podcast);
        $app = Yii::$app;
        $q = $app->request->get('term');
        $app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = PodcastEpisode::find()
            ->andFilterWhere(['like', PodcastEpisode::tableName().'.title', strtr($q,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false])
            //->orFilterWhere(['like', PodcastEpisode::tableName().'.title_ua', strtr($q,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false])
            //->andWhere([PodcastEpisode::tableName().'.podcast_id' => $podcast])
            ->orderBy(['title' => 'ASC'])
            ->limit(100);
        $data = $query->all();
        $res = [];
        foreach($data as $item) {
            $res[] =   ['id' => $item->id, 'label' => $item->displayTitle];
        }
        return $res;
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PodcastEpisode the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PodcastEpisode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
