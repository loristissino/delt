<?php 

// see http://www.yiiframework.com/wiki/23/
 
class CChallenge extends CWidget
{
  
    public $hideOnEmpty=false;
    public $title='Challenge Manager';
 
    public function getChallenges()
    {
      if (Yii::app()->controller->DEUser)
      {
        return Yii::app()->controller->DEUser->getOpenChallenges();
      }
      else
      {
        return array();
      }
    }
    
    public function run()
    {
        $this->render('challenge');
    }
    
}
