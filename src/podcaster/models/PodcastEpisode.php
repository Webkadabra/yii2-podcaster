<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */
namespace webkadabra\podcaster\models;

use creocoder\taggable\TaggableBehavior;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/**
 * This is the model class for table "podcast_episode".
 *
 * @property string $id
 * @property int $podcast_id
 * @property int $slug
 * @property int $file_id
 * @property int $number
 * @property string $title
 * @property string $description
 * @property string $pub_date
 * @property string $status
 * @property string $custom_artwork_url
 * @property string $rss_description_append
 * @property string $link
 * @property string $length_bytes
 * @property string $raw_tags
 *
 * @property Podcast $podcast
 */
class PodcastEpisode extends \yii\db\ActiveRecord
{
    const STATUS_PUBLIC = 'public';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'podcast_episode';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number', 'podcast_id'], 'integer'],
            [['podcast_id'], 'required'],
            [['title', 'slug', 'custom_artwork_url', 'rss_description_append', 'link', 'raw_tags', 'link',],
             'string', 'max' => 255],
            [['pub_date','description','youtube_video_id'], 'safe',],
            ['tagValues', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'file_id' => Yii::t('app', 'File ID'),
            'number' => Yii::t('app', 'Number'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @inheritdoc
     * @return PodcastEpisodeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PodcastEpisodeQuery(get_called_class());
    }

    public function getFile() {
        return $this->hasOne(PodcastFile::className(), ['id'=>'file_id']);
    }

    public function getChannelSlug() {
        return 'gama';
    }

    public function getDownloadUrl($dest = null) {
        return Yii::$app->urlManager->createAbsoluteUrl(['/podcaster/traffic/download',
            'episodeId' => ($this->slug ? $this->slug : $this->id),
            'channel' => $this->channelSlug,
            'dest' => $dest,
        ]);
    }

    public function getBackendDownloadUrl() {
        return Yii::$app->urlManager->createAbsoluteUrl(['/podcaster/traffic/download',
            'episodeId' => ($this->slug ? $this->slug : $this->id),
            'channel' => $this->channelSlug,
            'noStat' => true,
        ]);
    }
    public function getMdFilePath() {
        return 'podcast/media/'.$this->slug.'.mp3';
    }

    public function getMdImagePath() {
        return '/' . str_replace(['https://sergiigama.com/','http://sergiigama.com/'], '', $this->custom_artwork_url);
    }

    /**
     * @deprecated
     */
    public function getFilePath() {
        return '/' . str_replace(Yii::$app->urlManager->createAbsoluteUrl('/', true), '', Yii::$app->urlManager->createAbsoluteUrl(['/podcaster/traffic/download',
            'episodeId' => ($this->slug ? $this->slug : $this->id),
            'channel' => $this->channelSlug,
        ]));
    }

    public function getCountUniqueDownloads()
    {
        // Customer has_many Order via Order.customer_id -> id
        return $this->hasMany(PodcastStatListen::className(), ['episode_id' => 'id'])
            ->select([
                'DATE_FORMAT(`time`, "%Y-%m-%d") `date`',])
            ->groupBy(['ip_address', 'date'])->count();
    }

    public function getDisplayTitle() {
        if ($this->title)
            return $this->title;
        if ($this->number)
            return '#'.$this->number . ' - ' . $this->title;
        if ($this->slug)
            return $this->slug;
        return $this->id;
    }

    public function getShownotesReversed() {
        return $this->hasMany(PodcastShownote::className(), ['episode_id'=>'id'])->orderBy('timecode DESC');
    }

    /**
     * @return ActiveDataProvider
     */
    public function reportDaily() {
        $query = PodcastStatListen::find();


        $query->andFilterWhere(['episode_id' => $this->id]);

//        $query->select([
//            PodcastStatListen::tableName().'.episode_id',
//            PodcastStatListen::tableName().'.ip_address',
//            'COUNT(DISTINCT '.PodcastStatListen::tableName().'.ip_address) AS total_sales'
//        ]);
//        $query->groupBy([
//            PodcastStatListen::tableName().'.episode_id',
//            PodcastStatListen::tableName().'.ip_address',
//        ]);

        $query->select([
            'episode_id',
            'DATE_FORMAT(`time`, "%Y-%m-%d") `date`',
            'COUNT(DISTINCT '.PodcastStatListen::tableName().'.ip_address) AS total_sales'
        ]);
        $query->groupBy([
//            PodcastStatListen::tableName().'.episode_id',
//            PodcastStatListen::tableName().'.ip_address',
            'date',
        ]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            // 'sort'=> ['defaultOrder' => ["id"=>'DESC']],
            'pagination' => false,
        ]);
        return $dataProvider;
    }

    /**
     * @return Podcast
     */
    public function getPodcast() {
        return $this->hasOne(Podcast::class, ['id' => 'podcast_id']);
    }

    public function applyDefaults() {
        $podcast = $this->podcast;

        $date = strtotime('last Monday');

        $this->title = $podcast->title.', '.date('d-m-Y', $date);
        $this->slug = date('Y-m-d', $date);
        $this->pub_date = date('Y-m-d 09:00:00', $date);

        $this->link = $podcast->website . (substr($podcast->website, -1) !== '/'
                ? '/' : '') . 'show/' . $this->slug;
    }

    public function getIsNewInSeries() {
        $newest = PodcastEpisode::find()->where(['status' => 'public'])->orderBy('id DESC')->one();
        return $newest && $newest->id == $this->id;
    }

    public function behaviors()
    {
        return [
            'taggable' => [
                'class' => TaggableBehavior::className(),
                // 'tagValuesAsArray' => false,
                // 'tagRelation' => 'tags',
                // 'tagValueAttribute' => 'name',
                // 'tagFrequencyAttribute' => 'frequency',
            ],
        ];
    }

    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('{{%podcast_episode_tag}}', ['episode_id' => 'id']);
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public function getEpisodeControls() {
        $items = [];
        $items[] = [
            'label' => 'Get *.MD',
            'url' => \yii\helpers\Url::toRoute(['/'.Yii::$app->controller->module->id.'/episode/summary-md','id' => $this->id]),
        ];

        return [
            'label' => Yii::t('app','More'),
            'encodeLabel' => false,
            'dropdown' => ['encodeLabels' => false,
                           'items' => $items,
            ],
        ];
    }

    public function getFeedPermalink()
    {
        if ($this->link) return $this->link;
        return $this->getDownloadUrl();
    }

    public function getArtworkUrl()
    {
        return ($this->custom_artwork_url
            ? $this->custom_artwork_url
            : $this->getPodcast()->default_artwork_url
        );
    }
}
