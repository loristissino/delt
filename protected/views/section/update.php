<?php
/* @var $this SectionController */
/* @var $model Section */

$this->breadcrumbs=array(
	'Sections'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Section', 'url'=>array('index')),
	array('label'=>'Create Section', 'url'=>array('create')),
	array('label'=>'View Section', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Sections', 'url'=>array('admin')),
);
?>

<h1>Update Section <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
