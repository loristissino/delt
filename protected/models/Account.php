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
			array('account_parent_id, firm_id, level, is_selectable', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>16),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, account_parent_id, firm_id, level, code, is_selectable', 'safe', 'on'=>'search'),
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

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}