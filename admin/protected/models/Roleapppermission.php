<?php

/**
 * This is the model class for table "roleapppermission".
 *
 * The followings are the available columns in table 'roleapppermission':
 * @property string $Id
 * @property integer $RoleId
 * @property integer $SectionId
 * @property integer $C
 * @property integer $R
 * @property integer $U
 * @property integer $D
 * @property integer $A
 * @property integer $Ch
 * @property integer $Print
 *
 * The followings are the available model relations:
 * @property Roles $role
 * @property Appsections $section
 */
class Roleapppermission extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'roleapppermission';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('RoleId, SectionId', 'required'),
			array('RoleId, SectionId, C, R, U, D, A, Ch, Print', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, RoleId, SectionId, C, R, U, D, A, Ch, Print', 'safe', 'on'=>'search'),
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
			'role' => array(self::BELONGS_TO, 'Roles', 'RoleId'),
			'section' => array(self::BELONGS_TO, 'Appsections', 'SectionId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'RoleId' => 'Role',
			'SectionId' => 'Section',
			'C' => 'C',
			'R' => 'R',
			'U' => 'U',
			'D' => 'D',
			'A' => 'A',
			'Ch' => 'Ch',
			'Print' => 'Print',
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
		$criteria->compare('RoleId',$this->RoleId);
		$criteria->compare('SectionId',$this->SectionId);
		$criteria->compare('C',$this->C);
		$criteria->compare('R',$this->R);
		$criteria->compare('U',$this->U);
		$criteria->compare('D',$this->D);
		$criteria->compare('A',$this->A);
		$criteria->compare('Ch',$this->Ch);
		$criteria->compare('Print',$this->Print);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Roleapppermission the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
