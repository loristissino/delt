<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Change Email");
$this->breadcrumbs=array(
	UserModule::t("Profile") => array('/user/profile'),
	UserModule::t("Change Email"),
);
$this->menu=array(
	((UserModule::isAdmin())
		?array('label'=>UserModule::t('Manage Users'), 'url'=>array('/user/admin'))
		:array()),
    array('label'=>UserModule::t('Profile'), 'url'=>array('/user/profile')),
    array('label'=>UserModule::t('Edit'), 'url'=>array('edit')),
    array('label'=>UserModule::t('Logout'), 'url'=>array('/user/logout')),
);

$this->menutitle=UserModule::t('Profile');

?>

<h1><?php echo UserModule::t("Change email"); ?></h1>

<p><?php echo Yii::t('delt', 'Sorry, this action has not been implemented yet.') ?> 
<?php echo Yii::t('delt', 'Please use the contact form if you want to change your email address.') ?></p>
