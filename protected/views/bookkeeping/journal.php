<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Journal',
);

$this->menu=array(
	array('label'=>Yii::t('delt', 'Chart of accounts'), 'url'=>array('bookkeeping/coa', 'slug'=>$model->slug)),
	array('label'=>Yii::t('delt', 'New journal post'), 'url'=>array('bookkeeping/newpost', 'slug'=>$model->slug)),
);

?>
<h1><?php echo Yii::t('delt', 'Journal') ?></h1>

