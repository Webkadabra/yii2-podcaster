<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */
/**
 * @var $model \webkadabra\podcaster\models\PodcastEpisode
 * @var $this \yii\web\View
 */

$this->title = Yii::t('app', 'Episode {ep} Shownotes', ['ep' => '#' . $model->displayTitle]);
$this->params['breadcrumbs'][] = [
    'label' =>  $model->podcast->title,
    'url' => ['/'.$this->context->module->id.'/podcast/view', 'id' => $model->podcast->id]];

$this->params['breadcrumbs'][] = [
    'label' =>  Yii::t('app', 'Episode {ep}', ['ep' => '#' . $model->displayTitle]),
    'url' => ['/'.$this->context->module->id.'/episode/view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Show Notes');

$js = <<<JS

$("#resumeButton").click(function(e) {
    e.preventDefault();
    var \$el = $(this);
    $("audio#player").get(0).currentTime = \$el.attr('data-timecode');
    $("audio#player").get(0).play();
});
$("audio#player").get(0).addEventListener("timeupdate",function(){
        // do your stuff here
        
        var duration = this.duration; //song is object of audio.  song= new Audio();

    var sec= new Number();
    var min= new Number();
     sec = Math.floor( duration );    
     min = Math.floor( sec / 60 );
    min = min >= 10 ? min : '0' + min;    
    sec = Math.floor( sec % 60 );
    sec = sec >= 10 ? sec : '0' + sec;

    $("#total_duration").html(min + ":"+ sec);   //Id where i have to print the total duration of song.
    $("#current_time").html(this.currentTime);   //Id where i have to print the total duration of song.
    $("#podcastshownote-timecode").val(this.currentTime);   //Id where i have to print the total duration of song.
    
    });
JS;

$this->registerJs($js);
$lastTimecode = null;
foreach ($notesDataProvider->models as $node) {
    if ($node->timecode) {
        $lastTimecode = $node->timecode;
        break;
    }
}
?>



    <audio id="player" preload="none" controls style="max-width: 100%;width: 100%">
        <source src="<?=$model->backendDownloadUrl?>" type="audio/mp3">
    </audio>

    <br />
    <span class="label label-default" id="total_duration"></span>
    <span class="label label-default" id="current_time"></span>
<?php
if ($lastTimecode) {
    echo \yii\helpers\Html::a('Set Time To '.$lastTimecode, '', [
        'data-timecode' => $lastTimecode,
        'id' => 'resumeButton',
        'class' => 'btn btn-outline btn-default btn-sm',
    ]);
}
?>
<?=\yii\helpers\Html::a('<span class="label label-default">'.$model->backendDownloadUrl.'</span>', $model->backendDownloadUrl, ['target' => '_blank'])?>

    <hr />

    <div class="container-fluid">
        <h3 class="section-title"><?=Yii::t('app', 'Shownotes')?></h3>
    </div>
<?=$this->render('_form',[
    'model' => new \webkadabra\podcaster\models\PodcastShownote(),
    'order' => $model,
])?>
<?php \yii\widgets\Pjax::begin(['id' => 'order-comment-list']); ?>
    <div class="ui-feed">
        <?= \yii\widgets\ListView::widget([
            'dataProvider' => $notesDataProvider,
            'itemView' => '_note',
            'layout' => '{items}',
            'options' => ['class' => 'ui-feed-list media-list']
        ]) ?>
    </div>
<?php \yii\widgets\Pjax::end(); ?>