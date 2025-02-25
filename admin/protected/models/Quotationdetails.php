<?php

/**
 * This is the model class for table "quotationdetails".
 *
 * The followings are the available columns in table 'quotationdetails':
 * @property integer $Id
 * @property integer $QId
 * @property string $SampleName
 * @property integer $PIndId
 * @property integer $SubIndId
 * @property integer $IndId
 * @property integer $TestCondId
 * @property integer $TestId
 * @property integer $SubStdId
 * @property integer $TestMethodId
 * @property string $ExtraDetails
 * @property double $Price
 * @property integer $Qty
 * @property double $Tax
 * @property double $Total
 *
 * The followings are the available model relations:
 * @property Quotation $q
 * @property Tests $test
 */
class Quotationdetails extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'quotationdetails';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('QId, IndId, TestId, ExtraDetails, Qty, Total', 'required'),
			array('QId, PIndId, SubIndId, IndId, TestCondId, TestId, SubStdId, TestMethodId, Qty', 'numerical', 'integerOnly'=>true),
			array('Price, Tax, Total', 'numerical'),
			array('SampleName', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, QId, SampleName, PIndId, SubIndId, IndId, TestCondId, TestId, SubStdId, TestMethodId, ExtraDetails, Price, Qty, Tax, Total', 'safe', 'on'=>'search'),
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
			'q' => array(self::BELONGS_TO, 'Quotation', 'QId'),
			'test' => array(self::BELONGS_TO, 'Tests', 'TestId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'QId' => 'Qid',
			'SampleName' => 'Sample Name',
			'PIndId' => 'Pind',
			'SubIndId' => 'Sub Ind',
			'IndId' => 'Ind',
			'TestCondId' => 'Test Cond',
			'TestId' => 'Test',
			'SubStdId' => 'Sub Std',
			'TestMethodId' => 'Test Method',
			'ExtraDetails' => 'Extra Details',
			'Price' => 'Price',
			'Qty' => 'Qty',
			'Tax' => 'Tax',
			'Total' => 'Total',
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
		$criteria->compare('QId',$this->QId);
		$criteria->compare('SampleName',$this->SampleName,true);
		$criteria->compare('PIndId',$this->PIndId);
		$criteria->compare('SubIndId',$this->SubIndId);
		$criteria->compare('IndId',$this->IndId);
		$criteria->compare('TestCondId',$this->TestCondId);
		$criteria->compare('TestId',$this->TestId);
		$criteria->compare('SubStdId',$this->SubStdId);
		$criteria->compare('TestMethodId',$this->TestMethodId);
		$criteria->compare('ExtraDetails',$this->ExtraDetails,true);
		$criteria->compare('Price',$this->Price);
		$criteria->compare('Qty',$this->Qty);
		$criteria->compare('Tax',$this->Tax);
		$criteria->compare('Total',$this->Total);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Quotationdetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function behaviors()
{
	return array(
		'LoggableBehavior'=>
			'application.modules.auditTrail.behaviors.LoggableBehavior',
	);
}
}
