<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

$this->breadcrumbs=array(
  'Exercises'=>array('index'),
  $model->title,
);

$this->menu=array(
  array('label'=>'List Exercise', 'url'=>array('index')),
  array('label'=>'Create Exercise', 'url'=>array('create')),
  array('label'=>'Update Exercise', 'url'=>array('update', 'id'=>$model->id)),
  array('label'=>'Delete Exercise', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
  array('label'=>'Manage Exercise', 'url'=>array('admin')),
);
?>

<h1>View Exercise #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
  'data'=>$model,
  'attributes'=>array(
    'id',
    'user_id',
    'firm_id',
    'slug',
    'title',
    'description',
    'introduction',
  ),
)); ?>
