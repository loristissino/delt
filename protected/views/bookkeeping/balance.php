<?php
/* @var $this BookkeepingController */

$currency_test_string=DELT::currency_value(3.14, $this->firm->currency); // this will be something like "$3.14" or "US$ 3,14", depending on the locale;

$cs = Yii::app()->getClientScript();  

$cs->registerScriptFile(
  Yii::app()->request->baseUrl.'/js/accounting.min.js',
  CClientScript::POS_HEAD
);

$cs->registerScript(
  'event-handler',
  '
    $(".totalcolumn").hide();
    
    $("#togglecolumns").click(function() {
      $(".totalcolumn").toggle();
      }
    );
    
    var decimal_separator = "' . $currency_test_string . '".replace(/[^d\.,]/g, "");
    console.log("decimal separator: " + decimal_separator);
    var thousand_separator = decimal_separator=="." ? ",":".";
    var currency = "' . $currency_test_string . '".replace(/[\d\.,]/g, "");
    
    var dr_text = "' . Yii::t('delt', 'Dr.<!-- outstanding balance -->') . '";
    var cr_text = "' . Yii::t('delt', 'Cr.<!-- outstanding balance -->') . '";

    function computeTotalAmountOfSelectedAccounts() {
      var amount = 0;
      $(".select-on-check").each(function(i, obj)
      {
        console.log($(obj));
        if($(obj).attr("checked"))
        {
          console.log("entro");
          var account_id = $(obj).context.value;
          var value = parseFloat($("#account_"+account_id).attr("data-rawvalue"));
          console.log(value);
          amount += value;
        }
      }
      );
      return amount;
    }
    
    $(".select-on-check").click(function(obj) {
      var amount = computeTotalAmountOfSelectedAccounts(0);
      var text = "";
      if (amount)
      {
        text = amount>0 ? dr_text : cr_text;
        text += " " + accounting.formatMoney(amount>0?amount:-amount, currency, 2, thousand_separator, decimal_separator);
      }
      $("#selected_accounts_balance").html(text);
      
    }
    );

    $(".select-on-check-all").click(function(obj) {
      $("#selected_accounts_balance").text("");
    }
    );

  '
  ,
  CClientScript::POS_READY
);



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
      'htmlOptions'=>array('class'=>'currency totalcolumn'),
      'header'=>Yii::t('delt', 'Total Debit'),
      'footer'=>DELT::currency_value($totaldebits, $this->firm->currency),
      'headerHtmlOptions'=>array('class'=>'totalcolumn'),
      'footerHtmlOptions'=>array('class'=>'currency grandtotal totalcolumn'),
      ),
    array(
      'class'=>'CDataColumn',
      'name'=>'account.creditgrandtotal',
      'value'=>array($this, 'RenderSingleCredit'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency totalcolumn'),
      'header'=>Yii::t('delt', 'Total Credit'),
      'footer'=>DELT::currency_value($totalcredits, $this->firm->currency),
      'headerHtmlOptions'=>array('class'=>'totalcolumn'),
      'footerHtmlOptions'=>array('class'=>'currency grandtotal totalcolumn'),
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
      'footer'=>DELT::currency_value(0, $this->firm->currency),
      'footerHtmlOptions'=>array('class'=>'currency grandtotal', 'id'=>'selected_accounts_balance'),
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

<div id="operations">
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
<p><?php echo Yii::t('delt', 'For the columns of totals:') ?> <span id="togglecolumns"><?php echo CHtml::link(Yii::t('delt', 'toggle visibility'), "#") ?></span></p>
</div>
