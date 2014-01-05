<?php
/* @var $this AccountController */
/* @var $account Account */
/* @var $firm Firm */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $firm->name => array('/bookkeeping/manage', 'slug'=>$firm->slug),
  $account->is_hidden?'Statements configuration':'Chart of accounts' => array($account->is_hidden?'/bookkeeping/configure':'/bookkeeping/coa', 'slug'=>$firm->slug),
  $account->name,
);

?>

<h1><?php echo Yii::t('delt', $account->is_hidden?'Edit item «{name}»':'Edit account «{name}»', array('{name}'=>$account->name)) ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$account)); ?>
