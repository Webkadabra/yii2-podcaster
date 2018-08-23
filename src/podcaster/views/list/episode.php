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
    <h3><?=\yii\helpers\Html::a($model->podcast->title, ['episodes', 'id' => $model->podcast_id], ['class' => 'btn btn-link'])?></h3>

    <h2 style="margin-top: 0"><?=$model->title?></h2>
</div>

<div class="row">
    <div class="col-sm-12">
        <p><?=$model->description?></p>

        <audio id="player" preload="none" controls style="max-width: 100%;width: 100%">
            <source src="<?=$model->getDownloadUrl()?>" type="audio/mp3">
        </audio>

    </div>
</div>
