<?php
/* @var $this ChallengeController */
/* @var $model Challenge */

$this->breadcrumbs=array(
  'Challenges'=>array('index'),
  $model->id,
);

$this->menu=array(
  array('label'=>'List Challenge', 'url'=>array('index')),
  array('label'=>'Create Challenge', 'url'=>array('create')),
  array('label'=>'Update Challenge', 'url'=>array('update', 'id'=>$model->id)),
  array('label'=>'Delete Challenge', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
  array('label'=>'Manage Challenge', 'url'=>array('admin')),
);
?>

<h1>View Challenge #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
  'data'=>$model,
  'attributes'=>array(
    'id',
    'exercise_id',
    'instructor_id',
    'user_id',
    'firm_id',
    'assigned_at',
    'started_at',
    'suspended_at',
    'completed_at',
    'method',
    'mark',
  ),
)); ?>
