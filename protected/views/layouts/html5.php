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
  <style type="text/css">
body
{
	margin: 20px;
	padding: 0;
	color: #555;
	font: normal 10pt Arial,Helvetica,sans-serif;
	background: #ffffff;
}

h1
{
  font-size: 2em;
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
.warning
{
  color: orange;
}

div.statementtable
{
  /* background-color: yellow; */
}

div.statementtable table td
{
  color: black;
  border-bottom-style: dotted;
  border-width: 1px;
}

div.statementtable table td.empty
{
  color: black;
  border-style: none;
  background-color: white;
}

div.statementtable table td.secondtolast
{
  border-top-style: solid;
  border-top-width: 1px;
}

div.statementtable table tr.level1
{
  background-color: black; /*#d7d7d7;*/
}

div.statementtable table tr.level1 td
{
  color: white;
}

div.statementtable table tr.level1 td a
{
  color: white;
}

div.statementtable table tr.level2
{
  background-color: #f0f0f0;
}

div.statementtable table tr.level3
{
  background-color: #f7f7f7;
}

div.statementtable table tr.level4
{
  color: blue;
  background-color: #ffffff;
}

div.statementtable table tr.level5
{
  color: #999999;
  background-color: #ffffff;
}

div.statementtable table tr.level6
{
  background-color: #ffffff;
}

div.statementtable table tr.aggregate
{
  background-color: #D6D6D6;
}

span.positiveamount
{
}

span.negativeamount
{
  color: red;
}

span.accountname_normal
{
}

span.accountname_indented
{
  padding-left: 20px;
}

tr.excluded
{
  color: #888888;
  text-decoration:line-through;
}

  </style>
	<meta charset="utf-8" />
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
    <p>This is an experimental HTML5 web page for one of the public firms created at <?php echo CHtml::link(Yii::app()->name, $this->createUrl('/site')) ?>.</p>
  </footer>

  <?php if(isset(Yii::app()->params['analytics'])) include_once(Yii::app()->params['analytics']) ?>
</body>
</html>
