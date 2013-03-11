<?php

class FirmController extends Controller
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
      /*
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
      */
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','fork'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
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
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
    throw new CHttpException(501, 'Not yet implemented.');

    
		$model=new Firm;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Firm']))
		{
			$model->attributes=$_POST['Firm'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Forks an existing, public, firm.
	 * If creation is successful, the browser will be redirected to the 'update' page.
	 */
	public function actionFork($slug=null)
	{
    $firm = null;
    if($slug)
    {
      $firm = $this->loadFirmBySlug($slug, false);
    }
    
		if($_POST)
		{
      $newfirm = new Firm();
      try
      {
			  $newfirm->forkFrom($firm, $this->DEUser);
        $newfirm->fixAccounts();
        $newfirm->fixAccountNames();
				$this->redirect(array('firm/update','id'=>$newfirm->id));
      }
      catch(Exception $e)
      {
        Yii::app()->user->setFlash('delt_failure','The information about the firm could not be saved.'); 
				$this->redirect(array('firm/form'));
      }
		}

    if($firm)
    {
      $this->render('fork_confirm',array(
        'firm'=>$firm,
      ));
    }
    else
    {
      $this->render('fork', array(
        'firms'=>Firm::model()->findForkableFirms()
      ));
    }
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id=null)
	{
    $model=$this->loadFirm($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Firm']))
		{
			$model->attributes=$_POST['Firm'];
      try
      {
        $model->save(false);
        Yii::app()->user->setFlash('delt_success','The information about the firm has been correctly saved.'); 
				$this->redirect(array('bookkeeping/manage','slug'=>$model->slug));
      }
      catch(Exception $e)
      {
        Yii::app()->user->setFlash('delt_failure','The information about the firm could not be saved.'); 
      }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
  /*
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
  */

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Firm');
		$this->render('index',array(
			'dataProvider'=>$this->DEUser->getFirmsAsDataProvider(),
		));
	}

	/**
	 * Manages all models.
	 */
  /*
	public function actionAdmin()
	{
		$model=new Firm('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Firm']))
			$model->attributes=$_GET['Firm'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
  */

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Firm the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
    return $this->loadFirm($id);
	}

	/**
	 * Performs the AJAX validation.
	 * @param Firm $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='firm-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
