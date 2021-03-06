<?php

class ProfileController extends Controller
{
	public $defaultAction = 'profile';
	public $layout='//layouts/column2';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;
	/**
	 * Shows a particular model.
	 */
	public function actionProfile()
	{
		$model = $this->loadUser();
	    $this->render('profile',array(
	    	'model'=>$model,
			'profile'=>$model->profile,
	    ));
	}


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionEdit()
	{
		$model = $this->loadUser();
		$profile=$model->profile;
    		
		// ajax validator
		if(isset($_POST['ajax']) && $_POST['ajax']==='profile-form')
		{
			echo UActiveForm::validate(array($model,$profile));
			Yii::app()->end();
		}
		
		if(isset($_POST['User']))
		{
      $model->current_email = $model->email;
			$model->attributes=$_POST['User'];
      $model->email = $_POST['User']['email'];  // this is not set as safe, so it wouldn't be assigned
			$profile->attributes=$_POST['Profile'];
      
			if($model->validate()&&$profile->validate()) {
        $email_changes = $model->checkEmailChanges($profile);
				$model->save();
				$profile->save();
        
        Event::model()->log($this->DEUser, null, Event::USER_EDITED_ACCOUNT, array(
          'user'=>array_diff_key(
            $model->attributes,
            array('id'=>true, 'create_at'=>true, 'lastvisit_at'=>true, 'password'=>true, 'activkey'=>true)
            ), 
          'profile'=>array_diff_key(
            $profile->attributes,
            array('terms'=>true, 'remote_addr'=>true, 'usercode'=>true)
            ),
        ));
        Yii::app()->user->updateSession();
				Yii::app()->user->setFlash('profileMessage',UserModule::t("Changes have been saved.") . ($email_changes? (' ' . UserModule::t("An email has been sent to you to verify the new email address.")): ''));
				$this->redirect(array('/user/profile'));
			} else $profile->validate();
		}

		$this->render('edit',array(
			'model'=>$model,
			'profile'=>$profile,
		));
	}
	
	/**
	 * Change password
	 */
	public function actionChangepassword() {
		$model = new UserChangePassword;

		if (Yii::app()->user->id) {
			
			// ajax validator
			if(isset($_POST['ajax']) && $_POST['ajax']==='changepassword-form')
			{
				echo UActiveForm::validate($model);
				Yii::app()->end();
			}
			
			if(isset($_POST['UserChangePassword'])) {

					$model->attributes=$_POST['UserChangePassword'];

					if($model->validate()) {
						//$new_password = User::model()->notsafe()->findbyPk(Yii::app()->user->id);
						$this->DEUser->password = UserModule::createPassword($model->password);
						$this->DEUser->activkey=md5((microtime().$model->password));
						$this->DEUser->save();
            Event::model()->log($this->DEUser, null, Event::USER_CHANGED_PASSWORD, null);
						Yii::app()->user->setFlash('profileMessage',UserModule::t("New password is saved."));
						$this->redirect(array("profile"));
					}
			}
			$this->render('changepassword',array('model'=>$model));
	    }
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadUser()
	{
		if($this->_model===null)
		{
			if(Yii::app()->user->id)
				$this->_model=Yii::app()->controller->module->user();
			if($this->_model===null)
				$this->redirect(Yii::app()->controller->module->loginUrl);
		}
		return $this->_model;
	}
}
