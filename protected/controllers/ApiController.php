<?php

/**
 * ApiController class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2018 Loris Tissino
 * @since 1.9.95
 */
/**
 * The AdminController class.
 * 
 * @package application.controllers
 * 
 */

class ApiController extends Controller
{
	public $apiuser = null;

	// Uncomment the following methods and override them if needed

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
        'actions'=>array('subscribe', 'unsubscribe', 'firms'),
        'users'=>array('@'),
	  ),
	  array('allow',
        'actions'=>array('firms'),
        'users'=>array('*'),
	  ),
      array('deny',  // deny all users
        'users'=>array('*'),
      ),
    );
  }

	public function actionSubscribe()
	{
		$this->apiuser = ApiUser::model()->findByPK($this->DEUser->id);	
		
		if (Yii::app()->getRequest()->isPostRequest)
		{
			if (!$this->apiuser)
			{
				$this->apiuser = new ApiUser();
				$this->apiuser->user_id = $this->DEUser->id;
			}

			$this->apiuser->apikey=strtoupper(substr(sha1($this->DEUser->id . time() . rand()), 0, 16));
			$this->apiuser->is_active = 1;
			try {
				$this->apiuser->save();
				Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'Your API key is "{key}".', array('{key}'=>$this->apiuser->apikey)));
				$this->redirect('subscribe');
			}
			catch (Exception $e)
			{
				Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'Your API key could not be activated.'));
			}
		}

		$this->render('subscribe');
	}

	public function actionUnsubscribe()
	{
		$this->apiuser = ApiUser::model()->findByPK($this->DEUser->id);
		if (!$this->apiuser)
		{
			throw new CHttpException(404,'The requested page does not exist.');
		}
		if (Yii::app()->getRequest()->isPostRequest)
		{
			$this->apiuser->apikey='';
			$this->apiuser->is_active = 0;
			try {
				$this->apiuser->save(false);
				Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'You have successfully unsubscribed from the API service.'));
			}
			catch (Exception $e)
			{
				Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'A problem occurred with your API key.'));
			}
		}
		$this->redirect('subscribe');
	}

	public function actionFirms($apikey)
	{
		$user = $this->loadUserByApiKey($apikey);
		$result = array();
		foreach ($user->firms as $firm)
		{
			$f = array();
			DELT::object2array($firm, $f, array('name', 'currency'));
			$result[$firm->slug][]=$f;
		}
		$this->serveJson($result);
	}

	public function actionFirm()
	{
		$this->render('firm');
	}






	public function actionAccount()
	{
		$this->render('account');
	}

	public function actionAccounts()
	{
		$this->render('accounts');
	}

	public function actionBalance()
	{
		$this->render('balance');
	}


	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionJournalentries()
	{
		$this->render('journalentries');
	}

	public function actionJournalentry()
	{
		$this->render('journalentry');
	}

	public function actionLedger()
	{
		$this->render('ledger');
	}

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @return DEUser the loaded model
   * @throws CHttpException
   */
  public function loadUserByApiKey($apikey)
  {
    $model=ApiUser::model()->getUserByApiKey($apikey);
    if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
    return $model;
  }

	
}