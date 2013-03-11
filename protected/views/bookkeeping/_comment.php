<?php if((($account->outstanding_balance=='C') and ($value>0)) or (($account->outstanding_balance=='D') and ($value<0))): ?>
  <span class="warning" title="<?php echo Yii::t('delt', 'According to its definition, the account should not have this kind of outstanding balance.') ?>">
  <?php if($only_icon): ?>
    <?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/bell.png') ?>
  <?php else: ?>
    <?php echo Yii::t('delt', 'Check the debits and the credits.') ?>
  <?php endif ?>
  </span>
<?php endif ?>
