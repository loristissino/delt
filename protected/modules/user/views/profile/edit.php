<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Profile");
$this->breadcrumbs=array(
	'Profile'=>array('profile'),
	'Edit'
);
$this->menu=array(
	((UserModule::isAdmin())
		?array('label'=>UserModule::t('Manage Users'), 'url'=>array('/user/admin'))
		:array()),
    array('label'=>UserModule::t('Profile'), 'url'=>array('/user/profile')),
    array('label'=>UserModule::t('Change password'), 'url'=>array('changepassword')),
    array('label'=>UserModule::t('Change email'), 'url'=>array('changeemail')),
    array('label'=>UserModule::t('Logout'), 'url'=>array('/user/logout')),
);

$this->menutitle=UserModule::t('Profile');

$languages=array_merge(array(
    '0'=>UserModule::t('unselected (use browser\'s preferences)'),
  ),
  Yii::app()->params['available_languages']
);

?><h1><?php echo UserModule::t('Edit profile'); ?></h1>

<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
<div class="success">
<?php echo Yii::app()->user->getFlash('profileMessage'); ?>
</div>
<?php endif; ?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'profile-form',
	'enableAjaxValidation'=>true,
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

	<?php echo $form->errorSummary(array($model,$profile)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

<?php 
		$profileFields=$profile->getFields();
		if ($profileFields) {
			foreach($profileFields as $field) {
			?>
	<div class="row">
		<?php echo $form->labelEx($profile,$field->varname);
		
		if ($widgetEdit = $field->widgetEdit($profile)) {
			echo $widgetEdit;
		} elseif ($field->range) {
			echo $form->dropDownList($profile,$field->varname,Profile::range($field->range));
		} elseif ($field->field_type=="TEXT") {
			echo $form->textArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
		} else {
			echo $form->textField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
		}
		echo $form->error($profile,$field->varname); ?>
	</div>	
			<?php
			}
		}
?>

	<div class="row">
		<?php echo $form->labelEx($profile, 'language') ?>
		<?php echo $form->dropDownList($profile,'language', $languages); ?>
		<?php echo $form->error($profile,'language'); ?>
	</div>
  
  <?php if($profile->mustAcceptTerms()): ?>
  <div class="row checkbox">
    <?php echo $form->checkBox($profile, 'terms') ?>&nbsp;<?php echo UserModule::t('I agree with the <a href="{tos}">Terms of Service</a> and the <a href="{privacy}">Privacy Policy</a>.', array('{tos}'=>$this->createUrl('/site/page', array('view'=>Yii::app()->language . '.tos')), '{privacy}'=>$this->createUrl('/site/page', array('view'=>Yii::app()->language . '.privacy')))) ?>
    <?php echo $form->error($profile, 'terms'); ?>
  </div>
  <?php endif ?>

  <div class="row checkbox">
    <?php echo $form->checkBox($profile, 'email_notices') ?>&nbsp;<?php echo UserModule::t('I want to receive email notices and news from the website.') ?>
    <?php echo $form->error($profile, 'email_notices'); ?>
  </div>
  
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? UserModule::t('Create') : UserModule::t('Save')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
