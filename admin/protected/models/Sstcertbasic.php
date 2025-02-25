<?php

/**
 * This is the model class for table "sstcertbasic".
 *
 * The followings are the available columns in table 'sstcertbasic':
 * @property string $Id
 * @property string $CertDate
 * @property string $To
 * @property string $Component
 * @property string $BatchNo
 * @property string $LoadedOn
 * @property string $SampleNos
 * @property string $Hrs
 * @property string $OnDate
 * @property string $Remarks
 * @property string $Ref
 * @property string $SerialNo
 * @property string $LastModified
 * @property string $ModifiedBy
 * @property string $CreationDate
 * @property string $TestedBy
 * @property string $ApprovedBy
 */
class Sstcertbasic extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sstcertbasic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('To, Remarks, Ref', 'length', 'max'=>250),
			array('Component', 'length', 'max'=>500),
			array('BatchNo, SampleNos, SerialNo', 'length', 'max'=>100),
			array('Hrs', 'length', 'max'=>50),
			array('ModifiedBy, TestedBy, ApprovedBy', 'length', 'max'=>20),
			array('CertDate, LoadedOn, OnDate, LastModified, CreationDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, CertDate, To, Component, BatchNo, LoadedOn, SampleNos, Hrs, OnDate, Remarks, Ref, SerialNo, LastModified, ModifiedBy, CreationDate, TestedBy, ApprovedBy', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'CertDate' => 'Cert Date',
			'To' => 'To',
			'Component' => 'Component',
			'BatchNo' => 'Batch No',
			'LoadedOn' => 'Loaded On',
			'SampleNos' => 'Sample Nos',
			'Hrs' => 'Hrs',
			'OnDate' => 'On Date',
			'Remarks' => 'Remarks',
			'Ref' => 'Ref',
			'SerialNo' => 'Serial No',
			'LastModified' => 'Last Modified',
			'ModifiedBy' => 'Modified By',
			'CreationDate' => 'Creation Date',
			'TestedBy' => 'Tested By',
			'ApprovedBy' => 'Approved By',
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
		$criteria->compare('CertDate',$this->CertDate,true);
		$criteria->compare('To',$this->To,true);
		$criteria->compare('Component',$this->Component,true);
		$criteria->compare('BatchNo',$this->BatchNo,true);
		$criteria->compare('LoadedOn',$this->LoadedOn,true);
		$criteria->compare('SampleNos',$this->SampleNos,true);
		$criteria->compare('Hrs',$this->Hrs,true);
		$criteria->compare('OnDate',$this->OnDate,true);
		$criteria->compare('Remarks',$this->Remarks,true);
		$criteria->compare('Ref',$this->Ref,true);
		$criteria->compare('SerialNo',$this->SerialNo,true);
		$criteria->compare('LastModified',$this->LastModified,true);
		$criteria->compare('ModifiedBy',$this->ModifiedBy,true);
		$criteria->compare('CreationDate',$this->CreationDate,true);
		$criteria->compare('TestedBy',$this->TestedBy,true);
		$criteria->compare('ApprovedBy',$this->ApprovedBy,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sstcertbasic the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
