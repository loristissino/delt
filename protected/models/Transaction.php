<?php

/**
 * Transaction class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.8
 */
/**
 * Transaction represents a single task belonging to an {@link Exercise}
 *
 * @property integer $id
 * @property integer $exercise_id
 * @property integer $rank
 * @property string $event_date
 * @property string $description
 * @property string $hint
 * @property string $regexps
 * @property integer $points
 * @property integer $penalties
 * @property integer $entries number of entries needed to record this transaction
 *
 * The followings are the available model relations:
 * @property Challenge[] $challenges
 * @property Exercise $exercise 
 * @property Journalentry[] $journalentries
 * 
 * @package application.models
 * 
 */

class Transaction extends CActiveRecord
{
  
  private $_regexps;
  
  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return '{{transaction}}';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('exercise_id, event_date, description', 'required'),
      array('exercise_id, rank, points, penalties, entries', 'numerical', 'integerOnly'=>true),
      array('hint, regexps', 'safe'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, exercise_id, event_date, description, hint, regexps', 'safe', 'on'=>'search'),
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
      'challenges' => array(self::HAS_MANY, 'Challenge', 'transaction_id'),
      'exercise' => array(self::BELONGS_TO, 'Exercise', 'exercise_id'),
      'journalentries' => array(self::HAS_MANY, 'Journalentry', 'transaction_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'exercise_id' => Yii::t('delt', 'Exercise'),
      'rank' => Yii::t('delt', 'Rank'),
      'event_date' => Yii::t('delt', 'Event Date'),
      'description' => Yii::t('delt', 'Description'),
      'hint' => Yii::t('delt', 'Hint'),
      'regexps' => Yii::t('delt', 'Regular Expressions'),
      'points' => Yii::t('delt', 'Points'),
      'penalties' => Yii::t('delt', 'Penalties'),
      'entries' => Yii::t('delt', 'Number of Journal Entries'),
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
    $criteria->compare('rank',$this->rank);
    $criteria->compare('event_date',$this->event_date,true);
    $criteria->compare('description',$this->description,true);
    $criteria->compare('hint',$this->hint,true);
    $criteria->compare('regexps',$this->hint,true);
    $criteria->compare('points',$this->points,true);
    $criteria->compare('penalties',$this->penalties,true);
    $criteria->compare('entries',$this->entries,true);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
    ));
  }

  /**
   * Returns the static model of the specified AR class.
   * Please note that you should have this exact method in all your CActiveRecord descendants!
   * @param string $className active record class name.
   * @return Task the static model class
   */
  public static function model($className=__CLASS__)
  {
    return parent::model($className);
  }
  
  public function __toString()
  {
    return $this->description;
  }

  public function safeSave()
  {
    $regexps=$this->getRegexps();
    if (sizeof($regexps)>$this->entries)
    {
      $this->addError('regexps', Yii::t('delt', 'There are more regular expressions than expected entries.'));
    }
    $count=0;
    foreach($regexps as $regexp)
    {
      if($regexp)
      {
        if (@preg_match($regexp, 'mytesttext')===false)
        {
          $this->addError('regexps', Yii::t('delt', 'The regular expression at line {number} is invalid.', array('{number}'=>$count+1)));
        }      
      }
      $count++;
    }

    if ($this->hasErrors())
    {
      return false;
    }
    
    $date=DateTime::createFromFormat(DELT::getConvertedJQueryUIDateFormat(), $this->event_date);
    $this->event_date = $date->format('Y-m-d');
    
    try
    {
      $this->save();
      return true;
    }
    catch (Exception $e)
    {
      if (strpos($e->getMessage(), 'Invalid datetime format')!==false)
      {
        $this->addError('event_date', 'Invalid date');
      }
      return false;
    }
  }
  
  protected function beforeSave()
  {
    DELT::sanitizeProperties($this, array(
      'description',
      'hint',
      'regexps',
      )
    );
    
    return parent::beforeSave();
  }
  
  public function getRegexps()
  {
    if (is_array($this->_regexps))
    {
      return $this->_regexps;
    }
    if ($this->regexps)
    {
      $this->_regexps = array_map('trim', explode("\n", str_replace("\r", "", $this->regexps)));
    }
    else
    {
     $this->_regexps = array();
    }
    return $this->_regexps;
  }
  
  public function getRegexp($position)
  {
    return DELT::getValueFromArray($this->getRegexps(), $position, '');
  }
  
  public function getJournalEntriesFromFirm($firm_id)
  {
    return Journalentry::model()->getJournalEntriesByFirmAndTransaction($firm_id, $this->id);
  }
}
