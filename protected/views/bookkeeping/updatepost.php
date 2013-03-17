<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
	'Journal' => array('/bookkeeping/journal', 'slug'=>$model->slug),
  'Update post',
);

$this->menu=array(
  array('label'=>Yii::t('delt', 'Create Reason'), 'url'=>$this->createUrl('bookkeeping/createreason', array('id'=>$postform->post->id)),'linkOptions'=>array(
    'title'=>Yii::t('delt', 'Create a Reason based on this post'),
    ))
  );

if(true) // TODO -- we might manage a 'is deletable' condition
{
  $this->menu[]= array('label'=>Yii::t('delt', 'Delete'), 'url'=>$url=$this->createUrl('bookkeeping/deletepost', array('id'=>$postform->post->id)),  'linkOptions'=>array(   
      'submit' => $url,
      'title' => Yii::t('delt', 'Delete this post'),
      'confirm' => Yii::t('delt', 'Are you sure you want to delete this journal post?'),
    ),
  );
}

?>
<h1><?php echo Yii::t('delt', 'Edit journal post') ?></h1>

<?php echo $this->renderPartial('_postform', array('postform'=>$postform, 'items'=>$items)) ?>
