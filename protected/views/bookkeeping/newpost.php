<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'New journal post',
);

if(sizeof($model->reasons))
{
  $this->menutitle='Reasons';
  $this->menu=array();
  foreach($model->reasons as $reason)
  {
    $this->menu[]=array('label'=>$reason->description, 'url'=>array('bookkeeping/postfromreason', 'id'=>$reason->id));
  }
}
?>
<h1><?php echo Yii::t('delt', 'New journal post') ?></h1>

<?php echo $this->renderPartial('_postform', array('postform'=>$postform, 'items'=>$items)) ?>
