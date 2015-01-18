<?php if(!$account->hasValidPosition()): ?>
  <span class="warning">
    <?php echo $this->createIcon('bell', Yii::t('delt', 'warning'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'This account has not been correctly positioned («{position}»).', array('{position}'=>$account->position)))) ?>
  </span>
<?php else: ?>
  <?php echo $account->position ?>
<?php endif ?>

