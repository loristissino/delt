<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Ledger',
);
?>
<h1><?php echo Yii::t('delt', 'Ledger') ?></h1>

<h2><?php echo $account->name ?></h2>
