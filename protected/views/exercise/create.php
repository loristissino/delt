<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

$this->breadcrumbs=array(
  'Exercises'=>array('index'),
  'Create',
);

$this->menu=array(
  array('label'=>'List Exercise', 'url'=>array('index')),
  array('label'=>'Manage Exercise', 'url'=>array('admin')),
);
?>

<h1>Create Exercise</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>