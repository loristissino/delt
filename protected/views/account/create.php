<?php
/* @var $this AccountController */
/* @var $account Account */
/* @var $firm Firm */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $firm->name => array('/bookkeeping/manage', 'slug'=>$firm->slug),
  $account->isHidden()?'Statements configuration':'Chart of accounts' => array($account->isHidden()?'/bookkeeping/configure':'/bookkeeping/coa', 'slug'=>$firm->slug),
  $account->isHidden()?'New item':'New account',
  
);

?>

<h1><?php echo Yii::t('delt', $account->isHidden()?'Create new item':'Create new account') ?></h1>

<?php echo $this->renderPartial('_children', array('parent'=>$parent, 'type'=>'create')); ?>

<?php echo $this->renderPartial('_form', array('model'=>$account)); ?>
