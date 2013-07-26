<?php

class BookkeepingController extends Controller
{
  
  public $layout='//layouts/column2';
  
  private $line_shown = false;
  // we set this to true when we want to avoid duplicates, like in the journal
  
  public $show_link_on_description = false;
  
  public $last_post_id = null;
  
  public $post_id = null;
  
  public $debit_sum = 0;
  public $credit_sum = 0;
  
  public $accounts;
  public $postdescription;
  public $is_closing = 0;
  
	public function actionIndex()
	{
    if($this->DEUser)
    {
      $this->render('index', array('firms'=>$this->DEUser->firms, 'wfirms'=>$this->DEUser->wfirms));
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
            $this->redirect(array('bookkeeping/balance','slug'=>$this->firm->slug, 'format'=>'csv'));
          }
        }
        
        $exportbalanceform->separator = Yii::app()->getUser()->getState('separator', ',');
        $exportbalanceform->delimiter = Yii::app()->getUser()->getState('delimiter', '');
        $exportbalanceform->type = Yii::app()->getUser()->getState('type', '2');
        $exportbalanceform->charset = Yii::app()->getUser()->getState('charset', 'utf-8');
        
        $this->render('exportbalance', array(
          'model'=>$this->firm,
          'exportbalanceform'=>$exportbalanceform,
        ));
        
