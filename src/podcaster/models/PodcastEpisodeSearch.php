<?php
/**
 * Podcast Engine, <https://webkadabra.github.io/yii2-podcast-engine/>
 *
 * Copyright (C) 2017-present Sergii Webkadabra <devkadabra@gmail.com>
 * All rights reserved.
 */
namespace webkadabra\podcaster\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class PodcastEpisodeSearch extends PodcastEpisode
{
    public $searchQuery;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'number'], 'integer'],
            [['searchQuery', /*'status', 'fulfillment_status', 'grand_total', */ 'pub_date',], 'safe'],
            //[['email', 'username', 'password', 'auth_key', 'access_token', 'logged_in_ip', 'logged_in_at', 'created_ip', 'pub_date', 'updated_at', 'banned_at', 'banned_reason', 'profile.full_name'], 'safe'],
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

        /** @var \amnah\yii2\user\models\User $user */
        /** @var \amnah\yii2\user\models\Profile $profile */
        // get models
//        $user = $this->module->model("User");
//        $profile = $this->module->model("Profile");
        $orderTable = self::tableName();
//        $profileTable = $profile::tableName();
        // set up query relation for `user`.`profile`
        // http://www.yiiframework.com/doc-2.0/guide-output-data-widgets.html#working-with-model-relations
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
            'sort'=> ['defaultOrder' => ["pub_date"=>'DESC']],
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
//            "{$orderTable}.fulfillment_status" => $this->fulfillment_status,
//            "{$orderTable}.payment_status" => $this->payment_status,
//            "{$orderTable}.customer_id" => $this->customer_id,
//            "{$orderTable}.status" => $this->status,
        ]);
//        $query->andFilterCompare('grand_total', $this->grand_total);
        if ($this->pub_date) {
            if(strstr($this->pub_date, ' -- ')) {
                list($start_date, $end_date) = explode(' -- ', $this->pub_date);
                $query->andFilterWhere(['between', 'DATE(pub_date)', $start_date, $end_date]);
            } else {
                $query->andFilterWhere(['DATE(pub_date)' => date('Y-m-d', strtotime($this->pub_date)),]);
            }
        }
        $searchQuery = trim($this->searchQuery);
        if ($searchQuery) {
            if (is_numeric($searchQuery)) {
                $query->andWhere([
                    "{$orderTable}.id" => $searchQuery,
                ]);
                $query->orWhere([
                    "{$orderTable}.number" => $searchQuery,
                ]);
                $query->orWhere([
                    "{$orderTable}.title" => $searchQuery,
                ]);
//                $query->orFilterWhere([
//                    "{$orderTable}.grand_total" => $searchQuery,
//                ]);

            } else {
                $query->orFilterWhere([
                    "{$orderTable}.title" => $searchQuery,
                ]);
            }
        }
//        $query->andFilterWhere(['like', 'email', $this->email])
//            ->andFilterWhere(['like', 'username', $this->username])
//            ->andFilterWhere(['like', 'password', $this->password])
//            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
//            ->andFilterWhere(['like', 'access_token', $this->access_token])
//            ->andFilterWhere(['like', 'logged_in_ip', $this->logged_in_ip])
//            ->andFilterWhere(['like', 'created_ip', $this->created_ip])
//            ->andFilterWhere(['like', 'banned_reason', $this->banned_reason])
//            ->andFilterWhere(['like', 'logged_in_at', $this->logged_in_at])
//            ->andFilterWhere(['like', "{$userTable}.pub_date", $this->pub_date])
//            ->andFilterWhere(['like', "{$userTable}.updated_at", $this->updated_at])
//            ->andFilterWhere(['like', 'banned_at', $this->banned_at])
//            ->andFilterWhere(['like', "profile.full_name", $this->getAttribute('profile.full_name')]);
        return $dataProvider;
    }
}