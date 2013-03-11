<?php echo $this->renderPartial('_comment', array('account'=>$account, 'value'=>$value, 'only_icon'=>true), true) ?>
<?php echo DELT::currency_value($account->debitgrandtotal + $account->creditgrandtotal, $this->firm->currency, true) ?>
