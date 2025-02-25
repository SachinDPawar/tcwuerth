<?php

/**
 * This is the model class for table "settingscert".
 *
 * The followings are the available columns in table 'settingscert':
 * @property integer $Id
 * @property integer $CID
 * @property string $CFNo
 * @property string $CRevNo
 * @property string $CRevDate
 */
class Settingscert extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'settingscert';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CID, CFNo, CRevNo, CRevDate', 'required'),
			array('CID', 'numerical', 'integerOnly'=>true),
			array('CFNo, CRevNo, CRevDate', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, CID, CFNo, CRevNo, CRevDate', 'safe', 'on'=>'search'),
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
			'CID' => 'Cid',
			'CFNo' => 'Cfno',
			'CRevNo' => 'Crev No',
			'CRevDate' => 'Crev Date',
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
		$criteria->compare('CID',$this->CID);
		$criteria->compare('CFNo',$this->CFNo,true);
		$criteria->compare('CRevNo',$this->CRevNo,true);
		$criteria->compare('CRevDate',$this->CRevDate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Settingscert the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
