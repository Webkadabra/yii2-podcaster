<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
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