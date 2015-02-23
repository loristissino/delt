<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Journal' =>array('/bookkeeping/journal', 'slug'=>$model->slug),
  'Template update',
);

$this->menu=array(
);

?>
<h1><?php echo Yii::t('delt', 'Template update') ?></h1>

<p><?php echo Yii::t('delt', 'Sorry, this action has not been implemented yet.') ?></p>

<p><?php echo CHtml::link(Yii::t('delt', 'Use a template'), array('template/admin', 'slug'=>$model->slug)) ?></p>
