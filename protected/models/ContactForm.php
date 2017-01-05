<?php

/**
 * ContactForm class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.0
 */
/** ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 * 
 * @package application.forms
 * 
 */
class ContactForm extends CFormModel
{
  public $name;
  public $email;
  public $subject;
  public $body;
  public $verifyCode;

  /**
   * Declares the validation rules.
   */
  public function rules()
  {
    return array(
      // name, email, subject and body are required
      array('name, email, subject, body', 'required'),
      // email has to be a valid email address
      array('email', 'email'),
      // verifyCode needs to be entered correctly
      array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
    );
  }

  /**
   * Declares customized attribute labels.
   * If not declared here, an attribute would have a label that is
   * the same as its name with the first letter in upper case.
   */
  public function attributeLabels()
  {
    return array(
      'verifyCode'=>Yii::t('delt', 'Verification Code'),
      'name'=>Yii::t('delt', 'Name'),
      'email'=>Yii::t('delt', 'Email where you want to receive a reply'),
      'subject'=>Yii::t('delt', 'Subject'),
      'body'=>Yii::t('delt', 'Body'),
    );
  }
}
