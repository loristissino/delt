<?php
/**
 * UserModule class file.
 *
 * @author Mikhail Mangushev <mishamx@gmail.com> 
 * @link http://yii-user.2mx.org/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */

/**
 * Yii-User module
 * 
 * @author Mikhail Mangushev <mishamx@gmail.com> 
 * @link http://yii-user.2mx.org/
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @author Loris Tissino <loris.tissino@gmail.com> (adaptation to DELT)
 * @package application.modules.user
 * 
 */

class UserModule extends CWebModule
{
	/**
	 * @var int
	 * @desc items on page
	 */
	public $user_page_size = 10;
	
	/**
	 * @var int
	 * @desc items on page
	 */
	public $fields_page_size = 10;
	
	/**
	 * @var string
	 * @desc hash method (md5,sha1 or algo hash function http://www.php.net/manual/en/function.hash.php)
	 */
	public $hash='md5';
  
	/**
	 * @var string
	 * @desc cost parameter for BlowFish encrypter
	 */
  public $cost='04';
	
	/**
	 * @var boolean
	 * @desc use email for activation user account
	 */
	public $sendActivationMail=true;
	
	/**
	 * @var boolean
	 * @desc allow auth for is not active user
	 */
	public $loginNotActiv=false;
	
	/**
	 * @var boolean
	 * @desc activate user on registration (only $sendActivationMail = false)
	 */
	public $activeAfterRegister=false;
	
	/**
	 * @var boolean
	 * @desc login after registration (need loginNotActiv or activeAfterRegister = true)
	 */
	public $autoLogin=true;
	
	public $registrationUrl = array("/user/registration");
	public $recoveryUrl = array("/user/recovery/recovery");
	public $resendactivationUrl = array("/user/recovery/resend");
	public $loginUrl = array("/user/login");
	public $logoutUrl = array("/user/logout");
	public $profileUrl = array("/user/profile");
	public $returnUrl = array("/bookkeeping/index");
	public $returnLogoutUrl = array("/user/login");
	
	
	/**
	 * @var int
	 * @desc Remember Me Time (seconds), defalt = 2592000 (30 days)
	 */
	public $rememberMeTime = 2592000; // 30 days
	
	public $fieldsMessage = '';
	
	/**
	 * @var array
	 * @desc User model relation from other models
	 * @see http://www.yiiframework.com/doc/guide/database.arr
	 */
	public $relations = array();
	
	/**
	 * @var array
	 * @desc Profile model relation from other models
	 */
	public $profileRelations = array();
	
	/**
	 * @var boolean
	 */
	public $captcha = array('registration'=>true);
	
	/**
	 * @var boolean
	 */
	//public $cacheEnable = false;
	
	public $tableUsers = '{{users}}';
	public $tableProfiles = '{{profiles}}';
	public $tableProfileFields = '{{profiles_fields}}';

    public $defaultScope = array(
            'with'=>array('profile'),
    );
	
	static private $_user;
	static private $_users=array();
	static private $_userByName=array();
	static private $_admin;
	static private $_admins;
	
