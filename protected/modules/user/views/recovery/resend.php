<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Resend activation link");
$this->breadcrumbs=array(
	UserModule::t("Login") => array('/user/login'),
	UserModule::t('Resend activation link'),
);
?>

<h1><?php echo UserModule::t('Resend activation link'); ?></h1>

<?php if(Yii::app()->user->hasFlash('resendMessage')): ?>
<div class="success">
<?php echo Yii::app()->user->getFlash('resendMessage'); ?>
</div>
<?php else: ?>

<div class="form">
<?php echo CHtml::beginForm(); ?>

	<?php echo CHtml::errorSummary($form); ?>
	
	<div class="row">
		<?php echo CHtml::activeLabel($form,'login_or_email'); ?>
		<?php echo CHtml::activeTextField($form,'login_or_email') ?>
		<p class="hint"><?php echo UserModule::t('Please enter your login or email address.'); ?></p>
	</div>
	
	<div class="row submit">
		<?php echo CHtml::submitButton(UserModule::t('Resend activation link')); ?>
	</div>

<?php echo CHtml::endForm(); ?>
</div><!-- form -->
<?php endif; ?>
