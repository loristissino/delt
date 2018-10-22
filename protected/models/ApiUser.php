<?php

/**
 * This is the model class for table "{{apiuser}}".
 *
 * The followings are the available columns in table '{{apiuser}}':
 * @property integer $user_id
 * @property string $apikey
 * @property integer $uses
 * @property boolean $is_active
 *
 * The followings are the available model relations:
 * @property Users $user
 */
class ApiUser extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{apiuser}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, apikey', 'required'),
			array('user_id, uses, is_active', 'numerical', 'integerOnly'=>true),
			array('apikey', 'length', 'max'=>16),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, apikey, uses, is_active', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'User',
			'apikey' => 'Apikey',
			'uses' => 'Uses',
			'is_active' => 'Is Active',
		);
	}

	public static function getUserByApikey($apikey, $increaseUses = false)
	{
		if ($apiuser = ApiUser::model()->findByAttributes(array('apikey'=>$apikey, 'is_active'=>1)))
		{
			if ($increaseUses)
			{
				$apiuser->uses++;
				$apiuser->save(false);
			}
			$DEUser = DEUser::model()->findByPK($apiuser->user_id);
			$DEUser->apiuser = $apiuser;
			return $DEUser;
		}
		return null;
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('apikey',$this->apikey,true);
		$criteria->compare('uses',$this->uses);
		$criteria->compare('is_active',$this->is_active);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ApiUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
