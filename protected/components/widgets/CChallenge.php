<?php 

// see http://www.yiiframework.com/wiki/23/
 
class CChallenge extends CWidget
{
  
    public $hideOnEmpty=false;
    public $title='Challenge Manager';
 
    public function getChallenge()
    {
      if (Yii::app()->controller->DEUser)
      {
        if ($challenge = Yii::app()->controller->DEUser->getOpenChallenge())
        {
          Yii::app()->user->setState('transaction', $challenge->transaction_id);
        }
        return $challenge;
      }
      else
      {
        return false;
      }
    }
    
    public function run()
    {
        $this->render('challenge');
    }
    
}
