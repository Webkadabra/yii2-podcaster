<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */
namespace webkadabra\podcaster\controllers;

class DefaultController extends \webkadabra\podcaster\components\Controller
{
    public function actionIndex()
    {
        return $this->redirect(['/'.$this->module->id . '/stats']);
    }
}
