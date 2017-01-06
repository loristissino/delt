<?php
/* @var $this TransactionController */
/* @var $model Transaction */

$this->breadcrumbs=array(
  'Exercises'=>array('exercise/index'),
  $this->exercise->title=>array('exercise/view', 'id'=>$this->exercise->id),
  'New Transaction',
);

$this->menu=array(
	array('label'=>'View Exercise', 'url'=>array('exercise/view', 'id'=>$this->exercise->id)),
);
?>

<h1><?php echo Yii::t('delt', 'New Transaction') ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
