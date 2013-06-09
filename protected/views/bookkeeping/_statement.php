<?php
  $tag0 = 'h' . $hlevel;
  $tag1 = 'h' . ($hlevel+1);
?>
<<?php echo $tag0 ?>><?php echo Yii::t('delt', $title) ?></<?php echo $tag0 ?>>

<?php $ggt = 0 ?>
<?php foreach($order as $key=>$value): ?>
<?php if($with_subtitles): ?>
  <<?php echo $tag1 ?>><?php echo Yii::t('delt', $value) ?></<?php echo $tag1 ?>>
<?php endif ?>
<div class="statementtable" style="width: <?php echo 300 + 100*($level) ?>px">
<table>
<tbody>
<?php $gt=0; $lastlevel = 0; foreach($data as $account): ?>
  <?php if($account['type']==$key): ?>
    <tr class="statementrow level<?php echo $account['level'] ?>">
      <td style="width: 300px;">
        <?php if(!$account['is_selectable']): ?>
          <?php echo Yii::t('delt', 'Total:') ?>
        <?php endif ?>
        <?php if($links): ?>
          <?php echo CHtml::link($account['name'], $this->createUrl('bookkeeping/ledger', array('id'=>$account['id'])), array('class'=>'hiddenlink')) ?>
        <?php else: ?>
          <?php echo $account['name'] ?>
        <?php endif ?>
      </td>
      <?php $found=false; for($i=$level; $i>=1; $i--): ?>
        <td class="currency<?php if($found) echo ' empty' ?><?php if($account['level']==$i-1 && $lastlevel==$i) echo ' secondtolast' ?>" style="width: 100px;">
          <?php if($account['level']==$i): $found=true ?>
              <?php $amount = $key=='+' ? $account['amount'] : -$account['amount'] ?>
              <?php $lastlevel = $i; echo DELT::currency_value(
                $amount, 
                $model->currency,
                false,
                false,
                'span',
                array('class'=>($amount>0 ? 'positiveamount': 'negativeamount'))
                )
              ?>
          <?php else: ?>
            &nbsp;
          <?php endif ?>
        </td>
      <?php endfor ?>
    </tr>
  <?php endif ?>
  <?php if($account['type']==$key && $account['level']==1) $gt+=$amount ?>
<?php endforeach ?>
</tbody>
<tfoot>
<tr>
  <th><?php echo Yii::t('delt', 'Grandtotal') ?></th>
  <?php for($i=1; $i< $level; $i++): ?>
    <th>&nbsp;</th>
  <?php endfor ?>
  <td class="currency"><?php echo DELT::currency_value($gt, $model->currency) ?></td>
</tr>
</tfoot>
</table>
</div>
<?php if($key=='+') $ggt += $gt; else $ggt -= $gt; ?>
<?php endforeach ?>

<?php if($with_subtitles and $ggt): ?>

<div class="statementtable" style="width: <?php echo 300 + 100*($level) ?>px">
<table>
  <tr class="statementrow aggregate">
    <td style="width: 300px;"><?php echo Yii::t('delt', 'Aggregate Grandtotal') ?></td>
    <td class="currency"><?php echo DELT::currency_value($ggt, $model->currency) ?></td>
  </tr>
</table>
</div>

<?php endif ?>
