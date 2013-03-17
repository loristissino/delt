<?php

/**
 * This is the model class for table "{{reason}}".
 *
 * The followings are the available columns in table '{{reason}}':
 * @property integer $id
 * @property integer $firm_id
 * @property string $description
 * @property string $info
 * @property integer $post_id the original post id (not stored in the db) 
 *
 * The followings are the available model relations:
 * @property Firm $firm
 */
class Reason extends CActiveRecord
{
  
  public $post_id;
  
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Reason the static model class
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
		return '{{reason}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('description', 'required'),
			array('firm_id', 'numerical', 'integerOnly'=>true),
			array('description', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, firm_id, description, info', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'firm_id' => 'Firm',
			'description' => Yii::t('delt', 'Description'),
			'info' => 'Info',
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
		$criteria->compare('firm_id',$this->firm_id);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('info',$this->info,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
  
  public function beforeSave()
  {
    if($post = Post::model()->findByPk($this->post_id))
    {
      $accounts = array();
      foreach($post->debitcredits as $debitcredit)
      {
        $accounts[$debitcredit->account_id] = array('rank'=>$debitcredit->rank, 'type'=>DELT::amount2type($debitcredit->amount, false));
      }
      $this->info=serialize($accounts);
    }
    
    return parent::beforeSave();
  }
  
  public function getAccountsInvolved($currency)
  {
    $result=array();
    if(!$info=unserialize($this->info))
    {
      return $result;
    }
    
    $accounts = Account::model()->with('names')->findAllByPk(array_keys($info));
    foreach($accounts as $account)
    {
      $result[$info[$account->id]['rank']]=array(
        'name'=> $account->code . ' - ' . $account->name,
        'debit'=>$info[$account->id]['type']=='D' ? DELT::currency_value(0, $currency, false, true): '',
        'credit'=>$info[$account->id]['type']=='C' ? DELT::currency_value(0, $currency, false, true): '',
      );
    }
    ksort($result);
    return $result;
  }
  
}
