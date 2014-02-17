<?php
$this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Login");
$this->breadcrumbs=array(
	'Login',
);
?>

<h1><?php echo UserModule::t("Login"); ?></h1>

<?php if(Yii::app()->user->hasFlash('loginMessage')): ?>

<div class="success">
	<?php echo Yii::app()->user->getFlash('loginMessage'); ?>
</div>

<?php endif; ?>

<?php if($oauth): ?>

  <?php ob_start(); $this->widget('ext.hoauth.widgets.HOAuth'); $hoauth_widget=ob_get_contents(); ob_end_clean(); ?>

  <?php $this->widget('zii.widgets.jui.CJuiTabs', array(
      'tabs'=>array(
          Yii::t('delt', 'Login with username / email')=>$this->renderPartial('/user/_login_email', array('model'=>$model), true),
          Yii::t('delt', 'Login with social network account')=>$hoauth_widget,
      ),
      'options'=>array(
          'collapsible'=>true,
          'selected'=>0,
      ),
      'htmlOptions'=>array(
          'style'=>'width:700px;'
      ),
  ));
  ?>
<?php else: ?>
  <?php echo $this->renderPartial('/user/_login_email', array('model'=>$model), true) ?>
<?php endif ?>
