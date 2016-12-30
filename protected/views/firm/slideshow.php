<?php
/* @var $this FirmController */

$this->layout='//layouts/remark';
$this->css=$model->css;

$journalentries = $model->getJournalentriesData();

$journalentryform = new JournalentryForm;  // used for transaction analysis

?>
name: inverse
layout: true
class: center, middle, inverse
---
#<?php echo CHtml::encode($model->name) . "\n" ?>

<?php echo CHtml::encode($model->description) . "\n" ?>

.footnote[<?php echo Yii::t('delt', 'See more at [{site}]({url})', array('{site}'=>Yii::app()->name, '{url}'=>$this->createAbsoluteUrl(Yii::app()->params['publicpages'][$this->firm->firmtype].$this->firm->slug))) ?>]

---
layout: false

<?php foreach($journalentries as $journalentry): $journalentryform->firm = $model; $journalentryform->loadFromJournalentry($journalentry); $text=$this->renderPartial('_slideshow_journalentry', array('journalentry'=>$journalentry, 'model'=>$model, 'journalentryform'=>$journalentryform), true) ?>

<?php echo $text ?>

---
layout: false

<?php echo $text ?>

<?php $this->renderPartial('../bookkeeping/_transaction_analysis', array('items'=>$journalentryform->postings, 'class'=>'slideshow analysis')) ?>

---

<?php endforeach ?>

name: inverse
layout: true
class: center, middle, inverse

<?php echo Yii::t('delt', 'Thank you.') ?>

---
