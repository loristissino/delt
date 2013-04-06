<?php

/**
 * ContactFormForUsers class.
 */
class ContactFormForUsers extends ContactForm
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
			array('email, subject, body', 'required'),
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
      'email'=>Yii::t('delt', 'Email'),
      'subject'=>Yii::t('delt', 'Subject'),
      'body'=>Yii::t('delt', 'Body'),
		);
	}
}
