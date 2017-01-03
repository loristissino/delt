<?php

/**
 * ExerciseController class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2015-2017 Loris Tissino
 * @since 1.8.3
 * 
 */
/**
 * The ExerciseController class.
 * 
 * @package application.controllers
 * 
 */

class ExerciseController extends Controller
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
    
      array('allow', // allow authenticated user to perform 'create' and 'update' actions
        'actions'=>array('index', 'invite', 'view', 'create', 'update', 'report', 'transactions'),
        'users'=>array('@'),
      ),
      array('allow', // allow admin user to perform 'admin' and 'delete' actions
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
   * Shows the report of challenges of an exercise.
   * @param integer $id the ID of the exercise
   */
  public function actionReport($id)
  {
    $this->render('report',array(
      'model'=>$this->loadModel($id),
    ));
  }


  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate()
  {
    $model=new Exercise;

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['Exercise']))
    {
      $_POST['Exercise']['user_id']=$this->DEUser->id;
      $model->attributes=$_POST['Exercise'];
      
      if($model->save())
      {
        Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'The exercise has been successfully created.'));
        $this->redirect(array('view','id'=>$model->id));
      }
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

    if(Yii::app()->request->getIsPostRequest())
    {
      $model->attributes=$_POST['Exercise'];
      if($model->save())
        $this->redirect(array('view','id'=>$model->id));
    }

    $this->render('update',array(
      'model'=>$model,
    ));
  }

  /**
   * Manages transactions belonging to this exercise.
   * @param integer $id the ID of the exercise
   */
  public function actionTransactions($id)
  {
    $model=$this->loadModel($id);

    if(Yii::app()->request->getIsPostRequest())
    {
      $users = explode("\n", DELT::getValueFromArray($_POST, 'users', ''));
      $method = DELT::getValueFromArray($_POST, 'method', 61);
      
      $invited = $model->invite($users, $method);
      if($invited)
      {
        Yii::app()->user->setFlash('delt_success', 'Invited: '. $invited);
      }
      else
      {
        Yii::app()->user->setFlash('delt_failure', 'No user invited');
      }
      $this->redirect(array('index'));
    }

    $this->render('transactions',array(
      'model'=>$model,
    ));
  }


  /**
   * Invites users to start a challenge based on this exercise.
   * @param integer $id the ID of the model to be updated
   */
  public function actionInvite($id)
  {
    $model=$this->loadModel($id);

    if(Yii::app()->request->getIsPostRequest())
    {
      $users = explode("\n", DELT::getValueFromArray($_POST, 'users', ''));
      $method = DELT::getValueFromArray($_POST, 'method', 61);
      
      $invited = $model->invite($users, $method);
      if($invited)
      {
        Yii::app()->user->setFlash('delt_success', 'Invited: '. $invited);
      }
      else
      {
        Yii::app()->user->setFlash('delt_failure', 'No user invited');
      }
      $this->redirect(array('index'));
    }

    $this->render('invite',array(
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
   * Lists all models.
   */
  public function actionIndex()
  {
    $dataProvider=Exercise::model()->getExercisesOfUser($this->DEUser->id);
    $this->render('index',array(
      'dataProvider'=>$dataProvider,
    ));
  }

  /**
   * Manages all models.
   */
  public function actionAdmin()
  {
    $model=new Exercise('search');
    $model->unsetAttributes();  // clear any default values
    if(isset($_GET['Exercise']))
      $model->attributes=$_GET['Exercise'];

    $this->render('admin',array(
      'model'=>$model,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Exercise the loaded model
   * @throws CHttpException
   */
  public function loadModel($id)
  {
    $model=Exercise::model()->findByPk($id);
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
    if($model->user_id!=$this->DEUser->id)
      throw new CHttpException(403, 'You are not allowed to access the requested page.');
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param Exercise $model the model to be validated
   */
  protected function performAjaxValidation($model)
  {
    if(isset($_POST['ajax']) && $_POST['ajax']==='exercise-form')
    {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
}
