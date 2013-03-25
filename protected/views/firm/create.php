<?php
/* @var $this FirmController */
/* @var $model Firm */

$this->breadcrumbs=array(
	'Firms'=>array('index'),
	'Create',
);

$this->menu=array();
?>

<h1><?php echo Yii::t('delt', 'Create a Firm') ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
