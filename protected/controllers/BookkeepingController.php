<?php

class BookkeepingController extends Controller
{
  
  public $layout='//layouts/column2';
  public $firm=null;
  
  public $hide_date_and_description = false;
  // we set this to true when we want to avoid duplicates, like in the journal
  
  public $show_link_on_description = false;
  
  public $last_post_id = null;
  
  public $post_id = null;
  
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

	public function actionJournal($slug, $post=null)
	{
    $this->firm=$this->loadModelBySlug($slug);
    $this->post_id = $post;
		$this->render('journal', array(
      'model'=>$this->firm,
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

	public function actionUpdatepost($id)
	{
    throw new CHttpException(501, 'Not yet implemented.');
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
    $this->last_post_id = $debitcredit->post_id;
    return $this->renderPartial('_debit',array('debitcredit'=>$debitcredit),true);
  }
  
  public function renderCredit(Debitcredit $debitcredit, $row)
  {
    return $this->renderPartial('_credit',array('debitcredit'=>$debitcredit),true);
  }

  public function renderDate(Debitcredit $debitcredit, $row)
  {
    if((!$this->hide_date_and_description) or ($debitcredit->post_id != $this->last_post_id))
    {
      return $this->renderPartial('_date',array('debitcredit'=>$debitcredit),true);
    }
    return '';
  }
  
  public function renderDescription(Debitcredit $debitcredit, $row)
  {
    if((!$this->hide_date_and_description) or ($debitcredit->post_id != $this->last_post_id))
    {
      return $this->renderPartial('_description', array('debitcredit'=>$debitcredit), true);
    }
    return '';
  }
  
  public function renderAccount(Debitcredit $debitcredit, $row)
  {
    return $this->renderPartial('_account',array('account'=>$debitcredit->account, 'post'=>$debitcredit->post),true);
  }


}
