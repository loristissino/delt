<?php
/**
 * Account class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013 Loris Tissino
 * @since 1.0
 */
/**
 * Account represents a single account belonging to the chart of accounts of a {@link Firm}.
 *
 * @property integer $id
 * @property integer $account_parent_id the id of the parent account
 * @property integer $firm_id
 * @property integer $is_hidden hidden accounts are used for configuration purposes
 * @property integer $level
 * @property string $code
 * @property string $rcode
 * @property integer $is_selectable
 * @property string $position
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
 * @property Posting[] $postings
 * 
 * 
 * @package application.models
 * 
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
      array('position', 'checkPosition'),
      array('comment', 'checkComment'),
      array('position,outstanding_balance', 'length', 'max'=>1),
      array('textnames', 'checkNames'),
      array('currentname', 'safe'),
      array('is_hidden', 'safe'),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('id, account_parent_id, firm_id, level, code, is_selectable, position, outstanding_balance', 'safe', 'on'=>'search'),
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
      'postings' => array(self::HAS_MANY, 'Posting', 'account_id'),
      'journalentries' => array(self::MANY_MANY, 'Journalentry', '{{posting}}(account_id, journalentry_id)'),
      'debitgrandtotal' => array(self::STAT, 'Posting', 'account_id', 
        'select'=>'SUM(amount)',
        'join'=> 'INNER JOIN {{journalentry}} ON t.journalentry_id = {{journalentry}}.id',
        'condition'=>'{{journalentry}}.is_included = 1 and amount > 0',
        ),
      'creditgrandtotal' => array(self::STAT, 'Posting', 'account_id', 
        'select'=>'SUM(amount)',
        'join'=> 'INNER JOIN {{journalentry}} ON t.journalentry_id = {{journalentry}}.id',
        'condition'=>'{{journalentry}}.is_included = 1 and amount < 0',
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
      'is_hidden' => 'Is Hidden',
      'position' => Yii::t('delt', 'position'),
      'outstanding_balance' => Yii::t('delt', 'Ordinary outstanding balance'),
      'textnames' => Yii::t('delt', 'Localized names'),
      'number_of_children' => Yii::t('delt', 'Number of children'),
      'comment'=> Yii::t('delt', 'Comment'),
    );
  }
  
  /**
   * @return array valid account positions (key=>label)
   */
  public function validpositions($withUnpositioned=true)
  {
    /*
    $positions = array(
      'P'=>Yii::t('delt', 'Balance Sheet (Asset / Liability / Equity)'),
      'E'=>Yii::t('delt', 'Income Statement (Revenues / Expenses)'),
      'M'=>Yii::t('delt', 'Memorandum Accounts Table'),
      'p'=>Yii::t('delt', 'Transitory Balance Sheet Accounts'),
      'e'=>Yii::t('delt', 'Transitory Income Statement Accounts'),
      'r'=>Yii::t('delt', 'Result Accounts (Net profit / Total loss)'),
      ); 
    */
    $positions=$this->firm->getValidPositions();
    if($withUnpositioned)
    {
      $positions['?'] = Yii::t('delt', 'Unknown');
    }
    return $positions;
  }
  
  /*
  public function getPositionLabel()
  {
    switch($this->position)
    {
      
      case 'P': return 'BS';
      case 'E': return 'IS';
      default: return $this->position;
    }
  }
  */

  /**
   * @return array valid account positions
   */
  public function getValidpositionByCode($code)
  {
    $positions=$this->validpositions();
    return isset($positions[$code]) ? $positions[$code] : null;
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
    $criteria->compare('is_hidden',$this->is_hidden);
    $criteria->compare('position',$this->position);
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
  }
  
  public function getParent()
  {
    return Account::model()->findByPk($this->account_parent_id);
  }
  
  public function __toString()
  {
    return sprintf('%s - %s', $this->code, $this->name);
  }
  
  public function getAnalysis($amount, $currency='EUR')
  {
    $parent = $this->getParent();
    
    $code =
      $this->position .
      ($parent ? $parent->outstanding_balance : '-') . 
      ($this->outstanding_balance ? $this->outstanding_balance : '/') .
      ($amount>0?'D':'C')
    ;
    
    $explanations = array(
      'PDDD' => 'Increase in Asset',
      'PDDC' => 'Decrease in Asset',
      'PD/C' => 'Decrease in Asset',
      'PDCD' => 'Decrease in Asset Contra Account',
      'PDCC' => 'Increase in Asset Contra Account',
      'PCDD' => 'Increase in a Liability / Equity Contra Account',
      'PCDC' => 'Decrease in a Liability / Equity Contra Account',
      'PCCD' => 'Decrease in Liability / Equity',
      'PCCC' => 'Increase in Liability / Equity',
      'EDDD' => 'Increase in Cost / Expense / Loss',
      'EDDC' => 'Decrease in Cost / Expense / Loss',
      'EDCC' => 'Increase in Cost / Expense / Loss Contra Account',
      'ECDD' => 'Increase in Revenue / Income / Gain Contra Account',
      'ECCD' => 'Decrease in Revenue / Income / Gain',
      'ECCC' => 'Increase in Revenue / Income / Gain',
      'rDDD' => 'Net Loss recorded',
      'rDCC' => 'Net Profit recorded',
      'rCDD' => 'Net Loss recorded',
      'rCCC' => 'Net Profit recorded',
    );

    $explanation = array_key_exists($code, $explanations) ? Yii::t('delt', $explanations[$code]) : Yii::t('delt', 'unexplained entry');
    return sprintf('<strong>%s</strong> - %s for %s.', $this->currentname, $explanation, DELT::currency_value(abs($amount), $currency));
    
  }
    
  public function belongingTo($firm_id)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'t.firm_id = ' . $firm_id,
        'order'=>'code ASC',
    ));
    return $this;
  }
  
  public function hidden($hidden)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'is_hidden = ' . $hidden,
    ));
    return $this;
  }
  
  public function ofLevel($level)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'t.level = ' . $level,
    ));
    return $this;
  }

  public function childrenOf($id)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'t.account_parent_id = ' . $id,
    ));
    return $this;
  }

  public function getNumberOfJournalentries()
  {
    return Posting::model()->countByAttributes(array('account_id'=>$this->id));
  }
  
  /**
   * This method is invoked before deleting a record.
   * @return boolean whether the record should be deleted. Defaults to true.
   */
  protected function beforeDelete()
  {
    if($this->getNumberOfJournalentries() > 0)
    {
      return false;
    }
    else
      return parent::beforeDelete();
  }
  

  protected function _computeLevel()
  {
    $this->level = sizeof(explode('.', $this->code));
  }
  
  protected function beforeSave()
  {
    $this->_computeLevel();
    
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
      throw $e;
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
    if($this->position=='?')
    {
      return !preg_match('/^[a-zA-Z0-9\.\!]*$/', $this->code);
    }
    return preg_match('/^[a-zA-Z0-9\.]*$/', $this->code);
  }
    
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
    if(!$this->textnames)
    {
      return $this->setDefaultForNames();
    }
    
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

  public function getPostingsAsDataProvider()
  {
    return new CActiveDataProvider(Posting::model()->with('journalentry')->belongingTo($this->id), array(
      'pagination'=>array(
          'pageSize'=>30,
          ),
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

  
  public function checkPosition()
  {
     if($this->position=='?' && substr($this->code, 0, 1)!='!')
     {
       $this->addError('position', Yii::t('delt', 'This position is allowed only for bang accounts.'));
     }
     if(!$this->is_hidden)
     {
       if(!$this->hasValidPosition())
       {
         $this->addError('position', Yii::t('delt', 'Not a valid position.'));
       }
     }
     else
     {
       $this->_computeLevel();
       if($this->level==1 and $this->position!=strtoupper($this->position))
       {
         $this->addError('position', Yii::t('delt', 'The position code must be uppercase.'));
       }
     }
  }
  
  public function hasValidPosition()
  {
    return in_array(strtolower($this->position), array_map('strtolower', array_keys($this->firm->getValidPositions())));
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
      ->from('{{posting}} dc')
      ->leftJoin('{{account}} a', 'dc.account_id = a.id')
      ->leftJoin('{{journalentry}} p', 'dc.journalentry_id = p.id')
      ->where('a.code REGEXP "^' . $this->code .'"')
      ->andWhere('p.firm_id = :id', array(':id'=>$this->firm_id))
      ->andWhere($without_closing ? 'p.is_closing = 0': 'true')
      ->andWhere('p.is_included = 1')
      ->queryScalar();
            
    return $amount;
  }

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
      $this->currentname = str_replace('--', '—', $names[$this->firm->language->getLocale()]);
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
  }
  
  
}
