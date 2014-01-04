<?php
/* @var $this FirmController */
/* @var $model Firm */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name=>array('bookkeeping/manage','slug'=>$model->slug),
  'Disown',
);

$other_owners = $model->getAllOwnersExcept($this->DEUser->id);

$this->menu[]=array('label'=>Yii::t('delt', 'Edit'), 'url'=>array('firm/update', 'id'=>$model->id));
$this->menu[]=array('label'=>Yii::t('delt', 'Configuration'), 'url'=>array('bookkeeping/configure', 'slug'=>$model->slug));

?>

<h1><?php echo Yii::t('delt', 'Disown the firm «{name}»', array('{name}'=>$model->name)) ?></h1>

<?php echo $this->renderPartial('/firm/_otherowners', array('other_owners'=>$other_owners)) ?>

<?php if(sizeof($other_owners)>=1): ?>

  <p><?php echo Yii::t('delt', 'You can disown this firm, if you want. It will be left to the other owners.') ?></p>
  <?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'ShareFirmForm',
    'enableAjaxValidation'=>false,
    'method'=>'POST',
    'action'=>$this->createUrl('firm/disown', array('slug'=>$model->slug)),
  )); ?>

    <div class="row">
      <?php echo CHtml::submitButton(Yii::t('delt', 'Disown'), array('name'=>'disown')) ?>
    </div>

  <?php $this->endWidget() ?>

<?php else: ?>
   <p><?php echo Yii::t('delt', 'You cannot disown this firm, because you are the only owner.') ?></p>
<?php endif ?>
