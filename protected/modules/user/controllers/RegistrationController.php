<?php

class RegistrationController extends Controller
{
	public $defaultAction = 'registration';
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
		);
	}
	/**
	 * Registration user
	 */
	public function actionRegistration() {
            $model = new RegistrationForm;
            $profile=new Profile;
            $profile->regMode = true;
            
			// ajax validator
			if(isset($_POST['ajax']) && $_POST['ajax']==='registration-form')
			{
				echo UActiveForm::validate(array($model,$profile));
				Yii::app()->end();
			}
			
		    if (Yii::app()->user->id) {
		    	$this->redirect(Yii::app()->controller->module->profileUrl);
		    } else {
		    	if(isset($_POST['RegistrationForm'])) {
					$model->attributes=$_POST['RegistrationForm'];
					$profile->attributes=((isset($_POST['Profile'])?$_POST['Profile']:array()));
					if($model->validate()&&$profile->validate())
					{
						$sourcePassword = $model->password;
						$model->activkey=UserModule::createActiveKey($model->password);
						$model->password=UserModule::createPassword($model->password);
						$model->verifyPassword=$model->password;
						$model->superuser=0;
						$model->status=((Yii::app()->controller->module->activeAfterRegister)?User::STATUS_ACTIVE:User::STATUS_NOACTIVE);
						
						if ($model->save()) {
							$profile->user_id=$model->id;
							$profile->save();
              
              Event::model()->log(DEUser::model()->getBy('username', $model->username), null, Event::USER_SIGNED_UP, array(
                'user'=>array_diff_key(
                  $model->attributes,
                  array('id'=>true, 'create_at'=>true, 'lastvisit_at'=>true, 'password'=>true, 'activkey'=>true)
                  ), 
                'profile'=>array_diff_key(
                  $profile->attributes,
                  array('terms'=>true, 'remote_addr'=>true, 'usercode'=>true)
                  ),
              ));

              
							if (Yii::app()->controller->module->sendActivationMail) {
                $model->sendActivationMail($this, $profile);
                /*
								$activation_url = $this->createAbsoluteUrl('/user/activation/activation',array("activkey" => $model->activkey, "email" => $model->email));
								UserModule::sendMail(
                  $model->email,
                  Yii::t('delt', Yii::app()->params['mail']['verify']['subject']),
                  Yii::t('delt', Yii::app()->params['mail']['verify']['body'],
                    array(
                      '{activation_url}'=>$activation_url,
                      '{name}'=>$profile->first_name ? $profile->first_name : $model->username
                      )
                    )
                  );*/
							}
							
							if ((Yii::app()->controller->module->loginNotActiv||(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false))&&Yii::app()->controller->module->autoLogin) {
									$identity=new UserIdentity($model->username,$sourcePassword);
									$identity->authenticate();
									Yii::app()->user->login($identity,0);
									$this->redirect(Yii::app()->controller->module->returnUrl);
							} else {
								if (!Yii::app()->controller->module->activeAfterRegister&&!Yii::app()->controller->module->sendActivationMail) {
									Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Contact Admin to activate your account."));
								} elseif(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false) {
									Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please {{login}}.",array('{{login}}'=>CHtml::link(UserModule::t('Login'),Yii::app()->controller->module->loginUrl))));
								} elseif(Yii::app()->controller->module->loginNotActiv) {
									Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please check your email or login."));
								} else {
									Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please check your email and follow the instructions there (be sure to check also your spam folder, because sometimes the message is put there)."));
								}
								$this->refresh();
							}
						}
					} else $profile->validate();
				}
			    $this->render('/user/registration',array('model'=>$model,'profile'=>$profile));
		    }
	}
}
