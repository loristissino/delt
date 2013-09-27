<?php

class TimelinesWidget extends CWidget
{
  
  public $timeline;
  
  public function init()
  {
    // this method is called by CController::beginWidget()
  }
 
  public function run()
  {
    if($this->timeline)
    {
      $this->render($this->timeline, array(
      ));
    }
  }
}
