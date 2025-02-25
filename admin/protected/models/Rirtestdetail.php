<?php

/**
 * This is the model class for table "rirtestdetail".
 *
 * The followings are the available columns in table 'rirtestdetail':
 * @property string $Id
 * @property string $TestNo
 * @property string $RIRId
 * @property integer $TestId
 * @property integer $PlanId
 * @property string $TestName
 * @property string $TSeq
 * @property string $TUID
 * @property integer $SSID
 * @property integer $TMID
 * @property string $ExtraInfo
 * @property string $RevNo
 * @property string $RevDate
 * @property string $FormatNo
 * @property string $ReqDate
 * @property integer $AssignedTo
 * @property integer $AssignTL
 * @property string $TestDate
 * @property string $TestEndDate
 * @property string $Status
 * @property string $Remark
 * @property string $TNote
 * @property string $Note
 * @property string $LastModified
 * @property integer $ModifiedBy
 * @property integer $TestedBy
 * @property integer $ApprovedBy
 * @property string $ApprovedDate
 * @property string $ULRNo
 * @property string $CreationDate
 * @property double $Price
 * @property integer $Qty
 * @property double $Tax
 * @property integer $CID
 *
 * The followings are the available model relations:
 * @property Rirtestbasic[] $rirtestbasics
 * @property Receiptir $rIR
 * @property Tests $test
 * @property Testmethods $tM
 * @property Users $testedBy
 * @property Users $approvedBy
 * @property Rirtestobs[] $rirtestobs
 * @property Rirtestuploads[] $rirtestuploads
 */
class Rirtestdetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rirtestdetail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('RIRId, TestId, ReqDate', 'required'),
			array('TestId, PlanId, SSID, TMID, AssignedTo, AssignTL, ModifiedBy, TestedBy, ApprovedBy, Qty, CID', 'numerical', 'integerOnly'=>true),
			array('Price, Tax', 'numerical'),
			array('TestNo, RIRId, TSeq, TUID, RevNo, RevDate', 'length', 'max'=>20),
			array('TestName', 'length', 'max'=>100),
			array('FormatNo, Remark, ULRNo', 'length', 'max'=>50),
			array('Status', 'length', 'max'=>30),
			array('ExtraInfo, TestDate, TestEndDate, TNote, Note, LastModified, ApprovedDate, CreationDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, TestNo, RIRId, TestId, PlanId, TestName, TSeq, TUID, SSID, TMID, ExtraInfo, RevNo, RevDate, FormatNo, ReqDate, AssignedTo, AssignTL, TestDate, TestEndDate, Status, Remark, TNote, Note, LastModified, ModifiedBy, TestedBy, ApprovedBy, ApprovedDate, ULRNo, CreationDate, Price, Qty, Tax, CID', 'safe', 'on'=>'search'),
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
			'rirtestbasics' => array(self::HAS_MANY, 'Rirtestbasic', 'RTID'),
			'rIR' => array(self::BELONGS_TO, 'Receiptir', 'RIRId'),
			'test' => array(self::BELONGS_TO, 'Tests', 'TestId'),
			'tM' => array(self::BELONGS_TO, 'Testmethods', 'TMID'),
			'testedBy' => array(self::BELONGS_TO, 'Users', 'TestedBy'),
			'approvedBy' => array(self::BELONGS_TO, 'Users', 'ApprovedBy'),
			'rirtestobs' => array(self::HAS_MANY, 'Rirtestobs', 'RTID'),
			'rirtestuploads' => array(self::HAS_MANY, 'Rirtestuploads', 'rirtestid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'TestNo' => 'Test No',
			'RIRId' => 'Ririd',
			'TestId' => 'Test',
			'PlanId' => 'Plan',
			'TestName' => 'Test Name',
			'TSeq' => 'Tseq',
			'TUID' => 'Tuid',
			'SSID' => 'Ssid',
			'TMID' => 'Tmid',
			'ExtraInfo' => 'Extra Info',
			'RevNo' => 'Rev No',
			'RevDate' => 'Rev Date',
			'FormatNo' => 'Format No',
			'ReqDate' => 'Req Date',
			'AssignedTo' => 'Assigned To',
			'AssignTL' => 'Assign Tl',
			'TestDate' => 'Test Date',
			'TestEndDate' => 'Test End Date',
			'Status' => 'Status',
			'Remark' => 'Remark',
			'TNote' => 'Tnote',
			'Note' => 'Note',
			'LastModified' => 'Last Modified',
			'ModifiedBy' => 'Modified By',
			'TestedBy' => 'Tested By',
			'ApprovedBy' => 'Approved By',
			'ApprovedDate' => 'Approved Date',
			'ULRNo' => 'Ulrno',
			'CreationDate' => 'Creation Date',
			'Price' => 'Price',
			'Qty' => 'Qty',
			'Tax' => 'Tax',
			'CID' => 'Cid',
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
		$criteria->compare('TestNo',$this->TestNo,true);
		$criteria->compare('RIRId',$this->RIRId,true);
		$criteria->compare('TestId',$this->TestId);
		$criteria->compare('PlanId',$this->PlanId);
		$criteria->compare('TestName',$this->TestName,true);
		$criteria->compare('TSeq',$this->TSeq,true);
		$criteria->compare('TUID',$this->TUID,true);
		$criteria->compare('SSID',$this->SSID);
		$criteria->compare('TMID',$this->TMID);
		$criteria->compare('ExtraInfo',$this->ExtraInfo,true);
		$criteria->compare('RevNo',$this->RevNo,true);
		$criteria->compare('RevDate',$this->RevDate,true);
		$criteria->compare('FormatNo',$this->FormatNo,true);
		$criteria->compare('ReqDate',$this->ReqDate,true);
		$criteria->compare('AssignedTo',$this->AssignedTo);
		$criteria->compare('AssignTL',$this->AssignTL);
		$criteria->compare('TestDate',$this->TestDate,true);
		$criteria->compare('TestEndDate',$this->TestEndDate,true);
		$criteria->compare('Status',$this->Status,true);
		$criteria->compare('Remark',$this->Remark,true);
		$criteria->compare('TNote',$this->TNote,true);
		$criteria->compare('Note',$this->Note,true);
		$criteria->compare('LastModified',$this->LastModified,true);
		$criteria->compare('ModifiedBy',$this->ModifiedBy);
		$criteria->compare('TestedBy',$this->TestedBy);
		$criteria->compare('ApprovedBy',$this->ApprovedBy);
		$criteria->compare('ApprovedDate',$this->ApprovedDate,true);
		$criteria->compare('ULRNo',$this->ULRNo,true);
		$criteria->compare('CreationDate',$this->CreationDate,true);
		$criteria->compare('Price',$this->Price);
		$criteria->compare('Qty',$this->Qty);
		$criteria->compare('Tax',$this->Tax);
		$criteria->compare('CID',$this->CID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Rirtestdetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
