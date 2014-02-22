<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Test page</h1>


<?php

  function getNamesAsArray()
  {
    
    $textnames=
"
en_US: testoUS
en_CA: testoCA
it_IT: itali 
";
    
    
    $result=array();
    $temp=array();
    
    foreach(explode("\n", str_replace("\r", "", $textnames)) as $line)
    {
      $info = explode(':', $line);
      if (sizeof($info)!=2)
      {
        continue;
      }
      $locale=trim($info[0]);
      
      $language = DELT::LocaleToLanguage($locale);
      
      $name=strip_tags(trim($info[1]));
      $result[$locale]=$name;
      if(!isset($temp[$language]))
      {
        $temp[$language]=$name;
      }
    }
    
    foreach($result as $key=>&$value)
    {
      if(!$value)
      {
        $tl=DELT::LocaleToLanguage($key);
        
        if(isset($temp[$tl]))
        {
          $value=$temp[$tl];
        }
      }
    }
    
    ksort($result);
    return $result;
  }

print_r(getNamesAsArray());
?>
</pre>
