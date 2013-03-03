<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
);
?>
<h1><?php echo Yii::t('delt', 'Bookkeeping and accountancy') ?></h1>

<?php if(sizeof($firms)): ?>
<?php foreach($firms as $firm): ?>
  <p><?php echo CHtml::link($firm->name, CHtml::normalizeUrl(array('bookkeeping/manage', 'slug'=>$firm->slug))) ?></p>
<?php endforeach ?>
<?php else: ?>
  <p><?php echo Yii::t('You have no firms') ?></p>
<?php endif ?>

