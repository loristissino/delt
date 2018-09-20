<?php
/**
 * FirmsCommand class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
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
  private $benchmarks;
  
  public function actionIndex()
  {
    $firms=Firm::model()->findAll();
    foreach($firms as $firm)
    {
      echo implode("\t", array($firm->id, $firm->status, $firm->language->language_code, $firm->slug, $firm->name)) . "\n";
    }
  }

  public function actionDeleteFirmsNotOwned()
  {
    // this deletes firms owned only by banned users
    $firms=Firm::model()->findAll();
    foreach($firms as $firm)
    {
      if ($firm->status > 1)
      {
        $fu = $firm->owners;
        $number=sizeof($fu);
        $count = 0;
        foreach ($fu as $u)
        {
          if (DEUser::model()->findByPk($u->user_id)->status<0)
          {
            $count++;
          }
        }
        if ($count==$number && $number>0)
        {
          echo implode("\t", array($firm->id, $firm->slug, $number)) . ":";
          $this->_deleteFirm($firm);
          echo "\n";
        }
      }
    }
  }

  public function actionMarkStale()
  {
    $reference_date = date('Y-m-d', time() - 24*60*60*365);
    echo $reference_date . "\n";

    $this->benchmarks = array();
    foreach (Exercise::model()->findAll() as $exercise)
    {
      $this->benchmarks[] = $exercise->firm_id;
      if ($exercise->firm)
      {
        $this->benchmarks[] = $exercise->firm->firm_parent_id;
      }
    }
    
    $firms=Firm::model()->findAll();
    foreach($firms as $firm)
    {
      echo $firm->id . ": ";
      $this->setStaleStatusIfNeeded($firm, $reference_date);
      echo "\n";
    }
  }
  
  public function actionDeleteExtraStale()
  {
    $reference_date = date('Y-m-d', time() - 24*60*60*(365+31));  // after one month of being stale, we delete the firm.
    echo $reference_date . "\n";
    
    $firms=Firm::model()->findAllByAttributes(array('status'=>Firm::STATUS_STALE));
    foreach($firms as $firm)
    {
      echo $firm->id . ": ";
      
      $lastEvent = Event::model()->ofFirm($firm->id)->sorted()->find();
      if (!$lastEvent || $lastEvent->happened_at < $reference_date)
      {
         $this->_deleteFirm($firm);
      }
      else
      {
        echo "kept in stale state";
      }
      echo "\n";
    }
  }
  
  private function _deleteFirm($firm)
  {
     if ($firm->softDelete())
     {
       Event::model()->log(null, $firm->id, Event::FIRM_DELETED);
       echo "deleted";
     }
     else
     {
       echo "NOT deleted for unknown reason";
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
      echo $firm->id . ": ";
      switch($firm->safeDelete())
      {
        case Firm::STATUS_CLEARED:
          echo 'cleared';
          Event::model()->log(null, $firm->id, Event::FIRM_CLEARED);
          break;
        case Firm::STATUS_DELETED:
          echo 'deleted';  // old firms with no events
          break;
        case false:
          echo 'NOT deleted';
          break;
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

  protected function setStaleStatusIfNeeded($firm, $reference_date)
  {    
    if ($firm->status == Firm::STATUS_STALE)
    {
      echo "ALREADY STALE";
      return false;
    }
    if ($firm->status == Firm::STATUS_SYSTEM)
    {
      // firms set as public are always kept
      echo "KEPT (system)";
      return false;
    }
    
    if (in_array($firm->id, $this->benchmarks))
    {
      // firms used as benchmarks for challenges are always kept
      echo "KEPT (benchmark)";
      return false;
    }
    
    foreach($firm->getAllOwnersExcept(-1, ', p.is_blogger') as $owner)
    {
      if ($owner->is_blogger)
      {
        echo "KEPT (owned by blogger: " . $owner->username . ")";
        return false;
      }
    }

    $lastEvent = Event::model()->ofFirm($firm->id)->sorted()->find();
    if (!$lastEvent)
    {
      echo "STALE (no events)";
      return $this->markStale($firm);
    }
    elseif ($lastEvent->happened_at < $reference_date)
    {
      echo "STALE (last event: " . $lastEvent->happened_at . ")";
      return $this->markStale($firm);
    }
    else
    {
      echo "KEPT (active)";
      return false;
    }
  }
  
  protected function markStale($firm)
  {
    $firm->status = Firm::STATUS_STALE;
    return $firm->save(false);
  }
  
  public function actionCreateSections()
  {
    $firms=Firm::model()->findAll();
    foreach($firms as $firm)
    {
      echo $firm->id;
      
      $entries = $firm->journalentries;
      if (sizeof($entries)) {
        echo " creating...";
        $section = new Section();
        $section->firm_id = $firm->id;
        $section->name = "Default";
        $section->is_visible = true;
        $section->rank = 1;
        $section->color = 'ffffff';
        $section->save(false);
        echo $section->id . "\n";
        
        foreach ($entries as $je) {
          $je->section_id = $section->id;
          $je->save(false);
          echo $je->id . ", ";
        }
        echo "\n";
      }
      echo "\n";
    }
  }
  
}
