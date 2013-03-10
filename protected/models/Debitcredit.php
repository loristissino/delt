<?php

/**
 * This is the model class for table "{{debitcredit}}".
 *
 * The followings are the available columns in table '{{debitcredit}}':
 * @property integer $id
 * @property integer $account_id
 * @property integer $post_id
 * @property string $amount
 * @property integer $rank
 *
 * The followings are the available model relations:
 * @property Account $account
 * @property Post $post
 */
class Debitcredit extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DebitCredit the static model class
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
		return '{{debitcredit}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('account_id, post_id, amount', 'required'),
			array('account_id, post_id, rank', 'numerical', 'integerOnly'=>true),
			array('amount', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, account_id, post_id, amount, rank', 'safe', 'on'=>'search'),
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
			'post' => array(self::BELONGS_TO, 'Post', 'post_id'),
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
			'post_id' => 'Post',
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
		$criteria->compare('post_id',$this->post_id);
		$criteria->compare('amount',$this->amount,true);
		$criteria->compare('rank',$this->rank);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

  public function belongingTo($account_id)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'account_id = ' . $account_id,
    ));
    return $this;
  }
  
  public function ofFirm($firm_id)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'post.firm_id = ' . $firm_id,
        'order'=>'post.date ASC, post.rank ASC',
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
