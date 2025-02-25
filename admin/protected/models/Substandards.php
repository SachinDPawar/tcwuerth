<?php

/**
 * This is the model class for table "substandards".
 *
 * The followings are the available columns in table 'substandards':
 * @property integer $Id
 * @property integer $StdId
 * @property string $Grade
 * @property string $ExtraInfo
 * @property integer $TestId
 * @property double $Cost
 *
 * The followings are the available model relations:
 * @property Stdsubdetails[] $stdsubdetails
 * @property Stdsubplans[] $stdsubplans
 * @property Standards $std
 * @property Tests $test
 */
class Substandards extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'substandards';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('StdId, Grade', 'required'),
			array('StdId, TestId', 'numerical', 'integerOnly'=>true),
			array('Cost', 'numerical'),
			array('Grade', 'length', 'max'=>250),
			array('ExtraInfo', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, StdId, Grade, ExtraInfo, TestId, Cost', 'safe', 'on'=>'search'),
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
			'stdsubdetails' => array(self::HAS_MANY, 'Stdsubdetails', 'SubStdId'),
			'stdsubplans' => array(self::HAS_MANY, 'Stdsubplans', 'SubStdId'),
			'std' => array(self::BELONGS_TO, 'Standards', 'StdId'),
			'test' => array(self::BELONGS_TO, 'Tests', 'TestId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'StdId' => 'Std',
			'Grade' => 'Grade',
			'ExtraInfo' => 'Extra Info',
			'TestId' => 'Test',
			'Cost' => 'Cost',
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
		$criteria->compare('StdId',$this->StdId);
		$criteria->compare('Grade',$this->Grade,true);
		$criteria->compare('ExtraInfo',$this->ExtraInfo,true);
		$criteria->compare('TestId',$this->TestId);
		$criteria->compare('Cost',$this->Cost);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Substandards the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
