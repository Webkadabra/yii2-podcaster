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
?>
<br />
<br />
<div class="row">
    <div class="col-lg-10 col-lg-offset-1 col-md-12 col-sm-12">
        <div class="ui-card notification-list">
            <div class="card-section">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'export' => false,
                    'columns' => [
                        [
                            'attribute' => 'referral',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) {
                                if (!$model[$column->attribute]) {
                                    return '<span title="Неизвестный рефер или клиент">UNKNOWN</span>';
                                }
                                return \yii\helpers\Html::a($model[$column->attribute],
                                    \yii\helpers\Url::to(['/'.$this->context->module->id.'/stats/day', 'date' =>$model[$column->attribute]]));
                            },
                        ],
                        [
                            'attribute' => 'total_downalods',
                            'pageSummary' => function ($summary, $data, $widget) {
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

