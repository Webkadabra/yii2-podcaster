<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */
?>

<div class="row">

    <div class="col-md-7 col-md-offset-1">
        <?if (1==2 && $model->deleted == 1) {?>
            <div class="alert alert-danger">
                <?=Yii::t('app', 'This item is DELETED')?>.
            </div>
        <?} ?>
        <div class="form-inputs-block">



            <?= \yii\widgets\DetailView::widget([
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
        </div>


    </div>


</div>

<hr />

<div class="row">
    <div class="col-lg-6 col-lg-offset-1 col-md-8">
        <?= \yii\helpers\Html::a(Yii::t('app', 'Delete'), ['note/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>

    </div>
</div>

<div class="controls-panel">

</div>