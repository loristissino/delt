<?php if(!isset($linked)) $linked=true ?>
<tr <?php if($excluded) echo 'class="excluded"' ?>>
  <td></td>
  <td></td>
  <td class="accountname">
    <span class="<?php echo $posting->amount > 0 ? 'accountname_normal': 'accountname_indented' ?>"><?php $name = CHtml::tag('span', array('class'=>'account' . ($posting->account->classes ? ' ' .$posting->account->classes: '')), 
      $posting->account->name) ?><?php if ($linked): ?>
        <?php echo CHtml::link($name, array('firm/ledger', 'slug'=>$firm->slug, 'account'=>$posting->account_id), array('class'=>'hiddenlink')) ?>
      <?php else: ?>
        <?php echo $name ?>
      <?php endif ?>
    </span>
    <?php if($posting->comment): ?>
      <em> (<?php echo $posting->comment ?>)</em>
    <?php endif ?>
  </td>
  <?php echo $this->renderPartial('/firm/_td_debit_amount', array('amount'=>$posting->amount)) ?>
  <?php echo $this->renderPartial('/firm/_td_credit_amount', array('amount'=>$posting->amount)) ?>
</tr>
