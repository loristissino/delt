<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

$md = new CMarkdown();

$this->breadcrumbs=array(
  'Exercises'=>array('index'),
  $model->title,
);

$this->layout = '//layouts/column2';

$this->menu=array(
  array('label'=>'List Exercises', 'url'=>array('index')),
  array('label'=>'Edit Exercise', 'url'=>array('update', 'id'=>$model->id)),
  array('label'=>'View Transactions', 'url'=>array('transactions', 'id'=>$model->id)),
  array('label'=>'View Report', 'url'=>array('report', 'id'=>$model->id)),
  array('label'=>'Invite Users', 'url'=>array('invite', 'id'=>$model->id)),
  array('label'=>'Delete', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('delt', 'Are you sure you want to delete this exercise?'))),
  //array('label'=>'Manage Exercises', 'url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('delt', 'Exercise «{name}»', array('{name}'=>$model->title)) ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
  'data'=>$model,
  'attributes'=>array(
    array(
        'label'=>'Benchmark Firm',
        'type'=>'raw',
        'value'=>CHtml::link(CHtml::encode($model->firm->name),
           array('bookkeeping/manage','slug'=>$model->firm->slug)
        ),
    ),
    'slug',
    'title',
    'description',
    array(
        'label'=>'introduction',
        'type'=>'raw',
        'value'=>$md->transform($model->introduction),
        ),
    array(
        'label'=>'Transactions',
        'type'=>'raw',
        'value'=>CHtml::link(sizeof($model->transactions),
           array('transactions','id'=>$model->id)
          ),
        ),
    array(
        'label'=>'Challenges',
        'type'=>'raw',
        'value'=>CHtml::link(sizeof($model->challenges),
           array('report','id'=>$model->id)
          ),
        ),
  ),
)); ?>
