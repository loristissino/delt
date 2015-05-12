<?php
/**
 * ChallengeController class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2015 Loris Tissino
 * @since 1.8.1
 * 
 */
/**
 * The ChallengeController class.
 * 
 * @package application.controllers
 * 
 */

class ChallengeController extends Controller
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
      'postOnly + delete', // we only allow deletion via POST request,
      'postOnly + changestatus', // we only allow status change via POST request,
      // FIXME 'postOnly + connect', // we only allow connection change via POST request,
      'postOnly + activatetransaction', // we only allow transaction activation via POST request,
      'postOnly + checktransaction', // we only allow transaction activation via POST request,
      'postOnly + requesthint', // we only allow hint requests via POST request,
      'postOnly + requestshow', // we only allow hint requests via POST request,
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
      array('allow', 
        'actions'=>array('index','changestatus','connect', 'activatetransaction', 'requesthint', 'requesthelp', 'results'),
        'users'=>array('@'),
      ),
      array('allow', 
        'actions'=>array('create','delete'),
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
    $model=new Challenge;

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['Challenge']))
    {
      $model->attributes=$_POST['Challenge'];
      if($model->save())
        $this->redirect(array('view','id'=>$model->id));
    }

    $this->render('create',array(
      'model'=>$model,
    ));
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id)
  {
    $model=$this->loadModel($id);

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['Challenge']))
    {
      $model->attributes=$_POST['Challenge'];
      if($model->save())
        $this->redirect(array('view','id'=>$model->id));
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
  public function actionDelete($id)
  {
    $this->loadModel($id)->delete();

    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    if(!isset($_GET['ajax']))
      $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
  }

  /**
   * Changes the status of a challenge.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionChangestatus($id)
  {
    $model=$this->loadModel($id);
    
    $action = array_keys($_POST)[0];
    
    if($action=='check')
    {
      return $this->_check($model);
    }
    
    if ($model->changeStatus($action))
    {
      //Yii::app()->user->setFlash('delt_success',Yii::t('delt', 'Change successfully applied.'));
    }
    else
    {
      Yii::app()->user->setFlash('delt_failure',Yii::t('delt', 'Something went wrong with the requested change.'));
    }
    
    $this->redirect(array('challenge/index'));
  }
  

  public function actionResults($id)
  {
    $model=$this->loadModel($id);
    
    if(!$model->isChecked())
      throw new CHttpException(404,'The requested page does not exist.');
    
    return $this->_check($model);
  }
  
  
  /**
   * Connects a firm to the challenge.
   * @param integer $id the ID of the model to be updated
   * @param string $slug the slug of the firm to connect
   */
  public function actionConnect($id, $slug)
  {
    $model=$this->loadModel($id);
    
    $firm=$this->loadFirmBySlug($slug);
    $this->checkFrostiness($firm);
    
    if ($model->connect($firm))
    {
      //Yii::app()->user->setFlash('delt_success',Yii::t('delt', 'Change successfully applied.'));
    }
    else
    {
      Yii::app()->user->setFlash('delt_failure',Yii::t('delt', 'Something went wrong with the requested change.'));
    }
    
    $this->redirect(array('bookkeeping/manage', 'slug'=>$firm->slug));
  }
  
  public function actionActivatetransaction($id, $transaction)
  {
    $model=$this->loadModel($id);
    
    if ($model->method & Challenge::SHOW_CHECKS_ON_TRANSACTION_CHANGE)
    {
      $result = $model->check(false);
    }
    else
    {
      $result = array();
    }
    
    if ($model->activateTransaction($transaction))
    {
      Yii::app()->user->setState('transaction', $transaction);
      //Yii::app()->user->setFlash('delt_success',Yii::t('delt', 'Change successfully applied.'));
    }
    else
    {
      Yii::app()->user->setFlash('delt_failure',Yii::t('delt', 'Something went wrong with the requested change.'));
    }
    $this->renderPartial('_challenge', array('result'=>$result));
  }
  
  public function actionRequesthint($id, $transaction)
  {
    $model=$this->loadModel($id);
    if ($model->addHint($transaction))
    {
      //Yii::app()->user->setFlash('delt_success',Yii::t('delt', 'Change successfully applied.'));
    }
    else
    {
      Yii::app()->user->setFlash('delt_failure',Yii::t('delt', 'Something went wrong with the requested change.'));
    }
    $this->renderPartial('_challenge', array('result'=>array()));
  }
  
  public function actionRequesthelp($id, $transaction)
  {
    $model=$this->loadModel($id);
    if ($model->addShown($transaction))
    {
      //Yii::app()->user->setFlash('delt_success',Yii::t('delt', 'Change successfully applied.'));
    }
    else
    {
      Yii::app()->user->setFlash('delt_failure',Yii::t('delt', 'Something went wrong with the requested change.'));
    }
    
    $this->firm = $this->loadFirm($model->exercise->firm_id, false);
    
    $postings = Posting::model()->ofFirm($model->exercise->firm_id)->with('journalentry')->with('account')->connectedTo($transaction)->findAll();
    
    $this->renderPartial('_journalentries', array('postings'=>$postings, 'model'=>$this->firm));
  }

  public function actionChecktransaction($id, $transaction)
  {
    
    $this->renderPartial('_challenge', array('result'=>$result));
  }


  private function _check(Challenge $model)
  {
    if($model->isOpen())
      throw new CHttpException(404,'The requested page does not exist.');
    
    $results = $model->check();
    $this->render('results', array('model'=>$model, 'results'=>$results));
  }

  /**
   * Lists all models.
   */
  public function actionIndex()
  {
    $dataProvider=Challenge::model()->getChallengesForUser($this->DEUser->id);
    $this->render('index',array(
      'dataProvider'=>$dataProvider,
    ));
  }

  /**
   * Manages all models.
   */
  public function actionAdmin()
  {
    $model=new Challenge('search');
    $model->unsetAttributes();  // clear any default values
    if(isset($_GET['Challenge']))
      $model->attributes=$_GET['Challenge'];

    $this->render('admin',array(
      'model'=>$model,
    ));
  }
  

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Challenge the loaded model
   * @throws CHttpException
   */
  public function loadModel($id)
  {
    $model=Challenge::model()->findByPk($id);
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
    if($model->user_id!=$this->DEUser->id)
      throw new CHttpException(403, 'You are not allowed to access the requested page.');
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param Challenge $model the model to be validated
   */
  protected function performAjaxValidation($model)
  {
    if(isset($_POST['ajax']) && $_POST['ajax']==='challenge-form')
    {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
}
