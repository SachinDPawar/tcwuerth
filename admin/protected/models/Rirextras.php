<?php

/**
 * This is the model class for table "rirextras".
 *
 * The followings are the available columns in table 'rirextras':
 * @property string $Id
 * @property string $RIRId
 * @property string $GrinNoDate
 * @property string $Quantity
 * @property string $InvoiceNo
 * @property string $InvoiceDate
 * @property string $Grade
 * @property string $Condition
 * @property string $TCNo
 * @property string $RouteCardNo
 *
 * The followings are the available model relations:
 * @property Receiptir $rIR
 */
class Rirextras extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rirextras';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('RIRId', 'required'),
			array('RIRId', 'length', 'max'=>20),
			array('GrinNoDate, Quantity', 'length', 'max'=>150),
			array('InvoiceNo, InvoiceDate, Grade, Condition, TCNo, RouteCardNo', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, RIRId, GrinNoDate, Quantity, InvoiceNo, InvoiceDate, Grade, Condition, TCNo, RouteCardNo', 'safe', 'on'=>'search'),
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
			'rIR' => array(self::BELONGS_TO, 'Receiptir', 'RIRId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'RIRId' => 'Ririd',
			'GrinNoDate' => 'Grin No Date',
			'Quantity' => 'Quantity',
			'InvoiceNo' => 'Invoice No',
			'InvoiceDate' => 'Invoice Date',
			'Grade' => 'Grade',
			'Condition' => 'Condition',
			'TCNo' => 'Tcno',
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
		$criteria->compare('RIRId',$this->RIRId,true);
		$criteria->compare('GrinNoDate',$this->GrinNoDate,true);
		$criteria->compare('Quantity',$this->Quantity,true);
		$criteria->compare('InvoiceNo',$this->InvoiceNo,true);
		$criteria->compare('InvoiceDate',$this->InvoiceDate,true);
		$criteria->compare('Grade',$this->Grade,true);
		$criteria->compare('Condition',$this->Condition,true);
		$criteria->compare('TCNo',$this->TCNo,true);
		$criteria->compare('RouteCardNo',$this->RouteCardNo,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Rirextras the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
