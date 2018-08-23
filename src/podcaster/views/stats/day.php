<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 *
 * THIS IS NOT A FREE SOFTWARE. PLEASE, VISIT <https://webkadabra.github.io/yii2-podcast-engine/> FOR DETAILS
 */
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Playback stats for ' . $date);
$this->params['breadcrumbs'][] = $this->title . ' '
    . \webkadabra\podcaster\models\PodcastStatListen::dateEvents($date);

?>

<?php
//$this->beginBlock('actions');?>
<a href="<?=\yii\helpers\Url::toRoute(['report', 'PodcastDownloadStatsSearch[time]' => $date])?>"
   class="btn btn-default"><i class="fa fa-file-text-o" aria-hidden="true"></i> Full Report</a>
<?// $this->endBlock(); ?>
<br />
<br />
<div class="row">
    <div class="col-lg-10 col-lg-offset-1 col-md-12 col-sm-12">
        <div class="ui-card notification-list">
            <div class="card-section">
                <h5>Episodes:</h5>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'export' => false,
                    'columns' => [
                        [
                            'attribute' => 'episode_id',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) {
                                return \yii\helpers\Html::a('Episode #' .$model->episode->displayTitle
                                    . ' ('.$model->episode->countUniqueDownloads.')',
                                    \yii\helpers\Url::to(['/'.$this->context->module->id.'/stats/episode', 'id' =>$model[$column->attribute]]));
                            },
                        ],
                        [
                            'attribute' => 'total_downalods',
                            'value' => function ($model, $key, $index, $column) {
                                return $model->total_downalods . ' / '.$model->total_downalods2.'';
                            },
                            'pageSummary' => function ($summary, $data, $widget) {
                                if ($widget->grid->filterModel && $widget->grid->dataProvider->query)
                                    return $widget->grid->filterModel->find()->where($widget->grid->dataProvider->query->where)->sum('total_downalods');
                                if ($widget->grid->dataProvider->query)
                                    return \webkadabra\podcaster\models\PodcastEpisodeSearch::find()->where($widget->grid->dataProvider->query->where)->sum('total_downalods');
                                return \webkadabra\podcaster\models\PodcastEpisodeSearch::findBySql($widget->grid->dataProvider->sql)->sum('total_downalods');

                            },
                        ]
                    ],

                ]) ?>
            </div>
        </div>

        <div class="ui-card notification-list">
            <div class="card-section">
                <h5>Hourly Downloads:</h5>
                <?= sjaakp\gcharts\ColumnChart::widget([
                    'dataProvider' => $dataProvider2,
                    'columns' => [
                        'date:string',
                        'total_downalods:number',
                    ],
                ]) ?>
            </div>
        </div>

        <div class="ui-card notification-list">
            <div class="card-section">
                <h5>User Agents:</h5>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider3,
                    'columns' => [
                        'user_agent',
                        'total_downalods',
                    ],
                ]) ?>
            </div>
        </div>

        <div class="ui-card notification-list">
            <div class="card-section">
                <h5>Referrals:</h5>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider4,
                    'columns' => [
                        'referral',
                        'total_downalods',
                    ],
                ]) ?>
            </div>
        </div>

        <div class="ui-card notification-list">
            <div class="card-section">
                <h5>Notes on that date:</h5>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider5,
                    'rowOptions' => function ($model, $index, $widget, $grid){
                        $options = [];
                        if($model->deleted == 1){
                            $options['class'] = 'text-muted';
                        }
                        return $options;
                    },
                    'columns' => \webkadabra\podcaster\models\PodcastNote::defaultGridColumns(['event_date'])
                ]) ?>
            </div>
        </div>

        <br />
        <a href="<?=\yii\helpers\Url::toRoute(['report', 'PodcastDownloadStatsSearch[time]' => $date])?>"
           class="btn btn-default"><i class="fa fa-file-text-o" aria-hidden="true"></i> Full Report</a>
        <a href="<?=\yii\helpers\Url::toRoute(['note/create', 'date' => $date])?>"
           class="btn btn-default"><i class="fa fa-sticky-note" aria-hidden="true"></i> Add a Note</a>

    </div>
</div>

