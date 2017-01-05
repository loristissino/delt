<?php
/**
 * TransactionController class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.9
 * 
 */
/**
 * The TransactionController class.
 * 
 * @package application.controllers
 * @author Loris Tissino <loris.tissino@gmail.com>
 * 
 */
class TransactionController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
  
  public $exercise;

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
			array('allow',  // 
				'actions'=>array(),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update','delete'),
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
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($exercise_id)
	{
    $this->loadExercise($exercise_id);
		$model=new Transaction;

    $model->event_date = DELT::getDateForFormWidget(Yii::app()->getUser()->getState('lasttransactiondate', date('Y-m-d')));

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Transaction']))
		{
      $_POST['Transaction']['exercise_id']=$this->exercise->id;
			$model->attributes=$_POST['Transaction'];
			if($model->safeSave())
      {
        Yii::app()->getUser()->setState('lasttransactiondate', $model->event_date);
				$this->redirect(array('exercise/transactions','id'=>$this->exercise->id));
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
    
    $model->event_date = DELT::getDateForFormWidget($model->event_date);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Transaction']))
		{
			$model->attributes=$_POST['Transaction'];
			if($model->safeSave())
      {
        Yii::app()->getUser()->setState('lasttransactiondate', $model->event_date);
				$this->redirect(array('exercise/transactions','id'=>$model->exercise_id));
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
	public function actionDelete($id)
	{
    $transaction = $this->loadModel($id);
    try {
      $transaction->delete();
      Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'The transaction has been successfully deleted.'));
    }
    catch (Exception $e)
    {
      Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'The transaction could not be deleted.'));
    }

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('exercise/transactions', 'id'=>$this->exercise->id));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Transaction the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Transaction::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
    $this->loadExercise($model->exercise_id);
		return $model;
	}
  
  public function loadExercise($id)
  {
    $this->exercise = Exercise::model()->findByPk($id);
    if($this->exercise->user_id!==$this->DEUser->id)
      throw new CHttpException(403, 'You are not allowed to access the requested page.');
  }

	/**
	 * Performs the AJAX validation.
	 * @param Transaction $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='transaction-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
