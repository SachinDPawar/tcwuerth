<?php

/**
 * This is the model class for table "receiptir".
 *
 * The followings are the available columns in table 'receiptir':
 * @property string $Id
 * @property string $LabNo
 * @property string $BatchCode
 * @property string $BatchNo
 * @property string $HeatNo
 * @property string $SampleName
 * @property string $Description
 * @property string $RefPurchaseOrder
 * @property string $IsMdsTds
 * @property integer $MdsTdsId
 * @property integer $SupplierId
 * @property integer $CustomerId
 * @property string $RegDate
 * @property string $CreationDate
 * @property integer $EnteredBy
 * @property string $Status
 * @property integer $IndId
 * @property integer $BranchId
 * @property string $qst
 * @property integer $ModifiedBy
 * @property string $LastModified
 * @property integer $PDFG
 * @property integer $CID
 *
 * The followings are the available model relations:
 * @property Customerinfo $customer
 * @property Suppliers $supplier
 * @property Industry $ind
 * @property Mdstds $mdsTds
 * @property Rirextras[] $rirextrases
 * @property Rirtestdetail[] $rirtestdetails
 */
class Receiptir extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'receiptir';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CreationDate, IndId', 'required'),
			array('MdsTdsId, SupplierId, CustomerId, EnteredBy, IndId, BranchId, ModifiedBy, PDFG, CID', 'numerical', 'integerOnly'=>true),
			array('LabNo', 'length', 'max'=>100),
			array('BatchCode, BatchNo, HeatNo, IsMdsTds', 'length', 'max'=>50),
			array('SampleName', 'length', 'max'=>250),
			array('RefPurchaseOrder', 'length', 'max'=>200),
			array('Status', 'length', 'max'=>20),
			array('Description, RegDate, qst, LastModified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, LabNo, BatchCode, BatchNo, HeatNo, SampleName, Description, RefPurchaseOrder, IsMdsTds, MdsTdsId, SupplierId, CustomerId, RegDate, CreationDate, EnteredBy, Status, IndId, BranchId, qst, ModifiedBy, LastModified, PDFG, CID', 'safe', 'on'=>'search'),
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
			'customer' => array(self::BELONGS_TO, 'Customerinfo', 'CustomerId'),
			'supplier' => array(self::BELONGS_TO, 'Suppliers', 'SupplierId'),
			'ind' => array(self::BELONGS_TO, 'Industry', 'IndId'),
			'mdsTds' => array(self::BELONGS_TO, 'Mdstds', 'MdsTdsId'),
			'rirextrases' => array(self::HAS_MANY, 'Rirextras', 'RIRId'),
			'rirtestdetails' => array(self::HAS_MANY, 'Rirtestdetail', 'RIRId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'LabNo' => 'Lab No',
			'BatchCode' => 'Batch Code',
			'BatchNo' => 'Batch No',
			'HeatNo' => 'Heat No',
			'SampleName' => 'Sample Name',
			'Description' => 'Description',
			'RefPurchaseOrder' => 'Ref Purchase Order',
			'IsMdsTds' => 'Is Mds Tds',
			'MdsTdsId' => 'Mds Tds',
			'SupplierId' => 'Supplier',
			'CustomerId' => 'Customer',
			'RegDate' => 'Reg Date',
			'CreationDate' => 'Creation Date',
			'EnteredBy' => 'Entered By',
			'Status' => 'Status',
			'IndId' => 'Ind',
			'BranchId' => 'Branch',
			'qst' => 'Qst',
			'ModifiedBy' => 'Modified By',
			'LastModified' => 'Last Modified',
			'PDFG' => 'Pdfg',
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
		$criteria->compare('LabNo',$this->LabNo,true);
		$criteria->compare('BatchCode',$this->BatchCode,true);
		$criteria->compare('BatchNo',$this->BatchNo,true);
		$criteria->compare('HeatNo',$this->HeatNo,true);
		$criteria->compare('SampleName',$this->SampleName,true);
		$criteria->compare('Description',$this->Description,true);
		$criteria->compare('RefPurchaseOrder',$this->RefPurchaseOrder,true);
		$criteria->compare('IsMdsTds',$this->IsMdsTds,true);
		$criteria->compare('MdsTdsId',$this->MdsTdsId);
		$criteria->compare('SupplierId',$this->SupplierId);
		$criteria->compare('CustomerId',$this->CustomerId);
		$criteria->compare('RegDate',$this->RegDate,true);
		$criteria->compare('CreationDate',$this->CreationDate,true);
		$criteria->compare('EnteredBy',$this->EnteredBy);
		$criteria->compare('Status',$this->Status,true);
		$criteria->compare('IndId',$this->IndId);
		$criteria->compare('BranchId',$this->BranchId);
		$criteria->compare('qst',$this->qst,true);
		$criteria->compare('ModifiedBy',$this->ModifiedBy);
		$criteria->compare('LastModified',$this->LastModified,true);
		$criteria->compare('PDFG',$this->PDFG);
		$criteria->compare('CID',$this->CID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Receiptir the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
