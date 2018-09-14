<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */
namespace webkadabra\podcaster\models;

use Yii;

/**
 * This is the model class for table "podcast_stat_listen".
 *
 * @property string $id
 * @property string $session_client
 * @property int $session_dest
 * @property int $episode_id
 * @property string $time
 * @property string $ip_address
 * @property string $referral
 * @property string $user_agent
 * @property string $file_url
 * @property int $in_stats
 */
class PodcastStatListen extends \yii\db\ActiveRecord
{
    public $total_downalods;
    public $total_downalods2;
    public $date;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'podcast_stat_listen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['session_dest', 'episode_id', 'in_stats'], 'integer'],
            [['time'], 'safe'],
            [['session_client'], 'string', 'max' => 64],
            [['ip_address'], 'string', 'max' => 45],
            [['referral', 'user_agent', 'file_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'session_client' => Yii::t('app', 'Session Client'),
            'session_dest' => Yii::t('app', 'Session Dest'),
            'episode_id' => Yii::t('app', 'Episode ID'),
            'time' => Yii::t('app', 'Time'),
            'ip_address' => Yii::t('app', 'Ip Address'),
            'referral' => Yii::t('app', 'Referral'),
            'user_agent' => Yii::t('app', 'User Agent'),
            'file_url' => Yii::t('app', 'File Url'),
            'in_stats' => Yii::t('app', 'In Stats'),
        ];
    }

    /**
     * @inheritdoc
     * @return PodcastStatListenQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PodcastStatListenQuery(get_called_class());
    }

    public function getEpisode() {
        return $this->hasOne(PodcastEpisode::className(), ['id'=>'episode_id']);
    }

    public static function dateEvents($date) {
        // find podcasts released
        // find notes
        $hasNote = \webkadabra\podcaster\models\PodcastNote::find()->where(['DATE(event_date)' => date('Y-m-d', strtotime($date)),])->count();
        $hasPodcastRelease = \webkadabra\podcaster\models\PodcastEpisode::find()->where(['DATE(pub_date)' => date('Y-m-d', strtotime($date)),])->one();

        $out = [];
        if ($hasNote)
            $out[] = '<i class="fa fa-sticky-note" aria-hidden="true" title="Has Notes"></i>';
        if ($hasPodcastRelease)
            $out[] = '<i class="fa fa-microphone" aria-hidden="true" title="Podcast Release"></i>';

        return implode(' ', $out);
    }
}
