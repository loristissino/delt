<?php
/**
 * FirmsCommand class file.
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
 *  ./yiic firms
 * 
 * see http://www.yiiframework.com/doc/guide/1.1/en/topics.console
*/

class FirmsCommand extends CConsoleCommand
{
  
  public function actionIndex()
  {
    $firms=Firm::model()->findAll();
    foreach($firms as $firm)
    {
      echo implode("\t", array($firm->id, $firm->status, $firm->language->language_code, $firm->slug, $firm->name)) . "\n";
    }
  }

  public function actionInfo($id)
  {
    if(!$firm = Firm::model()->findByPk($id))
    {
      echo "Firm not found: " . $id . "\n";
      return;
    }
    
    echo "id: " . $firm->id . "\n";
    echo "slug: " . $firm->slug . "\n";
    echo "name: " . $firm->name . "\n";
    echo "status: " . $firm->status . "\n";
    echo "accounts: " . sizeof($firm->accounts) . "\n";
    echo "journalentries: " . sizeof($firm->journalentries) . "\n";
    echo "owners: " . sizeof($firm->owners) . "\n";
    echo "languages: " . sizeof($firm->languages) . "\n";
    echo "----------------------\n";
    
  }

  public function actionEntries($id)
  {
    if(!$firm = Firm::model()->findByPk($id))
    {
      echo "Firm not found: " . $id . "\n";
      return;
    }

    foreach($firm->journalentries as $journalentry)
    {
      echo $journalentry->date . ' -- ' . $journalentry->description . "\n";
      foreach($journalentry->postings as $posting)
      {
        echo "   " . $posting->account . " -- " . $posting->amount . "\n";
      }
    }
    
  }
  
  public function actionClearDeleted()
  {
    $firms = Firm::model()->findAllByAttributes(array('status'=>Firm::STATUS_DELETED));
    foreach($firms as $firm)
    {
      echo $firm->id . " ";
      if ($firm->safeDelete())
      {
        echo 'deleted';
        Event::model()->log(null, $firm->id, Event::FIRM_CLEARED);
      }
      else
      {
        echo 'NOT deleted';
      }
      
      echo "\n";
    }
  }
  
  public function actionFixAccounts($id)
  {
    if(!$firm = Firm::model()->findByPk($id))
    {
      echo "Firm not found: " . $id . "\n";
      return;
    }
    
    echo $id .' ';
    $firm->fixAccounts();
    $firm->fixAccountNames();
    echo "fixed!\n";
    
  }

}
