<?php

class AccountController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','delete'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
  /*
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
  */

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($slug, $id=null)
	{
    $firm = $this->loadFirmBySlug($slug);
		$model=new Account;
    $model->firm_id = $firm->id;

    if($id)
    {
      $parent = $this->loadAccount($id);
      $model->code = $parent->code . '.';
      $model->nature = $parent->nature;
    }

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Account']))
		{
			$model->attributes=$_POST['Account'];
      if($model->validate())
      {
        if($model->save())
        {
          $firm->fixAccounts();
          $this->redirect(array('bookkeeping/coa','slug'=>$firm->slug));
        }
      }
		}

		$this->render('create',array(
			'account'=>$model,
      'firm'=>$firm,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$account=$this->loadModel($id);
    $this->checkManageability($this->firm=$this->loadFirm($account->firm_id));
    if(!$account->textnames = $account->l10n_names)
    {
      $account->setDefaultForNames();
    }
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($account);

		if(isset($_POST['Account']))
		{
			$account->attributes=$_POST['Account'];
      if($account->validate())
      {
        if($account->save())
        {
          $firm->fixAccounts();
          $this->redirect(array('bookkeeping/coa','slug'=>$firm->slug));
        }
      }
		}

		$this->render('update',array(
			'account'=>$account,
      'firm'=>$this->firm,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
    $account=$this->loadAccount($id);
    $this->checkManageability($firm=$this->loadFirm($account->firm_id));
    
    try
    {
      $account->delete();
      $firm->fixAccounts();
    }
    catch (Exception $e)
    {
      return false;
    }

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('bookkeeping/coa', 'slug'=>$firm->slug));
      
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Account');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
  /*
	public function actionAdmin()
	{
		$model=new Account('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Account']))
			$model->attributes=$_GET['Account'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
  */

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Account the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
    return $this->loadAccount($id);
    // we define this in the parent class, because it is of common use...
	}

	/**
	 * Performs the AJAX validation.
	 * @param Account $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='account-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
