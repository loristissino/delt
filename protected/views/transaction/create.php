<?php
/* @var $this TransactionController */
/* @var $model Transaction */

$this->breadcrumbs=array(
  'Exercises'=>array('exercise/index'),
  $this->exercise->title=>array('exercise/view', 'id'=>$this->exercise->id),
  'Transactions'=>array('exercise/transactions', 'id'=>$this->exercise->id),
  'Create',
);

$this->menu=array(
	array('label'=>'View Transactions', 'url'=>array('exercise/transactions', 'id'=>$this->exercise->id)),
);
?>

<h1>Create Transaction</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
