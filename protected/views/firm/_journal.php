<section id="journal">
<h2><?php echo $title ?></h2>

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
        <span style="text-decoration-line: underline; text-decoration-style: solid; text-decoration-color: #<?php echo $posting->journalentry->section->color ?>; text-decoration-thickness: 5px" title="<?php echo $posting->journalentry->section->name ?>">
        <?php echo Yii::app()->dateFormatter->formatDateTime($posting->journalentry->date, 'short', null) ?>
        </span>
    </td>
    <td class="journaldescription firstjournalentryrow">
      <?php echo $editjournalentry ? 
        Chtml::link($posting->journalentry->description, array('bookkeeping/updatejournalentry', 'id'=>$posting->journalentry_id))
        :
        $posting->journalentry->description ?>
    </td>
    <td class="firstjournalentryrow"></td>
    <td class="firstjournalentryrow"></td>
  </tr>
  <?php echo $this->renderPartial('//firm/_postingrow', array('posting'=>$posting, 'firm'=>$model, 'excluded'=>$excluded, 'linked'=>$linked)); if(!$excluded) {if($posting->amount>0) $td+=$posting->amount; else $tc-=$posting->amount;} ?>
  <?php else: ?>
  <?php echo $this->renderPartial('//firm/_postingrow', array('posting'=>$posting, 'firm'=>$model, 'excluded'=>$excluded, 'linked'=>$linked)); if(!$excluded) {if($posting->amount>0) $td+=$posting->amount; else $tc-=$posting->amount;} ?>
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
<hr />
</section>
