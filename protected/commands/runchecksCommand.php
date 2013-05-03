<?php

/*
 *  run this command with something like:
 *  ./yiic runchecks
 * 
 * see http://www.yiiframework.com/doc/guide/1.1/en/topics.console
*/

class RunchecksCommand extends CConsoleCommand
{
  
  public function actionIndex($id)
  {
    if($firm = Firm::model()->findByPk($id))
    {
      echo "Firm: " . $firm->id . "\n";
      $accounts = Account::model()->findAllByAttributes(array('firm_id'=>$id, 'currentname'=>''));
      foreach($accounts as $account)
      {
        echo "account: ". $account->id . ": ";
        $account->setName();
        echo $account->currentname . "\n";
        $account->save(false);
      }
    }
    else
    {
      echo "Firm not found: " . $id . "\n";
    }
  }
  
}
