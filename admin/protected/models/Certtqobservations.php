<?php

/**
 * This is the model class for table "certtqobservations".
 *
 * The followings are the available columns in table 'certtqobservations':
 * @property string $Id
 * @property string $CertTestId
 * @property string $LabNo
 * @property string $TestId
 * @property string $SeqNo
 * @property string $HeatNo
 * @property string $BatchCode
 * @property string $Remark
 * @property string $ShowInCert
 * @property string $LastModified
 * @property string $Torque
 * @property string $Force
 * @property string $Coff_Friction
 *
 * The followings are the available model relations:
 * @property Certtest $certTest
 */
class Certtqobservations extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'certtqobservations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CertTestId, BatchCode, LastModified', 'required'),
			array('CertTestId, TestId, ShowInCert', 'length', 'max'=>20),
			array('LabNo, Remark', 'length', 'max'=>50),
			array('SeqNo', 'length', 'max'=>11),
			array('HeatNo, BatchCode', 'length', 'max'=>100),
			array('Torque, Force, Coff_Friction', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, CertTestId, LabNo, TestId, SeqNo, HeatNo, BatchCode, Remark, ShowInCert, LastModified, Torque, Force, Coff_Friction', 'safe', 'on'=>'search'),
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
			'certTest' => array(self::BELONGS_TO, 'Certtest', 'CertTestId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'CertTestId' => 'Cert Test',
			'LabNo' => 'Lab No',
			'TestId' => 'Test',
			'SeqNo' => 'Seq No',
			'HeatNo' => 'Heat No',
			'BatchCode' => 'Batch Code',
			'Remark' => 'Remark',
			'ShowInCert' => 'Show In Cert',
			'LastModified' => 'Last Modified',
			'Torque' => 'Torque',
			'Force' => 'Force',
			'Coff_Friction' => 'Coff Friction',
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
		$criteria->compare('CertTestId',$this->CertTestId,true);
		$criteria->compare('LabNo',$this->LabNo,true);
		$criteria->compare('TestId',$this->TestId,true);
		$criteria->compare('SeqNo',$this->SeqNo,true);
		$criteria->compare('HeatNo',$this->HeatNo,true);
		$criteria->compare('BatchCode',$this->BatchCode,true);
		$criteria->compare('Remark',$this->Remark,true);
		$criteria->compare('ShowInCert',$this->ShowInCert,true);
		$criteria->compare('LastModified',$this->LastModified,true);
		$criteria->compare('Torque',$this->Torque,true);
		$criteria->compare('Force',$this->Force,true);
		$criteria->compare('Coff_Friction',$this->Coff_Friction,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Certtqobservations the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
