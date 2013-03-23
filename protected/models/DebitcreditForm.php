<?php

class DebitcreditForm extends CFormModel
{

  public $name;
  public $debit;
  public $credit;
  public $name_errors=false;
  public $debit_errors=false;
  public $credit_errors=false;
  public $account_id;
  public $guessed=false;
  
  public function rules()
	{
		return array(
			array('name', 'required'),
		);
	}
  
  /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'name' => Yii::t('delt', 'Account'),
			'debit' => Yii::t('delt', 'Debit'),
			'credit' => Yii::t('delt', 'Credit'),
		);
	}

}

