<?php

class ActivationController extends Controller
{
	public $defaultAction = 'activation';

	
	/**
	 * Activation user account
	 */
	public function actionChangeaddress () {
		$change = $_GET['change'];
    
    $info = UserModule::decrypt($change);
    $info = json_decode($info);
    if(is_object($info))
    {
      if($info->expiration > time())
      {
        if($user = User::model()->findByPK($info->user_id))
        {
          if($user->status == User::STATUS_ACTIVE  || $user->status == User::STATUS_WAITING)
          {
            $user->email = $info->email;
            $user->save();
            Event::model()->log(DEUser::model()->getBy('email', $user->email), null, Event::USER_CHANGED_EMAIL);
            $this->render('/user/message',array('title'=>UserModule::t("User email change"),'content'=>UserModule::t("Your new email address has been recorded.")));
          }
          else
          {
            $this->render('/user/message',array('title'=>UserModule::t("User email change"),'content'=>UserModule::t("Your user account is not active.")));
          }
        }
      }
      else
      {
			    $this->render('/user/message',array('title'=>UserModule::t("User email change"),'content'=>UserModule::t("Sorry, this link has expired.")));
      }
    }
    else
    {
			$this->render('/user/message',array('title'=>UserModule::t("User email change"),'content'=>UserModule::t("Incorrect URL.")));
    }
	}

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
