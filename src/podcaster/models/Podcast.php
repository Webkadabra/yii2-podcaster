<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */
namespace webkadabra\podcaster\models;

use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "podcast".
 *
 * @property string $id
 * @property string $alias
 * @property string $title
 * @property string $description
 * @property string $language
 * @property string $author_name
 * @property string $author_email
 * @property string $default_artwork_url
 * @property string $website
 * @property string $defaultFeedUrl
 * @property string $itunes_category
 * @property string $itunes_artwork_url
 * @property int $itunes_explicit_yn
 * @property string $marketing_tweet
 * @property string $marketing_hashtag
 * @property string $rss_description_append
 * @property int $published_yn
 * @property int $web_list_yn
 * @property PodcastEpisode $episodes
 */
class Podcast extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'podcast';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['description', 'rss_description_append'], 'string'],
            [['itunes_explicit_yn', 'published_yn', 'web_list_yn'], 'integer'],
            [['alias'], 'string', 'max' => 30],
            [['title', 'author_name', 'author_email', 'default_artwork_url', 'website', 'defaultFeedUrl', 'itunes_category', 'itunes_artwork_url', 'marketing_tweet', 'marketing_hashtag'], 'string', 'max' => 255],
            [['language'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'alias' => Yii::t('app', 'Alias'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'language' => Yii::t('app', 'Language'),
            'author_name' => Yii::t('app', 'Author Name'),
            'author_email' => Yii::t('app', 'Author Email'),
            'default_artwork_url' => Yii::t('app', 'Default Artwork Url'),
            'website' => Yii::t('app', 'Website'),
            'defaultFeedUrl' => Yii::t('app', 'Default Feed Url'),
            'itunes_category' => Yii::t('app', 'Itunes Category'),
            'itunes_artwork_url' => Yii::t('app', 'Itunes Artwork Url'),
            'itunes_explicit_yn' => Yii::t('app', 'Itunes Explicit Yn'),
            'marketing_tweet' => Yii::t('app', 'Marketing Tweet'),
            'marketing_hashtag' => Yii::t('app', 'Marketing Hashtag'),
            'published_yn' => Yii::t('app', 'Published Yn'),
            'web_list_yn' => Yii::t('app', 'Show on website'),
        ];
    }

    /**
     * уникальное прослушивание ПОДКАСТА - считается уникальным прослушиванием в СУТКИ с одного АЙПИ
     * это НЕ УНИКАЛЬНЫЕ СЛУШАТЕЛИ, а  ПРОСЛУШИВАНИЯ (impressions)
     * @return int|string
     */
    public function getCountUniqueDownloads()
    {
        return PodcastStatListen::find()->select([
                'DATE_FORMAT(`time`, "%Y-%m-%d") `date`',])
            ->groupBy(['episode_id', 'ip_address', 'date'])->count();
    }

    public static function availableOptions($idAttribute = 'id', $nameAttribute = 'title')
    {
        $data = self::find()->where(['published_yn' => 1])->orderBy($nameAttribute . ' ASC')->all();

        return ArrayHelper::map($data, $idAttribute, $nameAttribute);
    }

    /**
     * @return Podcast
     */
    public function getEpisodes() {
        return $this->hasMany(PodcastEpisode::class, ['podcast_id' => 'id']);
    }

    /**
     * @return Podcast
     * @throws Exception
     */
    public static function defaultPodcast() {
        $model = Podcast::find()->one();
        if(Podcast::find()->count() > 1) {
            throw new Exception('Multiple podcasts are not supported: please, delete duplicate podcasts');
        }
        return $model;
    }

    public function getRssFeedUrl() {
        if ($this->defaultFeedUrl) {
            return $this->defaultFeedUrl; // e.g. if you have a feed on a Feedburner
        } else {
            return \yii\helpers\Url::to(['/podcaster/feed/feed', 'podcast' => ($this->alias ? $this->alias : $this->id)], true);
        }
    }
}
