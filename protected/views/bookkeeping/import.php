<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Import',
);

?>
<h1><?php echo Yii::t('delt', 'Import') ?></h1>

<?php echo $this->renderPartial('_importform', array('model'=>$model)) ?>

<?php if(isset($data)): ?>
<pre>
<?php print_r($data) ?>
</pre>
<?php endif ?>
