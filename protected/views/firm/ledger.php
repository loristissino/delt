<?php
/* @var $this FirmController */

$this->layout='//layouts/html5';

?>

<article>
<h1><?php echo CHtml::link(CHtml::encode($model->name), array('/firms/'.$model->slug), array('class'=>'hiddenlink')) ?></h1>
<p><?php echo CHtml::encode($model->description) ?></p>
<section>
<h2><?php echo Yii::t('delt', 'Ledger') ?> - <?php echo sprintf('%s «%s»', $account->code, $account->name) ?></h2>

<table>
  <tr>
    <th><?php echo Yii::t('delt', 'Date') ?></th>
    <th><?php echo Yii::t('delt', 'Description') ?></th>
    <th><?php echo Yii::t('delt', 'Debit') ?></th>
    <th><?php echo Yii::t('delt', 'Credit') ?></th>
  </tr>
<?php $n=0; $journalentryid=0; $td=0; $tc=0; foreach($postings as $posting): $excluded=!$posting->journalentry->is_included ?>
  <?php if($journalentryid!=$posting->journalentry_id): $journalentryid=$posting->journalentry_id ?>
  <tr id="entry<?php echo ++$n ?>" <?php if($excluded) echo 'class="excluded"' ?>>
    <td>
      <?php echo Yii::app()->dateFormatter->formatDateTime($posting->journalentry->date, 'short', null) ?>
    </td>
    <td class="journaldescription">
      <?php echo $posting->journalentry->description ?>
    </td>
    <?php echo $this->renderPartial('_td_debit_amount', array('amount'=>$posting->amount)) ?>
    <?php echo $this->renderPartial('_td_credit_amount', array('amount'=>$posting->amount)) ?>
    <?php if(!$excluded) {if($posting->amount>0) $td+=$posting->amount; else $tc-=$posting->amount;} ?>
  </tr>
  <?php endif ?>
<?php endforeach ?>
  <tr>
    <td class="firstjournalentryrow"></td>
    <td class="firstjournalentryrow">
      <?php echo Yii::t('delt', 'Total:') ?>
    </td>
    <td class="firstjournalentryrow currency lastjournalentryrow"><?php echo DELT::currency_value($td, $this->firm->currency) ?></td>
    <td class="firstjournalentryrow currency lastjournalentryrow"><?php echo DELT::currency_value($tc, $this->firm->currency) ?></td>
  </tr>
</table>
</section>

<?php echo $model->getLicenseCode($this) ?>

</article>
