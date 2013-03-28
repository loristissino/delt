<?php echo CHtml::link($account, $this->createUrl('bookkeeping/ledger', array('id'=>$account->id, 'post'=>$post->id)), array('class'=>'hiddenlink')) ?>
<?php if(!$account->is_selectable): ?>
  <span class="warning" title="<?php echo Yii::t('delt', 'This account now has children, so it should not be used directly anymore.') ?>">
  <?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/bell.png') ?>
  </span>
<?php endif ?>

