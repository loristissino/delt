<tr>
  <td></td>
  <td></td>
  <td>
    <?php echo $debitcredit->account->name ?>
  </td>
  <td class="currency">
    <?php if($debitcredit->amount>0) echo DELT::currency_value($debitcredit->amount, $this->firm->currency) ?>
  </td>
  <td class="currency">
    <?php if($debitcredit->amount<0) echo DELT::currency_value(-$debitcredit->amount, $this->firm->currency) ?>
  </td>
</tr>
