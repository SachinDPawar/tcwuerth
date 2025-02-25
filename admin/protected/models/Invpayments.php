<?php

/**
 * This is the model class for table "invpayments".
 *
 * The followings are the available columns in table 'invpayments':
 * @property integer $Id
 * @property string $TransDate
 * @property string $Description
 * @property integer $InvId
 * @property double $Total
 * @property string $PayType
 * @property string $PayDetails
 *
 * The followings are the available model relations:
 * @property Invoices $inv
 */
class Invpayments extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'invpayments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Description, InvId, PayType', 'required'),
			array('InvId', 'numerical', 'integerOnly'=>true),
			array('Total', 'numerical'),
			array('Description', 'length', 'max'=>250),
			array('PayType', 'length', 'max'=>20),
			array('TransDate, PayDetails', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, TransDate, Description, InvId, Total, PayType, PayDetails', 'safe', 'on'=>'search'),
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
			'TransDate' => 'Trans Date',
			'Description' => 'Description',
			'InvId' => 'Inv',
			'Total' => 'Total',
			'PayType' => 'Pay Type',
			'PayDetails' => 'Pay Details',
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
		$criteria->compare('TransDate',$this->TransDate,true);
		$criteria->compare('Description',$this->Description,true);
		$criteria->compare('InvId',$this->InvId);
		$criteria->compare('Total',$this->Total);
		$criteria->compare('PayType',$this->PayType,true);
		$criteria->compare('PayDetails',$this->PayDetails,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Invpayments the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
