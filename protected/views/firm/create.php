<?php
/* @var $this FirmController */
/* @var $model Firm */

$this->breadcrumbs=array(
  'Firms'=>array('index'),
  'Create',
);

$this->menu=array();

$available_firms = $this->DEUser->profile->allowed_firms - sizeof($this->DEUser->firms);

?>

<h1><?php echo Yii::t('delt', 'Create a Firm') ?></h1>

<?php if($available_firms <= 0): ?>
  <?php echo $this->renderPartial('/firm/_available') ?>
<?php else: ?>

  <p class="note"><?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/exclamation.png') ?> <?php echo Yii::t('delt', 'Please note that when you create a firm from scratch, it will have an empty chart of accounts, and no configuration at all.') ?> <?php echo Yii::t('delt', 'You might prefer to start by {forking} an existing firm (have a look at the standard ones provided).', array('{forking}'=>CHtml::link(Yii::t('delt', 'forking (duplicating)'), array('/firm/fork')))) ?></p>

  <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php endif ?>

