<?php
/* @var $this FirmController */
/* @var $model Firm */

$this->breadcrumbs=array(
  'Firms'=>array('index'),
  'Fork'=>array('firm/fork'),
  'Error'
);

$available_firms = $this->DEUser->profile->allowed_firms - sizeof($this->DEUser->firms);

?>
<h1><?php echo Yii::t('delt', 'Firm not found') ?></h1>

<p>
  <?php echo Yii::t('delt', 'Sorry, we could not find a firm with the slug «%slug%».', array('%slug%'=>$slug)) ?>
  <?php echo CHtml::link(Yii::t('delt', 'Try forking another one.'), array('firm/fork')) ?>
</p>
