<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Journal',
);

$this->menu=array(
	array('label'=>Yii::t('delt', 'New journal post'), 'url'=>array('bookkeeping/newpost', 'slug'=>$model->slug)),
	array('label'=>Yii::t('delt', 'Closing post'), 'url'=>array('bookkeeping/closingpost', 'slug'=>$model->slug)),
  );

if(sizeof($model->posts))
{
  $this->menu[] = array('label'=>Yii::t('delt', 'Clear'), 'url'=>$url=$this->createUrl('bookkeeping/clearjournal', array('slug'=>$model->slug)),  'linkOptions'=>array(   
      'submit' => $url,
      'title' => Yii::t('delt', 'Delete all journal posts'),
      'confirm' => Yii::t('delt', 'Are you sure you want to delete all journal posts?'),
      ),
    );  
}

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
      'cssClassExpression'=>'$data->post->is_closing && $data->rank==1? \'closing\' : \'\'',
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
      'headerHtmlOptions'=>array('style'=>'width: 20px;', 'class'=>'buttons'),
      'htmlOptions'=>array('style'=>'text-align: right', 'class'=>'buttons'),
      'buttons'=>array(
        'update'=>array(
          'label'=>'Edit',
          'options'=>array('title'=>Yii::t('delt', 'Edit')),
          'visible'=>array($this, 'isLineShown'),
        )
      )
		),
	),
)); ?>

