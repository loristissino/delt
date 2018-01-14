<?php
/**
 * Firm class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.0
 */
/**
 * Firm represents a single firm for which users practice accounting (or a single exercise).
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property integer $firmtype
 * @property string $description
 * @property integer $status
 * @property string $currency
 * @property string $csymbol
 * @property integer $language_id
 * @property Language $language
 * @property integer $firm_parent_id
 * @property string $create_date
 * @property string $banner
 * @property string $checked_positions
 * @property integer $shortcodes
 * @property string $css
 *
 * The followings are the available model relations:
 * @property Account[] $accounts
 * @property Users[] $tblUsers
 * @property Journalentry[] $journalentries
 * @property Template[] $templates
 * @property Language[] $languages
 * 
 * 
 * @package application.models
 * 
 */

class Firm extends CActiveRecord
{

  // negative values for firms that we do not want to show
  const STATUS_DELETED = -3; 
  const STATUS_SUSPENDED = -4;
  const STATUS_CLEARED = -5;
  
  // positive values for firms that we do want to show
  const STATUS_SYSTEM = 1;
  const STATUS_PRIVATE = 2;
  const STATUS_STALE = 3;
  
  // types of "firms"
  const FIRMTYPE_BUSINESS = 1;
  const FIRMTYPE_NPO = 2;
  
  public $license_confirmation;
  
  public $positions = null; 
  
  private $_cache = array();
  
  private $_tempfile; 
  
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
   * Returns the associated database table name. 
   * @return string the associated database table name
   */
  public function tableName()
  {
    return '{{firm}}';
  }
  
