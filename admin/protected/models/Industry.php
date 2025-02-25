<?php

/**
 * This is the model class for table "industry".
 *
 * The followings are the available columns in table 'industry':
 * @property integer $Id
 * @property string $Name
 * @property integer $HasSubInd
 * @property integer $ParentId
 * @property string $Status
 * @property integer $SeqNo
 *
 * The followings are the available model relations:
 * @property Industry $parent
 * @property Industry[] $industries
 * @property Receiptir[] $receiptirs
 * @property Standards[] $standards
 * @property Tests[] $tests
 */
class Industry extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'industry';
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
			array('HasSubInd, ParentId, SeqNo', 'numerical', 'integerOnly'=>true),
			array('Name', 'length', 'max'=>20),
			array('Status', 'length', 'max'=>1),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Name, HasSubInd, ParentId, Status, SeqNo', 'safe', 'on'=>'search'),
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
			'parent' => array(self::BELONGS_TO, 'Industry', 'ParentId'),
			'industries' => array(self::HAS_MANY, 'Industry', 'ParentId'),
			'receiptirs' => array(self::HAS_MANY, 'Receiptir', 'IndId'),
			'standards' => array(self::HAS_MANY, 'Standards', 'IndId'),
			'tests' => array(self::HAS_MANY, 'Tests', 'IndId'),
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
			'HasSubInd' => 'Has Sub Ind',
			'ParentId' => 'Parent',
			'Status' => 'Status',
			'SeqNo' => 'Seq No',
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
		$criteria->compare('HasSubInd',$this->HasSubInd);
		$criteria->compare('ParentId',$this->ParentId);
		$criteria->compare('Status',$this->Status,true);
		$criteria->compare('SeqNo',$this->SeqNo);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Industry the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
