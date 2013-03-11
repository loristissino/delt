<?php

class DELT
{
  public static function currency_value($amount, $currency, $with_debit_credit=false)
  {
    if($amount==0)
    {
      return '0';
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
  
  
  public static function getConvertedJQueryUIDateFormat()
  {
    $locale=strtolower(str_replace('_', '-', Yii::app()->request->getPreferredLanguage()));
    
    if($format = self::_getConvertedJQueryUIDateFormat($locale))
    {
      return $format;
    }
    
    $locale = substr($locale, 0, strpos($locale, '_'));

    if($format = self::_getConvertedJQueryUIDateFormat($locale))
    {
      return $format;
    }

    return 'm/d/Y';
  }


  private static function _getConvertedJQueryUIDateFormat($locale)
  {
    switch($locale)
    {
        // the following are extracted from jquery.ui.i18n (version 1.9.2)
        case '' : return 'm/d/Y'; 
        case 'af' : return 'd/m/Y'; 
        case 'ar-dz' : return 'd/m/Y'; 
        case 'ar' : return 'd/m/Y'; 
        case 'az' : return 'd.m.Y'; 
        case 'bg' : return 'd.m.Y'; 
        case 'bs' : return 'd.m.Y'; 
        case 'ca' : return 'd/m/Y'; 
        case 'cs' : return 'd.m.Y'; 
        case 'cy-gb' : return 'd/m/Y'; 
        case 'da' : return 'd-m-Y'; 
        case 'de' : return 'd.m.Y'; 
        case 'el' : return 'd/m/Y'; 
        case 'en-au' : return 'd/m/Y'; 
        case 'en-gb' : return 'd/m/Y'; 
        case 'en-nz' : return 'd/m/Y'; 
        case 'eo' : return 'd/m/Y'; 
        case 'es' : return 'd/m/Y'; 
        case 'et' : return 'd.m.Y'; 
        case 'eu' : return 'Y-m-d'; 
        case 'fa' : return 'Y/m/d'; 
        case 'fi' : return 'd.m.Y'; 
        case 'fo' : return 'd-m-Y'; 
        case 'fr-ch' : return 'd.m.Y'; 
        case 'fr' : return 'd/m/Y'; 
        case 'gl' : return 'd/m/Y'; 
        case 'he' : return 'd/m/Y'; 
        case 'hi' : return 'd/m/Y'; 
        case 'hr' : return 'd.m.Y.'; 
        case 'hu' : return 'Y.m.d.'; 
        case 'hy' : return 'd.m.Y'; 
        case 'id' : return 'd/m/Y'; 
        case 'is' : return 'd/m/Y'; 
        case 'it' : return 'd/m/Y'; 
        case 'ja' : return 'Y/m/d'; 
        case 'ka' : return 'd-m-Y'; 
        case 'kk' : return 'd.m.Y'; 
        case 'km' : return 'd-m-Y'; 
        case 'ko' : return 'Y-m-d'; 
        case 'lb' : return 'd.m.Y'; 
        case 'lt' : return 'Y-m-d'; 
        case 'lv' : return 'd-m-Y'; 
        case 'mk' : return 'd.m.Y'; 
        case 'ml' : return 'd/m/Y'; 
        case 'ms' : return 'd/m/Y'; 
        case 'nl-be' : return 'd/m/Y'; 
        case 'nl' : return 'd-m-Y'; 
        case 'no' : return 'd.m.Y'; 
        case 'pl' : return 'd.m.Y'; 
        case 'pt-br' : return 'd/m/Y'; 
        case 'pt' : return 'd/m/Y'; 
        case 'rm' : return 'd/m/Y'; 
        case 'ro' : return 'd.m.Y'; 
        case 'ru' : return 'd.m.Y'; 
        case 'sk' : return 'd.m.Y'; 
        case 'sl' : return 'd.m.Y'; 
        case 'sq' : return 'd.m.Y'; 
        case 'sr-sr' : return 'd/m/Y'; 
        case 'sr' : return 'd/m/Y'; 
        case 'sv' : return 'Y-m-d'; 
        case 'ta' : return 'd/m/Y'; 
        case 'th' : return 'd/m/Y'; 
        case 'tj' : return 'd.m.Y'; 
        case 'tr' : return 'd.m.Y'; 
        case 'uk' : return 'd/m/Y'; 
        case 'vi' : return 'd/m/Y'; 
        case 'zh-cn' : return 'Y-m-d'; 
        case 'zh-hk' : return 'd-m-Y'; 
        case 'zh-tw' : return 'Y/m/d'; 
        default: return false; 
    }
  }

}
