<?php 

// see http://www.yiiframework.com/wiki/23/
 
class CChallenge extends CWidget
{
    public $hideOnEmpty=false;
    public $title='Challenge Manager';
    public $challenge;
    public $result;
 
    public function getChallenge()
    {
      if (isset(Yii::app()->controller->DEUser))
      {
        if ($this->challenge = Yii::app()->controller->DEUser->getOpenChallenge())
        {
          Yii::app()->user->setState('challenge', $this->challenge->id);
          Yii::app()->user->setState('transaction', $this->challenge->transaction_id);
        }
      return $this->challenge;
      }
      
      return null;
    }
    
    public function run()
    {
      $this->getChallenge();

      if($this->challenge)
      {
        $this->result = $this->challenge->getResults();
        Yii::app()->user->setState('challenge', $this->challenge->id);
      }
      else
      {
        $this->result = array();
        Yii::app()->user->setState('challenge', false);
      }
      $this->render('challenge', array('challenge'=>$this->challenge));
    }
    
}
