<span class="<?php echo ($debitcredit->post->id == $this->post_id) ? 'highlighted': 'ordinary' ?>">
<?php echo $debitcredit->post->date == '0000-00-00' ? '' : Yii::app()->dateFormatter->formatDateTime($debitcredit->post->date, 'short', null) ?>
</span>
