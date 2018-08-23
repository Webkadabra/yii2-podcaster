<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Podcasts');
$this->params['breadcrumbs'][] = $this->title;

//$this->beginBlock('actions');
echo \yii\helpers\Html::a('<i class="fa fa-plus"></i> ' . Yii::t('app', 'Add podcast'), ['create',  ],['class' => 'btn btn-primary pull-right']);
//$this->endBlock();
?>
<div class="ui-card">
    <div class="ui-card-tabs"></div>
    <?php Pjax::begin(); ?>
    <div class="card-section card-section--roots">
        <?=$this->render('_search', ['model' => $searchModel])?>
    </div>
    <div class="card-section">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'bordered' => 0,
            'export' => false,
            'columns' => [
                [
                    'attribute' =>  'title',
                    'format' =>'raw',
                    'value' =>  function ($model, $key, $index, $column) {
                        $out = [
                            Html::a($model->title, \yii\helpers\Url::to(['view', 'id' => $model->id]), ['data-pjax' => '0',])
                        ];
                        $out[] = Html::tag('span', implode(' ', [
                            $model->alias,
                        ]));

                        $out[] = Html::a(Yii::t('app', 'Rss Feed'), \yii\helpers\Url::to(['/podcaster/feed/feed', 'podcast' => ($model->alias ? $model->alias : $model->id)]), ['data-pjax' => '0',]);
                        return $out ? implode('<br />', $out) : '';
                    }
                ],
                'description',
                [
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::a('Stats', ['stats/index', 'podcast' => $model->id], ['class' => 'btn btn-default', 'data-pjax' => 'false'])
                            . ' '
                            . Html::a('Details', ['view', 'id' => $model->id], ['class' => 'btn btn-default', 'data-pjax' => 'false'])
                            . ' '
                            . Html::a('Edit', ['update', 'id' => $model->id], ['class' => 'btn btn-default', 'data-pjax' => 'false'])
                            ;
                    },
                    'contentOptions' => ['class' => 'text-right'],
                ]
            ]
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
