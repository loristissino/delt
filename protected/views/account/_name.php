<?php $class=($account->level == 1) ? 'main' : ($account->is_selectable ? 'selectable' : 'unselectable') ?>
<span class="account_<?php echo $class ?>" style="padding-left: <?php echo 10*$account->level - 10 ?>px"><?php echo CHtml::link($account->name, array('bookkeeping/ledger', 'id'=>$account->id), array('class'=>'hiddenlink', 'title'=>Yii::t('delt', 'Ledger'))) ?></span>
