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
    foreach($users as $user)
    {
      echo implode("\t", array($user->id, $user->username, $user->status, $user->create_at, $user->lastvisit_at)) . "\n";
    }
  }

  public function actionDeleteUnconfirmed()
  {
    $date = date('Y-m-d', time()-7*24*60*60); // we give users a week to confirm the registration
      
    $users=User::model()->findAllByAttributes(array('status'=>0));
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
