<tr>
  <td></td>
  <td></td>
  <td>
    <span class="<?php echo $debitcredit->amount > 0 ? 'accountname_normal': 'accountname_indented' ?>"><?php echo $debitcredit->account->name ?></span>
  </td>
  <td class="currency">
    <?php if($debitcredit->amount>0) echo DELT::currency_value($debitcredit->amount, $this->firm->currency) ?>
  </td>
  <td class="currency">
    <?php if($debitcredit->amount<0) echo DELT::currency_value(-$debitcredit->amount, $this->firm->currency) ?>
  </td>
</tr>
