<?php if($account->outstanding_balance===null and $account->is_selectable) echo '/'; else switch($account->outstanding_balance){
  case 'D': echo Yii::t('delt', 'Dr.<!-- outstanding balance -->') . '&nbsp;&nbsp;&nbsp;&nbsp;'; break;
  case 'C': echo '&nbsp;&nbsp;&nbsp;&nbsp;' . Yii::t('delt', 'Cr.<!-- outstanding balance -->'); break;
}?>
