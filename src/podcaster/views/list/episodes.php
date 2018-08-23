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
    <h1><?=$podcast->title?></h1>
</div>
<?=\yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => '{items}{pager}',
    'itemView' => function ($model, $key, $index, $widget) {?>
        <div class="row">
            <div class="col-sm-12">
                <h3 style="margin-top: 0"><?=$model->title?></h3>
                <p><?=$model->description?></p>

                <audio id="player" preload="none" controls style="max-width: 100%;width: 100%">
                    <source src="<?=$model->getDownloadUrl()?>" type="audio/mp3">
                </audio>

            </div>
            <div class="col-sm-12">
                <?=\yii\helpers\Html::a(Yii::t('app', 'Permalink &raquo;'), ['episode', 'id' => $model->id], ['class' => 'btn btn-link'])?>
            </div>
        </div>
        <hr />
    <? }
]);
?>