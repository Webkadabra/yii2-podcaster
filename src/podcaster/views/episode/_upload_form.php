<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 *
 * THIS IS NOT A FREE SOFTWARE. PLEASE, VISIT <https://webkadabra.github.io/yii2-podcast-engine/> FOR DETAILS
 */
use kartik\builder\Form;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
$staticOnly = false;

// script to parse the results into the format expected by Select2
$resultsJs = "function (data, params) {return {results: data };}";
?>


    <div class="row">
        <div class="col-md-4">
            <div class="form-labels-block">
                <label><?=Yii::t('app', 'File Info')?></label>

                <?php if(!$fileModel->isNewRecord) { ?>
                <br />
                <small class="text-muted" title="<?=$model->file->file_folder?>">File ID: <?=$model->file->id?></small>
                <? } ?>

            </div>
        </div>
        <div class="col-md-8">
            <div class="form-inputs-block">
                <?  echo Form::widget([
                    'model'=>$fileModel,
                    'form'=>$form,
                    'staticOnly'=>$staticOnly,
                    'columns'=>1,
                    'attributes'=>[
                        'file_folder'=>['type'=>Form::INPUT_TEXT],
//                        'file_name'=>['type'=>Form::INPUT_TEXT],
                    ]
                ]); ?>
            </div>
            <hr />
            <div class="form-inputs-block text-center">
                <?= Html::submitButton($model->isNewRecord
                    ? Yii::t('app', 'Continue &raquo;')
                    : Yii::t('app', 'Update'), [
                    'class' => 'btn btn-success btn-lg']) ?>

            </div>
        </div>
    </div>

    <? /*echo Form::widget([
        'model'=>$model,
        'form'=>$form,
        'staticOnly'=>$staticOnly,
        'columns'=>2,
        'attributes'=>[
            'first_name'=>['type'=>Form::INPUT_TEXT],
            'last_name'=>['type'=>Form::INPUT_TEXT],
            //                        'middle_name'=>['type'=>Form::INPUT_TEXT],
        ]
    ]);*/ ?>
