<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */
use kartik\grid\GridView;
?>
<br />
<br />
<div class="row">
    <div class="col-lg-10 col-lg-offset-1 col-md-12 col-sm-12">
        <div class="ui-card notification-list">
            <div class="card-section">
                <?= GridView::widget([
                    'export' => false,
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'attribute' => 'user_agent',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) {
                    if (!$model[$column->attribute]) {
                        return '<span title="'.Yii::t('app', 'Unknown client').'">UNKNOWN</span>';
                    }
                                return \yii\helpers\Html::a($model[$column->attribute],
                                    \yii\helpers\Url::to(['/'.$this->context->module->id.'/stats/day', 'date' =>$model[$column->attribute]]));
                            },
                        ],
                        [
                            'attribute' => 'total_downalods',
                            'pageSummary' => function ($summary, $data, $widget) {
//                                            \yii\helpers\VarDumper::dump($widget->grid->dataProvider,5,5);exit;
                                if ($widget->grid->filterModel && $widget->grid->dataProvider->query)
                                    return $widget->grid->filterModel->find()->where($widget->grid->dataProvider->query->where)->sum('total_downalods');
                                return \webkadabra\podcaster\models\PodcastEpisodeSearch::findBySql($widget->grid->dataProvider->sql)->sum('total_downalods');

                            },
                        ]
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>

