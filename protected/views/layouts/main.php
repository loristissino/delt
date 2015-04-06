<?php /* @var $this Controller */ 

$logoutLabel = Yii::app()->getModule('user')->t("Logout");
if($this->DEUser)
{
  $logoutLabel .= ' ('. $this->DEUser->username. ')';
}

$mainmenu_items=array(
        array('label'=>Yii::t('delt', 'Home'), 'url'=>array('/site/'. Yii::app()->language . '/index')),
        array('label'=>Yii::t('delt','About'), 'url'=>array('/site/'. Yii::app()->language . '/about')),
        array('label'=>Yii::t('delt','Contact'), 'url'=>array('/site/contact')),
        array('label'=>Yii::t('delt','Blog'), 'url'=>Yii::app()->params['blog'], 'visible'=>Yii::app()->params['blog']!=''),
        array('label'=>Yii::t('delt','Bookkeeping/Accounting'), 'url'=>array('/bookkeeping/index'), 'visible'=>!Yii::app()->user->isGuest),
        array('url'=>Yii::app()->getModule('user')->loginUrl, 'label'=>Yii::app()->getModule('user')->t("Login"), 'visible'=>Yii::app()->user->isGuest),
        array('url'=>Yii::app()->getModule('user')->registrationUrl, 'label'=>Yii::app()->getModule('user')->t("Sign up"), 'visible'=>Yii::app()->user->isGuest),
        array('url'=>Yii::app()->getModule('user')->profileUrl, 'label'=>Yii::app()->getModule('user')->t("Profile"), 'visible'=>!Yii::app()->user->isGuest),
        array('url'=>Yii::app()->getModule('user')->logoutUrl, 'label'=>$logoutLabel, 'visible'=>!Yii::app()->user->isGuest),
      );
?>
<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language ?>">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width">

  <!-- blueprint CSS framework -->
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
  <!--[if lt IE 8]>
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
  <![endif]-->

  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.calculator.alt.css" />
  
  <link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/doubleentry.png" type="image/png" />
  
  <link type="text/plain" rel="author" href="<?php echo Yii::app()->request->baseUrl; ?>/humans.txt" />

  <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

  <div id="header">
    <div id="logo"><?php echo $this->createIcon('LearnDoubleEntryHeading', 'LearnDoubleEntry.org', array('width'=>400, 'height'=>63, 'title'=>Yii::t('delt', 'Welcome to «{name}»', array('{name}'=>Yii::app()->name)))) ?></div>
  </div><!-- header -->

  <div id="mainmenu">
    <?php $this->widget('zii.widgets.CMenu',array(
      'items'=>$mainmenu_items,
    )); ?>
  </div><!-- mainmenu -->
  <?php if(isset($this->breadcrumbs)):?>
    <?php $this->widget('zii.widgets.CBreadcrumbs', array(
      'links'=>$this->breadcrumbs,
    )); ?><!-- breadcrumbs -->
  <?php endif?>

  <?php echo $content; ?>

  <div class="clear"></div>

  <div id="footer">
    Website based on <a href="http://loristissino.github.com/delt/">DELT</a>, Double Entry Learning Tool (release <?php echo DELT::getVersion() ?>) - Copyright &copy; <?php echo date('Y'); ?> by DELT Project. All Rights Reserved.<br/>
    <?php echo Yii::powered(); ?>
    <?php echo Yii::app()->params['tagLine'] ?>
  </div><!-- footer -->

</div><!-- page -->
<?php if(isset(Yii::app()->params['analytics'])) include_once(Yii::app()->params['analytics']) ?>
</body>
</html>
