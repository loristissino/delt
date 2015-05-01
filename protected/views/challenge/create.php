<?php
/* @var $this ChallengeController */
/* @var $model Challenge */

$this->breadcrumbs=array(
  'Challenges'=>array('index'),
  'Create',
);

$this->menu=array(
  array('label'=>'List Challenge', 'url'=>array('index')),
  array('label'=>'Manage Challenge', 'url'=>array('admin')),
);
?>

<h1>Create Challenge</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>