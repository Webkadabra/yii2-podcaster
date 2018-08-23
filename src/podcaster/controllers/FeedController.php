<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 *
 * THIS IS NOT A FREE SOFTWARE. PLEASE, VISIT <https://webkadabra.github.io/yii2-podcast-engine/> FOR DETAILS
 */
namespace webkadabra\podcaster\controllers;

use webkadabra\podcaster\models\Podcast;
use webkadabra\podcaster\models\PodcastEpisode;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\helpers\Html;

class FeedController  extends Controller
{
    public function actionFeed($podcast, $dest = null) {
        return $this->actionBuildFeed($podcast, $dest);
    }

    public function actionBuildFeed($podcast, $dest = null) {
        $rss = self::buildFeed($podcast, $dest);

        if ($dest) {
            $name = 'feed-'.substr(md5($dest), -5).'.xml';
        } else
            $name = 'feed.xml';

        return $rss;
        file_put_contents(Yii::getAlias('@webroot/podcast/'.$name), $rss);

//        return $this->redirect('/podcast/feed.xml');
        return $this->redirect('/podcast/' . $name);
    }

    protected static function buildFeed($podcast, $dest) {
        $query = PodcastEpisode::find()->availableForPublic();
        if (is_numeric($podcast)) {
            $podcastRecord = Podcast::find()->where(['id' => $podcast])->one();
            if (!$podcastRecord) {
                throw new NotFoundHttpException();
            }
            $query->andWhere(['podcast_id' => $podcast]);
        } else {
            $podcastRecord = Podcast::find()->where(['alias' => $podcast])->one();
            if (!$podcastRecord) {
                throw new NotFoundHttpException();
            }
            $query->andWhere(['podcast_id' => $podcastRecord->id]);
        }

        $query->orderBy('pub_date DESC');

        $dataProvider = new ActiveDataProvider([
            'query' =>$query,
            'pagination' => false
        ]);
        $response = Yii::$app->getResponse();
        $response->format = yii\web\Response::FORMAT_RAW;
        $headers = $response->getHeaders();
        $headers->set('Content-Type', 'image/svg+xml; charset=utf-8');

        $podcast = Podcast::defaultPodcast();
        $rss = \Zelenin\yii\extensions\Rss\RssView::widget([
            'dataProvider' => $dataProvider,
            'channel' => [
                'itunes:explicit' => 'Yes',
                'itunes:author' => $podcast->author_name,
                'itunes:owner' => function($widget, \Zelenin\Feed $feed) use ($podcast){
                    $feed->addChannelElementWithSub('itunes:owner', [
                        'itunes:name' => $podcast->author_name,
                        'itunes:email' => $podcast->author_email,
                    ]);

                },
                'itunes:category' => function($widget, \Zelenin\Feed $feed) use ($podcast){
                    $feed->addChannelElement('itunes:category', '', ['text'=>"Comedy"]);
                },
                'attributes' => function ($widget, \Zelenin\Feed $feed) {
                    $feed->firstChild->setAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');
                    $feed->firstChild->setAttribute('xmlns:content', 'http://purl.org/rss/1.0/modules/content/');
                    $feed->firstChild->setAttribute('xmlns:itunes', 'http://www.itunes.com/dtds/podcast-1.0.dtd');
                    $feed->firstChild->setAttribute('xmlns:image', 'http://purl.org/rss/1.0/modules/image/');
                    $feed->firstChild->setAttribute('xmlns:rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');

                },
                'title' => function ($widget, \Zelenin\Feed $feed) use ($podcast) {
                    $feed->addChannelTitle($podcast->title);
                },
                'link' => $podcastRecord->getRssFeedUrl(),
                'description' => $podcast->description,
                'language' => function ($widget, \Zelenin\Feed $feed) use ($podcast) {
                    foreach ($feed->getElementsByTagName('link') as $el ) {
                        /** @var $el \DOMElement */
                        $el->nodeValue = $podcast->website;
                    }
                    return Yii::$app->language;
                },
            ],
            'items' => [
                'title' => function ($model, $widget, \Zelenin\Feed $feed) {
                    return $model->title;
                },
                'description' => function (\webkadabra\podcaster\models\PodcastEpisode $model, $widget, \Zelenin\Feed $feed) use ($podcast) {

                    $base = Html::img($model->getArtworkUrl())
                        . Html::tag('p', $model->description)
                        .  ($model->rss_description_append
                            ? $model->rss_description_append
                            : $podcast->rss_description_append)
                    ;
                    $out = \yii\helpers\HtmlPurifier::process($base, [
                        'AutoFormat.Linkify' => true,
                        'AutoFormat.AutoParagraph' => true,
                        'AutoFormat.RemoveEmpty' => true,
                    ]);

                    $item = $feed->getElementsByTagName('item')
                        ->item($feed->getElementsByTagName('item')->length - 1);

                    $contentTag  = $feed->createElement("description",  '');
                    $contentTag->appendChild($feed->createCDATASection($out));
                    $item->appendChild($contentTag);

                    if ($model->getArtworkUrl()) {
                        $imgTag = $feed->createElement("image:item",  '');
                        $imgTag->setAttribute('rdf:about', $model->getArtworkUrl());
                        $imgTag->setAttribute('href', $model->getArtworkUrl());
                        $imgTag->setAttribute('url', $model->getArtworkUrl());
                        $imgTag->setAttribute('rdf:about', $model->getArtworkUrl());
                        $imgTag->setAttribute('href', $model->getArtworkUrl());
                        $imgTag->setAttribute('url', $model->getArtworkUrl());
                        $imgTag->appendChild($feed->createElement("image:width",  854));
                        $imgTag->appendChild($feed->createElement("image:height",  400));
                        $item->appendChild($imgTag);
                    }
                },
                'link' => function ($model, $widget, \Zelenin\Feed $feed) {
                    $feed->addItemGuid($model->getFeedPermalink(), true);
                    return $model->getFeedPermalink();
                },
                'enclosure' => function (\webkadabra\podcaster\models\PodcastEpisode $model, $widget, \Zelenin\Feed $feed) {
                    $feed->addItemEnclosure($model->getDownloadUrl(), null, 'audio/mp3');
                    $feed->addItemElement('itunes:image', '', ['url' => $model->getArtworkUrl()]);
                },
                'dc:creator' => function ($model, $widget, \Zelenin\Feed $feed) use ($podcast) {
                    return $podcast->author_name;
                },
                'pubDate' => function ($model, $widget, \Zelenin\Feed $feed) {
                    if (!$model->pub_date) return;
                    $date = \DateTime::createFromFormat('Y-m-d H:i:s', $model->pub_date);
                    return $date->format(DATE_RSS);
                }
            ]
        ]);
        return $rss;
    }
    public static function ranger($url){
        $headers = array(
            "Range: bytes=0-32768"
        );

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
    }
}