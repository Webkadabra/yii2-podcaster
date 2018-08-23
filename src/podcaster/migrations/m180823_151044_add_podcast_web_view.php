<?php

use yii\db\Migration;

/**
 * Class m180823_151044_add_podcast_web_view
 */
class m180823_151044_add_podcast_web_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(\webkadabra\podcaster\models\Podcast::tableName(), 'web_list_yn', $this->boolean()->unsigned()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180823_151044_add_podcast_web_view cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180823_151044_add_podcast_web_view cannot be reverted.\n";

        return false;
    }
    */
}
