<?php

class DebitcreditForm extends CFormModel
{

  public $name;
  public $debit;
  public $credit;
  
  public function rules()
	{
		return array(
			array('name', 'required'),
		);
	}

}

