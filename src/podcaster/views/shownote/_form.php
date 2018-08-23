<?php
use kartik\form\ActiveForm;
use yii\helpers\Html;

?>
<?php

$this->registerJs(
    '$("#order-comment-form").on("pjax:start", function() {
        $("#order-comment-form .btn-lh").button(\'loading\');
    });', \yii\web\View::POS_READY
);
$this->registerJs(
    '$("#order-comment-form").on("pjax:end", function() {
        $.pjax.reload({container:"#order-comment-list"});
        $("#order-comment-form .btn-lh").button(\'reset\');
        
        $("#comment-success-alert").fadeTo(2000, 500).slideUp(500, function(){
            $("#comment-success-alert").slideUp(500);
        });

    });', \yii\web\View::POS_READY
);
\yii\widgets\Pjax::begin([
    'id' => 'order-comment-form',
    'timeout' => false,
    'enablePushState' => false]) ?>
<div class="EventCommentFormWrapper">
    <?php $form = ActiveForm::begin(['action'=>'/'.$this->context->module->id . '/shownote/create', 'options'=>['data-pjax'=> true]]); ?>
    <?=$form->errorSummary($model)?>
    <div class="media comment">
        <div class="media-left media-top">
            <img style="width: 48px; height: 48px;" class="media-object"  src="<?=Yii::$app->user->identity->getAvatarUrl()?>" />
        </div>
        <div class="media-body">
            <?
            $staticOnly = false;
            echo $form->field($model, 'note')->textarea()->label(false);
            echo Html::activeHiddenInput($model, 'episode_id', ['value' => $order->id]);
            echo Html::activeHiddenInput($model, 'timecode', ['value' => $model->timecode]);
            ?>
            <div class="clearfix">

                <? if (isset($ok)) {
                    echo '<div id="comment-success-alert" style="display: -inline-block" class="alert alert-info pull-left">'.Yii::t('app', 'Comment posted').'</div>';
                } ?>
                <div class="pull-right">
                    <?= Html::button('Submit', [
                        'type'=>'submit',
                        'class'=>'btn btn-primary btn-lh',
                        'data-loading-text' => '<span class="fa fa-spinner fa-spin"></span>'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php yii\widgets\Pjax::end() ?>
