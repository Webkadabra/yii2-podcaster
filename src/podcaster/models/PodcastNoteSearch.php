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

use yii\base\Model;
use yii\data\ActiveDataProvider;

class PodcastNoteSearch extends PodcastNote
{
    public $searchQuery;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'podcast_id', 'episode_id'], 'integer'],
            [['searchQuery', /*'status', 'fulfillment_status', 'grand_total', */ 'event_date',], 'safe'],
            //[['email', 'username', 'password', 'auth_key', 'access_token', 'logged_in_ip', 'logged_in_at', 'created_ip', 'event_date', 'updated_at', 'banned_at', 'banned_reason', 'profile.full_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Search
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params, $filters = [], $extraSorts = false)
    {
        $orderTable = self::tableName();
        $query = self::find();
        if ($extraSorts) {
//            $query->joinWith('history')
//                ->select(
//                    [self::tableName().'.*','COUNT('.OrderHistory::tableName().'.id) AS countedcomments']
//                )
//                ->groupBy(self::tableName().'.order_id');;
        }
//        $query->joinWith(['profile' => function ($query) use ($profileTable) {
//            $query->from(['profile' => $profileTable]);
//        }]);
        // create data provider
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ["event_date"=>'DESC']],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);
        if ($extraSorts) {
            $dataProvider->sort->attributes['countComments'] = [
                'asc' => ['countedcomments' => SORT_ASC],
                'desc' => ['countedcomments' => SORT_DESC],
            ];
        }
        // enable sorting for the related columns
//        $addSortAttributes = ["profile.full_name"];
//        foreach ($addSortAttributes as $addSortAttribute) {
//            $dataProvider->sort->attributes[$addSortAttribute] = [
//                'asc' => [$addSortAttribute => SORT_ASC],
//                'desc' => [$addSortAttribute => SORT_DESC],
//            ];
//        }
        if (!($this->load($params) && $this->validate())) {
            $FilterStatus = [];
            if (in_array('closed',$filters)) {
                $FilterStatus[] = Order::STATUS_CLOSED;
            }
            if (in_array('open',$filters)) {
                $FilterStatus[] = Order::STATUS_NEW;
                $FilterStatus[] = Order::STATUS_SHIPPING;
            }
            if ($FilterStatus) {
                $query->andWhere(['status' => $FilterStatus]);
            }
            return $dataProvider;
        }
        $query->andFilterWhere([
            "{$orderTable}.id" => $this->id,
            "{$orderTable}.episode_id" => $this->episode_id,
            "{$orderTable}.podcast_id" => $this->podcast_id,
            "{$orderTable}.owner_user_id" => $this->owner_user_id,
            "{$orderTable}.type" => $this->type,
        ]);
//        $query->andFilterCompare('grand_total', $this->grand_total);
        if ($this->event_date) {
            if(strstr($this->event_date, ' -- ')) {
                list($start_date, $end_date) = explode(' -- ', $this->event_date);
                $query->andFilterWhere(['between', 'DATE(event_date)', $start_date, $end_date]);
            } else {
                $query->andFilterWhere(['DATE(event_date)' => date('Y-m-d', strtotime($this->event_date)),]);
            }
        }
        $searchQuery = trim($this->searchQuery);
        if ($searchQuery) {
            if (is_numeric($searchQuery)) {
                $query->andWhere([
                    "{$orderTable}.id" => $searchQuery,
                ]);
                $query->orWhere([
                    "{$orderTable}.note" => $searchQuery,
                ]);
            } else {
                $query->orFilterWhere([
                    "{$orderTable}.note" => $searchQuery,
                ]);
            }
        }
        $query->andFilterWhere(['like', 'note', $this->note]);
        return $dataProvider;
    }
}