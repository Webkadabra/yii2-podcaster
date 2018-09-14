<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */

echo \yii\helpers\Html::a('<i class="fa fa-report"></i> Full Report', ['report',  ],['class' => 'btn btn-default']),
    ' ',
 \yii\helpers\Html::a('<i class="fa fa-world"></i> Referrals Report', ['referrals',  ],['class' => 'btn btn-default']),
 ' ',
 \yii\helpers\Html::a('<i class="fa fa-world"></i> Devices', ['devices',  ],['class' => 'btn btn-default'])
;

?>
<br />
<br />
<div class="row">
    <div class="col-lg-10 col-lg-offset-1 col-md-12 col-sm-12">
        <div class="card card-loose">
            <div class="card-section">
                <h5 class="text-uppercase report-title"><?=\yii\helpers\Html::a('Daily Downloads:',
                        \yii\helpers\Url::to(['/' . $this->context->module->id.'/stats/daily']))?></h5>
                <?= sjaakp\gcharts\ColumnChart::widget([
                    'dataProvider' => \webkadabra\podcaster\panels\DailyListensPanel::dataProvider(),
                    'columns' => [
                        'date:string',
                        'total_downalods:number',
                    ],
                ]) ?>
            </div>
        </div>

        <div class="card card-loose">
            <div class="card-section">
                <h5 class="text-uppercase report-title"><?=\yii\helpers\Html::a('Monthly Downloads:',
                        \yii\helpers\Url::to(['/' . $this->context->module->id.'/stats/monthly']))?></h5>
                <?= sjaakp\gcharts\LineChart::widget([
                    'dataProvider' => \webkadabra\podcaster\panels\MontlyListensPanel::dataProvider(),
                    'columns' => [
                        'date:string',
                        'total_downalods:number',
                    ],
                ]) ?>
            </div>
        </div>

        <div class="card card-loose">
            <div class="card-section">
                <h5 class="text-uppercase report-title"><?=\yii\helpers\Html::a('Weekly Downloads:',
                        \yii\helpers\Url::to(['/' . $this->context->module->id.'/stats/weekly']))?></h5>
                <?= sjaakp\gcharts\LineChart::widget([
                    'dataProvider' => \webkadabra\podcaster\panels\WeeklyListensPanel::dataProvider(),
                    'columns' => [
                        'date:string',
                        'total_downalods:number',
                    ],
                    'options' => [
                        //'title' => ''
                    ],
                ]) ?>
            </div>
        </div>

        <br />
        <?php if ($count > 0) { ?>
            <div class="alert alert-info">There are <?=$count?> downloads made from your current IP (<?=Yii::$app->request->userIP?>). <?=\yii\helpers\Html::a('Click here',
                    \yii\helpers\Url::toRoute(['maintenance/index']))?> to clean 'em up!</div>
        <?php } ?>
        <div class="text-muted text-right">
            <small class="text-muted">* <?=Yii::t('app', 'Downloads are counted as unique episodes downloads/listens over 24 hours')?>.</small>
        </div>



    </div>
</div>
<?php echo \yii\helpers\Html::img(\yii\helpers\Url::toRoute(['/podcaster/stats/pull-stats']), ['width' => 1, 'height' => 1, 'alt' => 'Empty Pixel']);?>

