<?php

/**
 * This is the model class for table "customerinfo".
 *
 * The followings are the available columns in table 'customerinfo':
 * @property string $Id
 * @property string $Name
 * @property string $Email
 * @property string $CreatedOn
 * @property integer $CreatedBy
 * @property integer $BranchId
 * @property integer $UserId
 * @property string $GSTIN
 *
 * The followings are the available model relations:
 * @property Certbasic[] $certbasics
 * @property Custaddresses[] $custaddresses
 * @property Invoices[] $invoices
 * @property Quotation[] $quotations
 * @property Receiptir[] $receiptirs
 */
class Customerinfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'customerinfo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Name, CreatedOn', 'required'),
			array('CreatedBy, BranchId, UserId', 'numerical', 'integerOnly'=>true),
			array('Name', 'length', 'max'=>250),
			array('Email', 'length', 'max'=>50),
			array('GSTIN', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Name, Email, CreatedOn, CreatedBy, BranchId, UserId, GSTIN', 'safe', 'on'=>'search'),
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
			'certbasics' => array(self::HAS_MANY, 'Certbasic', 'CustomerId'),
			'custaddresses' => array(self::HAS_MANY, 'Custaddresses', 'CustId'),
			'invoices' => array(self::HAS_MANY, 'Invoices', 'CustId'),
			'quotations' => array(self::HAS_MANY, 'Quotation', 'CustId'),
			'receiptirs' => array(self::HAS_MANY, 'Receiptir', 'CustomerId'),
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
			'CreatedOn' => 'Created On',
			'CreatedBy' => 'Created By',
			'BranchId' => 'Branch',
			'UserId' => 'User',
			'GSTIN' => 'Gstin',
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
		$criteria->compare('CreatedOn',$this->CreatedOn,true);
		$criteria->compare('CreatedBy',$this->CreatedBy);
		$criteria->compare('BranchId',$this->BranchId);
		$criteria->compare('UserId',$this->UserId);
		$criteria->compare('GSTIN',$this->GSTIN,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Customerinfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
