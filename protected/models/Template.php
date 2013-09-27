<?php

/**
 * Template class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013 Loris Tissino
 * @since 1.0
 */
/**
 * Template represents an example of a {@link Journalentry} that can be used for other similar journal entries.
 *
 * @property integer $id
 * @property integer $firm_id
 * @property string $description
 * @property string $info
 * @property integer $journalentry_id the original journalentry id (not stored in the db) 
 *
 * The followings are the available model relations:
 * @property Firm $firm
 * 
 * @package application.models
 * 
 */
class Template extends CActiveRecord
{
  
  public $journalentry_id;
  
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Template the static model class
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
    return '{{template}}';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('description', 'required'),
      array('firm_id', 'numerical', 'integerOnly'=>true),
      array('description', 'length', 'max'=>255),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('id, firm_id, description, info', 'safe', 'on'=>'search'),
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
      'firm_id' => 'Firm',
      'description' => Yii::t('delt', 'Description'),
      'info' => 'Info',
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
    $criteria->compare('firm_id',$this->firm_id);
    $criteria->compare('description',$this->description,true);
    $criteria->compare('info',$this->info,true);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
    ));
  }
  
  public function beforeSave()
  {
    if($journalentry = Journalentry::model()->findByPk($this->journalentry_id))
    {
      $accounts = array();
      foreach($journalentry->postings as $posting)
      {
        $accounts[$posting->account_id] = array('rank'=>$posting->rank, 'type'=>DELT::amount2type($posting->amount, false));
      }
      $this->info=serialize($accounts);
    }
    
    return parent::beforeSave();
  }
  
  public function getAccountsInvolved($currency)
  {
    $result=array();
    if(!$info=unserialize($this->info))
    {
      return $result;
    }
    

    $accounts = Account::model()->findAllByPk(array_keys($info));
    foreach($accounts as $account)
    {
      $result[$info[$account->id]['rank']]=array(
        'name'=> $account->code . ' - ' . $account->name,
        'debit'=>$info[$account->id]['type']=='Dr.' ? DELT::currency_value(0, $currency, false, true): '',
        'credit'=>$info[$account->id]['type']=='Cr.' ? DELT::currency_value(0, $currency, false, true): '',
      );
    }
    ksort($result);
    return $result;
  }
  
}
