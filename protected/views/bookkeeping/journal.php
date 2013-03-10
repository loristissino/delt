<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Journal',
);

$this->menu=array(
	array('label'=>Yii::t('delt', 'New journal post'), 'url'=>array('bookkeeping/newpost', 'slug'=>$model->slug)),
);

$this->hide_date_and_description = true;

?>
<h1><?php echo Yii::t('delt', 'Journal') ?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'firm-grid',
	'dataProvider'=>$this->firm->getPostsAsDataProvider(),
	'columns'=>array(
    array(
      'class'=>'CDataColumn',
      'name'=>'post.date',
      'value'=>array($this, 'RenderDate'),
      'type'=>'raw',
      'header'=>Yii::t('delt', 'Date'),
      ),
    array(
      'name'=>'post.description',
      'header'=>Yii::t('delt', 'Description'),
      'value'=>array($this, 'RenderDescription'),
      ),
    array(
      'name'=>'post.account',
      'header'=>Yii::t('delt', 'Account'),
      'sortable'=>false,
      'type'=>'raw',
      'value'=>array($this, 'RenderAccount'),
      ),
    array(
      'class'=>'CDataColumn',
      'name'=>'debit',
      'value'=>array($this, 'RenderDebit'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      //'footer'=>DELT::currency_value($debitgrandtotal, $this->firm->currency),
      //'footerHtmlOptions'=>array('class'=>'currency grandtotal'),
      ),
    array(
      'class'=>'CDataColumn',
      'name'=>'credit',
      'value'=>array($this, 'RenderCredit'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      //'footer'=>DELT::currency_value(-$creditgrandtotal, $this->firm->currency),
      //'footerHtmlOptions'=>array('class'=>'currency grandtotal'),
      ),
		array(
      // see http://www.yiiframework.com/wiki/106/using-cbuttoncolumn-to-customize-buttons-in-cgridview/
			'class'=>'CButtonColumn',
      'template'=>'{update}',
      'updateButtonUrl'=>'Yii::app()->controller->createUrl("bookkeeping/updatepost",array("id"=>$data->post_id))',
      'headerHtmlOptions'=>array('style'=>'width: 20px;'),
      'htmlOptions'=>array('style'=>'text-align: right'),
      'buttons'=>array(
        'update'=>array(
          'label'=>'Edit',
          'options'=>array('title'=>Yii::t('delt', 'Edit')),
        )
      )
		),
	),
)); ?>

