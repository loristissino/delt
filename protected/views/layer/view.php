<?php
/* @var $this LayerController */
/* @var $model Layer */

$this->breadcrumbs=array(
	'Layers'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Layer', 'url'=>array('layer/admin', 'slug'=>$firm->slug)),
	array('label'=>'Create Layer', 'url'=>array('create')),
	array('label'=>'Delete Layer', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View Layer #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'firm_id',
		'name',
		'is_visible',
		'rank',
	),
)); ?>
