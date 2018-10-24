<?php
/* @var $this ExerciseController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
  'Exercises',
);

$this->menu=array(
  array('label'=>Yii::t('delt', 'New Exercise'), 'url'=>array('create')),
  array('label'=>Yii::t('delt', 'Sessions'), 'url'=>array('sessions')),
);
?>

<h1><?php echo Yii::t('delt', 'Exercises') ?></h1>

<?php $this->widget('zii.widgets.CListView', array(
  'dataProvider'=>$dataProvider,
  'itemView'=>'_view',
)); ?>

<hr />
<p>
  <?php echo Yii::t('delt', 'Exercises are prepared by teachers who want to challenge their students.') ?><br />
  <?php echo Yii::t('delt', 'If you are a student, you are probably looking for <a href="{url}">challenges</a>.', array('{url}'=>$this->createUrl('challenge/index'))) ?>
</p>

