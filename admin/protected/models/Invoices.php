<?php

/**
 * This is the model class for table "invoices".
 *
 * The followings are the available columns in table 'invoices':
 * @property integer $Id
 * @property string $InvoiceNo
 * @property string $InvDate
 * @property string $DueDate
 * @property string $Reference
 * @property string $CustId
 * @property string $CreatedOn
 * @property string $InvType
 * @property double $SubTotal
 * @property double $Discount
 * @property integer $IsTax
 * @property double $Tax
 * @property double $TotTax
 * @property double $Total
 * @property string $Note
 * @property string $Status
 * @property string $PayStatus
 * @property integer $CoaId
 * @property string $ApprovedOn
 * @property integer $ApprovedBy
 *
 * The followings are the available model relations:
 * @property Invoicedetails[] $invoicedetails
 * @property Customerinfo $cust
 */
class Invoices extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'invoices';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('InvoiceNo, InvDate, CreatedOn, SubTotal, Discount, TotTax, Total', 'required'),
			array('IsTax, CoaId, ApprovedBy', 'numerical', 'integerOnly'=>true),
			array('SubTotal, Discount, Tax, TotTax, Total', 'numerical'),
			array('InvoiceNo, CustId, InvType, Status, PayStatus', 'length', 'max'=>20),
			array('Reference', 'length', 'max'=>50),
			array('DueDate, Note, ApprovedOn', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, InvoiceNo, InvDate, DueDate, Reference, CustId, CreatedOn, InvType, SubTotal, Discount, IsTax, Tax, TotTax, Total, Note, Status, PayStatus, CoaId, ApprovedOn, ApprovedBy', 'safe', 'on'=>'search'),
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
			'invoicedetails' => array(self::HAS_MANY, 'Invoicedetails', 'InvId'),
			'cust' => array(self::BELONGS_TO, 'Customerinfo', 'CustId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'InvoiceNo' => 'Invoice No',
			'InvDate' => 'Inv Date',
			'DueDate' => 'Due Date',
			'Reference' => 'Reference',
			'CustId' => 'Cust',
			'CreatedOn' => 'Created On',
			'InvType' => 'Inv Type',
			'SubTotal' => 'Sub Total',
			'Discount' => 'Discount',
			'IsTax' => 'Is Tax',
			'Tax' => 'Tax',
			'TotTax' => 'Tot Tax',
			'Total' => 'Total',
			'Note' => 'Note',
			'Status' => 'Status',
			'PayStatus' => 'Pay Status',
			'CoaId' => 'Coa',
			'ApprovedOn' => 'Approved On',
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

		$criteria->compare('Id',$this->Id);
		$criteria->compare('InvoiceNo',$this->InvoiceNo,true);
		$criteria->compare('InvDate',$this->InvDate,true);
		$criteria->compare('DueDate',$this->DueDate,true);
		$criteria->compare('Reference',$this->Reference,true);
		$criteria->compare('CustId',$this->CustId,true);
		$criteria->compare('CreatedOn',$this->CreatedOn,true);
		$criteria->compare('InvType',$this->InvType,true);
		$criteria->compare('SubTotal',$this->SubTotal);
		$criteria->compare('Discount',$this->Discount);
		$criteria->compare('IsTax',$this->IsTax);
		$criteria->compare('Tax',$this->Tax);
		$criteria->compare('TotTax',$this->TotTax);
		$criteria->compare('Total',$this->Total);
		$criteria->compare('Note',$this->Note,true);
		$criteria->compare('Status',$this->Status,true);
		$criteria->compare('PayStatus',$this->PayStatus,true);
		$criteria->compare('CoaId',$this->CoaId);
		$criteria->compare('ApprovedOn',$this->ApprovedOn,true);
		$criteria->compare('ApprovedBy',$this->ApprovedBy);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Invoices the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
