<?php

/**
 * This is the model class for table "labaccredit".
 *
 * The followings are the available columns in table 'labaccredit':
 * @property integer $Id
 * @property string $Name
 * @property double $Cost
 * @property integer $Status
 * @property integer $ISP
 * @property integer $CID
 *
 * The followings are the available model relations:
 * @property Labaccreditlogos[] $labaccreditlogoses
 */
class Labaccredit extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'labaccredit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Name, Cost', 'required'),
			array('Status, ISP, CID', 'numerical', 'integerOnly'=>true),
			array('Cost', 'numerical'),
			array('Name', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Name, Cost, Status, ISP, CID', 'safe', 'on'=>'search'),
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
			'labaccreditlogoses' => array(self::HAS_MANY, 'Labaccreditlogos', 'labid'),
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
			'Cost' => 'Cost',
			'Status' => 'Status',
			'ISP' => 'Isp',
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
		$criteria->compare('Cost',$this->Cost);
		$criteria->compare('Status',$this->Status);
		$criteria->compare('ISP',$this->ISP);
		$criteria->compare('CID',$this->CID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Labaccredit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
