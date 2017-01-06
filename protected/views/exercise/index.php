<?php
/* @var $this ExerciseController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
  'Exercises',
);

$this->menu=array(
  array('label'=>'New Exercise', 'url'=>array('create')),
);
?>

<h1><?php echo Yii::t('delt', 'Exercises') ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
  'dataProvider'=>$dataProvider,
  'itemView'=>'_view',
)); ?>
