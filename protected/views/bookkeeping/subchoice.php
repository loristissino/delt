<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Subchoices',
  $subchoice,
);

$this->show_link_on_description = true;

$currency_test_string=DELT::currency_value(3.14, $this->firm->currency); // this will be something like "$3.14" or "US$ 3,14", depending on the locale;

$cs = Yii::app()->getClientScript();  

$cs->registerScriptFile(
  Yii::app()->request->baseUrl.'/js/accounting.min.js',
  CClientScript::POS_HEAD
);

$cs->registerScript(
  'event-handler',
  '

    var decimal_separator = "' . $currency_test_string . '".replace(/[^d\.,]/g, "");
    var thousand_separator = decimal_separator=="." ? ",":".";
    var currency = "' . $currency_test_string . '".replace(/[\d\.,]/g, "");
    
    var dr_text = "' . Yii::t('delt', 'Dr.<!-- outstanding balance -->') . '";
    var cr_text = "' . Yii::t('delt', 'Cr.<!-- outstanding balance -->') . '";

    function computeTotalAmountOfSelectedPostings() {
      var amount = 0;
      $(".select-on-check").each(function(i, obj)
      {
        if($(obj).attr("checked"))
        {
          var account_id = $(obj).context.value;
          var value = parseFloat($("#posting_"+account_id).attr("data-rawvalue"));
          amount += value;
        }
      }
      );
      return amount;
    }
    
    $(".select-on-check").click(function(obj) {
      var amount = computeTotalAmountOfSelectedPostings();
      var text = "";
      if (amount)
      {
        text = amount>0 ? dr_text : cr_text;
        text += " " + accounting.formatMoney(amount>0?amount:-amount, currency, 2, thousand_separator, decimal_separator);
      }
      $("#selected_accounts_balance").html(text);
      }
    );


'
  ,
  CClientScript::POS_READY
);


?>
<h1><?php echo Yii::t('delt', 'Report for «{subchoice}»', array('{subchoice}'=>$subchoice)) ?></h1>


<?php 


$this->widget('zii.widgets.grid.CGridView', array(
  'dataProvider'=>$model->getJournalentriesAsDataProviderForSubchoice($subchoice),
  'summaryText'=>'',
  'selectableRows'=>2, // multiple rows can be selected
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
      ),
    array(
      'class'=>'CCheckBoxColumn',
      'id'=>'id',
      'value'=>'$data->id',
      'footer'=>DELT::currency_value(0, $this->firm->currency),
      'footerHtmlOptions'=>array('class'=>'currency grandtotal', 'id'=>'selected_accounts_balance'),
      ),
    )
  )
);
  
?>
