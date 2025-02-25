<?php

/**
 * This is the model class for table "hbcregister".
 *
 * The followings are the available columns in table 'hbcregister':
 * @property string $Id
 * @property string $LabNo
 * @property string $NoType
 * @property string $BatchCode
 * @property string $BatchNo
 * @property string $HeatNo
 * @property string $PartName
 * @property string $Supplier
 * @property string $Quantity
 * @property string $MaterialGrade
 * @property string $MaterialCondition
 * @property string $TCNo
 * @property string $InvoiceRate
 * @property string $InvoiceDate
 * @property string $InvoiceNo
 * @property string $PoNo
 * @property string $GrinNo
 * @property string $Customer
 * @property string $RouteCardNo
 */
class Hbcregister extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'hbcregister';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('LabNo, Customer, RouteCardNo', 'length', 'max'=>10),
			array('NoType', 'length', 'max'=>20),
			array('BatchCode', 'length', 'max'=>3),
			array('BatchNo', 'length', 'max'=>100),
			array('HeatNo', 'length', 'max'=>19),
			array('PartName', 'length', 'max'=>34),
			array('Supplier', 'length', 'max'=>30),
			array('Quantity', 'length', 'max'=>18),
			array('MaterialGrade, MaterialCondition', 'length', 'max'=>23),
			array('TCNo', 'length', 'max'=>45),
			array('InvoiceRate', 'length', 'max'=>14),
			array('InvoiceDate', 'length', 'max'=>22),
			array('InvoiceNo', 'length', 'max'=>28),
			array('PoNo', 'length', 'max'=>25),
			array('GrinNo', 'length', 'max'=>31),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, LabNo, NoType, BatchCode, BatchNo, HeatNo, PartName, Supplier, Quantity, MaterialGrade, MaterialCondition, TCNo, InvoiceRate, InvoiceDate, InvoiceNo, PoNo, GrinNo, Customer, RouteCardNo', 'safe', 'on'=>'search'),
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
			'LabNo' => 'Lab No',
			'NoType' => 'No Type',
			'BatchCode' => 'Batch Code',
			'BatchNo' => 'Batch No',
			'HeatNo' => 'Heat No',
			'PartName' => 'Part Name',
			'Supplier' => 'Supplier',
			'Quantity' => 'Quantity',
			'MaterialGrade' => 'Material Grade',
			'MaterialCondition' => 'Material Condition',
			'TCNo' => 'Tcno',
			'InvoiceRate' => 'Invoice Rate',
			'InvoiceDate' => 'Invoice Date',
			'InvoiceNo' => 'Invoice No',
			'PoNo' => 'Po No',
			'GrinNo' => 'Grin No',
			'Customer' => 'Customer',
			'RouteCardNo' => 'Route Card No',
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
		$criteria->compare('NoType',$this->NoType,true);
		$criteria->compare('BatchCode',$this->BatchCode,true);
		$criteria->compare('BatchNo',$this->BatchNo,true);
		$criteria->compare('HeatNo',$this->HeatNo,true);
		$criteria->compare('PartName',$this->PartName,true);
		$criteria->compare('Supplier',$this->Supplier,true);
		$criteria->compare('Quantity',$this->Quantity,true);
		$criteria->compare('MaterialGrade',$this->MaterialGrade,true);
		$criteria->compare('MaterialCondition',$this->MaterialCondition,true);
		$criteria->compare('TCNo',$this->TCNo,true);
		$criteria->compare('InvoiceRate',$this->InvoiceRate,true);
		$criteria->compare('InvoiceDate',$this->InvoiceDate,true);
		$criteria->compare('InvoiceNo',$this->InvoiceNo,true);
		$criteria->compare('PoNo',$this->PoNo,true);
		$criteria->compare('GrinNo',$this->GrinNo,true);
		$criteria->compare('Customer',$this->Customer,true);
		$criteria->compare('RouteCardNo',$this->RouteCardNo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Hbcregister the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
