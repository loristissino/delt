<?php
/* @var $this FirmController */

$this->layout='//layouts/html5';
/*
$this->breadcrumbs=array(
	'Firms'=>array('/firm/index'),
	$model->name => array('/firm/public', 'slug'=>$model->slug),
  'Public',
);
*/
$debitcredits = $this->firm->getPostsAsDataProvider()->data;

?>

<article>
<h1><?php echo $model->name ?></h1>
<section>
<h2><?php echo Yii::t('delt', 'Journal') ?></h2>

<p>This is only a proof of concept. This page will contain all public information available about this firm.</p>

<table>
  <tr>
    <th><?php echo Yii::t('delt', 'No.') ?></th>
    <th><?php echo Yii::t('delt', 'Date') ?></th>
    <th><?php echo Yii::t('delt', 'Description') ?></th>
    <th><?php echo Yii::t('delt', 'Debit') ?></th>
    <th><?php echo Yii::t('delt', 'Credit') ?></th>
  </tr>
<?php $n=0; $postid=0; $td=0; $tc=0; foreach($debitcredits as $debitcredit): ?>
  <?php if($postid!=$debitcredit->post_id): $postid=$debitcredit->post_id ?>
  <tr>
    <td class="firstpostrow"><?php echo ++$n ?></td>
    <td class="firstpostrow">
      <?php echo Yii::app()->dateFormatter->formatDateTime($debitcredit->post->date, 'short', null) ?>
    </td>
    <td class="journaldescription firstpostrow">
      <?php echo $debitcredit->post->description ?>
    </td>
    <td class="firstpostrow"></td>
    <td class="firstpostrow"></td>
  </tr>
  <?php echo $this->renderPartial('_debitcreditrow', array('debitcredit'=>$debitcredit)); if($debitcredit->amount>0) $td+=$debitcredit->amount; else $tc-=$debitcredit->amount ?>
  <?php else: ?>
  <?php echo $this->renderPartial('_debitcreditrow', array('debitcredit'=>$debitcredit)); if($debitcredit->amount>0) $td+=$debitcredit->amount; else $tc-=$debitcredit->amount ?>
  <?php endif ?>
<?php endforeach ?>
  <tr>
    <td class="firstpostrow" colspan="2"></td>
    <td class="firstpostrow">
      <?php echo Yii::t('delt', 'Total:') ?>
    </td>
    <td class="firstpostrow currency lastpostrow"><?php echo DELT::currency_value($td, $this->firm->currency) ?></td>
    <td class="firstpostrow currency lastpostrow"><?php echo DELT::currency_value($tc, $this->firm->currency) ?></td>
  </tr>
</table>
</section>

</article>
