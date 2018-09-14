<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */

use kartik\form\ActiveForm;

$this->title = Yii::t('app', 'Upload Podcast Episode');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Episodes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$form = ActiveForm::begin([
    'enableClientValidation' => false,
    'options' => ['class' => 'ux-form-submit']
]); ?>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <!--<h1><?/*= Html::encode($this->title) */?></h1>-->

        <?=\yii\helpers\Html::errorSummary($model)?>

        <?= $this->render('_form', [
            'form' => $form,
            'model' => $model,
        ]) ?>

    </div>
</div>
<?php ActiveForm::end(); ?>
