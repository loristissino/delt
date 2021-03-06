<?php

/**
 * Language class file.
 *
 * @license http://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License
 * @author Loris Tissino <loris.tissino@gmail.com>
 * @copyright Copyright &copy; 2013-2017 Loris Tissino
 * @since 1.0
 */
/**
 * Language represents a language (or, better, a "locale"), in which account names can be written.
 *
 * @property integer $id
 * @property string $language_code
 * @property string $country_code
 * @property string $english_name
 * @property string $native_name
 * @property string $locale
 * @property integer $is_default (1 = generic default; 2 = main default)
 *
 * The followings are the available model relations:
 * @property Account[] $tblAccounts
 * 
 * @package application.models
 * 
 */
class Language extends CActiveRecord
{
  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Language the static model class
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
    return '{{language}}';
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules()
  {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
      array('language_code, english_name, native_name', 'required'),
      array('is_default', 'numerical', 'integerOnly'=>true),
      array('language_code, country_code', 'length', 'max'=>3),
      array('english_name, native_name', 'length', 'max'=>64),
      // The following rule is used by search().
      // Please remove those attributes that should not be searched.
      array('id, language_code, country_code, english_name, native_name, is_default', 'safe', 'on'=>'search'),
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
      'tblAccounts' => array(self::MANY_MANY, 'Account', '{{account_name}}(language_id, account_id)'),
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels()
  {
    return array(
      'id' => 'ID',
      'language_code' => 'Language Code',
      'country_code' => 'Country Code',
      'english_name' => 'English Name',
      'native_name' => 'Native Name',
      'is_default' => 'Is Default',
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
    $criteria->compare('language_code',$this->language_code,true);
    $criteria->compare('country_code',$this->country_code,true);
    $criteria->compare('english_name',$this->english_name,true);
    $criteria->compare('native_name',$this->native_name,true);
    $criteria->compare('is_default',$this->is_default);

    return new CActiveDataProvider($this, array(
      'criteria'=>$criteria,
    ));
  }
  
  public function scopes()
  {
    return array(
       'marked'=>array(
          'condition'=>'is_default > 0',
          ),
      );
  }
  
  public function __toString()
  {
    return sprintf('%s (%s_%s)', $this->english_name, $this->language_code, $this->country_code);
  }
  
  public function getComplete_name()
  {
    if($this->native_name)
    {
      return sprintf('%s_%s - %s / %s', $this->language_code, $this->country_code, $this->english_name, $this->native_name);
    }
    else
    {
      return sprintf('%s_%s - %s', $this->language_code, $this->country_code, $this->english_name);
    }
  }
  
  public function getLocale()
  {
    return $this->language_code . '_' . $this->country_code;
  }
  
  /**
   * Retrieves a language based on the locale provided.
   * @return Language the language found.
   */
  public function findByLocale($locale)
  {
    $info=explode('_', $locale);
    switch(sizeof($info))
    {
      case 1:
        $language_code=$info;
        $country_code=null;
        break;
      case 2:
        $language_code=$info[0];
        $country_code=$info[1];
        break;
      default:
        $language_code=null;
        $country_code=null;
    }
    return Language::model()->findByAttributes(array('language_code'=>$language_code, 'country_code'=>$country_code));
  }
  
  public function getAllLocales()
  {
    $languages = Language::model()->findAllSorted();
    $locales=array();
    foreach($languages as $language)
    {
      $locales[]=$language->getLocale();
    }
    return $locales;
  }
  
  public function findAllSorted($marked=false)
  {
    return $marked ? self::model()->marked()->findAll(array('order'=>'language_code, country_code')) : self::model()->findAll(array('order'=>'language_code, country_code'));
  }
    
}
