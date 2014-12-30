<?php
/* @var $this FirmController */

$this->layout='//layouts/html5';
$this->pageTitle=Yii::app()->name . ' - ' . $model->name;
/*
$this->breadcrumbs=array(
  'Firms'=>array('/firm/index'),
  $model->name => array('/firm/public', 'slug'=>$model->slug),
  'Public',
);
*/
$this->css=$model->css;

?>

<article>
<h1><?php echo CHtml::encode($model->name) ?></h1>
<p><?php echo CHtml::encode($model->description) ?></p>
<?php echo $this->renderPartial('_banner', array('firm'=>$model)) ?>
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
<?php $n=0; $journalentryid=0; $td=0; $tc=0; foreach($postings as $posting): $excluded=!$posting->journalentry->is_included ?>
  <?php if($journalentryid!=$posting->journalentry_id): $journalentryid=$posting->journalentry_id ?>
  <tr id="entry<?php echo ++$n ?>" <?php if($excluded) echo 'class="excluded"' ?>>
    <td class="firstjournalentryrow"><?php echo CHtml::link($n, '#entry'. $n, array('class'=>'hiddenlink')) ?></td>
    <td class="firstjournalentryrow">
      <?php echo Yii::app()->dateFormatter->formatDateTime($posting->journalentry->date, 'short', null) ?>
    </td>
    <td class="journaldescription firstjournalentryrow">
      <?php echo $posting->journalentry->description ?>
    </td>
    <td class="firstjournalentryrow"></td>
    <td class="firstjournalentryrow"></td>
  </tr>
  <?php echo $this->renderPartial('_postingrow', array('posting'=>$posting, 'firm'=>$model, 'excluded'=>$excluded)); if(!$excluded) {if($posting->amount>0) $td+=$posting->amount; else $tc-=$posting->amount;} ?>
  <?php else: ?>
  <?php echo $this->renderPartial('_postingrow', array('posting'=>$posting, 'firm'=>$model, 'excluded'=>$excluded)); if(!$excluded) {if($posting->amount>0) $td+=$posting->amount; else $tc-=$posting->amount;} ?>
  <?php endif ?>
<?php endforeach ?>
  <tr>
    <td class="firstjournalentryrow" colspan="2"></td>
    <td class="firstjournalentryrow">
      <?php echo Yii::t('delt', 'Total:') ?>
    </td>
    <td class="firstjournalentryrow currency lastjournalentryrow"><?php echo DELT::currency_value($td, $this->firm->currency) ?></td>
    <td class="firstjournalentryrow currency lastjournalentryrow"><?php echo DELT::currency_value($tc, $this->firm->currency) ?></td>
  </tr>
</table>
</section>
<hr />

<section>
<h2><?php echo Yii::t('delt', 'Statements') ?></h2>
<?php foreach($model->getMainPositions() as $statement): ?>
  <?php echo $this->renderPartial('/bookkeeping/_statement', array(
    'statement'=>$statement,
    'data'=>$model->getStatement($statement, $level),
    'model'=>$model,
    'level'=>$level,
    'maxlevel'=>$maxlevel,
    'hlevel'=>3,
    'links'=>true,
    )) ?>
<?php endforeach ?>
</section>  
  
<hr />

<?php echo $this->renderPartial('_frostiness', array('model'=>$model)) ?>

<?php echo $model->getLicenseCode($this) ?>

</article>
