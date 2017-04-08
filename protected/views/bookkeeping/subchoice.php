<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Subchoices',
  $subchoice,
);

$this->show_link_on_description = true;

?>
<h1><?php echo Yii::t('delt', 'Subchoice Report for «{subchoice}»', array('{subchoice}'=>$subchoice)) ?></h1>


<?php 


$this->widget('zii.widgets.grid.CGridView', array(
  'dataProvider'=>$model->getJournalentriesAsDataProviderForSubchoice($subchoice),
  'summaryText'=>'',
  'columns'=>array(
    array(
      'class'=>'CDataColumn',
      'name'=>'date',
      'value'=>array($this, 'RenderDate'),
      'type'=>'raw',
      'header'=>Yii::t('delt', 'Date'),
      ),
    array(
      'name'=>'account.name',
      'header'=>Yii::t('delt', 'Account'),
      'value'=>array($this, 'RenderAccount'),
      'cssClassExpression'=>'$data->journalentry->is_closing? \'closing\' : \'\'',
      'type'=>'raw',
      ),
    array(
      'class'=>'CDataColumn',
      'name'=>'Debit',
      'header'=>Yii::t('delt', 'Debit'),
      'value'=>array($this, 'RenderDebit'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      ),
    array(
      'class'=>'CDataColumn',
      'name'=>'Credit',
      'header'=>Yii::t('delt', 'Credit'),
      'value'=>array($this, 'RenderCredit'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      )
    )
  )
);
  
?>
