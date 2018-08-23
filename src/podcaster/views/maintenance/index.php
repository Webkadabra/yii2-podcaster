<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 *
 * THIS IS NOT A FREE SOFTWARE. PLEASE, VISIT <https://webkadabra.github.io/yii2-podcast-engine/> FOR DETAILS
 */
$form = \yii\bootstrap\ActiveForm::begin([
    'enableClientValidation' => false,
    'options' => ['class' => 'ux-form-submit']
]); ?>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        Clean recent records with your IP: <?=Yii::$app->request->userIP?>?
        <br/ >
        There are <b><?=$count?></b> records.

        <hr />
        <div class="form-inputs-block text-center">
            <?= \yii\bootstrap\Html::submitButton(Yii::t('app', 'Clean'), [
                'class' => 'btn btn-success btn-lg']) ?>

        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
