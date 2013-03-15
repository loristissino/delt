<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
	'Journal' => array('/bookkeeping/journal', 'slug'=>$model->slug),
  'Update post',
);

$this->menu=array();

if(true) // TODO -- we might manage a 'is deletable' condition
{
  $this->menu[]= array('label'=>Yii::t('zii', 'Delete'), 'url'=>$url=$this->createUrl('bookkeeping/deletepost', array('id'=>$postform->post->id)),  'linkOptions'=>array(   
      'submit' => $url,
      'title' => Yii::t('delt', 'Delete this post'),
      'confirm' => Yii::t('zii', 'Are you sure you want to delete this item?'),
    ),
  );
}

?>
<h1><?php echo Yii::t('delt', 'Edit journal post') ?></h1>

<?php echo $this->renderPartial('_postform', array('postform'=>$postform, 'items'=>$items)) ?>
