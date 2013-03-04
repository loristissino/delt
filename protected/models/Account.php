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
 * @property integer $nature
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
			array('account_parent_id, firm_id, level, is_selectable', 'numerical', 'integerOnly'=>true),
			array('code', 'length', 'max'=>16),
			array('nature,outstanding_balance', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, account_parent_id, firm_id, level, code, is_selectable, nature, outstanding_balance', 'safe', 'on'=>'search'),
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
			'debitcredits' => array(self::HAS_MANY, 'Debitcredit', 'account_id'),
      'names' => array(self::HAS_MANY, 'AccountName', 'account_id'),
      'currentname' => array(self::HAS_ONE, 'AccountName', '', 'on' => 'currentname.account_id = t.id and currentname.language_id = firm.language_id'),
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
			'nature' => 'Nature',
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
//    $criteria->compare('name', $this->name);
    $criteria->compare('firm_id',$this->firm_id);
    $criteria->compare('level',$this->level);
    $criteria->compare('code',$this->code,true);
    $criteria->compare('is_selectable',$this->is_selectable);
    $criteria->compare('nature',$this->nature);
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
  /*
  public function scopes()
  {
    return array(
      'published'=>array(
          'condition'=>'status=1',
      ),
      'recently'=>array(
          'order'=>'create_time DESC',
          'limit'=>5,
      ),
    );
  }
  */
    
  public function belongingTo($firm_id)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'firm_id = ' . $firm_id,
        'order'=>'code ASC',
    ));
    return $this;
  }
  
  public function getL10nNames()
  {
    $names='';
    foreach($this->names as $name)
    {
      $names .= $name->name ;
    }
    return $names;
  }
  /*
  public function getNamex()
  {
    $name=$this->name;
    if(!$this->is_selectable)
    {
      $name = '<b>' . $name . '</b>';
    }
    
    return $name;
  }
  */
  
  /**
	 * Deletes the row corresponding to this active record.
	 * @return boolean whether the deletion is successful.
	 * @throws CException if the record is new
   * @override parent::delete()
	 */
	public function delete()
	{
    Yii::trace('deltm', 'deleting...');
		if(!$this->getIsNewRecord())
		{
			Yii::trace(get_class($this).'.delete()','system.db.ar.CActiveRecord');
      Yii::trace('deltm', 'before before');
			if($this->beforeDelete())
			{
        Yii::trace('deltm', 'trans');
        $transaction = $this->getDbConnection()->beginTransaction();
        try
        {
          Yii::trace('deltm', 'in try');
          $deleted=AccountName::model()->deleteAllByAttributes(array('account_id'=>$this->id));
          $result=$this->deleteByPk($this->getPrimaryKey())>0;
          $transaction->commit();
          Yii::trace('deltm', 'deleted: ' . $deleted . '  result: '. $result);
        }
        catch(Exception $e)
        {
          $transaction->rollback();
        }
        
        $this->afterDelete();
        return true;

			}
			else
				return false;
		}
		else
			throw new CDbException(Yii::t('yii','The active record cannot be deleted because it is new.'));
	}
  
  public function getNumberOfPosts()
  {
    return DebitCredit::model()->countByAttributes(array('account_id'=>$this->id));
  }
  
  /**
	 * This method is invoked before deleting a record.
	 * @return boolean whether the record should be deleted. Defaults to true.
	 */
	protected function beforeDelete()
	{
		if($this->getNumberOfPosts() > 0)
		{
      return false;
		}
		else
			return parent::beforeDelete();
	}
  
}
