<?php
/* @var $this ExerciseController */
/* @var $dataProvider CActiveDataProvider */

$md = new CMarkdown();

$this->breadcrumbs=array(
  'Exercises'=>array('index'),
  $model->title=>array('view','id'=>$model->id),
  'Transactions',
);


$this->menu=array(
  array('label'=>'View Exercise', 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1><?php echo Yii::t('delt', 'Transactions') ?></h1>
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
