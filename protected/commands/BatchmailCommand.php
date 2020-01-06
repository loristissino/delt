<?php
require_once('modules/user/UserModule.php');

/**
 * BatchmailCommand class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.9.99m
 * 
 * @package application.commands
 * 
 */

/*
 *  run this command with something like:
 *  ./yiic batchmail
 * 
 * see http://www.yiiframework.com/doc/guide/1.1/en/topics.console
*/

class BatchmailCommand extends CConsoleCommand
{

  public function actionWarnUsersForStaleFirms($jsonfile)
  {
    $maildata = json_decode(file_get_contents($jsonfile));
    
    if (!is_object($maildata)) {
        echo "Not a valid file";
        return false;
    }
        
    foreach($maildata as $user=>$data) {
        echo "mailto: $user " . $data->email . "\n";
        
        $message = "Dear $user,
        
the following firms that you manage on the website LearnDoubleEntry.org have been marked stale and will be removed in 30 days if you do not access them:

";
        
        foreach ($data->firms as $firm) {
            $message.= "* " . $firm->firm . "\n"; // . "--> " . $firm->slug . "\n";
        }

$message.="\nBest regards,

     LearnDoubleEntry team.";
        
        UserModule::sendMail($data->email,"Warning: stale firms are going to be deleted",$message);
    }
    
  }


}
