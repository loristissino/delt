<?php

if($separator=='t')
{
  $separator = chr(9);
}

if($delimiter=='none')
{
  $delimiter='';
}

echo pack('CCC', 0xEF, 0xBB, 0xBF);  /* BOM */

$headers=array(
  DELT::delimittext(Yii::t('delt', 'Code'), $delimiter),
  DELT::delimittext(Yii::t('delt', 'Name'), $delimiter),
);
switch($type)
{
  case 'S':
    $headers[]=DELT::delimittext(Yii::t('delt', 'Amount'), $delimiter);
    break;
  case 'U':
    $headers[]=DELT::delimittext(Yii::t('delt', 'Amount'), $delimiter);
    $headers[]=DELT::delimittext(Yii::t('delt', 'Outstanding balance'), $delimiter);
    break;
  case '2':
    $headers[]=DELT::delimittext(Yii::t('delt', 'Debit'), $delimiter);
    $headers[]=DELT::delimittext(Yii::t('delt', 'Credit'), $delimiter);
    break;
}


?>
<?php echo implode($separator, $headers) . "\n" ?>
<?php foreach($accounts as $account): ?><?php

$values = array(
  DELT::delimittext($account['code'], $delimiter),
  DELT::delimittext($account['name'], $delimiter),
);

switch($type)
{
  case 'S':
    $values[]=Yii::app()->numberFormatter->formatDecimal($account['total']);
    break;
  case 'U':
    $values[]=Yii::app()->numberFormatter->formatDecimal(abs($account['total']));
    $values[]=$account['total']>=0 ? Yii::t('delt', 'Debit') : Yii::t('delt', 'Credit');
    break;
  case '2':
    $values[]=$account['total']>=0 ? Yii::app()->numberFormatter->formatDecimal(abs($account['total'])) : '';
    $values[]=$account['total']<0 ? Yii::app()->numberFormatter->formatDecimal(abs($account['total'])) : '';
    break;
}

?><?php echo implode($separator, $values). "\n" ?>
<?php endforeach ?>
