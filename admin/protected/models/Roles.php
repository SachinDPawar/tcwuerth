<?php

/**
 * This is the model class for table "roles".
 *
 * The followings are the available columns in table 'roles':
 * @property integer $Id
 * @property string $Role
 * @property string $RoleFlag
 * @property integer $Status
 * @property integer $P
 *
 * The followings are the available model relations:
 * @property Roleapppermission[] $roleapppermissions
 * @property Userinroles[] $userinroles
 */
class Roles extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'roles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Role', 'required'),
			array('Status, P', 'numerical', 'integerOnly'=>true),
			array('Role', 'length', 'max'=>255),
			array('RoleFlag', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Role, RoleFlag, Status, P', 'safe', 'on'=>'search'),
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
			'roleapppermissions' => array(self::HAS_MANY, 'Roleapppermission', 'RoleId'),
			'userinroles' => array(self::HAS_MANY, 'Userinroles', 'RoleId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'Role' => 'Role',
			'RoleFlag' => 'Role Flag',
			'Status' => 'Status',
			'P' => 'P',
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
		$criteria->compare('Role',$this->Role,true);
		$criteria->compare('RoleFlag',$this->RoleFlag,true);
		$criteria->compare('Status',$this->Status);
		$criteria->compare('P',$this->P);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Roles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
