<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'New post',
);

$this->menu=array(
	array('label'=>Yii::t('delt', 'Chart of accounts'), 'url'=>array('bookkeeping/accountschart', 'slug'=>$model->slug)),
);

?>
<h1>New post</h1>

<?php echo $this->renderPartial('_postform', array('postform'=>$postform, 'items'=>$items)) ?>
