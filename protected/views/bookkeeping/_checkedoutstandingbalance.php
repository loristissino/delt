<?php echo $this->renderPartial('_comment', array('account'=>$account, 'value'=>$value), true) ?>
<?php echo DELT::currency_value($account->debitgrandtotal + $account->creditgrandtotal, $this->firm->currency, true) ?>
