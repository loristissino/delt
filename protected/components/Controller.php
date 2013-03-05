<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
  
  public $DEUser; //= DEUser::model()->findByPK(Yii::app()->user->id);
  
  protected function beforeAction($action)
  {
    $this->DEUser = DEUser::model()->findByPK(Yii::app()->user->id);
    return true;
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

	/**
	 * Returns the Firm object based on the id value given.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the id of the model to be loaded
	 * @return Firm the loaded model
	 * @throws CHttpException
	 */
	public function loadFirm($id)
	{
		$firm=Firm::model()->findByPk($id);
		if($firm===null)
			throw new CHttpException(404,'The requested page does not exist.');
    if(!$firm->isManageableBy($this->DEUser))
      throw new CHttpException(403, 'You are not allowed to access the requested page.');
		return $firm;
	}

	/**
	 * Returns the data model based on the slug value given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param string $slug the slug of the model to be loaded
	 * @return Firm the loaded model
	 * @throws CHttpException
	 */
	public function loadFirmBySlug($slug)
	{
		$model=Firm::model()->findByAttributes(array('slug'=>$slug));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
    $this->checkManageability($model);
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
  
}