	/**
	 * @var array
	 * @desc Behaviors for models
	 */
	public $componentBehaviors=array();
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'user.models.*',
			'user.components.*',
		));
	}
	
	public function getBehaviorsFor($componentName){
        if (isset($this->componentBehaviors[$componentName])) {
            return $this->componentBehaviors[$componentName];
        } else {
            return array();
        }
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
	
	/**
	 * @param $str
	 * @param $params
	 * @param $dic
	 * @return string
	 */
	public static function t($str='',$params=array(),$dic='user') {
		if (Yii::t("UserModule", $str)==$str)
		    return Yii::t("UserModule.".$dic, $str, $params);
        else
            return Yii::t("UserModule", $str, $params);
	}
	
	/**
	 * @return hash string.
	 */
  /*
	public static function encrypting($string="") {
		$hash = Yii::app()->getModule('user')->hash;
		if ($hash=="md5")
			return md5($string);
		if ($hash=="sha1")
			return sha1($string);
		else
			return hash($hash,$string);
	}
  */

	/**
	 * @return boolean true if the password is valid
	 */
	public static function validatePassword($password_given, $password_stored) 
  {
    if(strlen($password_stored)==32)
    { 
      // for old passwords, stored as md5 hashes
      return md5($password_given)==$password_stored;
    }
    else
    {
      return crypt($password_given, $password_stored)==$password_stored;
    }
	}

	/**
	 * @return string a BlowFish password string with random salt, to be stored in the db
	 */
	public static function createPassword($password_given) 
  {
    
    $cost = Yii::app()->getModule('user')->cost;    
    
    $salt = '$2a$' . $cost . '$';
    
    $r = openssl_random_pseudo_bytes(22);
    
    for($i=0; $i<22; $i++)
    {
      $salt.=substr('./0123456789ABCDEFGHIJKLMNOPQRSTUWWXYZabcdefghijklmnopqrstuvwxyz', floor(hexdec(bin2hex($r[$i]))/4), 1);
    }
    
    return crypt($password_given, $salt);
	}

	/**
	 * @return string a string to be used as validation key for email validation
	 */
	public static function createActiveKey($string) 
  {
    return md5(rand(0,100000).microtime().$string);
	}

	
	/**
	 * @param $place
	 * @return boolean 
	 */
	public static function doCaptcha($place = '') {
		if(!extension_loaded('gd'))
			return false;
		if (in_array($place, Yii::app()->getModule('user')->captcha))
			return Yii::app()->getModule('user')->captcha[$place];
		return false;
	}
	
	/**
	 * Return admin status.
	 * @return boolean
	 */
	public static function isAdmin() {
		if(Yii::app()->user->isGuest)
			return false;
		else {
			if (!isset(self::$_admin)) {
				if(self::user()->superuser)
					self::$_admin = true;
				else
					self::$_admin = false;	
			}
			return self::$_admin;
		}
	}

	/**
	 * Return admins.
	 * @return array syperusers names
	 */	
	public static function getAdmins() {
		if (!self::$_admins) {
			$admins = User::model()->active()->superuser()->findAll();
			$return_name = array();
			foreach ($admins as $admin)
				array_push($return_name,$admin->username);
			self::$_admins = ($return_name)?$return_name:array('');
		}
		return self::$_admins;
	}
	
	/**
	 * Send mail method
	 */
	public static function sendMail($email,$subject,$message) {
        /*
    	$adminEmail = Yii::app()->params['adminEmail'];
	    $headers = "MIME-Version: 1.0\r\nFrom: $adminEmail\r\nReply-To: $adminEmail\r\nContent-Type: text/html; charset=utf-8";
      
      if(Yii::app()->params['mail']['bcc_admin'])
      {
        $headers .= "\r\nBcc: $adminEmail";
      }
      
	    $message = wordwrap($message, 70);
	    $message = str_replace("\n.", "\n..", $message);
      $message = str_replace("\n", "\r\n", $message);
      
      return mail($email,'=?UTF-8?B?'.base64_encode($subject).'?=',$message,$headers);
      
       */
       
       $apikey = Yii::app()->params['emailApikey'];
       $emailService = Yii::app()->params['emailService'];
              
       $message = wordwrap($message, 70);
       $message = str_replace("\n.", "\n..", $message);
       $message = str_replace("\n", "\r\n", $message);
       
       $data = array(
            'apikey'=>$apikey,
            'recipientName'=>'',
            'recipientEmail'=>$email,
            'subject'=>'=?UTF-8?B?'.base64_encode($subject).'?=',
            'body'=>$message,
       );
       
       $payload = json_encode($data);
         
       // Prepare new cURL resource
       $ch = curl_init($emailService);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLINFO_HEADER_OUT, true);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
         
        // Set HTTP Header for POST request 
       curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload))
       );
         
       // Submit the POST request
       $result = curl_exec($ch);
       
       // Close cURL session handle
       curl_close($ch);
       
       $r=json_decode($result);
       return $r->status=='OK';
              
    }

	/**
	 * Return safe user data.
	 * @param user id not required
	 * @return user object or false
	 */
	public static function user($id=0,$clearCache=false) {
        if (!$id&&!Yii::app()->user->isGuest)
            $id = Yii::app()->user->id;
		if ($id) {
            if (!isset(self::$_users[$id])||$clearCache)
                self::$_users[$id] = User::model()->with(array('profile'))->findbyPk($id);
			return self::$_users[$id];
        } else return false;
	}
	
	/**
	 * Return safe user data.
	 * @param user name
	 * @return user object or false
	 */
	public static function getUserByName($username) {
		if (!isset(self::$_userByName[$username])) {
			$_userByName[$username] = User::model()->findByAttributes(array('username'=>$username));
		}
		return $_userByName[$username];
	}
	
	/**
	 * Return safe user data.
	 * @param user id not required
	 * @return user object or false
	 */
	public function users() {
		return User;
	}
  
  public static function encrypt($string)
  {
    $key = Yii::app()->params['key'];
    return str_replace(array('+', '/', '='), array('.', '_', '-'), base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(sha1($key)))));
  }

  public static function decrypt($string)
  {
    $key = Yii::app()->params['key'];
    return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), 
    base64_decode(str_replace(array('.', '_', '-'), array('+', '/', '='), $string)), MCRYPT_MODE_CBC, md5(sha1($key))), "\0");
  }
  
}
