<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 *
 * THIS IS NOT A FREE SOFTWARE. PLEASE, VISIT <https://webkadabra.github.io/yii2-podcast-engine/> FOR DETAILS
 */
namespace webkadabra\podcaster;

use webkadabra\podcaster\models\Podcast;
class Module extends \yii\base\Module
{
    public $allowedRoles = ['@'];

    public $controllerNamespace = 'webkadabra\podcaster\controllers';

    public $uploadPath = null;

    public function getPodcast() {
        return Podcast::defaultPodcast();
    }
}