<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully installed <b>Podcaster</b> module!</p>

        <p><a class="btn btn-lg btn-success" href="<?=\yii\helpers\Url::toRoute(['podcaster/podcast'])?>">Podcast Management</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-md-4">
                <h2>Create podcasts</h2>

                <p>Podcaster module support unlimited number of podcasts.</p>

                <p><a class="btn btn-default" href="<?=\yii\helpers\Url::toRoute(['podcaster/podcast'])?>">Podcasts &raquo;</a></p>
            </div>
            <div class="col-md-4">
                <h2>Upload episodes</h2>

                <p>Podcaster supports MP3 podcasts. <br/> Ready to upload?</p>

                <p><a class="btn btn-default" href="<?=\yii\helpers\Url::toRoute(['podcaster/episode'])?>">Episodes  &raquo;</a></p>
            </div>
            <div class="col-md-4">
                <h2>Stats</h2>

                <p>Check out how your podcasts are received by your audience.</p>

                <p><a class="btn btn-default" href="<?=\yii\helpers\Url::toRoute(['podcaster/stats'])?>">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
