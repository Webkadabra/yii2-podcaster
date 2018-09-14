<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */
namespace webkadabra\podcaster\controllers;
use webkadabra\podcaster\models\PodcastDownloadStatsSearch;
use webkadabra\podcaster\models\PodcastEpisode;
use webkadabra\podcaster\models\PodcastStatListen;
use yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

class StatsController extends \webkadabra\podcaster\components\Controller
{
    public function actionIndex($podcast=null)
    {
        $count = PodcastStatListen::find()->where(['ip_address' => Yii::$app->request->userIP])->count();
        return $this->render('index', [
            'count' => $count
        ]);
    }

    public function actionReport()
    {
        $searchModel = new PodcastDownloadStatsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), [], true);
        return $this->render('report', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionDaily()
    {
        $this->view->title = Yii::t('app', 'Daily downloads');
        $this->view->params['breadcrumbs'][] = [
            'url' => ['index'],
            'label' => Yii::t('app', 'Stats'),
        ];
        $this->view->params['breadcrumbs'][] = $this->view->title;
        return $this->render('daily',[
            'dataProvider' => \webkadabra\podcaster\panels\DailyListensPanel::dataProvider('DESC'),
        ]);
    }

    public function actionWeekly()
    {
        $this->view->title = Yii::t('app', 'Weekly downloads');
        $this->view->params['breadcrumbs'][] = [
            'url' => ['index'],
            'label' => Yii::t('app', 'Stats'),
        ];
        $this->view->params['breadcrumbs'][] = $this->view->title;
        return $this->render('daily',[
            'dataProvider' => \webkadabra\podcaster\panels\WeeklyListensPanel::dataProvider('DESC'),
        ]);
    }
    public function actionMonthly()
    {
        $this->view->title = Yii::t('app', 'Monthly downloads');
        $this->view->params['breadcrumbs'][] = [
            'url' => ['index'],
            'label' => Yii::t('app', 'Stats'),
        ];
        $this->view->params['breadcrumbs'][] = $this->view->title;
        return $this->render('daily',[
            'dataProvider' => \webkadabra\podcaster\panels\MontlyListensPanel::dataProvider('DESC'),
        ]);
    }

    public function actionReferrals()
    {
        $query = PodcastStatListen::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        $query->select([
            PodcastStatListen::tableName().'.referral',
            'COUNT(DISTINCT '.PodcastStatListen::tableName().'.ip_address) AS total_downalods',
            'COUNT('.PodcastStatListen::tableName().'.ip_address) AS total_downalods2',
        ]);
        $query->groupBy([
            PodcastStatListen::tableName().'.referral',
        ]);
        $query->orderBy('total_downalods desc');
        return $this->render('referrals', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDevices()
    {
        $query = PodcastStatListen::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        $query->select([
            PodcastStatListen::tableName().'.user_agent',
            'COUNT(DISTINCT '.PodcastStatListen::tableName().'.ip_address) AS total_downalods',
            'COUNT('.PodcastStatListen::tableName().'.ip_address) AS total_downalods2',
        ]);
        $query->groupBy([
            PodcastStatListen::tableName().'.user_agent',
        ]);
        $query->orderBy('total_downalods desc');
        return $this->render('devices', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Downloads summary for a given `$date`
     *
     * @param $date
     * @return string
     */
    public function actionDay($date)
    {
        # episodes played that day:
        $query = PodcastStatListen::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        $query->andFilterWhere(['DATE(time)' => date('Y-m-d', strtotime($date)),]);
        $query->select([
            PodcastStatListen::tableName().'.episode_id',
            'COUNT(DISTINCT '.PodcastStatListen::tableName().'.ip_address) AS total_downalods',
            'COUNT('.PodcastStatListen::tableName().'.ip_address) AS total_downalods2',
        ]);
        $query->groupBy([
            PodcastStatListen::tableName().'.episode_id',
        ]);
        $query->orderBy('total_downalods desc');

        # hourly downloads this day
        $query2 = PodcastStatListen::find();
        $dataProvider2 = new ActiveDataProvider([
            'query' => $query2,
            'pagination' => false,
        ]);
        $query2->andFilterWhere(['DATE(time)' => date('Y-m-d', strtotime($date)),]);
        $query2->select([
            'DATE_FORMAT(`time`, "%Y-%m-%d-%H") `dateGroup`',
            'DATE_FORMAT(`time`,"%H") `date`',
            'COUNT(DISTINCT '.PodcastStatListen::tableName().'.ip_address) AS total_downalods',
        ]);
        $query2->groupBy([
            'dateGroup',
            'date',
        ]);
        $query2->orderBy('dateGroup ASC');
        $dates = ArrayHelper::index($dataProvider2->models, 'date');
        $period=range(01,23);
        $stats = [];
        $keys=[];
        foreach ($period as $d){
            if ($d<10) {
                $d = '0'.$d;
            }
            $keys[] = $d;
            $usercount = isset($dates[$d]) ? $dates[$d]['total_downalods'] :0;
            $statRow = new PodcastStatListen();
            $statRow->id = $d;
            $statRow->total_downalods = $usercount;
            $statRow->date = $d;
            $stats[] = $statRow;
        }
        $dataProvider2->keys = $keys; // or there will be issue with rendering table
        $dataProvider2->models = $stats;

        # User Agents
        $query3 = PodcastStatListen::find();
        $dataProvider3 = new ActiveDataProvider([
            'query' => $query3,
            'pagination' => false,
        ]);
        $query3->andFilterWhere(['DATE(time)' => date('Y-m-d', strtotime($date)),]);
        $query3->select([
            PodcastStatListen::tableName().'.user_agent',
            'COUNT(DISTINCT '.PodcastStatListen::tableName().'.ip_address, episode_id) AS total_downalods',
        ]);
        $query3->groupBy([
            PodcastStatListen::tableName().'.user_agent',
        ]);
        $query3->orderBy('total_downalods desc');

        # Referrals
        $query4 = PodcastStatListen::find();
        $dataProvider4 = new ActiveDataProvider([
            'query' => $query4,
            'pagination' => false,
        ]);
        $query4->andFilterWhere(['DATE(time)' => date('Y-m-d', strtotime($date)),]);
        $query4->select([
            PodcastStatListen::tableName().'.referral',
            'COUNT(DISTINCT '.PodcastStatListen::tableName().'.ip_address) AS total_downalods',
        ]);
        $query4->groupBy([
            PodcastStatListen::tableName().'.referral',
        ]);
        $query4->orderBy('total_downalods desc');

        # Referrals
        $dataProvider5 = new ActiveDataProvider([
            'query' => \webkadabra\podcaster\models\PodcastNote::find()
                ->where(['DATE(event_date)' => date('Y-m-d', strtotime($date)),])
                ->orderBy('event_date desc'),
            'pagination' => false,
        ]);

        return $this->render('day', [
            'date' => $date,
            'dataProvider' => $dataProvider,
            'dataProvider2' => $dataProvider2,
            'dataProvider3' => $dataProvider3,
            'dataProvider4' => $dataProvider4,
            'dataProvider5' => $dataProvider5,
        ]);
    }

    /**
     * Episode playbacks summary
     *
     * @param $id
     * @return string
     * @throws HttpException
     */
    public function actionEpisode($id)
    {
        $episode = PodcastEpisode::findOne($id);
        if (!$episode) {
            throw new HttpException(404);
        }
        $query = PodcastStatListen::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        $query->andFilterWhere(['episode_id' => $id]);
        $query->select([
            'DATE_FORMAT(`time`, "%Y-%m-%d") `date`',
            PodcastStatListen::tableName().'.episode_id',
            'COUNT(DISTINCT '.PodcastStatListen::tableName().'.ip_address) AS total_downalods'
        ]);
        $query->groupBy([
            'date',
        ]);
        return $this->render('episode', [
            'episode' => $episode,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPullStats() {
        $filePath = Yii::getAlias('@app/runtime/') . 'db_bypass_listens.txt';
        if (file_exists($filePath)) {
            $data = file_get_contents($filePath);
            $data = explode("---------------------------", $data);
            $parsedClients = [];

            foreach ($data as $datum) {
                $datum = trim($datum);
                $statRaw = explode("\n", $datum);
                $stat = [];
                foreach ($statRaw as $item) {
                    $item = explode(':', $item);
                    if (isset($item[0]) && isset($item[1])) {
                        $stat[$item[0]] = trim($item[1]);
                    }
                }
                if (isset($stat['episode']) && isset($stat['ip']) && isset($stat['date'])) {
                    if (isset($stat['client'])) {
                        if (in_array($stat['client'], $parsedClients))
                            continue;
                        else
                            $parsedClients[] = $stat['client'];
                    }

                    if (is_numeric($stat['episode'])) {
                        $episode = PodcastEpisode::findOne($stat['episode']);
                    } else {
                        $episode = PodcastEpisode::find()->where(['slug' => $stat['episode']])->one();
                    }

                    $statLog = new PodcastStatListen();
                    $statLog->episode_id = $episode->id;
                    $statLog->file_url = $episode->file->file_folder;
                    $statLog->session_client = isset($stat['client']) ? $stat['client'] : null;
                    $statLog->session_dest = isset($stat['dest']) ? $stat['dest'] : null;
                    $statLog->user_agent = isset($stat['user_agent']) ? $stat['user_agent'] : null;
                    $statLog->ip_address = $stat['ip'];
                    $statLog->time = $stat['date'];
                    $statLog->save(false);
                }
            }
            file_put_contents(Yii::getAlias('@app/runtime/') . 'db_bypass_listens.txt', null);
        }
        header('Content-Type: image/gif');
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        die("\x47\x49\x46\x38\x39\x61\x01\x00\x01\x00\x90\x00\x00\xff\x00\x00\x00\x00\x00\x21\xf9\x04\x05\x10\x00\x00\x00\x2c\x00\x00\x00\x00\x01\x00\x01\x00\x00\x02\x02\x04\x01\x00\x3b");
    }
}
