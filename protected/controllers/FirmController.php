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
      'postOnly + invitation', 
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

			array('allow', // allow authenticated user to perform the following actions
				'actions'=>array('create','update','fork','prefork','owners','delete','share','invitation'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
      array('allow', // allow authenticated user to perform 'public' actions
				'actions'=>array('public'),
				'users'=>array('*'),
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

  public function actionPublic($slug)
  {
    $this->firm=$this->loadFirmBySlug($slug, false);
    if(sizeof($debitcredits = $this->firm->getPostsAsDataProvider(100000)->data))
    {
      $this->render('public', array(
        'model'=>$this->firm,
        'debitcredits'=>$debitcredits,
      ));
    }
    else
    {
      $this->render('empty', array(
        'model'=>$this->firm
      ));
    }
  }

  public function actionOwners($slug)
  {
    throw new CHttpException(501, 'Not yet implemented.');
  }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Firm;
    $model->currency = 'EUR';

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Firm']))
		{
      if($this->DEUser->canCreateFirms())
      {
        $model->attributes=$_POST['Firm'];
        if($model->validate())
        {
          if($model->saveWithOwner($this->DEUser))
          {
            Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'The firm has been successfully created.'));
            $this->redirect(array('/bookkeeping/manage','slug'=>$model->slug));
          }
          else
          {
            die('something wrong');
          }
        }
        else
        {
          if(!$model->slug)
          {
            $model->slug = md5($model->name . rand(0, 100000));
          }
        }
      }
      else
      {
        Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'Sorry, you are not allowed to create firms at this time.'));
        $this->redirect(array('/bookkeeping/index'));
      }
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionPrefork($slug=null)
	{
    $firm=Firm::model()->findByAttributes(array('slug'=>$slug));
    if($firm)
    {
      $this->redirect(array('firm/fork', 'slug'=>$firm->slug));
    }
    else
    {
      $this->render('prefork',array(
        'slug'=>$slug,
      ));
    }
  }

	/**
	 * Forks an existing, public, firm, or a personal private firm.
	 * If creation is successful, the browser will be redirected to the 'update' page.
	 */
	public function actionFork($slug=null)
	{
    $firm = null;
    if($slug)
    {
      $firm = $this->loadFirmBySlug($slug, false);
      if(!$firm->isForkableBy($this->DEUser))
        throw new CHttpException(403, 'You are not allowed to access the requested page.');
    }
    
    $form = new ForkfirmForm;
    
		if(isset($_POST['ForkfirmForm']))
		{
      if($this->DEUser->canCreateFirms())
      {
        $form->attributes=$_POST['ForkfirmForm'];
        
        if($form->validate())
        {
          $newfirm = new Firm();
          try
          {
            $newfirm->forkFrom($firm, $this->DEUser, $form->type);
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
      }
      else
      {
        Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'Sorry, you are not allowed to create firms at this time.'));
        $this->redirect(array('/bookkeeping/index'));
      }
		}

    if($firm)
    {
      $this->render('fork_confirm',array(
        'firm'=>$firm, 'forkfirmform'=>$form,
      ));
    }
    else
    {
      $this->render('fork', array(
        'publicfirms'=>Firm::model()->findForkableFirms(),
        'ownfirms'=>$this->DEUser->firms,
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
		 $this->performAjaxValidation($model);
     
     $old_language_id = $model->language_id;

		if(isset($_POST['Firm']))
		{
			$model->attributes=$_POST['Firm'];
      if($model->validate())
      {
        try
        {
          $model->save(false);
          if($model->language_id != $old_language_id)
          {
            $model->fixAccountNames();
          }
          Yii::app()->user->setFlash('delt_success','The information about the firm has been correctly saved.'); 
          $this->redirect(array('bookkeeping/manage','slug'=>$model->slug));
        }
        catch(Exception $e)
        {
          Yii::app()->user->setFlash('delt_failure','The information about the firm could not be saved.'); 
        }
      }
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Shares a firm, by inviting another user.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $slug the slug of the firm to be shared
	 */
	public function actionShare($slug)
	{
    $model=$this->loadFirmBySlug($slug);
    
		if(isset($_POST['username']) && $username = $_POST['username'])
		{
      if($model->invite($username))
      {
          Yii::app()->user->setFlash('delt_success',Yii::t('delt', 'An invitation has been sent to «{username}». When accepted, the firm will be considered shared.', array('{username}'=>$username))); 
          $this->redirect(array('bookkeeping/manage','slug'=>$model->slug));
      }
      else
      {
        Yii::app()->user->setFlash('delt_failure',Yii::t('delt', 'The invitation could not be sent to «{username}». Please check the username.', array('{username}'=>$username))); 
      }
		}
    
		$this->render('share',array(
			'model'=>$model,
		));
	}

	public function actionInvitation($slug)
	{
    $model=$this->loadFirmBySlug($slug, false);
    
    if(isset($_GET['action']) && $_GET['action']=='accept')
    {
      
      if($fu = FirmUser::model()->findByAttributes(array('firm_id'=>$model->id, 'user_id'=>$this->DEUser->id, 'role'=>'I')))
      {
        $fu->role='O';
        $fu->save();
        Yii::app()->user->setFlash('delt_success',Yii::t('delt', 'You are now allowed to manage the firm «{firm}».', array('{firm}'=>$model->name))); 
      }
      else
      {
        Yii::app()->user->setFlash('delt_success',Yii::t('delt', 'Something went wrong with the firm «{firm}».', array('{firm}'=>$model->name))); 
      }
    }

    if(isset($_GET['action']) && $_GET['action']=='decline')
    {
      if($fu = FirmUser::model()->findByAttributes(array('firm_id'=>$model->id, 'user_id'=>$this->DEUser->id, 'role'=>'I')))
      {
        $fu->delete();
        Yii::app()->user->setFlash('delt_success',Yii::t('delt', 'You successfully declined the invitation to manage the firm «{firm}».', array('{firm}'=>$model->name)));
      }
      else
      {
        Yii::app()->user->setFlash('delt_success',Yii::t('delt', 'Something went wrong with the firm «{firm}».', array('{firm}'=>$model->name))); 
      }
    }

    $this->redirect(array('bookkeeping/index'));

    
	}





	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
    $firm=$this->loadFirm($id);
    if($firm->softDelete())
    {
      Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'The firm has been correctly deleted.'));
      $this->redirect(array('/bookkeeping/index'));
    }
    else
    {
      Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'The firm could not be deleted.') . ' ' . Yii::app()->getUser()->getFlash('delt_failure'));
      $this->redirect(array('/bookkeeping/manage', 'slug'=>$firm->slug));
    }
	}

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
