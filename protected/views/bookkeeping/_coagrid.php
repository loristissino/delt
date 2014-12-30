<?php 

$columns = array(
    array(
      'class'=>'CDataColumn',
      'sortable'=>true,
      'name'=>'code',
      'header'=>Yii::t('delt', 'Code'),
      ),
    array(
      'class'=>'CDataColumn',
      'sortable'=>true,
      'name'=>'name',
      'header'=>Yii::t('delt', 'Name'),
      'value'=>array($this, $renderNameCallable),
      // this will call the function RenderName() of the Controller, passing the current object and the row number as parameter
      'type'=>'raw',
      'cssClassExpression'=>'$data->position == \'?\' ? \'unpositioned\' : \'\'',
      ),
    array(
      'class'=>'CDataColumn',
      'sortable'=>true,
      'name'=>'position',
      'header'=>Yii::t('delt', 'Position'),
      'value'=>array($this, 'RenderPosition'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'centered')
      ),
    array(
      'class'=>'CDataColumn',
      'sortable'=>true,
      'name'=>'outstanding_balance',
      'header'=>Yii::t('delt', 'Outstanding balance'),
      'value'=>array($this, 'RenderOutstandingBalance'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'centered')
      ),
    );

if($showclasses)
{
  $columns[]='classes';
}
    
$columns[]=
    array(
      // see http://www.yiiframework.com/wiki/106/using-cbuttoncolumn-to-customize-buttons-in-cgridview/
      'class'=>'CButtonColumn',
      'template'=>$buttonsTemplate,
      'viewButtonUrl'=>'Yii::app()->controller->createUrl("bookkeeping/ledger",array("id"=>$data->primaryKey))',
      'updateButtonUrl'=>'Yii::app()->controller->createUrl("account/update",array("id"=>$data->primaryKey))',
      'deleteButtonUrl'=>'Yii::app()->controller->createUrl("account/delete",array("id"=>$data->primaryKey))',
      'headerHtmlOptions'=>array('class'=>'buttons'),
      'htmlOptions'=>array('style'=>'text-align: right; width: 60px', 'class'=>'buttons'),
      'buttons'=>array(
        'new'=>array(
          'label'=>'New',
          'url'=>'Yii::app()->controller->createUrl("account/create",array("slug"=>"' . $model->slug . '","id"=>$data->primaryKey))',
          'imageUrl'=>Yii::app()->request->baseUrl.'/images/new.png',
          'options'=>array('title'=>Yii::t('delt', 'Create a new account as child of this one'), 'class'=>'new'),
        ),
        'view'=>array(
          'visible'=>'$data->is_selectable',
        ),
        'update'=>array(
          'label'=>'Edit',
          'options'=>array('title'=>Yii::t('delt', 'Edit')),
        )
      ),
    );

$this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'account-grid',
  'dataProvider'=>$dataProvider,
//  'filter'=>$model,
  'columns'=> $columns,
)); ?>
