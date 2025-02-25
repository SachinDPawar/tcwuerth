<?php

/**
 * This is the model class for table "invoicedetails".
 *
 * The followings are the available columns in table 'invoicedetails':
 * @property integer $Id
 * @property integer $InvId
 * @property string $Details
 * @property string $DType
 * @property double $Price
 * @property integer $Qty
 * @property double $Tax
 *
 * The followings are the available model relations:
 * @property Invoices $inv
 */
class Invoicedetails extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'invoicedetails';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('InvId, Details, Qty', 'required'),
			array('InvId, Qty', 'numerical', 'integerOnly'=>true),
			array('Price, Tax', 'numerical'),
			array('DType', 'length', 'max'=>2),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, InvId, Details, DType, Price, Qty, Tax', 'safe', 'on'=>'search'),
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
			'inv' => array(self::BELONGS_TO, 'Invoices', 'InvId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'InvId' => 'Inv',
			'Details' => 'Details',
			'DType' => 'Dtype',
			'Price' => 'Price',
			'Qty' => 'Qty',
			'Tax' => 'Tax',
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
		$criteria->compare('InvId',$this->InvId);
		$criteria->compare('Details',$this->Details,true);
		$criteria->compare('DType',$this->DType,true);
		$criteria->compare('Price',$this->Price);
		$criteria->compare('Qty',$this->Qty);
		$criteria->compare('Tax',$this->Tax);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Invoicedetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
