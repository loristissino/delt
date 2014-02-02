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

	/**
	 * Displays the login page
	 */
	public function actionLogin($oauth=false)
	{
		if (Yii::app()->user->isGuest) {
			$model=new UserLogin;
			// collect user input data
			if(isset($_POST['UserLogin']))
			{
				$model->attributes=$_POST['UserLogin'];
				// validate user input and redirect to previous page if valid
				if($model->validate()) {
          $this->lastViset();
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
	
	private function lastViset() {
		$lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
		$lastVisit->lastvisit = time();
		$lastVisit->save();
	}

}
