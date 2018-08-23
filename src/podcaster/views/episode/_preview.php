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
$staticOnly = true;

// script to parse the results into the format expected by Select2
$resultsJs = "function (data, params) {return {results: data };}";
?>



    <div class="row">
        <div class="col-md-4">
            <div class="form-labels-block">
                <label><?=Yii::t('app', 'Media Preview')?></label>
                <br />
                <small class="text-muted" title="<?=$model->file->file_folder?>">File ID: <?=$model->file->id?></small>
                <br />
                <?=Html::a('Изменить', ['upload', 'id' => $model->id],
                    ['class' => 'btn btn-default btn-sm btn-outline']);?>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-inputs-block">
                <audio id="player" preload="none" controls style="max-width: 100%;width: 100%">
                    <source src="<?=$model->backendDownloadUrl?>" type="audio/mp3">
                </audio>


            </div>
        </div>
    </div>
    <hr />
<div class="row">
        <div class="col-md-4">
            <div class="form-labels-block">
                <label><?=Yii::t('app', 'Episode Info')?></label>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-inputs-block">
                <?  echo Form::widget([
                    'model'=>$model,
                    'form'=>$form,
                    'staticOnly'=>$staticOnly,
                    'columns'=>1,
                    'attributes'=>[
                        'title'=>['type'=>Form::INPUT_TEXT],
                        'description'=>['type'=>Form::INPUT_TEXTAREA],
                        'slug'=>['type'=>Form::INPUT_TEXT, 'fieldConfig'=>[
                            'addon' => ['append' => ['content'=>'.mp3']]]
                        ],
                        'link'=>['type'=>Form::INPUT_TEXT, 'fieldConfig' =>[
                                        'staticValue'=>Html::a($model->link, $model->link),
                        ]],
                        'pub_date' => [
                            'type'=>Form::INPUT_WIDGET,
                            //                'widgetClass'=>'\kartik\datecontrol\DateControl',
                            'widgetClass'=>'\kartik\datetime\DateTimePicker',
                            'options'=>[
                                'options'=>[
                                    'options'=>['placeholder'=>'Date from...']
                                ]
                            ],
                        ],
                    ]
                ]); ?>

                <?=Html::a('Изменить', ['create', 'id' => $model->id],
                    ['class' => 'btn btn-default btn-sm']);?>
            </div>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-md-4">
            <div class="form-labels-block">
                <label><?=Yii::t('app', 'Additional Properties')?></label>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-inputs-block">
                <?  echo Form::widget([
                    'model'=>$model,
                    'form'=>$form,
                    'staticOnly'=>$staticOnly,
                    'columns'=>1,
                    'attributes'=>[
                        'number'=>['type'=>Form::INPUT_TEXT],
                        'custom_artwork_url'=>[
                                'type'=>Form::INPUT_TEXT,
                        'hint' => 'If not set, default will be used ' . $this->context->module->getPodcast()->default_artwork_url],
                        'rss_description_append'=>['type'=>Form::INPUT_TEXTAREA],

                    ]
                ]); ?>
                <?=Html::a('Изменить', ['create', 'id' => $model->id],
                    ['class' => 'btn btn-default btn-sm']);?>
            </div>
            <hr />
            <div class="form-inputs-block text-center">
                <?= Html::submitButton('Publish', [
                    'data' => [
                        'confirm' => Yii::t('app', 'Have you gots balls?!'),
                        'method' => 'post',
                    ],
                    'name' => 'publish',
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
