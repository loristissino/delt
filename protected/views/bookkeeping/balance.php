<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping/Accounting'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Balance',
);

$this->menu=array(
	array('label'=>Yii::t('delt', 'Export (CSV)'), 'url'=>array('bookkeeping/balance', 'slug'=>$model->slug, 'format'=>'unknown')),
);

$totaldebits=$this->firm->getTotalAmounts('D');
$totalcredits=$this->firm->getTotalAmounts('C');

?>
<h1><?php echo Yii::t('delt', 'Trial Balance') ?></h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'firm-grid',
	'dataProvider'=>$dataProvider,
	'columns'=>array(
    array(
      'name'=>'position',
      'header'=>Yii::t('delt', 'Position'),
      'sortable'=>false,
      'value'=>array($this, 'RenderPosition'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'centered')
      ),
    array(
      'name'=>'account',
      'header'=>Yii::t('delt', 'Account'),
      'value'=>array($this, 'RenderSingleAccount'),
      'type'=>'raw',
      'cssClassExpression'=>'$data->position == \'?\' ? \'unpositioned\' : \'\'',
      ),
    array(
      'class'=>'CDataColumn',
      'name'=>'account.debitgrandtotal',
      'value'=>array($this, 'RenderSingleDebit'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      'header'=>Yii::t('delt', 'Total Debit'),
      'footer'=>DELT::currency_value($totaldebits, $this->firm->currency),
      'footerHtmlOptions'=>array('class'=>'currency grandtotal'),
      ),
    array(
      'class'=>'CDataColumn',
      'name'=>'account.creditgrandtotal',
      'value'=>array($this, 'RenderSingleCredit'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      'header'=>Yii::t('delt', 'Total Credit'),
      'footer'=>DELT::currency_value($totalcredits, $this->firm->currency),
      'footerHtmlOptions'=>array('class'=>'currency grandtotal'),
      ),
    array(
      'class'=>'CDataColumn',
      'name'=>'account.computedoutstandingbalancedr',
      'value'=>array($this, 'RenderCheckedOutstandingBalanceDr'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      'header'=>Yii::t('delt', 'Balance (Dr.)'),
      ),
    array(
      'class'=>'CDataColumn',
      'name'=>'account.computedoutstandingbalancecr',
      'value'=>array($this, 'RenderCheckedOutstandingBalanceCr'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      'header'=>Yii::t('delt', 'Balance (Cr.)'),
      ),
    array(
      'class'=>'CDataColumn',
      'name'=>'account.computedoutstandingbalance',
      'value'=>array($this, 'RenderCheckedOutstandingBalance'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      'header'=>Yii::t('delt', 'Notes'),
      ),
	),
)); ?>

<div style="display: none">
<p>Grandtotal Debit: <?php echo DELT::currency_value($this->debit_sum, $this->firm->currency) ?>.</p>
<p>Grandtotal Credit: <?php echo DELT::currency_value(-$this->credit_sum, $this->firm->currency) ?>.</p>
</div>
