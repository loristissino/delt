<?php
/**
 * DEUser class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013 Loris Tissino
 * @since 1.0
 */
/** DEUser represents a single user of DELT.
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $activkey
 * @property integer $superuser
 * @property integer $status
 * @property string $create_at
 * @property string $lastvisit_at
 *
 * The followings are the available model relations:
 * @property Firm[] $tblFirms
 * @property Profiles $profiles
 * @property Challenge[] $challenges
 * 
 * @package application.models
 */
class DEUser extends CActiveRecord
{
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return DEUser the static model class
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
    return '{{users}}';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('create_at', 'required'),
      array('superuser, status', 'numerical', 'integerOnly'=>true),
      array('username', 'length', 'max'=>20),
      array('password, email, activkey', 'length', 'max'=>128),
      array('lastvisit_at', 'safe'),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('id, username, password, email, activkey, superuser, status, create_at, lastvisit_at', 'safe', 'on'=>'search'),
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
      'firms' => array(self::MANY_MANY, 'Firm', '{{firm_user}}(user_id, firm_id)', 'condition'=>'status > 0 AND role = "O"'),
      'wfirms' => array(self::MANY_MANY, 'Firm', '{{firm_user}}(user_id, firm_id)', 'condition'=>'status > 0 AND role = "I"'),
      'profiles' => array(self::HAS_ONE, 'Profiles', 'user_id'),
      'id0' => array(self::BELONGS_TO, 'Profiles', 'id'),
      'challenges' => array(self::HAS_MANY, 'Challenge', 'user_id'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'username' => 'Username',
      'password' => 'Password',
      'email' => 'Email',
      'activkey' => 'Activkey',
      'superuser' => 'Superuser',
      'status' => 'Status',
      'create_at' => 'Create At',
      'lastvisit_at' => 'Lastvisit At',
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
    $criteria->compare('username',$this->username,true);
    $criteria->compare('password',$this->password,true);
    $criteria->compare('email',$this->email,true);
    $criteria->compare('activkey',$this->activkey,true);
    $criteria->compare('superuser',$this->superuser);
    $criteria->compare('status',$this->status);
    $criteria->compare('create_at',$this->create_at,true);
    $criteria->compare('lastvisit_at',$this->lastvisit_at,true);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
    ));
  }
  
  public function getFirmsAsDataProvider()
  {
    $sort = new CSort;
    $sort->defaultOrder = 'name ASC';
    $sort->attributes = array(
        'code'=>'name',
        'slug'=>'slug',
    );    
    
    return new CActiveDataProvider('Firm', array(
      'criteria'=>array(
        'with'=>array('tblUsers'=>array(
          'on'=>'user.id = ' . $this->id,
          'together'=>true,
          'joinType' => 'INNER JOIN',
          ),
        ),
      ),
      'pagination'=>array(
          'pageSize'=>30,
          ),
      'sort'=>$sort,
      )
    );
  }
  
  public function getBy($key, $value)
  {
    return $this->findByAttributes(array($key=>$value));
  }
  
  public function getProfile()
  {
    if($u = WebUser::model(Yii::app()->user->id))
    {
      return $u->profile;
    }
  }
  
  public function canCreateFirms()
  {
    return (($this->status > 0) && $this->profile->allowed_firms - sizeof($this->firms) > 0);
  }
  
  public function __toString()
  {
    return $this->username;
  }
  
  public function getOpenChallenges()
  {
    return Challenge::model()->forUser($this->id)->started()->completed(false)->findAll();
  }

  
}
