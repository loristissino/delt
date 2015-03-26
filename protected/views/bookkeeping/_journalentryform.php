<?php
/* @var $this BookkeepingController */
/* @var $model Firm */
/* @var $form CActiveForm */

// a quick and dirty function to help setting widths of the table -- FIXME should be improved
function printWidth($col)
{
  $widths = array('icons'=>40, 'account'=>430, 'debit'=>100, 'credit'=>100);
  if($col=='total')
  {
    $w = 0; foreach($widths as $n) $w+=$n;
  }
  else
  {
    $w = $widths[$col];
  }
  echo  'style="width: ' . $w. 'px"';
}

//$this->layout = 'column1_menu_below';
$this->layout = 'column2';

$n = sizeof($items);

$choose_icon=addslashes($this->createIcon('choose', Yii::t('delt', 'Choose'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'Choose'))));
$delete_icon=addslashes($this->createIcon('delete', Yii::t('delt', 'Delete'), array('width'=>16, 'height'=>16, 'style'=>'padding-top: 0px;', 'title'=>Yii::t('delt', 'Delete row # {n}'))));

$raw_input_icon=addslashes($this->createIcon('text_align_left', Yii::t('delt', 'Raw input'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 8px;', 'title'=>Yii::t('delt', 'Switch to raw input mode'))));
$textfields_icon=addslashes($this->createIcon('application_form', Yii::t('delt', 'Text fields'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 0px;', 'title'=>Yii::t('delt', 'Switch to text fields mode'))));
$load_accounts_icon=addslashes($this->createIcon('table_go', Yii::t('delt', 'Load accounts'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 0px;', 'title'=>Yii::t('delt', 'Load all accounts'))));
$sort_icon=addslashes($this->createIcon('sortdc', Yii::t('delt', 'Sort postings'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 8px;', 'title'=>Yii::t('delt', 'Sort postings, debits first'))));
$explain_icon=addslashes($this->createIcon('analyze', Yii::t('delt', 'Analyze the transaction'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 8px;', 'title'=>Yii::t('delt', 'Analyze the transaction'))));
$swap_debits_credits_icon=addslashes($this->createIcon('arrows', Yii::t('delt', 'Swap debits and credits'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 8px;', 'title'=>Yii::t('delt', 'Swap debit and credits for the whole journal entry'))));
$help_icon=addslashes($this->createIcon('help', Yii::t('delt', 'Show usage help'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 8px;', 'title'=>Yii::t('delt', 'Show usage help'))));

$calculator_icon = addslashes(Yii::app()->request->baseUrl.'/images/calculator.png');

$json_url_sa = addslashes($this->createUrl('bookkeeping/suggestaccount', array('slug'=>$this->firm->slug)));
$json_url_aca = addslashes($this->createUrl('bookkeeping/accountclosingamount', array('slug'=>$this->firm->slug)));

$currency_test_string=DELT::currency_value(3.14, $this->firm->currency); // this will be something like "$3.14" or "US$ 3,14", depending on the locale;

$placeholder_string = addslashes(Yii::t('delt', 'Start typing (code or name) or double-click...'));

$unload_alert_string = addslashes(Yii::t('delt', 'There might be some unsaved changes in the form.'));

$create_template_string = addslashes(Yii::t('delt', 'Create Template'));

$spacer = $this->createIcon('tp', '', array('height'=>1, 'width'=>60, 'style'=>''));

$cs = Yii::app()->getClientScript();  
$cs->registerScript(
  'swap-rows-handler',
  '

  var decimal_separator = "' . $currency_test_string . '".replace(/[^d\.,]/g, "");
  console.log("decimal separator: " + decimal_separator);
  var thousand_separator = decimal_separator=="." ? ",":".";
  var currency = "' . $currency_test_string . '".replace(/[\d\.,]/g, "");

  var view_as_textfields = true;
  var n = ' . $n . ';
  var vn = n;
  var current = 0;
  var fields = new Array("name", "debit", "credit");

  var raw_input_icon = "' . $raw_input_icon . '";
  var textfields_icon = "' . $textfields_icon . '";
  var load_accounts_icon = "' . $load_accounts_icon . '";
  var sort_icon = "' . $sort_icon . '";
  var explain_icon = "' . $explain_icon . '";
  var help_icon = "' . $help_icon . '";
  var swap_debits_credits_icon = "' . $swap_debits_credits_icon . '";
  
  var identifier = "'. $journalentryform->identifier . '";
  
  var check_dirty = true;
  var clear_local_storage_on_exit = true;
  
  $("#commands").html(
    "<span id=\"toggle\">" + raw_input_icon + 
    "</span>&nbsp;<span id=\"sort_accounts\">" + sort_icon + "</span>" +
    "</span>&nbsp;<span id=\"swap_debits_credits\">" + swap_debits_credits_icon + "</span>" + 
    "</span>&nbsp;<span id=\"explain\">" + explain_icon + "</span>" +
    "</span>&nbsp;<span id=\"help\">" + help_icon + "</span>" +
    "</span>&nbsp;<span id=\"load_accounts\">" + load_accounts_icon + "</span>"

    );
  $("#load_accounts").hide();
  
  $("#analysis").hide();
  
  $("#template_button").after("<a id=\"template_link\" href=\"#\">' . $create_template_string . '</a>");
  $("#template_button").hide();
  $("#template_link").click(function() {
    var input = $("<input>").attr("type", "hidden").attr("name", "template").val("");
    console.log("submitting...");
    check_dirty=false;
    clear_local_storage_on_exit = true;
    $("#journalentryform").append($(input)).submit();
  });
  

  updatetotals();
  
  form2localstorage();
  
  $("#explain").click(function()
    {
      $("#analysis").toggle(500);
    }
  );

  $("#help").click(function()
    {
      $("#usage").toggle(500);
    }
  );
  
  $("#load_accounts").click(function()
    {

      var jsonUrl = "' . $json_url_sa . '";
      $.getJSON(
        jsonUrl,
        {},
        function (json)
          {
            var value="";
            $.each(json, function()
              {
                value += this + "\n";
            }
            );
            $("#raw_input").val(value);
            $("#raw_input").attr("rows", json.length);
            $("#load_accounts").hide();
          }
        );
      return false;
    }
  );
  
  $("#toggle").click(function()
    {
      if(view_as_textfields)
      {
        toTextArea();
      }
      else
      {
        fromTextArea();
      }
      $("#rows_as_textfields").toggle(500);
      $("#rows_as_textarea").toggle(500);
      view_as_textfields = ! view_as_textfields;
    }
  );

  $("#sort_accounts").click(function()
    {

      var arr = [];
      for (i=1; i<=n; i++)
      {
        cleanValue($("#debit"+i));
        cleanValue($("#credit"+i));
        var obj = {
          name: $("#name"+i).val(),
          debit: $("#debit"+i).val().replace(/[^\d.,]/g, ""),
          credit: $("#credit"+i).val().replace(/[^\d.,]/g, ""),
          content: ($("#debit"+i).val()!="" ? "A" : "B") + $("#name"+i).val()
          };
        arr.push(obj);
      }
      arr.sort(function(a,b) {return a.content < b.content ? -1: 1 });
      var i=1;
      $.each(arr, function()
        {
          $("#name" + i).val(this.name);
          putFormattedValue($("#debit" + i), this.debit, false);
          putFormattedValue($("#credit" + i), this.credit, false);
          i++;
        }
      )
    }
  );

  $("#swap_debits_credits").click(function()
    {

      var arr = [];
      var d;
      var c;
      for (i=1; i<=n; i++)
      {
        d = $("#debit"+i).val();
        c = $("#credit"+i).val();
        $("#debit"+i).val(c);
        $("#credit"+i).val(d);
      }
    }
  );
  
  function toTextArea()
  {

    var text="";
    for(i=1; i<=n; i++)
    {
      var name = $("#name"+i).val();
      var debit = $("#debit"+i).val().replace(/[^\d.,]/g, "");
      var credit = $("#credit"+i).val().replace(/[^\d.,]/g, "");
      if (name || debit || credit)
      {
        text += $("#name"+i).val() + "\t" + debit + "\t" + credit + "\t\n";
      }
    }
    $("#raw_input").val(text);
    $("#toggle").html(textfields_icon);
    if(text=="")
    {
      $("#load_accounts").show();
    }
    else
    {
      $("#load_accounts").hide();
    }
    $("#sort_accounts").hide();
    $("#explain").hide();
    $("#swap_debits_credits").hide();
    
  }
  
  function fromTextArea()
  {

    $("#load_accounts").hide();
    $("#sort_accounts").show();
    $("#explain").show();
    $("#swap_debits_credits").show();
    var text = $("#raw_input").val();
    $("#toggle").html(raw_input_icon);
    
    lines = text.replace(/\n$/).split(/\n/);
    if(lines.length<=n)
    {
      for(i=0; i<lines.length; i++)
      {
        data = lines[i].split(/\t/);
        console.log("Evaluating line: " + (i+1));
        if(data.length>=3)
        {
          var name = data[0];
          var debit = accounting.unformat(data[1], decimal_separator);
          console.log("debit: " + debit);
          var credit = accounting.unformat(data[2], decimal_separator);
          console.log("credit: " + credit);
          placeValue(i+1, credit-debit);
          $("#debit" + (i+1)).removeClass("error");
          $("#credit" + (i+1)).removeClass("error");
          $("#name" + (i+1)).val(name);
          console.log("Value inserted: " + (debit-credit));
        }
        else
        {
          $("#name" + (i+1)).val("");
          $("#debit" + (i+1)).val("");
          $("#credit" + (i+1)).val("");
          console.log("Value null");
        }
      }
      // we clean the remaining lines
      for(k=i+1; k<=n; k++)
      {
        $.each(fields, function()
          {
            $("#" + this + k).val("");
          }
        );
      }
      text = $("#raw_input").val("");
      updatetotals();

    }
    else  // we have extra lines, we need to do a post...
    {
      dirty = false;
      $("form#journalentryform").submit();
    }
    
    
  }
  
  addIcons();
  addEventManagers();

  
  function addIcons()
  {
    for(i=1; i<= n; i++)
    {
      var text_delete_icon = "' . $delete_icon  .'".replace("{n}", i);
    
      $("#chooseicon" +  i).html("<span id=\'choose" + i +"\'>' . $choose_icon . '</span>");
      $("#deleteicon" +  i).html("<span class=\"deletebutton\" id=\'delete" + i +"\'>" + text_delete_icon + "</span>");
      $("#delete" + i).hide();
    }
  }


  function showDeleteButton(index)
  {
    $(".deletebutton").hide();
    if(vn > 2)
    {
      $("#delete"+index).show();
    }
    return false;
  }

  function addEventManagers()
  {
    var done=false;
    
    for(i=1; i<= n; i++)
    {
      $("#delete"+i).click((function(index)
        {
          return function()
          {
            if(vn>2)
            {
              $("#name" + index).val("");
              $("#debit" + index).val(0);
              $("#credit" + index).val(0);
              $("#row" + index).remove();
              vn--;
              updatetotals();
              return false;
            }
          }
        })(i));
    
      $("#name" +i).dblclick((function(index)
        {
          return function()
          {
            row_number = index;
            $("#chooseaccountdialog").dialog("open");
            showDeleteButton(index);
            return false;
          }
        })(i));

      $("#name" +i).mouseover((function(index)
        {
          return function()
          {
            showDeleteButton(index);
          }
        })(i));

      $("#choose" +i).click((function(index)
        {
          return function()
          {
            row_number = index;
            $("#chooseaccountdialog").dialog("open");
            showDeleteButton(index);
            return false;
          }
        })(i));
        
      if(!$("#name" +i).val() && !done)
      {
        $("#name" +i).attr("placeholder", "'. $placeholder_string . '");
        done=true;
      }

      $("#debit" +i).blur(function() {updatetotals(true); });
      $("#debit" +i).focus(function(obj) { cleanAndPutValue($(obj.target)); });
      $("#debit" +i).attr("_row", i);
      $("#credit" +i).blur(function() {updatetotals(true); });
      $("#credit" +i).focus(function(obj) { cleanAndPutValue($(obj.target)); });
      $("#credit" +i).attr("_row", i);
      $("#debit" +i).calculator({ showOn: "operator", isOperator: checkCh});
      $("#credit" +i).calculator({ showOn: "operator", isOperator: checkCh});

    }
  }
  function checkCh(ch, event, value, base, decimalChar) {
    var source = event.target;
    var row = $("#"+source.id).attr("_row");
    var chars = ch + $("#debit" + row).val() + $("#credit" + row).val();
    if(chars == "=")
    {
      // we do this only if the debit field has an equal sign, and the credit field is empty, or viceversa
      updatetotals();
      var value = total_debit - total_credit;
      placeValue(row, value);
      updatetotals();
      return false;
    }
    
    if(chars == "?")
    {
      var name = $("#name"+row).val();
      var code = name.substring(0, name.indexOf(" "));
      $("#debit"+row).attr("placeholder", "⌛").addClass("updating");
      $("#credit"+row).attr("placeholder", "⌛").addClass("updating");
      var posting_id = $("#row"+row).attr("data-posting-id");
      var jsonUrl = "' . $json_url_aca . '?code=" + code + "&posting=" + posting_id;
      $.getJSON(
        jsonUrl,
        {},
        function (json)
          {
            placeValue(row, json.amount);
          }
        );
      return false;
    }
        
    return "+-*/".indexOf(ch) > -1 && !(ch === "-" && value === ""); 
  }
  
  function placeValue(row, value)
  {
    $("#debit" + row).val("").attr("placeholder", "").removeClass("updating");
    $("#credit" + row).val("").attr("placeholder", "").removeClass("updating");

    var affected;

    if (value >= 0)
    {
      putFormattedValue($("#credit" + row), value, true);
    }
    else if (value < 0)
    {
      putFormattedValue($("#debit" + row), -value, true);
    }
  }
  
  function putFormattedValue(element, value, addclass)
  {
    element.val(value ? accounting.formatMoney(value, currency, 2, thousand_separator, decimal_separator): "");
    if(addclass)
    {
      element.addClass("flashed");
    }
  }
  
  function cleanValue(element)
  {
    var value = accounting.unformat(element.val(), decimal_separator);
    element.val(value ? accounting.unformat(element.val(), decimal_separator): "");
  }
  
  function cleanAndPutValue(element)
  {
    cleanValue(element);
    var v = element.val() ? accounting.formatNumber(element.val(), 2, "", decimal_separator) : "";
    element.val(v);
    console.log("cleaned " + element.attr("id") + " value: " + v);
  }
  
  function swaprows(a,b)
  {
    $.each(fields, function()
      {
        swapcontent("#" + this + a, "#" + this +b);
      }
    );
  }
  
  function swapcontent(a, b)
  {
    var c=$(a).val();
    $(a).val($(b).val());
    $(b).val(c);
  }
  
  function updatetotals(removeClass)
  {
    var rc = (typeof removeClass === "undefined") ? false : true;
  
    total_debit=0;
    total_credit=0;
    
    var debit;
    var credit;
    for(i=1; i<=n; i++)
    {
      if($("#row"+i).length)
      {
        debit = accounting.unformat($("#debit"+i).val(), decimal_separator);
        credit = accounting.unformat($("#credit"+i).val(), decimal_separator);
        total_debit += debit;
        total_credit += credit;
        $("#debit"+i).val(debit ? accounting.formatMoney(debit, currency, 2, thousand_separator, decimal_separator) : "");
        $("#credit"+i).val(credit ? accounting.formatMoney(credit, currency, 2, thousand_separator, decimal_separator) : "");
        if(rc)
        {
          $("#debit"+i).removeClass("flashed");
          $("#credit"+i).removeClass("flashed");
        }
      }
    }
    
    if(total_debit == total_credit)
    {
      $("#td_total_debit").addClass("valuesok").removeClass("valueswrong");
      $("#td_total_credit").addClass("valuesok").removeClass("valueswrong");
    }
    else
    {
      $("#td_total_debit").removeClass("valuesok").addClass("valueswrong");
      $("#td_total_credit").removeClass("valuesok").addClass("valueswrong");
    }
    
    $("#total_debit").html(accounting.formatMoney(total_debit, currency, 2, thousand_separator, decimal_separator));
    $("#total_credit").html(accounting.formatMoney(total_credit, currency, 2, thousand_separator, decimal_separator));
    
  }

  function supports_html5_storage()
  {
    try {
      return "localStorage" in window && window["localStorage"] !== null;
    } catch (e) {
      return false;
    }
  }
  
  function form2localstorage()
  {
    if(!supports_html5_storage())
      return;
    
    if(localStorage[identifier])
      return;
    
    var original_form = {
      date: $("#JournalentryForm_date").val(),
      description: $("#JournalentryForm_description").val(),
      postings: []
      }
    
    for(var i=1; i<=n; i++)
    {
      original_form.postings.push({
        account: $("#name"+i).val(),
        debit: accounting.unformat($("#debit"+i).val(), decimal_separator),
        credit: accounting.unformat($("#credit"+i).val(), decimal_separator)
      });
    }
    
    localStorage[identifier] = JSON.stringify(original_form);
  }
  
  $(window).bind("beforeunload", function(e) {
    if(!supports_html5_storage()  || !check_dirty)
      return;

    var original_form = JSON.parse(localStorage[identifier]);
    
    var dirty = original_form.date!=$("#JournalentryForm_date").val()
      || original_form.description!=$("#JournalentryForm_description").val()
      || original_form.postings.length!=n;
    
    for(var i=1; !dirty && i<=n ; i++)
    {
      dirty |= original_form.postings[i-1].account!=$("#name"+i).val();
      dirty |= original_form.postings[i-1].debit!=accounting.unformat($("#debit"+i).val(), decimal_separator);
      dirty |= original_form.postings[i-1].credit!=accounting.unformat($("#credit"+i).val(), decimal_separator);
    }
    
    if(dirty)
    {
      return "' . $unload_alert_string . '";
    }
  });
  
  function prepareExit()
  {
    if(clear_local_storage_on_exit)
      localStorage.removeItem(identifier);
  }
  
  $(window).bind("unload", prepareExit);
  
  $("#save_button").click(function(e) {check_dirty=false; clear_local_storage_on_exit = true; })
  $("#addline_button").click(function(e) {check_dirty=false; clear_local_storage_on_exit = false; })
  $("#done_button").click(function(e) {check_dirty=false; clear_local_storage_on_exit = true; })
  $("#new_button").click(function(e) {check_dirty=false; clear_local_storage_on_exit = true; })
    
  '
/*
    $(".form").bind("keyup", "ctrl-u", function()
    {
      console.log("pressed key up");
    }
  );
*/
  
  ,
  CClientScript::POS_READY
);

$cs->registerScript(
  'dialog-handler',
  '
  
  var row_number;
  
  function chooseAccount(name)
  {
    $("#name" + row_number).val(name);
    $("#chooseaccountdialog").dialog("close");
  }

  ',
  CClientScript::POS_HEAD
);

$cs->registerScriptFile(
  Yii::app()->request->baseUrl.'/js/accounting.min.js',
  CClientScript::POS_HEAD
);

$cs->registerScriptFile(
  Yii::app()->request->baseUrl.'/js/jquery.plugin.min.js',
  CClientScript::POS_HEAD
);

$cs->registerScriptFile(
  Yii::app()->request->baseUrl.'/js/calculator/jquery.calculator.min.js',
  CClientScript::POS_HEAD
);

if(Yii::app()->language!=='en')
{
  $cs->registerScriptFile(
    Yii::app()->request->baseUrl.'/js/calculator/jquery.calculator-' . Yii::app()->language . '.js',
    CClientScript::POS_HEAD
  );
}
?>

<div class="form" style="width: 700px">

<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'journalentryform',
  'enableAjaxValidation'=>false,
  'focus'=> (isset($journalentryform->journalentry) ? null : array($journalentryform, 'description')),
  'action'=>$this->form_action_required, //array('bookkeeping/' . ($journalentryform->journalentry? 'updatejournalentry': 'newjournalentry'), 'slug'=>$journalentryform->firm->slug),
)); ?>

  <p class="note">
    <?php echo Yii::t('delt', 'Fields with <span class="required">*</span> are required.') ?><br />
    <?php echo Yii::t('delt', 'The lines in which the account field is empty are ignored.') ?>
  </p>

  <?php echo $form->errorSummary($journalentryform, Yii::t('delt', 'Please fix the following errors:')); ?>

  <div class="row">
    <?php echo $form->labelEx($journalentryform,'date'); ?>
    <?php // echo $form->textField($journalentryform,'date'); ?>
    
    <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
      'name'=>'JournalentryForm[date]',
      'value'=>$journalentryform->date,
      'language'=>Yii::app()->language,
      'options'=>array(
          'showAnim'=>'fold', // 'show' (the default), 'slideDown', 'fadeIn', 'fold'
          'showOn'=>'both', // 'focus', 'button', 'both'
          'buttonText'=>Yii::t('delt','Select date from calendar'),
          'buttonImage'=>Yii::app()->request->baseUrl.'/images/calendar.png',
          'buttonImageOnly'=>true,
      ),
      'htmlOptions'=>array(
          'style'=>'width:80px;vertical-align:top',
          'class'=>'datepicker',
      ),
    ));
   ?>
    <?php echo $form->error($journalentryform,'date'); ?>
  </div>

  <div class="row">
    <?php echo $form->labelEx($journalentryform,'description'); ?>
    <?php echo $form->textField($journalentryform,'description', array('size'=>80)); ?>
    <?php echo $form->error($journalentryform,'description'); ?>
  </div>

  <div id="commands"></div>

  <div id="rows_as_textfields" style="display: block">
    <div class="accountsrows">
      <table id="postings" <?php printWidth('total') ?>>
      <thead>
      <tr><th <?php printWidth('icons') ?>>&nbsp;</th><th <?php printWidth('account') ?>><?php echo Yii::t('delt', 'Account') ?></th><th <?php printWidth('debit') ?>><?php echo Yii::t('delt', 'Debit') ?></th><th <?php printWidth('credit') ?>><?php echo Yii::t('delt', 'Credit') ?></th></tr>
      </thead>
      <tfoot>
      <tr><th <?php printWidth('icons') ?>>&nbsp;</th><th <?php printWidth('account') ?>><?php echo Yii::t('delt', 'Sum') ?></th><th  <?php printWidth('debit') ?> class="currency <?php echo $class=$journalentryform->total_debit==$journalentryform->total_credit? 'valuesok':'valueswrong' ?>" id="td_total_debit"><span id="total_debit"><?php echo DELT::currency_value($journalentryform->total_debit, $this->firm->currency) ?></span></th><th  <?php printWidth('credit') ?> class="currency <?php echo $class ?>" id="td_total_credit"><span id="total_credit"><?php echo  DELT::currency_value($journalentryform->total_credit, $this->firm->currency) ?></span></th></tr>
      </tfoot>
      <tbody>
      <?php $row=0; foreach($items as $i=>$item): ?>
      <tr id="row<?php echo ++$row ?>" data-posting-id="<?php echo $journalentryform->journalentry? $i : 0 ?>">
      <td class="number" <?php printWidth('icons') ?>>
      <span id="chooseicon<?php echo $row ?>"></span>
      </td>
      <td  <?php printWidth('account') ?> style="width: 450px;"><?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
        'id'=>'name'.$row,
        'name'=>"PostingForm[$i][name]",
        'value'=>$item->name,
        'source'=>$this->createUrl('bookkeeping/suggestaccount', array('slug'=>$this->firm->slug)),
         'options'=>array(
          'delay'=>200,
          'minLength'=>2,
          ),
        'htmlOptions'=>array(
           'size'=>'50',
           'class'=>$item->name_errors ? 'error': 'valid',
           ),
        ))
      ?><span id="deleteicon<?php echo $row ?>"></span>
</td>
      <td <?php printWidth('debit') ?>><?php echo CHtml::activeTextField($item,"[$i]debit", array('size'=> 10, 'id'=>'debit'.$row, 'class'=>'currency ' . ($item->debit_errors ? 'error': 'valid') . ($item->guessed ? ' guessed': '') . ( $this->accounts[$row]['debitfromtemplate'] ? ' fromtemplate': ''))) ?></td>
      <td <?php printWidth('credit') ?>><?php echo CHtml::activeTextField($item,"[$i]credit", array('size'=> 10, 'id'=>'credit'.$row, 'class'=>'currency ' . ($item->credit_errors ? 'error': 'valid') . ($item->guessed ? ' guessed': ''). ( $this->accounts[$row]['creditfromtemplate'] ? ' fromtemplate': ''))) ?></td>
      </tr>
      <?php endforeach; ?>
      
      </tbody>
      </table>
    </div><!--accountsrows -->

    <?php echo CHtml::activeHiddenField($journalentryform, 'is_closing', array('value'=>$journalentryform->is_closing)) ?>
    <?php if ($journalentryform->adjustment_checkbox_needed): ?>
    <div class="row">
      <?php echo $form->labelEx($journalentryform, 'options') ?>
      <?php echo $form->checkBox($journalentryform, 'is_adjustment', array('checked'=>$journalentryform->is_adjustment)) ?>&nbsp;<?php echo Yii::t('delt', 'Mark this journal entry as adjustment, thus allowing exceptions in debit/credit checks') ?>
    </div>
    <?php endif ?>
    <div class="row buttons">
      <?php echo CHtml::submitButton(Yii::t('delt', 'Add a line'), array('name'=>'addline', 'id'=>'addline_button')); ?>
      <?php echo CHtml::submitButton(Yii::t('delt', 'Save'), array('name'=>'save', 'id'=>'save_button')); ?>
      <?php echo CHtml::submitButton(Yii::t('delt', 'Save & Close'), array('name'=>'done', 'id'=>'done_button')); ?>
      <?php echo CHtml::submitButton(Yii::t('delt', 'Save & New'), array('name'=>'new', 'id'=>'new_button')); ?>
      <?php if(!$journalentryform->journalentry): ?>
        <?php echo CHtml::submitButton(Yii::t('delt', 'Create Template'), array('name'=>'template', 'id'=>'template_button')); ?>
      <?php endif ?>
    </div>
  </div><!-- rows_as_textfields -->
  <div id="rows_as_textarea" style="display: none">
    <div class="row">
      <?php echo $form->labelEx($journalentryform, 'raw_input'); ?>
      <br />
      <span class="hint">
      <?php echo Yii::t('delt', 'Copy the contents of the text area to a spreadsheet (fields are separated by tabs), and edit the data there (if the text area is empty, you can click on the "Load all accounts" icon above to load all available accounts).')?><br />
      <?php echo Yii::t('delt', 'When you are done with the spreadsheet, paste here the three columns (name, debit and credit), and switch to text fields mode.')?>
      </span>
      <br />
      <?php echo $form->textArea($journalentryform, 'raw_input', array('id'=>'raw_input', 'maxlength' => 10000, 'rows' => $n, 'cols' => 65)); ?>
      <?php echo $form->error($journalentryform,'raw_input'); ?>
    </div>
  </div><!-- rows_as_textarea -->

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php echo $this->renderPartial('_usage_help', array('section'=>'journalentry', 'display'=>'none', 'title'=>'Usage Help', 'class'=>'help')) ?>

<?php
$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
    'id'=>'chooseaccountdialog',
    // additional javascript options for the dialog plugin
    'options'=>array(
        'title'=>Yii::t('delt', 'Choose an account'),
        'autoOpen'=>false,
    ),
));

echo $this->renderPartial('_accounts_tree');

$this->endWidget('zii.widgets.jui.CJuiDialog'); ?>


<div id="analysis" style="display: none">
<h2><?php echo Yii::t('delt', 'Transaction analysis') ?></h2>


<?php if($journalentryform->is_closing): ?>
  <p><?php echo Yii::t('delt', 'Transaction analysis is meaningless for closing entries.') ?></p>
  <?php else: ?>
  <?php if($journalentryform->show_analysis): ?>

  <?php echo $this->renderPartial('_transaction_analysis', array('items'=>$items, 'class'=>'journalentry analysis')) ?>
  <p><?php echo $this->createIcon('bell', Yii::t('delt', 'warning'), array('width'=>16, 'height'=>16)) ?> <?php echo Yii::t('delt', 'Please note that the transaction analysis is experimental and depends on a consistent chart of accounts.') ?></p>
  
    <?php else: ?>
      <p><?php echo Yii::t('delt', 'Transaction analysis is currently disabled. Please save the journal entry first.') ?></p>

    <?php endif ?>
  <?php endif ?>
</div>
