<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 *
 * THIS IS NOT A FREE SOFTWARE. PLEASE, VISIT <https://webkadabra.github.io/yii2-podcast-engine/> FOR DETAILS
 */
namespace webkadabra\podcaster\controllers;

class DefaultController extends \webkadabra\podcaster\components\Controller
{
    public function actionIndex()
    {
        return $this->redirect(['/'.$this->module->id . '/stats']);
    }
}
