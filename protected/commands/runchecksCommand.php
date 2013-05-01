<?php

/*
 *  run this command with something like:
 *  ./yiic runchecks
 * 
 * see http://www.yiiframework.com/doc/guide/1.1/en/topics.console
*/

class RunchecksCommand extends CConsoleCommand
{
  
  public function actionIndex()
  {
    $accounts=Account::model()->findAll();
    foreach($accounts as $account)
    {
      echo $account->id . "\n";
      $account->setName();
      $account->save();
    }
  }
  
  
  
}
