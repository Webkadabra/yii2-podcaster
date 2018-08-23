<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 *
 * THIS IS NOT A FREE SOFTWARE. PLEASE, VISIT <https://webkadabra.github.io/yii2-podcast-engine/> FOR DETAILS
 */

/**
 * @var $model \webkadabra\podcaster\models\PodcastEpisode
 * @var $this \yii\web\View
 */
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \webkadabra\podcaster\models\PodcastEpisode */
$title = $model->displayTitle;
if ($model->getIsNewInSeries()) {
    $title .= ' <span class="label label-success" title="'.Yii::t('app', 'Newest in series!').'">New</span>';
}
$this->title = Yii::t('app', 'Episode {ep}', ['ep' => '#' . $model->displayTitle]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Episodes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;


//$this->beginBlock('actions');
echo Html::beginForm('');
if ($buttons = $model->getEpisodeControls()) {
    echo \yii\bootstrap\ButtonDropdown::widget($buttons), ' ';
}
//$this->endBlock();

?>
<div class="row">

    <div class="col-md-6 col-md-offset-1">
        <?if (1==2 && $model->deleted_yn == 1) {?>
            <div class="alert alert-danger">
                <?/*=Yii::t('app', 'This item is DELETED') */?>.
            </div>
        <?} ?>
        <div class="card">
            <div class="clearfix">
                <?=Html::a(Yii::t('app', 'Update'), \yii\helpers\Url::toRoute(['update', 'id' => $model->id]), [
                        'class' => 'btn-modal-control pull-right btn btn-default',
                        'data-ux-modal-type' => 'iframe',
                    ])?>
                <h3 style="margin:0 0 15px 0"><?= Html::encode($model->displayTitle)?></h3>
                <div class="form-control">
                    <?=Html::a('<i class="fa fa-share-square-o" aria-hidden="true"></i> '
                        . \yii\helpers\StringHelper::truncate($model->getDownloadUrl(), 80), $model->getDownloadUrl(), [
                        'class' => ' ',
                    ])?>
                </div>
            </div>

            <?= DetailView::widget([
                'model' => $model,
//                'columns' => [
//                    [
//                        'attribute' => 'date',
//                        'format' => 'raw',
//                        'value' => function ($model, $key, $index, $column) {
//                            return \yii\helpers\Html::a($model->date, /*\yii\helpers\Url::to([
//                                    '/'.$this->context->module->id.'/stats/episode-date',
//                                    'id' =>$model->episode_id,'date' =>$model[$column->attribute],
//                                ])*/ '');
//                        },
//                    ],[
//                        'attribute' => 'total_sales',
//                    ]
//                ],
            ]) ?>
            <br />
            <br />
            <hr />

            <div class="clearfix">
                <span class="fa fa-lg fa-file-text-o" aria-hidden="true"></span>
                <span class="text-uppercase"><?=Yii::t('app', 'Show Notes')?></span>

                <div class="pull-right">
                    <a class="btn btn-primary" href="<?=\yii\helpers\Url::to(['/podcaster/shownote/episode', 'id' => $model->id])?>"><?=Yii::t('app', 'View')?></a>
                </div>
            </div>
        </div>


    </div>


    <div class="col-md-3">
        <div class="card card-one text-center card-muted" style="background-color: #c5f3ec;">

        <h3 style="font-size: 40px;
    padding: 7px;
    margin: 0;"><?=$model->countUniqueDownloads?></h3>
        <h4 style="margin: 0">Total downloads</h4>
        </div>

        <audio id="player" preload="none" controls style="max-width: 100%;width: 100%">
            <source src="<?=$model->backendDownloadUrl?>" type="audio/mp3">
        </audio>
    </div>
</div>


<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <h3 class="section-title"><?=Yii::t('app', 'Downloads')?>:</h3>
        <div class="ui-card">
            <div class="card-section">
                <?= GridView::widget([
                    'dataProvider' => $model->reportDaily(),
                    'bordered' => false,
                    'export' => false,
                    'striped' => false,
                    'columns' => [
                        [
                            'attribute' => 'date',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) {
                                return \yii\helpers\Html::a($model->date, \yii\helpers\Url::toRoute([
                                        'stats/report',
                                        'PodcastDownloadStatsSearch[episode_id]' => $model->episode_id,
                                        'PodcastDownloadStatsSearch[time]' => $model->date])  );
                            },
                        ],[
                            'attribute' => 'total_downalods',
                        ]
                    ],
                ]) ?>
                <a href="<?=\yii\helpers\Url::toRoute(['stats/report', 'PodcastDownloadStatsSearch[episode_id]' => $model->id])?>"
                   class="btn btn-default"><i class="fa fa-file-text-o" aria-hidden="true"></i> Full Report</a>
            </div>
        </div>
    </div>
</div>

