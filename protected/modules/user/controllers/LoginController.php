<?php

class LoginController extends Controller
{
	public $defaultAction = 'login';


// see https://github.com/SleepWalker/hoauth/wiki/%5Binstall%5D-hoauth-and-yii-user-extension

  public function actions()
  {
    return array(
      'oauth' => array(
        'class'=>'ext.hoauth.HOAuthAction',
      ),
      /*
      'oauthadmin' => array(
        'class'=>'ext.hoauth.HOAuthAdminAction',
      ),
      */
    );
  }
  
  public function hoauthCheckAccess($user)
  {
    // here we can decide whether to set status to active
    if($user->status == User::STATUS_NOACTIVE)
    {
      $user->status = User::STATUS_ACTIVE;
      $user->save();
    }
    return $user->status == User::STATUS_ACTIVE ? 1 : 0;
  }

  public function hoauthAfterLogin($user, $newUser)
  {
    Event::model()->log(DEUser::model()->getBy('username', $user->username), null, Event::USER_LOGGED_IN_SOCIAL);
    $this->_updateLastVisit();
    Yii::app()->user->returnUrl = $this->createUrl('/bookkeeping/index');
    //Yii::app()->end();
  }

	/**
	 * Displays the login page
	 */
	public function actionLogin($oauth=true)
	{
		if (Yii::app()->user->isGuest) {
			$model=new UserLogin;
			// collect user input data
			if(isset($_POST['UserLogin']))
			{
				$model->attributes=$_POST['UserLogin'];
				// validate user input and redirect to previous page if valid
				if($model->validate()) {
          $this->_updateLastVisit();
          
          Event::model()->log(DEUser::model()->getBy('username', $model->username), null, Event::USER_LOGGED_IN);
					$this->redirect(array('/bookkeeping/index')); 
          /*
          if (Yii::app()->user->returnUrl=='/index.php')
						$this->redirect(Yii::app()->controller->module->returnUrl);
					else
						$this->redirect(Yii::app()->user->returnUrl);
          */
				}
			}
			// display the login form
			$this->render('/user/login',array('model'=>$model, 'oauth'=>$oauth));
		} else
			$this->redirect(Yii::app()->controller->module->returnUrl);
	}
	
	private function _updateLastVisit() {
		$user = User::model()->notsafe()->findByPk(Yii::app()->user->id);
		$user->lastvisit = time();
		$user->save(false);
	}

}
