<?php

class DELT
{
  
  public static function getVersion()
  {
    return '0.9.4';
  }
  
  public static function currency_value($amount, $currency, $with_debit_credit=false, $with_zero=false)
  {
    if($amount==0)
    {
      return $with_zero ? Yii::app()->numberFormatter->formatCurrency(0, $currency): '';
    }
    
    if($with_debit_credit)
    {
      return Yii::app()->numberFormatter->formatCurrency(abs($amount), $currency) . ' ' . Yii::t('delt', self::amount2type($amount));
    }
    else
    {
      return Yii::app()->numberFormatter->formatCurrency($amount, $currency);
    }
  }
  
  public static function currency2decimal($value, $currency)
  {
    $sample=Yii::app()->numberFormatter->formatCurrency(12345.67, $currency);
    
    // strangely enough, this information does not seem to be available:
    $thousand_sep = substr($sample, strpos($sample, '2')+1, 1);
    $decimal_sep = substr($sample, strpos($sample, '5')+1, 1);
    
    $legalchars = $thousand_sep . $decimal_sep . '0123456789';
    
    $clean='';
    for($i=0; $i<strlen($value); $i++)
    {
      $c=substr($value, $i, 1);
      if(strpos($legalchars, $c)!==false)
      {
        $clean .= $c;
      }
    }
    
    $value=str_replace($thousand_sep, '', $clean);
    $value=str_replace($decimal_sep, '.', $value);
    
    return $value;
  }
  
  public static function amount2type($amount, $with_html_comment=true)
  {
    $type = $amount > 0 ? 'D' : ($amount < 0 ? 'C': null);
    if($with_html_comment)
    {
      $type .='<!-- outstanding balance -->';
    }
    return $type;
    
  }

  public static function getConvertedJQueryUIDateFormat()
  {
    $locale=strtolower(str_replace('_', '-', Yii::app()->request->getPreferredLanguage()));
    
    if($format = self::_getConvertedJQueryUIDateFormat($locale))
    {
      return $format;
    }
    
    $locale = substr($locale, 0, strpos($locale, '-'));

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
  
 /**
 * Get either a Gravatar URL or complete image tag for a specified email address.
 *
 * @param string $email The email address
 * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
 * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
 * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
 * @param boole $img True to return a complete IMG tag False for just the URL
 * @param array $atts Optional, additional key/value attributes to include in the IMG tag
 * @return String containing either just a URL or a complete image tag
 * @source http://gravatar.com/site/implement/images/php/
 */
  public static function getGravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() )
  {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
  }
  
  public static function object2array($object, &$array, $properties=array())
  {
    foreach ($properties as $property)
    {
      $array[$property] = $object->$property; 
    }
  }

  public static function array2object($array, $object, $properties=array())
  {
    foreach ($properties as $property)
    {
      $object->$property = $array[$property];
    }
  }

  public static function getDateForFormWidget($date)
  {
    $date=DateTime::createFromFormat('Y-m-d', $date);
    $format = DELT::getConvertedJQueryUIDateFormat();
    return $date->format($format);
  }
  
}
