<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Statements',
);

?>
<h1><?php echo Yii::t('delt', 'Statements') ?></h1>

<?php echo $this->renderPartial('_statement', array(
  'title'=>'Financial Statement',
  'data'=>$financial,
  'model'=>$model,
  'level'=>$level,
  'order'=>array('D'=>'Assets', 'C'=>'Liabilities and Equity'),
  )) ?>
<?php echo $this->renderPartial('_statement', array(
  'title'=>'Profit and Loss Statement',
  'data'=>$economic,
  'model'=>$model,
  'level'=>$level,
  'order'=>array('C'=>'Revenues', 'D'=>'Expenses'),
  )) ?>
