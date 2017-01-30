<?php if(sizeof($data)): ?>
<?php

  $tag0 = 'h' . $hlevel;
  $tag1 = 'h' . ($hlevel+1);

  $add_to_level = max($maxlevel, 4) - $level; // we add this to every level to avoid blacks when not needed
    
  $order=array();
  
  switch($statement->type)
  {
    case 1:  // pancake
      $order['+']=$statement->currentname;
      $with_subtitles=false;
      $grandtotal_line = 'Net result';
      break;
    case 2:  // two separate sections
      foreach($statement->getChildren() as $child)
      {
        if (DELT::isuppercase($child->position))
        {
          $key=$child->outstanding_balance=='D' ? '+':'-';
          $order[$key]=$child->currentname;
        }
      }
      $with_subtitles=true;
      $grandtotal_line = ''; // 'Grandtotal';
      break;
    case 3:
      break;
  }
  
  $caption = str_replace('{date}', $last_date, $statement->getValueFromCommentByKeyword('@caption'));
  
?>

<?php echo CHtml::tag($tag0, array('class'=>'statement-title ' . ($caption?'withcaption':'withnocaption')), $statement->currentname) ?>
<?php if($caption): ?>
  <div class="caption"><?php echo $caption ?></div>
<?php endif ?>

<?php if(in_array($statement->type, array(1,2))): ?>

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
    <tr class="statementrow level<?php echo ($account['level'] +  $add_to_level) ?>">
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
<?php if($gt): ?>
  <tfoot>
  <tr>
    <th><?php //if($statement->type == 1) echo $model->findClosingAccountName($statement->position, ($gt>0 ? 'C':'D'), false, '') ?></th>
    <?php for($i=1; $i< $level; $i++): ?>
      <th>&nbsp;</th>
    <?php endfor ?>
    <td class="currency"><?php echo DELT::currency_value($gt, $model->currency) ?></td>
  </tr>
  </tfoot>
<?php endif ?>
</table>
</div>
<?php if($key=='+') $ggt += $gt; else $ggt -= $gt; ?>
<?php endforeach ?>

<?php if($with_subtitles and !DELT::nearlyZero($ggt)): ?>
<div class="statementtable" style="width: <?php echo 300 + 100*($level) ?>px">
<table>
  <tr class="statementrow aggregate">
    <td style="width: 500px;"><?php echo Yii::t('delt', 'Difference Yet Unexplained') ?></td>
    <td class="currency" style="width: 100px;"><?php echo DELT::currency_value($ggt, $model->currency) ?></td>
  </tr>
</table>
</div>

<?php endif //$with_subtitles and $ggt?>
<?php endif //sizeof($data)>0 ?>


<?php endif // statement->type 1 or 2 ?>
<?php if($statement->type == 3): ?>

<?php /*<pre><?php print_r($data) ?></pre> */ ?>
<div class="statementtable">
<table>
  <thead>
    <tr class="statementrow level2">
      <td class="empty">&nbsp;</td>
      <?php foreach($data['totals']['columns'] as $key=>$value): ?>
        <td class="heading"><?php echo $key ?></td>
      <?php endforeach ?>
      <td class="heading"><?php echo Yii::t('delt', 'Total') ?></td>
    </tr>
  </thead>
  <tfoot>
    <tr class="statementrow level2">
      <td class="empty"><?php echo Yii::t('delt', 'Ending Balances') ?></td>
      <?php foreach($data['totals']['columns'] as $key=>$value): ?>
        <th class="currency"><?php echo DELT::currency_value(
          $value, $model->currency,
          false,
          false,
          'span',
          array('class'=>($value>0 ? 'positiveamount': 'negativeamount')))
        ?></th>
      <?php endforeach ?>
      <th class="currency"><?php echo DELT::currency_value(
          $data['grandtotal'], $model->currency,
          false,
          false,
          'span',
          array('class'=>($data['grandtotal']>0 ? 'positiveamount': 'negativeamount')))
        ?></th>
    </tr>
  </tfoot>
  <tbody>
    <?php foreach ($data['totals']['rows'] as $key=>$value): ?>
      <tr>
        <td><?php echo $key ?></td>
        <?php foreach($data['totals']['columns'] as $k=>$v): ?>
          <?php if(isset($data['values'][$k][$key])): ?>
            <td class="currency" style="width: 150px;"><?php echo DELT::currency_value(
              $data['values'][$k][$key], 
              $model->currency,
              false,
              false,
              'span',
              array('class'=>($value>0 ? 'positiveamount': 'negativeamount'))
              )
          ?></td>
          <?php else: ?>
            <td>&nbsp;</td>
          <?php endif ?>
        <?php endforeach ?>
        <td class="currency" style="width: 150px;"><?php echo DELT::currency_value(
          $value, 
          $model->currency,
          false,
          false,
          'span',
          array('class'=>($value>0 ? 'positiveamount': 'negativeamount'))
          ) ?></td>
      </tr>
    <?php endforeach ?>
  </tbody>

</table>
</div>
  
  
  
  
<?php endif ?>
