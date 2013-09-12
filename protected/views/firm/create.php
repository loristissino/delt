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
  <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php endif ?>

