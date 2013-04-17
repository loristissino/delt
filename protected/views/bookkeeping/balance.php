<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Balance',
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
      'name'=>'collocation',
      'header'=>Yii::t('delt', 'Collocation'),
      'sortable'=>false,
      ),
    array(
      'name'=>'account',
      'header'=>Yii::t('delt', 'Account'),
      'value'=>array($this, 'RenderSingleAccount'),
      'type'=>'raw',
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
      'name'=>'account.computedoutstandingbalance',
      'value'=>array($this, 'RenderCheckedOutstandingBalance'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      'header'=>Yii::t('delt', 'Outstanding balance'),
      ),
	),
)); ?>

<div style="display: none">
<p>Grandtotal Debit: <?php echo DELT::currency_value($this->debit_sum, $this->firm->currency) ?>.</p>
<p>Grandtotal Credit: <?php echo DELT::currency_value(-$this->credit_sum, $this->firm->currency) ?>.</p>
</div>
