
_<?php echo Yii::app()->dateFormatter->formatDateTime($journalentry->date, 'short', null)?>_

## <?php echo $journalentry->description . "\n" ?>

<br />

<table class="slideshow journalentry">
  <?php foreach($journalentry->postings as $posting): ?>
  <?php echo $this->renderPartial('_postingrow', array('posting'=>$posting, 'firm'=>$model, 'excluded'=>false)) ?> 
  <?php endforeach ?>
</table>
