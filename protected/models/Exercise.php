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
      array('user_id, firm_id', 'numerical', 'integerOnly'=>true),
      array('slug', 'SlugValidator', 'model'=>Exercise::model()),
      array('title, description', 'length', 'max'=>255),
      array('introduction', 'safe'),
      // The following rule is used by search().
      // @todo Please remove those attributes that should not be searched.
      array('id, user_id, firm_id, slug, title, description, introduction', 'safe', 'on'=>'search'),
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
      'introduction' => Yii::t('delt', 'Introduction'),
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

  public function invite($users=array(), $method=61, $session='')
  {
    $count = 0;
    foreach($users as $username)
    {
      $username=trim($username);
      try
      {
        if ($DEUser = DEUser::model()->getBy('username', $username))
        {
          $challenge = new Challenge();
          $challenge->setInitialDefaults();
          $challenge->exercise_id = $this->id;
          $challenge->instructor_id = Yii::app()->controller->DEUser->id;
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
  
  
}
