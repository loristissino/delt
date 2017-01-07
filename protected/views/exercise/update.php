<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

$this->breadcrumbs=array(
  'Exercises'=>array('index'),
  $model->title=>array('view','id'=>$model->id),
  'Edit',
);

$this->menu=array(
  array('label'=>Yii::t('delt', 'Manage Exercises'), 'url'=>array('index')),
  array('label'=>Yii::t('delt', 'View'), 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1><?php echo Yii::t('delt', 'Edit Exercise «{name}»', array('{name}'=>$model->title)); ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
