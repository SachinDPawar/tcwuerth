<?php

/**
 * This is the model class for table "threadlapbasic".
 *
 * The followings are the available columns in table 'threadlapbasic':
 * @property string $Id
 * @property string $RirTestId
 * @property string $Sample
 * @property string $HTBatchNo
 * @property string $Orientation
 * @property string $Etchant
 * @property string $Magnification
 * @property string $PolSampSize
 * @property string $TestReportNo
 * @property string $HeatTreat
 * @property string $TestTemp
 * @property string $PlantLoc
 * @property string $Equipment
 * @property string $ObsRemark
 *
 * The followings are the available model relations:
 * @property Rirtestdetail $rirTest
 * @property Threadlapobs[] $threadlapobs
 */
class Threadlapbasic extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'threadlapbasic';
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
			array('RirTestId', 'length', 'max'=>20),
			array('Sample, HTBatchNo, Orientation, Etchant, Magnification, PolSampSize, TestReportNo, HeatTreat, TestTemp, PlantLoc, Equipment', 'length', 'max'=>250),
			array('ObsRemark', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, RirTestId, Sample, HTBatchNo, Orientation, Etchant, Magnification, PolSampSize, TestReportNo, HeatTreat, TestTemp, PlantLoc, Equipment, ObsRemark', 'safe', 'on'=>'search'),
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
			'threadlapobs' => array(self::HAS_MANY, 'Threadlapobs', 'ThreadId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'RirTestId' => 'Rir Test',
			'Sample' => 'Sample',
			'HTBatchNo' => 'Htbatch No',
			'Orientation' => 'Orientation',
			'Etchant' => 'Etchant',
			'Magnification' => 'Magnification',
			'PolSampSize' => 'Pol Samp Size',
			'TestReportNo' => 'Test Report No',
			'HeatTreat' => 'Heat Treat',
			'TestTemp' => 'Test Temp',
			'PlantLoc' => 'Plant Loc',
			'Equipment' => 'Equipment',
			'ObsRemark' => 'Obs Remark',
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
		$criteria->compare('RirTestId',$this->RirTestId,true);
		$criteria->compare('Sample',$this->Sample,true);
		$criteria->compare('HTBatchNo',$this->HTBatchNo,true);
		$criteria->compare('Orientation',$this->Orientation,true);
		$criteria->compare('Etchant',$this->Etchant,true);
		$criteria->compare('Magnification',$this->Magnification,true);
		$criteria->compare('PolSampSize',$this->PolSampSize,true);
		$criteria->compare('TestReportNo',$this->TestReportNo,true);
		$criteria->compare('HeatTreat',$this->HeatTreat,true);
		$criteria->compare('TestTemp',$this->TestTemp,true);
		$criteria->compare('PlantLoc',$this->PlantLoc,true);
		$criteria->compare('Equipment',$this->Equipment,true);
		$criteria->compare('ObsRemark',$this->ObsRemark,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Threadlapbasic the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
