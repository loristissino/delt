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
			if (isset($find)&&($find->status==User::STATUS_ACTIVE)) {
			    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Your account is active.")));
			} elseif(isset($find->activkey) && ($find->activkey==$activkey) && ($find->status!=User::STATUS_BANNED)) {
				$find->activkey = UserModule::createActiveKey($find->email); // we change the active key to a different value
        
        $newuser = $find->status==User::STATUS_NOACTIVE;
				$find->status = User::STATUS_ACTIVE;
				$find->save();
        
        Event::model()->log(DEUser::model()->getBy('email', $email), null, Event::USER_ACTIVATED_ACCOUNT);
        
        if($newuser)
        {
          UserModule::sendMail(
            $find->email,
            Yii::t('delt', Yii::app()->params['mail']['welcome']['subject']),
            Yii::t('delt', Yii::app()->params['mail']['welcome']['body'],
              array(
                '{name}'=>($find->profile && $find->profile->first_name ? $find->profile->first_name: $find->username),
                '{username}'=>$find->username,
                )
              )
            );
        }

			    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Your account has been activated. You can change your preferences editing your profile settings.")));
			} else {
			    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Incorrect activation URL.")));
			}
		} else {
			$this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Incorrect activation URL.")));
		}
	}

}
