<?php
/**
 * DELT class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.0
 */
/**
 * DELT is a class with some useful static methods.
 *
 * @package application.components
 * 
 */
class DELT
{
  
  public static function getVersion()
  {
    return '1.9.45';
  }
  
  public static function currency_value($amount, $currency, $with_debit_credit=false, $with_zero=false, $element='', $htmlOptions=array())
  {
    if($amount==0)
    {
      if(!$with_zero)
      {
        return '';
      }
      $value = $with_zero ? Yii::app()->numberFormatter->formatCurrency(0, $currency): '';
    }
    
    if($with_debit_credit)
    {
      $value = Yii::app()->numberFormatter->formatCurrency(abs($amount), $currency);
      if($amount)
      {
        $value .= ' ' .Yii::t('delt', self::amount2type($amount));
      }
    }
    else
    {
      $value = Yii::app()->numberFormatter->formatCurrency($amount, $currency);
    }
    
    $t='';
    if($element)
    {
      $t='<' . $element;
      if(sizeof($htmlOptions))
      {
        foreach($htmlOptions as $k=>$v)
        {
          $t.= ' ' . $k . '="' . $v . '"';
        }
      }
      $t.='>';
    }
    $u='';
    if($element)
    {
      $u='</' . $element .'>';
    }
    return $t . $value . $u;
    
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
    $type = $amount > 0 ? 'Dr.' : ($amount < 0 ? 'Cr.': null);
    if($with_html_comment)
    {
      $type .='<!-- outstanding balance -->';
    }
    return $type;
    
  }

  public static function getConvertedJQueryUIDateFormat($with_year=true)
  {
    $locale=strtolower(str_replace('_', '-', Yii::app()->language));
    
    if($format = self::_getConvertedJQueryUIDateFormat($locale, $with_year))
    {
      return $format;
    }
    
    $locale = substr($locale, 0, strpos($locale, '-'));

    if($format = self::_getConvertedJQueryUIDateFormat($locale, $with_year))
    {
      return $format;
    }

    return 'm/d/Y';
  }


  private static function _getConvertedJQueryUIDateFormat($locale, $with_year)
  {
    switch($locale)
    {
        // the following are extracted from jquery.ui.i18n (version 1.9.2)
        case '' :      return $with_year? 'm/d/Y' : 'm/d/Y'; 
        case 'af' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'ar-dz' : return $with_year? 'd/m/Y': 'd/m'; 
        case 'ar' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'az' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'bg' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'bs' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'ca' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'cs' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'cy-gb' : return $with_year? 'd/m/Y': 'd/m'; 
        case 'da' :    return $with_year? 'd-m-Y': 'd-m'; 
        case 'de' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'el' :    return $with_year? 'd/m/Y': 'd/m';
        case 'en-us' : return $with_year? 'm/d/Y': 'm/d';
        case 'en-au' : return $with_year? 'd/m/Y': 'd/m'; 
        case 'en-gb' : return $with_year? 'd/m/Y': 'd/m'; 
        case 'en-nz' : return $with_year? 'd/m/Y': 'd/m'; 
        case 'eo' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'es' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'et' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'eu' :    return $with_year? 'Y-m-d': 'm-d'; 
        case 'fa' :    return $with_year? 'Y/m/d': 'm/d'; 
        case 'fi' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'fo' :    return $with_year? 'd-m-Y': 'd-m'; 
        case 'fr-ch' : return $with_year? 'd.m.Y': 'd.m'; 
        case 'fr' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'gl' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'he' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'hi' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'hr' :    return $with_year? 'd.m.Y.': 'd.m.'; 
        case 'hu' :    return $with_year? 'Y.m.d.': 'm.d.'; 
        case 'hy' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'id' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'is' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'it' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'ja' :    return $with_year? 'Y/m/d': 'm/d'; 
        case 'ka' :    return $with_year? 'd-m-Y': 'd-m'; 
        case 'kk' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'km' :    return $with_year? 'd-m-Y': 'd-m'; 
        case 'ko' :    return $with_year? 'Y-m-d': 'm-d'; 
        case 'lb' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'lt' :    return $with_year? 'Y-m-d': 'm-d'; 
        case 'lv' :    return $with_year? 'd-m-Y': 'd-m'; 
        case 'mk' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'ml' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'ms' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'nl-be' : return $with_year? 'd/m/Y': 'd/m'; 
        case 'nl' :    return $with_year? 'd-m-Y': 'd-m'; 
        case 'no' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'pl' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'pt-br' : return $with_year? 'd/m/Y': 'd/m'; 
        case 'pt' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'rm' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'ro' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'ru' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'sk' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'sl' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'sq' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'sr-sr' : return $with_year? 'd/m/Y': 'd/m'; 
        case 'sr' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'sv' :    return $with_year? 'Y-m-d': 'm-d'; 
        case 'ta' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'th' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'tj' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'tr' :    return $with_year? 'd.m.Y': 'd.m'; 
        case 'uk' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'vi' :    return $with_year? 'd/m/Y': 'd/m'; 
        case 'zh-cn' : return $with_year? 'Y-m-d': 'm-d'; 
        case 'zh-hk' : return $with_year? 'd-m-Y': 'd-m'; 
        case 'zh-tw' : return $with_year? 'Y/m/d': 'm/d'; 
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
      if(isset($array[$property]))
      {
        $object->$property = $array[$property];
      }
    }
  }
  
