<?php

/**
 * This is the model class for table "{{event}}".
 *
 * The followings are the available columns in table '{{event}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $firm_id
 * @property integer $action
 * @property string $happened_at
 * @property string $content
 * @property string $referer
 * @property string $address
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Firm $firm
 */
class Event extends CActiveRecord
{
  
  const SITE_PAGE_SEEN              =    1;
  const SITE_CONTACT_FORM           =    2;
  const SITE_CONTACT_SENT           =    3;
  const USER_SIGNED_UP              = 1001;
  const USER_ACTIVATED_ACCOUNT      = 1002;
  const USER_SIGNED_UP_SOCIAL       = 1003;
  const USER_LOGGED_IN              = 1010;
  const USER_LOGGED_IN_SOCIAL       = 1011;
  const USER_LOGGED_OUT             = 1012;
  const USER_EDITED_ACCOUNT         = 1021;
  const USER_CHANGED_PASSWORD       = 1022;
  const FIRM_CREATED                = 2001;
  const FIRM_DELETED                = 2002;
  const FIRM_FORKED                 = 2003;
  const FIRM_CLEARED                = 2005;
  const FIRM_SHARED                 = 2011;
  const FIRM_JOINED                 = 2012;
  const FIRM_DECLINED               = 2013;
  const FIRM_DISOWNED               = 2014;
  const FIRM_FROZEN                 = 2021;
  const FIRM_UNFROZEN               = 2022;
  const FIRM_EDITED                 = 2031;
  const FIRM_EXPORTED               = 2041;
  const FIRM_IMPORTED               = 2042;
  const FIRM_SEEN                   = 2051;
  const FIRM_JOURNALENTRY_CREATED   = 2061;
  const FIRM_JOURNALENTRY_UPDATED   = 2062;
  const FIRM_JOURNALENTRY_DELETED   = 2062;
  const FIRM_JOURNALENTRIES_DELETED = 2062;
  const FIRM_JOURNAL_CLEARED        = 2064;
  const FIRM_COA_UPDATED            = 2071;
  
  
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Event the static model class
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
    return '{{event}}';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('action, happened_at', 'required'),
      array('user_id, firm_id, action', 'numerical', 'integerOnly'=>true),
      array('referer', 'length', 'max'=>255),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('id, user_id, firm_id, action, happened_at, content, referer, address', 'safe', 'on'=>'search'),
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
      'user' => array(self::BELONGS_TO, 'DEUser', 'user_id'),
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
      'user_id' => 'User',
      'firm_id' => 'Firm',
      'action' => 'Action',
      'happened_at' => 'Happened At',
      'content' => 'Content',
      'referer' => 'Referer',
      'address' => 'Address',
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
    $criteria->compare('user_id',$this->user_id);
    $criteria->compare('firm_id',$this->firm_id);
    $criteria->compare('action',$this->action);
    $criteria->compare('happened_at',$this->happened_at,true);
    $criteria->compare('content',$this->content,true);
    $criteria->compare('referer',$this->referer,true);

    $sort = new CSort;
    $sort->defaultOrder = 'happened_at DESC';

    return new CActiveDataProvider($this->with('firm')->with('user'), array(
      'criteria'=>$criteria,
      'pagination'=>array('pageSize'=>100),
      'sort'=> $sort,
    ));
  }
  
  public function log(DEUser $user=null, $firm_id=null, $action=null, $content='')
  {
    $event = new Event();
    $event->user_id = $user ? $user->id : null;
    $event->firm_id = $firm_id;
    $event->action = $action;
    $event->content = json_encode($content);
    $event->happened_at = new CDbExpression('NOW()');
    $event->referer = Yii::app()->request->getUrlReferrer();
    $event->address = Yii::app()->request->getUserHostAddress();
    $event->save();
  }
  
  public function getActionDescription()
  {
    $constants = $this->getClassConstants();
    return isset($constants[$this->action]) ? $constants[$this->action] : '';
  }
  
  public function getClassConstants()
  {
    $reflect = new ReflectionClass(get_class($this));
    return array_flip($reflect->getConstants());
  }
  
  public function getExternalReferer()
  {
    $excluded = Yii::app()->params['referer_excluded'];
    return substr($this->referer, 0, strlen($excluded))!=$excluded ? $this->referer : false;
  }
  
  
}
