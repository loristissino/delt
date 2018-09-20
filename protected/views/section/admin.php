<?php
/* @var $this SectionController */
/* @var $model Section */

$this->breadcrumbs=array(
	'Sections'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create Section', 'url'=>array('create')),
);

?>

<h1>Manage Sections</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'section-grid',
	'dataProvider'=>$dataProvider,
	//'filter'=>$model,
	'columns'=>array(
		'name',
		'is_visible',
		'rank',
    array(
      'class'=>'DataColumn',
      'sortable'=>false,
      'name'=>'color',
      'header'=>Yii::t('delt', 'Color'),
      'value'=>' ',
      'type'=>'raw',
      'evaluateHtmlOptions'=>true,
      'htmlOptions'=>array('style'=>'"background-color: #{$data->color}"'),
      ),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
