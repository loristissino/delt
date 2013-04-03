<?php $class=($account->level == 1) ? 'main' : ($account->is_selectable ? 'selectable' : 'unselectable') ?>
<span class="account_<?php echo $class ?>" style="padding-left: <?php echo 10*$account->level - 10 ?>px"><?php echo CHtml::link($account->name, array('bookkeeping/ledger', 'id'=>$account->id), array('class'=>'hiddenlink', 'title'=>Yii::t('delt', 'Ledger for account «{name}»', array('{name}'=>$account->name)))) ?></span>
<?php if($account->comment): ?>
  <?php echo $this->createIcon('comment', Yii::t('delt', 'Comment'), array('width'=>16, 'height'=>12, 'title'=>$account->comment)) ?>
<?php endif ?>

