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

?>

<article>
<h1><?php echo $model->name ?></h1>
<section>
<h2><?php echo Yii::t('delt', 'Journal') ?></h2>

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
<hr />

<section>
<h2><?php echo Yii::t('delt', 'Statements') ?></h2>

<?php echo $this->renderPartial('/bookkeeping/_statement', array(
  'title'=>'Financial Statement',
  'data'=>$financial,
  'model'=>$model,
  'level'=>$level,
  'order'=>array('+'=>'Assets', '-'=>'Liabilities and Equity'),
  'with_subtitles'=>true,
  'hlevel'=>3,
  'links'=>false,
  )) ?>
<?php echo $this->renderPartial('/bookkeeping/_statement', array(
  'title'=>'Income Statement',
  'data'=>$economic,
  'model'=>$model,
  'level'=>$level,
  'order'=>array('+'=>'Value Added Income Statement'),
  'with_subtitles'=>false,
  'hlevel'=>3,
  'links'=>false,
  )) ?>
</section>  
  


</article>
