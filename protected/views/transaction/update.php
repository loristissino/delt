<?php
/* @var $this TransactionController */
/* @var $model Transaction */

$this->breadcrumbs=array(
  'Exercises'=>array('exercise/index'),
  $this->exercise->title=>array('exercise/view', 'id'=>$this->exercise->id),
  'Transactions',
  $model->event_date,
  'Edit'
);

$this->menu=array(
);
?>

<h1><?php echo Yii::t('delt', 'Edit Transaction') ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
