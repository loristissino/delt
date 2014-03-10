<?php
/**
 * EventController class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2014 Loris Tissino
 * @since 1.2.7
 * 
 */
/**
 * The BookkeepingController class.
 * 
 * @package application.controllers
 * @author Loris Tissino <loris.tissino@gmail.com>
 * 
 */
class EventController extends Controller
{
  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout='//layouts/column2';
  public $defaultAction = 'admin';

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
      array('allow', // allow admin user to perform 'admin' and 'delete' actions
        'actions'=>array('admin','delete','index','view'),
//        'users'=>array('admin'),
        'expression'=>'DEUser::model()->findByPK($user->id)->superuser==1',
        // we should use RBAC here... see http://www.yiiframework.com/doc/guide/1.1/en/topics.auth
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
    $model=new Event;

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(isset($_POST['Event']))
    {
      $model->attributes=$_POST['Event'];
      if($model->save())
        $this->redirect(array('view','id'=>$model->id));
    }

    $this->render('create',array(
      'model'=>$model,
    ));
  }

  /**
   * Lists all models.
   */
  public function actionIndex()
  {
    $dataProvider=new CActiveDataProvider('Event');
    $this->render('index',array(
      'dataProvider'=>$dataProvider,
    ));
  }

  /**
   * Manages all models.
   */
  public function actionAdmin()
  {
    $model=new Event('search');
    $model->unsetAttributes();  // clear any default values
    if(isset($_GET['Event']))
      $model->attributes=$_GET['Event'];

    $this->render('admin',array(
      'model'=>$model,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer $id the ID of the model to be loaded
   * @return Event the loaded model
   * @throws CHttpException
   */
  public function loadModel($id)
  {
    $model=Event::model()->findByPk($id);
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param Event $model the model to be validated
   */
  protected function performAjaxValidation($model)
  {
    if(isset($_POST['ajax']) && $_POST['ajax']==='event-form')
    {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
  
  public function renderUser(Event $event, $row)
  {
    return $this->renderPartial('_user', array('event'=>$event), true);
  }  
  
  public function renderFirmPage(Event $event, $row)
  {
    return $this->renderPartial('_firmpage', array('event'=>$event), true);
  }  
  
  public function renderAction(Event $event, $row)
  {
    return $this->renderPartial('_action', array('event'=>$event), true);
  }  

  public function renderAddress(Event $event, $row)
  {
    return $this->renderPartial('_address', array('event'=>$event), true);
  }  
  
  
}
