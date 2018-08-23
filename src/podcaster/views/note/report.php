<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Episodes');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ui-card">
    <div class="ui-card-tabs">
    </div>
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
                'ip_address',
                'user_agent',
                'time',
                [
                    'format' => 'raw',
                    'attribute' =>  'referral',
                    'value' =>  function ($model, $key, $index, $column) {
                        return \yii\helpers\Html::a(
                            \yii\helpers\StringHelper::truncate($model->referral, 25),
                            $model->referral
                        );
                    }
                ],
                [
                    'format' => 'raw',
                    'attribute' =>  'episode_id',
                    'value' =>  function ($model, $key, $index, $column) {
                        $out = [
                            Html::a('#' . $model->episode->displayTitle,
                                \yii\helpers\Url::toRoute(['episode/view', 'id' => $model->episode_id]),
                                ['data-pjax' => 'false',])
                        ];
                        return $out ? implode('<br />', $out) : '';
                    }
                ],
            ]
        ]); ?>
    </div>
    <?php Pjax::end(); ?>
</div>
