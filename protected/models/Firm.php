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
  
  public $license_confirmation;
  
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
			array('name, slug, language_id', 'required'),
			array('status, language_id, firm_parent_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('currency', 'length', 'max'=>5),
			array('csymbol', 'length', 'max'=>1),
      array('description', 'safe'),
      array('slug', 'validateSlug'),
      array('currency', 'validateCurrency'),
      array('license_confirmation', 'checkLicense'),
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
			'license_confirmation' => Yii::t('delt', 'I understand that the contents of the firm I\'m creating will be available under the <a href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}">Creative Commons Attribution-ShareAlike 3.0 Unported</a> License.', array('{locale}'=>Yii::app()->language)),
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
  
  public function checkLicense()
  {
    if(!$this->id and !$this->license_confirmation)
    {
      $this->addError('license_confirmation', Yii::t('delt', 'You must confirm that you accept the license for the contents.'));
    }
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
          'pageSize'=>200,
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
    if($nature=='e')
    {
      $result[]=array(
        'name'=>Yii::t('delt', $grandtotal<0 ? 'Select account of profit destination': 'Select account of loss destination'),
        'debit'=>($grandtotal>0) ? DELT::currency_value($grandtotal, $this->currency) : '',
        'credit'=>($grandtotal<0) ? DELT::currency_value(-$grandtotal, $this->currency) : '',
        );
    }
    elseif($grandtotal!=0)
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
          'pageSize'=>100,
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

  public function getCOAMaxLevel()
  {
    $level = Yii::app()->db->createCommand()
      ->select('MAX(level) as level')
      ->from('{{account}}')
      ->where('firm_id=:id', array(':id'=>$this->id))
      ->queryScalar();
    return $level;
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
  
  public function saveWithOwner(DEUser $user)
  {
    $transaction = $this->getDbConnection()->beginTransaction();
    
    try
    {
      $this->save();
      
      $fu = new FirmUser();
      $fu->firm_id=$this->id;
      $fu->user_id=$user->id;
      $fu->role='O';
      $fu->save(false);
      $transaction->commit();
      return true;
    }
    catch(Exception $e)
    {
      $transaction->rollback();
      return false;
    }
    
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
    $text = Yii::t('delt', '<a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-sa/3.0/88x31.png" width="88" height="31" /></a><br />
<span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">«{firmname}»</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="{author_url}" property="cc:attributionName" rel="cc:attributionURL">{author_name}</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>.'
,

    array(
      '{locale}' => $this->language->locale,
      '{firmname}' => $this->name,
      '{author_name}' => $this->getOwners(true),
      '{author_url}' => $controller->createUrl('firm/owners', array('slug'=>$this->slug)),
      )
    );
    

    if($this->firm_parent_id && $this->parent)
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
    
    DELT::object2array($this, $data['base'], array('name', 'description', 'currency'));

    $data['base']['language'] = $this->language->locale;
        
    $references = array();
    foreach($this->accounts as $account)
    {
      $values=array();
      DELT::object2array($account, $values, array('code', 'textnames', 'nature', 'outstanding_balance', 'comment'));
      $data['accounts'][]=$values;
      $references[$account->id]=$account->code;
    }
    
    $data['reasons']=array();
    if(substr($type, 1, 1)=='1')
    {
      // we must export reasons...
      foreach($this->reasons as $reason)
      {
        $values=array();
        DELT::object2array($reason, $values, array('description'));
        $info=array();
        foreach(unserialize($reason->info) as $id=>$value)
        {
          $info[$references[$id]]=$value;
        }
        $values['accounts']=$info;
        $data['reasons'][]=$values;
      }
    }
    
    $data['posts'] = array();
    if(substr($type, 2, 1)=='1')
    {
      // we must export posts...
      foreach($this->posts as $post)
      {
        $values = array();
        DELT::object2array($post, $values, array('date', 'description', 'is_confirmed', 'is_closing', 'rank'));
        
        foreach($post->debitcredits as $debitcredit)
        {
          $info = array();
          DELT::object2array($debitcredit, $info, array('amount', 'rank'));
          $info['account_code'] = $references[$debitcredit->account_id];
          $values['debitcredits'][]=$info;
        }

        $data['posts'][]=$values;
      }
    }
    else
    {
      $data['posts']=array();
    }

    $data['meta']=array(
      'delt_version'=>DELT::getVersion(),
      'website'=>Yii::app()->getRequest()->getHostInfo(),
      'id'=>$this->id,
      );
      
    $data['md5sum']=$this->_md5($data);

    return $data;
  }
  
  public function loadFromFile(CUploadedFile $file)
  {
    try
    {
      $data = CJSON::decode(implode('', file($file->getTempName())));
    }
    catch (Exception $e)
    {
      return false;
    }
    
    if(!$this->_md5($data, true))
    {
      return false;
    }
    
    // data are valid, we can proceed...

    // the following do not need to be in the transaction:    
    $this->_deletePosts();
    $this->_deleteReasons();
    $this->_deleteAccounts();
    
    $transaction = $this->getDbConnection()->beginTransaction();
    
    try
    {

      DELT::array2object($data['base'], $this, array('name', 'description', 'currency'));

      $language=Language::model()->findByLocale($data['base']['language']);
      $this->language_id = $language->id;
      
      $this->save(false);
     
      $references = array();
      foreach($data['accounts'] as $values)
      {
        $newaccount = new Account;
        $newaccount->firm_id = $this->id;
        DELT::array2object($values, $newaccount, array('code', 'textnames', 'nature', 'outstanding_balance', 'comment'));
        $newaccount->basicSave(false);
        $references[$values['code']]=$newaccount->id;
      }
      
      
      foreach($data['reasons'] as $values)
      {
        $newreason = new Reason;
        $newreason->firm_id = $this->id;
        DELT::array2object($values, $newreason, array('description'));

        $info=array();
        foreach($values['accounts'] as $id=>$value)
        {
          $info[$references[$id]]=$value;
        }
        
        $newreason->info=serialize($info);
        $newreason->save(false);
      }

      foreach($data['posts'] as $values)
      {
        $newpost = new Post;
        $newpost->firm_id = $this->id;
        DELT::array2object($values, $newpost, array('date', 'description', 'is_confirmed', 'is_closing', 'rank'));
        
        $newpost->save(false);
        
        foreach($values['debitcredits'] as $debitcredit)
        {
          $newdebitcredit = new Debitcredit;
          $newdebitcredit->post_id = $newpost->id;
          DELT::array2object($debitcredit, $newdebitcredit, array('amount', 'rank'));
          $newdebitcredit->account_id = $references[$debitcredit['account_code']];
          $newdebitcredit->save(false);
        }
      }
      
      $transaction->commit();
      $this->fixAccounts();
      $this->fixAccountNames();
      
      return true;
    }
    catch (Exception $e)
    {
      $this->addError('description', $e->getMessage());
      return false;
    }
  }
  
  /**
   * Returns or checks the md5sum of json-encoded data from an array.
   * @param data array the data to checksum
   * @param check boolean whether to return the checksum or the validation
   * @return mixed the result
   */
  private function _md5($data, $check=false)
  {
    $export_info=Yii::app()->params['export'];
    
    if($check and (!isset($export_info['check_on_import']) or $export_info['check_on_import']==false))
    {
      return true;
    }
    
    $key = isset($export_info['key']) ? $export_info['key'] : '';

    $md5 = md5(CJSON::encode($data['base'] . $data['accounts'] . $data['reasons'] . $data['posts'] . $data['meta'] . $key));
    
    return $check ? $md5 == $data['md5sum'] : $md5;
    
  }
  
  private function _deletePosts()
  {
    foreach($this->posts as $post)
    {
      $post->safeDelete();
    }
  }

  private function _deleteReasons()
  {
    Reason::model()->deleteAllByAttributes(array('firm_id'=>$this->id));
  }

  private function _deleteUsers()
  {
    FirmUser::model()->deleteAllByAttributes(array('firm_id'=>$this->id));
  }

  private function _deleteAccounts()
  {
    $account_ids = Yii::app()->db->createCommand()
      ->select('id')
      ->from('{{account}}')
      ->where('firm_id = :id', array(':id'=>$this->id))
      ->queryColumn();
    
    AccountName::model()->deleteAllByAttributes(array('account_id'=>$account_ids));
    Account::model()->deleteAllByAttributes(array('firm_id'=>$this->id));
  }

  public function getFinancialStatement($level=1)
  {
    return $this->_getStatement('P', $level);
  }
  
  public function getEconomicStatement($level=1)  // aka Profit and Loss statement
  {
    return $this->_getStatement('E', $level);
  }
  
  private function _getStatement($nature, $level=1)
  {
    $accounts = Yii::app()->db->createCommand()
      ->select('id, code, level, name, is_selectable')
      ->from('{{account}}')
      ->leftJoin('{{account_name}} n', 'n.account_id = id AND n.language_id=:language_id', array(':language_id'=>$this->language_id))
      ->where('firm_id=:id', array(':id'=>$this->id))
      ->andWhere('nature = :nature', array(':nature'=>$nature))
      ->andWhere('level <= :level', array(':level'=>$level))
      ->order('rcode')
      ->queryAll();
    
    foreach($accounts as $key=>&$item)
    {
      $account=Account::model()->findByPk($item['id']);
      $item['amount']=$account->getConsolidatedBalance(true);
      
      if($item['amount'] == 0)
      {
        unset($accounts[$key]);  // we remove items that yeld a zero value...
      }
      
      if($nature=='P')
      {
        $ob = ($ancestor=$account->firstAncestor) ? $ancestor->outstanding_balance : $account->outstanding_balance;
        $item['type']= $ob=='D' ? '+': '-';
      }
      
      if($nature=='E')
      {
        $item['type'] = '+';
        $item['amount'] = -$item['amount'];
      }
      
    }
    
    return $accounts;
  }
  
  public function safeDelete()
  {
    $this->_deletePosts();
    $this->_deleteReasons();
    $this->_deleteAccounts();
    $this->_deleteUsers();

    $transaction = $this->getDbConnection()->beginTransaction();
    try
    {
      $this->delete();
      $transaction->commit();
      return true;
    }
    catch (Exception $e)
    {
      $transaction->rollback();
      Yii::app()->getUser()->setFlash('delt_failure', $e->getMessage());
      return false;
    }
  }


}
