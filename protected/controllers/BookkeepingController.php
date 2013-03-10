<?php

class BookkeepingController extends Controller
{
  
  public $layout='//layouts/column2';
  public $firm=null;
  
	public function actionIndex()
	{
		$this->render('index', array('firms'=>$this->DEUser->firms));
	}

	/**
	 * Displays the actions available for a particular model.
	 * @param string $slug the slug of the model to be displayed
	 */
	public function actionManage($slug)
	{
		$this->render('manage', array(
      'model'=>$this->loadModelBySlug($slug)
    ));
  }

	public function actionCoa($slug)
	{
    $model=$this->loadModelBySlug($slug);
		$this->render('coa', array(
      'model'=>$model,
			'dataProvider'=>$model->getAccountsAsDataProvider(),
    ));
	}

	public function actionBalance($slug)
	{
		$this->render('balance', array(
      'model'=>$this->loadModelBySlug($slug)
    ));
	}

	public function actionJournal($slug)
	{
		$this->render('journal', array(
      'model'=>$this->loadModelBySlug($slug)
    ));
	}

	public function actionNewpost($slug)
	{
    $this->firm=$this->loadModelBySlug($slug);
    
    $postform = new PostForm();
    $postform->firm_id = $this->firm->id;
    $postform->currency = $this->firm->currency;
    
    $postform->debitcredits = array(new DebitcreditForm(), new Debitcreditform());
    
        
		if(isset($_POST['PostForm']))
		{
			$postform->attributes=$_POST['PostForm'];
      $postform->acquireItems($_POST['DebitcreditForm']);
      if(isset($_POST['addrow']))
      {
        $postform->debitcredits[] = new DebitcreditForm();
      }
      
      if($postform->validate())
      {
        if($postform->save())
        {
          $this->redirect(array('bookkeeping/journal','slug'=>$this->firm->slug));
        }
      }
		}

    
		$this->render('newpost', array(
      'model'=>$this->loadModelBySlug($slug),
      'postform'=>$postform,
      'items'=>$postform->debitcredits,
    ));
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


	public function actionLedger($id /* account_id */)
	{
    $account=$this->loadAccount($id);
    $this->checkManageability($this->firm=$this->loadFirm($account->firm_id));
    
		$this->render('ledger', array(
      'model'=>$this->firm,
      'account'=>$account,
    ));
	}
  
  public function actionFixAccountsChart($slug)
  {
    $firm=$this->loadModelBySlug($slug);
    
    if($_POST)
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
    return $this->renderPartial('_debit',array('debitcredit'=>$debitcredit),true);
  }
  
  public function renderCredit(Debitcredit $debitcredit, $row)
  {
    return $this->renderPartial('_credit',array('debitcredit'=>$debitcredit),true);
  }

  public function renderDate(Debitcredit $debitcredit, $row)
  {
    return $this->renderPartial('_date',array('debitcredit'=>$debitcredit),true);
  }


}
