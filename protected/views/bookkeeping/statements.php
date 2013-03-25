<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Statements',
);

?>
<h1><?php echo Yii::t('delt', 'Statements') ?></h1>

<?php echo $this->renderPartial('_statement', array('title'=>'Financial statement', 'data'=>$financial, 'model'=>$model)) ?>
<?php echo $this->renderPartial('_statement', array('title'=>'Profit and loss statement', 'data'=>$economic, 'model'=>$model)) ?>
