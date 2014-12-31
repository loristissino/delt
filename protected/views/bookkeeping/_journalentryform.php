<?php
/* @var $this AbcdeController */
/* @var $model Abcde */
/* @var $form CActiveForm */

$n = sizeof($items);

$up_icon=addslashes($this->createIcon('arrow_up', Yii::t('delt', 'Up'), array('width'=>8, 'height'=>16, 'style'=>'padding-left: 2px; padding-right: %pr%px', 'title'=>Yii::t('delt', 'Move Up'))));
$down_icon=addslashes($this->createIcon('arrow_down', Yii::t('delt', 'Down'), array('width'=>8, 'height'=>16, 'style'=>'padding-left: %pl%px;', 'title'=>Yii::t('delt', 'Move Down'))));
$choose_icon=addslashes($this->createIcon('choose', Yii::t('delt', 'Choose'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'Choose'))));

$raw_input_icon=addslashes($this->createIcon('text_align_left', Yii::t('delt', 'Raw input'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 8px;', 'title'=>Yii::t('delt', 'Switch to raw input mode'))));
$textfields_icon=addslashes($this->createIcon('application_form', Yii::t('delt', 'Text fields'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 0px;', 'title'=>Yii::t('delt', 'Switch to text fields mode'))));
$load_accounts_icon=addslashes($this->createIcon('table_go', Yii::t('delt', 'Load accounts'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 0px;', 'title'=>Yii::t('delt', 'Load all accounts'))));
$sort_icon=addslashes($this->createIcon('sortdc', Yii::t('delt', 'Sort postings'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 8px;', 'title'=>Yii::t('delt', 'Sort postings, debits first'))));
$explain_icon=addslashes($this->createIcon('analyze', Yii::t('delt', 'Analyze the transaction'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 8px;', 'title'=>Yii::t('delt', 'Analyze the transaction'))));
$swap_debits_credits_icon=addslashes($this->createIcon('arrows', Yii::t('delt', 'Swap debits and credits'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 8px;', 'title'=>Yii::t('delt', 'Swap debit and credits for the whole journal entry'))));

$calculator_icon = addslashes(Yii::app()->request->baseUrl.'/images/calculator.png');

$json_url = addslashes($this->createUrl('bookkeeping/suggestaccount', array('slug'=>$this->firm->slug)));

$currency_test_string=DELT::currency_value(3.14, $this->firm->currency); // this will be something like "$3.14" or "US$ 3,14", depending on the locale;

$placeholder_string = addslashes(Yii::t('delt', 'Start typing (code or name) or double-click...'));

$unload_alert_string = addslashes(Yii::t('delt', 'There might be some unsaved changes in the form.'));

$cs = Yii::app()->getClientScript();  
$cs->registerScript(
  'swap-rows-handler',
  '
  
  var decimal_separator = "' . $currency_test_string . '".replace(/[^d\.,]/g, "");
  
  var thousand_separator = decimal_separator=="." ? ",":".";

  var currency = "' . $currency_test_string . '".replace(/[\d\.,]/g, "");

  var view_as_textfields = true;
  var n = ' . $n . ';
  var fields = new Array("name", "debit", "credit");

  var raw_input_icon = "' . $raw_input_icon . '";
  var textfields_icon = "' . $textfields_icon . '";
  var load_accounts_icon = "' . $load_accounts_icon . '";
  var sort_icon = "' . $sort_icon . '";
  var explain_icon = "' . $explain_icon . '";
  var swap_debits_credits_icon = "' . $swap_debits_credits_icon . '";
  
  var identifier = "'. $journalentryform->identifier . '";
  
  var check_dirty = true;
  var clear_local_storage_on_exit = true;
  
  $("#commands").html(
    "<span id=\"toggle\">" + raw_input_icon + 
    "</span>&nbsp;<span id=\"sort_accounts\">" + sort_icon + "</span>" +
    "</span>&nbsp;<span id=\"explain\">" + explain_icon + "</span>" +
    "</span>&nbsp;<span id=\"load_accounts\">" + load_accounts_icon + "</span>" +
    "</span>&nbsp;<span id=\"swap_debits_credits\">" + swap_debits_credits_icon + "</span>"

    );
  $("#load_accounts").hide();
  
  $("#analysis").hide();
  
  updatetotals();
  
  form2localstorage();
  
  $("#explain").click(function()
    {
      $("#analysis").toggle(500);
    }
  );
  
  $("#load_accounts").click(function()
    {

      var jsonUrl = "' . $json_url . '";
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
          $("#debit" + i).val(this.debit);
          $("#credit" + i).val(this.credit);
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
    text = $("#raw_input").val();
    $("#toggle").html(raw_input_icon);
    
    lines = text.replace(/\n$/).split(/\n/);
    if(lines.length<=n)
    {
      for(i=0; i<lines.length; i++)
      {
        data = lines[i].split(/\t/);
        if(data.length>=3)
        {
          var name = data[0];
          var debit = data[1];
          var credit = data[2];
          if((debit != "") || (credit !=""))
          {
            $("#name" + (i+1)).val(name);
            $("#debit" + (i+1)).val(debit);
            $("#credit" + (i+1)).val(credit);
          }
        }
      }
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
      $("form#journalentryform").submit();
    }
    
    
  }
  
  addArrows();
  addEventManagers();
  
  function addArrows()
  {
    for(i=1; i<= n; i++)
    {
      var down = "<span id=\'down" + i +"\'>' . $down_icon . '</span>";
      var up   = "<span id=\'up" + i +"\'>' . $up_icon . '</span>";
      var choose = "<span id=\'choose" + i +"\'>' . $choose_icon . '</span>";
      var text = "";
      if(i>1)
      {
        text += up.replace("%pr%", (i==n ? "8" : "0"));
      }
      if(i<n)
      {
        text += down.replace("%pl%", (i==1 ? "10": "0"));
      }
      text += choose;
      $("#swap" +  i).html(text);
    }
    for(i=1; i<n; i++)
    {
      $("#down" +i).click((function(index)
        {
          return function()
          {
            swaprows(index, index+1);
            return false;
          }
        })(i));
    }
    for(i=2; i<=n; i++)
    {
      $("#up" +i).click((function(index)
        {
          return function()
          {
            swaprows(index, index-1);
            return false;
          }
        })(i));
    }
  }


  function addEventManagers()
  {
    var done=false;
    for(i=1; i<= n; i++)
    {
      $("#name" +i).dblclick((function(index)
        {
          return function()
          {
            row_number = index;
            $("#chooseaccountdialog").dialog("open");
            return false;
          }
        })(i));
      $("#choose" +i).click((function(index)
        {
          return function()
          {
            row_number = index;
            $("#chooseaccountdialog").dialog("open");
            return false;
          }
        })(i));
        
      if(!$("#name" +i).val() && !done)
      {
        $("#name" +i).attr("placeholder", "'. $placeholder_string . '");
        done=true;
      }

      $("#debit" +i).blur(function() {updatetotals(); });
      $("#credit" +i).blur(function() {updatetotals(); });
      $("#debit" +i).calculator({ showOn: "operator", isOperator: checkCharDebit});
      $("#credit" +i).calculator({ showOn: "operator", isOperator: checkCharCredit});

    }
  }

  function checkCharDebit(ch, event, value, base, decimalChar) {
    return checkCh(ch, event, total_credit > total_debit ? total_credit - total_debit : "");
  }

  function checkCharCredit(ch, event, value, base, decimalChar) {
    return checkCh(ch, event, total_debit > total_credit ? total_debit - total_credit : "");
  }

  
  function checkCh(ch, event, value) {
    if(ch=="=")
    {
      updatetotals();
      $("#"+event.srcElement.id).val(value);
      updatetotals();
      return false;
    }
    return "+-*/".indexOf(ch) > -1 && !(ch === "-" && value === ""); 
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
  
  function updatetotals()
  {
    total_debit=0;
    total_credit=0;
    
    for(i=1; i<=n; i++)
    {
      total_debit += accounting.unformat($("#debit"+i).val(), decimal_separator);
      total_credit += accounting.unformat($("#credit"+i).val(), decimal_separator);
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
  
  $("#save_button").click(function(e) {check_dirty=false;; clear_local_storage_on_exit = true; })
  $("#addline_button").click(function(e) {check_dirty=false; clear_local_storage_on_exit = false; })
  $("#done_button").click(function(e) {check_dirty=false;; clear_local_storage_on_exit = true; })
  $("#new_button").click(function(e) {check_dirty=false;; clear_local_storage_on_exit = true; })
    
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

?>

<div class="form" style="width: 630px">

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
      <table>
      <thead>
      <tr><th style="width: 700px"><?php echo Yii::t('delt', 'Row') ?></th><th><?php echo Yii::t('delt', 'Account') ?></th><th><?php echo Yii::t('delt', 'Debit') ?></th><th><?php echo Yii::t('delt', 'Credit') ?></th></tr>
      </thead>
      <tfoot>
      <tr><th style="width: 700px">&nbsp;</th><th><?php echo Yii::t('delt', 'Sum') ?></th><th class="currency <?php echo $class=$journalentryform->total_debit==$journalentryform->total_credit? 'valuesok':'valueswrong' ?>" id="td_total_debit"><span id="total_debit"><?php echo DELT::currency_value($journalentryform->total_debit, $this->firm->currency) ?></span></th><th class="currency <?php echo $class ?>" id="td_total_credit"><span id="total_credit"><?php echo  DELT::currency_value($journalentryform->total_credit, $this->firm->currency) ?></span></th></tr>
      </tfoot>
      <tbody>
      <?php $row=0; foreach($items as $i=>$item): ?>
      <tr id="row<?php echo ++$row ?>">
      <td class="number" style="width: 200px;">
      <?php echo $this->createIcon('tp', '', array('height'=>1, 'width'=>44)) ?><br />
      <?php echo $row ?><span id="swap<?php echo $row ?>"></span>
      </td>
      <td><?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
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
      ?></td>
      <td><?php echo CHtml::activeTextField($item,"[$i]debit", array('size'=> 10, 'id'=>'debit'.$row, 'class'=>'currency ' . ($item->debit_errors ? 'error': 'valid') . ($item->guessed ? ' guessed': ''))) ?></td>
      <td><?php echo CHtml::activeTextField($item,"[$i]credit", array('size'=> 10, 'id'=>'credit'.$row, 'class'=>'currency ' . ($item->credit_errors ? 'error': 'valid') . ($item->guessed ? ' guessed': ''))) ?></td>
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
      <?php echo $form->textArea($journalentryform, 'raw_input', array('id'=>'raw_input', 'maxlength' => 10000, 'rows' => $n, 'cols' => 65)); ?>
      <?php echo $form->error($journalentryform,'raw_input'); ?>
    </div>
  </div><!-- rows_as_textarea -->

<?php $this->endWidget(); ?>

</div><!-- form -->

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
    <table style="width: 600px;">
    <tr>
      <th><?php echo Yii::t('delt', 'Account') ?></th>
      <th><?php echo Yii::t('delt', 'Classification') ?></th>
      <th  style="width: 120px;"><?php echo Yii::t('delt', 'Change') ?></th>
      <th  style="width: 80px;"><?php echo Yii::t('delt', 'Value') ?></th>
    </tr>
    <?php foreach($items as $item): $analysis=$item->analysis ?>
      <?php if(isset($analysis['account'])): ?>
        <tr>
          <td>
            <?php echo $analysis['account']?>
          </td>
          <td>
            <?php echo $analysis['classification'] ?>
            <?php if($analysis['type']=='C'): ?>
              (<?php echo Yii::t('delt', 'Contra Account') ?>)
            <?php endif ?>
          </td>
          <td  style="width: 120px;">
            <?php echo $this->renderPartial('_change', array('change'=>$analysis['change'], 'type'=>$analysis['type'])) ?>
          </td>
          <td class="currency"  style="width: 80px;"><?php echo $analysis['value'] ?></td>
        </tr>
      <?php endif ?>
    <?php endforeach ?>
    </table>

    <p><?php echo $this->createIcon('bell', Yii::t('delt', 'warning'), array('width'=>16, 'height'=>16)) ?> <?php echo Yii::t('delt', 'Please note that the transaction analysis is experimental and depends on a consistent chart of accounts.') ?></p>
    <?php else: ?>
      <p><?php echo Yii::t('delt', 'Transaction analysis is currently disabled. Please save the journal entry first.') ?></p>

    <?php endif ?>
  <?php endif ?>
</div>
