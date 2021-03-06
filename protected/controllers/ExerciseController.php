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
      'postOnly + createtransactionsfrombenchmark', // we only allow creation of transactions from benchmark via POST request
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
        'actions'=>array('index', 'invite', 'view', 'solution', 'create', 'update', 'delete', 'report', 'export', 'import', 'createtransactionsfrombenchmark', 'sessions'),
        'users'=>array('@'),
      ),
      array('allow', // allow admin user to perform 'admin' and 'delete' actions
        'actions'=>array(),
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
    $model = $this->loadModel($id);
    $parent_firm = $model->firm->parent;
    Yii::app()->getUser()->setState('exercise_view', 'exercise/view');
    $this->render('view',array(
      'model'=>$model,
      'parent_firm'=>$parent_firm,
      'solution'=>false,
    ));
  }

  /**
   * Displays a particular model.
   * @param integer $id the ID of the model to be displayed
   */
  public function actionSolution($id)
  {
    $model = $this->loadModel($id);
    $parent_firm = $model->firm->parent;
    Yii::app()->getUser()->setState('exercise_view', 'exercise/solution');
    $this->render('view',array(
      'model'=>$model,
      'parent_firm'=>$parent_firm,
      'solution'=>true,
    ));
  }


  /**
   * Shows an exercise as a YAML file.
   * @param integer $id the ID of the exercise
   */
  public function actionExport($id, $wordwrap=60, $format='web')
  {
    if (!is_numeric($wordwrap) || $wordwrap<20 || $wordwrap>200)
    {
      $wordwrap=60;
    }
    
    $model = $this->loadModel($id);
    $model->createYaml($wordwrap);
    Event::model()->log($this->DEUser, null, Event::EXERCISE_EXPORTED, array('exercise_id'=>$model->id));
    if($format=='yaml')
    {
      $this->sendDispositionHeader(sprintf('%s_%s.yml', $model->slug, date('Ymd-His')));
      $this->serveYamlText($model->yaml);
    }
    $this->render('export',array(
      'model'=>$model,
      'wordwrap'=>$wordwrap,
    ));
  }

  /**
   * Shows the report of challenges of an exercise.
   * @param integer $id the ID of the exercise
   */
  public function actionReport($id, $session="")
  {
    $model = $this->loadModel($id, false);
    if ($session)
    {
      $challenges=$model->getChallenges($session, $this->DEUser->id);
      $sessions=false;
    }
    else
    {
      $challenges = false;
      $sessions=$model->getSessions($this->DEUser->id);
    }
    Event::model()->log($this->DEUser, null, Event::EXERCISE_REPORT, array('exercise_id'=>$model->id, 'session'=>$session));

    $this->render('report',array(
      'model'=>$model,
      'challenges'=>$challenges,
      'session'=>$session,
      'sessions'=>$sessions,
    ));
  }

  public function actionSessions()
  {
    $sessions_grouped = $this->DEUser->getSessionsGrouped();

    $this->render('sessions',array(
      'sessions_grouped'=>$sessions_grouped,
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
      $_POST['Exercise']['method'] = $this->_computeMethod($_POST['Exercise']);
      $model->attributes=$_POST['Exercise'];
      
      if($model->save())
      {
        $message = Yii::t('delt', 'The exercise has been successfully created.');
        Event::model()->log($this->DEUser, null, Event::EXERCISE_CREATED, array('exercise_id'=>$model->id, 'title'=>$model->title));

        $message .= $this->_manageChallenge($model, 'start', ' ');

        Yii::app()->getUser()->setFlash('delt_success', $message);

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
      $_POST['Exercise']['method'] = $this->_computeMethod($_POST['Exercise']);
      $model->attributes=$_POST['Exercise'];
      if($model->save())
      {
        Event::model()->log($this->DEUser, null, Event::EXERCISE_EDITED, array('exercise_id'=>$model->id, 'title'=>$model->title));

        if ($message = $this->_manageChallenge($model, 'resume', ''))
        {
          Yii::app()->getUser()->setFlash('delt_success', $message);
        }

        $this->redirect(array('view','id'=>$model->id));
      }
    }

    $this->render('update',array(
      'model'=>$model,
    ));
  }

  private function _manageChallenge(Exercise $model, $status, $initial_string='')
  {
    switch ($event = $model->setChallengeForInstructor('start'))
    {
      case Event::CHALLENGE_ACCEPTED:
        Event::model()->log($this->DEUser, $model->firm_id, $event);
        return $initial_string . Yii::t('delt', 'A new challenge has been created.');
      case Event::CHALLENGE_FIRM_CONNECTED:
        Event::model()->log($this->DEUser, $model->firm_id, $event);
        return $initial_string . Yii::t('delt', 'A different firm has been connected to an existing challenge.');
    }
  }

  public function actionImport($id)
  {
    $model=$this->loadModel($id);
    $string = DELT::getValueFromArray(DELT::getValueFromArray($_POST, 'ExerciseYamlForm', array()), 'content', '');
    
    $form = new ExerciseYamlForm;
    
    if(Yii::app()->request->getIsPostRequest())
    {
      if ($model->importFromYaml($string))
      {
        Yii::app()->user->setFlash('delt_success', 'The exercise has been successfully imported.');
        Event::model()->log($this->DEUser, null, Event::EXERCISE_IMPORTED, array('exercise_id'=>$model->id, 'title'=>$model->title));
        $this->redirect(array('view','id'=>$model->id));
      }
      else
      {
        Yii::app()->user->setFlash('delt_failure', 'The exercise could not be imported.');
      }
    }
    $form->content = $string;
    $this->render('import',array(
      'model'=>$model,
      'exerciseform'=>$form,
    ));
  }
  
  public function actionCreatetransactionsfrombenchmark($id)
  {
    $model=$this->loadModel($id);
    
      if ($model->createTransactionsFromBenchmark())
      {
        Yii::app()->user->setFlash('delt_success', 'The transactions have been successfully created.');
        Event::model()->log($this->DEUser, null, Event::EXERCISE_TRANSACTIONS, array('exercise_id'=>$model->id, 'benchmark'=>$model->firm_id));
      }
      else
      {
        Yii::app()->user->setFlash('delt_failure', 'The transactions could not be created.');
      }
      $this->redirect(array('view','id'=>$model->id));
  }

  /**
   * Invites users to start a challenge based on this exercise.
   * @param integer $id the ID of the model to be updated
   */
  public function actionInvite($slug)
  {
    $model=$this->loadModelBySlug($slug);

    if(Yii::app()->request->getIsPostRequest())
    {
      $users = array_values(array_filter(array_map("trim", explode("\n", DELT::getValueFromArray($_POST, 'users', '')))));
      
      $session = DELT::getValueFromArray($_POST, 'session', '');

      $method = $this->_computeMethod($_POST['Exercise']);
      
      $invited = $model->invite($this->DEUser->id, $users, $method, $session);
      if($invited)
      {
        Yii::app()->user->setFlash('delt_success', Yii::t('delt', 'Users invited: {number}.', array('{number}'=>$invited)));
        Event::model()->log($this->DEUser, null, Event::EXERCISE_USERS_INVITED, array('exercise_id'=>$model->id, 'session'=>$session, 'users'=>$users, 'method'=>$method));
      }
      else
      {
        Yii::app()->user->setFlash('delt_failure', 'No users have been invited.');
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
    $exercise = $this->loadModel($id);
    try {
      $exercise->delete();
      Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'The exercise has been successfully deleted.'));
      Event::model()->log($this->DEUser, null, Event::EXERCISE_DELETED, array('exercise_id'=>$id));
    }
    catch (Exception $e)
    {
      Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'The exercise could not be deleted.'));
      $this->redirect(array('exercise/view', 'id'=>$exercise->id));
    }

    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    if(!isset($_GET['ajax']))
      $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
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
  public function loadModel($id, $checkOwnership=true)
  {
    $model=Exercise::model()->findByPk($id);
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
    if($checkOwnership && $model->user_id!=$this->DEUser->id)
      throw new CHttpException(403, 'You are not allowed to access the requested page.');
    return $model;
  }

  public function loadModelBySlug($slug)
  {
    $model=Exercise::model()->findByAttributes(array('slug'=>$slug));
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
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
  
  private function _computeMethod($array)
  {
    return array_sum(
      array_map(
        function($a,$b) { return $a*$b; }, 
          $array['method_items'], 
          array_keys($array['method_items']
          )
        )
      );
  }

  
  
  
}
