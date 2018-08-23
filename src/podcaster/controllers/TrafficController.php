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

use webkadabra\podcaster\models\PodcastEpisode;
use webkadabra\podcaster\models\PodcastStatListen;
use Yii;
use yii\db\Exception;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class TrafficController  extends Controller
{
    const COOKIE_EPISODE_LISTENER = 'epl';
    const COOKIE_PREFIX = 'PVLT_';

    public static $channels = [
        'gama'
    ];

    public function getExpireDate() {
        return strtotime('+1 day');
    }
    public function actionDownload($episodeId, $channel, $client = null, $dest=null, $expire=null, $noStat = false) {
        if (!in_array($channel, self::$channels)) {
            throw new HttpException(404);
        }
        if (!$client) {
            $client = md5(uniqid('', true));
        }
        if (!$expire) {
            $expire = $this->getExpireDate();
        }
        try {
            // try opening connection....
            Yii::$app->db->open();
        } catch (Exception $e) {}
        if (!Yii::$app->db->isActive) {
            return $this->redirect(['/podcaster/traffic/stream',
                'episodeId' => $episodeId,
                'channel' => $channel,
                'client' => $client,
                'dest' => $dest,
                'expire' => $expire,
            ]);
        }
        if (is_numeric($episodeId)) {
            $episode = PodcastEpisode::findOne($episodeId);
        } else {
            $episode = PodcastEpisode::find()->where(['slug' => $episodeId])->one();
        }
        if (!$episode) {
            throw new HttpException(404);
        }


        $cookies = Yii::$app->request->cookies;
        $cookieName = self::COOKIE_PREFIX.self::COOKIE_EPISODE_LISTENER . '_' . $episode->id;
        $didHear = $cookies->getValue($cookieName, false);
        if (!$didHear && !$noStat) {
            // save stats
            $stat = new PodcastStatListen();
            $stat->episode_id = $episode->id;
            $stat->file_url = $episode->file->file_folder;
            $stat->session_client = $client;
            $stat->session_dest = $dest;
            $stat->ip_address = \Yii::$app->request->userIP;
            $stat->referral = \Yii::$app->request->referrer;
            $stat->user_agent = \Yii::$app->request->userAgent;
            $stat->time = new Expression('NOW()');
            $stat->save(false);

            $setCookies = Yii::$app->response->cookies;
            $setCookies->add(new \yii\web\Cookie([
                'name' => $cookieName,
                'value' => true,
                'expire' => $expire - 1,
            ]));
        }

        return $this->redirect(['/podcaster/traffic/stream',
            'episodeId' => $episodeId,
            'channel' => $channel,
            'client' => $client,
            'dest' => $dest,
            'expire' => $expire,
        ]);
    }

    public function actionStream($episodeId, $channel, $client=null, $expire=null, $dest=null) {
        if (!in_array($channel, self::$channels)) {
            throw new HttpException(404, 'www');
        }
        $now = time();
        if ($now >= $expire || (!$client && !$expire)) {
            return $this->redirect(['/podcaster/traffic/download',
                'episodeId' => $episodeId,
                'channel' => $channel,
                'client' => $client,
                'dest' => $dest,
            ]);
        }
        try {
            // try opening connection....
            Yii::$app->db->open();
        } catch (Exception $e) {}
        if (!Yii::$app->db->isActive) { // for rare cases when DB is not working...
            $serverMediaPath = '/var/www/sergiigama/vault/media/';
            $file = $serverMediaPath . $episodeId . '.mp3';
            $log = [
                'date' => date('Y-m-d- H:i:s'),
                'ip' => Yii::$app->request->userIP,
                'user_agent' => \Yii::$app->request->userAgent,
                'episode' => $episodeId,
                'channel' => $channel,
                'client' => $client,
                'dest' => $dest,
            ];
            $logText = '';
            foreach ($log as $cat => $item) {
                if ($item)
                    $logText .= $cat .': '.$item . "\n";
            }
            $logText .= "---------------------------\n\n";
            file_put_contents(Yii::getAlias('@app/runtime/') . 'db_bypass_listens.txt', $logText, FILE_APPEND);
            return self::stream($file, 'audio/mpeg');
        }

        if (is_numeric($episodeId)) {
            $episode = PodcastEpisode::findOne($episodeId);
        } else {
            $episode = PodcastEpisode::find()->where(['slug' => $episodeId])->one();
        }

        if (!$episode) {
            throw new NotFoundHttpException();
        }

//        $file = 'http://sergiigama.com/podcast/media/2017-10-23.mp3';
        $file = $episode->file->file_folder;
        return self::stream($file, 'audio/mpeg');
    }

    public static function stream($file, $content_type = 'application/octet-stream') {
        $isRemote = strstr($file,'://');
        @error_reporting(0);
        // Make sure the files exists, otherwise we are wasting our time
        if (!file_exists($file) && !$isRemote) {
            header("HTTP/1.1 404 Not Found");
            exit;
        }
        // Get file size
        if (false == $isRemote) {
            $filesize = sprintf("%u", filesize($file));
        } else {
            $head = array_change_key_case(get_headers($file, TRUE));
            $filesize = $head['content-length'];
        }
        // Handle 'Range' header
        if(isset($_SERVER['HTTP_RANGE'])){
            $range = $_SERVER['HTTP_RANGE'];
        }elseif($apache = apache_request_headers()){
            $headers = array();
            foreach ($apache as $header => $val){
                $headers[strtolower($header)] = $val;
            }
            if(isset($headers['range'])){
                $range = $headers['range'];
            }
            else $range = FALSE;
        } else $range = FALSE;

        //Is range
        if($range && strstr($range, '=')) {
            $partial = true;
            list($param, $range) = explode('=',$range);
            // Bad request - range unit is not 'bytes'
            if(strtolower(trim($param)) != 'bytes'){
                header("HTTP/1.1 400 Invalid Request");
                exit;
            }
            // Get range values
            $range = explode(',',$range);
            $range = explode('-',$range[0]);
            // Deal with range values
            if ($range[0] === ''){
                $end = $filesize - 1;
                $start = $end - intval($range[0]);
            } else if ($range[1] === '') {
                $start = intval($range[0]);
                $end = $filesize - 1;
            }else{
                // Both numbers present, return specific range
                $start = intval($range[0]);
                $end = intval($range[1]);
                if ($end >= $filesize || (!$start && (!$end || $end == ($filesize - 1)))) $partial = false; // Invalid range/whole file specified, return whole file
            }
            $length = $end - $start + 1;
        }
        // No range requested
        else {
            $partial = false;
            if ($range) {
                Yii::error('Bad partial identifier: '. print_r($range, 1));
            }
        }
        // Send standard headers
        header("Content-Type: $content_type");
        header("Content-Length: $filesize");
        header('Accept-Ranges: bytes');

        // send extra headers for range handling...
        if ($partial) {
            header('HTTP/1.1 206 Partial Content');
            header("Content-Range: bytes $start-$end/$filesize");
            if (!$fp = fopen($file, 'rb')) {
                header("HTTP/1.1 500 Internal Server Error");
                exit;
            }
            if ($start) fseek($fp,$start);
            while($length){
                set_time_limit(0);
                $read = ($length > 8192) ? 8192 : $length;
                $length -= $read;
                print(fread($fp,$read));
            }
            fclose($fp);
        }
        //just send the whole file
        else readfile($file);
        exit;
    }
}