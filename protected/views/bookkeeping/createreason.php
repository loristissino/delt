<?php
/* @var $this BookkeepingController */

$this->breadcrumbs=array(
	'Bookkeeping'=>array('/bookkeeping'),
	$model->name => array('/bookkeeping/manage', 'slug'=>$model->slug),
  'Journal' =>array('/bookkeeping/journal', 'slug'=>$model->slug),
  'Reason creation',
);

$this->menu=array(
);

?>
<h1><?php echo Yii::t('delt', 'Reason creation') ?></h1>

<p><?php echo Yii::t('delt', 'You are going to create a new reason with the following accounts:') ?></p>
<ul>
<?php foreach($this->post->debitcredits as $debitcredit): $type=DELT::amount2type($debitcredit->amount) ?>
  <li>
  <?php echo $debitcredit->account ?> (<?php echo Yii::t('delt', $type) ?>)
  </li>
<?php endforeach ?>
</ul>
<?php echo $this->renderPartial('_reason', array('model'=>$reason)) ?>
