<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

$this->breadcrumbs=array(
  'Exercises'=>array('index'),
  $model->title=>array('view','id'=>$model->id),
  'Invite',
);

$this->menu=array(
  array('label'=>Yii::t('delt', 'Manage Exercises'), 'url'=>array('index')),
  array('label'=>Yii::t('delt', 'View'), 'url'=>array('view', 'id'=>$model->id)),
);
?>

<h1><?php echo Yii::t('delt', 'Invite Users') ?></h1>

<p><?php echo Yii::t('delt', 'Exercise «{name}»', array('{name}'=>$model->title)); ?>
<div class="form" style="width: 700px">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'exerciseform_'. $model->id,
  'enableAjaxValidation'=>false,
  'method'=>'POST',
  'action'=>array('invite', 'slug'=>$model->slug),
)); ?>


  <div class="row">
    <?php echo CHtml::label(Yii::t('delt', 'List of usernames'), 'users') ?><span class="hint"><?php echo Yii::t('delt', 'One username per line.') ?></span><br />
    <?php echo CHtml::textArea('users', '', array('cols'=>30, 'rows'=>10)) ?>
  </div>

  <div class="row">
    <?php echo CHtml::label(Yii::t('delt', 'Session'), 'session') ?>
    <?php echo CHtml::textField('session', date($model->session_pattern)) ?>
  </div>

  <div class="row checkbox">
    <?php echo CHtml::label(Yii::t('delt', 'Options'), 'method') ?>
    <?php foreach($model->method_items as $key=>$value): ?>
      <?php echo $form->checkBox($model, 'method_items['.$key.']', array('checked'=>$value['value']!=0)) ?>&nbsp;
      <?php echo Yii::t('delt', $value['label']) ?><br />
    <?php endforeach ?>
  </div>

<div class="actions buttons">
  <?php echo CHtml::submitButton(Yii::t('delt', 'Invite Users'), array('name'=>'invite')) ?>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<hr />
<p><?php echo Yii::t('delt', 'You can also invite logged-in users by sharing this link:') ?><br />
<?php echo CHtml::link($url=$this->createAbsoluteUrl('challenge/index', array('exercise'=>$model->slug)), $url) ?>
</p>
