<?php
/* @var $this FirmController */
/* @var $model Firm */

$this->breadcrumbs=array(
	'Firms'=>array('index'),
	'Fork',
);

?>

<h1><?php echo Yii::t('delt', 'Fork an existing firm') ?></h1>

<h2><?php echo Yii::t('delt', 'Public firms') ?></h2>
<ul>
<?php foreach($publicfirms as $firm): ?>
  <li><?php echo CHtml::link($firm, $this->createUrl('firm/fork', array('slug'=>$firm->slug)), array('title'=>Yii::t('delt', 'Fork this firm'))) ?></li>
<?php endforeach ?>
</ul>

<h2><?php echo Yii::t('delt', 'Your firms') ?></h2>
<ul>
<?php foreach($ownfirms as $firm): ?>
  <li><?php echo CHtml::link($firm, $this->createUrl('firm/fork', array('slug'=>$firm->slug)), array('title'=>Yii::t('delt', 'Fork this firm'))) ?></li>
<?php endforeach ?>
</ul>
