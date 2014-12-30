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

$this->layout = '//layouts/column1_menu_below';

$totaldebits=$this->firm->getTotalAmounts('D');
$totalcredits=$this->firm->getTotalAmounts('C');

?>
<h1><?php echo Yii::t('delt', 'Trial Balance') ?></h1>

<?php
echo CHtml::beginForm('','post',array('id'=>'balance-form'));

$this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'firm-grid',
  'dataProvider'=>$dataProvider,
  'selectableRows'=>2, // multiple rows can be selected

  'columns'=>array(
    array(
      'name'=>'position',
      'header'=>Yii::t('delt', 'P'),
      'headerHtmlOptions'=>array('title'=>Yii::t('delt', 'Position')),
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
    array(
      'class'=>'CCheckBoxColumn',
      'id'=>'id',
      'value'=>'$data->id',
      ),
  ),
)); 
echo CHtml::endForm();
?>

<div style="display: none">
<p>Grandtotal Debit: <?php echo DELT::currency_value($this->debit_sum, $this->firm->currency) ?>.</p>
<p>Grandtotal Credit: <?php echo DELT::currency_value(-$this->credit_sum, $this->firm->currency) ?>.</p>
</div>

<p><?php echo Yii::t('delt', 'With the selected accounts:') ?>
<?php $this->widget('ext.widgets.bmenu.XBatchMenu', array(
    'formId'=>'balance-form',
    'checkBoxId'=>'id',
//    'ajaxUpdate'=>'person-grid', // if you want to update grid by ajax
    'emptyText'=>addslashes(Yii::t('delt','Please select the entries you would like to perform this action on!')),
    'items'=>array(
        array('label'=>Yii::t('delt','prepare closing entry'),'url'=>array('bookkeeping/prepareentry', 'slug'=>$model->slug, 'op'=>'closing'), 'linkOptions'=>array('title'=>Yii::t('delt', 'Prepare a journal entry that will close the selected accounts'))),
        array('label'=>Yii::t('delt','prepare snapshot entry'),'url'=>array('bookkeeping/prepareentry', 'slug'=>$model->slug, 'op'=>'snapshot'), 'linkOptions'=>array('title'=>Yii::t('delt', 'Prepare a journal entry that will open the selected accounts with the current outstanding balance'))),
    ),
    'htmlOptions'=>array('class'=>'actionBar'),
    'containerTag'=>'span',
));
?></p>
