<?php

/**
 * Task class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2015 Loris Tissino
 * @since 1.8
 */
/**
 * Task represents a single task belonging to an {@link Exercise}
 *
 * @property integer $id
 * @property integer $exercise_id
 * @property string $event_date
 * @property string $description
 * @property string $hint
 * @property string $je_ranks
 * 
 *
 * The followings are the available model relations:
 * @property Challenge[] $challenges
 * @property Exercise $exercise 
 * @property Journalentry[] $journalentries
 * 
 * @package application.models
 * 
 */

class Task extends CActiveRecord
{
  /**
   * @return string the associated database table name
   */
  public function tableName()
  {
    return '{{task}}';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('exercise_id, event_date, description, je_ranks', 'required'),
      array('exercise_id', 'numerical', 'integerOnly'=>true),
      array('je_ranks', 'length', 'max'=>255),
      array('hint', 'safe'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, exercise_id, event_date, description, hint, je_ranks', 'safe', 'on'=>'search'),
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
      'challenges' => array(self::HAS_MANY, 'Challenge', 'task_id'),
      'exercise' => array(self::BELONGS_TO, 'Exercise', 'exercise_id'),
      'journalentries' => array(self::HAS_MANY, 'Journalentry', 'task_id'),
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
      'event_date' => 'Event Date',
      'description' => 'Description',
      'hint' => 'Hint',
      'je_ranks' => 'Je Ranks',
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
    $criteria->compare('event_date',$this->event_date,true);
    $criteria->compare('description',$this->description,true);
    $criteria->compare('hint',$this->hint,true);
    $criteria->compare('je_ranks',$this->je_ranks,true);

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
}
