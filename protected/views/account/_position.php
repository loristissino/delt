<?php if($account->position!='?'): ?>
  <?php echo Yii::t('delt', $account->positionLabel.'<!-- position -->') ?>
<?php else: ?>
<span class="warning">
  <?php echo $this->createIcon('bell', Yii::t('delt', 'warning'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'This account has not been correctly positioned.'))) ?>
</span>
<?php endif ?>
