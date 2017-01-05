<?php
/* @var $this ExerciseController */
/* @var $model Exercise */

$this->breadcrumbs=array(
  'Exercises'=>array('index'),
  $model->title=>array('view','id'=>$model->id),
  'Export',
);

$this->menu=array(
  array('label'=>'View', 'url'=>array('view', 'id'=>$model->id)),
  array('label'=>'Edit', 'url'=>array('update', 'id'=>$model->id)),
);

?>

<h1><?php echo Yii::t('delt', 'Exercise «{name}»', array('{name}'=>$model->title)) ?></h1>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'export-exercise-form',
  'enableAjaxValidation'=>false,
)); ?>

  <p class="note"><?php echo Yii::t('delt', 'The file is in YAML format.') ?></p>

  <div class="row">
    <?php echo $form->textArea($model, 'yaml', array('rows' => 30, 'cols' => 70)); ?>
  </div>
  
<?php $this->endWidget(); ?>

</div><!-- form -->
