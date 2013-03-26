<?php
/* @var $this FirmController */
/* @var $model Firm */

$this->breadcrumbs=array(
	'Firms'=>array('index'),
	'Fork',
);

?>

<h1><?php echo Yii::t('delt', 'Fork the firm «{firm}»', array('{firm}'=>$firm)) ?></h1>

<h2><?php echo Yii::t('delt', 'Description') ?></h2>

<p><?php echo $firm->description ?></p>

<hr />

<div class="form">
<?php $form=$this->beginWidget('CActiveForm'); ?>

	<?php echo $form->errorSummary($forkfirmform, Yii::t('delt', 'Please fix the following errors:')); ?>

    <div class="row buttons">
      <?php echo $form->label($forkfirmform, 'type'); ?><?php echo $form->dropDownList($forkfirmform,
          'type',
          $forkfirmform->getTypeOptions()
          ) ?>
          <hr />
    </div>
    <div class="row checkbox">
      <?php echo $form->checkBox($forkfirmform, 'license_confirmation') ?>
      <?php echo $form->label($forkfirmform,'license_confirmation'); ?>
        <hr />
    </div>
    <div class="row">
    <p><?php echo Yii::t('delt', 'Do you want to proceed?') ?></p>
    </div>
    <div class="row submit">
        <?php echo CHtml::submitButton(Yii::t('delt', 'Yes, please, fork this firm')) ?>
    </div>
<?php $this->endWidget(); ?>
</div><!-- form -->
