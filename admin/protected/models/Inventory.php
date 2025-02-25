<?php

/**
 * This is the model class for table "inventory".
 *
 * The followings are the available columns in table 'inventory':
 * @property integer $Id
 * @property string $EquipType
 * @property string $Equipment
 * @property string $Model
 * @property string $InstallDate
 * @property integer $LCU
 * @property integer $LOQ
 * @property string $TestRange
 * @property string $NextCalliDate
 * @property string $Vendor
 */
class Inventory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'inventory';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('EquipType, Equipment, InstallDate, NextCalliDate', 'required'),
			array('LCU, LOQ', 'numerical', 'integerOnly'=>true),
			array('EquipType', 'length', 'max'=>20),
			array('Equipment, Model, TestRange, Vendor', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, EquipType, Equipment, Model, InstallDate, LCU, LOQ, TestRange, NextCalliDate, Vendor', 'safe', 'on'=>'search'),
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
			'EquipType' => 'Equip Type',
			'Equipment' => 'Equipment',
			'Model' => 'Model',
			'InstallDate' => 'Install Date',
			'LCU' => 'Lcu',
			'LOQ' => 'Loq',
			'TestRange' => 'Test Range',
			'NextCalliDate' => 'Next Calli Date',
			'Vendor' => 'Vendor',
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
		$criteria->compare('EquipType',$this->EquipType,true);
		$criteria->compare('Equipment',$this->Equipment,true);
		$criteria->compare('Model',$this->Model,true);
		$criteria->compare('InstallDate',$this->InstallDate,true);
		$criteria->compare('LCU',$this->LCU);
		$criteria->compare('LOQ',$this->LOQ);
		$criteria->compare('TestRange',$this->TestRange,true);
		$criteria->compare('NextCalliDate',$this->NextCalliDate,true);
		$criteria->compare('Vendor',$this->Vendor,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Inventory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
