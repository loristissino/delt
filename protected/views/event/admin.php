<?php
/* @var $this EventController */
/* @var $model Event */

$this->breadcrumbs=array(
	'Events'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Event', 'url'=>array('index')),
	array('label'=>'Create Event', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#event-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Events</h1>

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
	'id'=>'event-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
    array(
      'name'=>'user',
      'header'=>Yii::t('delt', 'User'),
      'sortable'=>false,
      'value'=>array($this, 'RenderUser'),
      'type'=>'raw',
      ),
    array(
      'name'=>'firm',
      'header'=>Yii::t('delt', 'Firm'),
      'sortable'=>false,
      'value'=>array($this, 'RenderFirm'),
      'type'=>'raw',
      ),
    array(
      'name'=>'action',
      'header'=>Yii::t('delt', 'Action'),
      'sortable'=>false,
      'value'=>array($this, 'RenderAction'),
      'type'=>'raw',
      ),
		'happened_at',
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
