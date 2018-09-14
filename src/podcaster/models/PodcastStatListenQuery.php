<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */
namespace webkadabra\podcaster\models;

/**
 * This is the ActiveQuery class for [[PodcastStatListen]].
 *
 * @see PodcastStatListen
 */
class PodcastStatListenQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PodcastStatListen[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PodcastStatListen|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
