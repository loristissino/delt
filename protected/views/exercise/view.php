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
  array('label'=>'View Report', 'url'=>array('report', 'id'=>$model->id)),
  array('label'=>'Invite Users', 'url'=>array('invite', 'id'=>$model->id)),
  array('label'=>'Import', 'url'=>array('import', 'id'=>$model->id)),
  array('label'=>'Export', 'url'=>array('export', 'id'=>$model->id)),
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
        'label'=>'Challenges',
        'type'=>'raw',
        'value'=>CHtml::link(sizeof($model->challenges),
           array('report','id'=>$model->id)
          ),
        ),
  ),
)); ?>

<hr />
<h2><?php echo Yii::t('delt', 'Transactions') ?></h2>
<div id="challenge">
<?php foreach($model->transactions as $transaction): ?>
  <div class="transaction">
  <b><?php echo CHtml::link(Yii::app()->dateFormatter->formatDateTime($transaction->event_date, 'short', null),  array('transaction/update', 'id'=>$transaction->id)) ?></b>
  <span class="score">
    (<?php echo Yii::t('delt', 'points: {points}; penalties: {penalties}', array('{points}'=>$transaction->points, '{penalties}'=>$transaction->penalties)) ?>)
  </span>
  <div class="description">
    <?php echo $md->transform($transaction->description) ?>
  </div>
  <div class="hint">
    <?php echo $md->transform($transaction->hint) ?>
  </div>
  <hr />
  </div>
<?php endforeach ?>
</div>
<p><?php echo CHtml::link(Yii::t('delt', 'Add transaction'), array('transaction/create', 'exercise_id'=>$model->id)) ?></p>
