<?php
/* @var $this FirmController */
/* @var $model Firm */

/* @var $this FirmController */

$this->layout='//layouts/html5';

?>

<?php if($model->firmtype==Firm::FIRMTYPE_BUSINESS): ?>
<h1><?php echo Yii::t('delt', 'Empty Firm «{name}»', array('{name}'=>$model->name)) ?></h1>
<p class="warning"><?php echo Yii::t('delt', 'This firm exists, but does not have any journal entry yet.') ?></p>
<?php endif ?>

<?php if($model->firmtype==Firm::FIRMTYPE_NPO): ?>
<h1><?php echo Yii::t('delt', 'Empty Organization «{name}»', array('{name}'=>$model->name)) ?></h1>
<p class="warning"><?php echo Yii::t('delt', 'This organization exists, but does not have any journal entry yet.') ?></p>
<?php endif ?>
