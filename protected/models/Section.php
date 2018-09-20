<?php

/**
 * This is the model class for table "{{section}}".
 *
 * The followings are the available columns in table '{{section}}':
 * @property integer $id
 * @property integer $firm_id
 * @property string $name
 * @property integer $is_visible
 * @property integer $rank
 * @property string $color
 *
 * The followings are the available model relations:
 * @property Firm $firm
 */
class Section extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{section}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('firm_id, rank, color', 'required'),
			array('firm_id, is_visible, rank', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			array('color', 'match', 'pattern'=>'/^[0-9a-f]{6}$/'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, firm_id, name, is_visible, rank, color', 'safe', 'on'=>'search'),
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
			'name' => 'Name',
			'is_visible' => 'Is Visible',
			'rank' => 'Rank',
      'color' => 'Color',
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
		$criteria->compare('firm_id',$this->firm_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('is_visible',$this->is_visible);
		$criteria->compare('rank',$this->rank);
		$criteria->compare('color',$this->color);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

  public function belongingTo($firm_id, $order='rank ASC')
  {
    $this->getDbCriteria()->mergeWith(array(
        'condition'=>'t.firm_id = :firm_id',
        'params'=>array(':firm_id'=>$firm_id),
        'order'=>$order,
    ));
    return $this;
  }  
  
  public function afterSave()
  {
    $r = parent::afterSave();
    
    $criteria = new CDbCriteria();
    $criteria->condition = 'section_id = :section_id';
    $criteria->params = array(':section_id'=>$this->id);
    
    Journalentry::model()->updateAll(array('is_visible'=>$this->is_visible), $criteria);
    return $r;    
  }
  
  public static function maxRank($firm_id)
  {
    $maxRank = Yii::app()->db->createCommand()
      ->select('MAX(rank) as rank')
      ->from('{{section}} sect')
      ->where('sect.firm_id = :firm_id', array(':firm_id'=>$firm_id))
      ->queryScalar();
            
    return $maxRank;
  }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Layer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
  
  public function __toString()
  {
    return $this->name;
  }
}
