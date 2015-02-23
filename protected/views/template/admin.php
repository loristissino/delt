<?php
/* @var $this TemplateController */
/* @var $model Template */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $this->firm->name => array('/bookkeeping/manage', 'slug'=>$this->firm->slug),
  'Templates',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#template-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

$dataProvider = $model->search();

?>

<h1><?php echo Yii::t('delt', 'Templates') ?></h1>


<?php if(sizeof($dataProvider->data)): ?>
  <p><?php echo Yii::t('delt', 'Click on a template\'s description to start a journal entry from it.') ?></p>

  <?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'template-grid',
    'dataProvider'=>$dataProvider,
    'columns'=>array(
      array(
        'class'=>'CDataColumn',
        'name'=>'automatic',
        'value'=>array($this, 'RenderAutomatic'),
        'htmlOptions'=>array('style'=>'text-align: center; width: 60px'),
        'type'=>'raw',
        'header'=>Yii::t('delt', 'Automatic') . '?',
        ),
      array(
        'class'=>'CDataColumn',
        'name'=>'description',
        'value'=>array($this, 'RenderDescription'),
        'htmlOptions'=>array('class'=>'hiddenlink'),
        'type'=>'raw',
        'header'=>Yii::t('delt', 'Description'),
        ),
      array(
        // see http://www.yiiframework.com/wiki/106/using-cbuttoncolumn-to-customize-buttons-in-cgridview/
        'class'=>'CButtonColumn',
        'template'=>'{update}{delete}',
        'updateButtonUrl'=>'Yii::app()->controller->createUrl("bookkeeping/updatetemplate",array("id"=>$data->primaryKey))',
        'deleteButtonUrl'=>'Yii::app()->controller->createUrl("bookkeeping/deletetemplate",array("id"=>$data->primaryKey))',
        'headerHtmlOptions'=>array('class'=>'buttons'),
        'htmlOptions'=>array('style'=>'text-align: center; width: 50px', 'class'=>'buttons'),
        'buttons'=>array(
          'delete'=>array(
            'label'=>'Delete',
            'options'=>array('title'=>Yii::t('delt', 'Delete')),
          ),
          'update'=>array(
            'label'=>'Edit',
            'options'=>array('title'=>Yii::t('delt', 'Edit')),
          ),
          'toggle'=>array(
            // FIXM is there a way to use POST with buttons? apparently not...
            'label'=>'Toggle Automatic Status',
            'url'=>'Yii::app()->controller->createUrl("bookkeeping/toggleautomaticstatus",array("id"=>"$data->primaryKey"))',
            'options'=>array('title'=>Yii::t('delt', 'Toggle Automatic Status')),
            'imageUrl'=>Yii::app()->request->baseUrl.'/images/automatictoggle.png',
          )

        ),
      ),

    ),
  )); ?>
    
<?php else: ?>
  <p><?php echo Yii::t('delt', 'No templates available for this firm.') ?></p>
<?php endif ?>
