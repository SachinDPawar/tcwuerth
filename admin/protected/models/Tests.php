<?php

/**
 * This is the model class for table "tests".
 *
 * The followings are the available columns in table 'tests':
 * @property integer $Id
 * @property string $TestName
 * @property string $TUID
 * @property integer $Qty
 * @property integer $Status
 * @property string $FormatNo
 * @property string $RevDate
 * @property string $RevNo
 * @property double $Cost
 * @property string $DefaultNote
 * @property integer $SeqNo
 * @property string $icon
 * @property integer $IndId
 * @property string $MachinePath
 * @property string $TType
 * @property integer $IsImg
 * @property integer $ImgCount
 *
 * The followings are the available model relations:
 * @property Formulation[] $formulations
 * @property Mdstdstests[] $mdstdstests
 * @property Rirtestdetail[] $rirtestdetails
 * @property Stdsubdetails[] $stdsubdetails
 * @property Substdtests[] $substdtests
 * @property Testbasicparams[] $testbasicparams
 * @property Testfeature[] $testfeatures
 * @property Testobsparams[] $testobsparams
 * @property Testparamcategory[] $testparamcategories
 */
class Tests extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tests';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('TestName', 'required'),
			array('Qty, Status, SeqNo, IndId, IsImg, ImgCount', 'numerical', 'integerOnly'=>true),
			array('Cost', 'numerical'),
			array('TestName', 'length', 'max'=>100),
			array('TUID', 'length', 'max'=>10),
			array('FormatNo, RevNo', 'length', 'max'=>50),
			array('icon', 'length', 'max'=>30),
			array('TType', 'length', 'max'=>2),
			array('RevDate, DefaultNote, MachinePath', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, TestName, TUID, Qty, Status, FormatNo, RevDate, RevNo, Cost, DefaultNote, SeqNo, icon, IndId, MachinePath, TType, IsImg, ImgCount', 'safe', 'on'=>'search'),
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
			'formulations' => array(self::HAS_MANY, 'Formulation', 'TestId'),
			'mdstdstests' => array(self::HAS_MANY, 'Mdstdstests', 'TID'),
			'rirtestdetails' => array(self::HAS_MANY, 'Rirtestdetail', 'TestId'),
			'stdsubdetails' => array(self::HAS_MANY, 'Stdsubdetails', 'TUID'),
			'substdtests' => array(self::HAS_MANY, 'Substdtests', 'TID'),
			'testbasicparams' => array(self::HAS_MANY, 'Testbasicparams', 'TestId'),
			'testfeatures' => array(self::HAS_MANY, 'Testfeature', 'TestId'),
			'testobsparams' => array(self::HAS_MANY, 'Testobsparams', 'TestId'),
			'testparamcategories' => array(self::HAS_MANY, 'Testparamcategory', 'TestId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'TestName' => 'Test Name',
			'TUID' => 'Tuid',
			'Qty' => 'Qty',
			'Status' => 'Status',
			'FormatNo' => 'Format No',
			'RevDate' => 'Rev Date',
			'RevNo' => 'Rev No',
			'Cost' => 'Cost',
			'DefaultNote' => 'Default Note',
			'SeqNo' => 'Seq No',
			'icon' => 'Icon',
			'IndId' => 'Ind',
			'MachinePath' => 'Machine Path',
			'TType' => 'Ttype',
			'IsImg' => 'Is Img',
			'ImgCount' => 'Img Count',
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
		$criteria->compare('TestName',$this->TestName,true);
		$criteria->compare('TUID',$this->TUID,true);
		$criteria->compare('Qty',$this->Qty);
		$criteria->compare('Status',$this->Status);
		$criteria->compare('FormatNo',$this->FormatNo,true);
		$criteria->compare('RevDate',$this->RevDate,true);
		$criteria->compare('RevNo',$this->RevNo,true);
		$criteria->compare('Cost',$this->Cost);
		$criteria->compare('DefaultNote',$this->DefaultNote,true);
		$criteria->compare('SeqNo',$this->SeqNo);
		$criteria->compare('icon',$this->icon,true);
		$criteria->compare('IndId',$this->IndId);
		$criteria->compare('MachinePath',$this->MachinePath,true);
		$criteria->compare('TType',$this->TType,true);
		$criteria->compare('IsImg',$this->IsImg);
		$criteria->compare('ImgCount',$this->ImgCount);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Tests the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
