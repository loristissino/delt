<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Ledger',
);

$debitgrandtotal = $account->debitgrandtotal;
$creditgrandtotal = $account->creditgrandtotal;
$grandtotal = $debitgrandtotal + $creditgrandtotal;

$deletable = $debitgrandtotal==0 && $creditgrandtotal==0;

//$url=CHtml::normalizeUrl(array('account/delete','id'=>$account->id));

$this->menu=array(
	array('label'=>Yii::t('delt', 'Chart of accounts'), 'url'=>array('bookkeeping/accountschart', 'slug'=>$model->slug)),
	array('label'=>Yii::t('delt', 'Edit'), 'url'=>array('account/update', 'id'=>$account->id)),
  array('label'=>Yii::t('zii', 'Delete'), 'url'=>$url=CHtml::normalizeUrl(array('account/delete','id'=>$account->id)),  'linkOptions'=>array(   
      'submit' => $url,
      'title' => Yii::t('delt', 'Delete this account'),
      'confirm' => Yii::t('zii', 'Are you sure you want to delete this item?'),
    ),
  ),
);

?>
<h1><?php echo Yii::t('delt', 'Ledger') ?></h1>

<h2><?php echo $account->code ?> - <?php echo $account->name ?></h2>

<?php if(!$deletable): ?>
<div class="balance">
  <p>
    <?php echo Yii::t('delt', 'Outstanding balance') ?>: <?php echo DELT::currency_value($grandtotal, $this->firm->currency, true) ?>.
    <?php if((($account->outstanding_balance=='C') and ($grandtotal>0)) or (($account->outstanding_balance=='D') and ($grandtotal<0))): ?>
      <span class="warning" title="<?php echo Yii::t('delt', 'According to its definition, the account should not have this kind of outstanding balance.') ?>"><?php echo Yii::t('delt', 'Check the debits and the credits.') ?></span>
    <?php endif ?>
  </p>
</div>
<?php endif ?>

<?php if($account->number_of_children==0): ?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'firm-grid',
	'dataProvider'=>$account->getDebitcreditsAsDataProvider(),
	'columns'=>array(
		'post.date',
    'post.description',
    array(
      'class'=>'CDataColumn',
      'name'=>'debit',
      'value'=>array($this, 'RenderDebit'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      'footer'=>DELT::currency_value($debitgrandtotal, $this->firm->currency),
      'footerHtmlOptions'=>array('class'=>'currency grandtotal'),
      ),
    array(
      'class'=>'CDataColumn',
      'name'=>'credit',
      'value'=>array($this, 'RenderCredit'),
      'type'=>'raw',
      'htmlOptions'=>array('class'=>'currency'),
      'footer'=>DELT::currency_value(-$creditgrandtotal, $this->firm->currency),
      'footerHtmlOptions'=>array('class'=>'currency grandtotal'),
      ),
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
<?php else: ?>
<p><?php echo Yii::t('delt', 'The following accounts belong here:') ?></p>
<ul>
<?php foreach($account->children as $child_account): ?>
<li><?php echo $child_account->code ?> - <?php echo CHtml::link($child_account->name, array('bookkeeping/ledger', 'id'=>$child_account->id)) ?></li>
<?php endforeach ?>
</ul>

<?php endif ?>
