<?php
/**
 * BookkeepingController class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.0
 * 
 */
/**
 * The BookkeepingController class.
 * 
 * @package application.controllers
 * @author Loris Tissino <loris.tissino@gmail.com>
 * 
 */
class BookkeepingController extends Controller
{
  
  public $layout='//layouts/column2';
  
  private $line_shown = false;
  // we set this to true when we want to avoid duplicates, like in the journal
  
  public $show_link_on_description = false;
  
  public $last_journalentry_id = null;
  
  public $journalentry_id = null;
  
  public $debit_sum = 0;
  public $credit_sum = 0;
  
  public $accounts;
  public $journalentrydescription;
  public $is_closing = 0;
  
  public $form_action_required = null;  // used in actionPrepareentry
  
  public function filters()
  {
    return array(
      'postOnly + deleteTemplate', // we only allow deletion via POST request
      'postOnly + toggleautomaticstatus', // we allow automatic status change only via POST request
    );
  }
  
  public function actionIndex($list='off')
  {
    if($this->DEUser)
    {
      if($list=='on' and sizeof($firms=$this->DEUser->firms)>0)
      {
        $this->render('index_detailed', array('firms'=>$firms, 'wfirms'=>$this->DEUser->wfirms));
      }
      else
      {
        $this->render('index', array('firms'=>$this->DEUser->firms, 'wfirms'=>$this->DEUser->wfirms));
      }
    }
    else
    {
      $this->redirect(array('site/index'));
    }
  }

  /**
   * Displays the actions available for a particular model.
   * @param string $slug the slug of the model to be displayed
   */
  public function actionManage($slug)
  {
    $this->firm = $this->loadModelBySlug($slug);
    $this->render('manage', array(
      'model'=>$this->firm
    ));
  }

  public function actionCoa($slug, $template='coa')
  {
    $this->firm=$this->loadModelBySlug($slug);
    $this->render($template, array(
      'model'=>$this->firm,
      'dataProvider'=>$this->firm->getAccountsAsDataProvider(),
    ));
  }

