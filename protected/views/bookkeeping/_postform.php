<?php
/* @var $this AbcdeController */
/* @var $model Abcde */
/* @var $form CActiveForm */

$n = sizeof($items);

$up_icon=addslashes($this->createIcon('arrow_up', Yii::t('delt', 'Up'), array('width'=>8, 'height'=>16, 'style'=>'padding-left: 2px; padding-right: %pr%px', 'title'=>Yii::t('delt', 'Move Up'))));
$down_icon=addslashes($this->createIcon('arrow_down', Yii::t('delt', 'Down'), array('width'=>8, 'height'=>16, 'style'=>'padding-left: %pl%px;', 'title'=>Yii::t('delt', 'Move Down'))));

$raw_input_icon=addslashes($this->createIcon('text_align_left', Yii::t('delt', 'Raw input'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 8px;', 'title'=>Yii::t('delt', 'Switch to raw input mode'))));
$textfields_icon=addslashes($this->createIcon('application_form', Yii::t('delt', 'Text fields'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 0px;', 'title'=>Yii::t('delt', 'Switch to text fields mode'))));
$load_accounts_icon=addslashes($this->createIcon('table_go', Yii::t('delt', 'Load accounts'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 0px;', 'title'=>Yii::t('delt', 'Load all accounts'))));
$sort_icon=addslashes($this->createIcon('sortdc', Yii::t('delt', 'Sort postings'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 8px;', 'title'=>Yii::t('delt', 'Sort postings, debits first'))));
$explain_icon=addslashes($this->createIcon('analyze', Yii::t('delt', 'Analyze the transaction'), array('width'=>16, 'height'=>16, 'style'=>'padding-bottom: 8px;', 'title'=>Yii::t('delt', 'Analyze the transaction'))));

$json_url = addslashes($this->createUrl('bookkeeping/suggestaccount', array('slug'=>$this->firm->slug)));

$cs = Yii::app()->getClientScript();  
$cs->registerScript(
  'swap-rows-handler',
  '
  
  var view_as_textfields = true;
  var n = ' . $n . ';
  var fields = new Array("name", "debit", "credit");
  
  var raw_input_icon = "' . $raw_input_icon . '";
  var textfields_icon = "' . $textfields_icon . '";
  var load_accounts_icon = "' . $load_accounts_icon . '";
  var sort_icon = "' . $sort_icon . '";
  var explain_icon = "' . $explain_icon . '";
  
  $("#commands").html(
    "<span id=\"toggle\">" + raw_input_icon + 
    "</span>&nbsp;<span id=\"sort_accounts\">" + sort_icon + "</span>" +
    "</span>&nbsp;<span id=\"explain\">" + explain_icon + "</span>" +
    "</span>&nbsp;<span id=\"load_accounts\">" + load_accounts_icon + "</span>"
    );
  $("#load_accounts").hide();
  
  $("#analysis").hide();
  
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
  }
  
  function fromTextArea()
  {
    $("#load_accounts").hide();
    $("#sort_accounts").show();
    $("#explain").show();
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

    }
    else  // we have extra lines, we need to do a post...
    {
      $("form#postform").submit();
    }
    
    
  }
  
  addArrows();
  
  function addArrows()
  {
    for(i=1; i<= n; i++)
    {
      var down = "<span id=\'down" + i +"\'>' . $down_icon . '</span>";
      var up   = "<span id=\'up" + i +"\'>' . $up_icon . '</span>";
      var text = "";
      if(i>1)
      {
        text += up.replace("%pr%", (i==n ? "8" : "0"));
      }
      if(i<n)
      {
        text += down.replace("%pl%", (i==1 ? "10": "0"));
      }
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

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'postform',
	'enableAjaxValidation'=>false,
  'focus'=> (isset($postform->post) ? null : array($postform, 'description')),
  'action'=>$this->form_action_required, //array('bookkeeping/' . ($postform->post? 'updatepost': 'newpost'), 'slug'=>$postform->firm->slug),
)); ?>

	<p class="note">
    <?php echo Yii::t('delt', 'Fields with <span class="required">*</span> are required.') ?><br />
    <?php echo Yii::t('delt', 'The lines in which the account field is empty are ignored.') ?>
  </p>

	<?php echo $form->errorSummary($postform, Yii::t('delt', 'Please fix the following errors:')); ?>

	<div class="row">
		<?php echo $form->labelEx($postform,'date'); ?>
		<?php // echo $form->textField($postform,'date'); ?>
    
    <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
      'name'=>'PostForm[date]',
      'value'=>$postform->date,
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
		<?php echo $form->error($postform,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($postform,'description'); ?>
		<?php echo $form->textField($postform,'description', array('size'=>80)); ?>
		<?php echo $form->error($postform,'description'); ?>
	</div>

  <div id="commands"></div>

  <div id="rows_as_textfields" style="display: block">
    <div class="accountsrows">
      <table>
      <thead>
      <tr><th style="width: 700px"><?php echo Yii::t('delt', 'Row') ?></th><th><?php echo Yii::t('delt', 'Account') ?></th><th><?php echo Yii::t('delt', 'Debit') ?></th><th><?php echo Yii::t('delt', 'Credit') ?></th></tr>
      </thead>
      <tbody>
      <?php $row=0; foreach($items as $i=>$item): ?>
      <tr id="row<?php echo ++$row ?>">
      <td class="number" style="width: 200px;">
      <?php echo $this->createIcon('tp', '', array('height'=>1, 'width'=>40)) ?><br />
      <?php echo $row ?><span id="swap<?php echo $row ?>"></span>
      </td>
      <td><?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
        'id'=>'name'.$row,
        'name'=>"DebitcreditForm[$i][name]",
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

    <?php echo CHtml::activeHiddenField($postform, 'is_closing', array('value'=>$postform->is_closing)) ?>
    <?php if ($postform->adjustment_checkbox_needed): ?>
    <div class="row">
      <?php echo $form->labelEx($postform, 'options') ?>
      <?php echo $form->checkBox($postform, 'is_adjustment', array('checked'=>$postform->is_adjustment)) ?>&nbsp;<?php echo Yii::t('delt', 'Mark this journal entry as adjustment, thus allowing exceptions in debit/credit checks') ?>
    </div>
    <?php endif ?>
    <div class="row buttons">
      <?php echo CHtml::submitButton(Yii::t('delt', 'Save'), array('name'=>'save')); ?>
      <?php echo CHtml::submitButton(Yii::t('delt', 'Add a line'), array('name'=>'addline')); ?>
      <?php echo CHtml::submitButton(Yii::t('delt', 'Done'), array('name'=>'done')); ?>
    </div>
  </div><!-- rows_as_textfields -->
  <div id="rows_as_textarea" style="display: none">
    <div class="row">
      <?php echo $form->labelEx($postform, 'raw_input'); ?>
      <br />
      <span class="hint">
      <?php echo Yii::t('delt', 'Copy the contents of the text area to a spreadsheet (fields are separated by tabs), and edit the data there (if the text area is empty, you can click on the "Load all accounts" icon above to load all available accounts).')?><br />
      <?php echo Yii::t('delt', 'When you are done with the spreadsheet, paste here the three columns (name, debit and credit), and switch to text fields mode.')?>
      </span>
      <?php echo $form->textArea($postform, 'raw_input', array('id'=>'raw_input', 'maxlength' => 10000, 'rows' => $n, 'cols' => 65)); ?>
      <?php echo $form->error($postform,'raw_input'); ?>
    </div>
  </div><!-- rows_as_textarea -->

<?php $this->endWidget(); ?>

</div><!-- form -->

<div id="analysis" style="display: none">
<h2><?php echo Yii::t('delt', 'Transaction analysis') ?></h2>

<?php if($postform->show_analysis): ?>
  <p>
  <?php foreach($items as $item): ?>
    <?php if($item->analysis != 'none'): ?>
      <?php echo $item->analysis ?><br />
    <?php endif ?>
  <?php endforeach ?>
  </p>

  <p><?php echo $this->createIcon('bell', Yii::t('delt', 'warning'), array('width'=>16, 'height'=>16)) ?> <?php echo Yii::t('delt', 'Please note that the transaction analysis is experimental and is based on a correct chart of accounts.') ?></p>
<?php else: ?>
  <p><?php echo Yii::t('delt', 'Transaction analysis is currently disabled. Please save the journal entry first.') ?></p>

<?php endif ?>

</div>
