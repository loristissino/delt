<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping/Accounting'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Chart of accounts',
);

$this->menu=array(
	array('label'=>Yii::t('delt', 'Create new account'), 'url'=>array('account/create', 'slug'=>$model->slug)),
	array('label'=>Yii::t('delt', 'Import accounts'), 'url'=>array('account/import', 'slug'=>$model->slug)),
	array('label'=>Yii::t('delt', 'Export accounts'), 'url'=>array('account/export', 'slug'=>$model->slug)),
	//array('label'=>Yii::t('delt', 'Text list'), 'url'=>array('bookkeeping/coa', 'slug'=>$model->slug, 'template'=>'coatextlist')),
	//array('label'=>Yii::t('delt', 'Fix chart'), 'url'=>array('bookkeeping/fixaccountschart', 'slug'=>$model->slug)),
);
if($model->firm_parent_id)
{
  $this->menu[]=array('label'=>Yii::t('delt', 'Synchronize'), 'url'=>array('account/synchronize', 'slug'=>$model->slug), 'linkOptions'=>array('title'=>Yii::t('delt', 'Syncronize accounts from ancestor firms')));
}

?>
<h1><?php echo Yii::t('delt', 'Chart of accounts') ?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'account-grid',
	'dataProvider'=>$dataProvider,
//	'filter'=>$model,
	'columns'=>array(
/*    array(
      'class'=>'CCheckBoxColumn',
      'selectableRows'=>2,
    ),
*/
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
      'value'=>array($this, 'RenderName'),
      // this will call the function RenderName() of the Controller, passing the current object and the row number as parameter
      'type'=>'raw',
      'cssClassExpression'=>'$data->position == \'?\' ? \'unpositioned\' : \'\'',
      ),
    /* the following works, but we don't really need it
    array(
      'name'=>'l10names',
      'value'=>'$data->l10nnames',
      ),
    */
		//'is_economic',
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
		array(
      // see http://www.yiiframework.com/wiki/106/using-cbuttoncolumn-to-customize-buttons-in-cgridview/
			'class'=>'CButtonColumn',
      'template'=>'{view}{update}{new}',
      'viewButtonUrl'=>'Yii::app()->controller->createUrl("bookkeeping/ledger",array("id"=>$data->primaryKey))',
      'updateButtonUrl'=>'Yii::app()->controller->createUrl("account/update",array("id"=>$data->primaryKey))',
      'deleteButtonUrl'=>'Yii::app()->controller->createUrl("account/delete",array("id"=>$data->primaryKey))',
      'headerHtmlOptions'=>array('class'=>'buttons'),
      'htmlOptions'=>array('style'=>'text-align: right', 'class'=>'buttons'),
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
        /*
        'delete'=>array(
          'visible'=>'$data->is_deletable',
        ),*/
      )
      
		),
	),
)); ?>


