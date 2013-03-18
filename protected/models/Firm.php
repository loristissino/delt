<?php

/**
 * This is the model class for table "{{firm}}".
 *
 * The followings are the available columns in table '{{firm}}':
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $description
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
 * @property Reason[] $reasons
 *
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
			array('name, slug, language_id, create_date', 'required'),
			array('status, language_id, firm_parent_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('currency', 'length', 'max'=>5),
			array('csymbol', 'length', 'max'=>1),
      array('description', 'safe'),
      array('slug', 'validateSlug'),
      array('currency', 'validateCurrency'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, slug, description, status, currency, csymbol, language_id, firm_parent_id, create_date', 'safe', 'on'=>'search'),
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
			'posts' => array(self::HAS_MANY, 'Post', 'firm_id', 'order'=>'posts.date ASC'),
      'reasons' => array(self::HAS_MANY, 'Reason', 'firm_id', 'order'=>'reasons.description ASC'),
      'language' => array(self::BELONGS_TO, 'Language', 'language_id'),
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
      'description' => Yii::t('delt', 'Description'),
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
    $criteria->compare('description',$this->description,true);
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
  
  public function validateSlug()
  {
    if(strlen($this->slug)>32)
    {
      $this->addError('slug', Yii::t('delt', 'The maximum length of a slug is of 32 characters.'));
      return;
    }
    if(preg_match('/[^0-9a-z\-]/', $this->slug))
    {
      $this->addError('slug', Yii::t('delt', 'Only lowercase letters, digits and minus sign are allowed.'));
      return;
    }
    
    $f=Firm::model()->findByAttributes(array('slug'=>$this->slug));
    if($f and $f->id != $this->id)
    {
      $this->addError('slug', Yii::t('delt', 'This slug is already in use.'));
    }
  }

  public function validateCurrency()
  {
    if($this->currency=='')
    {
      return;
    }
    if(strlen($this->currency)!=3)
    {
      $this->addError('currency', Yii::t('delt', 'The currency must be expressed as an ISO 4217 code (three characters).'));
    }
    $this->currency = strtoupper($this->currency);
  }

  
  public function getParent()
  {
    return Firm::model()->findByPk($this->firm_parent_id);
  }
  
	/**
	 * @param DEUser $user the user to check
	 * @return boolean true if the firm is manageable by $user, false otherwise
	 */
  public function isManageableBy(DEUser $user=null)
  {
    if(!$user)
    {
      return false;
    }
    $fu=FirmUser::model()->findByAttributes(array('firm_id'=>$this->id, 'user_id'=>$user->id));
    return sizeof($fu) > 0;
  }
  
  public function getOwners($as_text=false)
  {
    // FIXME This should be done with one query, I must study how the model from the plugin works...
    
    $rows = FirmUser::model()->findAllByAttributes(array('firm_id'=>$this->id));
    
    if($as_text)
    {
      $users = array();
      foreach($rows as $row)
      {
        $users[]=$row->user_id;
      }
      
      $profiles=Profile::model()->findAllByPk($users);
      
      $lines = array();
      {
        foreach($profiles as $profile)
        {
          $lines[]=$profile->first_name . ' ' . $profile->last_name;
        }
      }
           
      return implode(', ', $lines);
    }
    
    return $rows;
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
  
  public function getAccountBalancesAsDataProvider()
  {
    return new CActiveDataProvider(Account::model()->with('firm')->with('currentname')->belongingTo($this->id), array(
      'criteria'=>array(
          'condition'=>'is_selectable = 1',
          'order' => 'code ASC',
          'with'=>array('debitcredits'=>array(
            'on'=>'t.id = debitcredits.account_id',
            'together'=>true,
            'joinType' => 'INNER JOIN',
            )),
        ),
      'pagination'=>array(
          'pageSize'=>100,
          ),
      )
    );
  }
  
  public function getAccountBalances($nature='')
  {
    $result=array();
    $accounts = Yii::app()->db->createCommand()
      ->select('SUM(amount) as total, a.code as code, n.name')
      ->from('{{debitcredit}}')
      ->leftJoin('{{account}} a', 'account_id = a.id')
      ->leftJoin('{{account_name}} n', 'a.id = n.account_id AND n.language_id = ' . $this->language_id)
      ->leftJoin('{{post}} p', 'post_id = p.id')
      ->where('p.firm_id=:id', array(':id'=>$this->id))
      ->andWhere('a.nature = :nature', array(':nature'=>$nature))
      ->order('a.code')
      ->group('a.code, n.name')
      ->having('total <> 0')
      ->queryAll();
    
    $grandtotal=0;  
    foreach($accounts as $account)
    {
      $grandtotal+=$account['total'];
      $row=array();
      $row['name']=$account['code'] . ' - ' . $account['name'];
      if($account['total'] > 0)
      {
        $row['debit']='';
        $row['credit']= DELT::currency_value($account['total'], $this->currency);
      }
      elseif($account['total'] < 0)
      {
        $row['debit']= DELT::currency_value(-$account['total'], $this->currency);
        $row['credit']='';
      }
      else
      { // this should theoretically never happen...
        $row['debit']='';
        $row['credit']='';
      }
      $result[]=$row;
    }
    if($grandtotal!=0)
    {
      $closingaccounts=Account::model()->with('names')->findAllByAttributes(array('firm_id'=>$this->id, 'nature'=>strtolower($nature), 'is_selectable'=>true));
      if(sizeof($closingaccounts)==1)
      { 
        $result[]=array(
          'name'=>$closingaccounts[0]->__toString(),
          'debit'=>($grandtotal>0) ? DELT::currency_value($grandtotal, $this->currency) : '',
          'credit'=>($grandtotal<0) ? DELT::currency_value(-$grandtotal, $this->currency) : '',
          );
      }
    }
    
    return $result;
  }
  
  public function getPostsAsDataProvider()
  {
    return new CActiveDataProvider(Debitcredit::model()->with('post')->with('account')->with('account.names')->ofFirm($this->id), array(
      'pagination'=>array(
          'pageSize'=>30,
          ),
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
      $info['model']->number_of_children = sizeof($info['children']);
    }
    
    foreach($a as $id=>$info)
    {
      foreach($info['children'] as $child_id)
      {
        $a[$child_id]['model']->nature = $info['model']->nature;
        $a[$child_id]['model']->setParentCode($info['model']->code);
      }
      
    }
    
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
  
  public function fixAccountNames()
  {
    // useful after firm forking (this way we get the i18n names)
    foreach($this->accounts as $account)
    {
      $account->save();
    }
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
  
  public function findAccounts($term)
  {
    $accounts = Yii::app()->db->createCommand()
      ->select('code, outstanding_balance, n.name')
      ->from('{{account}}')
      ->leftJoin('{{account_name}} n', 'n.account_id = id AND n.language_id=:language_id', array(':language_id'=>$this->language_id))
      ->where('firm_id=:id', array(':id'=>$this->id))
      ->andWhere(array('or', 
        array('like', 'code', '%' . $term . '%'),
        array('like', 'n.name', '%' . $term . '%')
        ))
      ->andWhere('is_selectable = 1')
      ->queryAll();
    
    $result=array();
    foreach($accounts as $account)
    {
      $result[]=$account['code']. ' - ' . $account['name'];
    }
    return $result;
  }
  
  
  public function getTotalAmounts($type='D')
  {
    $amount = Yii::app()->db->createCommand()
      ->select('SUM(amount) as total')
      ->from('{{debitcredit}}')
      ->leftJoin('{{post}} p', 'post_id = p.id')
      ->where('p.firm_id=:id', array(':id'=>$this->id))
      ->andWhere('amount ' . $type='D'? '>0' : '<0')
      ->queryScalar();
            
    return $type='D' ? $amount : -$amount;

  }
  
  public function findForkableFirms()
  {
    return Firm::model()->findAllByAttributes(array('status'=>1));
  }
  
  public function forkFrom(Firm $source, DEUser $user, $type)
  {
    $this->name=Yii::t('delt', 'Copy of "{name}"', array('{name}'=>$source->name));
    $this->slug=md5($this->name + microtime());
    foreach(array('language_id','currency','csymbol','description') as $property)
    {
      $this->$property = $source->$property;
    }
    $this->status = 0;
    $this->firm_parent_id = $source->id;
    
    $transaction = $this->getDbConnection()->beginTransaction();
    
    try
    {
      $this->save(false);
      
      $fu = new FirmUser();
      $fu->firm_id=$this->id;
      $fu->user_id=$user->id;
      $fu->role='O';
      $fu->save(false);

      $references = array();  // this will keep references between the old and the new codes of accounts

      foreach($source->accounts as $account)
      {
        $newaccount = new Account;
        $newaccount->firm_id = $this->id;
        $newaccount->account_parent_id = null;
        
        foreach(array('code', 'level', 'nature', 'is_selectable', 'outstanding_balance', 'number_of_children') as $property)
        {
          $newaccount->$property = $account->$property ? $account->$property : '1';
        }
        $newaccount->textnames = $account->l10n_names;
        $newaccount->basicSave(false);
        
        $references[$account->id]=$newaccount->id;
      }
      
      if(substr($type, 1, 1)=='1')
      {
        // we must fork reasons...
        foreach($source->reasons as $reason)
        {
          $newreason = new Reason;
          $newreason->firm_id = $this->id;
          foreach(array('description') as $property)
          {
            $newreason->$property = $reason->$property;
          }
          $info=array();
          foreach(unserialize($reason->info) as $id=>$value)
          {
            $info[$references[$id]]=$value;
          }
          $newreason->info=serialize($info);
          $newreason->save(false);
        }
      }
      
      if(substr($type, 2, 1)=='1')
      {
        // we must fork posts...
        foreach($source->posts as $post)
        {
          $newpost = new Post;
          $newpost->firm_id = $this->id;
          foreach(array('date', 'description', 'is_confirmed', 'rank') as $property)
          {
            $newpost->$property = $post->$property;
          }
          $newpost->save(false);
          foreach($post->debitcredits as $debitcredit)
          {
            $newdebitcredit = new Debitcredit;
            $newdebitcredit->post_id = $newpost->id;
            foreach(array('amount', 'rank') as $property)
            {
              $newdebitcredit->$property = $debitcredit->$property;
            }
            $newdebitcredit->account_id = $references[$debitcredit->account_id];
            $newdebitcredit->save(false);
          }
        }
      }
      
      $transaction->commit();
      Yii::app()->getUser()->setFlash('delt_success', Yii::t('delt', 'The firm has been successfully forked.'));
      return true;
    }
    catch(Exception $e)
    {
      $transaction->rollBack();
      Yii::app()->getUser()->setFlash('delt_failure', $e->getMessage());
      return false;
    }
    
  }
  
  public function isForkableBy(DEUser $user)
  {
    if($this->status==1)
      return true;
    
    if($this->isManageableBy($user))
      return true;
      
    return false;
  }
  
  /*
  public function behaviors()
  {
    return array(
        'CTimestampBehavior'=>array(
          'class'=>'zii.behaviors.CTimestampBehavior',
          'createAttribute'=>'create_date',
        ),
    );
  }
  */
  
  public function getLicenseCode(CController $controller)
  {
    $text = Yii::t('delt', '<a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-sa/3.0/88x31.png" /></a><br />
<span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">«{firmname}»</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="{author_url}" property="cc:attributionName" rel="cc:attributionURL">{author_name}</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>.'
,

    array(
      '{locale}' => $this->language->locale,
      '{firmname}' => $this->name,
      '{author_name}' => $this->getOwners(true),
      '{author_url}' => $controller->createUrl('firm/owners', array('slug'=>$this->slug)),
      )
    );
    

    if($this->firm_parent_id)
    {
      $text .= Yii::t('delt', '<br />Based on a work at <a xmlns:dct="http://purl.org/dc/terms/" href="{source_url}" rel="dct:source">{source_name}</a>.',
      
      array(
        '{source_url}' => $controller->createUrl('firm/public', array('slug'=>$this->parent->slug)),
        '{source_name}' => $this->parent,
      )
      
      );
      
    }
    
    return $text;
  }
  
  /**
   * Builds an array of all relevant data of a firm.
   * @param type string the type of data to return
   * @return array the data
   */  
  public function getExportData($type)
  {
    /* type can be:
     *   100 -- only accounts
     *   110 -- accounts and reasons
     *   111 -- accounts, reasons, posts
     */
    
    $data=array();
    
    $data['base']=array(
      'name'=>$this->name,
      'description'=>$this->description,
      'currency'=>$this->currency,
      'language'=>$this->language->locale,
      );
    
    $references = array();
    foreach($this->accounts as $account)
    {
      $values=array();
      foreach(array('code', 'textnames', 'nature', 'outstanding_balance') as $property)
      {
        $values[$property] = $account->$property;
      }
      $data['accounts'][]=$values;
      $references[$account->id]=$account->code;
    }
    
    if(substr($type, 1, 1)=='1')
    {
      // we must export reasons...
      foreach($this->reasons as $reason)
      {
        $values=array();
        foreach(array('description') as $property)
        {
          $values[$property] = $reason->$property;
        }
        $info=array();
        foreach(unserialize($reason->info) as $id=>$value)
        {
          $info[$references[$id]]=$value;
        }
        $values['accounts']=$info;
        $data['reasons'][]=$values;
      }
    }
    
    if(substr($type, 2, 1)=='1')
    {
      // we must export posts...
      foreach($this->posts as $post)
      {
        $values = array();
        foreach(array('date', 'description', 'is_confirmed', 'rank') as $property)
        {
          $values[$property] = $post->$property;
        }
        foreach($post->debitcredits as $debitcredit)
        {
          $info = array();
          foreach(array('amount', 'rank') as $property)
          {
            $info[$property] = $debitcredit->$property;
          }
          $info['account_code'] = $references[$debitcredit->account_id];
          $values['debitcredits'][]=$info;
        }

        $data['posts'][]=$values;
      }
    }

    return $data;
  }
  
  

}
