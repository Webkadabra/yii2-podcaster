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
$staticOnly = false;

// script to parse the results into the format expected by Select2
$resultsJs = "function (data, params) {return {results: data };}";
?>


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
                        'podcast_id'=>['type'=>Form::INPUT_DROPDOWN_LIST, 'items' => \yii\helpers\ArrayHelper::map(\webkadabra\podcaster\models\Podcast::find()->all(),'id', 'title')],
                    ]
                ]); ?>
            </div>
        </div>
    </div>
<hr/>
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
                        'link'=>['type'=>Form::INPUT_TEXT],
                        'youtube_video_id'=>['type'=>Form::INPUT_TEXT],
                        'pub_date' => [
                            'type'=>Form::INPUT_WIDGET,
                            //                'widgetClass'=>'\kartik\datecontrol\DateControl',
                            'widgetClass'=>\kartik\datetime\DateTimePicker::class,
                            'options'=>[
                                'options'=>[
                                    'options'=>['placeholder'=>'Date from...']
                                ]
                            ],
                        ],
                    ]
                ]); ?>
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
