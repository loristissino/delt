<?php
/* @var $this LayerController */
/* @var $model Layer */

$this->breadcrumbs=array(
	'Layers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Layer', 'url'=>array('index')),
	array('label'=>'Manage Layer', 'url'=>array('admin')),
);
?>

<h1>Create Layer</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>