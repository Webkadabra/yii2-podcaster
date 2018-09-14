<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */
use kartik\grid\GridView;

$this->title = Yii::t('app', 'Playback stats for episode #' . $episode->displayTitle);
$this->params['breadcrumbs'][] = $this->title;
?>
<br />
<br />
<div class="row">
    <div class="col-lg-10 col-lg-offset-1 col-md-12 col-sm-12">
        <div class="ui-card notification-list">
            <div class="card-section">
                <?= GridView::widget([
                    'export' => false,
                    'showPageSummary' => true,
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'attribute' => 'date',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) {
                                return \yii\helpers\Html::a($model->date,
                                    \yii\helpers\Url::to(['/'.$this->context->module->id.'/stats/episode-date',
                                        'id' =>$model->episode_id,
                                        'date' =>$model[$column->attribute],
                                    ]));
                            },
                        ],
                        [
                            'attribute' => 'total_downalods',
                            'pageSummary' => function ($summary, $data, $widget) use ($episode) {
                                return $episode->countUniqueDownloads;
                            },
                        ]
                    ],

                ]) ?>
            </div>
        </div>



    </div>
</div>

