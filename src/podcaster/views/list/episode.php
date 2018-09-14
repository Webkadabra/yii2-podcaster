<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */
?>
<style>
    .page-header {
        background: #000;
        padding: 40px;
        color: #fff;
        margin-top: 0;
    }
    .page-header a {
        color: #b190ff;
    }
</style>
<div class="page-header">
    <h3><?=\yii\helpers\Html::a($model->podcast->title, ['episodes', 'id' => $model->podcast_id], ['class' => ''])?></h3>

    <h2 style="margin-top: 0"><?=$model->title?></h2>
</div>

<div class="row">
    <div class="col-sm-8">

        <div class="form-group">
        <p><?=$model->description?></p>
        </div>

        <audio id="player" preload="none" controls style="max-width: 100%;width: 100%">
            <source src="<?=$model->getDownloadUrl()?>" type="audio/mp3">
        </audio>

    </div>
    <div class="col-sm-4">
        <ul class="list-group">
        <?=\yii\widgets\ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}{pager}',
            'itemView' => function ($episode, $key, $index, $widget) use ($model) {
                if ($episode->id == $model->id) {
                    return '';
                }
            ?>
               <li class="list-group-item">
                        <?=\yii\helpers\Html::a('<h3 style="margin-top: 0">'.$episode->title.'</h3>', ['episode', 'id' => $episode->id], ['class' => 'btn btn-link'])?>
                    </li>
            <? }
        ]);
        ?>
        </ul>
    </div>
</div>
