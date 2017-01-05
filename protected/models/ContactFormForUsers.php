<?php

/**
 * ContactFormForUsers class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.0
 */
/** ContactFormForUsers class.
 * 
 * @package application.forms
 * 
 */
class ContactFormForUsers extends ContactForm
{

  /**
   * Declares the validation rules.
   */
  public function rules()
  {
    return array(
      // name, email, subject and body are required
      array('email, subject, body', 'required'),
      // email has to be a valid email address
      array('email', 'email'),
      // verifyCode needs to be entered correctly
      array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
    );
  }


}
