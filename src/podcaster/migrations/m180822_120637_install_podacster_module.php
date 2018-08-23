<?php

use yii\db\Migration;

/**
 * Class m180822_120637_install_podacster_module
 */
class m180822_120637_install_podacster_module extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("SET FOREIGN_KEY_CHECKS=0;
        
        DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `frequency` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Tag_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=112 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `podcast`;
CREATE TABLE `podcast` (
  `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(30) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `language` varchar(20) DEFAULT NULL,
  `author_name` varchar(255) DEFAULT NULL,
  `author_email` varchar(255) DEFAULT NULL,
  `default_artwork_url` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `defaultFeedUrl` varchar(255) DEFAULT NULL,
  `itunes_category` varchar(255) DEFAULT NULL,
  `itunes_artwork_url` varchar(255) DEFAULT NULL,
  `itunes_explicit_yn` tinyint(1) unsigned DEFAULT '0',
  `marketing_tweet` varchar(255) DEFAULT NULL,
  `marketing_hashtag` varchar(255) DEFAULT NULL,
  `published_yn` tinyint(1) unsigned DEFAULT '0',
  `rss_description_append` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `podcast_episode`;
CREATE TABLE `podcast_episode` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_id` int(11) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `pub_date` datetime DEFAULT NULL,
  `status` enum('draft','pending','public','deleted') DEFAULT 'draft',
  `custom_artwork_url` varchar(255) DEFAULT NULL,
  `rss_description_append` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `length_bytes` int(11) DEFAULT NULL,
  `raw_tags` varchar(255) DEFAULT NULL,
  `youtube_video_id` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10074 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `podcast_episode_tag`;
CREATE TABLE `podcast_episode_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `episode_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`episode_id`,`tag_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1871 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `podcast_file`;
CREATE TABLE `podcast_file` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_folder` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `total_downloads` mediumint(9) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1072 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `podcast_note`;
CREATE TABLE `podcast_note` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `podcast_id` int(10) unsigned DEFAULT NULL,
  `episode_id` int(11) unsigned DEFAULT NULL,
  `owner_user_id` int(11) unsigned DEFAULT NULL,
  `note` text,
  `type` enum('comment','system','event','auto') DEFAULT 'comment',
  `event_date` datetime DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) unsigned DEFAULT '0',
  `deleted_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `podcast_shownote`;
CREATE TABLE `podcast_shownote` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `episode_id` int(11) DEFAULT NULL,
  `timecode` decimal(12,6) DEFAULT NULL,
  `note` text,
  `owner_user_id` int(10) unsigned DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_user_id` int(10) unsigned DEFAULT NULL,
  `deleted_yn` tinyint(3) unsigned DEFAULT '0',
  `deleted_on` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `podcast_stat_listen`;
CREATE TABLE `podcast_stat_listen` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_client` varchar(64) DEFAULT NULL,
  `session_dest` mediumint(9) DEFAULT NULL,
  `episode_id` int(11) DEFAULT NULL,
  `time` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `ip_address` varchar(45) DEFAULT NULL,
  `referral` varchar(255) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `file_url` varchar(255) DEFAULT NULL,
  `in_stats` tinyint(1) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=659 DEFAULT CHARSET=utf8;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180822_120637_install_podacster_module cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180822_120637_install_podacster_module cannot be reverted.\n";

        return false;
    }
    */
}
