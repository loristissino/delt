<?php

/**
 * This is the model class for table "{{post}}".
 *
 * The followings are the available columns in table '{{post}}':
 * @property integer $id
 * @property integer $firm_id
 * @property string $date
 * @property string $description
 * @property integer $is_confirmed
 * @property integer $is_closing
 * @property integer $is_adjustment
 * @property integer $rank
 * @property integer $maxrank
 *
 * The followings are the available model relations:
 * @property Debitcredit[] $debitcredits
 * @property Firm $firm
 */
class Post extends CActiveRecord
{
  public $maxrank;
  
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Post the static model class
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
		return '{{post}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('firm_id, date, description', 'required'),
			array('firm_id, is_confirmed, is_closing, rank', 'numerical', 'integerOnly'=>true),
      array('is_adjustment', 'safe'),
			array('description', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, firm_id, date, description, is_confirmed, is_closing, rank', 'safe', 'on'=>'search'),
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
			'debitcredits' => array(self::HAS_MANY, 'Debitcredit', 'post_id', 'order'=>'debitcredits.rank ASC'),
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
			'date' => 'Date',
			'description' => 'Description',
			'is_confirmed' => 'Is Confirmed',
      'is_closing' => 'Is Closing',
      'is_adjustment' => 'Are Exceptions Allowed',
			'rank' => 'Rank',
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
		$criteria->compare('date',$this->date,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('is_confirmed',$this->is_confirmed);
		$criteria->compare('is_closing',$this->is_closing);
    $criteria->compare('is_adjustment',$this->is_adjustment);
		$criteria->compare('rank',$this->rank);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
  
  public function belongingTo($post_id)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'{{debitcredit}}.post_id = ' . $post_id,
        'order'=>'code ASC',
    ));
    return $this;
  }  
    
  public function getDateForFormWidget()
  {
    return DELT::getDateForFormWidget($this->date);
  }
  
  public function getCurrentMaxRank()
  {
    $criteria = new CDbCriteria;
    $criteria->select='MAX(rank) as maxrank';
    $criteria->condition='firm_id = :firm_id';
    $criteria->params=array(':firm_id' => $this->firm_id);
    
    $result = self::model()->find($criteria);
    return $result->maxrank;
  }
  
  public function deleteDebitcredits()
  {
    Debitcredit::model()->deleteAllByAttributes(array('post_id'=>$this->id));
  }
  
  public function safeDelete()
  {
    $transaction=$this->getDbConnection()->beginTransaction();
    
    try
    {
      $this->deleteDebitcredits();
      $this->delete();
      $transaction->commit();
      return true;
    }
    catch(Exception $e)
    {
      $transaction->rollback();
      return false;
    }
    
  }
  
  
}
