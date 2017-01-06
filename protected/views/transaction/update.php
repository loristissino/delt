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
	array('label'=>'View Exercise', 'url'=>array('exercise/view', 'id'=>$this->exercise->id)),
  array('label'=>'Delete', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('delt', 'Are you sure you want to delete this transaction?'))),
);
?>

<h1><?php echo Yii::t('delt', 'Edit Transaction') ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
