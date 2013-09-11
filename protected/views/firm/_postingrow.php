<tr <?php if($excluded) echo 'class="excluded"' ?>>
  <td></td>
  <td></td>
  <td>
    <span class="<?php echo $posting->amount > 0 ? 'accountname_normal': 'accountname_indented' ?>"><?php echo $posting->account->name ?></span>
  </td>
  <td class="currency">
    <?php if($posting->amount>0) echo DELT::currency_value($posting->amount, $this->firm->currency) ?>
  </td>
  <td class="currency">
    <?php if($posting->amount<0) echo DELT::currency_value(-$posting->amount, $this->firm->currency) ?>
  </td>
</tr>
