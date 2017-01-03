<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

$this->breadcrumbs=array(
  'Exercises'=>array('index'),
  $model->title=>array('view','id'=>$model->id),
  'Edit',
);

$this->menu=array(
  array('label'=>'List Exercises', 'url'=>array('index')),
  array('label'=>'View Exercise', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1><?php echo Yii::t('delt', 'Edit «{name}»', array('{name}'=>$model->title)); ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
