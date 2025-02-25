<?php

/**
 * This is the model class for table "testbasicparams".
 *
 * The followings are the available columns in table 'testbasicparams':
 * @property integer $Id
 * @property string $Parameter
 * @property string $PSymbol
 * @property string $PUnit
 * @property string $PDType
 * @property string $LCategory
 * @property integer $SeqNo
 * @property string $Position
 * @property integer $TestId
 *
 * The followings are the available model relations:
 * @property Rirtestbasic[] $rirtestbasics
 * @property Tests $test
 */
class Testbasicparams extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'testbasicparams';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Parameter, PDType, TestId', 'required'),
			array('SeqNo, TestId', 'numerical', 'integerOnly'=>true),
			array('Parameter', 'length', 'max'=>50),
			array('PSymbol, PUnit', 'length', 'max'=>20),
			array('PDType', 'length', 'max'=>2),
			array('LCategory', 'length', 'max'=>250),
			array('Position', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Parameter, PSymbol, PUnit, PDType, LCategory, SeqNo, Position, TestId', 'safe', 'on'=>'search'),
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
			'rirtestbasics' => array(self::HAS_MANY, 'Rirtestbasic', 'TBPID'),
			'test' => array(self::BELONGS_TO, 'Tests', 'TestId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'Parameter' => 'Parameter',
			'PSymbol' => 'Psymbol',
			'PUnit' => 'Punit',
			'PDType' => 'Pdtype',
			'LCategory' => 'Lcategory',
			'SeqNo' => 'Seq No',
			'Position' => 'Position',
			'TestId' => 'Test',
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
		$criteria->compare('Parameter',$this->Parameter,true);
		$criteria->compare('PSymbol',$this->PSymbol,true);
		$criteria->compare('PUnit',$this->PUnit,true);
		$criteria->compare('PDType',$this->PDType,true);
		$criteria->compare('LCategory',$this->LCategory,true);
		$criteria->compare('SeqNo',$this->SeqNo);
		$criteria->compare('Position',$this->Position,true);
		$criteria->compare('TestId',$this->TestId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Testbasicparams the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
