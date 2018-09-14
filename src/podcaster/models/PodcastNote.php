<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */
namespace webkadabra\podcaster\models;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * This is the model class for table "podcast_note".
 *
 * @property string $id
 * @property string $podcast_id
 * @property string $episode_id
 * @property string $owner_user_id
 * @property string $note
 * @property string $type
 * @property string $event_date
 * @property string $created_on
 * @property string $updated_on
 * @property int $deleted
 * @property string $deleted_on
 */
class PodcastNote extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'podcast_note';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['podcast_id', 'episode_id', 'owner_user_id', 'deleted'], 'integer'],
            [['note', 'type'], 'string'],
            [['note'], 'required'],
            [['event_date', 'created_on', 'updated_on', 'deleted_on'], 'safe'],
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
            'episode_id' => Yii::t('app', 'Episode ID'),
            'owner_user_id' => Yii::t('app', 'Owner User ID'),
            'note' => Yii::t('app', 'Note'),
            'type' => Yii::t('app', 'Type'),
            'event_date' => Yii::t('app', 'Event Date'),
            'created_on' => Yii::t('app', 'Created On'),
            'updated_on' => Yii::t('app', 'Updated On'),
            'deleted' => Yii::t('app', 'Deleted'),
            'deleted_on' => Yii::t('app', 'Deleted On'),
        ];
    }

    public function getEpisode() {
        return $this->hasOne(PodcastEpisode::className(), ['id'=>'episode_id']);
    }
    public function getPodcast() {
        return $this->hasOne(Podcast::className(), ['id'=>'podcast_id']);
    }

    /**
     * @inheritdoc
     * @return ActiveQuery the newly created [[ActiveQuery]] instance.
     */
    public static function find()
    {
        return parent::find()->where(['deleted' => 0]);
    }

    public static function defaultGridColumns($skip = []) {
        $columns = [
            [
                'attribute' =>  'podcast_id',
                'visible' => false,
                'format' =>'raw',
                'value' =>  function ($model, $key, $index, $column) {
                    if ($model->podcast)
                        return Html::a($model->podcast->title, \yii\helpers\Url::toRoute(['podcast/view', 'id' => $model->podcast_id]), ['data-pjax' => 'false',]);
                }
            ],
            'event_date' => ['attribute' => 'event_date'],
            [
                'attribute' =>  'episode_id',
                'format' =>'raw',
                'value' =>  function ($model, $key, $index, $column) {
                    if ($model->episode)
                        return Html::a($model->episode->displayTitle, \yii\helpers\Url::toRoute(['episode/view', 'id' => $model->episode_id]), ['data-pjax' => 'false',]);
                }
            ],
            'type' => ['attribute' => 'type'],
            'note' => ['attribute' => 'note'],
            [
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a('Details', ['view', 'id' => $model->id], ['class' => 'btn btn-default', 'data-pjax' => 'false'])
                        ;
                }
            ]
        ];
        if ($skip) foreach ($skip as $key) unset($columns[$key]);
        return $columns;
    }
}
