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
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\web\JsExpression;
use webkadabra\podcaster\models\Podcast;

/* @var $this yii\web\View */
/* @var $model webkadabra\podcaster\models\Podcast */
/* @var $form yii\widgets\ActiveForm */
$staticOnly = false;
// script to parse the results into the format expected by Select2
$resultsJs = "function (data, params) {return {results: data };}";
?>
<script>
    var form_podcast = "<?=$model->podcast_id?>";
</script>

<div class="modal-body">
    <?  echo Form::widget([
        'model'=>$model,
        'form'=>$form,
        'staticOnly'=>$staticOnly,
        'columns'=>1,
        'attributes'=>[
            'podcast_id'=>[
                'type'=>Form::INPUT_WIDGET,
                'widgetClass'=>'\kartik\select2\Select2',
                'options'=>[
                    'data'=>Podcast::availableOptions(),
//                            'pluginOptions' => [
//                                'onChange'=>new JsExpression('function(){country = this.value;}'),
//                            ],
                    'pluginEvents' => [
                        'change'=>'function(){form_podcast = this.value;}',
                    ],
                ],
            ],
            'episode_id'=>[
                'type'=>Form::INPUT_WIDGET,
                'widgetClass'=>'\kartik\select2\Select2',
                'options'=>[
                    'initValueText' => $model->episode ? $model->episode->displayTitle : null,
                    'value' => $model->episode_id,
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 2,
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(city) { return (city.text ? city.text : city.label); }'),
                        'templateSelection' => new JsExpression('function (city) { return (city.text ? city.text : city.label); }'),
                        'ajax' => [
                            'url' => new JsExpression('function() { return "'.\yii\helpers\Url::toRoute(['episode/autocomplete']).'?podcast="+form_podcast; }'),
                            'dataType' => \yii\web\Response::FORMAT_JSON,
//                                'type' => 'POST',
//                                'data' => new JsExpression('function(params) { return {q:params.term, page: params.page}; }'),
                            'processResults' => new JsExpression($resultsJs),
//                                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                    ],
                    'pluginEvents' => [
                        'change'=>'function(){region = this.value;}',
                    ],
                ],
            ],
            'note'=>['type'=>Form::INPUT_TEXTAREA],
            'event_date' => [
                'type'=>Form::INPUT_WIDGET,
                'widgetClass'=>'\kartik\datetime\DateTimePicker',
                'options'=>[
                    'options'=>[
                        //'options'=>['placeholder'=>'Date from...']
                    ]
                ],
            ],
        ]
    ]); ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Continue') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>


</div>
