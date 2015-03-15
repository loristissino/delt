<?php

Yii::import('ext.parsedown.*');

class HandbookManager extends Parsedown
{
  private $language;
  private $file;
  private $path;
  private $content;
  
  public function __construct($language, $file, $path, $ext='.md')
  {
    $filename = $path.'/'.$language.'/'.$file.$ext;
    if(file_exists($filename))
    {
      $this->content = file_get_contents($filename);
      return;
    }
    $filename = $path.'/en/'.$file.$ext;
    if(file_exists($filename))
    {
      $this->content = file_get_contents($filename);
      return;
    }
    throw new Exception('Content not available: ' . $filename);
    
  }
  
  public function getRenderedContent()
  {
    return $this->text($this->content);
  }

}
