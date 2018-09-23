<?php
/* @var $this SectionController */
/* @var $model Section */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $this->firm->name => array('/bookkeeping/manage', 'slug'=>$this->firm->slug),
  'Sections',
);

$this->menu=array(
	array('label'=>Yii::t('delt', 'Create Section'), 'url'=>array('create', 'slug'=>$this->firm->slug)),
);

?>

<h1><?php echo Yii::t('delt', 'Sections') ?></h1>


<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'section-grid',
	'dataProvider'=>$dataProvider,
	//'filter'=>$model,
	'columns'=>array(
		'name',
      array(
        'class'=>'CDataColumn',
        'name'=>'automatic',
        'value'=>array($this, 'RenderIsVisible'),
        'htmlOptions'=>array('style'=>'text-align: center; width: 60px'),
        'type'=>'raw',
        'header'=>Yii::t('delt', 'Visible') . '?',
        ),
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
      'template'=>'{view}{update}',
		),
	),
)); ?>
