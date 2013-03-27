<?php
/* @var $this AbcdeController */
/* @var $model Abcde */
/* @var $form CActiveForm */

$n = sizeof($items);

$up_icon=addslashes(CHtml::image(Yii::app()->request->baseUrl.'/images/arrow_up.png', Yii::t('delt', 'Up'), array('width'=>8, 'height'=>16, 'title'=>Yii::t('delt', 'Move Up'))));
$down_icon=addslashes(CHtml::image(Yii::app()->request->baseUrl.'/images/arrow_down.png', Yii::t('delt', 'Down'), array('width'=>8, 'height'=>16, 'title'=>Yii::t('delt', 'Move Down'))));

$cs = Yii::app()->getClientScript();  
$cs->registerScript(
  'swap-rows-handler',
  '
  
  for(i=1; i<= ' . $n . '; i++)
  {
    var down = "<span id=\'down" + i +"\'>' . $down_icon . '</span>";
    var up   = "<span id=\'up" + i +"\'>' . $up_icon . '</span>";
    var text = "";
    if(i>1)
    {
      text += up;
    }
    if(i< ' . $n . ')
    {
      text += down;
    }
    $("#swap" +  i).html(text);
  }
  for(i=1; i< ' . $n . '; i++)
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
  for(i=2; i<= ' . $n . '; i++)
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
  
  function swaprows(a,b)
  {
    swapcontent("#name"+a, "#name"+b);
    swapcontent("#debit"+a, "#debit"+b);
    swapcontent("#credit"+a, "#credit"+b);
  }
  
  function swapcontent(a, b)
  {
    var c=$(a).val();
    $(a).val($(b).val());
    $(b).val(c);
  }
  '
  ,
  CClientScript::POS_READY
);

?>



<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'postform',
	'enableAjaxValidation'=>false,
  'focus'=> (isset($postform->post) ? null : array($postform, 'description')),
)); ?>

	<p class="note">
    <?php echo Yii::t('delt', 'Fields with <span class="required">*</span> are required.') ?><br />
    <?php echo Yii::t('delt', 'The rows in which the account field is empty are ignored.') ?>
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

<table>
<thead>
<tr><th style="width: 700px"><?php echo Yii::t('delt', 'Row') ?></th><th><?php echo Yii::t('delt', 'Account') ?></th><th><?php echo Yii::t('delt', 'Debit') ?></th><th><?php echo Yii::t('delt', 'Credit') ?></th></tr>
</thead>
<tbody>
<?php $row=0; foreach($items as $i=>$item): ?>
<tr id="row<?php echo ++$row ?>>">
<td class="number" style="width: 200px">
<span id="swap<?php echo $row ?>"></span>

<?php echo $row ?>

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


	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('delt', 'Save journal post'), array('name'=>'submit')); ?>
		<?php echo CHtml::submitButton(Yii::t('delt', 'Add a row'), array('name'=>'addrow')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
