<p><?php echo UserModule::t("Please fill out the following form with your login credentials:"); ?></p>

<div class="form">
<?php echo CHtml::beginForm(); ?>

	<p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
	
	<?php echo CHtml::errorSummary($model); ?>
	
	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'username'); ?>
		<?php echo CHtml::activeTextField($model,'username') ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::activeLabelEx($model,'password'); ?>
		<?php echo CHtml::activePasswordField($model,'password') ?>
	</div>
	
	<div class="row">
		<p class="hint">
		<?php echo CHtml::link(UserModule::t('Sign up'),Yii::app()->getModule('user')->registrationUrl); ?> | <?php echo CHtml::link(UserModule::t('Lost Password?'),Yii::app()->getModule('user')->recoveryUrl); ?> | <?php echo CHtml::link(UserModule::t('Didn\'t receive the activation link?'),Yii::app()->getModule('user')->resendactivationUrl); ?>
		</p>
	</div>
	
	<div class="row rememberMe">
		<?php echo CHtml::activeCheckBox($model,'rememberMe'); ?>
		<?php echo CHtml::activeLabelEx($model,'rememberMe'); ?>
	</div>

	<p class="note"><?php echo UserModule::t('We use cookies to store the information concerning your activities on our website.'); ?> <?php echo UserModule::t('See our <a href="{privacy_url}">privacy policy</a> to find out more.', array('{privacy_url}'=>Yii::app()->params['privacy_url'])); ?></p>

	<div class="row submit">
		<?php echo CHtml::submitButton(UserModule::t("Login")); ?>
	</div>
	
<?php echo CHtml::endForm(); ?>
</div><!-- form -->
