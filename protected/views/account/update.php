<?php
/* @var $this AccountController */
/* @var $account Account */
/* @var $firm Firm */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $firm->name => array('/bookkeeping/manage', 'slug'=>$firm->slug),
  $account->isHidden()?'Statements configuration':'Chart of Accounts' => array($account->isHidden()?'/bookkeeping/configure':'/bookkeeping/coa', 'slug'=>$firm->slug),
  $account->name,
);

$noc = $account->number_of_children;
$deletable = $account->isHidden();

if($noc==0)
{
  $debitgrandtotal = $account->debitgrandtotal;
  $creditgrandtotal = $account->creditgrandtotal;
  $grandtotal = $debitgrandtotal + $creditgrandtotal;
  $deletable = $debitgrandtotal==0 && $creditgrandtotal==0;
}
else
{
  $deletable = false;
}

if($deletable)
{
  $this->menu=array(
   array('label'=>Yii::t('delt', 'Delete'), 'url'=>$url=$this->createUrl('account/delete', array('id'=>$account->id)),  'linkOptions'=>array(   
      'submit' => $url,
      'title' => Yii::t('delt', 'Delete this item'),
      'confirm' => Yii::t('zii', 'Are you sure you want to delete this item?') . ($account->number_of_children ? ' ' . Yii::t('delt', 'The children accounts won\'t be deleted, but they will remain orphans.') : ''),
    ),
  )
  );
}


?>

<h1><?php echo Yii::t('delt', $account->isHidden()?'Edit item «{name}»':'Edit account «{name}»', array('{name}'=>$account->name)) ?></h1>

<?php echo $this->renderPartial('_children', array('parent'=>$parent, 'type'=>$moving?'moving':'update')); ?>

<?php echo $this->renderPartial('_form', array('model'=>$account)); ?>
