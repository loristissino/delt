<?php
/* @var $this SectionController */
/* @var $model Section */

$this->breadcrumbs=array(
	'Sections'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Section', 'url'=>array('section/admin', 'slug'=>$firm->slug)),
	array('label'=>'Create Section', 'url'=>array('create')),
	array('label'=>'Delete Section', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View Section #<?php echo $model->id; ?></h1>

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
