<?php

class PostForm extends CFormModel
{

  public $date;
  public $description;
  public $debitcredits;
  
  public function rules()
	{
		return array(
			array('date, description', 'required'),
		);
	}
  
  public function acquireItems($values)
  {
    
    foreach($values as $key => $value)
    {
      $this->debitcredits[$key] = new DebitcreditForm();
      $this->debitcredits[$key]->name = $value['name'];
      $this->debitcredits[$key]->debit = $value['debit'];
      $this->debitcredits[$key]->credit = $value['credit'];
    }
    
  }

}

