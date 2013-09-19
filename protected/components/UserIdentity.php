<?php
/**
 * UserIdentity class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013 Loris Tissino
 * @since 1.0
 */
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 *
 * @package application.components
 * 
 */
class UserIdentity extends CUserIdentity
{
  /**
   * Authenticates a user.
   * @return boolean whether authentication succeeds.
   */
  public function authenticate()
  {
    $users=array(
      // username => password
      'demo'=>'demo',
      'admin'=>'admin',
    );
    if(!isset($users[$this->username]))
      $this->errorCode=self::ERROR_USERNAME_INVALID;
    elseif($users[$this->username]!==$this->password)
      $this->errorCode=self::ERROR_PASSWORD_INVALID;
    else
      $this->errorCode=self::ERROR_NONE;
    return !$this->errorCode;
  }
}