  /**
   * Returns the validation rules for model attributes.
   * @return array validation rules for model attributes
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
      array('slug', 'SlugValidator', 'model'=>Firm::model()),
      array('firmtype', 'validateFirmtype'),
      array('currency', 'validateCurrency'),
      array('license_confirmation', 'validateLicense'),
      array('banner', 'file', 
        'types'=>'png',
        'maxSize'=>131072, // 128 KiB
        'tooLarge'=>Yii::t('delt', 'The banner file was too large. Please upload a smaller file.'),
        'allowEmpty'=> true,
        ),
      array('checked_positions', 'safe'),
      array('shortcodes', 'safe'),
      array('css', 'validateCss'),

      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('id, name, slug, firmtype, description, status, currency, csymbol, language_id, firm_parent_id, create_date', 'safe', 'on'=>'search'),
    );
  }

  /**
   * Returns the relational rules.
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
   * Returns the customized attribute labels.
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => Yii::t('delt', 'ID'),
      'name' => Yii::t('delt', 'Name'),
      'slug' => Yii::t('delt', 'Slug'),
      'firmtype' => Yii::t('delt', 'Type'),
      'description' => Yii::t('delt', 'Description'),
      'status' => Yii::t('delt', 'Status'),
      'currency' => Yii::t('delt', 'Currency'),
      'csymbol' => Yii::t('delt', 'Currency symbol'),
      'language_id' => Yii::t('delt', 'Language'),
      'languages' => Yii::t('delt', 'Additional languages'),
      'firm_parent_id' => Yii::t('delt', 'Parent firm'),
      'create_date' => Yii::t('delt', 'Create Date'),
      'license'=>Yii::t('delt', 'License'),
      'checked_positions'=>Yii::t('delt', 'Checked Positions'),
      'shortcodes'=>Yii::t('delt', 'Short Codes'),
      'css'=>Yii::t('delt', 'CSS'),
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
    $criteria->compare('firmtype',$this->firmtype,true);
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
   * Returns a textual description of the firm.
   * @return string a textual description of the firm
   */
  public function __toString()
  {
    return $this->name;
  }

  
  /**
   * Returns the set of owners of the firm, excluding a specified one.
   * @param integer $user_id the id of the {@link DEUser} that must be ecluded
   * @return array the owners of the firm (as PDO objects)
   */
  public function getAllOwnersExcept($user_id, $extra_fields='')
  {
    // FIXME: merge / integrate with Firm::getOwners()
    
    $users = Yii::app()->db->createCommand()
      ->select('u.username, p.first_name, p.last_name' . $extra_fields)
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

  /**
   * Validates the type chosen for the firm.
   */
  public function validateFirmtype()
  {
    if(!array_key_exists($this->firmtype, $this->getValidFirmTypes()))
    {
      $this->addError('firmtype', Yii::t('delt', 'Not a valid type.'));
    }
  }

  /**
   * Validates the currency chosen for the firm.
   */
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

  /**
   * Validates the currency chosen for the firm.
   */
  public function validateCss()
  {
    if($this->css=='')
    {
      return;
    }
    if($this->css!=strip_tags($this->css))
    {
      $this->addError('css', Yii::t('delt', 'HTML tags are not allowed in the CSS code.'));
    }
  }

  
  /**
   * Validates the input by checking that the license has been accepted.
   */
  public function validateLicense()
  {
    if(!$this->id and !$this->license_confirmation)
    {
      $this->addError('license_confirmation', Yii::t('delt', 'You must confirm that you accept the license for the contents.'));
    }
  }

  /**
   * Returns the parent firm.
   * @return Firm the parent firm.
   */
  public function getParent()
  {
    return Firm::model()->findByPk($this->firm_parent_id);
  }
  
  /**
   * Returns the ancestors of the firm.
   * @return array the ancestors of the firm.
   */
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
  
  /**
   * Checks if the firm is a descendant of a specified firm.
   * @param Firm $firm the firm to be checked against
   * @return boolean whether the firm is a descendant of the specified firm.
   */
  public function isDescendantOf(Firm $firm)
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
  
  
  /**
   * Finds the accounts from the specified firm that differ from the current one.
   * @param Firm $firm the firm to be checked against
   * @return array an array of new accounts and of differences.
   */
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
  
  /**
   * Synchronizes the accounts of the firm using the data posted by the user.
   * @param array $postdata the data posted by the end user with the form
   * @return boolean whether the operation was successful.
   */
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
      //die($e->getMessage());
      return false;
    }
  }
  
  /**
   * Checks if the firm is manageable by a specified user.
   * @param DEUser $user the user to check
   * @return boolean whether the firm is manageable by $user
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
  
  /**
   * Returns the owners of the firm.
   * @param boolean $as_text whether the value must be returned as text
   * @return mixed the owners
   */
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
          $lines[]=$profile->getFullName();
        }
      }
           
      return implode(', ', $lines);
    }
    
    return $rows;
  }

  /**
   * Invites a user to join the management of the firm.
   * @param string $username the username of the user to be invited
   * @return boolean whether the operation was successful
   */
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

  /**
   * Returns a data provider for the accounts of the firm.
   * @param integer $pagesize the pagesize desired
   * @return CActiveDataProvider the accounts of the firm
   */
  public function getAccountsAsDataProvider($hidden=0, $pagesize=5000)
  {
    $sort = new CSort;
    $sort->defaultOrder = 'code ASC';
    $sort->attributes = array(
        'code'=>'code',
        'name'=>'currentname',
        'position'=>'position',
    );    
    
    return new CActiveDataProvider(Account::model()->with('firm')->belongingTo($this->id)->hidden($hidden), array(
      'pagination'=>array(
          'pageSize'=>$pagesize,
          ),
      'sort'=>$sort,
      )
    );
  }
  
  /**
   * Returns a data provider for the account balances of the firm.
   * @param integer $pagesize the pagesize desired
   * @return CActiveDataProvider the account balances of the firm
   */
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
  
  public function getAccountBalancesData($position='', $ids=array(), $closing_entries_included=true)
  {
    $positions=array($position, strtolower($position));
    
    $accounts = Yii::app()->db->createCommand()
      ->select('SUM(amount) as total, a.code as code, a.currentname as name, a.id as id, a.classes as classes, p.subchoice as subchoice')
      ->from('{{posting}} p')
      ->leftJoin('{{account}} a', 'account_id = a.id')
      ->leftJoin('{{journalentry}} j', 'journalentry_id = j.id')
      ->where('j.firm_id=:id', array(':id'=>$this->id))
      ->andWhere($position==''? true : array('in', 'position', $positions))
      ->andWhere('j.is_included = 1')
      ->andWhere(sizeof($ids)? array('in', 'a.id', $ids) : ' TRUE')
      ->andWhere($closing_entries_included ? ' TRUE': 'j.is_closing = FALSE')
      ->order('a.code')
      ->group('a.code, a.currentname, a.id, p.subchoice')
      ->having('total <> 0')
      ->queryAll();
      
    return $accounts;

  }
  
  /**
   * Returns the account balances of the firm.
   * @param string $position the position of the account
   * @param array $ids the accounts to look for
   * @param boolean $reverse whether to return sorted in reverse order
   * @return array the account balances of the firm
   */
  public function getAccountBalances($position='', $ids=array(), $reverse=true)
  {
    $result=array();
    $count = 1;  // for compatibility with templates management, we need to start indexing from 1
    $accounts = $this->getAccountBalancesData($position, $ids);
    
    $grandtotal=0;  
    foreach($accounts as $account)
    {
      if(!$reverse)
      {
        $account['total']=-$account['total'];
      }
      $grandtotal+=$account['total'];
      $row=array('debitfromtemplate'=>false, 'creditfromtemplate'=>false);
      $row['name']=$this->renderAccountCodeAndName($account['code'], $account['name']);
      $row['id']=$account['id'];
      if($account['total'] > 0)
      {
        $row['debit']='';
        $row['credit']= $account['total'];
      }
      elseif($account['total'] < 0)
      {
        $row['debit']= -$account['total'];
        $row['credit']='';
      }
      else
      { // this should theoretically never happen...
        $row['debit']='';
        $row['credit']='';
      }
      $result[$count++]=$row;
    }
    
    if($grandtotal!=0)
    {
      $ob=$grandtotal>0 ? 'D': 'C';
      
      $closingAccount = $this->findClosingAccount($position, $ob);
      $name = $this->findClosingAccountName($closingAccount);
        
      $result[$count++]=array(
        'debitfromtemplate'=>false,
        'creditfromtemplate'=>false,
        'name'=>$name,
        'debit'=>($grandtotal>0) ? $grandtotal : 0,
        'credit'=>($grandtotal<0) ? -$grandtotal : 0,
        'id'=>($closingAccount) ? $closingAccount->id : null,
        );
        
        if(sizeof($result)==2 and $result[1]['name']==$name)   // we have already closed everything, this wouldn't make sense
        {
          return array();
        }
    }
    
    return $result;
  }
  

  public function findClosingAccount($position, $outstanding_balance)
  {      
      // first, we look for a specific one
      $closingAccounts = Account::model()->findAllByAttributes(array('firm_id'=>$this->id, 'position'=>strtolower($position), 'outstanding_balance'=>$outstanding_balance, 'type'=>0));
      if(sizeof($closingAccounts)==1)
      {
        return $closingAccounts[0];
      }
      else
      {
        // we relax, and look for a generic one that could fit
        $closingAccounts = Account::model()->findAllByAttributes(array('firm_id'=>$this->id, 'position'=>strtolower($position), 'number_of_children'=>0, 'type'=>0));
        if(sizeof($closingAccounts)==1)
        {
          return $closingAccounts[0];
        }
        else
        {
          // we haven't found anything specific
          return null;
        }
      }
  }

  public function findClosingAccountName(Account $account=null, $with_code=true, $default='')
  {
    if($account)
    {
      if($with_code)
      {
        return $this->renderAccountCodeAndName($account['code'], $account['name']);
      }
      else
      {
        return $account['name'];
      }
    }
    else
    {
      return $default;
    }
  }

  
  /**
   * Returns a data provider for the journal entries of the firm.
   * @param integer $pagesize the pagesize desired
   * @return CActiveDataProvider the postings related to the journal entries of the firm
   */
  public function getJournalentriesAsDataProvider($pagesize=1000)
  {
    return new CActiveDataProvider(Posting::model()->with('journalentry')->with('account')->ofFirm($this->id), array(
      'pagination'=>array(
          'pageSize'=>$pagesize,
          ),
      )
    );
  }

  /**
   * Returns a data provider for the journal entries of the firm, for a specific subchoice.
   * @param integer $pagesize the pagesize desired
   * @return CActiveDataProvider the postings related to the journal entries of the firm
   */
  public function getJournalentriesAsDataProviderForSubchoice($subchoice, $pagesize=1000)
  {
    return new CActiveDataProvider(Posting::model()->with('journalentry')->with('account')->ofFirm($this->id, '')->withSubchoice($subchoice), array(
      'pagination'=>array(
          'pageSize'=>$pagesize,
          ),
      )
    );
  }

  
  
  public function getJournalentriesData()
  {
    return Journalentry::model()->with('postings')->ofFirm($this->id)->findAll();
  }
  
  /**
   * Caches general ledger data.
   */
  public function cacheGeneralLedgerData()
  {
    $postings = Posting::model()->with('journalentry')->with('account')->included()->ofFirm($this->id, 'account.code ASC, journalentry.date ASC, journalentry.rank ASC')->findAll();
    
    $code = '';
    foreach($postings as $posting)
    {
      $this->_cache[$posting->account->code]['currentname']=$posting->account->currentname;
      $this->_cache[$posting->account->code]['id']=$posting->account_id;
      $this->_cache[$posting->account->code]['entries'][]=$posting;
      if(!isset($this->_cache[$posting->account->code]['totaldebit'])) $this->_cache[$posting->account->code]['totaldebit']=0;
      if(!isset($this->_cache[$posting->account->code]['totalcredit'])) $this->_cache[$posting->account->code]['totalcredit']=0;
      if($posting->amount>0) $this->_cache[$posting->account->code]['totaldebit']+=$posting->amount;
      if($posting->amount<0) $this->_cache[$posting->account->code]['totalcredit']+=-$posting->amount;
    }
  }
  
  public function getLedgerDataCache()
  {
    return $this->_cache;
  }
  
  public function getLedgerDataAsDataProvider($code)
  {
    $dataProvider=new CArrayDataProvider($this->_cache[$code]['entries'], array('id'=>'ledger', 'pagination'=>array(
        'pageSize'=>1000,
    )));
    return $dataProvider;
  }
  
  public function getLedgerDataTotalDebit($code)
  {
    return $this->_cache[$code]['totaldebit'];
  }

  public function getLedgerDataTotalCredit($code)
  {
    return $this->_cache[$code]['totalcredit'];
  }


  /**
   * Returns a data provider for the postings of a specified account of the firm.
   * @param integer $pagesize the pagesize desired
   * @return CActiveDataProvider the postings related to the journal entries of the firm of one specified account
   */
  public function getAccountPostingsAsDataProvider($account_id, $pagesize=100)
  {
    return new CActiveDataProvider(Posting::model()->with('journalentry')->with('account')->ofFirm($this->id)->ofAccount($account_id), array(
      'pagination'=>array(
          'pageSize'=>$pagesize,
          ),
      )
    );
  }
  
  
  
  /**
   * Fixes the accounts of the firm.
   * @return boolean whether the operation was successful
   */
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
        'parent_id'=>null,
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
      $info['model']->number_of_children = sizeof($info['children']);
      $info['model']->is_selectable = $info['model']->number_of_children==0;
      // an account is selectable when it has no children
      
      if($info['parent_id'] and isset($a[$info['parent_id']]))
      {
        $info['model']->setClassesFromComment($a[$info['parent_id']]['model']);
        
      }
      else
      {
        $info['model']->setClassesFromComment();
      }
    }

    
    foreach($a as $id=>$info)
    {
      foreach($info['children'] as $child_id)
      {
        if($a[$child_id]['model']->number_of_children>0)
        {
          $a[$child_id]['model']->position=$info['model']->position;
        }
        elseif(strtoupper($a[$child_id]['model']->position)!=$info['model']->position)
        {
          $a[$child_id]['model']->position = DELT::islowercase($a[$child_id]['model']->position) ? strtolower($info['model']->position): $info['model']->position;
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

  /**
   * Fixes the account names.
   * 
   * This is useful after firm forking (this way we get the i18n names)
   */
  public function fixAccountNames()
  {
    foreach($this->accounts as $account)
    {
      $account->save();
    }
  }
  
  /**
   * Compares two accounts by level.
   * @return integer -1, 0 or 1, according to the result of the comparison.
   */
  private function _compareAccountsByLevel($a, $b)
  {
    if($a['model']->level == $b['model']->level)
    {
      return 0;
    }
    return $a['model']->level < $b['model']->level ? -1: 1;
  }
  
  /**
   * Checks if the firm is manageable by a specified user.
   * @param integer $user_id the id of the {@link DEUser} to be chechek against
   * @return boolean whether the firm is manageable by the specified user
   */
  public function manageableBy($user_id)
  {
    $this->getDbCriteria()->mergeWith(array(
        'with'=>'tblUsers',
        'condition'=>'user.id = ' . $user_id,
    ));
    return $this;
  }
  
   /**
   * Retrieves the accounts that match a specified term in the code or in the name.
   * @param string $term the term to be cheched against
   * @return array the strings (code + name) of the accounts found
   */
  public function findAccounts($term)
  {
    $accounts = Yii::app()->db->createCommand()
      ->select('code, outstanding_balance, currentname, subchoices')
      ->from('{{account}}')
      ->where('firm_id=:id', array(':id'=>$this->id))
      ->andWhere(array('or', 
        array('like', 'code', '%' . $term . '%'),
        array('like', 'currentname', '%' . $term . '%')
        ))
      ->andWhere('is_selectable = 1')
      ->andWhere('type = 0')
      ->order('code')
      ->queryAll();
    
    $result=array();
    foreach($accounts as $account)
    {
      $result[]=$this->renderAccountCode($account['code']). ' - ' . $account['currentname'] . Account::getPostfix($account['subchoices']);
    }
    return $result;
  }
  
   /**
   * Returns the total amounts of postings.
   * @param string $type 'D' for debit values, 'C' for credit values
   * @return decimal the total amount
   */
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

  /**
  * Returns the max level of accounts for the firm.
  * @return integer the level
  */
  public function getCOAMaxLevel()
  {
    $level = Yii::app()->db->createCommand()
      ->select('MAX(level) as level')
      ->from('{{account}}')
      ->where('firm_id=:id', array(':id'=>$this->id))
      ->andWhere('type=0')
      ->queryScalar();
    return $level;
  }
  
  /**
  * Returns the firms that can be forked.
  * @return array the forkable firms
  */
  public function findForkableFirms()
  {
    return Firm::model()->findAllByAttributes(array('status'=>1));
  }
  
  public function getCopiedNameFrom($source)
  {
    return Yii::t('delt', 'Copy of "{name}"', array('{name}'=>$source->name));
  }

  /**
  * Forks a firm and sets a user as its owner.
  * @param Firm $source the firm to be forked from
  * @param DEUser $user the owner
  * @param integer $type the kind of forking required 
  * @return boolean whether the operation was successful
  */
  public function forkFrom(Firm $source, DEUser $user, $type, $name='')
  {
    $this->name= $name ? $name : $this->getCopiedNameFrom($source);
    
    DELT::object2object($source, $this, array('language_id','currency','csymbol','description','firmtype', 'checked_positions', 'shortcodes', 'css'));
    
    $this->status = self::STATUS_PRIVATE;
    $this->firm_parent_id = $source->id;
    
    $this->slug = substr(md5($this->name . rand(0, 100000)), 0, 32);
    
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
        $newaccount->type = $account->type;
        
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
          foreach(array('description', 'automatic') as $property)
          {
            $newtemplate->$property = $template->$property;
          }
          $info=array();
          foreach(unserialize($template->info) as $id=>$value)
          {
            if(isset($references[$id]))
            {
              $info[$references[$id]]=$value;
            }
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
            foreach(array('amount', 'rank', 'comment') as $property)
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
  
  /**
  * Checks if the firm is forkable by a specified user.
  * @param DEUser $user the user
  * @return boolean whether the firm is forkable
  */
  public function isForkableBy(DEUser $user)
  {
    if($this->status>0)
      return true;
    
    return false;
  }
  
  
  /**
  * Saves the firm and sets its owner.
  * @param DEUser $user the user
  * @return boolean whether the operation was successful
  */
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

  /**
  * Returns the HTML code used to inform about the license.
  * @param Controller $controller the controller
  * @return string the HTML code
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
    
    DELT::object2array($this, $data['base'], array('name', 'description', 'firmtype', 'currency', 'checked_positions', 'shortcodes', 'css'));

    $data['base']['language'] = $this->language->locale;
    
    foreach($this->languages as $language)
    {
      $data['base']['languages'][] = $language->locale;
    }
    
    $references = array();
    foreach($this->accounts as $account)
    {
      $values=array();
      DELT::object2array($account, $values, array('code', 'textnames', 'type', 'position', 'outstanding_balance', 'comment'));
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
          DELT::object2array($posting, $info, array('amount', 'rank', 'comment', 'subchoice'));
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
  
  public function getSQLiteTempFile()
  {
	$this->_tempfile = tempnam("/tmp", "delt");
	$db = new PDO("sqlite:" . $this->_tempfile);
    
    DELT::data2Sqlite($db, 'account', $this->accounts, array(
        'id'=>'INTEGER PRIMARY KEY NOT NULL',
        'account_parent_id'=>'INTEGER',
        'type'=>'INTEGER',
        'level'=>'INTEGER',
        'code'=>'TEXT',
        'is_selectable'=>'INTEGER',
        'position'=>'TEXT',
        'outstanding_balance'=>'TEXT',
        'currentname'=>'TEXT',
        'number_of_children'=>'INTEGER',
        'classes'=>'TEXT'
    ));
 
    DELT::data2Sqlite($db, 'journalentry', $this->journalentries, array(
        'id'=>'INTEGER PRIMARY KEY NOT NULL',
        'date'=>'TEXT',
        'description'=>'TEXT',
        'is_confirmed'=>'INTEGER',
        'is_closing'=>'INTEGER',
        'is_adjustment'=>'INTEGER',
        'is_included'=>'INTEGER',
        'rank'=>'INTEGER',
    ));

    DELT::data2Sqlite($db, 'posting', Posting::model()->ofFirm($this->id)->with('journalentry')->findAll(), array(
        'id'=>'INTEGER PRIMARY KEY NOT NULL',
        'account_id'=>'INTEGER',
        'journalentry_id'=>'INTEGER',
        'amount'=>'REAL',
        'rank'=>'INTEGER',
        'comment'=>'TEXT',
        'subchoice'=>'TEXT',
    ));

    return $this->_tempfile;
  }

  public function deleteSQLiteTempFile()
  {
    try
    {
       unlink($this->_tempfile);
    }
    catch(Exception $e)
    {
       // nothing special to do... 
    }
  }
  
  /**
   * Returns a multiline string with the journal's transaction in a 
   * format compatible with ledger-cli program.
   * @return array the journal in ledger-cli's format
   */  
  public function getLedgerFormatJournal()
  {
    $data=array(
      '; This is the format of journal entries used by ledger-cli program.',
      '; You can read more about ledger-cli at http://www.ledger-cli.org/.',
      '',
    );
    
    $count = 0;
    
    foreach($this->journalentries as $journalentry)
    {
      $beginwith = $journalentry->is_included ? '' : '; ';
      $data[] .= $beginwith . '; ' . $journalentry->description;
      $line = $beginwith . $journalentry->date . ' ';
      if(!$journalentry->is_closing)
      {
        $line .= '*';
      }
      
      $line .= ' ; Entry: ' . ++$count; 
      
      $data[] = $line;
      
      $references = array(); // used to cache accounts' data
      
      foreach($journalentry->postings as $posting)
      {
        if (!array_key_exists($posting->account_id, $references))
        {
          $references[$posting->account_id]=Account::model()->getPath($posting->account_id);
        }
        
        $line = $beginwith . '  ' . $references[$posting->account_id] . '  ' . $this->csymbol . ' ' . number_format($posting->amount, 2);
        
        if($posting->comment)
        {
          $line .= '; ' . $posting->comment;
        }
        
        $data[] = $line;
        
      }
      $data[] = '';

    }

    return implode("\n", $data);
  }
  
  
  
  /**
  * Loads data from an uploaded file, and fills them to the firm.
  * @param CUploadedFile $file the file to load data from
  * @return boolean whether the operation was successful
  */
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

      DELT::array2object($data['base'], $this, array('name', 'description', 'firmtype', 'currency', 'language', 'languages', 'shortcodes', 'css'));

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
        DELT::array2object($values, $newaccount, array('code', 'textnames', 'position', 'type', 'outstanding_balance', 'comment'));
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
          DELT::array2object($posting, $newposting, array('amount', 'rank', 'comment', 'subchoice'));
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

//  $md5 = md5(CJSON::encode($data['base'] . $data['accounts'] . $data['templates'] . $data['journalentries'] . $data['meta'] . $key));
    $md5 = md5(CJSON::encode(serialize($data['base']) . serialize($data['accounts']) . serialize($data['templates']) . serialize($data['journalentries']) . serialize($data['meta']) . $key));
    
    return $check ? $md5 == $data['md5sum'] : $md5;
    
  }
  
  /**
   * Deletes all journal entries.
   */
  private function _deleteJournalentries()
  {
    foreach($this->journalentries as $journalentry)
    {
      $journalentry->safeDelete();
    }
  }
  
  /**
   * Deletes the specified journal entries.
   * @param array $ids the ids of the journal entries to delete
   * @return integer the number of journal entries actually deleted
   */
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

  /**
   * Toggles the in-statement visibility of specified journal entries.
   * @param array $ids the ids of the journal entries to toggle visibility for
   * @return integer the number of journal entries toggled
   */
  public function toggleStatementVisibilityOfSelectedJournalentries($ids=array())
  {
    $journalentries=$this->_findJournalentries($ids);
    $number=sizeof($journalentries);
    // FIXME This should be done with a common update query
    foreach($journalentries as $journalentry)
    {
      $journalentry->toggleInStatementVisibility();
    }
    return $number;
  }

  /**
   * Connects the specified journal entries to a transaction from a challenge.
   * @param array $ids the ids of the journal entries to connect to the transaction
   * @param integer $transaction_id the id of the transaction to connect the entries to
   * @return integer the number of journal entries connected
   */
  public function connectSelectedJournalentriesToTransaction($ids=array(), $transaction_id, Challenge $challenge)
  {
    $journalentries=$this->_findJournalentries($ids);
    $number=sizeof($journalentries);
    // FIXME This should be done with a common update query
    foreach($journalentries as $journalentry)
    {
      $journalentry->connectToTransaction($challenge, $transaction_id);
    }
    return $number;
  }

  /**
   * Changes, for the specified journal entries, the date, increasing or decreasing the year.
   * @param array $ids the ids of the journal entries to toggle visibility for
   * @param integer $years the number of years
   * @return integer the number of journal entries changed
   */
  public function changeYearForSelectedJournalentries($ids=array(), $years)
  {
    $journalentries=$this->_findJournalentries($ids);
    $number=sizeof($journalentries);
    // FIXME This should be done with a common update query
    foreach($journalentries as $journalentry)
    {
      $journalentry->changeYear($years);
    }
    return $number;
  }

  
  /**
   * Returns the specified journal entries.
   * @param array $ids the ids of the journal entries to find
   * @return array the journal entries found
   */
  private function _findJournalentries($ids=array())
  {
    
    $criteria = new CDbCriteria();
    $criteria->condition = 'firm_id = :firm_id';
    $criteria->params = array(':firm_id'=>$this->id);
    $criteria->addInCondition('id',$ids);
    
    return Journalentry::model()->findAll($criteria);
  }
  
  /**
   * Deletes all the languages associated with the firm.
   */
  private function _deleteLanguages()
  {
    FirmLanguage::model()->deleteAllByAttributes(array('firm_id'=>$this->id));
  }

  /**
   * Deletes all the templates associated with the firm.
   */
  private function _deleteTemplates()
  {
    Template::model()->deleteAllByAttributes(array('firm_id'=>$this->id));
  }

  /**
   * Deletes all the users (owners) associated with the firm.
   */
  private function _deleteUsers()
  {
    FirmUser::model()->deleteAllByAttributes(array('firm_id'=>$this->id));
  }

  /**
   * Deletes all the accounts associated with the firm.
   */
  private function _deleteAccounts()
  {
    Account::model()->deleteAllByAttributes(array('firm_id'=>$this->id));
  }

  public function findMainPositionsWeShouldTryToCloseAutomatically()
  {
    // we just look for statements that have a type set to 1 (like the income statement)
    // perhaps this will change for better configurability
    // return Account::model()->belongingTo($this->id)->withOneOfTypes(array(1))->ofLevel(1)->findAll();
    
    return $this->getMainPositionsForClosingEntries();
  }

  public function cacheStatementsData($level=1)
  {
    /* to prepare the statement, we insert some journal entries using
     * the templates flagged as "automatic"
     * 
     * we open a transaction, add the entries, compute the data,
     * and rollback
     */
    
    $transaction = $this->getDbConnection()->beginTransaction();
    
    $date = $this->getLastDate('none');
    
    $journalentries = array();
    
    // first, we close the income statement

    $rank = -1;
    foreach($this->findMainPositionsWeShouldTryToCloseAutomatically() as $statement)
    { 
      $entry = $this->_prepareEntriesWith($this->getAccountBalances($statement->position), $statement->getClosingDescription(), $rank--, 1, $date);
      $entry['source']=array('table'=>'statement','id'=>$statement->id);
      $journalentries[] = $entry;
    }
    
    // then, we add automatic entries from templates

    foreach(Template::model()->belongingTo($this->id)->automatic(1)->findAll() as $template)
    {
      $entry = $this->_prepareEntriesWith($template->getAccountsInvolved($this), $template->description, $rank--, 0, $date);
      $entry['source']=array('table'=>'template','id'=>$template->id);
      $journalentries[] = $entry;
    }
    
    // now the data are ready, we can make the queries...
    
    foreach($this->getMainPositions(false, array(1,2,3)) as $statement)
    {
      $this->_cache[$statement->id]=$this->getStatementData($statement, $level);
    }
    
    // let's roll back to avoid polluting data with automatic things
    $transaction->rollBack();
    return $journalentries;
  }

  private function _prepareEntriesWith($accounts=array(), $description='...', $rank, $is_closing, $date)
  {
    $totaldebit=0;
    $totalcredit=0;
    
    $evaluatedAccounts=array();
    
    foreach($accounts as $item)
    {
      $evaluatedAccounts[$item['id']]=$item;
      if($item['id'])
      {
        $totaldebit += DELT::getValueFromArray($item, 'debit', 0);
        $totalcredit += DELT::getValueFromArray($item, 'credit', 0);
      }
    }

    $postings = array();
      
    if (DELT::nearlyZero($totaldebit - $totalcredit) and $totaldebit)
    {
      $je = new Journalentry();
      $je->setDefaultsForAutomaticEntry($this, $description, $rank, $is_closing, $date);
      $je->save(false);
      
      $count = 1;
      foreach($accounts as $item)
      {
        try
        {
          $info = $je->savePosting($item['id'], DELT::getValueFromArray($item, 'debit', 0) - DELT::getValueFromArray($item, 'credit', 0), DELT::getValueFromArray($item, 'comment', null), $count++);
          $info['account_name']=$evaluatedAccounts[$item['id']]['name'];
          $postings[] = $info;
        }
        catch(Exception $e)
        {
          // echo $e->getMessage();
          // this shouldn't happen, but better to play safe...
        }
      }
      $class = 'normal';
    }
    else
    {
      $class = 'excluded';
    }
    return array('journalentry'=>array('description'=>$description, 'class'=>$class), 'postings'=>$postings, 'accounts'=>$accounts);
  } 
  
  /**
   * Returns the data needed for a generic statement.
   * @param string $position the position required
   * @param integer $level the level required
   * @return array the data
   */
  public function getStatement(Account $statement, $level=1)
  {
    if(!isset($this->_cache[$statement->id]))
    {
      throw new Exception('Uncached data, something went wrong');
    }
    return $this->_cache[$statement->id];
  }



  public function getStatementData(Account $statement, $level=1)
  {
    
    if(in_array($statement->type, array(1,2)))
    {
      $position=$statement->position;
      if($statement->type==2)
      {
        $positions=array(strtoupper($position), strtolower($position)); 
      }
      else
      {
        $positions=array(strtoupper($position)); 
      }

      $data = Yii::app()->db->createCommand()
        ->select('id, code, level, currentname as name, is_selectable')
        ->from('{{account}}')
        ->where('firm_id=:id', array(':id'=>$this->id))
        ->andWhere(array('in', 'position', $positions))
        ->andWhere('level <= :level', array(':level'=>$level))
        ->order('rcode')
        ->queryAll();

      foreach($data as $key=>&$item)
      {
        $account=Account::model()->findByPk($item['id']);
        
        if(($pos=mb_strpos($item['name'], '—')) !== false)
        {
          $item['name']=mb_substr($item['name'], 0, $pos); 
        }
        
        
        $item['amount']=$account->getConsolidatedBalance(true);
        
        if($item['amount'] == 0)
        {
          unset($data[$key]);  // we remove items that yeld a zero value...
        }
        
        if($statement->type==2)
        {
          $ob = ($ancestor=$account->firstAncestor) ? $ancestor->outstanding_balance : $account->outstanding_balance;
          $item['type']= $ob=='D' ? '+': '-';
        }
        else
        {
          $item['type'] = '+';
          $item['amount'] = -$item['amount'];
        }
        
      }
      
    }
    elseif($statement->type==3)
    {
      // analytic statement
      
      $data=array('values'=>array(), 'totals'=>array('rows'=>array(), 'columns'=>array()), 'grandtotal'=>0);
     
      $strip = $statement->getValueFromCommentByKeyword('@strip');
      
      foreach($statement->getChildren() as $child)
      {
        $codes = DELT::splitByDelimiter($child->getValueFromCommentByKeyword('@analyze'));
        $amount = 0;
        $postings = array();
        foreach($codes as $code)
        {
          $account = $this->findAccount($code);
          if ($account) {
            foreach($account->getPostings() as $posting)
            {
            $key = $posting->comment ? $posting->comment : DELT::stripString($strip, $posting->journalentry->description);
            $amount = $child->outstanding_balance=='C' ? -$posting->amount: $posting->amount;
            DELT::addValueToArray($postings, $key, $amount);
            DELT::addValueToArray($data['totals']['rows'], $key, $amount);
            DELT::addValueToArray($data['totals']['columns'], $child->currentname, $amount);
            $data['grandtotal'] += $amount;
            }
          }
        }
        
        $data['values'][$child->currentname]=$postings;
      }
      
      // we must get rid of zeroes...
      
      foreach($data['values'] as $k=>$v)
      {
        foreach($v as $a=>$b)
        {
          if((abs($b)<0.00001))
          {
            unset($data['values'][$k][$a]);
            unset($data['totals']['rows'][$a]);
          }
        }
      }
      
    }

    return $data;
    
  }
  
  /**
   * Deletes the firm, by setting a flag (soft deletion).
   * @return boolean whether the operation was successful
   */
  public function softDelete()
  {
    $this->status=self::STATUS_DELETED;
    $this->save();
    return true;
  }

  /**
   * Deletes the firm, actually cleaning off all the data.
   * @return boolean whether the operation was successful
   */
  public function safeDelete()
  {
    $this->_deleteJournalentries();
    $this->_deleteTemplates();
    $this->_deleteAccounts();
    $this->_deleteUsers();
    $this->_deleteLanguages();

    $transaction = $this->getDbConnection()->beginTransaction();
    try
    {
      if(sizeof(Event::model()->findByAttributes(array('firm_id'=>$this->id))))
      {
        $this->slug = substr('~' . md5($this->id), 0, 32);
        $exit = $this->status = self::STATUS_CLEARED;
        $this->save(false);
      }
      else
      {
        $exit = self::STATUS_DELETED;
        $this->delete();
      }
      
      $transaction->commit();
      return $exit;
    }
    catch (Exception $e)
    {
      $transaction->rollback();
      if(method_exists(Yii::app(), 'getUser') and Yii::app()->getUser())
      {
        Yii::app()->getUser()->setFlash('delt_failure', $e->getMessage());
      }
      else // it is run on the command line
      {
        echo $e->getMessage() . "\n";
      }
      return false;
    }
  }

  /**
   * Deletes all journal entries.
   * @return boolean whether the operation was successful
   */
  public function clearJournal()
  {
    $this->_deleteJournalentries();
    return true;
  }
  
  /**
   * Creates a bang account.
   * @param string $name the name of the account to be created
   * @return Account the account created
   */
  public function createBangAccount($name)
  {
    $account = new Account();
    $account->currentname = $name;
    $account->firm_id = $this->id;
    $account->firm = $this;
    $account->position = '?';
    $account->outstanding_balance = '/';
    $account->id = substr('!' . md5($name . rand(0, 100000)), 0, 16);
    return $account;
  }
  
  /**
   * Returns the number of bang accounts created for this firm.
   * @return integer the number
   */
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

  /**
   * Returns the number of firms with a slug starting with a specified text.
   * @param string $text the string to check against
   * @return integer the number
   */
  public function countFirmsWithSlugStartingWith($text)
  {
    $number = Yii::app()->db->createCommand()
      ->select('COUNT(*) as number')
      ->from('{{firm}}')
      ->where('slug LIKE :text', array(':text'=>$text.'%'))
      ->queryScalar();
    return $number;
  }

  /**
   * Saves the languages associated with the firm.
   * @param array $values the ids of the languages to be set
   */
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
  
  /**
   * Returns the data needed to build a tree for the chart of accounts.
   * @param CController $controller the controller
   * @param integer $id the id of an account, if known
   * @return array the data
   */
  public function getCoatree($id=null)
  {
    $controller = Yii::app()->getController();
    
    $result=array();
    
    if($id)
    {
      $accounts=Account::model()->belongingTo($this->id)->hidden(0)->childrenOf($id)->findAll();
    }
    else
    {
      $accounts=Account::model()->belongingTo($this->id)->hidden(0)->ofLevel(1)->findAll();
    }
    
    foreach($accounts as $account)
    {
      // see http://www.yiiframework.com/doc/api/1.1/CTreeView#data-detail
      
      if($account->number_of_children==0)
      {
        $postfix = Account::getPostfix($account->subchoices);
        $text = '<a href="#" onclick="chooseAccount(\'' . str_replace('"', '&quot;', addslashes($account->getCodeAndName($this) . $postfix)) . '\');">'. $account->currentname . $postfix. '</a>';
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
    
    if(sizeof($result)==0)
    {
      $result[]=array(
        'text'=>
          Yii::t('delt', 'The Chart of Accounts is empty.') . ' ' .
          Yii::t('delt', 'You probably created a new one from scratch instead of forking an existing one.')
        , 
        'expanded'=>false,
        'id'=>1,
        'hasChildren'=>false,
        );
    }
    
    return $result;
    
  }
  /**
   * Imports accounts from the data posted in a form.
   * @param IEAccountsForm $form the form where the data are written
   * @return integer the number of accounts actually imported
   */
  
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
          if(isset($items[4]))  // type
          {
            $account->type = $items[4];
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
  
  public function setDefaultLanguageFromUserProfile(DEUser $user)
  {
    if($language=Language::model()->findByAttributes(array('language_code'=>$user->profile->language)))
    {
      $this->language_id=$language->id;
    }
  }
  
  public function acquireBanner(CUploadedFile $file)
  {
    if ($file->size)
    {
      $fp = fopen($file->tempName, 'r');
      $content = fread($fp, filesize($file->tempName));
      fclose($fp);
      $this->banner = $content;
    }
    else
    {
      $this->banner = null;
    }
  }
  
  public function freeze($DE_user_id)
  {
    $this->frozen_at = new CDbExpression('NOW()');
    return $this->save();
  }

  public function unfreeze($DE_user_id)
  {
    $this->frozen_at=null;
    return $this->save();
  }
  
  public function getFrozenAtTimestamp()
  {
    $datetime = new DateTime($this->frozen_at);
    return $datetime->getTimeStamp();
  }
  
  public function getValidPositions($types=array(1,2))
  {
    if($this->positions)
    {
      return $this->positions;
    }
    
    $t = sprintf('type in (%s)', implode(', ', $types));
    
    $items = Yii::app()->db->createCommand()
      ->select('id, account_parent_id, position, currentname as name')
      ->from('{{account}}')
      ->where('firm_id=:id', array(':id'=>$this->id))
      ->andWhere('level <= 3')
      ->andWhere($t)
      ->order('code')
      ->queryAll();
    
    $values=array();
    foreach($items as $item)
    {
      if(!$item['account_parent_id'])
      {
        $values[$item['position']]=array('name'=>$item['name'], 'subitems'=>array(), 'matched'=>true);
        $values[strtolower($item['position'])]=array('name'=>$item['name'], 'subitems'=>array(), 'matched'=>false);
      }
      else
      {
        if($item['position']==strtolower($item['position']))
        {
          $values[$item['position']]['subitems'][]=$item['name'];
          $values[$item['position']]['matched']=true;
        }
        else
        {
          $values[$item['position']]['subitems'][]=$item['name'];
          $values[$item['position']]['matched']=true;
        }
      }
    }
    
    $this->positions=array();
    foreach($values as $key=>$value)
    {
      if($value['matched'])
      {
        $this->positions[$key]=isset($value['name'])?$value['name']:'';
        if(sizeof($value['subitems']))
        {
          $this->positions[$key] .= ' (' . implode(', ', $value['subitems']) . ')';
        }
      }
    }
    return $this->positions;
    
  }
  
  public function getMainPositions($reversed=false, $types=array(1,2))
  {
    return Account::model()->belongingTo($this->id, $reversed ? 'code DESC' : 'code ASC')->withOneOfTypes($types)->ofLevel(1)->findAll();
  }
  
  public function getMainPositionsForClosingEntries()
  {
    $accounts = $this->getMainPositions(true);
    foreach($accounts as $key=>$account)
    {
      if(DELT::falseValue($account->getValueFromCommentByKeyword('@closing', 'yes')))
      {
        unset($accounts[$key]);
      }
    }
    return $accounts;
  }
  
  public function getMainPosition($position)
  {
    return Account::model()->belongingTo($this->id)->hidden(1)->ofLevel(1)->withPosition($position)->find();
  }
  
  public function updateAccountsPositions($oldPosition, $newPosition)
  {
    if($oldPosition!=$newPosition)
    {
      $changes=array(
        strtoupper($oldPosition)=>strtoupper($newPosition),
        strtolower($oldPosition)=>strtolower($newPosition),
        );
      foreach($changes as $old=>$new)
      {
        $params=array(
           ':firm_id'=>$this->id,
           ':old_position'=>$old,
          );
        Yii::app()->db->createCommand()->update(
          '{{account}}',
          array('position'=>$new),
          
          array('and',
            'firm_id=:firm_id',
            'position=:old_position',
            'type=0',
            ),
          $params
          );
          //DELT::logdebug(serialize($params));
      }
    }

  }
  
  public function getValidFirmTypes()
  {
    return array(
      self::FIRMTYPE_BUSINESS => Yii::t('delt', 'Business'),
      self::FIRMTYPE_NPO => Yii::t('delt', 'Not-for-profit Organization'),
    );
  }
  
  public function renderAccountCode($code)
  {
    if ($this->shortcodes)  // firm's setting about abbreviating codes
    {
      $pos = strrpos($code, '.');
      return $pos===false ? $code : substr($code, $pos+1);
    }
    else
    {
      return $code;
    }
  }
  
  public function findAccount($code, $only_selectable=true, $complete_code=false)
  {
    if (!$complete_code and $this->shortcodes)
    {
      $accounts = Yii::app()->db->createCommand()
      ->select('id, code')
      ->from('{{account}}')
      ->where('firm_id=:id', array(':id'=>$this->id))
      ->andWhere(array('like', 'code', '%' . $code . '%'))
      ->andWhere($only_selectable ? 'is_selectable = 1' : '1 = 1')
      ->andWhere('type = 0')
      ->queryAll();
      
      foreach($accounts as $account)
      {
        if($code==$this->renderAccountCode($account['code']))
        {
          return Account::model()->findByPK($account['id']);
        }
      }
      return null;
    }
    else
    {
      $attributes = array('code'=>$code, 'firm_id'=>$this->id, 'type'=>0);
      if($only_selectable)
      {
        $attributes['is_selectable']=true;
      }

      return Account::model()->findByAttributes($attributes);
    }
  }

  public function findSubchoices($term)
  {
      $result = Yii::app()->db->createCommand()
      ->selectDistinct('subchoice')
      ->from('{{posting}}')
      ->leftJoin('{{journalentry}} j', 'journalentry_id = j.id')
      ->where('j.firm_id=:id', array(':id'=>$this->id))
      ->andWhere(array('like', 'subchoice', '%' . $term . '%'))
      ->queryAll();
      
      $values = array();
      foreach($result as $item)
      {
        $values[]=$item['subchoice'];
      }
      return $values;
  }


  
  public function getLastDate($type='long')
  {
      $date = Yii::app()->db->createCommand()
      ->select('date')
      ->from('{{journalentry}}')
      ->where('is_included =1')
      ->andWhere('firm_id=:id', array(':id'=>$this->id))
      ->order('date DESC')
      ->queryScalar();
      
      if($type=='none')
      {
        return $date;
      }
      
      $language = Yii::app()->getLanguage();
      Yii::app()->setLanguage($this->language->locale);
      $result = Yii::app()->dateFormatter->formatDateTime(
        CDateTimeParser::parse(
            $date, 
            'yyyy-MM-dd'
        ),
        $type,null
      );
      Yii::app()->setLanguage($language);
      return $result;
  }
  
  public function getClosingAmountInfo($code, $subchoice='', $posting=0)
  {
    // the closing amount does not take into consideration the posting $posting, if given, by subtracting its value
    if($account = $this->findAccount($code))
    {
      $amount = $account->getConsolidatedBalance(false, $subchoice);
      
      if($p=Posting::model()->findByPK($posting))
      {
         $amount -= $p->amount;
      }
      return array('account_id'=>$account->id, 'amount'=>$amount);
    }
    return array('account_id'=>null, 'amount'=>0);
  }
  
  public function renderAccountCodeAndName($code, $name, $regexp=false)
  {
    // added $regexp parameter for comparison of account names (challenges)
    if ($regexp)
    {
      return sprintf('%s - %s', $this->renderAccountCode($code), preg_replace($regexp, '', $name));
    }
    return sprintf('%s - %s', $this->renderAccountCode($code), $name);
  }
  
  public function swapSelectedJournalentries($ids=array())
  {
    if(sizeof($ids)!=2)
    {
      return false;
    }
    return $this->swapRecords(Journalentry::model(), $ids[0], $ids[1], 'rank');
  }
  
  public function getSelectableAccountsAsArray()
  {
    $accounts = Account::model()->belongingTo($this->id)->hidden(0)->selectable(1)->findAll();
    $result=array();
    foreach($accounts as $account)
    {
      $result[$account->id]=$account->getCodeAndName($this);
    }
    return $result;
  }
  
  /**
   * Swaps two records of a table changing their ids.
   * @param object $model the model to be used to retrieve objects (call passing, for instance, 'Template::model()')
   * @param integer $id1 the id of the first record
   * @param integer $id2 the id of the second record
   * @param string $field the field name to swap
   * @return boolean true if completed with success, false otherwise
   * 
   * @since 1.7.3
   */
  public function swapRecords($model, $id1, $id2, $field='id')
  {
    $c1 = array('id'=>$id1, 'firm_id'=>$this->id);
    $c2 = array('id'=>$id2, 'firm_id'=>$this->id);
    $t1=$model->findByAttributes($c1);
    $t2=$model->findByAttributes($c2);
    if($t1 && $t2)
    {
      try
      {
        $transaction = $t1->getDbConnection()->beginTransaction();
        $old1 = $t1->$field;
        $old2 = $t2->$field;
        $t1->$field=-1;
        $t1->save();
        $t2->$field=$old1;
        $t2->save();
        $t1->$field=$old2;
        $t1->save();
        $transaction->commit();
        return true;
      }
      catch (Exception $e)
      {
        $transaction->rollback();
        return false;
      }
    }
    return false;
  }
  
  public function getChallenge()
  {
    return Challenge::model()->linkedToFirm($this->id)->find();
  }
  
  public function getEvents()
  {
    return Event::model()->ofFirm($this->id)->findAll();
  }
  
}
