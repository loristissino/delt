<?php
/**
 * UsersCommand class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013 Loris Tissino
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
      echo implode("\t", array($user->id, $user->username, $user->status, $user->lastvisit_at)) . "\n";
    }
  }

}
