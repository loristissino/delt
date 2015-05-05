<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

$this->breadcrumbs=array(
  'Exercises'=>array('index'),
  $model->title=>array('view','id'=>$model->id),
  'Invite',
);

$this->menu=array(
  array('label'=>'List Exercise', 'url'=>array('index')),
  array('label'=>'Create Exercise', 'url'=>array('create')),
  array('label'=>'View Exercise', 'url'=>array('view', 'id'=>$model->id)),
  array('label'=>'Manage Exercise', 'url'=>array('admin')),
);
?>

<h1>«<?php echo $model->title ?>»: <?php echo Yii::t('delt', 'invite users') ?></h1>

<div class="form" style="width: 700px">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'exerciseform_'. $model->id,
  'enableAjaxValidation'=>false,
  'method'=>'POST',
  'action'=>array('invite', 'id'=>$model->id),
)); ?>

<?php echo CHtml::textArea('users', '', array('cols'=>30, 'rows'=>10)) ?><br />
<?php echo CHtml::textField('method', '13') ?>

<div class="actions buttons">
  <?php echo CHtml::submitButton(Yii::t('delt', 'Invite'), array('name'=>'invite')) ?>
</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
