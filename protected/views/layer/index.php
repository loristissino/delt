<?php
/* @var $this LayerController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Layers',
);

$this->menu=array(
	array('label'=>'Create Layer', 'url'=>array('create')),
	array('label'=>'Manage Layer', 'url'=>array('admin')),
);
?>

<h1>Layers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
