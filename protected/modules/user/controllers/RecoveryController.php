<?php

class RecoveryController extends Controller
{
	public $defaultAction = 'recovery';
	
	/**
	 * Recovery password
	 */
	public function actionRecovery () {
		$form = new UserRecoveryForm;
		if (Yii::app()->user->id) {
		    	$this->redirect(Yii::app()->controller->module->returnUrl);
		    } else {
				$email = ((isset($_GET['email']))?$_GET['email']:'');
				$activkey = ((isset($_GET['activkey']))?$_GET['activkey']:'');
				if ($email&&$activkey) {
					$form2 = new UserChangePassword;
          $find = User::model()->notsafe()->findByAttributes(array('email'=>$email));
          if(isset($find)&&$find->activkey==$activkey) {
			    		if(isset($_POST['UserChangePassword'])) {
                $form2->attributes=$_POST['UserChangePassword'];
                if($form2->validate()) {
                  $find->password = Yii::app()->controller->module->createPassword($form2->password);
                  $find->activkey=Yii::app()->controller->module->createActiveKey($form2->password);
                  if ($find->status==0) {
                    $find->status = 1;
                  }
                  $find->save();
                  Event::model()->log(DEUser::model()->getBy('id', $find->id), null, Event::USER_PASSWORD_RECOVERED);
                  Yii::app()->user->setFlash('recoveryMessage',UserModule::t("New password is saved."));
                  $this->redirect(Yii::app()->controller->module->recoveryUrl);
                  }
                } 
						$this->render('changepassword',array('form'=>$form2));
		    		} else {
		    			Yii::app()->user->setFlash('recoveryMessage',UserModule::t("Incorrect recovery link."));
						$this->redirect(Yii::app()->controller->module->recoveryUrl);
		    		}
		    	} else {
			    	if(isset($_POST['UserRecoveryForm'])) {
			    		$form->attributes=$_POST['UserRecoveryForm'];
			    		if($form->validate()) {
			    			$user = User::model()->notsafe()->findbyPk($form->user_id);
                $user->activkey=UserModule::createActiveKey(microtime().$user->password);
                $user->save();
                
                $activation_url = 'http://' . $_SERVER['HTTP_HOST'].$this->createUrl(implode(Yii::app()->controller->module->recoveryUrl),array("activkey" => $user->activkey, "email" => $user->email));
                
                $subject = UserModule::t("Password recovery for {site_name}",
			    					array(
			    						'{site_name}'=>Yii::app()->name,
			    					));
			    			$message = UserModule::t("You have requested the password recovery for the site {site_name}.\r\n To set a new password, go to {activation_url}.",
			    					array(
			    						'{site_name}'=>Yii::app()->name,
			    						'{activation_url}'=>$activation_url,
			    					));
							
			    			UserModule::sendMail($user->email,$subject,$message);
                Event::model()->log(DEUser::model()->getBy('email', $user->email), null, Event::USER_SENT_RECOVERYLINK);
			    			
                Yii::app()->user->setFlash('recoveryMessage',UserModule::t("Please check your email. Instructions have been sent to your email address."));
                  $this->refresh();
			    		}
			    	}
		    		$this->render('recovery',array('form'=>$form));
		    	}
		    }
	}

	/**
	 * Resend activation link
	 */
	public function actionResend () {
		$form = new UserRecoveryForm;
		if (Yii::app()->user->id) {
		    	$this->redirect(Yii::app()->controller->module->returnUrl);
		    } else {
			    	if(isset($_POST['UserRecoveryForm'])) {
			    		$form->attributes=$_POST['UserRecoveryForm'];
			    		if($form->validate()) {
			    			$user = User::model()->notsafe()->findbyPk($form->user_id);
                
                if($user->status==User::STATUS_ACTIVE)
                {
                  Yii::app()->user->setFlash('resendMessage',UserModule::t("Your account is not waiting for activation."));
                  $this->refresh();
                }
                
                else
                {
                  $user->activkey=UserModule::createActiveKey(microtime().$user->password);
                  $user->save();
                  
                  $activation_url = $this->createAbsoluteUrl('/user/activation/activation',array("activkey" => $user->activkey, "email" => $user->email));
                  UserModule::sendMail(
                    $user->email,
                    Yii::t('delt', Yii::app()->params['mail']['resend']['subject']),
                    Yii::t('delt', Yii::app()->params['mail']['resend']['body'],
                      array(
                        '{activation_url}'=>$activation_url,
                        '{name}'=>$user->username
                        )
                      )
                    );
                  
                  Event::model()->log(DEUser::model()->getBy('id', $user->id), null, Event::USER_RESENT_ACTIVATIONLINK);
                  
                  Yii::app()->user->setFlash('resendMessage',UserModule::t("Please check your email. We resent the activation link to the address you specified."));
                  $this->refresh();
                  
                }
                
			    		}
			    	}
		    		$this->render('resend',array('form'=>$form));
		    	}
		    }

}
