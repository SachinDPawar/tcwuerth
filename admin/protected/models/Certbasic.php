<?php

/**
 * This is the model class for table "certbasic".
 *
 * The followings are the available columns in table 'certbasic':
 * @property string $Id
 * @property integer $FormatNo
 * @property string $TCFormat
 * @property integer $CustId
 * @property string $Rirs
 * @property string $TCNo
 * @property string $CertDate
 * @property string $PartDescription
 * @property string $LastModified
 * @property integer $ModifiedBy
 * @property string $CreationDate
 * @property integer $CreatedBy
 * @property string $Note
 * @property integer $ApprovedBy
 * @property integer $PreparedBy
 * @property integer $CheckedBy
 *
 * The followings are the available model relations:
 * @property Certattachments[] $certattachments
 * @property Users $preparedBy
 * @property Users $modifiedBy
 * @property Users $approvedBy
 * @property Users $checkedBy
 * @property Customerinfo $cust
 * @property Certbasicextra[] $certbasicextras
 * @property Certsections[] $certsections
 */
class Certbasic extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'certbasic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CustId, LastModified, Note', 'required'),
			array('FormatNo, CustId, ModifiedBy, CreatedBy, ApprovedBy, PreparedBy, CheckedBy', 'numerical', 'integerOnly'=>true),
			array('TCFormat, TCNo', 'length', 'max'=>50),
			array('PartDescription', 'length', 'max'=>250),
			array('Rirs, CertDate, CreationDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, FormatNo, TCFormat, CustId, Rirs, TCNo, CertDate, PartDescription, LastModified, ModifiedBy, CreationDate, CreatedBy, Note, ApprovedBy, PreparedBy, CheckedBy', 'safe', 'on'=>'search'),
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
			'certattachments' => array(self::HAS_MANY, 'Certattachments', 'certid'),
			'preparedBy' => array(self::BELONGS_TO, 'Users', 'PreparedBy'),
			'modifiedBy' => array(self::BELONGS_TO, 'Users', 'ModifiedBy'),
			'approvedBy' => array(self::BELONGS_TO, 'Users', 'ApprovedBy'),
			'checkedBy' => array(self::BELONGS_TO, 'Users', 'CheckedBy'),
			'cust' => array(self::BELONGS_TO, 'Customerinfo', 'CustId'),
			'certbasicextras' => array(self::HAS_MANY, 'Certbasicextra', 'CBID'),
			'certsections' => array(self::HAS_MANY, 'Certsections', 'CBID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'FormatNo' => 'Format No',
			'TCFormat' => 'Tcformat',
			'CustId' => 'Cust',
			'Rirs' => 'Rirs',
			'TCNo' => 'Tcno',
			'CertDate' => 'Cert Date',
			'PartDescription' => 'Part Description',
			'LastModified' => 'Last Modified',
			'ModifiedBy' => 'Modified By',
			'CreationDate' => 'Creation Date',
			'CreatedBy' => 'Created By',
			'Note' => 'Note',
			'ApprovedBy' => 'Approved By',
			'PreparedBy' => 'Prepared By',
			'CheckedBy' => 'Checked By',
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
		$criteria->compare('FormatNo',$this->FormatNo);
		$criteria->compare('TCFormat',$this->TCFormat,true);
		$criteria->compare('CustId',$this->CustId);
		$criteria->compare('Rirs',$this->Rirs,true);
		$criteria->compare('TCNo',$this->TCNo,true);
		$criteria->compare('CertDate',$this->CertDate,true);
		$criteria->compare('PartDescription',$this->PartDescription,true);
		$criteria->compare('LastModified',$this->LastModified,true);
		$criteria->compare('ModifiedBy',$this->ModifiedBy);
		$criteria->compare('CreationDate',$this->CreationDate,true);
		$criteria->compare('CreatedBy',$this->CreatedBy);
		$criteria->compare('Note',$this->Note,true);
		$criteria->compare('ApprovedBy',$this->ApprovedBy);
		$criteria->compare('PreparedBy',$this->PreparedBy);
		$criteria->compare('CheckedBy',$this->CheckedBy);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Certbasic the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
