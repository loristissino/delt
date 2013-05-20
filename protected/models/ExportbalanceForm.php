<?php

class ExportbalanceForm extends CFormModel
{
  public $delimiter; // ', ", nothing
  public $separator; // tab, comma, colon, semicolon
  public $type;  // signed amount, unsigned amount with extra column, separate columns for debit and credit outstanding balance
  
  public $delimiters = array('"'=>'"', "'"=>"'", 'none'=>'');
  public $separators = array(','=>',', ';'=>';', ':'=>':', 't'=>'tab');
  public $types = array('S'=>'signed amount', 'U'=>'unsigned amount', '2'=>'two columns');
  
  public function rules()
	{
		return array(
      array('delimiter', 'ArrayValidator', 'values'=>$this->delimiters, 'message'=>'You must select a valid delimiter'),
      array('separator', 'ArrayValidator', 'values'=>$this->separators, 'message'=>'You must select a valid separator'),
      array('type', 'ArrayValidator', 'values'=>$this->types, 'message'=>'You must select a valid type'),
		);
	}
  
  /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'delimiter' => Yii::t('delt', 'Delimiter'),
			'separator' => Yii::t('delt', 'Separator'),
		);
	}
  
}
