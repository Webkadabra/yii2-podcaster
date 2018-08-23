<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 *
 * THIS IS NOT A FREE SOFTWARE. PLEASE, VISIT <https://webkadabra.github.io/yii2-podcast-engine/> FOR DETAILS
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>
<script>
    function clearForm(el)
    {
        var $context = $(el);
        $(':input, select', $context).not(':button, :submit, :reset, :hidden, :checkbox, :radio').val('');
        if (typeof $.select2 !== 'undefined')
            $('select', $context).select2("val", "");;

        $(':checkbox, :radio', $context).prop('checked', false);
    }
</script>
<? $formId = md5(get_class($model)); ?>

<button class="btn btn-befault btn-sm" type="button" data-toggle="collapse" data-target="#collapse<?=$formId?>" aria-expanded="false" aria-controls="collapse<?=$formId?>">
    <i class="fa fa-filter"></i>
</button>

<div class="collapse" id="collapse<?=$formId?>">
    <div class="post-search">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'id' => $formId,
            'method' => 'get','layout' => 'horizontal',
            'options' => ['data-pjax' => true],
        ]); ?>
        <?= $form->field($model, 'searchQuery')?>
        <?= $form->field($model, 'event_date')->widget('\kartik\daterange\DateRangePicker', [
            'convertFormat'=>true,
            'pluginOptions'=>[
                'locale'=>[
                    'format'=>'Y-m-d',
                    'separator' => ' -- ',
                ],
            ]
        ]) ?>
        <div class="form-group">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('Reset', ['class' => 'btn btn-default', 'onclick' => 'clearForm("#'.$formId.'");return false;']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>