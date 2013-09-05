<?php

/**
 * ImportAccountsForm class.
 * ImportAccountsForm is the data structure for keeping
 * import accounts form data. It is used by the 'import' action of 'AccountController'.
 */
class ImportAccountsForm extends CFormModel
{
	public $content;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// content is required
			array('content', 'required'),
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
      'content'=>Yii::t('delt', 'Content'),
		);
	}
}
