<?php
/* @var $this FirmController */

$this->layout='//layouts/remark';

$journalentries = $model->getJournalentriesData();

?>
name: inverse
layout: true
class: center, middle, inverse
---
#<?php echo CHtml::encode($model->name) . "\n" ?>

<?php echo CHtml::encode($model->description) . "\n" ?>

.footnote[<?php echo Yii::t('delt', 'See more at [%site%](%url%)', array('%site%'=>Yii::app()->name, '%url%'=>$this->createAbsoluteUrl(Yii::app()->params['publicpages'][$this->firm->firmtype].$this->firm->slug))) ?>]

---
layout: false

<?php foreach($journalentries as $journalentry): ?>
_<?php echo Yii::app()->dateFormatter->formatDateTime($journalentry->date, 'short', null)?>_

## <?php echo $journalentry->description . "\n" ?>

<br />

<table class="slideshow journalentry">
  <?php foreach($journalentry->postings as $posting): ?>
  <?php echo $this->renderPartial('_postingrow', array('posting'=>$posting, 'firm'=>$model, 'excluded'=>false)) ?> 
  <?php endforeach ?>
</table>

---

<?php endforeach ?>

name: inverse
layout: true
class: center, middle, inverse

<?php echo Yii::t('delt', 'Thank you.') ?>

---
