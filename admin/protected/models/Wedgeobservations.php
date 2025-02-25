<?php

/**
 * This is the model class for table "wedgeobservations".
 *
 * The followings are the available columns in table 'wedgeobservations':
 * @property string $Id
 * @property string $NatureSample
 * @property string $Size
 * @property string $Equipment
 * @property string $ConditionSample
 * @property string $Test
 * @property string $Sample
 * @property string $RequiredTS
 * @property string $ObservedTS
 * @property string $QtyTested
 * @property string $RirTestId
 *
 * The followings are the available model relations:
 * @property Rirtestdetail $rirTest
 */
class Wedgeobservations extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wedgeobservations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('RirTestId', 'required'),
			array('NatureSample', 'length', 'max'=>200),
			array('Size, Equipment, ConditionSample, Test, Sample', 'length', 'max'=>100),
			array('RequiredTS, ObservedTS', 'length', 'max'=>150),
			array('QtyTested', 'length', 'max'=>50),
			array('RirTestId', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, NatureSample, Size, Equipment, ConditionSample, Test, Sample, RequiredTS, ObservedTS, QtyTested, RirTestId', 'safe', 'on'=>'search'),
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
			'rirTest' => array(self::BELONGS_TO, 'Rirtestdetail', 'RirTestId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'NatureSample' => 'Nature Sample',
			'Size' => 'Size',
			'Equipment' => 'Equipment',
			'ConditionSample' => 'Condition Sample',
			'Test' => 'Test',
			'Sample' => 'Sample',
			'RequiredTS' => 'Required Ts',
			'ObservedTS' => 'Observed Ts',
			'QtyTested' => 'Qty Tested',
			'RirTestId' => 'Rir Test',
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

		$criteria->compare('Id',$this->Id,true);
		$criteria->compare('NatureSample',$this->NatureSample,true);
		$criteria->compare('Size',$this->Size,true);
		$criteria->compare('Equipment',$this->Equipment,true);
		$criteria->compare('ConditionSample',$this->ConditionSample,true);
		$criteria->compare('Test',$this->Test,true);
		$criteria->compare('Sample',$this->Sample,true);
		$criteria->compare('RequiredTS',$this->RequiredTS,true);
		$criteria->compare('ObservedTS',$this->ObservedTS,true);
		$criteria->compare('QtyTested',$this->QtyTested,true);
		$criteria->compare('RirTestId',$this->RirTestId,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Wedgeobservations the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
