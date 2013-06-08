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

<?php if(sizeof($other_owners)>0): ?>
<p><?php echo Yii::t('delt', 'This firm is currently shared with another user:|This firm is currently shared with other {n} users:', sizeof($other_owners)) ?></p>
<ul>
  <?php foreach($other_owners as $user): ?>
    <li>
      <?php echo $user->username ?>
      <?php if($user->first_name or $user->last_name): ?>
        (<?php echo $user->first_name . ' ' . $user->last_name ?>)
      <?php endif ?>
    </li>
  <?php endforeach ?>
</ul>
<?php else: ?>
<p><?php echo Yii::t('delt', 'This firm is not currently shared with any other user.') ?></p>
<?php endif ?>

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
