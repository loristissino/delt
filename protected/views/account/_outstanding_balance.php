<?php echo ($account->outstanding_balance===null and $account->is_selectable) ? '/' : Yii::t('delt', $account->outstanding_balance.'<!-- outstanding balance -->') ?>
