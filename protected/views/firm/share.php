<?php
/* @var $this FirmController */
/* @var $model Firm */

$this->breadcrumbs=array(
  'Firms'=>array('index'),
  $model->name=>array('public','slug'=>$model->slug),
  'Share',
);

$other_owners = $model->getAllOwnersExcept($this->DEUser->id);

?>

<h1><?php echo Yii::t('delt', 'Share the Firm «{name}»', array('{name}'=>$model->name)) ?></h1>

<?php echo $this->renderPartial('/firm/_otherowners', array('other_owners'=>$other_owners)) ?>

<p><?php echo Yii::t('delt', 'You can share it with another user by inviting them with the form below.') ?></p>
<?php $form=$this->beginWidget('CActiveForm', array(
  'id'=>'ShareFirmForm',
  'enableAjaxValidation'=>false,
  'method'=>'POST',
  'action'=>$this->createUrl('firm/share', array('slug'=>$model->slug)),
)); ?>

  <div class="row">
    <?php echo CHtml::label('username', false) ?>
    <?php echo CHtml::textField('username', '', array('size'=>40)) ?>
    <?php echo CHtml::submitButton(Yii::t('delt', 'Share'), array('name'=>'share')) ?>
  </div>

<?php $this->endWidget() ?>
