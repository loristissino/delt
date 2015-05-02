<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Journal',
);

$this->menu=array(
  array('label'=>Yii::t('delt', 'New journal entry'), 'url'=>array('bookkeeping/newjournalentry', 'slug'=>$model->slug)),
  array('label'=>Yii::t('delt', 'Use a template'), 'url'=>array('template/admin', 'slug'=>$model->slug)),
  array('label'=>Yii::t('delt', 'Closing entry'), 'url'=>array('bookkeeping/closingjournalentry', 'slug'=>$model->slug)),
  );

if(sizeof($model->journalentries))
{
  $this->menu[] = array('label'=>Yii::t('delt', 'Clear'), 'url'=>$url=$this->createUrl('bookkeeping/clearjournal', array('slug'=>$model->slug)),  'linkOptions'=>array(   
      'submit' => $url,
      'title' => Yii::t('delt', 'Delete all journal entries'),
      'confirm' => Yii::t('delt', 'Are you sure you want to delete all journal entries?'),
      ),
    );  
}

$dp = $this->firm->getJournalentriesAsDataProvider();

?>
<h1><?php echo Yii::t('delt', 'Journal') ?></h1>

<?php if(sizeof($dp->data)): ?>

<?php
echo CHtml::beginForm('','post',array('id'=>'journal-form'));
$this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'firm-grid',
  'dataProvider'=>$this->firm->getJournalentriesAsDataProvider(),
  'selectableRows'=>2, // multiple rows can be selected
  'rowCssClassExpression'=>'"journalentryrow " .($row%2 ? "even" : "odd") . ($data->journalentry->is_included==0 ? " excluded" : "")',
  'rowHtmlOptionsExpression' => 'array("data-transaction-id"=>$data->journalentry->transaction_id)',
  'columns'=>array(
    array(
      'class'=>'CDataColumn',
      'name'=>'journalentry.date',
      'value'=>array($this, 'RenderDate'),
      'type'=>'raw',
      'header'=>Yii::t('delt', 'Date'),
      ),
    array(
      'name'=>'journalentry.description',
      'header'=>Yii::t('delt', 'Description'),
      'value'=>array($this, 'RenderDescription'),
      'cssClassExpression'=>'$data->journalentry->is_closing && $data->rank==1? \'closing\' : \'\'',
      ),
    array(
      'name'=>'journalentry.account',
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
      ),
    array(
      // see http://www.yiiframework.com/wiki/106/using-cbuttoncolumn-to-customize-buttons-in-cgridview/
      'class'=>'CButtonColumn',
      'template'=>'{update}',
      'updateButtonUrl'=>'Yii::app()->controller->createUrl("bookkeeping/updatejournalentry",array("id"=>$data->journalentry_id))',
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
    array(
      'class'=>'ECCheckBoxColumn',
      'id'=>'id',
      'value'=>'$data->journalentry->id',
      'controller'=>$this,
      ),

  ),
)); 
echo CHtml::endForm(); ?>

<p><?php echo Yii::t('delt', 'Apply to the selected journal entries:') ?><br />
<?php 

    $items = array(
        array('label'=>Yii::t('delt','include'),'url'=>array('bookkeeping/updateJournal', 'slug'=>$model->slug, 'op'=>'include'), 'linkOptions'=>array('title'=>Yii::t('delt', 'Include the selected journal entries in computations'))),
        array('label'=>Yii::t('delt','exclude'),'url'=>array('bookkeeping/updateJournal', 'slug'=>$model->slug, 'op'=>'exclude'), 'linkOptions'=>array('title'=>Yii::t('delt', 'Exclude the selected journal entries from computations'))),
        array('label'=>Yii::t('delt','toggle in-statement visibility'),'url'=>array('bookkeeping/updateJournal', 'slug'=>$model->slug, 'op'=>'tisv'), 'linkOptions'=>array('title'=>Yii::t('delt', 'Toggle the visibility of the selected journal entries in the preparation of the statements'))),
        array('label'=>Yii::t('delt','swap positions'),'url'=>array('bookkeeping/updateJournal', 'slug'=>$model->slug, 'op'=>'swap'), 'linkOptions'=>array('title'=>Yii::t('delt', 'Swap the positions of the selected journal entries (choose only two entries for this to work)'))),
    );
    
    if (Yii::app()->user->getState('transaction'))
    {
      $items[] = array('label'=>Yii::t('delt','connect to current transaction'),'url'=>array('bookkeeping/updateJournal', 'slug'=>$model->slug, 'op'=>'connect'), 'linkOptions'=>array('title'=>Yii::t('delt', 'Connect the selected journal entries to the current transaction (from a challenge)')));
  
    }

    $this->widget('ext.widgets.bmenu.XBatchMenu', array(
    'formId'=>'journal-form',
    'checkBoxId'=>'id',
//    'ajaxUpdate'=>'person-grid', // if you want to update grid by ajax
    'emptyText'=>addslashes(Yii::t('delt','Please select the entries you would like to perform this action on!')),
//    'confirm'=>Yii::t('ui','Are you sure to perform this action on checked items?'),
    'items'=> $items,
    'htmlOptions'=>array('class'=>'actionBar'),
    'containerTag'=>'span',
));
?> | 
<?php $this->widget('ext.widgets.bmenu.XBatchMenu', array(
    'formId'=>'journal-form',
    'checkBoxId'=>'id',
//    'ajaxUpdate'=>'person-grid', // if you want to update grid by ajax
    'emptyText'=>addslashes(Yii::t('delt','Please select the entries you would like to perform this action on!')),
    'confirm'=>addslashes(Yii::t('delt','Are you sure to perform this action on checked items?')),
    'items'=>array(
        array('label'=>Yii::t('delt','delete'),'url'=>array('bookkeeping/updateJournal', 'slug'=>$model->slug, 'op'=>'delete'), 'linkOptions'=>array('title'=>Yii::t('delt', 'Delete permanently the selected journal entries'))),
    ),
    'htmlOptions'=>array('class'=>'actionBar'),
    'containerTag'=>'span',
));
?>

</p>
<?php else: ?>
<p>
<?php echo Yii::t('delt', 'This firm does not have any journal entry yet.') ?> 
<?php echo CHtml::link(Yii::t('delt', 'Create a new one now.'), array('bookkeeping/newjournalentry', 'slug'=>$model->slug)) ?>
</p>
<?php endif ?>
