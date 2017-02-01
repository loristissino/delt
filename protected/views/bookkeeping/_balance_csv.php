<?php

if($separator=='t')
{
  $separator = chr(9);
}

if($delimiter=='none')
{
  $delimiter='';
}

if($charset=='utf-8' and !$inline)
{
  echo pack('CCC', 0xEF, 0xBB, 0xBF);  /* BOM */
}

$headers=array(
  DELT::delimittext(Yii::t('delt', 'Code'), $delimiter, $charset),
  DELT::delimittext(Yii::t('delt', 'Name'), $delimiter, $charset),
);
switch($type)
{
  case 'S':
    $headers[]=DELT::delimittext(Yii::t('delt', 'Amount'), $delimiter, $charset);
    break;
  case 'U':
    $headers[]=DELT::delimittext(Yii::t('delt', 'Amount'), $delimiter, $charset);
    $headers[]=DELT::delimittext(Yii::t('delt', 'Outstanding balance'), $delimiter, $charset);
    break;
  case '2':
    $headers[]=DELT::delimittext(Yii::t('delt', 'Debit'), $delimiter, $charset);
    $headers[]=DELT::delimittext(Yii::t('delt', 'Credit'), $delimiter, $charset);
    break;
}
$headers[]=DELT::delimittext(Yii::t('delt', 'Classes'), $delimiter, $charset);

?>
<?php echo implode($separator, $headers) . "\n" ?>
<?php foreach($accounts as $account): ?><?php

$values = array(
  DELT::delimittext($model->renderAccountCode($account['code']), $delimiter, $charset),
  DELT::delimittext($account['name'], $delimiter, $charset),
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
$values[]=$account['classes'];

?><?php echo implode($separator, $values). "\n" ?>
<?php endforeach ?>
