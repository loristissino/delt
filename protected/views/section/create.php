<?php
/* @var $this SectionController */
/* @var $model Section */

$this->breadcrumbs=array(
	'Sections'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'Manage Sections', 'url'=>array('admin', 'slug'=>$this->firm->slug)),
);
?>

<h1>Create Section</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
