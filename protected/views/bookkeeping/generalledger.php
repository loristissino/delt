<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'General Ledger',
);

$this->show_link_on_description = true;

?>
<h1><?php echo Yii::t('delt', 'General Ledger') ?></h1>

<?php /*$this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'firm-grid',
  'dataProvider'=>$account->getPostingsAsDataProvider(),
  'columns'=>array(
    array(
      'class'=>'CDataColumn',
      'name'=>'date',
      'value'=>array($this, 'RenderDate'),
      'type'=>'raw',
      'header'=>Yii::t('delt', 'Date'),
      ),
    array(
      'name'=>'journalentry.description',
      'header'=>Yii::t('delt', 'Description'),
      'value'=>array($this, 'RenderDescriptionForLedger'),
      'footer'=>Yii::t('delt', 'Sum') . '<br />' . Yii::t('delt', 'Outstanding balance'),
      'cssClassExpression'=>'$data->journalentry->is_closing? \'closing\' : \'\'',
      'type'=>'raw',
      ),
    array(
      'class'=>'CDataColumn',
      'name'=>'debit',
      'value'=>array($this, 'RenderDebit'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      'footer'=>DELT::currency_value($debitgrandtotal, $this->firm->currency) . '<br/>' . ($grandtotal>0 ? '<span class="outstanding_balance">' . DELT::currency_value($grandtotal, $this->firm->currency) . '</span>' : '&nbsp;'),
      'footerHtmlOptions'=>array('class'=>'currency grandtotal'),
      ),
    array(
      'class'=>'CDataColumn',
      'name'=>'credit',
      'value'=>array($this, 'RenderCredit'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      'footer'=>DELT::currency_value(-$creditgrandtotal, $this->firm->currency) . '<br/>' . ($grandtotal<0 ? '<span class="outstanding_balance">' . DELT::currency_value(-$grandtotal, $this->firm->currency) . '</span>' : '&nbsp;'),
      'footerHtmlOptions'=>array('class'=>'currency grandtotal'),
      ),
  ),
)); */?>

<?php foreach($model->getLedgerDataCache() as $code=>$value): 
  $totaldebit = $model->getLedgerDataTotalDebit($code);
  $totalcredit = $model->getLedgerDataTotalCredit($code);
  $grandtotal = $totaldebit-$totalcredit;
  $this->last_journalentry_id = null;
?>

<h2><?php echo $code ?> - <?php echo $value['currentname'] ?></h2>

<?php $this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'firm-grid',
  'summaryText'=>'',
  'dataProvider'=>$model->getLedgerDataAsDataProvider($code),
  'columns'=>array(
    array(
      'class'=>'CDataColumn',
      'name'=>'date',
      'value'=>array($this, 'RenderDate'),
      'type'=>'raw',
      'header'=>Yii::t('delt', 'Date'),
      ),
    array(
      'name'=>'description',
      'header'=>Yii::t('delt', 'Description'),
      'value'=>array($this, 'RenderDescription'),
      'footer'=>Yii::t('delt', 'Sum') . '<br />' . Yii::t('delt', 'Outstanding balance'),
      'cssClassExpression'=>'$data->journalentry->is_closing? \'closing\' : \'\'',
      'type'=>'raw',
      ),
    array(
      'class'=>'CDataColumn',
      'name'=>'debit',
      'value'=>array($this, 'RenderDebit'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      'footer'=>DELT::currency_value($totaldebit, $this->firm->currency) . '<br/>' . ($grandtotal>0 ? '<span class="outstanding_balance">' . DELT::currency_value($grandtotal, $this->firm->currency) . '</span>' : '&nbsp;'),
      'footerHtmlOptions'=>array('class'=>'currency grandtotal'),
      ),
    array(
      'class'=>'CDataColumn',
      'name'=>'credit',
      'value'=>array($this, 'RenderCredit'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      'footer'=>DELT::currency_value($totalcredit, $this->firm->currency) . '<br/>' . ($grandtotal<0 ? '<span class="outstanding_balance">' . DELT::currency_value(-$grandtotal, $this->firm->currency) . '</span>' : '&nbsp;'),
      'footerHtmlOptions'=>array('class'=>'currency grandtotal'),
      ),
  ),
)); ?>

<hr />

<?php endforeach ?>
