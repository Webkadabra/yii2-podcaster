<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Notes');
$this->params['breadcrumbs'][] = $this->title;

//$this->beginBlock('actions');
echo \yii\helpers\Html::a('<i class="fa fa-plus"></i> Add Note', ['create',  ],['class' => 'btn btn-default']);
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
            'rowOptions' => function ($model, $index, $widget, $grid){
                $options = [];
                if($model->deleted == 1){
                    $options['class'] = 'text-muted';
                }
                return $options;
            },
            'columns' => \webkadabra\podcaster\models\PodcastNote::defaultGridColumns()
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
