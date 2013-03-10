<?php if((($account->outstanding_balance=='C') and ($value>0)) or (($account->outstanding_balance=='D') and ($value<0))): ?>
  <span class="warning" title="<?php echo Yii::t('delt', 'According to its definition, the account should not have this kind of outstanding balance.') ?>"><?php echo Yii::t('delt', 'Check the debits and the credits.') ?></span>
<?php endif ?>
