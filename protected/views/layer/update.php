<?php
/* @var $this LayerController */
/* @var $model Layer */

$this->breadcrumbs=array(
	'Layers'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Layer', 'url'=>array('index')),
	array('label'=>'Create Layer', 'url'=>array('create')),
	array('label'=>'View Layer', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Layer', 'url'=>array('admin')),
);
?>

<h1>Update Layer <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>