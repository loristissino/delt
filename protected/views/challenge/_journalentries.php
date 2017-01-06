<?php if ($draggable) $this->beginWidget('zii.widgets.jui.CJuiDraggable', array(
    'options'=>array(
        'cursor'=>'move',
    ),
    'htmlOptions'=>array(
//        'style'=>'width: 150px; height: 150px; padding: 5px; border: 1px solid #e3e3e3; background: #f7f7f7'
    ),
));
?>
<?php if (sizeof($postings)): ?>
<table style="background-color: white; color: black; width:650px">
  <?php if ($title): ?>
    <tr>
      <th colspan="5" style="border-bottom: 3px solid white; background-color: gray; color: white; font-weight: bold; text-align: center"><?php echo $title ?></th>
    </tr>
  <?php endif ?>
  <tr>
    <th style="width: 20px"><?php echo Yii::t('delt', 'No.') ?></th>
    <th style="width: 80px"><?php echo Yii::t('delt', 'Date') ?></th>
    <th style="width: 300px"><?php echo Yii::t('delt', 'Description') ?></th>
    <th class="currency" style="width: 80px"><?php echo Yii::t('delt', 'Debit') ?></th>
    <th class="currency" style="width: 80px"><?php echo Yii::t('delt', 'Credit') ?></th>
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
</table>
<?php endif ?>
<?php if ($draggable) $this->endWidget(); ?>
