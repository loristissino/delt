<?php $this->beginWidget('zii.widgets.jui.CJuiDraggable', array(
    'options'=>array(
        'cursor'=>'move',
    ),
    'htmlOptions'=>array(
//        'style'=>'width: 150px; height: 150px; padding: 5px; border: 1px solid #e3e3e3; background: #f7f7f7'
    ),
));
?>
<p style="background-color: black; color: white; font-weight: bold; text-align: center"><?php echo Yii::t('delt', 'Help on transaction') ?></p>
<hr />

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
  <?php echo $this->renderPartial('/firm/_postingrow', array('posting'=>$posting, 'firm'=>$model, 'excluded'=>$excluded, 'linked'=>false))?>
  <?php else: ?>
  <?php echo $this->renderPartial('/firm/_postingrow', array('posting'=>$posting, 'firm'=>$model, 'excluded'=>$excluded, 'linked'=>false)) ?>
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
<?php $this->endWidget(); ?>
