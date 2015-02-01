<?php /* @var $this Controller */ 

$mainmenu_items=array(
        array('label'=>Yii::t('delt', 'Home'), 'url'=>array('/site/'. Yii::app()->language . '/index')),
        array('label'=>Yii::t('delt','About'), 'url'=>array('/site/'. Yii::app()->language . '/about')),
        array('label'=>Yii::t('delt','Contact'), 'url'=>array('/site/contact')),
        array('label'=>Yii::t('delt','Bookkeeping/Accounting'), 'url'=>array('/bookkeeping/index'), 'visible'=>!Yii::app()->user->isGuest),
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
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/html5.css" >
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print5.css" media="print" />
  <meta charset="utf-8" />
  <?php if($this->css): ?>
  <style><?php echo $this->css ?></style>
  <?php endif ?>
</head>

<body>
  <header>
    <div id="logo"><?php echo $this->createIcon('LearnDoubleEntryHeading', 'LearnDoubleEntry.org', array('width'=>400, 'height'=>63, 'title'=>'Benvenuto su LearnDoubleEntry.org')) ?></div>
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
    <hr />
    <p>This page belongs to the website <?php echo CHtml::link(Yii::app()->name, $this->createUrl('/site')) ?>.</p>
  </footer>

  <?php if(isset(Yii::app()->params['analytics'])) include_once(Yii::app()->params['analytics']) ?>
</body>
</html>
