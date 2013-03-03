<?php

class BookkeepingController extends Controller
{
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
    $this->checkManageability($firm=$this->loadModel($account->firm_id));
    
		$this->render('ledger', array(
      'model'=>$firm,
      'account'=>$account
    ));
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
  
	/**
	 * Returns the data model based on the slug value given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param string $slug the slug of the model to be loaded
	 * @return Firm the loaded model
	 * @throws CHttpException
	 */
	public function loadModelBySlug($slug)
	{
		$model=Firm::model()->findByAttributes(array('slug'=>$slug));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
    $this->checkManageability($model);
		return $model;
	}

	/**
	 * Returns the data model based on the id value given.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the id of the model to be loaded
	 * @return Firm the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Firm::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
    if(!$model->isManageableBy($this->DEUser))
      throw new CHttpException(403, 'You are not allowed to access the requested page.');
		return $model;
	}
  
  /**
	 * Returns the account model based on the primary key.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Account the loaded model
	 * @throws CHttpException
	 */
	public function loadAccount($id)
	{
		$model=Account::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
  
  /**
	 * Checks whether a firm is manageable by the logged-in user.
	 * If not, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Account the loaded model
	 * @throws CHttpException
	 */
  public function checkManageability(Firm $firm)
  {
    if(!$firm->isManageableBy($this->DEUser))
      throw new CHttpException(403, 'You are not allowed to access the requested page.');
  }
  
  public function renderName(Account $account, $row)
  {
    return $this->renderPartial('../account/_name',array('account'=>$account),true);
  }

  public function renderIsEconomic(Account $account, $row)
  {
    return $this->renderPartial('../account/_is_economic',array('account'=>$account),true);
  }

  
}
