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
  array('label'=>Yii::t('delt', 'Manage Exercises'), 'url'=>array('index')),
  array('label'=>Yii::t('delt', 'Edit'), 'url'=>array('update', 'id'=>$model->id)),
  array('label'=>Yii::t('delt', 'View Report'), 'url'=>array('report', 'id'=>$model->id)),
  array('label'=>Yii::t('delt', 'Invite Users'), 'url'=>array('invite', 'id'=>$model->id)),
  array('label'=>Yii::t('delt', 'Import'), 'url'=>array('import', 'id'=>$model->id)),
  array('label'=>Yii::t('delt', 'Export'), 'url'=>array('export', 'id'=>$model->id)),
  array('label'=>Yii::t('delt', 'Delete'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>Yii::t('delt', 'Are you sure you want to delete this exercise?'))),
);
?>

<h1><?php echo Yii::t('delt', 'Exercise «{name}»', array('{name}'=>$model->title)) ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
  'data'=>$model,
  'attributes'=>array(
    array(
        'label'=>Yii::t('delt', 'Benchmark Firm'),
        'type'=>'raw',
        'value'=>CHtml::link(CHtml::encode($model->firm->name),
           array('bookkeeping/manage','slug'=>$model->firm->slug)
        ),
    ),
    array(
        'label'=>Yii::t('delt', 'Proposed Parent Firm'),
        'type'=>'raw',
        'value'=>CHtml::link(CHtml::encode($parent_firm->name),
           array(Yii::app()->params['publicpages'][$parent_firm->firmtype].$parent_firm->slug)
        ),
    ),
    array(
        'label'=>Yii::t('delt', 'Slug'),
        'value'=>$model->slug,
        ),
    array(
        'label'=>Yii::t('delt', 'Title'),
        'value'=>$model->title,
        ),
    'description',
    array(
        'label'=>Yii::t('delt', 'Introduction'),
        'type'=>'raw',
        'value'=>$md->transform($model->introduction),
        ),
    array(
        'label'=>Yii::t('delt', 'Challenges'),
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
<?php foreach($model->transactions as $transaction): $actual_entries = sizeof($transaction->getJournalEntriesFromFirm($model->firm_id)) ?>
  <div class="transaction">
  <?php if($transaction->entries!=$actual_entries): ?>
    <span class="warning">
      <?php echo $this->createIcon('bell', Yii::t('delt', 'warning'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'The number of journal entries in the benchmark firm do not match the number declared.'))) ?>
    </span>
  <?php endif ?>
  <b><?php echo CHtml::link(Yii::app()->dateFormatter->formatDateTime($transaction->event_date, 'short', null),  array('transaction/update', 'id'=>$transaction->id), array('title'=>Yii::t('delt', 'Event Date'). ' (' . Yii::t('delt', 'click to edit the transaction') . ')')) ?></b><sup title="<?php echo Yii::t('delt', 'Rank') ?>"><?php echo $transaction->rank ?></sup>
  [
  <span class="entries" title="<?php echo Yii::t('delt', 'Journal entries in Benchmark Firm actually used to record this transaction') ?>">
  <?php echo $actual_entries ?>
  </span>
  /
  <span class="entries" title="<?php echo Yii::t('delt', 'Journal entries declared to be needed to record this transaction') ?>">
  <?php echo $transaction->entries ?>
  </span>
  ]
  <span class="score">
    (<?php echo Yii::t('delt', 'Points: {points}. Penalties: {penalties}', array('{points}'=>$transaction->points, '{penalties}'=>$transaction->penalties)) ?>)
  </span>
  
  <div class="description">
    <?php echo $md->transform($transaction->description) ?>
  </div>
  <div class="hint">
    <?php echo $md->transform($transaction->hint) ?>
  </div>
  <br />
  <div class="journalentries_shown">
  <?php 
    
    $this->renderPartial('//challenge/_journalentries', array('postings'=>
      Posting::model()->getPostingsByFirmAndTransaction($model->firm_id, $transaction->id),
      'model'=>$model->firm,
      'title'=>false,
      'draggable'=>false,
      )
    );
  ?>
  </div>
  
  <hr />
  </div>
<?php endforeach ?>
</div>
<p><?php echo CHtml::link(Yii::t('delt', 'New Transaction'), array('transaction/create', 'exercise_id'=>$model->id)) ?></p>
