<?php
/* @var $this ChallengeController */
/* @var $model Challenge */

$this->breadcrumbs=array(
  'Challenges'=>array('index'),
  $model->id=>array('view','id'=>$model->id),
  'Update',
);

$this->menu=array(
  array('label'=>'List Challenge', 'url'=>array('index')),
  array('label'=>'Create Challenge', 'url'=>array('create')),
  array('label'=>'View Challenge', 'url'=>array('view', 'id'=>$model->id)),
  array('label'=>'Manage Challenge', 'url'=>array('admin')),
);
?>

<h1>Update Challenge <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>