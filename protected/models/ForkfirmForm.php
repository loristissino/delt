<?php

/**
 * ForkfirmForm class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.0
 */
/** ForkfirmForm class.
 * ForkfirmForm is the data structure for keeping
 * fork firm form data.
 * 
 * @package application.forms
 * 
 */

class ForkfirmForm extends CFormModel
{
  public $name;
  public $type;
  public $change_language;
  public $license_confirmation;
  
  public function rules()
  {
    return array(
      array('name', 'length', 'max'=>128),
      array('type, license_confirmation', 'required'),
      array('type', 'checkType'),
      array('license_confirmation', 'checkLicense'),
      array('change_language', 'safe'),
    );
  }
  
  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'name' => Yii::t('delt', 'Name of the new firm'),
      'type' => Yii::t('delt', 'Data to duplicate:'),
      'license'=>Yii::t('delt', 'License'),
      'language'=>Yii::t('delt', 'Language'),
      'change_language'=>Yii::t('delt', 'Change language'),
      'license_confirmation' => Yii::t('delt', 'I understand that the contents of the firm I\'m creating will be available under the <a href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}">Creative Commons Attribution-ShareAlike 3.0 Unported</a> License.', array('{locale}'=>Yii::app()->language)),
    );
  }
  
  public function getTypeOptions()
  {
    return array(
      '111' => Yii::t('delt', 'Chart of Accounts, Templates, and Journal Entries'),
      '110' => Yii::t('delt', 'Chart of Accounts and Templates'),
      '100' =>Yii::t('delt', 'Chart of Accounts'),
    );
  }
  
  public function checkLicense()
  {
    if(!$this->license_confirmation)
    {
      $this->addError('license_confirmation', Yii::t('delt', 'You must confirm that you accept the license for the contents.'));
    }
  }

  public function checkType()
  {
    if(!in_array($this->type, array_keys($this->getTypeOptions())))
    {
      $this->addError('type', Yii::t('delt', 'You must choose a valid creation type.'));
    }
  }

  
}
