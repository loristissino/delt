<?php if($account->collocation!='?'): ?>
  <?php echo Yii::t('delt', $account->collocationlabel.'<!-- collocation -->') ?>
<?php else: ?>
<span class="warning">
  <?php echo $this->createIcon('bell', Yii::t('delt', 'warning'), array('width'=>16, 'height'=>16, 'title'=>Yii::t('delt', 'This account has not been correctly collocated.'))) ?>
</span>
<?php endif ?>
