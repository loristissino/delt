<tr <?php if($excluded) echo 'class="excluded"' ?>>
  <td></td>
  <td></td>
  <td>
    <span class="<?php echo $posting->amount > 0 ? 'accountname_normal': 'accountname_indented' ?> <?php echo $posting->account->classes ?>"><?php echo CHtml::link($posting->account->name, array('firm/ledger', 'slug'=>$firm->slug, 'account'=>$posting->account_id), array('class'=>'hiddenlink')) ?></span>
    <?php if($posting->comment): ?>
      <em> (<?php echo $posting->comment ?>)</em>
    <?php endif ?>
  </td>
  <?php echo $this->renderPartial('_td_debit_amount', array('amount'=>$posting->amount)) ?>
  <?php echo $this->renderPartial('_td_credit_amount', array('amount'=>$posting->amount)) ?>
</tr>
