<?php
/* @var $this FirmController */
/* @var $model Firm */

$this->breadcrumbs=array(
	'Firms'=>array('index'),
	'Fork',
);

?>

<h1><?php echo Yii::t('delt', 'Fork an existing firm') ?></h1>

<ul>
<?php foreach($firms as $firm): ?>
  <li><?php echo CHtml::link($firm, $this->createUrl('firm/fork', array('slug'=>$firm->slug)), array('title'=>Yii::t('delt', 'Fork this firm'))) ?></li>
<?php endforeach ?>
</ul>
