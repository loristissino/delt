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
 * @property Language $language
 * @property integer $firm_parent_id
 * @property string $create_date
 *
 * The followings are the available model relations:
 * @property Account[] $accounts
 * @property Users[] $tblUsers
 * @property Journalentry[] $journalentries
 * @property Template[] $templates
 * @property Language[] $languages
 *
 */

class Firm extends CActiveRecord
{

  // negative values for firms that we do not want to show
  const STATUS_DELETED = -3; 
  const STATUS_SUSPENDED = -4;
  
  // positive values for firms that we do want to show
  const STATUS_SYSTEM = 1;
  const STATUS_PRIVATE = 2;
  
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
      'languages' => array(self::MANY_MANY, 'Language', '{{firm_language}}(firm_id, language_id)', 'order'=>'language_code, country_code ASC'),
      'journalentries' => array(self::HAS_MANY, 'Journalentry', 'firm_id', 'order'=>'journalentries.date ASC'),
      'templates' => array(self::HAS_MANY, 'Template', 'firm_id', 'order'=>'templates.description ASC'),
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
      'languages' => Yii::t('delt', 'Additional languages'),
      'firm_parent_id' => Yii::t('delt', 'Parent firm'),
      'create_date' => Yii::t('delt', 'Create Date'),
      'license'=>Yii::t('delt', 'License'),
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
      'pagination'=>array('pageSize'=>100),
    ));
  }

  /**
   * @return string a textual description of the firm
   */
  public function __toString()
  {
    return $this->name;
  }
  
  public function getAllOwnersExcept($user_id)
  {
    // FIXME: merge / integrate with Firm::getOwners()
    
    $users = Yii::app()->db->createCommand()
      ->select('u.username, p.first_name, p.last_name')
      ->from('{{users}} u')
      ->leftJoin('{{firm_user}} fu', 'id = fu.user_id')
      ->leftJoin('{{profiles}} p', 'u.id = p.user_id')
      ->where('fu.firm_id=:id', array(':id'=>$this->id))
      ->andWhere('fu.role=:role', array(':role'=>'O'))
      ->andWhere('not fu.user_id = :user', array(':user'=>$user_id))
      ->order('p.last_name')
      ->setFetchMode(PDO::FETCH_OBJ)
      ->queryAll();
      
    return $users;    
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
  
  public function getAncestors()
  {
    $ancestors = array();
    $firm = $this;
    while($firm = $firm->getParent())
    {
      array_push($ancestors, $firm);
    }
    return $ancestors;
  }
  
  public function isDescendantOf($firm)
  {
    foreach($this->ancestors as $ancestor)
    {
      if($ancestor->id == $firm->id)
      {
        return true;
      }
    }
    return false;
  }
  
  
  public function findDifferentAccounts(Firm $firm)
  {
    $own     = array();
    $new     = array();
    $changes = array();
    foreach($this->accounts as $account)
    {
      $own[$account->code]=$account;
    }
    foreach($firm->accounts as $account)
    {
      if(!in_array($account->code, array_keys($own)))
      {
        $new[]=$account;
      }
      else
      {
        if($differences = DELT::compareObjects($account, $own[$account->code], array('comment', 'position', 'outstanding_balance', 'textnames')))
        {
          $changes[] = array('account'=>$account, 'differences'=>$differences);
        }
      }
    }
    return array('new'=>$new, 'changes'=>$changes);
    
  }
  
  public function synchronizeAccounts($postdata)
  {
    $transaction = $this->getDbConnection()->beginTransaction();
    
    try
    {
      if(isset($postdata['newaccounts']))
      {
        foreach($postdata['newaccounts'] as $id)
        {
          $account = Account::model()->findByPk($id);
          $newaccount = new Account;
          DELT::object2object($account, $newaccount, array('code', 'textnames', 'position', 'outstanding_balance','comment'));
          $newaccount->firm_id = $this->id;
          $newaccount->basicSave(false);
        }
      }
      
      if(isset($postdata['changedaccounts']))
      {
        foreach($postdata['changedaccounts'] as $id)
        {
          $account = Account::model()->findByPk($id);
          $oldaccount = Account::model()->findByAttributes(array('firm_id'=>$this->id, 'code'=>$account->code));
          DELT::object2object($account, $oldaccount, array('code', 'textnames', 'position', 'outstanding_balance','comment'));
          $oldaccount->basicSave(false);
        }
      }
      
      $transaction->commit();
      return true;
    }
    catch(Exception $e)
    {
      $transaction->rollBack();
      die($e->getMessage());
      return false;
    }
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
    $fu=FirmUser::model()->findByAttributes(array('firm_id'=>$this->id, 'user_id'=>$user->id, 'role'=>'O'));
    return sizeof($fu) > 0;
  }
  
  public function getOwners($as_text=false)
  {
    // FIXME This should be done with one query, I must study how the model from the plugin works...
    
    $rows = FirmUser::model()->findAllByAttributes(array('firm_id'=>$this->id, 'role'=>'O'));
    
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
          $text=$profile->first_name . ' ' . $profile->last_name;
          if($text==' ')
          {
            $text='[Incognito user]';
          }
          $lines[]=ltrim(rtrim($text));
        }
      }
           
      return implode(', ', $lines);
    }
    
    return $rows;
  }
  
  public function invite($username)
  {
    if(!$user=DEUser::model()->findByAttributes(array('username'=>$username)))
    {
      return false;
    }
    
    $rows = FirmUser::model()->findAllByAttributes(array('firm_id'=>$this->id, 'user_id'=>$user->id, 'role'=>'I'));
    if(sizeof($rows))
    {
      return true; // already invited... we could send an email here...
    }
    else
    {
      try
      {
        $fu = new FirmUser();
        $fu->firm_id = $this->id;
        $fu->user_id = $user->id;
        $fu->role = 'I';
        $fu->save();
        return true;
      }
      catch (Exception $e)
      {
        return false;
      }
    }
    
  }

  public function getAccountsAsDataProvider($pagesize=5000)
  {
    $sort = new CSort;
    $sort->defaultOrder = 'code ASC';
    $sort->attributes = array(
        'code'=>'code',
        'name'=>'currentname',
        'position'=>'position',
    );    
    
    return new CActiveDataProvider(Account::model()->with('firm')->belongingTo($this->id), array(
      'pagination'=>array(
          'pageSize'=>$pagesize,
          ),
      'sort'=>$sort,
      )
    );
  }
  
  public function getAccountBalancesAsDataProvider($pagesize=5000)
  {
    return new CActiveDataProvider(Account::model()->with('firm')->belongingTo($this->id), array(
      'criteria'=>array(
          'condition'=>'is_selectable = 1 and journalentries.is_included = 1',
          'order' => 'code ASC',
          'with'=>array(
            'postings'=>array(
              'on'=>'t.id = postings.account_id',
              'together'=>true,
              'joinType' => 'INNER JOIN',
              ),
            'journalentries'=>array(
              'on'=>'postings.journalentry_id = journalentries.id',
              'together'=>true,
              'joinType' => 'INNER JOIN',
              )
            ),
        ),
      'pagination'=>array(
          'pageSize'=>$pagesize,
          ),
      )
    );
  }
  
  public function getAccountBalancesData($position='', $ids=array())
  {
    // FIXME -- we should have an array parameter instead of this...
    $positions=array($position);
    if($position=='P')
    {
      $positions[]='r';
    }
    if($position=='')
    {
      $positions=array('P', 'r', 'E', 'e', 'M');
    }
    
    $accounts = Yii::app()->db->createCommand()
      ->select('SUM(amount) as total, a.code as code, a.currentname as name')
      ->from('{{posting}}')
      ->leftJoin('{{account}} a', 'account_id = a.id')
      ->leftJoin('{{journalentry}} p', 'journalentry_id = p.id')
      ->where('p.firm_id=:id', array(':id'=>$this->id))
      ->andWhere(array('in', 'position', $positions))
      ->andWhere('p.is_included = 1')
      ->andWhere(sizeof($ids)? array('in', 'a.id', $ids) : ' TRUE')
      ->order('a.code')
      ->group('a.code, a.currentname')
      ->having('total <> 0')
      ->queryAll();
      //->text;
    //print_r($accounts);
    //die();
    return $accounts;

  }
  
  
  public function getAccountBalances($position='', $ids=array(), $reverse=true)
  {
    
    $result=array();
    $accounts = $this->getAccountBalancesData($position, $ids);
    
    $grandtotal=0;  
    foreach($accounts as $account)
    {
      if(!$reverse)
      {
        $account['total']=-$account['total'];
      }
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
    if($position=='e')
    {
      $ob=$grandtotal>0 ? 'D': 'C';
      
      $resultAccounts = Account::model()->findAllByAttributes(array('firm_id'=>$this->id, 'position'=>'r', 'outstanding_balance'=>$ob));
      if(sizeof($resultAccounts)==1)
      {
        $account=$resultAccounts[0];
        $name=$account['code'] . ' - ' . $account['name'];
      }
      else
      {
        $name = Yii::t('delt', $grandtotal<0 ? 'Select account of profit destination': 'Select account of loss destination');
      }
      
      $result[]=array(
        'name'=>$name,
        'debit'=>($grandtotal>0) ? DELT::currency_value($grandtotal, $this->currency) : '',
        'credit'=>($grandtotal<0) ? DELT::currency_value(-$grandtotal, $this->currency) : '',
        );
    }
    elseif($grandtotal!=0)
    {
      $closingaccounts=Account::model()->findAllByAttributes(array('firm_id'=>$this->id, 'position'=>strtolower($position), 'is_selectable'=>true));
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
  
  public function getJournalentriesAsDataProvider($size=100)
  {
    return new CActiveDataProvider(Posting::model()->with('journalentry')->with('account')->ofFirm($this->id), array(
      'pagination'=>array(
          'pageSize'=>$size,
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
        if($a[$child_id]['model']->position!='r')
        {
          $a[$child_id]['model']->position = $info['model']->position;
        }
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
      ->select('code, outstanding_balance, currentname')
      ->from('{{account}}')
//DELTOD      ->leftJoin('{{account_name}} n', 'n.account_id = id AND n.language_id=:language_id', array(':language_id'=>$this->language_id))
      ->where('firm_id=:id', array(':id'=>$this->id))
      ->andWhere(array('or', 
        array('like', 'code', '%' . $term . '%'),
        array('like', 'currentname', '%' . $term . '%')
        ))
      ->andWhere('is_selectable = 1')
      ->order('code')
      ->queryAll();
    
    $result=array();
    foreach($accounts as $account)
    {
      $result[]=$account['code']. ' - ' . $account['currentname'];
    }
    return $result;
  }
  
  
  public function getTotalAmounts($type='D')
  {
    $amount = Yii::app()->db->createCommand()
      ->select('SUM(amount) as total')
      ->from('{{posting}}')
      ->leftJoin('{{journalentry}} p', 'journalentry_id = p.id')
      ->where('p.firm_id=:id', array(':id'=>$this->id))
      ->andWhere('amount ' . $type='D'? '>0' : '<0')
      ->andWhere('p.is_included = 1')
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
    
    foreach(array('language_id','currency','csymbol','description') as $property)
    {
      $this->$property = $source->$property;
    }
    $this->status = self::STATUS_PRIVATE;
    $this->firm_parent_id = $source->id;

    $slug=Yii::t('delt', 'copy-of-{slug}', array('{slug}'=>$source->slug));
    $testsubstr=substr($slug, 0, 28);
    
    $number = $this->countFirmsWithSlugStartingWith($testsubstr);
    if($number>0)
    {
      $this->slug=$testsubstr . '-' . ++$number;
    }
    else
    {
      $this->slug=$slug;
    }
    
    if(Firm::model()->findByAttributes(array('slug'=>$this->slug)))
    {
      $this->slug = md5(rand()+mktime());
    }

    $transaction = $this->getDbConnection()->beginTransaction();
    
    try
    {
      $this->save(false);
            
      $fu = new FirmUser();
      $fu->firm_id=$this->id;
      $fu->user_id=$user->id;
      $fu->role='O';
      $fu->save(false);
      
      foreach($source->languages as $language)
      {
         $fl=new FirmLanguage();
         $fl->firm_id = $this->id;
         $fl->language_id = $language->id;
         $fl->save();
      }
      

      $references = array();  // this will keep references between the old and the new codes of accounts

      foreach($source->accounts as $account)
      {
        $newaccount = new Account;
        $newaccount->firm_id = $this->id;
        $newaccount->firm = $this;
        $newaccount->account_parent_id = null;
        
        foreach(array('code', 'level', 'position', 'is_selectable', 'outstanding_balance', 'number_of_children', 'textnames') as $property)
        {
          $newaccount->$property = $account->$property ? $account->$property : '1';
        }
        $newaccount->comment = $account->comment;
        $newaccount->basicSave(false);
        
        $references[$account->id]=$newaccount->id;
      }
      
      if(substr($type, 1, 1)=='1')
      {
        // we must fork templates...
        foreach($source->templates as $template)
        {
          $newtemplate = new Template;
          $newtemplate->firm_id = $this->id;
          foreach(array('description') as $property)
          {
            $newtemplate->$property = $template->$property;
          }
          $info=array();
          foreach(unserialize($template->info) as $id=>$value)
          {
            $info[$references[$id]]=$value;
          }
          $newtemplate->info=serialize($info);
          $newtemplate->save(false);
        }
      }
      
      if(substr($type, 2, 1)=='1')
      {
        // we must fork journalentries...
        foreach($source->journalentries as $journalentry)
        {
          $newjournalentry = new Journalentry;
          $newjournalentry->firm_id = $this->id;
          foreach(array('date', 'description', 'is_confirmed', 'is_closing', 'is_adjustment', 'is_included', 'rank') as $property)
          {
            $newjournalentry->$property = $journalentry->$property;
          }
          $newjournalentry->save(false);
          foreach($journalentry->postings as $posting)
          {
            $newposting = new Posting;
            $newposting->journalentry_id = $newjournalentry->id;
            foreach(array('amount', 'rank') as $property)
            {
              $newposting->$property = $posting->$property;
            }
            $newposting->account_id = $references[$posting->account_id];
            $newposting->save(false);
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
      echo $e->getMessage();
      die();
      return false;
    }
    
  }
  
  public function isForkableBy(DEUser $user)
  {
    if($this->status>0)
      return true;
    
    return false;
  }
  
  public function saveWithOwner(DEUser $user)
  {
    $transaction = $this->getDbConnection()->beginTransaction();
    
    try
    {
      $this->status = self::STATUS_PRIVATE;
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
      die($e->getMessage());
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
    $text = Yii::t('delt', '<a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}" target="_blank"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-sa/3.0/88x31.png" width="88" height="31" /></a><br />
<span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">«{firmname}»</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="{author_url}" property="cc:attributionName" rel="cc:attributionURL">{author_name}</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-sa/3.0/deed.{locale}" target="_blank">Creative Commons Attribution-ShareAlike 3.0 Unported License</a>.'
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
     *   110 -- accounts and templates
     *   111 -- accounts, templates, journalentries
     */
    
    $data=array();
    
    DELT::object2array($this, $data['base'], array('name', 'description', 'currency'));

    $data['base']['language'] = $this->language->locale;
    
    foreach($this->languages as $language)
    {
      $data['base']['languages'][] = $language->locale;
    }
    
    $references = array();
    foreach($this->accounts as $account)
    {
      $values=array();
      DELT::object2array($account, $values, array('code', 'textnames', 'position', 'outstanding_balance', 'comment'));
      $data['accounts'][]=$values;
      $references[$account->id]=$account->code;
    }
    
    $data['templates']=array();
    if(substr($type, 1, 1)=='1')
    {
      // we must export templates...
      foreach($this->templates as $template)
      {
        $values=array();
        DELT::object2array($template, $values, array('description'));
        $info=array();
        foreach(unserialize($template->info) as $id=>$value)
        {
          $info[$references[$id]]=$value;
        }
        $values['accounts']=$info;
        $data['templates'][]=$values;
      }
    }
    
    $data['journalentries'] = array();
    if(substr($type, 2, 1)=='1')
    {
      // we must export journalentries...
      foreach($this->journalentries as $journalentry)
      {
        $values = array();
        DELT::object2array($journalentry, $values, array('date', 'description', 'is_confirmed', 'is_closing', 'is_adjustment', 'is_included', 'rank'));
        
        foreach($journalentry->postings as $posting)
        {
          $info = array();
          DELT::object2array($posting, $info, array('amount', 'rank'));
          $info['account_code'] = $references[$posting->account_id];
          $values['postings'][]=$info;
        }

        $data['journalentries'][]=$values;
      }
    }
    else
    {
      $data['journalentries']=array();
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
    $this->_deleteLanguages();    
    $this->_deleteJournalentries();
    $this->_deleteTemplates();
    $this->_deleteAccounts();
    
    $transaction = $this->getDbConnection()->beginTransaction();
    
    try
    {

      DELT::array2object($data['base'], $this, array('name', 'description', 'currency', 'language', 'languages'));

      $languages = array();
      $language=Language::model()->findByLocale($data['base']['language']);
      
      if($language)
      {
        $this->language_id = $language->id;
        $languages = array($language->locale => $language->id);
      }
      $this->save(false);
      
      foreach($data['base']['languages'] as $locale)
      {
        if($language=Language::model()->findByLocale($locale))
        {
          $languages[$language->locale]= $language->id;
        }
      }
      
      
      // FIXME -- we should have a addlanguages function
      foreach($languages as $id)
      {
        $fl = new FirmLanguage();
        $fl->firm_id = $this->id;
        $fl->language_id = $id;
        $fl->save();
      }
     
      $references = array();
      foreach($data['accounts'] as $values)
      {
        $newaccount = new Account;
        $newaccount->firm_id = $this->id;
        DELT::array2object($values, $newaccount, array('code', 'textnames', 'position', 'outstanding_balance', 'comment'));
        $newaccount->basicSave(false);
        $references[$values['code']]=$newaccount->id;
      }
      
      
      foreach($data['templates'] as $values)
      {
        $newtemplate = new Template;
        $newtemplate->firm_id = $this->id;
        DELT::array2object($values, $newtemplate, array('description'));

        $info=array();
        foreach($values['accounts'] as $id=>$value)
        {
          $info[$references[$id]]=$value;
        }
        
        $newtemplate->info=serialize($info);
        $newtemplate->save(false);
      }

      foreach($data['journalentries'] as $values)
      {
        $newjournalentry = new Journalentry;
        $newjournalentry->firm_id = $this->id;
        DELT::array2object($values, $newjournalentry, array('date', 'description', 'is_confirmed', 'is_closing', 'is_adjustment', 'is_included', 'rank'));
        
        $newjournalentry->save(false);
        
        foreach($values['postings'] as $posting)
        {
          $newposting = new Posting;
          $newposting->journalentry_id = $newjournalentry->id;
          DELT::array2object($posting, $newposting, array('amount', 'rank'));
          $newposting->account_id = $references[$posting['account_code']];
          $newposting->save(false);
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

    $md5 = md5(CJSON::encode($data['base'] . $data['accounts'] . $data['templates'] . $data['journalentries'] . $data['meta'] . $key));
    
    return $check ? $md5 == $data['md5sum'] : $md5;
    
  }
  
  private function _deleteJournalentries()
  {
    foreach($this->journalentries as $journalentry)
    {
      $journalentry->safeDelete();
    }
  }
  
  public function deleteSelectedJournalentries($ids=array())
  {
    $journalentries=$this->_findJournalentries($ids);
    $number=sizeof($journalentries);
    foreach($journalentries as $journalentry)
    {
      $journalentry->safeDelete();
    }
    return $number;
  }
  
  private function _findJournalentries($ids=array())
  {
    
    $criteria = new CDbCriteria();
    $criteria->condition = 'firm_id = :firm_id';
    $criteria->params = array(':firm_id'=>$this->id);
    $criteria->addInCondition('id',$ids);
    
    return Journalentry::model()->findAll($criteria);
  }
  
  
  private function _deleteLanguages()
  {
    FirmLanguage::model()->deleteAllByAttributes(array('firm_id'=>$this->id));
  }

  private function _deleteTemplates()
  {
    Template::model()->deleteAllByAttributes(array('firm_id'=>$this->id));
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

  public function getBalanceSheet($level=1)
  {
    return $this->_getStatement('P', $level);
  }
  
  public function getIncomeStatement($level=1)  // aka Profit and Loss statement
  {
    return $this->_getStatement('E', $level);
  }
  
  private function _getStatement($position, $level=1)
  {
    $positions=array($position);
    if($position=='P')
    {
      $positions[]='r';
    }

    $accounts = Yii::app()->db->createCommand()
      ->select('id, code, level, currentname as name, is_selectable')
      ->from('{{account}}')
      ->where('firm_id=:id', array(':id'=>$this->id))
      ->andWhere(array('in', 'position', $positions))
      ->andWhere('level <= :level', array(':level'=>$level))
      ->order('rcode')
      ->queryAll();
    
    foreach($accounts as $key=>&$item)
    {
      $account=Account::model()->findByPk($item['id']);
      
      if(($pos=mb_strpos($item['name'], '—')) !== false)
      {
        $item['name']=mb_substr($item['name'], 0, $pos); 
      }
      
      
      $item['amount']=$account->getConsolidatedBalance(true);
      
      if($item['amount'] == 0)
      {
        unset($accounts[$key]);  // we remove items that yeld a zero value...
      }
      
      if($position=='P')
      {
        $ob = ($ancestor=$account->firstAncestor) ? $ancestor->outstanding_balance : $account->outstanding_balance;
        $item['type']= $ob=='D' ? '+': '-';
      }
      
      if($position=='E')
      {
        $item['type'] = '+';
        $item['amount'] = -$item['amount'];
      }
      
    }
    
    return $accounts;
  }
  
  public function softDelete()
  {
    $this->status=self::STATUS_DELETED;
    $this->save();
    return true;
  }
  
  public function safeDelete()
  {
    $this->_deleteJournalentries();
    $this->_deleteTemplates();
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
  
  public function clearJournal()
  {
    $this->_deleteJournalentries();
    return true;
  }
  
  public function createBangAccount($name)
  {
    $account = new Account();
    $account->currentname = $name;
    $account->firm_id = $this->id;
    $account->firm = $this;
    $account->position = '?';
    $account->outstanding_balance = '/';
    $account->id = '!' . md5($name . rand(0, 100000));
    return $account;
  }
  
  public function countBangAccounts()
  {
    $number = Yii::app()->db->createCommand()
      ->select('COUNT(*) as number')
      ->from('{{account}}')
      ->where('firm_id=:id', array(':id'=>$this->id))
      ->andWhere('code LIKE :code', array(':code'=>'!*'))
      ->queryScalar();
    return $number;
  }

  public function countFirmsWithSlugStartingWith($text)
  {
    $number = Yii::app()->db->createCommand()
      ->select('COUNT(*) as number')
      ->from('{{firm}}')
      ->where('slug LIKE :text', array(':text'=>$text.'%'))
      ->queryScalar();
    return $number;
  }

  public function saveLanguages($values=array())
  {
    if(!in_array($this->language_id, $values))
    {
      $values[]=$this->language_id;
    }
    
    $old=array();
    foreach($this->languages as $language)
    {
      $old[]=$language->id;
    }
    
    $inserts=array_diff($values, $old);    
    $deletes=array_diff($old, $values);
    foreach($inserts as $id)
    {
      $fl=new FirmLanguage();
      $fl->firm_id = $this->id;
      $fl->language_id = $id;
      $fl->save();
    }
    
    foreach($deletes as $id)
    {
      //FIXME how to make a single query?
      FirmLanguage::model()->deleteByPk(array('firm_id'=>$this->id, 'language_id'=>$id));
    }
    
  }
  
  public function getCoatree($controller, $id=null)
  {
    //FIXME -- we need an instance of controller so that we can use the createIcon function and other stuff -- this violates MVC, and should maybe fixed.
    
    $result=array();
    
    if($id)
    {
      $accounts=Account::model()->belongingTo($this->id)->childrenOf($id)->findAll();
    }
    else
    {
      $accounts=Account::model()->belongingTo($this->id)->ofLevel(1)->findAll();
    }
    
    foreach($accounts as $account)
    {
      // see http://www.yiiframework.com/doc/api/1.1/CTreeView#data-detail
      
      if($account->number_of_children==0)
      {
        $text = '<a href="#" onclick="chooseAccount(\'' . $account->__toString() . '\');">'. $account->currentname . '</a>';
      }
      else
      {
        $text = $account->currentname;
      }
      
      if($account->comment)
      {
        $text .= $controller->createIcon('comment', Yii::t('delt', 'Comment'), array('width'=>16, 'height'=>12, 'title'=>$account->comment));
      }
      
      $result[]=array(
        'text'=>$text, 
        'expanded'=>false,
        'id'=>$account->id,
        'hasChildren'=>$account->number_of_children>0
        );
    }
    return $result;
    
  }
  
  public function importAccountsFrom(IEAccountsForm $form)
  {
    $count = 0;
    
    $lines=explode("\n", $form->content);
    
    foreach($lines as $line)
    {
      $items=array();
      if(strpos($line, "\t")===false)
      {
        $name=trim($line);
      }
      else
      {
        $items=explode("\t", trim($line));
        $name=$items[0];
      }
      
      if($name)
      {
        $account = $this->createBangAccount(trim($name));
        $account->cleanup($this);
        
        if(sizeof($items))
        {
          if(isset($items[1]))  // the code
          {
            $account->code = $items[1];
          }
          if(isset($items[2]))  // position
          {
            $account->position = $items[2];
          }
          if(isset($items[3]))  // outstanding balance
          {
            $account->outstanding_balance = $items[3];
          }
        }
        
      }
      
      if(isset($account))
      {
        try
        {
          $account->basicSave(false) && $count++;
        }
        catch(Exception $e)
        {
          // we just silently ignore accounts that can't be imported
        }
        
        unset($account);
      }
      
    }
    
    
    return $count;
  }
  

}
