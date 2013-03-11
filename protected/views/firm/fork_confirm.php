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

<p><?php echo Yii::t('delt', 'Do you want to proceed?') ?></p>

<?php $form=$this->beginWidget('CActiveForm'); ?>
    <div class="row submit">
        <?php echo CHtml::submitButton(Yii::t('delt', 'Yes, please, fork this firm')) ?>
    </div>
<?php $this->endWidget(); ?>
