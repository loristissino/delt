<?php
/* @var $this AbcdeController */
/* @var $model Abcde */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'postform',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($postform); ?>

	<div class="row">
		<?php echo $form->labelEx($postform,'date'); ?>
		<?php echo $form->textField($postform,'date'); ?>
		<?php echo $form->error($postform,'date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($postform,'description'); ?>
		<?php echo $form->textField($postform,'description'); ?>
		<?php echo $form->error($postform,'description'); ?>
	</div>


<table>
<tr><th>Name</th><th>Debit</th><th>Credit</th></tr>
<?php foreach($items as $i=>$item): ?>
<tr>
<td><?php echo CHtml::activeTextField($item,"[$i]name"); ?></td>
<td><?php echo CHtml::activeTextField($item,"[$i]debit"); ?></td>
<td><?php echo CHtml::activeTextField($item,"[$i]credit"); ?></td>
</tr>
<?php endforeach; ?>
</table>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
		<?php echo CHtml::submitButton('Add row', array('name'=>'addrow')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
