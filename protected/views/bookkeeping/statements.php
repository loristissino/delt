<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Statements',
);

$this->menutitle=Yii::t('delt', 'Depth');

$this->menu=array();

for($i=1; $i<=$model->COAMaxLevel; $i++)
{
  $this->menu[]=array('label'=>Yii::t('delt', 'Down to Level {number}', array('{number}'=>$i)), 'url'=>array('bookkeeping/statements', 'slug'=>$model->slug, 'level'=>$i));
}

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
