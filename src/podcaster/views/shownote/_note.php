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

<div class="media comment card ">
    <div class="media-left media-middle hidden">
        <img style="width: 48px; height: 48px;" class="media-object"  src="<?/*=$model->autoUser->getAvatarUrl()*/?>" />
    </div>
    <div class="media-body">
        <h4 class="media-heading"><?/*=$model->autoUser->username*/?>
            <small class="text-muted"><?=$model->timecode?></small> </h4>
        <!--<div class="pull-right comment-controls">
            <a href="#" class="btn btn-sm btn-default">ред.</a>
        </div>-->
        <?=$model->note?>

        <div class="controls-panel">
            <?= \yii\helpers\Html::a(Yii::t('app', 'Delete'), ['/'.$this->context->module->id.'/shownote/delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
        </div>

    </div>
</div>