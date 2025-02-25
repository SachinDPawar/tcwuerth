<?php

/**
 * This is the model class for table "quotation".
 *
 * The followings are the available columns in table 'quotation':
 * @property integer $Id
 * @property string $QNo
 * @property string $QDate
 * @property string $ValidDate
 * @property string $CustId
 * @property integer $IndId
 * @property integer $IndCatId
 * @property string $CreatedOn
 * @property double $SubTotal
 * @property double $Discount
 * @property double $TotTax
 * @property integer $IsTax
 * @property double $Tax
 * @property double $Total
 * @property string $Note
 * @property string $Status
 * @property integer $CreatedBy
 * @property integer $VerifiedBy
 * @property string $VerifiedOn
 * @property integer $AssignedTo
 * @property string $ApprovedOn
 * @property string $SampleGroup
 * @property string $SampleConditions
 * @property string $EndUse
 * @property string $ModeOfReceipt
 * @property string $DrawnBy
 * @property string $Specifications
 *
 * The followings are the available model relations:
 * @property Customerinfo $cust
 * @property Quotationdetails[] $quotationdetails
 */
class Quotation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'quotation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('QNo, QDate, CreatedOn, SubTotal, Discount, TotTax, Total', 'required'),
			array('IndId, IndCatId, IsTax, CreatedBy, VerifiedBy, AssignedTo', 'numerical', 'integerOnly'=>true),
			array('SubTotal, Discount, TotTax, Tax, Total', 'numerical'),
			array('QNo, CustId, Status', 'length', 'max'=>20),
			array('ValidDate, Note, VerifiedOn, ApprovedOn, SampleGroup, SampleConditions, EndUse, ModeOfReceipt, DrawnBy, Specifications', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, QNo, QDate, ValidDate, CustId, IndId, IndCatId, CreatedOn, SubTotal, Discount, TotTax, IsTax, Tax, Total, Note, Status, CreatedBy, VerifiedBy, VerifiedOn, AssignedTo, ApprovedOn, SampleGroup, SampleConditions, EndUse, ModeOfReceipt, DrawnBy, Specifications', 'safe', 'on'=>'search'),
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
			'cust' => array(self::BELONGS_TO, 'Customerinfo', 'CustId'),
			'quotationdetails' => array(self::HAS_MANY, 'Quotationdetails', 'QId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'QNo' => 'Qno',
			'QDate' => 'Qdate',
			'ValidDate' => 'Valid Date',
			'CustId' => 'Cust',
			'IndId' => 'Ind',
			'IndCatId' => 'Ind Cat',
			'CreatedOn' => 'Created On',
			'SubTotal' => 'Sub Total',
			'Discount' => 'Discount',
			'TotTax' => 'Tot Tax',
			'IsTax' => 'Is Tax',
			'Tax' => 'Tax',
			'Total' => 'Total',
			'Note' => 'Note',
			'Status' => 'Status',
			'CreatedBy' => 'Created By',
			'VerifiedBy' => 'Verified By',
			'VerifiedOn' => 'Verified On',
			'AssignedTo' => 'Assigned To',
			'ApprovedOn' => 'Approved On',
			'SampleGroup' => 'Sample Group',
			'SampleConditions' => 'Sample Conditions',
			'EndUse' => 'End Use',
			'ModeOfReceipt' => 'Mode Of Receipt',
			'DrawnBy' => 'Drawn By',
			'Specifications' => 'Specifications',
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
		$criteria->compare('QNo',$this->QNo,true);
		$criteria->compare('QDate',$this->QDate,true);
		$criteria->compare('ValidDate',$this->ValidDate,true);
		$criteria->compare('CustId',$this->CustId,true);
		$criteria->compare('IndId',$this->IndId);
		$criteria->compare('IndCatId',$this->IndCatId);
		$criteria->compare('CreatedOn',$this->CreatedOn,true);
		$criteria->compare('SubTotal',$this->SubTotal);
		$criteria->compare('Discount',$this->Discount);
		$criteria->compare('TotTax',$this->TotTax);
		$criteria->compare('IsTax',$this->IsTax);
		$criteria->compare('Tax',$this->Tax);
		$criteria->compare('Total',$this->Total);
		$criteria->compare('Note',$this->Note,true);
		$criteria->compare('Status',$this->Status,true);
		$criteria->compare('CreatedBy',$this->CreatedBy);
		$criteria->compare('VerifiedBy',$this->VerifiedBy);
		$criteria->compare('VerifiedOn',$this->VerifiedOn,true);
		$criteria->compare('AssignedTo',$this->AssignedTo);
		$criteria->compare('ApprovedOn',$this->ApprovedOn,true);
		$criteria->compare('SampleGroup',$this->SampleGroup,true);
		$criteria->compare('SampleConditions',$this->SampleConditions,true);
		$criteria->compare('EndUse',$this->EndUse,true);
		$criteria->compare('ModeOfReceipt',$this->ModeOfReceipt,true);
		$criteria->compare('DrawnBy',$this->DrawnBy,true);
		$criteria->compare('Specifications',$this->Specifications,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Quotation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
