<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 *
 * THIS IS NOT A FREE SOFTWARE. PLEASE, VISIT <https://webkadabra.github.io/yii2-podcast-engine/> FOR DETAILS
 */
?>
<div class="page-header">
    <h1>Podcasts</h1>
</div>
<?=\yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{items}{pager}',
    'itemView' => function ($model, $key, $index, $widget) {?>
        <div class="row">
            <div class="col-sm-8">
                <h3 style="margin-top: 0"><?=$model->title?></h3>
                <p><?=$model->description?></p>
            </div>
            <div class="col-sm-2">
                <?=\yii\helpers\Html::a(Yii::t('app', 'Episodes &raquo;'), ['episodes', 'id' => ($model->alias ? $model->alias : $model->id)], ['class' => 'btn btn-default'])?>
            </div>
        </div>
        <hr />
    <? }
]);
?>