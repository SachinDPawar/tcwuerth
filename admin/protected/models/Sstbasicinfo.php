<?php

/**
 * This is the model class for table "sstbasicinfo".
 *
 * The followings are the available columns in table 'sstbasicinfo':
 * @property string $Id
 * @property string $ReportNo
 * @property string $BatchNo
 * @property string $LabNo
 * @property string $ReceiptOn
 * @property string $RefStd
 * @property string $CoatingSystem
 * @property string $CustomerName
 * @property string $ReportDate
 * @property string $LastModified
 * @property string $LoadingDate
 * @property string $CompleteDate
 * @property string $SaltSolnConc
 * @property string $FogCollection
 * @property string $ChemberTemp
 * @property string $TestDuration
 * @property string $PhTestSoln
 * @property string $PhCollectedSample
 * @property string $Angle
 * @property string $Interval
 * @property string $Sample
 * @property string $LoadTime
 * @property string $UnloadTime
 * @property string $Remark
 * @property string $Ref
 * @property string $Note
 * @property string $Status
 * @property string $TestedBy
 * @property string $ApprovedBy
 * @property string $ModifiedBy
 *
 * The followings are the available model relations:
 * @property Sstobservations[] $sstobservations
 */
class Sstbasicinfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sstbasicinfo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('LastModified', 'required'),
			array('ReportNo, BatchNo, LabNo, LoadTime, UnloadTime, Remark, Status', 'length', 'max'=>50),
			array('RefStd, CoatingSystem, Sample', 'length', 'max'=>250),
			array('CustomerName', 'length', 'max'=>150),
			array('SaltSolnConc, FogCollection, ChemberTemp, TestDuration, PhTestSoln, PhCollectedSample, Angle, Interval', 'length', 'max'=>100),
			array('Ref', 'length', 'max'=>500),
			array('TestedBy, ApprovedBy, ModifiedBy', 'length', 'max'=>20),
			array('ReceiptOn, ReportDate, LoadingDate, CompleteDate, Note', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, ReportNo, BatchNo, LabNo, ReceiptOn, RefStd, CoatingSystem, CustomerName, ReportDate, LastModified, LoadingDate, CompleteDate, SaltSolnConc, FogCollection, ChemberTemp, TestDuration, PhTestSoln, PhCollectedSample, Angle, Interval, Sample, LoadTime, UnloadTime, Remark, Ref, Note, Status, TestedBy, ApprovedBy, ModifiedBy', 'safe', 'on'=>'search'),
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
			'sstobservations' => array(self::HAS_MANY, 'Sstobservations', 'SstBasicId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'ReportNo' => 'Report No',
			'BatchNo' => 'Batch No',
			'LabNo' => 'Lab No',
			'ReceiptOn' => 'Receipt On',
			'RefStd' => 'Ref Std',
			'CoatingSystem' => 'Coating System',
			'CustomerName' => 'Customer Name',
			'ReportDate' => 'Report Date',
			'LastModified' => 'Last Modified',
			'LoadingDate' => 'Loading Date',
			'CompleteDate' => 'Complete Date',
			'SaltSolnConc' => 'Salt Soln Conc',
			'FogCollection' => 'Fog Collection',
			'ChemberTemp' => 'Chember Temp',
			'TestDuration' => 'Test Duration',
			'PhTestSoln' => 'Ph Test Soln',
			'PhCollectedSample' => 'Ph Collected Sample',
			'Angle' => 'Angle',
			'Interval' => 'Interval',
			'Sample' => 'Sample',
			'LoadTime' => 'Load Time',
			'UnloadTime' => 'Unload Time',
			'Remark' => 'Remark',
			'Ref' => 'Ref',
			'Note' => 'Note',
			'Status' => 'Status',
			'TestedBy' => 'Tested By',
			'ApprovedBy' => 'Approved By',
			'ModifiedBy' => 'Modified By',
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
		$criteria->compare('ReportNo',$this->ReportNo,true);
		$criteria->compare('BatchNo',$this->BatchNo,true);
		$criteria->compare('LabNo',$this->LabNo,true);
		$criteria->compare('ReceiptOn',$this->ReceiptOn,true);
		$criteria->compare('RefStd',$this->RefStd,true);
		$criteria->compare('CoatingSystem',$this->CoatingSystem,true);
		$criteria->compare('CustomerName',$this->CustomerName,true);
		$criteria->compare('ReportDate',$this->ReportDate,true);
		$criteria->compare('LastModified',$this->LastModified,true);
		$criteria->compare('LoadingDate',$this->LoadingDate,true);
		$criteria->compare('CompleteDate',$this->CompleteDate,true);
		$criteria->compare('SaltSolnConc',$this->SaltSolnConc,true);
		$criteria->compare('FogCollection',$this->FogCollection,true);
		$criteria->compare('ChemberTemp',$this->ChemberTemp,true);
		$criteria->compare('TestDuration',$this->TestDuration,true);
		$criteria->compare('PhTestSoln',$this->PhTestSoln,true);
		$criteria->compare('PhCollectedSample',$this->PhCollectedSample,true);
		$criteria->compare('Angle',$this->Angle,true);
		$criteria->compare('Interval',$this->Interval,true);
		$criteria->compare('Sample',$this->Sample,true);
		$criteria->compare('LoadTime',$this->LoadTime,true);
		$criteria->compare('UnloadTime',$this->UnloadTime,true);
		$criteria->compare('Remark',$this->Remark,true);
		$criteria->compare('Ref',$this->Ref,true);
		$criteria->compare('Note',$this->Note,true);
		$criteria->compare('Status',$this->Status,true);
		$criteria->compare('TestedBy',$this->TestedBy,true);
		$criteria->compare('ApprovedBy',$this->ApprovedBy,true);
		$criteria->compare('ModifiedBy',$this->ModifiedBy,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sstbasicinfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
