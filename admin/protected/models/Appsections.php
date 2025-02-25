<?php

/**
 * This is the model class for table "appsections".
 *
 * The followings are the available columns in table 'appsections':
 * @property integer $Id
 * @property string $Section
 * @property string $Description
 * @property string $Others
 * @property string $Group
 * @property integer $Status
 * @property integer $C
 * @property integer $R
 * @property integer $U
 * @property integer $D
 * @property integer $A
 * @property integer $Ch
 * @property integer $Print
 * @property integer $SM
 *
 * The followings are the available model relations:
 * @property Roleapppermission[] $roleapppermissions
 * @property Userapppermission[] $userapppermissions
 */
class Appsections extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'appsections';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Section', 'required'),
			array('Status, C, R, U, D, A, Ch, Print, SM', 'numerical', 'integerOnly'=>true),
			array('Section, Group', 'length', 'max'=>50),
			array('Others', 'length', 'max'=>20),
			array('Description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Section, Description, Others, Group, Status, C, R, U, D, A, Ch, Print, SM', 'safe', 'on'=>'search'),
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
			'roleapppermissions' => array(self::HAS_MANY, 'Roleapppermission', 'SectionId'),
			'userapppermissions' => array(self::HAS_MANY, 'Userapppermission', 'SectionId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'Section' => 'Section',
			'Description' => 'Description',
			'Others' => 'Others',
			'Group' => 'Group',
			'Status' => 'Status',
			'C' => 'C',
			'R' => 'R',
			'U' => 'U',
			'D' => 'D',
			'A' => 'A',
			'Ch' => 'Ch',
			'Print' => 'Print',
			'SM' => 'Sm',
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
		$criteria->compare('Section',$this->Section,true);
		$criteria->compare('Description',$this->Description,true);
		$criteria->compare('Others',$this->Others,true);
		$criteria->compare('Group',$this->Group,true);
		$criteria->compare('Status',$this->Status);
		$criteria->compare('C',$this->C);
		$criteria->compare('R',$this->R);
		$criteria->compare('U',$this->U);
		$criteria->compare('D',$this->D);
		$criteria->compare('A',$this->A);
		$criteria->compare('Ch',$this->Ch);
		$criteria->compare('Print',$this->Print);
		$criteria->compare('SM',$this->SM);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Appsections the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
