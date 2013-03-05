<?php

class BookkeepingController extends Controller
{
  
  public $layout='//layouts/column2';
  
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

	public function actionAccountschart($slug)
	{
    $model=$this->loadModelBySlug($slug);
		$this->render('accountschart', array(
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

	public function actionLedger($id /* account_id */)
	{
    $account=$this->loadAccount($id);
    $this->checkManageability($firm=$this->loadFirm($account->firm_id));
    
		$this->render('ledger', array(
      'model'=>$firm,
      'account'=>$account
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
      $this->redirect(array('bookkeeping/accountschart','slug'=>$firm->slug));
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
  
}
