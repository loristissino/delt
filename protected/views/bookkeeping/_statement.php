<h2><?php echo Yii::t('delt', $title) ?></h2>

<?php foreach($order as $key=>$value): ?>
<h3><?php echo Yii::t('delt', $value) ?></h3>
<div class="statementtable" style="width: <?php echo 300 + 100*($level) ?>px">
<table>
<?php foreach($data as $account): ?>
  <?php if($this->_amountOfType($account['amount'], $key)): ?>
    <tr class="statementrow">
      <td style="width: 300px;">
        <?php if(!$account['is_selectable']): ?>
          <?php echo Yii::t('delt', 'Total:') ?>
        <?php endif ?>
        <?php echo CHtml::link($account['name'], $this->createUrl('bookkeeping/ledger', array('id'=>$account['id'])), array('class'=>'hiddenlink')) ?>
      </td>
      <?php for($i=$level; $i>=1; $i--): ?>
        <td class="currency"  style="width: 100px;">
          <?php if($account['level']==$i): ?>
              <?php echo DELT::currency_value(
                $key=='D' ? $account['amount'] : -$account['amount'], 
                $model->currency)
              ?>
          <?php else: ?>
            &nbsp;
          <?php endif ?>
        </td>
      <?php endfor ?>
    </tr>
  <?php endif ?>
<?php endforeach ?>
</table>
</div>
<?php endforeach ?>
