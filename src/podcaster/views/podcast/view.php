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

$this->title = Yii::t('app', $model->title);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Episodes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
?>
<div class="row">

    <div class="col-md-7 col-md-offset-1">
        <?if (1==2 && $model->deleted_yn == 1) {?>
            <div class="alert alert-danger">
                <?/*=Yii::t('app', 'This item is DELETED') */?>.
            </div>
        <?} ?>
        <div class="form-inputs-block">
            <div class="clearfix">
                <h5 class="pull-right"><?=Html::a(Yii::t('app', 'Update'), \yii\helpers\Url::toRoute(['update', 'id' => $model->id]), [
                        'class' => 'btn-modal-control pull-right btn btn-default',
                        'data-ux-modal-type' => 'iframe',
                    ])?></h5>
                <h3 style="margin:0"><?= Html::encode($model->title)?></h3>

            </div>
            <?= DetailView::widget([
                'model' => $model,
            ]) ?>
            <br />
            <br />
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center card-muted card-one" style="background-color: #c5f3ec;margin-bottom: 2rem;padding:2rem">
        <h3 style="font-size: 40px;
    padding: 7px;
    margin: 0;"><?=$model->countUniqueDownloads  ?></h3>
        <h4 style="margin: 0">Total downloads</h4>
            <br />
            <a href="<?=\yii\helpers\Url::to(['/podcaster/stats', 'channel' => $model->id])?>"
               class="btn btn-default btn-outline"><?=Yii::t('app', 'Open stats')?></a>
        </div>
        <div class="card">
            <a href="<?=\yii\helpers\Url::toRoute(['/podcaster/note/', 'PodcastNoteSearch[podcast_id]' => $model->id])?>"
               class="btn btn-default btn-outline"><?=Yii::t('app', 'Shownotes & notes')?></a>
        </div>
    </div>
</div>