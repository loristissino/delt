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
 * @property integer $method
 * @property integer $mark
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
      array('exercise_id, instructor_id, user_id, firm_id, method, mark', 'numerical', 'integerOnly'=>true),
      array('started_at, suspended_at, completed_at', 'safe'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, exercise_id, instructor_id, user_id, firm_id, assigned_at, started_at, suspended_at, completed_at, method, mark', 'safe', 'on'=>'search'),
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
      'method' => 'Method',
      'mark' => 'Mark',
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
    $criteria->compare('mark',$this->mark);

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
  
  public function isOpen()
  {
    return $this->isStarted() && !$this->isSuspended() && !$this->isCompleted();
  }
  
  public function getStatus()
  {
    if ($this->isOpen()) return 'open';
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
      array('suspended_at'=>date('Y-m-d H:i:s')),
      
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
            $this->started_at = date('Y-m-d H:i:s');
            $this->suspended_at = null;
            $this->save();
            
            $done = true;
            break;
          }

        case 'suspend':
          if (!$this->isSuspended() && !$this->isCompleted())
          {
            $this->suspended_at = date('Y-m-d H:i:s');
            $this->save();
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
            $done = true;
            break;
          }
          
        case 'completed':
          if ($this->isStarted() && !$this->isSuspended() && !$this->isCompleted())
          {
            $this->completed_at = date('Y-m-d H:i:s');
            $this->suspended_at = null;
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
  
}
