<?php
/**
 * SectionController class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2018 Loris Tissino
 * @since 1.9.6
 * 
 */
/**
 * The SectionController class.
 * 
 * @package application.controllers
 * 
 */
class SectionController extends Controller
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
      array('allow', 
        'actions'=>array('view','create','update','delete','admin','index','togglevisibility'),
        'users'=>array('@'),
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
    $this->firm = $this->loadFirm($model->firm_id);
		$this->render('view',array(
			'model'=>$model,
      'firm'=>$this->firm,
      'postings'=>$model->getJournalentriesAsDataProvider(100000)->data,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate($slug)
	{
    $this->firm=$this->loadFirmBySlug($slug);
		$model=new Section;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Section']))
		{
			$model->attributes=$_POST['Section'];
      $model->firm_id = $this->firm->id;
			if($model->save())
				$this->redirect(array('admin','slug'=>$this->firm->slug));
		}
    
    $model->rank = Section::model()->maxRank($this->firm->id) + 1;

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
    $this->firm = $this->loadFirm($model->firm_id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Section']))
		{
			$model->attributes=$_POST['Section'];
			if($model->save())
				$this->redirect(array('section/admin','slug'=>$this->firm->slug));
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
    $model = $this->loadModel($id);
    $firm = $this->loadFirm($model->firm_id);
    
    try
    {
      $model->delete();
      Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'The section has been successfully deleted.'));
    }
    catch (Exception $e)
    {
      Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'The section could not be successfully deleted.'));
      die('flash set');
    }
    
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin', 'slug'=>$firm->slug));
	}

	/**
	 * Lists all models.
	 */
   /*
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Section');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}*/

	/**
	 * Manages all models.
	 */
   
	public function actionAdmin($slug)
	{
    $this->firm=$this->loadFirmBySlug($slug);
    $this->render('admin', array(
      'model'=>Section::model(),
      'dataProvider'=>$this->firm->getSectionsAsDataProvider(),
    ));
	}

  public function actionTogglevisibility($id)
  {
    $section=Section::model()->findByPK($id);
    $this->firm=$section->firm;
    $this->checkManageability($this->firm);
    $this->checkFrostiness($this->firm);

    $section->is_visible = !$section->is_visible;
    $section->save(false);

    $this->redirect(array('section/admin', 'slug'=>$this->firm->slug));
  }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Section the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Section::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

  public function renderIsVisible(Section $section, $row)
  {
    return $this->renderPartial('_is_visible',array('section'=>$section),true);
  }

	/**
	 * Performs the AJAX validation.
	 * @param Section $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='section-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
