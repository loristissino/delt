<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Ledger',
);

$noc = $account->number_of_children;
$deletable = true;

if($noc==0)
{
  $debitgrandtotal = $account->debitgrandtotal;
  $creditgrandtotal = $account->creditgrandtotal;
  $grandtotal = $debitgrandtotal + $creditgrandtotal;
  $deletable = $debitgrandtotal==0 && $creditgrandtotal==0;
}
else
{
  $grandtotal = $account->consolidatedBalance;
  $deletable = false;
}

$this->show_link_on_description = true;

$this->menu=array(
  array('label'=>Yii::t('delt', 'Edit'), 'url'=>array('account/update', 'id'=>$account->id)),
  );

if($deletable)
{
  $this->menu[]= array('label'=>Yii::t('delt', 'Delete'), 'url'=>$url=$this->createUrl('account/delete', array('id'=>$account->id)),  'linkOptions'=>array(   
      'submit' => $url,
      'title' => Yii::t('delt', 'Delete this account'),
      'confirm' => Yii::t('zii', 'Are you sure you want to delete this item?') . ($account->number_of_children ? ' ' . Yii::t('delt', 'The children accounts won\'t be deleted, but they will remain orphans.') : ''),
    ),
  );
}

?>
<h1><?php echo Yii::t('delt', 'Ledger') ?></h1>

<h2><?php echo $account->code ?> - <?php echo $account->name ?></h2>

<?php if($account->comment): ?>
  <?php echo $this->renderPartial('../account/_clevercomment', array('account'=>$account), true) ?>
<?php endif ?>

<?php if(!$deletable): ?>
<div class="balance">
  <p>
    <?php echo Yii::t('delt', 'Outstanding balance') ?>: <?php echo DELT::currency_value($grandtotal, $this->firm->currency, true, true) ?>.
    <?php echo $this->renderPartial('_comment', array('account'=>$account, 'value'=>$grandtotal, 'only_icon'=>false), true) ?>
  </p>
</div>
<?php endif ?>

<?php if($account->number_of_children==0): ?>

  <?php echo $this->renderPartial('_ledger', array(
    'id'=>'ledger-grid',
    'dataProvider'=>$account->getPostingsAsDataProvider(),
    'debitgrandtotal'=>$debitgrandtotal,
    'creditgrandtotal'=>$creditgrandtotal,
    'grandtotal'=>$grandtotal,
    ), true) ?>
    
<?php else: ?>
<p><?php echo Yii::t('delt', 'The above outstanding balance is the consolidated algebraic sum of the debits and the credits of the following accounts:') ?></p>
<ul>
<?php foreach($account->children as $child_account): ?>
<li><?php echo $child_account->code ?> - <?php echo CHtml::link($child_account->name, array('bookkeeping/ledger', 'id'=>$child_account->id)) ?></li>
<?php endforeach ?>
</ul>

<?php endif ?>

<?php if($account->account_parent_id): ?>
<hr />
<p><?php echo Yii::t('delt', 'Parent account') ?>: <?php echo CHtml::link($account->parentAccount, $this->createUrl('bookkeeping/ledger', array('id'=>$account->account_parent_id))) ?>.</p>
<?php endif ?>
