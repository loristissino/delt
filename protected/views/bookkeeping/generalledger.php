<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
  'Bookkeeping/Accounting'=>array('/bookkeeping'),
  $model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'General Ledger',
);

$this->show_link_on_description = true;

?>
<h1><?php echo Yii::t('delt', 'General Ledger') ?></h1>

<?php foreach($model->getLedgerDataCache() as $code=>$value): 
  $debitgrandototal = $model->getLedgerDataTotalDebit($code);
  $creditgrandtotal = -$model->getLedgerDataTotalCredit($code);
  $grandtotal = $debitgrandototal+$creditgrandtotal;
  $this->last_journalentry_id = null;
?>

<h2><?php echo $code ?> - <?php echo CHtml::link($value['currentname'], array('bookkeeping/ledger', 'id'=>$value['id']), array('class'=>'hiddenlink')) ?></h2>

  <?php echo $this->renderPartial('_ledger', array(
    'id'=>'ledger-grid-'.$code,
    'dataProvider'=>$model->getLedgerDataAsDataProvider($code),
    'debitgrandtotal'=>$debitgrandototal,
    'creditgrandtotal'=>$creditgrandtotal,
    'grandtotal'=>$grandtotal,
    ), true) ?>


<hr />

<?php endforeach ?>
