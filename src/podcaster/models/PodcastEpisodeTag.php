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

use Yii;

/**
 * This is the model class for table "podcast_episode_tag".
 *
 * @property string $id
 * @property string $podcast_id
 * @property string $tag_id
 */
class PodcastEpisodeTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'podcast_episode_tag';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['podcast_id', 'tag_id'], 'required'],
            [['podcast_id', 'tag_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'podcast_id' => Yii::t('app', 'Podcast ID'),
            'tag_id' => Yii::t('app', 'Tag ID'),
        ];
    }
}
