<?php
use kartik\builder\Form;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model webkadabra\podcaster\models\Podcast */
/* @var $form yii\widgets\ActiveForm */
$staticOnly = false;
// script to parse the results into the format expected by Select2
$resultsJs = "function (data, params) {return {results: data };}";
?>
<div class="modal-body">
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
    <?  echo Form::widget([
        'model'=>$model,
        'form'=>$form,
        'staticOnly'=>$staticOnly,
        'columns'=>1,
        'attributes'=>[
            'title'=>['type'=>Form::INPUT_TEXT],
            'alias'=>['type'=>Form::INPUT_TEXT],
            'description'=>['type'=>Form::INPUT_TEXTAREA],
            'rss_description_append'=>['type'=>Form::INPUT_TEXTAREA],
        ]
    ]); ?>
    <?  echo Form::widget([
        'model'=>$model,
        'form'=>$form,
        'staticOnly'=>$staticOnly,
        'columns'=>1,
        'attributes'=>[
            'language'=>[
                 'type'=>Form::INPUT_TEXT,
                'hint' => 'e.g. en-us, ru-ru'
            ],
            'author_name'=>['type'=>Form::INPUT_TEXT],
            'author_email'=>['type'=>Form::INPUT_TEXT],
            'default_artwork_url'=>['type'=>Form::INPUT_TEXT],
            'website'=>['type'=>Form::INPUT_TEXT],
            'defaultFeedUrl'=>['type'=>Form::INPUT_TEXT],
            'marketing_tweet'=>['type'=>Form::INPUT_TEXT],
            'marketing_hashtag'=>['type'=>Form::INPUT_TEXT],
        ]
    ]); ?>
    <h5>iTunes properties:</h5>
    <?  echo Form::widget([
        'model'=>$model,
        'form'=>$form,
        'staticOnly'=>$staticOnly,
        'columns'=>1,
        'attributes'=>[
            'itunes_category'=>['type'=>Form::INPUT_TEXT],
            'itunes_artwork_url'=>['type'=>Form::INPUT_TEXT],
            'itunes_explicit_yn'=>['type'=>Form::INPUT_CHECKBOX],
        ]
    ]); ?>
    <hr/>
    <?  echo Form::widget([
        'model'=>$model,
        'form'=>$form,
        'staticOnly'=>$staticOnly,
        'columns'=>1,
        'attributes'=>[
            'published_yn'=>['type'=>Form::INPUT_CHECKBOX],
        ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Continue') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>


</div>
