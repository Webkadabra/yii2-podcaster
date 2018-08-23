<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 *
 * THIS IS NOT A FREE SOFTWARE. PLEASE, VISIT <https://webkadabra.github.io/yii2-podcast-engine/> FOR DETAILS
 */
namespace webkadabra\podcaster\models;
use creocoder\taggable\TaggableQueryBehavior;
use yii\db\Expression;

/**
 * This is the ActiveQuery class for [[PodcastEpisode]].
 *
 * @see PodcastEpisode
 */
class PodcastEpisodeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return PodcastEpisode[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PodcastEpisode|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function availableForPublic() {
        return $this->statusPublic();
    }

    public function published()
    {
        return $this->andWhere(['or',
            ['IS', 'expires_on', (new Expression('Null'))],
            ['<','expires_on',(new Expression('NOW()'))]])
            ->andWhere(['<=', 'posted_on', (new Expression('NOW()'))]);
    }

    public function statusPublic()
    {
        return $this->andWhere(['status' => PodcastEpisode::STATUS_PUBLIC]);
    }

//    public function behaviors()
//    {
//        return [
//            TaggableQueryBehavior::className(),
//        ];
//    }
}
