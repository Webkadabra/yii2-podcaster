<?php
/**
 * @var $model \webkadabra\podcaster\models\PodcastEpisode
 */
?>
---
title: <?=$model->title?>

description: <?=$model->description?>

link: <?=$model->link?>

date: <?php echo date(DATE_RFC2822, strtotime($model->pub_date));?>

image: <?php echo $model->getMdImagePath();?>

enclosure:
  url: <?=$model->getDownloadUrl()?>

  file: <?=$model->getMdFilePath()?>

  type: 'audio/mp3'
youtube:
  video: <?=$model->youtube_video_id?>

---

<?=$model->description?>