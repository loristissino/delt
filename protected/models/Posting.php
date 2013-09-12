<?php

/**
 * This is the model class for table "{{posting}}".
 *
 * The followings are the available columns in table '{{posting}}':
 * @property integer $id
 * @property integer $account_id
 * @property integer $journalentry_id
 * @property string $amount
 * @property integer $rank
 *
 * The followings are the available model relations:
 * @property Account $account
 * @property Journalentry $journalentry
 */
class Posting extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Posting the static model class
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
		return '{{posting}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('account_id, journalentry_id, amount', 'required'),
			array('account_id, journalentry_id, rank', 'numerical', 'integerOnly'=>true),
			array('amount', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, account_id, journalentry_id, amount, rank', 'safe', 'on'=>'search'),
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
			'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
			'journalentry' => array(self::BELONGS_TO, 'Journalentry', 'journalentry_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'account_id' => 'Account',
			'journalentry_id' => 'Journalentry',
			'amount' => 'Amount',
			'rank' => 'Rank',
      'debit' => Yii::t('delt', 'Debit'),
      'credit' => Yii::t('delt', 'Credit'),
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
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('journalentry_id',$this->journalentry_id);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('rank',$this->rank);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

  public function belongingTo($account_id)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'journalentry.is_included = 1 and account_id = ' . $account_id,
        'order'=>'journalentry.date ASC, journalentry.rank ASC',
    ));
    return $this;
  }
  
  public function ofFirm($firm_id)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'journalentry.firm_id = ' . $firm_id,
        'order'=>'journalentry.date ASC, journalentry.rank ASC',
    ));
    return $this;
  }
  
  public function getDebit()
  {
    return $this->amount>0 ? $this->amount: null;
  }  
  
  public function getCredit()
  {
    return $this->amount<0 ? -$this->amount: null;
  }  

}
