<?php

class ForkfirmForm extends CFormModel
{
  public $type;
  public $license_confirmation;
  
  public function rules()
	{
		return array(
			array('type, license_confirmation', 'required'),
      array('type', 'checkType'),
      array('license_confirmation', 'checkLicense'),
		);
	}
  
  /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'type' => Yii::t('delt', 'Data to duplicate:'),
      'license'=>Yii::t('delt', 'License'),
			'license_confirmation' => Yii::t('delt', 'I understand that the contents of the firm I\'m creating will be available under the <a href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}">Creative Commons Attribution-ShareAlike 3.0 Unported</a> License.', array('{locale}'=>Yii::app()->language)),
		);
	}
  
  public function getTypeOptions()
  {
    return array(
      '100' =>Yii::t('delt', 'Chart of Accounts'),
      '110' => Yii::t('delt', 'Chart of Accounts and Templates'),
      '111' => Yii::t('delt', 'Chart of Accounts, Templates, and Journal Entries'),
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
