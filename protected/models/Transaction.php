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
 * @property integer $points
 * @property integer $penalties
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
      array('exercise_id, rank, points, penalties', 'numerical', 'integerOnly'=>true),
      array('hint', 'safe'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, exercise_id, event_date, description, hint', 'safe', 'on'=>'search'),
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
      'exercise_id' => 'Exercise',
      'rank' => 'Rank',
      'event_date' => 'Event Date',
      'description' => 'Description',
      'hint' => 'Hint',
      'points' => 'Points',
      'penalties' => 'Penalties',
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
    $criteria->compare('points',$this->points,true);
    $criteria->compare('penalties',$this->penalties,true);

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
  
}
