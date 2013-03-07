<?php

/**
 * This is the model class for table "{{firm}}".
 *
 * The followings are the available columns in table '{{firm}}':
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property integer $status
 * @property string $currency
 * @property string $csymbol
 * @property integer $language_id
 * @property integer $firm_parent_id
 * @property string $create_date
 *
 * The followings are the available model relations:
 * @property Account[] $accounts
 * @property Users[] $tblUsers
 * @property Post[] $posts
 */
class Firm extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Firm the static model class
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
		return '{{firm}}';
	}
  
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, slug, currency, csymbol, language_id, firm_parent_id, create_date', 'required'),
			array('status, language_id, firm_parent_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('slug', 'length', 'max'=>32),
			array('currency', 'length', 'max'=>5),
			array('csymbol', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, slug, status, currency, csymbol, language_id, firm_parent_id, create_date', 'safe', 'on'=>'search'),
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
			'accounts' => array(self::HAS_MANY, 'Account', 'firm_id', 'order'=>'accounts.code ASC'),
			'tblUsers' => array(self::MANY_MANY, 'User', '{{firm_user}}(firm_id, user_id)'),
			'posts' => array(self::HAS_MANY, 'Post', 'firm_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('delt', 'ID'),
			'name' => Yii::t('delt', 'Name'),
			'slug' => Yii::t('delt', 'Slug'),
			'status' => Yii::t('delt', 'Status'),
			'currency' => Yii::t('delt', 'Currency'),
			'csymbol' => Yii::t('delt', 'Currency symbol'),
			'language_id' => Yii::t('delt', 'Language'),
			'firm_parent_id' => Yii::t('delt', 'Parent firm'),
			'create_date' => Yii::t('delt', 'Create Date'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('csymbol',$this->csymbol,true);
		$criteria->compare('language_id',$this->language_id);
		$criteria->compare('firm_parent_id',$this->firm_parent_id);
		$criteria->compare('create_date',$this->create_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return string a textual description of the firm
	 */
	public function __toString()
	{
		return $this->name;
	}
  
	/**
	 * @param DEUser $user the user to check
	 * @return boolean true if the firm is manageable by $user, false otherwise
	 */
  public function isManageableBy(DEUser $user=null)
  {
    // FIXME - This could probably be done better... :-(
    foreach($this->tblUsers as $fuser)
    {
      if ($fuser->id == $user->id) return true;
    };
    return false;
  }

  public function getAccountsAsDataProvider()
  {
    $sort = new CSort;
    $sort->defaultOrder = 'code ASC';
    $sort->attributes = array(
        'code'=>'code',
        'name'=>'currentname.name',
        'nature'=>'nature',
    );    
    
    return new CActiveDataProvider(Account::model()->with('firm')->with('currentname')->belongingTo($this->id), array(
      'pagination'=>array(
          'pageSize'=>100,
          ),
      'sort'=>$sort,
      )
    );
  }
  
  public function fixAccounts()
  {
    $maxlevel=0;
    $accounts = $this->accounts;
    $a=array();
    foreach($accounts as $account)
    {
      $a[$account->id]=array(
        'model'=>$account,
        'children'=>array(),
      );      
    }
    foreach($a as $id=>$info)
    {
      $parent_id = $info['model']->account_parent_id;
      if(isset($a[$parent_id]))
      {
        $a[$parent_id]['children'][]=$id;
        $a[$id]['parent_id']=$parent_id;
      }
      $info['model']->level = sizeof(explode('.', $info['model']->code));
      $maxlevel=max($maxlevel, $info['model']->level);
    }
    
    uasort($a, array($this, '_compareAccountsByLevel'));
    
    foreach($a as $id=>$info)
    {
      $info['model']->is_selectable = sizeof($info['children'])==0;
      // an account is selectable when it has no children
      if(!$info['model']->is_selectable)
      {
        $info['model']->outstanding_balance = null;
      }
      $info['model']->number_of_children = sizeof($info['children']);
    }
//    echo "<pre>";
    
    foreach($a as $id=>$info)
    {
      foreach($info['children'] as $child_id)
      {
        $a[$child_id]['model']->nature = $info['model']->nature;
        $a[$child_id]['model']->setParentCode($info['model']->code);
      }
      
    }
    
//    die();
    
    $transaction = $this->getDbConnection()->beginTransaction();
    try
    {
      foreach($a as $id=>$info)
      {
        $info['model']->basicSave(false);
      }
      $transaction->commit();
    }
    catch(Exception $e)
    {
      $transaction->rollback();
      return false;
    }
    return true;
    
  }
  
  
  private function _compareAccountsByLevel($a, $b)
  {
    if($a['model']->level == $b['model']->level)
    {
      return 0;
    }
    return $a['model']->level < $b['model']->level ? -1: 1;
  }
  
  public function manageableBy($user_id)
  {
    $this->getDbCriteria()->mergeWith(array(
        'with'=>'tblUsers',
        'condition'=>'user.id = ' . $user_id,
    ));
    return $this;
  }

}
