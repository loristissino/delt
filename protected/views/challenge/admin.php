<?php
/* @var $this ChallengeController */
/* @var $model Challenge */

$this->breadcrumbs=array(
  'Challenges'=>array('index'),
  'Manage',
);

$this->menu=array(
  array('label'=>'List Challenge', 'url'=>array('index')),
  array('label'=>'Create Challenge', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
  $('.search-form').toggle();
  return false;
});
$('.search-form form').submit(function(){
  $('#challenge-grid').yiiGridView('update', {
    data: $(this).serialize()
  });
  return false;
});
");
?>

<h1>Manage Challenges</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
  'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'challenge-grid',
  'dataProvider'=>$model->search(),
  'filter'=>$model,
  'columns'=>array(
    'id',
    'exercise_id',
    'instructor_id',
    'user_id',
    'firm_id',
    'assigned_at',
    /*
    'started_at',
    'suspended_at',
    'completed_at',
    'method',
    'mark',
    */
    array(
      'class'=>'CButtonColumn',
    ),
  ),
)); ?>
