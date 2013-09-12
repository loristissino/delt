<span class="<?php echo ($posting->journalentry->id == $this->journalentry_id) ? 'highlighted': 'ordinary' ?>">
<?php echo $posting->journalentry->date == '0000-00-00' ? '' : Yii::app()->dateFormatter->formatDateTime($posting->journalentry->date, 'short', null) ?>
</span>
