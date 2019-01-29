<?php

require('protected/modules/user/UserModule.php');
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

	private $_error = '';

	private $_privilegedKey = false;

	public $defaultAction = 'subscribe';

	// Uncomment the following methods and override them if needed

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
		'accessControl', // perform access control for CRUD operations
		//'postOnly + subscribe', // we only allow subscription via POST request
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
        'actions'=>array('subscribe', 'unsubscribe'),
        'users'=>array('@'),
	  ),
	  array('allow',
        'actions'=>array('user', 'firms', 'firm', 'accounts', 'account', 'journalentries', 'journalentry', 'sections', 'section'),
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

			$this->apiuser->apikey=strtoupper(substr(sha1($this->DEUser->id . time() . rand()), 0, 32));
			
			$this->apiuser->is_active = 1;
			try {
				$this->apiuser->save();
				Event::log($this->DEUser, null, Event::APIKEY_ENABLED);
				$this->_sendEmail();
				Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'Your API key has been successfully generated.'));
				$this->redirect('subscribe');
			}
			catch (Exception $e)
			{
				Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'Your API key could not be activated.'. $e->getMessage()));
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
				Event::log($this->DEUser, null, Event::APIKEY_DISABLED);
				Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'You have successfully unsubscribed from the API service.'));
			}
			catch (Exception $e)
			{
				Yii::app()->getUser()->setFlash('delt_failure', Yii::t('delt', 'A problem occurred with your API key.'));
			}
		}
		$this->redirect('subscribe');
	}

	public function actionUser($apikey='')
	{
		if (Yii::app()->getRequest()->requestType!='GET')
		{
			$this->_exitWithError(400, 'Bad Request', 'Only GET requests are allowed', 5);
		}
		$this->_loadUserByApiKey($apikey);
		if (!$this->_privilegedKey)
		{
			$this->_exitWithError(403, 'Forbidden', 'The provided key does not grant the needed privileges.', 2);
		}

		$result = array();
		DELT::object2array($this->DEUser, $result, array('username', 'email'));

		$profile=Profile::model()->findByPK($this->DEUser->id);
		DELT::object2array($profile, $result, array('first_name', 'last_name'));

		$result['api_uses']=$this->DEUser->apiuser->uses;

		Event::log($this->DEUser, null, Event::APIKEY_USED_USER);
		$this->serveJson($result);
	}

	public function actionFirms($apikey='')
	{
		if (Yii::app()->getRequest()->requestType!='GET')
		{
			$this->_exitWithError(400, 'Bad Request', 'Only GET requests are allowed');
		}

	    $this->_loadUserByApiKey($apikey);
		if (!$this->_privilegedKey)
		{
			$this->_exitWithError(403, 'Forbidden', 'The provided key does not grant the needed privileges.', 2);
		}
		
		$result = array();
		foreach ($this->DEUser->firms as $firm)
		{
			$f = array();
			DELT::object2array($firm, $f, array('slug', 'name', 'currency', 'frozen_at'));
			$f['url']=Yii::app()->getController()->createAbsoluteUrl('/api/firm/slug/' . $firm->slug);
			$result[]=$f;
		}
		Event::log($this->DEUser, null, Event::APIKEY_USED_FIRMS);
		$this->serveJson($result);
	}

	public function actionFirm($slug, $apikey='')
	{
		$this->_loadUserByApiKey($apikey);

		$this->_loadFirmBySlugAndRunChecks($slug);

		if (Yii::app()->getRequest()->isPutRequest)
		{
			if (!$this->_privilegedKey)
			{
				$this->_exitWithError(403, 'Forbidden', 'The provided key does not grant the needed privileges.', 2);
			}
			if ($this->firm->frozen_at)
			{
				$this->_exitWithError(409, 'Conflict', 'Firm frozen');
			}
		
			$fields = array();
			$values = CJSON::decode(file_get_contents("php://input"), true);

			if (!is_array($values))
			{
				$this->_exitWithError(400, 'Bad Request', 'Invalid data provided');
			}

			DELT::array2array($values, $fields, array('slug', 'name', 'description', 'currency'), true);
			DELT::array2object($fields, $this->firm, array_keys($fields));
			try {
				$this->firm->save(false);
				Event::log($this->DEUser, $this->firm->id, Event::APIKEY_USED_FIRM, $fields);
				$this->serveJson(array(
					'http_code'=>200,
					'status'=>'accepted',
					'firm_url'=>Yii::app()->getController()->createAbsoluteUrl('/api/firm/slug/' . $this->firm->slug),
				));
			}
			catch (Exception $e){
				$this->_exitWithError(400, 'Bad Request', 'Invalid data provided');
			}

		}
		if (Yii::app()->getRequest()->requestType!='GET')
		{
			$this->_exitWithError(400, 'Bad Request');
		}

		//$firm = $this->loadFirmBySlug($slug, false);
		$result = array();
		DELT::object2array($this->firm, $result, array('slug', 'name', 'description', 'currency', 'create_date', 'frozen_at'));
		$result['language']=$this->firm->language->getLocale();
		$result['owners']=$this->firm->getOwners(true);
		$result['parent_slug']=$this->firm->parent->slug;
		$result['url']=Yii::app()->getController()->createAbsoluteUrl('/api/firm/slug/' . $this->firm->slug);
		$result['accounts_url']=Yii::app()->getController()->createAbsoluteUrl('/api/accounts/slug/' . $this->firm->slug);
		$result['sections_url']=Yii::app()->getController()->createAbsoluteUrl('/api/sections/slug/' . $this->firm->slug);
		$result['journalentries_url']=Yii::app()->getController()->createAbsoluteUrl('/api/journalentries/slug/' . $this->firm->slug);
		Event::log($this->DEUser, $this->firm->id, Event::APIKEY_USED_FIRM);
		$this->serveJson($result);
	}

	public function actionAccounts($slug, $apikey='')
	{
		$this->_loadUserByApiKey($apikey);

		$this->_loadFirmBySlugAndRunChecks($slug);

		if (Yii::app()->getRequest()->requestType!='GET')
		{
			$this->_exitWithError(400, 'Bad Request');
		}

		$result = array();
		foreach ($this->firm->accounts as $account)
		{
			$v = array();
			DELT::object2array($account, $v, array('type', 'code', 'is_selectable', 'position', 'outstanding_balance', 'currentname', 'comment', 'classes'));
			$v['url']=Yii::app()->getController()->createAbsoluteUrl('/api/account/slug/' . $this->firm->slug . '/code/'. $account->code);
			$v['firm_url']=Yii::app()->getController()->createAbsoluteUrl('/api/firm/slug/' . $this->firm->slug);
			$result[]=$v;
		}

		Event::log($this->DEUser, $this->firm->id, Event::APIKEY_USED_ACCOUNTS);
		$this->serveJson($result);		

	}

	public function actionAccount($slug, $code, $apikey='')
	{
		$this->_loadUserByApiKey($apikey);
		$this->_loadFirmBySlugAndRunChecks($slug);

		if (Yii::app()->getRequest()->requestType=='PATCH')
		{
			if (!$this->_privilegedKey)
			{
				$this->_exitWithError(403, 'Forbidden', 'The provided key does not grant the needed privileges.', 2);
			}
			if ($this->firm->frozen_at)
			{
				$this->_exitWithError(409, 'Conflict', 'Firm frozen');
			}
		
			if ($this->_updateAccount($code))
			{
				$result=array(
					'status'=>'updated', 
					'code'=>$code,
					'url'=>Yii::app()->getController()->createAbsoluteUrl('/api/journalentry/slug/' . $this->firm->slug . '/code/'. $code)
				);
				Event::log($this->DEUser, $this->firm->id, Event::APIKEY_USED_ACCOUNT);
				$this->serveJson($result);	
			}
			else
			{
				$this->_exitWithError(400, 'Bad Request', $this->_error);
			}
		}

		$this->_exitWithError(501, 'Not Implemented');
	}

	public function actionBalance($slug, $apikey='')
	{
		$this->_exitWithError(501, 'Not Implemented');
	}

	public function actionSections($slug, $apikey='')
	{
		$this->_loadUserByApiKey($apikey);

		$this->_loadFirmBySlugAndRunChecks($slug);

		if (Yii::app()->getRequest()->requestType!='GET')
		{
			$this->_exitWithError(400, 'Bad Request');
		}

		$result = array();
		foreach ($this->firm->sections as $section)
		{
			$v=array();
			DELT::object2array($section, $v, array('id', 'name', 'is_visible', 'rank', 'color'));
			$v['url']=Yii::app()->getController()->createAbsoluteUrl('/api/section/slug/' . $this->firm->slug . '/id/' . $section->id);
			$v['firm_url']=Yii::app()->getController()->createAbsoluteUrl('/api/firm/slug/' . $this->firm->slug);
			$result[]=$v;
		}

		Event::log($this->DEUser, $this->firm->id, Event::APIKEY_USED_SECTIONS);
		$this->serveJson($result);		
	}

	public function actionJournalentries($slug, $apikey='')
	{
		$this->_loadUserByApiKey($apikey);

		$this->_loadFirmBySlugAndRunChecks($slug);

		if (Yii::app()->getRequest()->requestType!='GET')
		{
			$this->_exitWithError(400, 'Bad Request');
		}

		$result = array();
		foreach ($this->firm->journalentries as $je)
		{
			$result[]=$this->_journalentryToArray($je);
		}

		Event::log($this->DEUser, $this->firm->id, Event::APIKEY_USED_JOURNALENTRIES);
		$this->serveJson($result);		
	}

	public function actionJournalentry($slug, $id=null, $apikey='')
	{
		$this->_loadUserByApiKey($apikey);

		$this->_loadFirmBySlugAndRunChecks($slug);

		if (Yii::app()->getRequest()->isDeleteRequest)
		{
			if (!$this->_privilegedKey)
			{
				$this->_exitWithError(403, 'Forbidden', 'The provided key does not grant the needed privileges.', 2);
			}
			if ($this->firm->frozen_at)
			{
				$this->_exitWithError(409, 'Conflict', 'Firm frozen');
			}
		
			if (!$id)
			{
				$this->_exitWithError(400, 'Bad Request');
			}
			$je=Journalentry::model()->findByAttributes(array('id'=>$id, 'firm_id'=>$this->firm->id));
			if (!$je)
			{
				$this->_exitWithError(404, 'Not Found', 'Journal entry not found', 5);
			}

			if ($je->safeDelete())
			{
				$result=array('http_code'=>200, 'status'=>'deleted');
				Event::log($this->DEUser, $this->firm->id, Event::APIKEY_USED_JOURNALENTRY);
				$this->serveJson($result);	
			}
			else
			{
				$result=array('http_code'=>500, 'status'=>'failed');
				$this->serveJson($result);	
			}
		}

		if (Yii::app()->getRequest()->isPostRequest)
		{
			if (!$this->_privilegedKey)
			{
				$this->_exitWithError(403, 'Forbidden', 'The provided key does not grant the needed privileges.', 2);
			}
			if ($this->firm->frozen_at)
			{
				$this->_exitWithError(409, 'Conflict', 'Firm frozen');
			}
		
			$id = $this->_saveJournalEntry(); 
			if ($id)
			{
				header('HTTP/1.1 201 Created', true, 201);
				$result=array(
					'http_code'=>201,
					'status'=>'created', 
					'id'=>$id,
					'url'=>Yii::app()->getController()->createAbsoluteUrl('/api/journalentry/slug/' . $this->firm->slug . '/id/'. $id)
				);
				Event::log($this->DEUser, $this->firm->id, Event::APIKEY_USED_JOURNALENTRY);
				$this->serveJson($result);	
			}
			else
			{
				$this->_exitWithError(400, $this->_error);
			}

		}

		if (Yii::app()->getRequest()->requestType=='PATCH')
		{
			if (!$this->_privilegedKey)
			{
				$this->_exitWithError(403, 'Forbidden', 'The provided key does not grant the needed privileges.', 2);
			}
			if ($this->firm->frozen_at)
			{
				$this->_exitWithError(409, 'Conflict', 'Firm frozen');
			}
		
			if ($this->_updateJournalEntry($id))
			{
				$result=array(
					'status'=>'updated', 
					'id'=>$id,
					'url'=>Yii::app()->getController()->createAbsoluteUrl('/api/journalentry/slug/' . $this->firm->slug . '/id/'. $id)
				);
				Event::log($this->DEUser, $this->firm->id, Event::APIKEY_USED_JOURNALENTRY);
				$this->serveJson($result);	
			}
			else
			{
				$this->_exitWithError(400, 'Bad Request', $this->_error);
			}

		}


		if (Yii::app()->getRequest()->requestType!='GET')
		{
			$this->_exitWithError(400, 'Bad Request');
		}

		if (!$id)
		{
			$this->_exitWithError(400, 'Bad Request');
		}
		$je=Journalentry::model()->findByAttributes(array('id'=>$id, 'firm_id'=>$this->firm->id));
		if (!$je)
		{
			$this->_exitWithError(404, 'Not Found', 'Journal entry not found', 5);
		}

		Event::log($this->DEUser, $this->firm->id, Event::APIKEY_USED_JOURNALENTRY);
		$this->serveJson($this->_journalentryToArray($je));

	}

	public function actionLedger($slug, $apikey='')
	{
		$this->_exitWithError(501, 'Not Implemented');
	}

	public function actionSection($slug, $id, $apikey='')
	{
		$this->_exitWithError(501, 'Not Implemented');
	}


  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @return DEUser the loaded model
   * @throws CHttpException
   */
  private function _loadUserByApiKey($apikey)
  {
	// we use the Basic Authorization value from the headers of the request,
	// if present. Otherwise, the key provided in the URL.
	$apikey = DELT::getValueFromArray($_SERVER, 'PHP_AUTH_PW', $apikey);

    $this->DEUser=ApiUser::model()->getUserByApiKey($apikey, true);
    if($this->DEUser===null)
	  $this->_exitWithError(403, 'Forbidden', 'The provided API key is not valid', 5);
	  
	if(strlen($apikey)==32){
		$this->_privilegedKey = true;
	}
  }

  private function _exitWithError($http_code, $error_message, $explanation='', $sleep_time=0) 
  {
	sleep($sleep_time);
	if (!$explanation)
	{
		$explanation = $error_message;
	}
	header('HTTP/1.1 ' . $http_code . ' ' . $error_message, true, $http_code);
	$this->serveJson(array('http_code'=>$http_code, 'status'=>'failed', 'explanation'=> $explanation));
  }

  private function _loadFirmBySlugAndRunChecks($slug)
  {
	try {
		$this->firm = $this->loadFirmBySlug($slug, false);
	}
	catch (Exception $e) {
		$this->_exitWithError(404, 'Not Found', 'Firm not found', 5);
	}
	
	if (!$this->firm->isManageableBy($this->DEUser))
	{
		$this->_exitWithError(403, 'Forbidden', 'Firm not manageable');
	}
  }

  private function _updateJournalEntry($id)
  {
	$je=Journalentry::model()->findByAttributes(array('id'=>$id, 'firm_id'=>$this->firm->id));
	$values = CJSON::decode(file_get_contents("php://input"), true);

	if (!is_array($values))
	{
		$this->_exitWithError(400, 'Bad Request', 'Invalid data provided');
	}

	DELT::array2object($values, $je, array('date', 'description', 'is_closing', 'is_adjustment', 'is_included', 'section_id'));

	$section = Section::model()->findByAttributes(array('id'=>$je->section_id, 'firm_id'=>$this->firm->id));
		
	if(!$section)
	{
		$this->_exitWithError(400, 'Bad Request', 'Invalid section');
	}

	$je->is_visible = $section->is_visible;

	try
	{
		$je->save();
		return true;
	}
	catch (Exception $e)
	{
		return false;
	}
  }

  private function _updateAccount($code)
  {
	$account=Account::model()->findByAttributes(array('code'=>$code, 'firm_id'=>$this->firm->id));
	$values = CJSON::decode(file_get_contents("php://input"), true);

	if (!is_array($values))
	{
		$this->_exitWithError(400, 'Bad Request', 'Invalid data provided');
	}

	DELT::array2object($values, $account, array('comment'));

	try
	{
		$account->save();
		return true;
	}
	catch (Exception $e)
	{
		return false;
	}
  }

  private function _saveJournalEntry()
  {
	$transaction = $this->firm->getDbConnection()->beginTransaction();
	try {
		$values = CJSON::decode(file_get_contents("php://input"), true);
		if (!is_array($values) || !is_array($values['postings']) || sizeof($values['postings'])<2 )
		{
			$this->_exitWithError(400, 'Bad Request', 'Invalid data provided');
		}
		$je = new Journalentry();
		$je->firm_id = $this->firm->id;
		DELT::array2object($values, $je, array('firm_id', 'date', 'description', 'is_closing', 'is_adjustment', 'section_id'));

		if($je->section_id)
		{
			// if we are given a section, we try to use that one
			$section = Section::model()->findByAttributes(array('id'=>$je->section_id, 'firm_id'=>$this->firm->id));
		}
		else
		{
			// otherwise, we just use the section with the lowest rank
			$section = $this->firm->sections[0];
			$je->section_id = $section->id;
		}
		
		if(!$section)
		{
			$this->_error = 'Invalid section';
			$transaction->rollback();
			return false;
		}

		$je->is_visible = $section->is_visible;

		$je->rank = $je->getCurrentMaxRank() + 1;
		$je->save();

		// FIXME This could be optized with a query that extracts all the ids at once
		$totalAmount = 0;
		$postingsNo = 0;
		$rank = 0;

		foreach($values['postings'] as $p)
		{
			$amount = (float)DELT::getValueFromArray($p, 'amount', 0);

			$code = DELT::getValueFromArray($p, 'code', null);
			if (!$amount)
				continue;
			if ($account = $this->firm->findAccount($code, true, true))
			{
				$posting = new Posting();
				DELT::array2object($p, $posting, array('comment', 'subchoice'));
				$posting->journalentry_id = $je->id;
				$posting->amount = $amount;
				$posting->account_id = $account->id;
				$posting->rank = ++$rank;
				$posting->save();
				$totalAmount += $amount;
				$postingsNo++;
			}
			else
			{
				$this->_error = 'Invalid account: ' . $code;
				$transaction->rollback();
				return false;
			}
		}

		if ($postingsNo<2 || !DELT::nearlyZero($totalAmount))
		{
			$transaction->rollback();
			$this->_error = 'Unbalanced postings';
			return false;
		}
		else
		{
			$transaction->commit();
			return $je->id;
		}
		
	}
	catch (Exception $e)
	{
		$this->_error = $e->getMessage();
		$transaction->rollback();
		return false;
	}

	return false;
  }

  private function _journalentryToArray($je)
  {
	$v = array();
	DELT::object2array($je, $v, array('id', 'date', 'description', 'is_closing', 'is_adjustment', 'is_included', 'rank', 'section_id'));
	$v['postings']=array();
	foreach ($je->postings as $posting)
	{
		$p = array();
		$p['code']=$posting->account->code;
		DELT::object2array($posting, $p, array('amount', 'comment', 'subchoice'));
		$v['postings'][]=$p;
	}
	$v['url']=Yii::app()->getController()->createAbsoluteUrl('/api/journalentry/slug/' . $this->firm->slug . '/id/'. $je->id);
	$v['firm_url']=Yii::app()->getController()->createAbsoluteUrl('/api/firm/slug/' . $this->firm->slug);
	return $v;
  }

  public function beforeAction($action)
	{
		if (Yii::app()->params['apiEnforceHttps'] && !Yii::app()->getRequest()->getIsSecureConnection())
		{
			$this->_exitWithError(403, 'Forbidden', 'Requests accepted only on HTTPS');
		}
		return parent::beforeAction($action);
	}

  private function _sendEmail()
  {
	$subject = Yii::t('delt', 'Your API key is ready');
	$message = Yii::t('delt', "You have requested the activation of an API key on {site_name}.",
	array(
		'{site_name}'=>Yii::app()->name,
	));
	UserModule::sendMail($this->DEUser->email,$subject,$message);
  }
	
}