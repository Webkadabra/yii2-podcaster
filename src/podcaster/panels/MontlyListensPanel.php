<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Gamaiunov <devkadabra@gmail.com>
 * All rights reserved.
 *
 * THIS IS NOT A FREE SOFTWARE. PLEASE, VISIT <https://webkadabra.github.io/yii2-podcast-engine/> FOR DETAILS
 */
namespace webkadabra\podcaster\panels;
use yii\data\SqlDataProvider;


class MontlyListensPanel
{
    public function getDataProvider() {
        return self::dataProvider();
    }
    public static function dataProvider($order='ASC') {
        $cutoff = date('Y-m-d', mktime(0,0,0,1,1, (date('Y') - 2)));
        $dataProvider = new SqlDataProvider(['sql' => "SELECT 
DATE_FORMAT(`time`, \"%Y-%m\") date,

count(`podcast_stat_listen`.id)  total_downalods
FROM  
podcast_stat_listen
join `podcast_episode` on podcast_stat_listen.episode_id = `podcast_episode`.id
/*WHERE 
  draft_yn = 0 AND  (payment_status = 'paid' OR payment_status = 'partially_paid')
   AND `order`.deleted_yn = 0
   AND (created_at >= :cutoff )
  
  
[ AND customer.id in ( {customer} ) ]
[ AND  DATE(created_at)  {daterange} ]*/
GROUP BY date 
order by date $order",
            'params' => [
                'cutoff' => $cutoff
            ]]);
        $dataProvider->pagination = false;
        return $dataProvider;
    }
}