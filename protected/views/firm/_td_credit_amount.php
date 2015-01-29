<td class="currency<?php if(isset($extraclasses)) echo ' ' . $extraclasses ?>">
  <?php if($amount<0) echo DELT::currency_value(-$amount, $this->firm->currency) ?>
</td>
