<?php
/**
 * UsersCommand class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.0
 * 
 * @package application.commands
 * 
 */

/*
 *  run this command with something like:
 *  ./yiic users
 * 
 * see http://www.yiiframework.com/doc/guide/1.1/en/topics.console
*/

// the following lines is required on tuxfamily because of the way files are organized there
require_once('modules/user/models/User.php');
require_once('modules/user/models/Profile.php');
require_once('modules/user/UserModule.php');

class UsersCommand extends CConsoleCommand
{
  
  public function actionIndex()
  {
    $users=DEUser::model()->findAll();
    
    $commands=array();
    
    foreach($users as $user)
    {
      echo implode("\t", array($user->id, $user->username, $user->status, $user->create_at, $user->lastvisit_at, $user->email)) . "\n";
    }
  }
  
  public function actionEmails()
  {
    $users=DEUser::model()->findAll();
    foreach($users as $user)
    {
      if ($user->status>0)
      {
        $profile=$this->_getProfile($user->id);
        $name = $profile->getFullName();
        if ($name=='[Incognito user]')
        {
          $name = $user->username;
        }
        $dear = $profile->first_name;
        if (!$dear)
        {
          $dear = $user->username;
        }
        
        echo implode("\t", array($user->id, $user->username, $name, $profile->language, $dear, $user->email)) . "\n";
      }
    }
  }
  

  public function actionMarkBanned($username)
  {
    $user = DEUser::model()->findByAttributes(array('username'=>$username));
    if ($user)
    {
        $this->_banUser($user);
    }
  }

  public function actionDeleteUnconfirmed()
  {
    $date = date('Y-m-d', time()-7*24*60*60); // we give users a week to confirm the registration
      
    $users=DEUser::model()->findAllByAttributes(array('status'=>0));
    foreach($users as $user)
    {
      if ($user->create_at < $date)
      {
        echo $user->username;
        try {
            $user->delete();
            echo " deleted\n";
        }
        catch (Exception $e) {
            echo " NOT deleted\n";
        }
      }
    }
  }
  
  public function actionFindFormMessageSenders($ban=0)
  {
      // we find users that sent a message with the contact form without having prepared a journal entry
      $users=DEUser::model()->findAll();
      foreach($users as $user)
      {
          if (sizeof(Event::model()->findAllByAttributes(array('user_id'=>$user->id, 'action'=>Event::SITE_CONTACT_FORM)))>0
            and
          sizeof(Event::model()->findAllByAttributes(array('user_id'=>$user->id, 'action'=>Event::FIRM_JOURNALENTRY_CREATED)))==0
          ) {
              echo sprintf("%d: %s\n", $user->id, $user->username);
              if ($ban==1) {
                $this->_banUser($user);
              }
          }
      }
  }
  
  public function actionCreateUser($username, $email, $password, $firstname="", $lastname="", $school=""){
      $user = new DEUser();
      $user->username=$username;
      $user->email=$email;
      $user->password=UserModule::createPassword($password);
      $user->status = User::STATUS_ACTIVE;
      $user->create_at = new CDbExpression('NOW()');
      $user->superuser=0;
      
      $us = $user->save();
      $profile = new Profile();
      $profile->user_id = $user->id;
      $profile->attributes = array(
        'first_name' => $firstname,
        'last_name' => $lastname,
        'school' => $school,
        'terms' => true,
        //'language'=>'en',
        'allowed_firms'=>20,
      );
      $ps = $profile->save(false);
      if ($us and $ps) {
          Event::model()->log($user, null, Event::USER_CREATED_BY_ADMIN, array(
            'user'=>array_diff_key(
              $user->attributes,
              array('id'=>true, 'create_at'=>true, 'lastvisit_at'=>true, 'password'=>true, 'activkey'=>true)
              ), 
            'profile'=>array_diff_key(
              $profile->attributes,
              array('terms'=>true, 'remote_addr'=>true, 'usercode'=>true)
              ),
          ));      
         echo sprintf("created: %d\n", $user->id);
      } 
      else {
          echo "not created\n";
      }
  }

  public function actionFindApiUsers()
  {
      $users=DEUser::model()->findAll();
      foreach($users as $user)
      {
          if (sizeof(Event::model()->findAllByAttributes(array('user_id'=>$user->id, 'action'=>Event::APIKEY_ENABLED)))>0) {
              $profile = $this->_getProfile($user);
              echo sprintf("%d: %s (%s)\n", $user->id, $user->username, $profile->getFullName());
          }
      }
  }
  
  protected function _banUser($user)
  {
      $user->status=User::STATUS_BANNED;
      $user->save();
      Event::log($user, null, Event::USER_BANNED_BY_ADMINS);
      echo $user->username . " banned\n";
  }
  
  protected function _getProfile($user)
  {
      return Profile::model()->findByPK($user->id);
  }

}
