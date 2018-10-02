<?php
/* @var $this ApiController */

$this->breadcrumbs=array(
	'Api'=>array('/api'),
	'Subscribe',
);
?>
<h1><?php echo Yii::t('delt', 'API Subscription') ?></h1>

<?php $this->renderPartial('_form') ?>

