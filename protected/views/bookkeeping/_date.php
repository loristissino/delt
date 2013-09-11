<span class="<?php echo ($posting->post->id == $this->post_id) ? 'highlighted': 'ordinary' ?>">
<?php echo $posting->post->date == '0000-00-00' ? '' : Yii::app()->dateFormatter->formatDateTime($posting->post->date, 'short', null) ?>
</span>
