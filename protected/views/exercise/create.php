<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

$this->breadcrumbs=array(
  'Exercises'=>array('index'),
  'Create',
);

$this->menu=array(
  array('label'=>'List Exercises', 'url'=>array('index')),
);
?>

<h1><?php echo Yii::t('delt', 'Create Exercise') ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
