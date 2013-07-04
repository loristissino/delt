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
 * @property string $rcode
 * @property integer $is_selectable
 * @property string $collocation
 * @property string $outstanding_balance
 * @property string $l10n_names
 * @property string $textnames
 * @property string $currentname
 * @property integer $number_of_children
 * @property string $comment
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
      array('comment', 'length', 'max'=>500),
      array('code', 'checkCode'),
      array('collocation', 'checkCollocation'),
      array('comment', 'checkComment'),
			array('collocation,outstanding_balance', 'length', 'max'=>1),
      array('textnames', 'checkNames'),
      array('currentname', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, account_parent_id, firm_id, level, code, is_selectable, collocation, outstanding_balance', 'safe', 'on'=>'search'),
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
      //DELTOD 'names' => array(self::HAS_MANY, 'AccountName', 'account_id'),
      //DELTOD 'currentname' => array(self::HAS_ONE, 'AccountName', '', 'on' => 'currentname.account_id = t.id and currentname.language_id = firm.language_id'),
      'debitgrandtotal' => array(self::STAT, 'Debitcredit', 'account_id', 
        'select'=>'SUM(amount)',
        'condition'=>'amount > 0',
        ),
      'creditgrandtotal' => array(self::STAT, 'Debitcredit', 'account_id', 
        'select'=>'SUM(amount)',
        'condition'=>'amount < 0',
        ),
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
			'code' => Yii::t('delt', 'Code'),
			'is_selectable' => 'Is Selectable',
			'collocation' => Yii::t('delt', 'Collocation'),
			'outstanding_balance' => Yii::t('delt', 'Ordinary outstanding balance'),
      'textnames' => Yii::t('delt', 'Localized names'),
      'number_of_children' => Yii::t('delt', 'Number of children'),
      'comment'=> Yii::t('delt', 'Comment'),
		);
	}
  
	/**
	 * @return array valid account collocations (key=>label)
	 */
	public function validCollocations($withUncollocated=true)
	{
    $collocations = array(
      'P'=>Yii::t('delt', 'Financial Statement (Asset / Liability / Equity)'),
      'E'=>Yii::t('delt', 'Income Statement (Revenues / Expenses)'),
      'M'=>Yii::t('delt', 'Memorandum Accounts Table'),
      'p'=>Yii::t('delt', 'Transitory Financial Statement Accounts'),
      'e'=>Yii::t('delt', 'Transitory Income Statement Accounts'),
      'r'=>Yii::t('delt', 'Result Accounts (Net profit / Total loss)'),
      ); 
    if($withUncollocated)
    {
      $collocations['?'] = Yii::t('delt', 'Unknown');
    }
    return $collocations;
	}
  
  public function getCollocationLabel()
  {
    switch($this->collocation)
    {
      case 'P': return 'FS';
      case 'E': return 'IS';
      default: return $this->collocation;
    }
  }

	/**
	 * @return array valid account collocations
	 */
	public function getValidCollocationByCode($code)
  {
    $collocations=$this->validCollocations();
    return isset($collocations[$code]) ? $collocations[$code] : null;
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
    $criteria->compare('collocation',$this->collocation);
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
    return $this->currentname;
    
    //DELTOD
    /*
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
    */
	}
  
  public function getParent()
  {
    return Account::model()->findByPk($this->account_parent_id);
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
  
  public function __toString()
  {
    return sprintf('%s - %s', $this->code, $this->name);
  }
    
  public function belongingTo($firm_id)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'firm_id = ' . $firm_id,
        'order'=>'code ASC',
    ));
    return $this;
  }
  
  //DELTOD
  /*
  public function getL10n_names()
  {
    $text='';
    $account_names=AccountName::model()->with('language')->findAllByAttributes(array('account_id'=>$this->id));
    foreach($account_names as $account_name)
    {
      $text .= $account_name->language->locale . ': ' . $account_name->name . "\n";
    }
    return $text;
  }
  */
  
  /**
	 * Deletes the row corresponding to this active record.
	 * @return boolean whether the deletion is successful.
	 * @throws CException if the record is new
   * @override parent::delete()
	 */
  /* DELTOD
	public function delete()
	{
		if(!$this->getIsNewRecord())
		{
			Yii::trace(get_class($this).'.delete()','system.db.ar.CActiveRecord');
			if($this->beforeDelete())
			{
        $transaction = $this->getDbConnection()->beginTransaction();
        try
        {
          $deleted=AccountName::model()->deleteAllByAttributes(array('account_id'=>$this->id));
          $result=$this->deleteByPk($this->getPrimaryKey())>0;
          $transaction->commit();
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
  */
  public function getNumberOfPosts()
  {
    return Debitcredit::model()->countByAttributes(array('account_id'=>$this->id));
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
  
  
  protected function beforeSave()
  {
    $this->level = sizeof(explode('.', $this->code));
    
    if($this->level > 1)
    {
      $parent = Account::model()->findByAttributes(array('firm_id'=>$this->firm_id, 'code'=>$this->getComputedParentCode()));

      if(!$parent)
      {
        $this->addError('code', Yii::t('delt', 'The parent account does not exist.'));
        return false;
      }
      
      $this->account_parent_id = $parent->id; 
    }
    
    else
    {
      $this->account_parent_id = null; 
    }
    
    if(!in_array($this->outstanding_balance, array('D', 'C')))
    {
      $this->outstanding_balance = null;
    }
    
    return parent::beforeSave();
  }
  
  public function save($runValidation=true,$attributes=null)
  {
    $this->_computeRcode();
    $this->setName();
    try
    {
      parent::save($runValidation, $attributes);
      return true;
    }
    catch(Exception $e)
    {
      $this->addError('code', Yii::t('delt', 'This code is already in use.'));
      return false;
    }
  }
  
  public function basicSave($runValidation=true,$attributes=null)
  {
    $this->_computeRcode();
    $this->setName();
    try
    {
      return parent::save($runValidation, $attributes);
    }
    catch (Exception $e)
    {
      die($e->getMessage());
    }
  }
  
  /*
   * Computes a reversed code, that will be used for sorting accounts
   * in a children-to-parent order
   * 
   */  
  private function _computeRcode()
  {
    $this->rcode = $this->code . str_repeat('~', 16);
  }
  
  
  /*
   * Checks whether the code contains only valid characters
   * @return boolean true if the code is legal, false otherwise
   */
  private function _codeContainsOnlyValidChars()
  {
    if($this->collocation=='?')
    {
      return !preg_match('/^[a-zA-Z0-9\.\!]*$/', $this->code);
    }
    return preg_match('/^[a-zA-Z0-9\.]*$/', $this->code);
  }
  
  /**
	 * This method is invoked after a model instance is created by new operator.
	 * The default implementation raises the {@link onAfterConstruct} event.
	 * It is here overridden to do postprocessing after model creation.
	 * We call the parent implementation so that the event is raised properly.
	 */  
  /*
  protected function afterConstruct()
  {
    parent::afterConstruct();
    $this->setDefaultForNames($this->firm);
  }
  */
  
  public function setDefaultForNames(Firm $firm=null, $name='')
  {
    $languages = $this->firm->languages;
    $this->textnames = '';
    {
      foreach($languages as $language)
      {
        $this->textnames .= $language->locale . ': ';
        if($firm && $language->id == $firm->language_id)
        {
          $this->textnames .= $name;
        }
        $this->textnames .= "\n";
      }
    }
    $this->textnames = substr($this->textnames, 0, strlen($this->textnames)-1);
  }
  
  public function fixDefaultForNames()
  {
    $languages = $this->firm->languages;
    
    $names = $this->getNamesAsArray($languages);
    
    $this->textnames = '';
    
    foreach($languages as $language)
    {
      $this->textnames .= $language->locale . ': ';
      if(isset($names[$language->locale]))
      {
        $this->textnames .= $names[$language->locale];
      }
      $this->textnames .= "\n";
    }
    $this->textnames = substr($this->textnames, 0, strlen($this->textnames)-1);
  }
  
  
  
  
  public function getNumberOfChildren()
  {
    return Account::model()->countByAttributes(array('account_parent_id'=>$this->id));
  }
  
  public function getChildren()
  {
    return Account::model()->findAllByAttributes(array('account_parent_id'=>$this->id));
  }
  
  public function getParentAccount()
  {
    return Account::model()->findByPk($this->account_parent_id);
  }
  
  public function getComputedParentCode()
  {
    return substr($this->code, 0, strrpos($this->code, '.'));
  }
  
  public function setParentCode($value)
  {
    $this->code = $value . substr($this->code, strrpos($this->code, '.'));
  }
  
  public function getFirstAncestor()
  {
    $code = substr($this->code, 0, strpos($this->code, '.'));
    return Account::model()->findByAttributes(array('code'=>$code, 'firm_id'=>$this->firm_id));
  }
  
  public function getIs_deletable()
  {
    return $this->number_of_children == 0;
  }

  public function getDebitcreditsAsDataProvider()
  {
/*    $sort = new CSort;
    $sort->defaultOrder = 'code ASC';
    $sort->attributes = array(
        'code'=>'code',
        'name'=>'currentname.name',
        'collocation'=>'collocation',
    );    
  */  
    return new CActiveDataProvider(Debitcredit::model()->with('post')->belongingTo($this->id), array(
      'pagination'=>array(
          'pageSize'=>30,
          ),
    //  'sort'=>$sort,
      )
    );
  }

  public function checkCode()
  {
    if(!$this->_codeContainsOnlyValidChars())
    {
      $this->addError('code', Yii::t('delt', 'The code contains illegal characters.'));
    }
    
    if(substr($this->code, -1, 1)=='.')
    {
      $this->addError('code', Yii::t('delt', 'The code cannot end with a dot.'));
    }
    
    $parent_code = $this->getComputedParentCode();
    if($parent_code && !Account::model()->findByAttributes(array('firm_id'=>$this->firm_id, 'code'=>$parent_code)))
    {
      $this->addError('code', Yii::t('delt', 'The parent account, with code «%code%», does not exist.', array('%code%'=>$parent_code)));
    }
  }

  
  public function checkCollocation()
  {
     if(!in_array($this->collocation, array_keys($this->validCollocations())))
     {
       $this->addError('collocation', Yii::t('delt', 'Not a valid collocation.'));
     } 
     if($this->collocation=='?' && substr($this->code, 0, 1)!='!')
     {
       $this->addError('collocation', Yii::t('delt', 'This collocation is allowed only for bang accounts.'));
     }
  }

  public function checkComment()
  {
     if($this->comment != strip_tags($this->comment))
     {
       $this->addError('comment', Yii::t('delt', 'The text cannot contain HTML tags.'));
     } 
  }
  
  public function checkNames()
  {
    if($this->textnames == '')
    {
      $this->setDefaultForNames($this->firm);
      $this->addError('textnames', Yii::t('delt', 'There must be at least one name for the account.'));
      return;
    }
    
    $names = $this->getNamesAsArray();
    if(sizeof($names)==0)
    {
      $this->setDefaultForNames($this->firm, $this->textnames);
      $this->addError('textnames', Yii::t('delt', 'You cannot remove the locale (language code). It was put back.'));
    }
  }
  
  public function getConsolidatedBalance($without_closing=false)
  {
    $amount = Yii::app()->db->createCommand()
      ->select('SUM(amount) as total')
      ->from('{{debitcredit}} dc')
      ->leftJoin('{{account}} a', 'dc.account_id = a.id')
      ->leftJoin('{{post}} p', 'dc.post_id = p.id')
      ->where('a.code REGEXP "^' . $this->code .'"')
      ->andWhere('p.firm_id = :id', array(':id'=>$this->firm_id))
      ->andWhere($without_closing ? 'p.is_closing = 0': 'true')
      ->queryScalar();
            
    return $amount;
  }

//DELTOD
/*  
  public function safeDelete()
  {
    $transaction=$this->getDbConnection()->beginTransaction();
    
    try
    {
      $this->deleteNames();
      $this->delete();
      $transaction->commit();
      return true;
    }
    catch(Exception $e)
    {
      Yii::trace('could not delete account ' . $this->id . ' reason: ' . $e->getMessage(), 'delt.debug');
      $transaction->rollback();
      return false;
    }
  }
  
  public function deleteNames()
  {
    AccountName::model()->deleteAllByAttributes(array('account_id'=>$this->id));
    Yii::trace('names deleted for account ' . $this->id, 'delt.debug');
  }
*/
  public function getNamesAsArray($languages=array())
  {
    $result=array();
    $temp=array();
    
    foreach(explode("\n", str_replace("\r", "", $this->textnames)) as $line)
    {
      $info = explode(':', $line);
      if (sizeof($info)!=2)
      {
        continue;
      }
      $locale=trim($info[0]);
      
      $language = DELT::LocaleToLanguage($locale);
      
      $name=strip_tags(trim($info[1]));
      $result[$locale]=$name;
      DELT::array_add_if_unset($temp, $language, $name);
    }
    
    foreach($languages as $language)
    {
      if(!array_key_exists($language->locale, $result))
      {
        $result[$language->locale] = '';
      }
    }
    
    foreach($result as $key=>&$value)
    {
      if(trim($value)=='')
      {
        $tl=DELT::LocaleToLanguage($key);
        
        if(isset($temp[$tl]))
        {
          $value=$temp[$tl];
        }
      }
    }
    
    ksort($result);
    return $result;

  }
  
  public function setName()
  {
    $names=$this->getNamesAsArray();
    if(array_key_exists($this->firm->language->getLocale(), $names) && $names[$this->firm->language->getLocale()]!='')
    {
      $this->currentname = $names[$this->firm->language->getLocale()];
    }
    else
    {
      $this->currentname = array_shift(array_filter($names)) . '*'; 
    }
  }
  
  public function cleanup(Firm $firm)
  {
    $this->code = str_replace('!', '~', $this->id);
    unset($this->id);
    $this->currentname = trim(str_replace('!', '', $this->currentname));
    $this->setDefaultForNames($firm, $this->currentname);
//    $this->code = '!' . ($firm->countBangAccounts()+1);
  }
  
  
}
