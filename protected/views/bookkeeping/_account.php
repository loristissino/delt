<div class="j<?php echo $amount>0 ? 'debit':'credit' ?>"><?php echo CHtml::link($account->name, $this->createUrl('bookkeeping/ledger', array('id'=>$account->id, 'post'=>$post->id)), array('class'=>'hiddenlink')) ?>
<?php if(!$account->is_selectable): ?>
  <span class="warning" title="<?php echo Yii::t('delt', 'This account now has children, so it should not be used directly anymore.') ?>">
  <?php echo $this->createIcon('bell', Yii::t('delt', 'warning'), array('width'=>16, 'height'=>16)) ?>
  </span>
<?php endif ?>
<?php if($account->collocation =='?'): ?>
  <span class="warning" title="<?php echo Yii::t('delt', 'This account has not been correctly collocated.') ?>">
  <?php echo $this->createIcon('bell', Yii::t('delt', 'warning'), array('width'=>16, 'height'=>16)) ?>
  </span>
<?php endif ?></div>

