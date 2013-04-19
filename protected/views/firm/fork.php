<?php
/* @var $this FirmController */
/* @var $model Firm */

$this->breadcrumbs=array(
	'Firms'=>array('index'),
	'Fork',
);

$available_firms = $this->DEUser->profile->allowed_firms - sizeof($this->DEUser->firms);

?>

<h1><?php echo Yii::t('delt', 'Fork an existing firm') ?></h1>

<?php if($available_firms <= 0): ?>
  <?php echo $this->renderPartial('/firm/_available') ?>
<?php else: ?>
  <h2><?php echo Yii::t('delt', 'Public firms') ?></h2>
  <ul>
  <?php foreach($publicfirms as $firm): ?>
    <li><?php echo CHtml::link($firm, $this->createUrl('firm/fork', array('slug'=>$firm->slug)), array('title'=>Yii::t('delt', 'Fork the firm «{firm}»', array('{firm}'=>$firm->name)))) ?></li>
  <?php endforeach ?>
  </ul>

  <?php if(sizeof($ownfirms)): ?>
  <h2><?php echo Yii::t('delt', 'Your firms') ?></h2>
  <ul>
  <?php foreach($ownfirms as $firm): ?>
    <li><?php echo CHtml::link($firm, $this->createUrl('firm/fork', array('slug'=>$firm->slug)), array('title'=>Yii::t('delt', 'Fork this firm'))) ?></li>
  <?php endforeach ?>
  </ul>
  <?php endif ?>
<?php endif ?>

