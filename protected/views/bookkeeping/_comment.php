<?php if((($account->outstanding_balance=='C') and ($value>0)) or (($account->outstanding_balance=='D') and ($value<0))): ?>
  <span class="warning" title="<?php echo Yii::t('delt', 'According to its definition, the account should not have this kind of outstanding balance.') ?>">
  <?php if($only_icon): ?>
    <?php echo $this->createIcon('bell', Yii::t('delt', 'warning'), array('width'=>16, 'height'=>16)) ?>
  <?php else: ?>
    <?php echo Yii::t('delt', 'Check the debits and the credits.') ?>
  <?php endif ?>
  </span>
<?php endif ?>
<span class="rawvalue" id="account_<?php echo $account->id ?>" data-rawvalue="<?php echo $value ?>" />
