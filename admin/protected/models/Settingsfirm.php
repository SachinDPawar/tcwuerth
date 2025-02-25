<?php

/**
 * This is the model class for table "settingsfirm".
 *
 * The followings are the available columns in table 'settingsfirm':
 * @property integer $Id
 * @property string $Name
 * @property string $ContactNo
 * @property string $Email
 * @property string $Address
 * @property string $GSTIN
 * @property string $PAN
 * @property integer $BranchId
 * @property string $Location
 * @property integer $CID
 */
class Settingsfirm extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'settingsfirm';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('GSTIN, PAN', 'required'),
			array('BranchId, CID', 'numerical', 'integerOnly'=>true),
			array('Name, Email', 'length', 'max'=>250),
			array('ContactNo, Location', 'length', 'max'=>20),
			array('Address', 'length', 'max'=>500),
			array('GSTIN, PAN', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Name, ContactNo, Email, Address, GSTIN, PAN, BranchId, Location, CID', 'safe', 'on'=>'search'),
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
			'Name' => 'Name',
			'ContactNo' => 'Contact No',
			'Email' => 'Email',
			'Address' => 'Address',
			'GSTIN' => 'Gstin',
			'PAN' => 'Pan',
			'BranchId' => 'Branch',
			'Location' => 'Location',
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

		$criteria->compare('Id',$this->Id);
		$criteria->compare('Name',$this->Name,true);
		$criteria->compare('ContactNo',$this->ContactNo,true);
		$criteria->compare('Email',$this->Email,true);
		$criteria->compare('Address',$this->Address,true);
		$criteria->compare('GSTIN',$this->GSTIN,true);
		$criteria->compare('PAN',$this->PAN,true);
		$criteria->compare('BranchId',$this->BranchId);
		$criteria->compare('Location',$this->Location,true);
		$criteria->compare('CID',$this->CID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Settingsfirm the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
