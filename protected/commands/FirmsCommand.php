<?php
/**
 * RunchecksCommand class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013 Loris Tissino
 * @since 1.0
 * 
 * @package application.commands
 * 
 */

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
