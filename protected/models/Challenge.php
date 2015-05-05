  <?php

/**
 * Challenge class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2015 Loris Tissino
 * @since 1.8
 */
/**
 * Challenge represents a single challenge for a student
 *
 * @property integer $id
 * @property integer $exercise_id
 * @property integer $instructor_id
 * @property integer $user_id
 * @property integer $firm_id
 * @property string $assigned_at
 * @property string $started_at
 * @property string $suspended_at
 * @property string $completed_at
 * @property string $checked_at
 * @property integer $method
 * @property integer $rate     // success rate (normalized 0 - 1000)
 * @property integer $transaction_id  (current transaction)
 * @property string $hints
 * @property string $shown
 *
 * The followings are the available model relations:
 * @property Exercise $exercise
 * @property Users $instructor
 * @property Users $user
 * @property Firm $firm
 * 
 * 
 * @package application.models
 * 
 */
class Challenge extends CActiveRecord
{
  
  const
     SHOW_POINTS_DURING_CHALLENGE          =   1,
     ALLOW_SHOW_CORRECT_ENTRIES            =   2,
     SHOW_CHECKS_ON_TRANSACTION_CHANGE     =   4,
     SHOW_CHECKS_ON_CHALLENGE_COMPLETED    =   8
     ;

  
  private $_hints = null;  // hints requested by the user and shown
  private $_shown = null;  // transactions shown to user
  private $work;           // just an alias
  private $benchmark;       // just an alias
  
  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return '{{challenge}}';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('exercise_id, user_id, assigned_at, method', 'required'),
      array('exercise_id, instructor_id, user_id, firm_id, method, rate', 'numerical', 'integerOnly'=>true),
      array('started_at, suspended_at, completed_at, hints, shown', 'safe'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, exercise_id, instructor_id, user_id, firm_id, assigned_at, started_at, suspended_at, completed_at, checked_at, method, rate', 'safe', 'on'=>'search'),
    );
  }

  /**
   * @return array relational rules.
   */
  public function relations()
  {
    return array(
      'exercise' => array(self::BELONGS_TO, 'Exercise', 'exercise_id'),
      'instructor' => array(self::BELONGS_TO, 'Users', 'instructor_id'),
      'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
      'firm' => array(self::BELONGS_TO, 'Firm', 'firm_id'),
      'transaction' => array(self::BELONGS_TO, 'Transaction', 'transaction_id'),  // current task
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'exercise_id' => 'Exercise',
      'instructor_id' => 'Instructor',
      'user_id' => 'User',
      'firm_id' => 'Firm',
      'assigned_at' => 'Assigned At',
      'started_at' => 'Started At',
      'suspended_at' => 'Suspended At',
      'completed_at' => 'Completed At',
      'checked_at' => 'Checked At',
      'method' => 'Method',
      'rate' => 'Rate',
      'transaction' => 'Transaction',
      'hints' => 'Hints',
      'shown' => 'Shown',
    );
  }

  /**
   * Retrieves a list of models based on the current search/filter conditions.
   *
   * Typical usecase:
   * - Initialize the model fields with values from filter form.
   * - Execute this method to get CActiveDataProvider instance which will filter
   * models according to data in model fields.
   * - Pass data provider to CGridView, CListView or any similar widget.
   *
   * @return CActiveDataProvider the data provider that can return the models
   * based on the search/filter conditions.
   */
  public function search()
  {
    // @todo Please modify the following code to remove attributes that should not be searched.

    $criteria=new CDbCriteria;

    $criteria->compare('id',$this->id);
    $criteria->compare('exercise_id',$this->exercise_id);
    $criteria->compare('instructor_id',$this->instructor_id);
    $criteria->compare('user_id',$this->user_id);
    $criteria->compare('firm_id',$this->firm_id);
    $criteria->compare('assigned_at',$this->assigned_at,true);
    $criteria->compare('started_at',$this->started_at,true);
    $criteria->compare('suspended_at',$this->suspended_at,true);
    $criteria->compare('completed_at',$this->completed_at,true);
    $criteria->compare('method',$this->method);
    $criteria->compare('rate',$this->rate);
    $criteria->compare('transaction_id',$this->transaction_id);
    
    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Challenge the static model class
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }
  
  public function __toString()
  {
    return $this->exercise->title;
  }
  
  public function setInitialDefaults()
  {
    $this->assigned_at = new CDbExpression('NOW()');
    $this->rate = 0;
    $this->_hints = array();
    $this->_shown = array();
  }
  
  public function forUser($user_id, $order='assigned_at ASC')
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'t.user_id = ' . $user_id,
        'order'=>$order,
    ));
    return $this;
  }  

  public function linkedToFirm($firm_id)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'t.firm_id = ' . $firm_id,
    ));
    return $this;
  }  
  
  public function started($started=true)
  {
    return $this->_timestampFilter('started', $started);
  }  

  public function suspended($suspended=true)
  {
    return $this->_timestampFilter('suspended', $suspended);
  }

  public function completed($completed=true)
  {
    return $this->_timestampFilter('completed', $completed);
  }  
  
  public function checked($checked=true)
  {
    return $this->_timestampFilter('checked', $checked);
  }  

  private function _timestampFilter($event, $condition)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'t.' . $event . '_at IS ' . ($condition ? 'NOT ': '') . 'NULL',
    ));
    return $this;
  }
  
  public function isStarted()
  {
    return $this->started_at != null;
  }
  
  public function isNew()
  {
    return !$this->isStarted();
  }

  public function isSuspended()
  {
    return $this->suspended_at != null;
  }

  public function isCompleted()
  {
    return $this->completed_at != null;
  }

  public function isChecked()
  {
    return $this->checked_at != null;
  }
  
  public function isOpen()
  {
    return $this->isStarted() && !$this->isSuspended() && !$this->isCompleted();
  }
  
  public function hasFirm()
  {
    return !!$this->firm_id;
  }
  
  public function getStatus()
  {
    if ($this->isOpen()) return 'open';
    if ($this->isChecked()) return 'checked';
    if ($this->isCompleted()) return 'completed';
    if ($this->isSuspended()) return 'suspended';
    if ($this->isNew()) return 'new';
  }
  
  private function _suspendChallengesForUser()
  {
    $params=array(
     ':user_id'=>$this->user_id,
    );
    Yii::app()->db->createCommand()->update(
      '{{challenge}}',
      array('suspended_at'=>new CDbExpression('NOW()')),
      
      array('and',
        'user_id=:user_id',
        'started_at  IS NOT NULL',
        'suspended_at IS NULL',
        'completed_at IS NULL',
        ),
      $params
      );
  }
  
  
  public function changeStatus($action)
  {
    
    $transaction = $this->getDbConnection()->beginTransaction();
    $done = false;
    $now = new CDbExpression('NOW()');
    
    try
    {
      switch($action)
      {
        case 'start':
          if (!$this->isStarted())
          {
            // suspend all the active challenges of the user...
            $this->_suspendChallengesForUser();
            // for this one, set started_at and delete suspended_at
            $this->started_at = $now;
            $this->suspended_at = null;
            $this->save();
            
            $done = true;
            break;
          }

        case 'suspend':
          if (!$this->isSuspended() && !$this->isCompleted())
          {
            $this->suspended_at = $now;
            $this->save();
            Yii::app()->user->setState('transaction', null);
            $done = true;
            break;
          }

        case 'resume':
          if ($this->isSuspended() && !$this->isCompleted())
          {
            // suspend all the active challenges of the user...
            $this->_suspendChallengesForUser();
            $this->suspended_at = null;
            $this->save();
            Yii::app()->user->setState('transaction', $this->transaction_id);
            $done = true;
            break;
          }
          
        case 'completed':
          if ($this->isStarted() && !$this->isSuspended() && !$this->isCompleted() && $this->hasFirm())
          {
            $this->firm->freeze(Yii::app()->user->id);
            $this->completed_at = $now;
            $this->suspended_at = null;
            $this->save();
            $done = true;
            break;
          }
          
        case 'checked':
          if ($this->isCompleted())
          {
            $this->checked_at = $now;
            $this->save();
            $done = true;
            break;
          }
      }  // end switch
      
      if($done)
      {
        $transaction->commit();
        return true;
      }
      
    }  // end try
    catch (Exception $e)
    {
      $transaction->rollBack();
      return false;
    }
    
    return false;
    
  }
  
  public function connect(Firm $firm)
  {
    try
    {
      $this->firm_id = $firm->id;
      $this->save();
      return true;
    }
    catch (Exception $e)
    {
      return false;
    }
  }

  public function activateTransaction($transaction_id)
  {
    try
    {
      $this->transaction_id = $transaction_id;
      $this->save(false);
      return true;
    }
    catch (Exception $e)
    {
      return false;
    }
  }
  
  /**
   * Returns a data provider for the challenges of a user.
   * @param  integer $user_id the user id
   * @return CActiveDataProvider the challenges of the user
   */
  public function getChallengesForUser($user_id)
  {
    return new CActiveDataProvider($this->forUser($user_id), array(
      'criteria'=>array(
          'order' => 'assigned_at DESC',
        ),
      'pagination'=>array(
          'pageSize'=>100,
          ),
      )
    );
  }
    
  protected function afterFind()
  {
    $this->_hints = $this->hints ? explode(',', $this->hints) : array();
    $this->_shown = $this->shown ? explode(',', $this->shown) : array();
    $this->work = $this->firm;
    $this->benchmark = $this->exercise->firm;
    
    return parent::afterFind();
  }

  protected function beforeSave()
  {
    $this->hints = implode(',', $this->_hints);
    $this->shown = implode(',', $this->_shown);
    return parent::beforeSave();
  }
  
  public function hasHint($transaction_id)
  {
    return in_array($transaction_id, $this->_hints);
  }

  public function beenShown($transaction_id)
  {
    return in_array($transaction_id, $this->_shown);
  }

  public function addHint($transaction_id)
  {
    if ($transaction = Transaction::model()->findByPK($transaction_id))
    {
      if(!$this->hasHint($transaction_id))
      {
        $this->_hints[] = $transaction_id;
        $this->save();
      }
      return true;
    }
    return false;
  }

  public function addShown($transaction_id)
  {
    if ($transaction = Transaction::model()->findByPK($transaction_id))
    {
      if(!$this->beenShown($transaction_id))
      {
        $this->_shown[] = $transaction_id;
        $this->save();
      }
      return true;
    }
    return false;
  }

  public function removeHint($transaction_id)
  {
    $this->_hints = array_diff($this->_hints, array($transaction_id));
    $this->save();
  }

  public function removeShown($transaction_id)
  {
    $this->_shown = array_diff($this->_shown, array($transaction_id));
    $this->save();
  }
  
  public function check($final=true)
  {
    $fatal = false;
    $results = array('warnings'=>array(), 'firm'=>array(), 'transactions'=>array(), 'score'=>0, 'possiblescore'=>0);

    if ($this->work->firm_parent_id != $this->benchmark->firm_parent_id)
    {
      $results['firm']['warnings'][]=Yii::t('delt', 'Different parents');
    }
    
    if ($this->work->language_id != $this->benchmark->language_id)
    {
      $results['firm']['warnings'][]=Yii::t('delt', 'Different languages');
    }
    
    if ($this->work->currency != $this->benchmark->currency)
    {
      $results['firm']['warnings'][]=Yii::t('delt', 'Different currencies');
    }
    
    if ($this->work->frozen_at > $this->completed_at)
    {
      $results['firm']['errors'][]=Yii::t('delt', 'The firm has been frozen after having been marked completed.');
      if ($final)
      {
        $fatal = true;
      }
    }
    
    if (!$this->work->frozen_at)
    {
      $results['firm']['errors'][]=Yii::t('delt', 'The firm has been unfrozen after having been marked completed.');
      if ($final)
      {
        $fatal = true;
      }
    }
    
    foreach($this->exercise->transactions as $transaction)
    {
      $results['transactions'][$transaction->id] = $this->checkTransaction($transaction, $final);
      $results['score'] += $results['transactions'][$transaction->id]['points'] - $results['transactions'][$transaction->id]['penalties'];
      $results['possiblescore'] += $transaction->points;
    }
    
    if($fatal)
    {
      $results['score'] = 0;
    }
    
    $this->rate = round(1000*$results['score']/$results['possiblescore']);
    if($final)
    {
      $this->changeStatus('checked');
    }
    $this->save();
    return $results;
  }
  
  public function checkTransaction(Transaction $transaction, $final=true)
  {
    $result = array();
    
    $wje = $this->findJournalEntriesOfWork($transaction);
    $bje = $this->findJournalEntriesOfBenchmark($transaction);
    
    $sizeOfWJE = sizeof($wje);
    $sizeOfBJE = sizeof($bje);

    $result['points'] = 0; // if we don't find any error, we'll assign the points afterwards
    $result['description'] = $transaction->description;
    $result['errors'] = array();
    
    if ( $sizeOfWJE != $sizeOfBJE )
    {
      $result['points'] = 0;
      if (!(($sizeOfWJE==0 && !$final)))
      {
      $result['errors'] = array(Yii::t('delt', 'Wrong number of journal entries (expected: %number_expected%, found: %number_found%)', 
        array('%number_expected%'=>$sizeOfBJE, '%number_found%'=>$sizeOfWJE))
        );
      }
    }
    else
    {
      for ($i=0; $i< $sizeOfBJE; $i++)   // they are sorted the same way, so we just go in parallel
      {
        if($wje[$i]->date != $bje[$i]->date)
        {
          $result['errors'][] = Yii::t('delt', 'Journal entry %number%: wrong date (expected: %date_expected%, found: %date_found%)', 
            array(
              '%number%'=>1+$i,
              '%date_expected%' => Yii::app()->dateFormatter->formatDateTime($bje[$i]->date, 'short', null),
              '%date_found%' => Yii::app()->dateFormatter->formatDateTime($wje[$i]->date, 'short', null),
              )
            );
        }

        $sizeOfWJEPostings = sizeof($wje[$i]->postings);
        $sizeOfBJEPostings = sizeof($bje[$i]->postings);
        
        if($sizeOfWJEPostings != $sizeOfBJEPostings)
        {
          $result['errors'][] = Yii::t('delt', 'Journal entry %number%: wrong number of postings (expected: %postings_expected%, found: %postings_found%)', 
            array(
              '%number%'=>1+$i,
              '%postings_expected%' => $sizeOfBJEPostings,
              '%postings_found%' => $sizeOfWJEPostings,
              )
            );
        }
        else
        {
          for ($j=0; $j< $sizeOfBJEPostings; $j++)
          {
            if (!DELT::nearlyZero($wje[$i]->postings[$j]->amount - $bje[$i]->postings[$j]->amount))
            {
              $result['errors'][] = Yii::t('delt', 'Journal entry %number%: wrong amount for posting %posting% (expected: %amount_expected%, found: %amount_found%)', 
            array(
              '%number%'=>1+$i,
              '%posting%'=>1+$j,
              '%amount_expected%' => DELT::currency_value($bje[$i]->postings[$j]->amount, $this->benchmark->currency, true),
              '%amount_found%' => DELT::currency_value($wje[$i]->postings[$j]->amount, $this->benchmark->currency, true),
              )
            );
            }
            
            if ($wje[$i]->postings[$j]->account->getCodeAndNameForComparison($this->work) != $bje[$i]->postings[$j]->account->getCodeAndNameForComparison($this->benchmark))
            {
              $result['errors'][] = Yii::t('delt', 'Journal entry %number%: wrong account for posting %posting% (expected: «%account_expected%», found: «%account_found%»)', 
            array(
              '%number%'=>1+$i,
              '%posting%'=>1+$j,
              '%account_expected%' => $bje[$i]->postings[$j]->account->getCodeAndName($this->benchmark),
              '%account_found%' => $wje[$i]->postings[$j]->account->getCodeAndName($this->work),
              )
            );
            }
            else
            {
              if ($wje[$i]->postings[$j]->account->getCodeAndName($this->work) != $bje[$i]->postings[$j]->account->getCodeAndName($this->benchmark))
              {
                $result['warnings'][] = Yii::t('delt', 'Journal entry %id%: wrong account name for posting %number% (expected: «%account_expected%», found: «%account_found%»)', 
              array(
                '%id%'=>$wje[$i]->id,
                '%number%'=>1+$j,
                '%account_expected%' => $bje[$i]->postings[$j]->account->getCodeAndName($this->benchmark),
                '%account_found%' => $wje[$i]->postings[$j]->account->getCodeAndName($this->work),
                )
              );
              }
              
            }
            
          }
        }
        
      }
      if (sizeof($result['errors'])==0)
      {
        $result['points'] = $transaction->points; // good!
      } 
    }
    
    $result['penalties'] = $this->hasHint($transaction->id) ? $transaction->penalties : 0;
    
    return $result;
  }
  
  private function findJournalEntriesOfWork(Transaction $transaction)
  {
    return Journalentry::model()->ofFirm($this->work->id, 'date ASC, t.id ASC, postings.amount ASC')->included()->connectedTo($transaction->id)->with('postings')->findAll();
  }

  private function findJournalEntriesOfBenchmark(Transaction $transaction)
  {
    return Journalentry::model()->ofFirm($this->benchmark->id, 'date ASC, t.id ASC, postings.amount ASC')->included()->connectedTo($transaction->id)->with('postings')->findAll();
    
  }

  
}
