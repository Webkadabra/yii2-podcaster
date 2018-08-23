<?php
use kartik\form\ActiveForm;

$this->title = Yii::t('app', 'Add New Podcast');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Podcasts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


$form = ActiveForm::begin([
    'enableClientValidation' => false,
    'options' => ['class' => 'ux-form-submit']
]); ?>
<div class="row">
    <div class="col-sm-12">
        <!--<h1><?/*= Html::encode($this->title) */?></h1>-->
        <div class="card">
            <?=\yii\helpers\Html::errorSummary($model)?>

            <hr />
            <?= $this->render('_form', [
                'form' => $form,
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
