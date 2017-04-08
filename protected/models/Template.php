<?php

/**
 * Template class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.0
 */
/**
 * Template represents an example of a {@link Journalentry} that can be used for other similar journal entries.
 *
 * @property integer $id
 * @property integer $firm_id
 * @property boolean $automatic
 * @property string $description
 * @property string $info
 * @property integer $journalentry_id the original journalentry id (not stored in the db) 
 * @property array $postingtypes (not stored in the db as such)
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
  public $methods;
  public $postings;
  
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
      array('automatic', 'boolean'),
      array('description', 'length', 'max'=>255),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('id, firm_id, automatic, description, info', 'safe', 'on'=>'search'),
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
      'automatic' => Yii::t('delt', 'Automatic'),
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
    $criteria->compare('automatica',$this->automatic);
    $criteria->compare('description',$this->description,true);
    $criteria->compare('info',$this->info,true);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
    ));
  }
  
  public function abbreviatedDescription($chars=15, $glue=' ')
  {
    return DELT::firstWordsOfString($this->description, $chars, $glue);
  }
  
  public function belongingTo($firm_id)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'t.firm_id = :firm_id',
        'params'=>array(':firm_id'=>$firm_id),
    ));
    return $this;
  }
  
  public function automatic($automatic)
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>($automatic?'automatic = 1':'automatic = 0'),
    ));
    return $this;
  }

  
  
  
  public function getMethods()
  {
    return array(
      '$'=>Yii::t('delt', 'Ask'),
      '?'=>Yii::t('delt', 'Close'),
      '='=>Yii::t('delt', 'Balance Anyway'),
      '/'=>Yii::t('delt', 'Balance On Match'),
    );
  }
  
  public function beforeSave()
  {
    if(sizeof($this->postings))
    {
      $this->info=serialize($this->postings );
    }
    
    return parent::beforeSave();
  }
  
  public function getAccountsInvolved($firm)
  {
    $result=array();
    if(!$info=unserialize($this->info))
    {
      return $result;  // returns an empty array
    }
    
    $accounts = Account::model()->findAllByPk(array_keys($info));
    
    $total = 0;
    $checkedMethod = '';
    $balanced_account_id = 0; // the last account for which we have a balance method
    foreach($accounts as $account)
    {
      $method = DELT::getValueFromArray($info[$account->id], 'method', '$');
      if($method=='=' || $method=='/')
      {
        $balanced_account_id = $account->id;
        $checkedMethod = $method;
      }
      
      $amount = $method=='?' ? -$account->consolidatedBalance: 0;
      $total += $amount;
      
      $comment = DELT::getValueFromArray($info[$account->id], 'comment', '');
      $item = array(
        'id'=> $account->id,
        'name'=> $account->getCodeAndName($firm) . ($comment? (' # '.$comment) :''),
        'outstanding_balance' => $account->outstanding_balance,
        'debitfromtemplate'=>$info[$account->id]['type']=='Dr.',
        'creditfromtemplate'=>$info[$account->id]['type']=='Cr.',
        'method'=>$method,
        'amount'=>$amount,
        'comment'=>$comment,
      );

      if($amount>0)
      {
        $item['debit']=$amount;
      }
      if($amount<0)
      {
        $item['credit']=-$amount;
      }
      
      $result[$info[$account->id]['rank']]=$item;

    }
    
    if($balanced_account_id)
    {
      if($total>0)
      {
        // if the method is "=", we close anyway, else we check whether we have the correct outstanding balance
        $a = ($checkedMethod=='=' || $result[$info[$balanced_account_id]['rank']]['outstanding_balance']=='C') ? $total : 0;
        $result[$info[$balanced_account_id]['rank']]['credit']=$a;
      }
      else
      {
        // if the method is "=", we close anyway, else we check whether we have the correct outstanding balance
        $a = ($checkedMethod=='=' || $result[$info[$balanced_account_id]['rank']]['outstanding_balance']=='D') ? -$total : 0;
        $result[$info[$balanced_account_id]['rank']]['debit']=$a;
      } 
    }
    
    ksort($result);
    return $result;
  }
  
  public function acquirePostingsFromForm($values=array())
  {
    $accounts = array();
      
    $rank=1;
    foreach($values['method'] as $k=>$v)
    {
      $accounts[$k]=array(
        'rank'=>$rank++,
        'type'=>DELT::amount2type(DELT::getValueFromArray($values['amount'], $k, 1), false),
        'method'=>$v,
        'comment'=>DELT::getValueFromArray($values['comment'], $k, ''),
        );
    }
    $this->postings = $accounts;
  }
  
  public function acquireRawPostings($values=array(), $firm)
  {
    $this->postings = array();
    foreach($values as $v)
    {
      $code = explode(' ', $v['name'])[0];
      if($account = $firm->findAccount($code))
      {
        $this->postings[] = array(
          'account_name'=>$account->getCodeAndName($firm),
          'account_id'=>$account->id,
          'amount'=>DELT::currency2decimal($v['debit'], $firm->currency)-DELT::currency2decimal($v['credit'], $firm->currency),
          'comment'=>DELT::findComment($v['name']),
        );
      }
    }
    
  }

  public function acquirePostingsFromJE(JournalEntry $je, $firm)
  {
    $this->firm_id = $firm->id;
    $this->postings = array();
    foreach($je->postings as $posting)
    {
      $this->postings[] = array(
        'account_name'=>$posting->account->getCodeAndName($firm),
        'account_id'=>$posting->account->id,
        'amount'=>$posting->amount,
        'comment'=>$posting->comment,
        );
    }
  }

  
}
