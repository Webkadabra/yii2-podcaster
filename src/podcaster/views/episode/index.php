<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Episodes');
$this->params['breadcrumbs'][] = $this->title;

//$this->beginBlock('actions');
echo \yii\helpers\Html::a('<i class="fa fa-plus"></i> Upload', ['create',  ],['class' => 'btn btn-primary pull-right']);
//$this->endBlock();
?>
<div class="ui-card">
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
                            Html::a($model->displayTitle, \yii\helpers\Url::to(['view', 'id' => $model->id]), ['data-pjax' => 'false',])
                        ];
                        $out[] = Html::tag('span', implode(' ', [
                            $model->title,
                        ]), ['title' => Yii::t('app', 'Title')]);
                        $out[] = $model->description;
                        return $out ? implode('<br />', $out) : '';
                    }
                ],

                    'pub_date',
                [
                    //'attribute' =>  'status',
                    'format' => 'raw',
                    'value' =>  function ($model, $key, $index, $column) {
                        $downloads = $model->countUniqueDownloads;
                        $options = [];
                        if ($downloads <= 2) {
                            $options['class'] = 'label-default text-muted label';
                        }
                        elseif ($downloads > 2) {
                            $options['class'] = 'label-default label';
                        }
                        elseif ($downloads > 20) {
                            $options['class'] = 'label-success label';
                        }
                        elseif ($downloads > 50) {
                            $options['class'] = 'label-warning label';
                        }
                        elseif ($downloads > 150) {
                            $options['class'] = 'label-danger label';
                        }
                        return Html::tag('span', $downloads, $options);
                    },

                ],
                [
                    'format' => 'raw',
                    'value' => function($model) {
                        return Html::a('Stats', ['stats/episode', 'id' => $model->id], ['class' => 'btn btn-default', 'data-pjax' => 'false'])
                        . ' '
                        . Html::a('Details', ['view', 'id' => $model->id], ['class' => 'btn btn-default', 'data-pjax' => 'false'])
                        . ' '
                        . Html::a('Shownotes', ['shownote/episode', 'id' => $model->id], ['class' => 'btn btn-default', 'data-pjax' => 'false'])
                            ;
                    }
                ]
            ]
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
