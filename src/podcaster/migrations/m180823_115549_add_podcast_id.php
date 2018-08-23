<?php

use yii\db\Migration;

/**
 * Class m180823_115549_add_podcast_id
 */
class m180823_115549_add_podcast_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(\webkadabra\podcaster\models\PodcastEpisode::tableName(), 'podcast_id', $this->integer()->unsigned()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180823_115549_add_podcast_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180823_115549_add_podcast_id cannot be reverted.\n";

        return false;
    }
    */
}
