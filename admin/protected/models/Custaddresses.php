<?php

/**
 * This is the model class for table "custaddresses".
 *
 * The followings are the available columns in table 'custaddresses':
 * @property string $Id
 * @property string $Name
 * @property string $Email
 * @property string $PinCode
 * @property string $Address
 * @property string $ContactNo
 * @property string $ContactPerson
 * @property integer $CustId
 *
 * The followings are the available model relations:
 * @property Customerinfo $cust
 */
class Custaddresses extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'custaddresses';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Name', 'required'),
			array('CustId', 'numerical', 'integerOnly'=>true),
			array('Name', 'length', 'max'=>250),
			array('Email, ContactPerson', 'length', 'max'=>50),
			array('PinCode, ContactNo', 'length', 'max'=>20),
			array('Address', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Name, Email, PinCode, Address, ContactNo, ContactPerson, CustId', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'Name' => 'Name',
			'Email' => 'Email',
			'PinCode' => 'Pin Code',
			'Address' => 'Address',
			'ContactNo' => 'Contact No',
			'ContactPerson' => 'Contact Person',
			'CustId' => 'Cust',
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
		$criteria->compare('Name',$this->Name,true);
		$criteria->compare('Email',$this->Email,true);
		$criteria->compare('PinCode',$this->PinCode,true);
		$criteria->compare('Address',$this->Address,true);
		$criteria->compare('ContactNo',$this->ContactNo,true);
		$criteria->compare('ContactPerson',$this->ContactPerson,true);
		$criteria->compare('CustId',$this->CustId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Custaddresses the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
