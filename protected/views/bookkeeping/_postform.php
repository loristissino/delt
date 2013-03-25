<?php
/* @var $this AbcdeController */
/* @var $model Abcde */
/* @var $form CActiveForm */

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

	<?php echo $form->errorSummary($postform); ?>

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
<tr><th><?php echo Yii::t('delt', 'Row') ?></th><th><?php echo Yii::t('delt', 'Account') ?></th><th><?php echo Yii::t('delt', 'Debit') ?></th><th><?php echo Yii::t('delt', 'Credit') ?></th></tr>
<?php $row=1; foreach($items as $i=>$item): ?>
<tr>
<td class="number"><?php echo $row++ ?></td>
<td><?php $this->widget('zii.widgets.jui.CJuiAutoComplete', array(
  'id'=>'name'.$i,
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
<td><?php echo CHtml::activeTextField($item,"[$i]debit", array('size'=> 10, 'class'=>'currency ' . ($item->debit_errors ? 'error': 'valid') . ($item->guessed ? ' guessed': ''))) ?></td>
<td><?php echo CHtml::activeTextField($item,"[$i]credit", array('size'=> 10, 'class'=>'currency ' . ($item->credit_errors ? 'error': 'valid') . ($item->guessed ? ' guessed': ''))) ?></td>
</tr>
<?php endforeach; ?>
</table>


	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('delt', 'Save journal post'), array('name'=>'submit')); ?>
		<?php echo CHtml::submitButton(Yii::t('delt', 'Add a row'), array('name'=>'addrow')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
