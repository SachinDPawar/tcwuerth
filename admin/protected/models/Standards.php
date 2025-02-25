<?php

/**
 * This is the model class for table "standards".
 *
 * The followings are the available columns in table 'standards':
 * @property integer $Id
 * @property string $Standard
 * @property integer $IndId
 * @property string $Description
 * @property integer $Status
 *
 * The followings are the available model relations:
 * @property Industry $ind
 * @property Substandards[] $substandards
 */
class Standards extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'standards';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Standard', 'required'),
			array('IndId, Status', 'numerical', 'integerOnly'=>true),
			array('Standard', 'length', 'max'=>100),
			array('Description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Standard, IndId, Description, Status', 'safe', 'on'=>'search'),
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
			'ind' => array(self::BELONGS_TO, 'Industry', 'IndId'),
			'substandards' => array(self::HAS_MANY, 'Substandards', 'StdId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'Standard' => 'Standard',
			'IndId' => 'Ind',
			'Description' => 'Description',
			'Status' => 'Status',
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

		$criteria->compare('Id',$this->Id);
		$criteria->compare('Standard',$this->Standard,true);
		$criteria->compare('IndId',$this->IndId);
		$criteria->compare('Description',$this->Description,true);
		$criteria->compare('Status',$this->Status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Standards the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
