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
 * This is the model class for table "podcast_shownote".
 *
 * @property string $id
 * @property int $episode_id
 * @property string $timecode
 * @property string $note
 */
class PodcastShownote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'podcast_shownote';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['episode_id'], 'integer'],
            [['timecode'], 'safe'],
            [['note'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'episode_id' => 'Episode ID',
            'timecode' => 'Timecode',
            'note' => 'Note',
        ];
    }

    public function getEpisode() {
        return $this->hasOne(PodcastEpisode::className(), ['id'=>'episode_id']);
    }
}
