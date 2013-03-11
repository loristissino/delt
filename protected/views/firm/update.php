<?php
/* @var $this FirmController */
/* @var $model Firm */

$this->breadcrumbs=array(
	'Firms'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

?>

<h1><?php echo Yii::t('delt', 'Update Firm «{name}»', array('{name}'=>$model->name)) ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
