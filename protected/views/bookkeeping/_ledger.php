<?php 

$columns = array(
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
      'footer'=>Yii::t('delt', 'Sum') . '<br />' . Yii::t('delt', 'Ending balance / Balance brought down'),
      'cssClassExpression'=>'$data->journalentry->is_closing? \'closing\' : \'\'',
      'type'=>'raw',
      ),
    );

if ($with_subchoices)
{
  $columns[]=
      array(
      'class'=>'CDataColumn',
      'name'=>'Subchoice',
      'header'=>Yii::t('delt', 'Subchoice'),
      'value'=>array($this, 'RenderSubchoice'),
      'type'=>'raw',
      );
}

$columns[]=
    array(
      'class'=>'CDataColumn',
      'name'=>'Debit',
      'header'=>Yii::t('delt', 'Debit'),
      'value'=>array($this, 'RenderDebit'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      'footer'=>DELT::currency_value($debitgrandtotal, $this->firm->currency) . '<br/>' . ($grandtotal>0 ? '<span class="outstanding_balance">' . DELT::currency_value($grandtotal, $this->firm->currency) . '</span>' : '&nbsp;'),
      'footerHtmlOptions'=>array('class'=>'currency grandtotal'),
      );
      
$columns[]=
    array(
      'class'=>'CDataColumn',
      'name'=>'Credit',
      'header'=>Yii::t('delt', 'Credit'),
      'value'=>array($this, 'RenderCredit'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      'footer'=>DELT::currency_value(-$creditgrandtotal, $this->firm->currency) . '<br/>' . ($grandtotal<0 ? '<span class="outstanding_balance">' . DELT::currency_value(-$grandtotal, $this->firm->currency) . '</span>' : '&nbsp;'),
      'footerHtmlOptions'=>array('class'=>'currency grandtotal'),
      );

$this->widget('zii.widgets.grid.CGridView', array(
  'id'=>$id,
  'dataProvider'=>$dataProvider,
  'summaryText'=>'',
  'columns'=>$columns,
  )
); ?>
