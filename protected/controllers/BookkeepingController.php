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
  
	public function actionIndex()
	{
    if($this->DEUser)
    {
      $this->render('index', array('firms'=>$this->DEUser->firms));
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

	public function actionCoa($slug)
	{
    $this->firm=$this->loadModelBySlug($slug);
		$this->render('coa', array(
      'model'=>$this->firm,
			'dataProvider'=>$this->firm->getAccountsAsDataProvider(),
    ));
	}

	public function actionBalance($slug)
	{
    $this->firm=$this->loadModelBySlug($slug);
		$this->render('balance', array(
      'model'=>$this->firm,
      'dataProvider'=>$this->firm->getAccountBalancesAsDataProvider(),
    ));
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
      $postform->acquireItems($_POST['DebitcreditForm']);
      if(isset($_POST['addrow']))
      {
        $postform->debitcredits[] = new DebitcreditForm();
      }
      else
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
    
		$this->render('newpost', array(
      'model'=>$this->loadModelBySlug($slug),
      'postform'=>$postform,
      'items'=>$postform->debitcredits,
    ));
	}
  
  public function actionClosingpost($slug, $nature='')
  {
    switch($nature)
    {
      case 'P':
        $this->postdescription=Yii::t('delt', 'Patrimonial closing post');
        break;
      case 'E':
        $this->postdescription=Yii::t('delt', 'Economic closing post');
        break;
      case 'M':
        $this->postdescription=Yii::t('delt', 'Memo closing post');
        break;
      default:
        $nature='';
        $this->postdescription=Yii::t('delt', 'Closing post');
    }
    $this->firm=$this->loadModelBySlug($slug);
    $this->accounts = $this->firm->getAccountBalances($nature);
    
    if(sizeof($this->accounts))
    {
      return $this->actionNewpost($slug);
      // we show the standard form
    }
    
    $this->render('closingpost', array('nature'=>$nature, 'model'=>$this->firm));
    
  }


	public function actionUpdatepost($id)
	{
    $this->post = $this->loadPost($id);
    $this->firm=$this->post->firm;
    $this->checkManageability($this->firm);
    
    $postform = new PostForm();
    $postform->firm_id = $this->firm->id;
    $postform->currency = $this->firm->currency;
    
    $postform->loadFromPost($this->post);
        
		if(isset($_POST['PostForm']))
		{
			$postform->attributes=$_POST['PostForm'];
      $postform->acquireItems($_POST['DebitcreditForm']);
      if(isset($_POST['addrow']))
      {
        $postform->debitcredits[] = new DebitcreditForm();
      }
      else
      {
        if($postform->validate())
        {
          if($postform->save())
          {
            $this->redirect(array('bookkeeping/journal','slug'=>$this->firm->slug));
          }
        }
      }
		}
    
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

  public function actionPostfromreason($id)
  {
    $reason=$this->loadReason($id);
    $this->firm=$reason->firm;
    $this->checkManageability($this->firm);
    
    $this->accounts = $reason->getAccountsInvolved($this->firm->currency);
    
    if(sizeof($this->accounts))
    {
      $this->postdescription=$reason->description;
      return $this->actionNewpost($this->firm->slug);
      // we show the standard form
    }
    
    throw new CHttpException(404,'The requested page does not exist.');
  }


  public function actionCreatereason($id)
  {
    $this->post = $this->loadPost($id);
    $this->firm=$this->post->firm;
    $this->checkManageability($this->firm);
    
    $reason=new Reason;

    if(isset($_POST['Reason']))
    {
        $reason->attributes=$_POST['Reason'];
        if($reason->validate())
        {
          $reason->firm_id = $this->firm->id;
          $reason->post_id = $this->post->id;
          if($reason->save())
          {
            Yii::app()->user->setFlash('delt_success','The reason has been correctly saved.'); 
          }
          else
          {
            Yii::app()->user->setFlash('delt_failure','The reason could not be saved.'); 
          }
          $this->redirect(array('bookkeeping/journal','slug'=>$this->firm->slug));
        }
    }
    if(!$reason->description)
    {
      $reason->description = $this->post->description;
    }

    $this->render('createreason',array('model'=>$this->firm, 'reason'=>$reason));
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
  
  public function loadReason($id)
  {
		$reason=Reason::model()->findByPk($id);
		if($reason===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $reason;
  }
  
  public function renderName(Account $account, $row)
  {
    return $this->renderPartial('../account/_name',array('account'=>$account),true);
  }

  public function renderNature(Account $account, $row)
  {
    return $this->renderPartial('../account/_nature',array('account'=>$account),true);
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
    return $this->renderPartial('_account',array('account'=>$debitcredit->account, 'post'=>$debitcredit->post),true);
  }
  
  public function renderSingleAccount(Account $account, $row)
  {
    return $this->renderPartial('_singleaccount',array('account'=>$account),true);
  }


}