  public static function object2object($source, $target, $properties=array())
  {
    foreach ($properties as $property)
    {
      $target->$property = $source->$property;
    }
  }
  
  public static function sanitizeProperties($object, $properties=array())
  {
    foreach($properties as $property)
    {
      $object->$property = strip_tags($object->$property);
    }
  }
  
  public static function compareObjects($source, $target, $properties=array())
  {
    $differences=array();
    foreach ($properties as $property)
    {
      if(($sp=chop($source->$property)) != ($tp=chop($target->$property)))
      {
        $differences[$property]=array('source'=>$sp, 'target'=>$tp);
      }
    }
    if(sizeof($differences))
    {
      return $differences;
    }
    return false;
  }

  public static function getDateForFormWidget($date, $with_year=true)
  {
    $date=DateTime::createFromFormat('Y-m-d', $date);
    $format = DELT::getConvertedJQueryUIDateFormat($with_year);
    return $date->format($format);
  }
  
  public static function getValidatedDate($date, $default)
  {
    return date('Y-m-d', strtotime($date))==$date ? $date: $default;
  }
    
  public static function delimittext($text, $delimiter, $charset='')
  {
    if($charset)
    {
      $text=@iconv('utf-8', $charset, $text);
      // we need to silence it because otherwise we could have a PHP notice
    }
    return $delimiter . str_replace(array("'", '"'), array('’', '”'), $text) . $delimiter;
    // we do the same replacements OpenOffice does...
  }
  
  public static function LocaleToLanguage($locale)
  {
    $info=explode('_', $locale);
    return $info[0];
  }
  
  public static function array_add_if_unset(&$array, $key, $value)
  {
    if(!array_key_exists($key, $array) or trim($array[$key])=='')
    {
      $array[$key]=$value;
    }
  }
  
  public static function islowercase($v)
  {
    return $v==strtolower($v);
  }

  public static function isuppercase($v)
  {
    return $v==strtoupper($v);
  }
  
  public static function logdebug($message)
  {
    Yii::trace($message, 'debug.log');
  }
  
  public static function splitByDelimiter($text, $delimiter=',')
  {
    if (strpos($text, $delimiter))
    {
      $values = explode($delimiter, $text);
    }
    else
    {
      $values = array($text);
    }
    array_walk($values, function (&$item) { $item = trim($item); });
    return $values;
  }
  
  public static function addValueToArray(&$array, $key, $value)
  {
    if(isset($array[$key]))
    {
      $array[$key] += $value;
    }
    else
    {
      $array[$key] = $value;
    }
  }
  
  public static function stripString($expression, $text)
  {
    // there's no easy way to check whether a regular expression is valid, so we just check if there is at least a slash in it
    if(strpos($expression, '/')!==false)
    {
      return trim(@preg_replace($expression, '', $text));
    }
    else
    {
      return trim(str_replace($expression, '', $text)); 
    }
  }
  
  public static function getValueFromArray($array, $key, $default)
  {
    return isset($array[$key]) ? $array[$key] : $default;
  }
  
  public static function nearlyZero($v)
  {
    return abs($v) < 0.0001;
  }
  
  public static function firstPartOfString($string, $chars)
  {
    if(strlen($string) < $chars)
    {
      return $string;
    }
    return mb_strcut($string, 0, $chars) . '…';
  }
  
  public static function firstWordsOfString($string, $chars, $glue=' ')
  {
    if(strlen($string)<$chars)
    {
      return $string;
    }
    $count = 0;
    $text = array();
    $length = 0;
    $words = explode(' ', $string);
    while ($length < $chars)
    {
      $text[] = $words[$count];
      $length += strlen($words[$count]) + strlen($glue);
      $count++;
    }
    return implode($glue, $text) . '…';
  }
  
  public static function findComment($text)
  {
    if(($pos=mb_strpos($text, '#'))!=false)
    {
      return strip_tags(trim(mb_substr($text, $pos+1)));
    }
    return null;
  }
  
  public static function falseValue($value)
  {
    return in_array(strtolower($value), array('false', 'no', '0'));
  }

  public static function trueValue($value)
  {
    return in_array(strtolower($value), array('true', 'yes', '1'));
  }
  
  
}