        break;
      case 'csv':
        $content=$this->renderPartial('_balance_csv', array(
          'accounts'=>$this->firm->getAccountBalancesData(''),
          'separator'=>Yii::app()->getUser()->getState('separator', ','),
          'delimiter'=>Yii::app()->getUser()->getState('delimiter', ''),
          'type'=>Yii::app()->getUser()->getState('type', '2'),
          'charset'=>Yii::app()->getUser()->getState('charset', 'utf-8'),
          ));
        $filename = $this->firm->slug . '-' . date('Y-m-d-His') . (Yii::app()->getUser()->getState('separator', ',')=='t' ? '.tsv' : '.csv');
        $this->sendDispositionHeader($filename);
        $this->serveContent(Yii::app()->getUser()->getState('separator', ',')=='t' ? 
            'text/tab-separated-values'
            :
            'text/csv', 
          $content);
        break;
      default:
        $this->render('balance', array(
          'model'=>$this->firm,
          'dataProvider'=>$this->firm->getAccountBalancesAsDataProvider(),
        ));
    }
    
	}

	public function actionStatements($slug, $level=1)
	{
    $this->firm=$this->loadModelBySlug($slug);
    if($level>$this->firm->getCOAMaxLevel())
      throw new CHttpException(404,'The requested page does not exist.');
		$this->render('statements', array(
      'model'=>$this->firm,
      'financial'=>$this->firm->getFinancialStatement($level),
      'economic'=>$this->firm->getEconomicStatement($level),
      'level'=>$level,
    ));
	}

	public function actionJournal($slug, $post=null)
	{
    $this->firm=$this->loadModelBySlug($slug);
    $this->post_id = $post;
		$this->render('journal', array(
      'model'=>$this->firm,
    ));
	}
  
  public function actionExport($slug)
  {
    $this->firm=$this->loadModelBySlug($slug);

    $data=$this->firm->getExportData('111');
    
    $filename = $this->firm->slug . '-' . date('Y-m-d-His') . '.delt';
    $this->sendDispositionHeader($filename);
    $this->serveJson($data);
  }

  public function actionImport($slug)
  {
    $this->firm=$this->loadModelBySlug($slug);
    
    if(isset($_POST['Firm']))
		{
      //throw new CHttpException(501, 'Not yet implemented.');
      $file=CUploadedFile::getInstance($this->firm, 'file');
      if (is_object($file) && get_class($file)==='CUploadedFile')
      {
        if($this->firm->loadFromFile($file))
        {
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

	public function actionNewpost($slug)
	{
    $this->firm=$this->loadModelBySlug($slug);
    
    $postform = new PostForm();
    $postform->firm_id = $this->firm->id;
    $postform->firm = $this->firm;  // FIXME: if we set the firm, we don't need to set the other values... here for compatibility only
    $postform->currency = $this->firm->currency;
    
    if(!$postform->date)
    {
      $postform->date = Yii::app()->getUser()->getState('lastpostdate', DELT::getDateForFormWidget(date('Y-m-d')));
    }
    
    if(isset($this->postdescription))
    {
      $postform->description = $this->postdescription;
    }
    
    if(isset($this->accounts) and sizeof($this->accounts)>0)
    {
      $postform->acquireItems($this->accounts);
    }
    else
    {
      $postform->debitcredits = array(new DebitcreditForm(), new Debitcreditform());
    }
        
		if(isset($_POST['PostForm']))
		{
			$postform->attributes=$_POST['PostForm'];
      if(isset($_POST['DebitcreditForm']))
      {
        $postform->acquireItems($_POST['DebitcreditForm']);
      }
      if(isset($_POST['addrow']))
      {
        $postform->debitcredits[] = new DebitcreditForm();
      }
      elseif(!$postform->raw_input)
      {
        if($postform->validate())
        {
          if($postform->save())
          {
            Yii::app()->getUser()->setState('lastpostdate', $postform->date);
            $this->redirect(array('bookkeeping/journal','slug'=>$this->firm->slug));
          }
        }
      }
		}
    
    $postform->raw_input='';
    $postform->is_closing = $this->is_closing;
		$this->render('newpost', array(
      'model'=>$this->loadModelBySlug($slug),
      'postform'=>$postform,
      'items'=>$postform->debitcredits,
    ));
	}
  
  public function actionClosingpost($slug, $position='')
  {
    switch($position)
    {
      case 'P':
        $this->postdescription=Yii::t('delt', 'Patrimonial closing entry entry');
        break;
      case 'E':
        $this->postdescription=Yii::t('delt', 'Economic closing journal entry');
        break;
      case 'M':
        $this->postdescription=Yii::t('delt', 'Memo closing journal entry');
        break;
      default:
        $position='';
        $this->postdescription=Yii::t('delt', 'Closing journal entry');
    }
    $this->firm=$this->loadModelBySlug($slug);
    if($position)
    {
      $this->accounts = $this->firm->getAccountBalances($position);
      $this->is_closing = true;
      if(sizeof($this->accounts))
      {
        return $this->actionNewpost($slug);
        // we show the standard form
      }
    }
    
    $this->render('closingpost', array('position'=>$position, 'model'=>$this->firm));
    
  }

  public function actionProfitlosspost($slug)
  {
    $this->firm=$this->loadModelBySlug($slug);
    $this->postdescription=Yii::t('delt', 'Profit/Loss');
    $this->accounts = $this->firm->getAccountBalances('e');
    
    if(sizeof($this->accounts))
    {
      return $this->actionNewpost($slug);
      // we show the standard form
    }
    
    $this->render('closingpost', array('position'=>'e', 'model'=>$this->firm));
  }

	public function actionUpdatepost($id)
	{
    $this->post = $this->loadPost($id);
    $this->firm=$this->post->firm;
    $this->checkManageability($this->firm);
    
    $postform = new PostForm();
    $postform->firm_id = $this->firm->id;
    $postform->firm = $this->firm;
    $postform->currency = $this->firm->currency;
    if($this->post->is_adjustment)
    {
      $postform->adjustment_checkbox_needed = true; 
    }
    
    $postform->loadFromPost($this->post);
        
		if(isset($_POST['PostForm']))
		{
			$postform->attributes=$_POST['PostForm'];
      $postform->acquireItems($_POST['DebitcreditForm']);
      if(isset($_POST['addrow']))
      {
        $postform->debitcredits[] = new DebitcreditForm();
        $postform->show_analysis = false;
      }
      elseif(!$postform->raw_input)
      {
        if($postform->validate())
        {
          if($postform->save())
          {
            Yii::app()->getUser()->setState('lastpostdate', $postform->date);
            $this->redirect(array('bookkeeping/journal','slug'=>$this->firm->slug));
          }
        }
      }
		}
    
    $postform->raw_input='';
		$this->render('updatepost', array(
      'model'=>$this->firm,
      'postform'=>$postform,
      'items'=>$postform->debitcredits,
    ));

	}


	public function actionDeletepost($id)
	{
    $this->post = $this->loadPost($id);
    $this->firm=$this->post->firm;
    $this->checkManageability($this->firm);
    
		if(Yii::app()->getRequest()->isPostRequest)
		{
      if($this->post->safeDelete())
      {
        Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'The post has been successfully deleted.'));
      }
      else
      {
        Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'The post could not be deleted.'));
      }
      $this->redirect(array('bookkeeping/journal','slug'=>$this->firm->slug));
      
		}
    throw new CHttpException(404, 'The requested page does not exist.');

	}


	public function actionClearjournal($slug)
	{
    $this->firm=$this->loadFirmBySlug($slug);
    
		if(Yii::app()->getRequest()->isPostRequest)
		{
      if($this->firm->clearJournal())
      {
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




  public function actionPostfromtemplate($id)
  {
    $template=$this->loadTemplate($id);
    $this->firm=$template->firm;
    $this->checkManageability($this->firm);
    
    $this->accounts = $template->getAccountsInvolved($this->firm->currency);
    
    if(sizeof($this->accounts))
    {
      $this->postdescription=$template->description;
      return $this->actionNewpost($this->firm->slug);
      // we show the standard form
    }
    
    throw new CHttpException(404,'The requested page does not exist.');
  }


  public function actionCreatetemplate($id)
  {
    $this->post = $this->loadPost($id);
    $this->firm=$this->post->firm;
    $this->checkManageability($this->firm);
    
    $template=new Template;

    if(isset($_POST['Template']))
    {
        $template->attributes=$_POST['Template'];
        if($template->validate())
        {
          $template->firm_id = $this->firm->id;
          $template->post_id = $this->post->id;
          if($template->save())
          {
            Yii::app()->user->setFlash('delt_success','The template has been correctly saved.'); 
          }
          else
          {
            Yii::app()->user->setFlash('delt_failure','The template could not be saved.'); 
          }
          $this->redirect(array('bookkeeping/journal','slug'=>$this->firm->slug));
        }
    }
    if(!$template->description)
    {
      $template->description = $this->post->description;
    }

    $this->render('createtemplate',array('model'=>$this->firm, 'template'=>$template));
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
    $this->serveJson($firm->findAccounts($term));
  }


	public function actionLedger($id /* account_id */, $post=null)
	{
    $account=$this->loadAccount($id);
    $this->checkManageability($this->firm=$this->loadFirm($account->firm_id));
    
    $this->post_id = $post;
		$this->render('ledger', array(
      'model'=>$this->firm,
      'account'=>$account,
    ));
	}
  
  public function actionFixAccountsChart($slug)
  {
    $firm=$this->loadModelBySlug($slug);
    
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
    return $this->renderPartial('../account/_name',array('account'=>$account),true);
  }

  public function renderposition(Account $account, $row)
  {
    return $this->renderPartial('../account/_position',array('account'=>$account),true);
  }
  
  public function renderOutstandingBalance(Account $account, $row)
  {
    return $this->renderPartial('../account/_outstanding_balance',array('account'=>$account),true);
  }

  public function renderDebit(Debitcredit $debitcredit, $row)
  {
    $this->last_post_id = $debitcredit->post_id;
    return $this->renderPartial('_debit',array('debitcredit'=>$debitcredit),true);
  }
  
  public function renderCredit(Debitcredit $debitcredit, $row)
  {
    return $this->renderPartial('_credit',array('debitcredit'=>$debitcredit),true);
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

  public function renderDate(Debitcredit $debitcredit, $row)
  {
    if($debitcredit->post_id != $this->last_post_id)
    {
      return $this->renderPartial('_date',array('debitcredit'=>$debitcredit),true);
    }
    return '';
  }
  
  public function renderDescription(Debitcredit $debitcredit, $row)
  {
    if($debitcredit->post_id != $this->last_post_id)
    {
      $this->line_shown = true;
      return $this->renderPartial('_description', array('debitcredit'=>$debitcredit), true);
    }
    $this->line_shown = false;
    return '';
  }
  
  public function isLineShown()
  {
    return $this->line_shown;
    //return $this->hide_date_and_description;
  }

  
  public function renderAccount(Debitcredit $debitcredit, $row)
  {
    return $this->renderPartial('_account',array('account'=>$debitcredit->account, 'post'=>$debitcredit->post, 'amount'=>$debitcredit->amount),true);
  }
  
  public function renderSingleAccount(Account $account, $row)
  {
    return $this->renderPartial('_singleaccount',array('account'=>$account),true);
  }


}
