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
 * @property integer $score
 * @property integer $transaction_id  (current transaction)
 * @property string $hints
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

  
  private $_hints = null;  // hints already requested / shown to user
  private $work;           // just an alias
  private $specimen;       // just an alias
  
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
      array('exercise_id, instructor_id, user_id, firm_id, method, score', 'numerical', 'integerOnly'=>true),
      array('started_at, suspended_at, completed_at, hints', 'safe'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, exercise_id, instructor_id, user_id, firm_id, assigned_at, started_at, suspended_at, completed_at, checked_at, method, score', 'safe', 'on'=>'search'),
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
      'score' => 'Score',
      'transaction' => 'Transaction',
      'hints' => 'Hints',
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
    $criteria->compare('score',$this->score);
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
  
  public function forUser($user_id, $order='assigned_at ASC')
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'t.user_id = ' . $user_id,
        'order'=>$order,
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
    $this->work = $this->firm;
    $this->specimen = $this->exercise->firm;
    
    return parent::afterFind();
  }

  protected function beforeSave()
  {
    $this->hints = implode(',', $this->_hints);
    return parent::beforeSave();
  }
  
  public function hasHint($transaction_id)
  {
    return in_array($transaction_id, $this->_hints);
  }

  public function addHint($transaction_id)
  {
    if ($transaction = Transaction::model()->findByPK($transaction_id))
    {
      if(!$this->hasHint($transaction_id))
      {
        $this->_hints[] = $transaction_id;
        $this->score -= $transaction->penalties;
        $this->save();
        return true;
      }
    }
    return false;
  }

  public function removeHint($transaction_id)
  {
    $this->_hints = array_diff($this->_hints, array($transaction_id));
    $this->save();
  }
  
  public function check()
  {
    $fatal = false;
    $results = array('warnings'=>array(), 'firm'=>array(), 'transactions'=>array(), 'score'=>0, 'possiblescore'=>0);

    if ($this->work->firm_parent_id != $this->specimen->firm_parent_id)
    {
      $results['warnings'][]=Yii::t('delt', 'Different parents');
    }
    
    if ($this->work->language_id != $this->specimen->language_id)
    {
      $results['warnings'][]=Yii::t('delt', 'Different languages');
    }
    
    if ($this->work->frozen_at > $this->completed_at)
    {
      $results['firm']['errors'][]=Yii::t('delt', 'The firm has been frozen after having been marked completed.');
      $fatal = true;
    }
    
    foreach($this->exercise->transactions as $transaction)
    {
      $results['transactions'][$transaction->id] = $this->checkTransaction($transaction);
      $results['score'] += $results['transactions'][$transaction->id]['points'] - $results['transactions'][$transaction->id]['penalties'];
      $results['possiblescore'] += $transaction->points;
    }
    
    if($fatal)
    {
      $results['score'] = 0;
    }
    $this->score = $results['score'];
    $this->changeStatus('checked');
    return $results;
  }
  
  public function checkTransaction(Transaction $transaction)
  {
    $result = array();
    
    $wje = $this->findJournalEntriesOfWork($transaction);
    $sje = $this->findJournalEntriesOfSpecimen($transaction);
    
    $sizeOfWJE = sizeof($wje);
    $sizeOfSJE = sizeof($sje);

    $result['points'] = 0; // if we find errors, we put this to 0 afterwards
    $result['description'] = $transaction->description;
    $result['errors'] = array();
    
    if ( $sizeOfWJE != $sizeOfSJE )
    {
      $result['points'] = 0;
      $result['errors'] = array(Yii::t('delt', 'Not the same number of journal entries (expected: %number_expected%, found: %number_found%)', 
        array('%number_expected%'=>$sizeOfSJE, '%number_found%'=>$sizeOfWJE))
        );
    }
    else
    {
      for ($i=0; $i< $sizeOfSJE; $i++)   // they are sorted the same way, so we just go in parallel
      {
        if($wje[$i]->date != $sje[$i]->date)
        {
          $result['errors'][] = Yii::t('delt', 'Journal entry %id%: not the correct date (expected: %date_expected%, found: %date_found%)', 
            array(
              '%id%'=>$wje[$i]->id,
              '%date_expected%' => Yii::app()->dateFormatter->formatDateTime($sje[$i]->date, 'short', null),
              '%date_found%' => Yii::app()->dateFormatter->formatDateTime($wje[$i]->date, 'short', null),
              )
            );
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
    return Journalentry::model()->ofFirm($this->work->id)->included()->connectedTo($transaction->id)->with('postings')->findAll();
  }

  private function findJournalEntriesOfSpecimen(Transaction $transaction)
  {
    return Journalentry::model()->ofFirm($this->specimen->id)->included()->withRanks($transaction->getJERanks())->with('postings')->findAll();
    
  }

  
}
