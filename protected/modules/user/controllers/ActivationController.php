<?php

class ActivationController extends Controller
{
	public $defaultAction = 'activation';

	
	/**
	 * Activation user account
	 */
	public function actionActivation () {
		$email = $_GET['email'];
		$activkey = $_GET['activkey'];
		if ($email&&$activkey) {
			$find = User::model()->notsafe()->findByAttributes(array('email'=>$email));
			if (isset($find)&&$find->status) {
			    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Your account is active.")));
			} elseif(isset($find->activkey) && ($find->activkey==$activkey)) {
				$find->activkey = UserModule::encrypting(microtime());
				$find->status = 1;
				$find->save();
        
        UserModule::sendMail(
          $find->email,
          Yii::t('delt', Yii::app()->params['mail']['welcome']['subject']),
          Yii::t('delt', Yii::app()->params['mail']['welcome']['body'],
            array(
              '{activation_url}'=>$activation_url,
              '{name}'=>$find->username
              )
            )
          );

			    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Your account has been activated. You can change your preferences editing your profile settings.")));
			} else {
			    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Incorrect activation URL.")));
			}
		} else {
			$this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Incorrect activation URL.")));
		}
	}

}
