<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Chart of accounts',
);
?>
<h1><?php echo Yii::t('delt', 'Chart of accounts') ?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'account-grid',
	'dataProvider'=>$model->getAccountsAsDataProvider(),
//	'filter'=>$model,
	'columns'=>array(
/*    array(
      'class'=>'CCheckBoxColumn',
      'selectableRows'=>2,
    ),
*/
		'code',
    array(
      'class'=>'CDataColumn',
      'sortable'=>true,
      'name'=>'name',
      'value'=>array($this, 'RenderName'),
      // this will call the function RenderName() of the Controller, passing the current object and the row number as parameter
      'type'=>'raw',
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
      'name'=>'is_economic',
      'value'=>array($this, 'RenderIsEconomic'),
      'type'=>'raw',
      'htmlOptions'=>array('style'=>'text-align: center') // FIXME we should do it with a class
      ),

		'outstanding_balance',
		array(
			'class'=>'ext.grid.CDeltAccountButtonColumn',
		),
	),
)); ?>

