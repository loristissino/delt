<?php
/* @var $this LayerController */
/* @var $model Layer */

$this->breadcrumbs=array(
	'Layers'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create Layer', 'url'=>array('create')),
);

?>

<h1>Manage Layers</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'layer-grid',
	'dataProvider'=>$dataProvider,
	'filter'=>$model,
	'columns'=>array(
		'id',
		'firm_id',
		'name',
		'is_visible',
		'rank',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
