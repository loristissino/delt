<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
	'Journal' => array('/bookkeeping/journal', 'slug'=>$model->slug),
  'Update post',
);

?>
<h1><?php echo Yii::t('delt', 'Update post') ?></h1>

<?php echo $this->renderPartial('_postform', array('postform'=>$postform, 'items'=>$items)) ?>
