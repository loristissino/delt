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
        $profile=Profile::model()->findByPK($user->id);
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
      $user->status=User::STATUS_BANNED;
      $user->save();
      Event::log($user->id, null, Event::USER_BANNED_BY_ADMINS);
      echo $username . " banned\n";
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
        if ($user->delete())
        {
            echo " deleted\n";
        }
        else
        {
            echo " NOT deleted\n";
        }
      }
    }
  }

}
