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
 * This is the model class for table "podcast_file".
 *
 * @property string $id
 * @property string $file_folder
 * @property string $file_name
 * @property int $total_downloads
 */
class PodcastFile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'podcast_file';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['total_downloads'], 'integer'],
            [['file_folder', 'file_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'file_folder' => Yii::t('app', 'Full File Path'),
            'file_name' => Yii::t('app', 'File Name'),
            'total_downloads' => Yii::t('app', 'Total Downloads'),
        ];
    }

    /**
     * @inheritdoc
     * @return PodcastFileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PodcastFileQuery(get_called_class());
    }
}
