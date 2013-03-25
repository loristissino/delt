<h2><?php echo Yii::t('delt', $title) ?></h2>

<p>This is just a proof of concept...</p>

<table>
  <tr>
    <th><?php echo Yii::t('delt', 'Account') ?></th>
    <th><?php echo Yii::t('delt', 'Debit') ?></th>
    <th><?php echo Yii::t('delt', 'Credit') ?></th>
  </tr>
<?php foreach($data as $account): $size=(1+(4-$account['level'])/4) ?>
  <tr style="font-size: <?php echo $size ?>em">
    <td>
      <?php if(!$account['is_selectable']): ?>
        <?php echo Yii::t('delt', 'Total:') ?>
      <?php endif ?>
      <?php echo CHtml::link($account['code'] . ' - ' . $account['name'], $this->createUrl('bookkeeping/ledger', array('id'=>$account['id'])), array('class'=>'hiddenlink')) ?>
    </td>
    <td class="currency">
      <?php echo $account['amount']>0 ? DELT::currency_value($account['amount'], $model->currency) : '' ?>
    </td>
    <td class="currency">
      <?php echo $account['amount']<0 ? DELT::currency_value(-$account['amount'], $model->currency) : '' ?>
    </td>
  </tr>
<?php endforeach ?>
</table>
