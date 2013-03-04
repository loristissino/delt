<?php
/* @var $this AccountController */
/* @var $account Account */
/* @var $firm Firm */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$firm->name => array('/bookkeeping/manage', 'slug'=>$firm->slug),
  'Chart of accounts' => array('/bookkeeping/accountschart', 'slug'=>$firm->slug),
  $account->name,
);

$this->menu=array(
	array('label'=>Yii::t('delt', 'Chart of accounts'), 'url'=>array('/bookkeeping/accountschart', 'slug'=>$firm->slug)),
);
?>

<h1><?php echo Yii::t('delt', 'Create new account') ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$account)); ?>
