<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Export',
);

$this->menu=array(
);

?>
<h1><?php echo Yii::t('delt', 'Export') ?></h1>

<p><?php echo Yii::t('delt', 'You can export the data of this firm in the following formats:') ?></p>

<ul>
  <li><?php echo CHtml::link('DELT', array('bookkeeping/export', 'slug'=>$model->slug, 'format'=>'delt')) ?> (<?php echo Yii::t('delt', 'standard JSON-based format used by DELT') ?>)</li>
  <li><?php echo CHtml::link('ledger-cli', array('bookkeeping/export', 'slug'=>$model->slug, 'format'=>'ledger')) ?> (<?php echo Yii::t('delt', 'text-based ledger-cli\'s format for transactions') ?>)</li>
</ul>
