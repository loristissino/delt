<?php

class DELT
{
  public static function currency_value($amount, $currency, $with_debit_credit=false)
  {
    if(!$amount)
    {
      return '';
    }
    if($with_debit_credit)
    {
      return Yii::app()->numberFormatter->formatCurrency(abs($amount), $currency) . ' ' . Yii::t('delt', ($amount>0 ? 'D':'C').'<!-- outstanding balance -->');
    }
    else
    {
      return Yii::app()->numberFormatter->formatCurrency($amount, $currency);
    }
  }
}
