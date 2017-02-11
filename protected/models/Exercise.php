<?php

/**
 * Exercise class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.8
 */
/**
 * Exercise represents a single exercise prepared by an instructor
 * 
 * @property integer $id
 * @property integer $user_id
 * @property integer $firm_id
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string $introduction
 * @property integer $method  // the default method
 * @property string $session_pattern // the default for session names
 *
 * The followings are the available model relations:
 * @property Challenge[] $challenges
 * @property Firm $firm
 * @property Users $user
 * @property Transaction[] $transactions
 * 
 *
 * @package application.models
 * 
 */

class Exercise extends CActiveRecord
{
  public $method_items;
  public $license_confirmation;
  public $yaml;
  
  private $_wordwrap;
  
  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return '{{exercise}}';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('title, slug', 'required'),
      array('user_id, firm_id, method', 'numerical', 'integerOnly'=>true),
      array('slug', 'SlugValidator', 'model'=>Exercise::model()),
      array('title, description', 'length', 'max'=>255),
      array('session_pattern', 'length', 'max'=>32),
      array('introduction', 'safe'),
      array('license_confirmation', 'validateLicense'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, user_id, firm_id, slug, title, description, introduction, session_pattern', 'safe', 'on'=>'search'),
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
      'challenges' => array(self::HAS_MANY, 'Challenge', 'exercise_id'),
      'firm' => array(self::BELONGS_TO, 'Firm', 'firm_id'),
      'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
      'transactions' => array(self::HAS_MANY, 'Transaction', 'exercise_id', 'order'=>'event_date, rank'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'user_id' => Yii::t('delt', 'User'),
      'firm_id' => Yii::t('delt', 'Benchmark Firm'),
      'slug' => Yii::t('delt', 'Slug'),
      'title' => Yii::t('delt', 'Title'),
      'description' => Yii::t('delt', 'Description'),
      'license' => Yii::t('delt', 'License'),
      'introduction' => Yii::t('delt', 'Introduction'),
      'method' => Yii::t('delt', 'Default Options'),
      'session_pattern' => Yii::t('delt', 'Session Pattern'),
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
    $criteria->compare('user_id',$this->user_id);
    $criteria->compare('firm_id',$this->firm_id);
    $criteria->compare('slug',$this->slug,true);
    $criteria->compare('title',$this->title,true);
    $criteria->compare('description',$this->description,true);
    $criteria->compare('introduction',$this->introduction,true);
    $criteria->compare('method',$this->method,true);
    $criteria->compare('session_pattern',$this->session_pattern,true);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Exercise the static model class
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }
  
  public function __toString()
  {
    return $this->title;
  }
  
  public function getOwnerProfile()
  {
    return Profile::model()->findByPk($this->user_id);
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
  
  public function ofUser($user_id, $order='assigned_at ASC')
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'t.user_id = ' . $user_id,
        'order'=>$order,
    ));
    return $this;
  }  
  
  /**
   * Returns a data provider for the exercise prepared by the user.
   * @param  integer $user_id the user id
   * @return CActiveDataProvider the challenges of the user
   */
  public function getExercisesOfUser($user_id, $order='title ASC')
  {
    return new CActiveDataProvider($this->ofUser($user_id, $order), array(
      'criteria'=>array(
        ),
      'pagination'=>array(
          'pageSize'=>100,
          ),
      )
    );
  }
  
  public function getChallenges($session)
  {
    return Challenge::model()->ofExercise($this->id)->withSession($session)->findAll();
  }
  
  public function getSessions()
  {
    return Yii::app()->db->createCommand()
           ->select('count(*) AS cnt, session')
           ->from('tbl_challenge') 
           ->group('session') 
           ->where('exercise_id='. $this->id)
           ->queryAll(); 
  }

  public function invite($users=array(), $method=false, $session='')
  {
    if ($method===false)
    {
      $method = $this->method; // we use the default set in Exercise
    }
    
    if ($session=='')
    {
      $session = date($this->session_pattern);
    }
    
    $count = 0;
    foreach($users as $username)
    {
      try
      {
        if ($DEUser = DEUser::model()->getBy('username', $username))
        {
          $challenge = new Challenge();
          $challenge->setInitialDefaults();
          $challenge->exercise_id = $this->id;
          $challenge->instructor_id = $this->user_id;
          $challenge->user_id =$DEUser->id;
          $challenge->method = $method;
          $challenge->session = $session;
          $challenge->save();
          $count++;
        }
      }
      catch(Exception $e)
      {
        // nothing special...
      }
    }
    return $count;
  }
  
  protected function afterConstruct()
  {
    $this->_loadMethodItems();
    return parent::afterConstruct();
  }
  
  protected function beforeSave()
  {
    DELT::sanitizeProperties($this, array(
      'title',
      'description',
      'introduction',
      )
    );
    return parent::beforeSave();
  }

  protected function afterFind()
  {
    $this->_loadMethodItems();
    return parent::afterFind();
  }
  
  private function _addQuotes($value)
  {
    return '"' . str_replace('"', '\"', $value) . '"';
  }

  private function _addSpaces($lines, $number)
  {
    $array=array();
    $spaces = str_repeat(' ', $number);
    foreach($lines as $line)
    {
      $array[]=$spaces . $line;
    }
    return $array;
  }
  
  private function _wordwrap($value)
  {
    return wordwrap($value, $this->_wordwrap, "\n");
  }
  
  private function _fixLongText($value, $indentation)
  {
    $value = str_replace("\r", '', $value);
    return $this->_addSpaces(explode("\n", implode("\n", array_map(array($this, '_wordwrap'), explode("\n", $value)))), $indentation);
  }
  
  public function createYaml($wordwrap)
  {
    $this->_wordwrap = $wordwrap;
    
    $yaml = array();
    $yaml[] = "---";
    $yaml[] = "slug: " . $this->slug;
    $yaml[] = "title: " . $this->_addQuotes($this->title);
    $yaml[] = "instructor: " . $this->getOwnerProfile()->getFullName();
    $yaml[] = "license: Creative Commons Attribution-ShareAlike 3.0";
    $yaml[] = "description: " . $this->_addQuotes($this->description);
    $yaml[] = "method: " . $this->method;
    $yaml[] = "session_pattern: " . $this->_addQuotes($this->session_pattern);
    $yaml[] = "benchmark: " . $this->firm->slug;
    $yaml[] = "introduction: |";
    $yaml = array_merge($yaml, $this->_fixLongText($this->introduction, 2));

    $yaml[] = "";
    $yaml[] = "exported: " . date('Y-m-d H:i:s');
    $yaml[] = "url: " . Yii::app()->controller->createAbsoluteUrl('exercise/view', array('id'=>$this->id));
    $yaml[] = "";

    $yaml[] = "transactions:";
    foreach($this->transactions as $transaction)
    {
      $yaml[] = '  -';
      $yaml[] = '     date: ' . $transaction->event_date;
      $yaml[] = '     rank: ' . $transaction->rank;
      $yaml[] = '     description: |';
      $yaml = array_merge($yaml, $this->_fixLongText($transaction->description, 7));
      $yaml[] = '     entries: ' . $transaction->entries;
      if ($transaction->hint)
      {
        $yaml[] = '     hint: |';
        $yaml = array_merge($yaml, $this->_fixLongText($transaction->hint, 7));
      }
      if ($transaction->regexps)
      {
        $yaml[] = '     regexps: |';
        $yaml = array_merge($yaml, $this->_fixLongText($transaction->regexps, 7));
      }
      $yaml[] = '     points: ' . $transaction->points;
      $yaml[] = '     penalties: ' . $transaction->penalties;
    }

    $this->yaml = implode("\n", $yaml);
    
  }
  
  public function importFromYaml($string)
  {
    $values = Spyc::YAMLLoadString(str_replace("\r", '', $string));
    
    if (!is_array($values) || sizeof($values)==0)
    {
      return false;
    }
    
    $dbtransaction = $this->getDbConnection()->beginTransaction();
    
    try
    {
      Transaction::model()->deleteAll('exercise_id = :id', array(':id' => $this->id));
      
      DELT::array2object($values, $this, array('title', 'description', 'method', 'session_pattern'));
      $benchmark = DELT::getValueFromArray($values, 'benchmark', false);
      if ($benchmark)
      {
        $benchmark_firm = Firm::model()->findByAttributes(array('slug'=>$benchmark));
        if ($benchmark_firm)
        {
          $this->firm_id = $benchmark_firm->id;
        }
      }
      
      $this->save(false);
      $transactions=DELT::getValueFromArray($values, 'transactions', array());
      if (sizeof($transactions)==0)
      {
        $dbtransaction->rollBack();
        return false;
      }
      if (is_array($transactions))
      {
        foreach(DELT::getValueFromArray($values, 'transactions', array()) as $transaction)
        {
          $newtransaction = new Transaction();
          DELT::array2object($transaction, $newtransaction, array('rank', 'description', 'hint', 'regexps', 'points', 'penalties', 'entries'));
          $newtransaction->event_date = DELT::getValueFromArray($transaction, 'date', date('Y-m-d'));
          $newtransaction->exercise_id = $this->id;
          $newtransaction->save();
        }
      }
      $dbtransaction->commit();
      return true;
    }
    catch(Exception $e)
    {
      //die($e->getMessage());
      $dbtransaction->rollBack();
      return false;
    }
    
  }
  
  public function createTransactionsFromBenchmark()
  {
    if (sizeof($this->transactions))
    {
      return false;
    }
    
    $dbtransaction = $this->getDbConnection()->beginTransaction();

    try
    {
      Transaction::model()->deleteAll('exercise_id = :id', array(':id' => $this->id));
      
      foreach ($this->firm->journalentries as $je)
      {
        $transaction = new Transaction();
        $transaction->event_date= $je->date;
        $transaction->description = $je->description;
        $transaction->exercise_id = $this->id;
        $transaction->entries = 1;
        $transaction->save();
        
        if (!$je->transaction_id)
        {
          $je->transaction_id = $transaction->id;
          $je->save();
        }
        
      }
      $dbtransaction->commit();
      return true;
    }
    catch(Exception $e)
    {
      die($e->getMessage());
      $dbtransaction->rollBack();
      return false;
    }
  }
  
  private function _loadMethodItems()
  {
    $this->method_items = Challenge::model()->getMethodItems();
    foreach($this->method_items as $key=>$value)
    {
      $this->method_items[$key]['value']=$this->method & $key;
    }
  }
}
