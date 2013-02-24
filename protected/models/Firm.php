<?php

/**
 * This is the model class for table "{{firm}}".
 *
 * The followings are the available columns in table '{{firm}}':
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property integer $is_public
 * @property string $currency
 * @property string $csymbol
 * @property integer $language_id
 *
 * The followings are the available model relations:
 * @property Account[] $accounts
 * @property Users[] $tblUsers
 * @property Post[] $posts
 */
class Firm extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Firm the static model class
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
		return '{{firm}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, slug, currency, csymbol, language_id', 'required'),
			array('is_public, language_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('slug', 'length', 'max'=>32),
			array('currency', 'length', 'max'=>5),
			array('csymbol', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, slug, is_public, currency, csymbol, language_id', 'safe', 'on'=>'search'),
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
			'accounts' => array(self::HAS_MANY, 'Account', 'firm_id'),
			'tblUsers' => array(self::MANY_MANY, 'Users', '{{firm_user}}(firm_id, user_id)'),
			'posts' => array(self::HAS_MANY, 'Post', 'firm_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'slug' => 'Slug',
			'is_public' => 'Is Public',
			'currency' => 'Currency',
			'csymbol' => 'Csymbol',
			'language_id' => 'Language',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('is_public',$this->is_public);
		$criteria->compare('currency',$this->currency,true);
		$criteria->compare('csymbol',$this->csymbol,true);
		$criteria->compare('language_id',$this->language_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
