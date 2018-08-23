<?php
/**
 * @author Sergii Gamaiuno <devkadabra@gmail.com>
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
                    'showPageSummary' => true,
                    'dataProvider' => $dataProvider,
                    'export' => false,
                    'columns' => [
                        [
                            'attribute' => 'date',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) {
                                return \yii\helpers\Html::a($model[$column->attribute],
                                    \yii\helpers\Url::to(['/'.$this->context->module->id.'/stats/day', 'date' =>$model[$column->attribute]]))
                                    . ' ' . \webkadabra\podcaster\models\PodcastStatListen::dateEvents($model[$column->attribute]);
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

