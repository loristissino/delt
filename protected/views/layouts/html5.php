<?php /* @var $this Controller */ 

$mainmenu_items=array(
				array('label'=>Yii::t('delt', 'Home'), 'url'=>array('/site/'. Yii::app()->language . '/index')),
				array('label'=>Yii::t('delt','About'), 'url'=>array('/site/'. Yii::app()->language . '/about')),
				array('label'=>Yii::t('delt','Contact'), 'url'=>array('/site/contact')),
				array('label'=>Yii::t('delt','Bookkeeping'), 'url'=>array('/bookkeeping/index'), 'visible'=>!Yii::app()->user->isGuest),
        array('url'=>Yii::app()->getModule('user')->loginUrl, 'label'=>Yii::app()->getModule('user')->t("Login"), 'visible'=>Yii::app()->user->isGuest),
        array('url'=>Yii::app()->getModule('user')->registrationUrl, 'label'=>Yii::app()->getModule('user')->t("Sign up"), 'visible'=>Yii::app()->user->isGuest),
        array('url'=>Yii::app()->getModule('user')->profileUrl, 'label'=>Yii::app()->getModule('user')->t("Profile"), 'visible'=>!Yii::app()->user->isGuest),
        array('url'=>Yii::app()->getModule('user')->logoutUrl, 'label'=>Yii::app()->getModule('user')->t("Logout").' ('.Yii::app()->user->name.')', 'visible'=>!Yii::app()->user->isGuest),
			);
?>
<!DOCTYPE html>
<html>

<head>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
  <style type="text/css">
body
{
	margin: 20px;
	padding: 0;
	color: #555;
	font: normal 10pt Arial,Helvetica,sans-serif;
	background: #ffffff;
}
td
{
  padding-left: 5px;
  padding-right: 5px;
}
.currency
{
  text-align: right;
  padding-left: 30px;
}
.journaldescription
{
  font-style: italic;
}
.firstpostrow
{
  border-top-style: solid;
  border-top-width: 1px;
}
.lastpostrow
{
  border-bottom-style: double;
  border-bottom-width: 3px;
}


  </style>
	<meta charset="utf-8" />
</head>

<body>
  <header>
    <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
  </header>
  <?php if(isset($this->breadcrumbs)):?>
		<nav>
      <?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
    </nav>
	<?php endif?>
  
  <?php echo $content; ?>

	<div class="clear"></div>

  <footer>
    <p>Website: <a href="http://learndoubleentry.org">learndoubleentry.org</a></p>
  </footer>

  <?php if(isset(Yii::app()->params['analytics'])) include_once(Yii::app()->params['analytics']) ?>
</body>
</html>
