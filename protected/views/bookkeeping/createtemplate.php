<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Journal' =>array('/bookkeeping/journal', 'slug'=>$model->slug),
  'Template creation',
);

$this->menu=array(
);

?>
<h1><?php echo Yii::t('delt', 'Template creation') ?></h1>

<?php echo $this->renderPartial('_template', array('model'=>$template, 'firm'=>$model)) ?>