  public function actionCoasearchreplace($slug)
  {
    $this->firm=$this->loadModelBySlug($slug);
    if(isset($_POST['search']))
    {
      $changes = Account::model()->searchAndReplaceOnNames(
        $this->firm->id,
        DELT::getValueFromArray($_POST, 'search', ''),
        DELT::getValueFromArray($_POST, 'replace', '')
        );
      if ($changes)
      {
        Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'Account names changed: {number}.', array('{number}'=>$changes)));
      }
      else
      {
        Yii::app()->getUser()->setFlash('delt_warning', Yii::t('delt', 'No account name has been changed.'));
      }
        $this->redirect(array('bookkeeping/coa', 'slug'=>$slug, 'changes'=>$changes));
    }
    else
    {
      throw new CHttpException(404,'The requested page does not exist.');
    }
  }

  public function actionConfigure($slug, $template='configure')
  {
    $this->firm=$this->loadModelBySlug($slug);
    $this->render($template, array(
      'model'=>$this->firm,
      'dataProvider'=>$this->firm->getAccountsAsDataProvider(1),
    ));
  }
  
  public function actionCoatree($slug, $root='source')
  {
    if (!Yii::app()->request->isAjaxRequest)
    {
      throw new CHttpException(404,'The requested page does not exist.');
    }
        
    $this->firm=$this->loadModelBySlug($slug);
    
    $id= $root && $root!='source' ? (int) $root : null;
    $items= $this->firm->getCoaTree($id);
    
    echo CTreeView::saveDataAsJson($items);
  }
  
  public function actionBalance($slug, $format='')
  {
    $this->firm=$this->loadModelBySlug($slug);
    
    switch($format)
    {
      case 'unknown':
        $exportbalanceform = new ExportbalanceForm;
        if(Yii::app()->request->getParam('export', false))
        {
          $exportbalanceform->attributes=$_GET['ExportbalanceForm'];
          if($exportbalanceform->validate())
          {
            Yii::app()->getUser()->setState('separator', $exportbalanceform->separator);
            Yii::app()->getUser()->setState('delimiter', $exportbalanceform->delimiter);
            Yii::app()->getUser()->setState('type', $exportbalanceform->type);
            Yii::app()->getUser()->setState('charset', $exportbalanceform->charset);
            Yii::app()->getUser()->setState('fruition', $exportbalanceform->fruition);
            Yii::app()->getUser()->setState('inclusion', $exportbalanceform->inclusion);
            $this->redirect(array('bookkeeping/balance','slug'=>$this->firm->slug, 'format'=>'csv'));
          }
        }
        
        $exportbalanceform->separator = Yii::app()->getUser()->getState('separator', ',');
        $exportbalanceform->delimiter = Yii::app()->getUser()->getState('delimiter', '');
        $exportbalanceform->type = Yii::app()->getUser()->getState('type', '2');
        $exportbalanceform->charset = Yii::app()->getUser()->getState('charset', 'utf-8');
        $exportbalanceform->fruition = Yii::app()->getUser()->getState('fruition', 'd');
        $exportbalanceform->inclusion = Yii::app()->getUser()->getState('inclusion', 'e');
        
        $this->render('exportbalance', array(
          'model'=>$this->firm,
          'exportbalanceform'=>$exportbalanceform,
        ));
        
        break;
      case 'csv':
        $inline=Yii::app()->getUser()->getState('fruition')=='d';
        $content=$this->renderPartial('_balance_csv', array(
          'accounts'=>$this->firm->getAccountBalancesData('', array(), Yii::app()->getUser()->getState('inclusion')=='i'),
          'separator'=>Yii::app()->getUser()->getState('separator', ','),
          'delimiter'=>Yii::app()->getUser()->getState('delimiter', ''),
          'type'=>Yii::app()->getUser()->getState('type', '2'),
          'charset'=>Yii::app()->getUser()->getState('charset', 'utf-8'),
          'inline'=>$inline,
          'model'=>$this->firm,
          ), true);
        if($inline)
        {
          $filename = $this->firm->slug . '-' . date('Y-m-d-His') . (Yii::app()->getUser()->getState('separator', ',')=='t' ? '.tsv' : '.csv');
          $this->sendDispositionHeader($filename);
          $this->serveContent(Yii::app()->getUser()->getState('separator', ',')=='t' ? 
              'text/tab-separated-values'
              :
              'text/csv', 
            $content);
        }
        else
        {
          $this->render('exportbalanceinline', array(
            'model'=>$this->firm,
            'content'=>$content,
          ));
          
        }
        break;
      default:
        $this->render('balance', array(
          'model'=>$this->firm,
          'dataProvider'=>$this->firm->getAccountBalancesAsDataProvider(),
        ));
    }
    
  }
  
  public function actionGeneralledger($slug)
  {
    $this->firm=$this->loadModelBySlug($slug);
    $this->firm->cacheGeneralLedgerData();
    $this->render('generalledger', array(
      'model'=>$this->firm,
    ));
  }

  public function actionSubchoice($slug, $subchoice)
  {
    $this->firm=$this->loadModelBySlug($slug);
    $this->render('subchoice', array(
      'model'=>$this->firm,
      'subchoice'=>$subchoice
    ));
  }

  public function actionStatements($slug, $level=1, $start="1900-01-01", $end="2999-12-31")
  {
    $startDate = DELT::getValidatedDate($start, '1900-01-01');
    $endDate = DELT::getValidatedDate($start, '2999-12-31');
    
    $this->firm=$this->loadModelBySlug($slug);
    $maxlevel = $this->firm->getCOAMaxLevel();
    if($level > $maxlevel)
      throw new CHttpException(404,'The requested page does not exist.');

    $automatic_entries = $this->firm->cacheStatementsData($level);
    $this->render('statements', array(
      'model'=>$this->firm,
      'level'=>$level,
      'maxlevel'=>$maxlevel,
      'automatic_entries'=>$automatic_entries,
    ));
  }

  public function actionJournal($slug, $journalentry=null)
  {
    $this->firm=$this->loadModelBySlug($slug);
    $this->journalentry_id = $journalentry;
    $this->render('journal', array(
      'model'=>$this->firm,
    ));
  }
  
  public function actionExport($slug, $format="")
  {
    $this->firm=$this->loadModelBySlug($slug);
    
    switch($format)
    {
      case 'delt':
        $data=$this->firm->getExportData('111');
        $filename = $this->firm->slug . '-' . date('Y-m-d-His') . '.delt';
        Event::log($this->DEUser, $this->firm->id, Event::FIRM_EXPORTED);
        $this->sendDispositionHeader($filename);
        $this->serveJson($data);
        break;
        
      case 'ledger':
        $data=$this->firm->getLedgerFormatJournal();
        $filename = $this->firm->slug . '-' . date('Y-m-d-His') . '.ledger';
        Event::log($this->DEUser, $this->firm->id, Event::FIRM_EXPORTED_LEDGER);
        $this->sendDispositionHeader($filename);
        $this->servePlainText($data);
        break;

      case 'sqlite':
        $file=$this->firm->getSQLiteTempFile();
        $filename = $this->firm->slug . '-' . date('Y-m-d-His') . '.sqlite';
        Event::log($this->DEUser, $this->firm->id, Event::FIRM_EXPORTED_SQLITE);
        $this->sendDispositionHeader($filename);
        $this->serveSQLite($file);
        $this->firm->deleteSQLiteTempFile();
        break;
        
      default:
        $this->render('export', array(
          'model'=>$this->firm,
        ));
    }
  }

  public function actionImport($slug)
  {
    $this->firm=$this->loadModelBySlug($slug);
    $this->checkFrostiness($this->firm);
    
    if(isset($_POST['Firm']))
    {
      $file=CUploadedFile::getInstance($this->firm, 'file');
      if (is_object($file) && get_class($file)==='CUploadedFile')
      {
        if($this->firm->loadFromFile($file))
        {
          Event::log($this->DEUser, $this->firm->id, Event::FIRM_IMPORTED);
          Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'Data correctly imported.'));
          $this->redirect(array('bookkeeping/manage','slug'=>$this->firm->slug));
        }
        else
        {
          $this->firm->addError('file', Yii::t('delt', 'The file seems to be invalid.'));
        }

      }
    }
    
    $this->render('import', array(
      'model'=>$this->firm,
    ));
  }

  public function actionNewjournalentry($slug, $template=null)
  {
    $this->firm=$this->loadModelBySlug($slug);
    $this->checkFrostiness($this->firm);
      
    $journalentryform = new JournalentryForm();
    $journalentryform->firm_id = $this->firm->id;
    $journalentryform->firm = $this->firm;  // FIXME: if we set the firm, we don't need to set the other values... here for compatibility only
    $journalentryform->currency = $this->firm->currency;
    $journalentryform->show_analysis = false;

    $journalentryform->setIdentifier();

    if(!$journalentryform->date)
    {
      $journalentryform->date = Yii::app()->getUser()->getState('lastjournalentrydate', DELT::getDateForFormWidget(date('Y-m-d')));
    }

    if(!$journalentryform->section_id)
    {
      $last_section_id = Yii::app()->getUser()->getState('lastsection', false);
      if ($last_section_id)
      {
        $journalentryform->section_id = $last_section_id;
      }
    }

    if(isset($this->journalentrydescription))
    {
      $journalentryform->description = $this->journalentrydescription;
    }
    
    if(isset($this->accounts) and sizeof($this->accounts)>0)
    {
      $journalentryform->acquireItems($this->accounts, true);
    }
    else
    {
      $journalentryform->postings = array(new PostingForm(), new Postingform());
    }
    
    if(isset($_POST['JournalentryForm']))
    {
      $journalentryform->attributes=$_POST['JournalentryForm'];
      $journalentryform->is_adjustment = DELT::getValueFromArray($_POST['JournalentryForm'], 'is_adjustment', 0);
      if(isset($_POST['template']))
      {
        $template= new Template();
        $template->description = $_POST['JournalentryForm']['description'];
        $template->firm_id = $this->firm->id;
        $template->acquireRawPostings($_POST['PostingForm'], $this->firm);
        Yii::app()->user->setState('template', $template);
        $this->redirect('createtemplate');
      }
      if(isset($_POST['PostingForm']))
      {
        $journalentryform->acquireItems($_POST['PostingForm'], false);
      }
      if(isset($_POST['addline']))
      {
        $journalentryform->removeEmptyRows(true);
        $journalentryform->postings[] = new PostingForm();
      }
      elseif(!$journalentryform->raw_input)
      {
        if($journalentryform->validate())
        {
          if($journalentryform->save($this->DEUser->getOpenChallenge()))
          {
            Event::log($this->DEUser, $journalentryform->firm_id, Event::FIRM_JOURNALENTRY_CREATED, array('description'=>$journalentryform->description, 'date'=>$journalentryform->date));
            Yii::app()->getUser()->setState('lastjournalentrydate', $journalentryform->date);
            Yii::app()->getUser()->setState('lastsection', $journalentryform->section_id);
            if(isset($_POST['done']))
            {
              $this->redirect(array('bookkeeping/journal','slug'=>$this->firm->slug));
            }
            elseif(isset($_POST['new']))
            {
              Yii::app()->user->setFlash('delt_success', Yii::t('delt', 'The journal entry «{description}» has been correctly saved.', array('{description}'=>$journalentryform->description)) . ' ' . Yii::t('delt', 'You can now prepare a new one.'));
              $this->redirect(array('bookkeeping/newjournalentry','slug'=>$this->firm->slug));
            }
            else
            {
              $this->redirect(array('bookkeeping/updatejournalentry','slug'=>$this->firm->slug, 'id'=>$journalentryform->journalentry->id));
            }
          }
        }
      }
    }
    
    $journalentryform->raw_input='';
    $journalentryform->is_closing = $this->is_closing;
    $this->render('newjournalentry', array(
      'model'=>$this->loadModelBySlug($slug),
      'journalentryform'=>$journalentryform,
      'items'=>$journalentryform->postings,
      'template'=>$template,
    ));
  }
  
  public function actionClosingjournalentry($slug, $position='')
  {
    $this->firm=$this->loadModelBySlug($slug);
    $this->checkFrostiness($this->firm);
    
    $p=null;
    if($p=$this->firm->getMainPosition($position))
    {
      $this->accounts = $this->firm->getAccountBalances($position);
      $this->is_closing = true;
      $this->journalentrydescription=$p->getClosingDescription();
      
      if(sizeof($this->accounts))
      {
        return $this->actionNewjournalentry($slug);
        // we show the standard form
      }
    }
    $this->render('closingjournalentry', array('position'=>$position, 'model'=>$this->firm, 'closing'=>$p));
    
  }
  
  public function actionPrepareentry($slug, $op='snapshot')
  {
    $this->firm=$this->loadModelBySlug($slug);
    $this->checkFrostiness($this->firm);
    $this->journalentrydescription=Yii::t('delt', 'Journal entry from balances');
    $this->journalentrydescription .= ' (' . Yii::t('delt', $op) . ')';
    
    $this->accounts = $this->firm->getAccountBalances('', $_POST['id'], $op!='snapshot');

    if(sizeof($this->accounts))
    {
      $this->form_action_required = $this->createUrl('bookkeeping/newjournalentry', array('slug'=>$this->firm->slug));
      return $this->actionNewjournalentry($this->firm->slug);
      // we show the standard form
    }
    $this->render('closingjournalentry', array('position'=>'', 'model'=>$this->firm));
  }

  public function actionUpdatejournalentry($id)
  {
    $this->journalentry = $this->loadJournalentry($id);
    $this->firm=$this->journalentry->firm;
    $this->checkManageability($this->firm);
    $this->checkFrostiness($this->firm);
    
    $journalentryform = new JournalentryForm();
    $journalentryform->firm_id = $this->firm->id;
    $journalentryform->firm = $this->firm;
    $journalentryform->currency = $this->firm->currency;
    if($this->journalentry->is_adjustment)
    {
      $journalentryform->adjustment_checkbox_needed = true; 
    }
    
    $journalentryform->loadFromJournalentry($this->journalentry);
    
    $journalentryform->setIdentifier();
        
    if(isset($_POST['JournalentryForm']))
    {
      $journalentryform->attributes=$_POST['JournalentryForm'];
      $journalentryform->is_adjustment = DELT::getValueFromArray($_POST['JournalentryForm'], 'is_adjustment', 0);
      $journalentryform->acquireItems($_POST['PostingForm'], false);
      if(isset($_POST['addline']))
      {
        $journalentryform->removeEmptyRows(true);
        $journalentryform->postings[] = new PostingForm();
        $journalentryform->show_analysis = false;
      }
      elseif(!$journalentryform->raw_input)
      {
        if($journalentryform->validate())
        {
          if($journalentryform->save($this->DEUser->getOpenChallenge()))
          {
            Event::log($this->DEUser, $journalentryform->firm_id, Event::FIRM_JOURNALENTRY_UPDATED, array('description'=>$journalentryform->description, 'date'=>$journalentryform->date));
            Yii::app()->getUser()->setState('lastjournalentrydate', $journalentryform->date);
            Yii::app()->getUser()->setState('lastsection', $journalentryform->section_id);
            if(isset($_POST['done']))
            {
              $this->redirect(array('bookkeeping/journal','slug'=>$this->firm->slug));
            }
            elseif(isset($_POST['new']))
            {
              Yii::app()->user->setFlash('delt_success', Yii::t('delt', 'The journal entry «{description}» has been correctly saved.', array('{description}'=>$journalentryform->description)) . ' ' . Yii::t('delt', 'You can now prepare a new one.'));
              $this->redirect(array('bookkeeping/newjournalentry','slug'=>$this->firm->slug));
            }
            elseif(isset($_POST['save']))
            {
              $this->redirect(array('bookkeeping/updatejournalentry','id'=>$id));
            }
          }
        }
      }
    }
    
    $journalentryform->raw_input='';
    $this->render('updatejournalentry', array(
      'model'=>$this->firm,
      'journalentryform'=>$journalentryform,
      'items'=>$journalentryform->postings,
    ));

  }


  public function actionDeletejournalentry($id)
  {
    $this->journalentry = $this->loadJournalentry($id);
    $this->firm=$this->journalentry->firm;
    $this->checkManageability($this->firm);
    $this->checkFrostiness($this->firm);
    
    if(Yii::app()->getRequest()->isPostRequest)
    {
      if($this->journalentry->safeDelete())
      {
        Event::log($this->DEUser, $this->firm->id, Event::FIRM_JOURNALENTRY_DELETED, array('id'=>$id));
        Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'The journal entry has been successfully deleted.'));
      }
      else
      {
        Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'The journal entry could not be deleted.'));
      }
      $this->redirect(array('bookkeeping/journal','slug'=>$this->firm->slug));
      
    }
    throw new CHttpException(404, 'The requested page does not exist.');

  }


  public function actionClearjournal($slug)
  {
    $this->firm=$this->loadFirmBySlug($slug);
    $this->checkFrostiness($this->firm);
    
    if(Yii::app()->getRequest()->isPostRequest)
    {
      if($this->firm->clearJournal())
      {
        Event::log($this->DEUser, $this->firm->id, Event::FIRM_JOURNAL_CLEARED);
        Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'The journal has been successfully cleared.'));
      }
      else
      {
        Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'The journal could not be cleared.'));
      }
      
      $this->redirect(array('bookkeeping/journal','slug'=>$this->firm->slug));
      
    }
    throw new CHttpException(404, 'The requested page does not exist.');

  }

  
  public function actionUpdatejournal($slug, $op)
  {
    $this->firm=$this->loadFirmBySlug($slug);
    $this->checkFrostiness($this->firm);
    
    if(Yii::app()->getRequest()->isPostRequest)
    {
      $ids = DELT::getValueFromArray($_POST, 'id', array());
      
      if($op=='include' or $op=='exclude')
      {
        $affected_rows = Yii::app()->db->createCommand()
        ->update('{{journalentry}}', array('is_included'=>$op=='include'?1:0),
          array('and',
            array('firm_id=:firm_id' , array(':firm_id'=>$this->firm->id)),
            array('in', 'id', $ids)
            )
          );
        if($affected_rows==0)
        {
          Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'No journal entry has been modified.'));
        }
        else
        {
          if($op=='include')
          {
            Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'One journal entry has been included. | {n} journal entries have been included.', $affected_rows));
          }
          else
          {
            Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'One journal entry has been excluded. | {n} journal entries have been excluded.', $affected_rows));
          }
        }
       }

      elseif($op=='clone')
      {
        $affected_rows = $this->firm->cloneSelectedJournalentries($_POST['id']);
        if($affected_rows==0)
        {
          Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'No journal entry has been cloned.'));  // this shouldn't happen
        }
        else
        {
          Event::log($this->DEUser, $this->firm->id, Event::FIRM_JOURNALENTRY_CREATED, array('ids'=>$ids));
          Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'One journal entry has been cloned. | {n} journal entries have been cloned.', $affected_rows));
        }
      }

      elseif($op=='tisv')
      {
        $affected_rows = $this->firm->toggleStatementVisibilityOfSelectedJournalentries($_POST['id']);
        // FIXME This could be done in one UPDATE statement (but it's not so easy as it might seem
        if($affected_rows==0)
        {
          Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'No journal entry has been toggled.'));  // this shouldn't happen
        }
        else
        {
          Event::log($this->DEUser, $this->firm->id, Event::FIRM_JOURNALENTRIES_DELETED, array('ids'=>$ids));
          Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'One journal entry has been toggled. | {n} journal entries have been toggled.', $affected_rows));
        }
      }  

      elseif($op=='swap')
      {
        if($this->firm->swapSelectedJournalentries($ids))
        {
          Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'The entries have been swapped.'));
        }
        else
        {
          Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'The entries could not be swapped.'));
        }
      }  

      elseif($op=='connect')
      {
        if($this->firm->connectSelectedJournalentriesToTransaction($ids, Yii::app()->user->getState('transaction'), $this->DEUser->getOpenChallenge()))
        {
          //Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'The entries have been connected.'));
        }
        else
        {
          Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'The entries could not be connected.'));
        }
      }  


      elseif($op=='delete')
      {
        $affected_rows = $this->firm->deleteSelectedJournalentries($ids);
        if($affected_rows==0)
        {
          Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'No journal entry has been deleted.'));
        }
        else
        {
          Event::log($this->DEUser, $this->firm->id, Event::FIRM_JOURNALENTRIES_DELETED, array('ids'=>$ids));
          Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'One journal entry has been deleted. | {n} journal entries have been deleted.', $affected_rows));
        }
      }

      elseif($op=='changeyear')
      {
        $affected_rows = $this->firm->changeYearForSelectedJournalentries($ids, DELT::getValueFromArray($_POST, 'years', 0));
        if($affected_rows==0)
        {
          Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'No journal entry has been updated.'));
        }
        else
        {
          Event::log($this->DEUser, $this->firm->id, Event::FIRM_JOURNALENTRY_UPDATED, array('ids'=>$ids));
          Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'One journal entry has been updated. | {n} journal entries have been updated.', $affected_rows));
        }
      }

      elseif($op=='changesection')
      {
        $section_id = DELT::getValueFromArray($_POST, 'section', 0);
        $affected_rows = $this->firm->changeSectionForSelectedJournalentries($ids, $section_id);
        Yii::app()->getUser()->setState('lastsection', $section_id);

        if($affected_rows==0)
        {
          Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'No journal entry has been updated.'));
        }
        else
        {
          Event::log($this->DEUser, $this->firm->id, Event::FIRM_JOURNALENTRY_UPDATED, array('ids'=>$ids));
          Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'One journal entry has been updated. | {n} journal entries have been updated.', $affected_rows));
        }
      }


      $this->redirect(array('bookkeeping/journal','slug'=>$this->firm->slug));
    }
    
    throw new CHttpException(404, 'The requested page does not exist.');

  }

  public function actionJournalentryfromtemplate($id)
  {
    $template=$this->loadTemplate($id);
    $this->firm=$template->firm;
    $this->checkManageability($this->firm);
    $this->checkFrostiness($this->firm);
    
    $this->accounts = $template->getAccountsInvolved($this->firm);

    if(sizeof($this->accounts))
    {
      $this->journalentrydescription=$template->description;
      return $this->actionNewjournalentry($this->firm->slug, $template);
      // we show the standard form
    }
    
    throw new CHttpException(404, Yii::t('delt', 'Sorry, it looks like the template has some errors in it.'));
  }

  public function actionUpdatetemplate($id)
  {
    $template=$this->loadTemplate($id);
    $this->firm=$template->firm;
    $this->checkManageability($this->firm);
    $this->checkFrostiness($this->firm);

    $this->render('updatetemplate',array('model'=>$this->firm));
  }

  public function actionCreatetemplate()
  {
    $template = Yii::app()->user->getState('template');
    $this->firm = $this->loadFirm($template->firm_id);
    if(isset($_POST['Template']))
    {
        $template->attributes=$_POST['Template'];
        if($template->validate())
        {
          $template->firm_id = $this->firm->id;
          $template->acquirePostingsfromForm($_POST);
          if($template->save())
          {
            Yii::app()->user->setFlash('delt_success', Yii::t('delt', 'The template has been correctly saved.')); 
          }
          else
          {
            Yii::app()->user->setFlash('delt_failure', Yii::t('delt', 'The template could not be saved.')); 
          }
          $this->redirect(array('bookkeeping/journal','slug'=>$this->firm->slug));
        }
    }
    
    if(sizeof($template->postings)<2)
    {
      Yii::app()->user->setFlash('delt_failure', Yii::t('delt', 'A template needs at least two postings.'));
      $this->redirect(Yii::app()->request->urlReferrer);
    }
    
    $this->render('createtemplate',array('model'=>$this->firm, 'template'=>$template));
  }

  public function actionCreateTemplateFromJournalEntry($id)
  {
    $this->journalentry = $this->loadJournalentry($id);
    $this->firm=$this->journalentry->firm;
    $this->checkManageability($this->firm);
    $this->checkFrostiness($this->firm);
    
    $template=new Template;
    $template->acquirePostingsFromJE($this->journalentry, $this->firm);
    $template->description = $this->journalentry->description;
    
    Yii::app()->user->setState('template', $template);
    $this->redirect(array('createtemplate'));
  }

  public function actionDeletetemplate($id)
  {
    $template=$this->loadTemplate($id);
    $this->firm=$template->firm;
    $this->checkManageability($this->firm);
    $this->checkFrostiness($this->firm);
    
    if($template->delete())
    {
      Yii::app()->user->setFlash('delt_success','The template has been correctly deleted.'); 
    }
    else
    {
      Yii::app()->user->setFlash('delt_failure','The template could not be deleted.'); 
    }
    $this->redirect(array('template/admin', 'slug'=>$this->firm->slug));
  }

  public function actionToggleautomaticstatus($id)
  {
    $template=$this->loadTemplate($id);
    
    $this->firm=$template->firm;
    $this->checkManageability($this->firm);
    $this->checkFrostiness($this->firm);

    $template->automatic = !$template->automatic;
    $template->save();
    
    //$this->refresh();

    $this->redirect(array('template/admin', 'slug'=>$this->firm->slug));
  }

  /**
   * Serves a list of suggestions matching the term $term, in form of
   * a json-encoded object.
   * @param string $term the string to match
   * @param string $slug the slug of the firm
   */
  public function actionSuggestaccount($term='', $slug='')
  {
    $firm=$this->loadFirmBySlug($_GET['slug']);
    //$this->checkFrostiness($firm);
    $this->serveJson($firm->findAccounts($term));
  }

  /**
   * Serves a list of suggestions matching the term $term, in form of
   * a json-encoded object.
   * @param string $term the string to match
   * @param string $slug the slug of the firm
   */
  public function actionSuggestsubchoices($term='', $slug='')
  {
    $firm=$this->loadFirmBySlug($_GET['slug']);
    //$this->checkFrostiness($firm);
    $this->serveJson($firm->findSubchoices($term));
  }

  /**
   * Serves a list of subchoices matching the account $account, in form of
   * a json-encoded object.
   * @param string $account the string to match
   * @param string $slug the slug of the firm
   */
  public function actionListsubchoices($code='', $slug='')
  {
    $firm=$this->loadFirmBySlug($slug);
    //$this->checkFrostiness($firm);
    if ($account=$firm->findAccount($code, true))
    {
      $choices = $account->getSubChoices();
    }
    else
    {
      $choices = null;
    }
    $this->serveJson($choices);
  }

  /**
   * Serves the amount needed to close the account $account, in form of
   * a json-encoded object.
   * @param string $account the string to match
   * @param string $slug the slug of the firm
   */
  public function actionAccountclosingamount($slug='', $code='', $subchoice='', $posting=0)
  {
    $firm=$this->loadFirmBySlug($slug);
    $this->checkFrostiness($firm);
    $info=$firm->getClosingAmountInfo($code, $subchoice, $posting);
    if($info['account_id'])
    {
      Yii::app()->getUser()->setState('last_account_closed_interactively', $info['account_id']);
    }
    $this->serveJson(array('amount'=>$info['amount']));
  }

  public function actionLedger($id /* account_id */, $journalentry=null)
  {
    $account=$this->loadAccount($id);
    $this->checkManageability($this->firm=$this->loadFirm($account->firm_id));
    
    $this->journalentry_id = $journalentry;
    $this->render('ledger', array(
      'model'=>$this->firm,
      'account'=>$account,
    ));
  }
  
  public function actionFixAccountsChart($slug)
  {
    $firm=$this->loadModelBySlug($slug);
    $this->checkFrostiness($firm);

    if(Yii::app()->getRequest()->isPostRequest)
    {
      set_time_limit(0);
      if($firm->fixAccounts())
      {
        Yii::app()->user->setFlash('delt_success','Checks have been correctly run.'); 
      }
      else
      {
        Yii::app()->user->setFlash('delt_failure','Problems with checks.'); 
      }
      $this->redirect(array('bookkeeping/coa','slug'=>$firm->slug));
    }
    
    $this->render('fixaccountschart', array(
      'model'=>$this->loadModelBySlug($slug)
    ));
  }
    
  public function loadModelBySlug($slug)
  {
    return $this->loadFirmBySlug($slug);
  }
  
  public function loadTemplate($id)
  {
    $template=Template::model()->findByPk($id);
    if($template===null)
      throw new CHttpException(404,'The requested page does not exist.');
    return $template;
  }
  
  public function renderName(Account $account, $row)
  {
    return $this->renderPartial('../account/_name',array('account'=>$account, 'link'=>true),true);
  }

  public function renderNameWithoutLink(Account $account, $row)
  {
    return $this->renderPartial('../account/_name',array('account'=>$account, 'link'=>false),true);
  }

  public function renderPosition(Account $account, $row)
  {
    return $this->renderPartial('../account/_position',array('account'=>$account),true);
  }
  
  public function renderOutstandingBalance(Account $account, $row)
  {
    return $this->renderPartial('../account/_outstanding_balance',array('account'=>$account),true);
  }

  public function renderDebit(Posting $posting, $row)
  {
    $this->last_journalentry_id = $posting->journalentry_id;
    return $this->renderPartial('_debit',array('posting'=>$posting),true);
  }
  
  public function renderCredit(Posting $posting, $row)
  {
    return $this->renderPartial('_credit',array('posting'=>$posting),true);
  }

  public function renderSingleDebit(Account $account, $row)
  {
    $this->debit_sum += $account->debitgrandtotal;
    return $this->renderPartial('_value',array('value'=>$account->debitgrandtotal),true);
  }
  
  public function renderSingleCredit(Account $account, $row)
  {
    $this->credit_sum += $account->creditgrandtotal;
    return $this->renderPartial('_value',array('value'=>-$account->creditgrandtotal),true);
  }
  
  public function renderCheckedOutstandingBalance(Account $account, $row)
  {
    $value = $account->debitgrandtotal + $account->creditgrandtotal;
    return $this->renderPartial('_checkedoutstandingbalance', array('account'=>$account, 'value'=>$value), true);
  }

  public function renderCheckedOutstandingBalanceDr(Account $account, $row)
  {
    $value = max($account->debitgrandtotal + $account->creditgrandtotal,0);
    return $this->renderPartial('_value', array('value'=>$value), true);
  }

  public function renderCheckedOutstandingBalanceCr(Account $account, $row)
  {
    $value = min($account->debitgrandtotal + $account->creditgrandtotal,0);
    return $this->renderPartial('_value', array('value'=>-$value), true);
  }

  public function renderDate(Posting $posting, $row)
  {
    if($posting->journalentry_id != $this->last_journalentry_id)
    {
      return $this->renderPartial('_date',array('posting'=>$posting),true);
    }
    return '';
  }
  
  public function renderDescription(Posting $posting, $row)
  {
    if($posting->journalentry_id != $this->last_journalentry_id)
    {
      $this->line_shown = true;
      return $this->renderPartial('_description', array('posting'=>$posting), true);
    }
    $this->line_shown = false;
    return '';
  }

  public function renderSection(Posting $posting, $row)
  {
    if($posting->journalentry_id != $this->last_journalentry_id)
    {
      $section = $posting->journalentry->getSection();
      return Chtml::tag('span', array(
        'style'=>'font-size: 1.5em; color: #' . $section->color,
        'title'=>$section->name,
      ), '■', false);
    }
    else
      return '';
  }

  public function renderSubchoice(Posting $posting, $row)
  {
    return $this->renderPartial('_subchoice', array('subchoice'=>$posting->subchoice), true);
  }

  public function renderDescriptionForLedger(Posting $posting, $row)
  {
    return $this->renderPartial('_descriptionforledger', array('posting'=>$posting), true);
  }

  
  public function isLineShown()
  {
    return $this->line_shown;
  }
  
  public function renderAccount(Posting $posting, $row)
  {
    return $this->renderPartial('_account', array(
      'account'=>$posting->account,
      'journalentry'=>$posting->journalentry,
      'amount'=>$posting->amount,
      'comment'=>$posting->comment,
      'subchoice'=>$posting->subchoice,
      ),true);
  }
  
  public function renderSingleAccount(Account $account, $row)
  {
    return $this->renderPartial('_singleaccount',array('account'=>$account),true);
  }


}
