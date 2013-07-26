<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'New journal entry',
);

if(sizeof($model->templates))
{
  $this->menutitle='Templates';
  $this->menu=array();
  foreach($model->templates as $template)
  {
    $this->menu[]=array('label'=>$template->description, 'url'=>array('bookkeeping/postfromtemplate', 'id'=>$template->id));
  }
}
?>
<h1><?php echo Yii::t('delt', 'New journal entry') ?></h1>

<?php echo $this->renderPartial('_postform', array('postform'=>$postform, 'items'=>$items)) ?>
