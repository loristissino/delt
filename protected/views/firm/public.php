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
<h1><?php echo CHtml::encode($model->name) ?></h1>
<p><?php echo CHtml::encode($model->description) ?></p>
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
<?php $n=0; $postid=0; $td=0; $tc=0; foreach($postings as $posting): $excluded=!$posting->post->is_included ?>
  <?php if($postid!=$posting->post_id): $postid=$posting->post_id ?>
  <tr <?php if($excluded) echo 'class="excluded"' ?>>
    <td class="firstpostrow"><?php echo ++$n ?></td>
    <td class="firstpostrow">
      <?php echo Yii::app()->dateFormatter->formatDateTime($posting->post->date, 'short', null) ?>
    </td>
    <td class="journaldescription firstpostrow">
      <?php echo $posting->post->description ?>
    </td>
    <td class="firstpostrow"></td>
    <td class="firstpostrow"></td>
  </tr>
  <?php echo $this->renderPartial('_postingrow', array('posting'=>$posting, 'excluded'=>$excluded)); if(!$excluded) {if($posting->amount>0) $td+=$posting->amount; else $tc-=$posting->amount;} ?>
  <?php else: ?>
  <?php echo $this->renderPartial('_postingrow', array('posting'=>$posting, 'excluded'=>$excluded)); if(!$excluded) {if($posting->amount>0) $td+=$posting->amount; else $tc-=$posting->amount;} ?>
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
  'title'=>'Balance Sheet',
  'data'=>$bs,
  'model'=>$model,
  'level'=>$level,
  'order'=>array('+'=>'Assets', '-'=>'Liabilities and Equity'),
  'with_subtitles'=>true,
  'hlevel'=>3,
  'links'=>false,
  )) ?>
<?php echo $this->renderPartial('/bookkeeping/_statement', array(
  'title'=>'Income Statement',
  'data'=>$is,
  'model'=>$model,
  'level'=>$level,
  'order'=>array('+'=>'Value Added Income Statement'),
  'with_subtitles'=>false,
  'hlevel'=>3,
  'links'=>false,
  )) ?>
</section>  
  
<hr />

<?php echo $model->getLicenseCode($this) ?>

</article>
