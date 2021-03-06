<?php

/**
 * Journalentry class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.0
 */
/**
 * Journalentry represents a single journal entry of a {@link Firm}.
 *
 * @property integer $id
 * @property integer $firm_id
 * @property string $date
 * @property string $description
 * @property integer $is_confirmed
 * @property integer $is_closing
 * @property integer $is_adjustment
 * @property integer $is_included
 * @property integer $is_visible
 * @property integer $rank
 * @property integer $maxrank
 * @property integer $transaction_id
 * @property integer $section_id
 * 
 *
 * The followings are the available model relations:
 * @property Posting[] $postings
 * @property Firm $firm
 * @property Transaction $transaction
 * 
 * @package application.models
 * 
 */
class Journalentry extends CActiveRecord
{
  public $maxrank;
  
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Journalentry the static model class
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
    return '{{journalentry}}';
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
      array('firm_id, is_confirmed, is_closing, rank, transaction_id', 'numerical', 'integerOnly'=>true),
      array('is_adjustment, is_included, is_visible', 'safe'),
      array('description', 'length', 'max'=>255),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('id, firm_id, date, description, is_confirmed, is_closing, is_included, is_visible, rank, transaction_id', 'safe', 'on'=>'search'),
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
      'postings' => array(self::HAS_MANY, 'Posting', 'journalentry_id'),
      'accounts' => array(self::HAS_MANY, 'Account', 'firm_id'),
      'firm' => array(self::BELONGS_TO, 'Firm', 'firm_id'),
      'transaction' => array(self::BELONGS_TO, 'Transaction', 'transaction_id'),
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
      'is_included' => 'Is Included',
      'is_visible' => 'Is Visible',
      'rank' => 'Rank',
      'transaction_id' => 'Transaction',
      'section_id' => 'Section',
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
    $criteria->compare('is_included',$this->is_included);
    $criteria->compare('is_visible',$this->is_visible);
    $criteria->compare('rank',$this->rank);
    $criteria->compare('transaction_id',$this->transaction_id);
    $criteria->compare('section_id',$this->section_id);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
    ));
  }
  
  public function belongingTo($journalentry_id)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'{{posting}}.journalentry_id = :journalentry_id',
        'params'=>array(':journalentry_id'=> $journalentry_id),
        'order'=>'code ASC',
    ));
    return $this;
  }  

  public function included()
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'is_included = 1',
    ));
    return $this;
  }

  public function visible()
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'is_visible = 1',
    ));
    return $this;
  }

  public function ofFirm($firm_id, $order='date ASC, t.id ASC, postings.rank ASC')
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'firm_id = :firm_id',
        'params'=>array(':firm_id'=>$firm_id),
        'order'=>$order,
    ));
    return $this;
  }

  public function connectedTo($transaction_id)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'transaction_id = :transaction_id',
        'params'=>array(':transaction_id'=>$transaction_id),
    ));
    return $this;
  }

  public function inSection($section_id)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'section_id = :section_id',
        'params'=>array(':section_id'=>$section_id),
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
  
  public function deletePostings()
  {
    Posting::model()->deleteAllByAttributes(array('journalentry_id'=>$this->id));
  }
  
  public function safeDelete()
  {
    $transaction=$this->getDbConnection()->beginTransaction();
    
    try
    {
      $this->deletePostings();
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

  public function cloneEntry()
  {
    $newentry = new Journalentry();
    DELT::object2object($this, $newentry, array('firm_id', 'date', 'description', 'is_confirmed', 'is_closing', 'is_adjustment', 'is_included', 'is_visible', 'transaction_id', 'section_id'));
    
    $newentry->rank = $this->getCurrentMaxRank() + 1;
      
    $transaction=$this->getDbConnection()->beginTransaction();
    
    try
    {
      $newentry->save(false);
      foreach($this->postings as $posting) {
        $newposting = new Posting();
        DELT::array2object($posting, $newposting, array('account_id', 'amount', 'comment', 'subchoice', 'rank'));
        $newposting->journalentry_id = $newentry->id;
        $newposting->save(false);
      }
      
      $transaction->commit();
      return true;
    }
    catch(Exception $e)
    {
      $transaction->rollback();
      return false;
    }
      
  }
  
  public function toggleInStatementVisibility()
  {
    $this->is_closing = !$this->is_closing;
    $this->save(false );
  }

  public function connectToTransaction(Challenge $challenge, $transaction_id)
  {
    if (!$challenge)
    {
      return false;
    }
    $this->transaction_id = $transaction_id;
    $this->save(false);
    $challenge->undeclareNotEconomic($transaction_id);
  }
  
  public function setDefaultsForAutomaticEntry($firm, $description, $rank, $is_closing, $date, $section_id)
  {
    $this->firm_id = $firm->id;
    $this->date = $date;
    $this->description = $description;
    $this->is_confirmed = 1;
    $this->is_closing = $is_closing;
    $this->is_adjustment = 1;
    $this->is_included = 1; 
    $this->is_visible = 1;
    $this->section_id = $section_id;
    $this->rank = $rank;
  }
  
  public function savePosting($account_id, $amount, $comment, $rank)
  {
    $p = new Posting();
    $p->journalentry_id = $this->id;
    $p->account_id = $account_id;
    $p->amount = $amount;
    $p->comment = $comment;
    $p->rank = $rank;
    $p->save(false);
    return array('account_id'=>$account_id, 'amount'=>$amount);
  }
  
  public function changeYear($years)
  {
    if (!is_numeric($years))
    {
      return false;
    }
    $years=floor($years);
    
    $d = new DateTime($this->date);
    $changed = false;
    
    if ($years > 0 && $years < 4)
    {
      $d->add(new DateInterval('P' . $years . 'Y'));
      $changed = true;
    }
    elseif ($years < 0 && $years > -4)
    {
      $d->sub(new DateInterval('P' . (-$years) . 'Y'));
      $changed = true;
    }
    
    if ($changed)
    {
      $this->date = $d->format('Y-m-d');
      $this->save();
      return true;
    }
    
    return false;
  }

  public function changeSection($section_id)
  {
    if ($this->section_id != $section_id)
    {
      $section = Section::model()->findByPK($section_id);
      if ($section && $section->firm_id == $this->firm_id)
      {
        $this->section_id = $section_id;
        $this->is_visible = $section->is_visible;
        $this->save(false);
      }
      return true;
    }
    return false;
  }
  
  public function getJournalEntriesByFirmAndTransaction($firm_id, $transaction_id)
  {
    return self::model()
      ->ofFirm($firm_id, 'date ASC, t.rank ASC, postings.amount DESC')
      ->included()
      ->visible()
      ->connectedTo($transaction_id)
      ->with('postings')
      ->findAll();
  }
  
  public function getSection()
  {
    $section = Section::model()->findByPK($this->section_id);
    return $section;
  }
  
}
