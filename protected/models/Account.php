<?php

/**
 * This is the model class for table "{{account}}".
 *
 * The followings are the available columns in table '{{account}}':
 * @property integer $id
 * @property integer $account_parent_id
 * @property integer $firm_id
 * @property integer $level
 * @property string $code
 * @property integer $is_selectable
 * @property integer $is_economic
 * @property string $outstanding_balance
 *
 * The followings are the available model relations:
 * @property Firm $firm
 * @property Language[] $tblLanguages
 * @property Debitcredit[] $debitcredits
 */
class Account extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Account the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{account}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('firm_id, code', 'required'),
			array('account_parent_id, firm_id, level, is_selectable, is_economic', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>16),
			array('outstanding_balance', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, account_parent_id, firm_id, level, code, is_selectable, is_economic, outstanding_balance', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'firm' => array(self::BELONGS_TO, 'Firm', 'firm_id'),
			'tblLanguages' => array(self::MANY_MANY, 'Language', '{{account_name}}(account_id, language_id)'),
			'debitcredits' => array(self::HAS_MANY, 'Debitcredit', 'account_id'),
      'names' => array(self::HAS_MANY, 'AccountName', 'account_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'account_parent_id' => 'Account Parent',
			'firm_id' => 'Firm',
			'level' => 'Level',
			'code' => 'Code',
			'is_selectable' => 'Is Selectable',
			'is_economic' => 'Is Economic',
			'outstanding_balance' => 'Outstanding Balance',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('account_parent_id',$this->account_parent_id);
		$criteria->compare('firm_id',$this->firm_id);
		$criteria->compare('level',$this->level);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('is_selectable',$this->is_selectable);
		$criteria->compare('is_economic',$this->is_economic);
		$criteria->compare('outstanding_balance',$this->outstanding_balance,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
  
	/**
   * Retrieves the name of the account in the language of the firm (if available)
	 * @return string the name of the account
	 */
	public function getName()
	{
    //Yii::trace('getName() called, account ' . $this->id, 'delt.debug');
    if($record = AccountName::model()->findByAttributes(array('account_id'=>$this->id, 'language_id'=>$this->firm->language_id)))
    {
      return $record->name;
    }
    else
    {
      $records = AccountName::model()->findAllByAttributes(array('account_id'=>$this->id));
      if(sizeof($records))
      {
        return $records[0]->name;
      }
      else
      {
        return '[unnamed]';
      }
    }
	}
  
}
