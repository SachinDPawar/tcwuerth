<?php

/**
 * This is the model class for table "testobsparams".
 *
 * The followings are the available columns in table 'testobsparams':
 * @property integer $Id
 * @property string $Parameter
 * @property integer $ISNABL
 * @property integer $TestId
 * @property string $PUnit
 * @property string $PSymbol
 * @property string $PDType
 * @property double $Cost
 * @property integer $SeqNo
 * @property integer $PCatId
 * @property integer $PId
 * @property string $TAT
 * @property integer $FormVal
 * @property integer $AvgNeeded
 * @property string $Formulation
 *
 * The followings are the available model relations:
 * @property Certtestobs[] $certtestobs
 * @property Formuladts[] $formuladts
 * @property Formulation[] $formulations
 * @property Rirtestobs[] $rirtestobs
 * @property Stdsubdetails[] $stdsubdetails
 * @property Tests $test
 * @property Testparamcategory $pCat
 */
class Testobsparams extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'testobsparams';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('TestId', 'required'),
			array('ISNABL, TestId, SeqNo, PCatId, PId, FormVal, AvgNeeded', 'numerical', 'integerOnly'=>true),
			array('Cost', 'numerical'),
			array('Parameter', 'length', 'max'=>150),
			array('PUnit, PSymbol, TAT', 'length', 'max'=>20),
			array('PDType', 'length', 'max'=>1),
			array('Formulation', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Parameter, ISNABL, TestId, PUnit, PSymbol, PDType, Cost, SeqNo, PCatId, PId, TAT, FormVal, AvgNeeded, Formulation', 'safe', 'on'=>'search'),
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
			'certtestobs' => array(self::HAS_MANY, 'Certtestobs', 'PId'),
			'formuladts' => array(self::HAS_MANY, 'Formuladts', 'PId'),
			'formulations' => array(self::HAS_MANY, 'Formulation', 'PId'),
			'rirtestobs' => array(self::HAS_MANY, 'Rirtestobs', 'TPID'),
			'stdsubdetails' => array(self::HAS_MANY, 'Stdsubdetails', 'PId'),
			'test' => array(self::BELONGS_TO, 'Tests', 'TestId'),
			'pCat' => array(self::BELONGS_TO, 'Testparamcategory', 'PCatId'),
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
			'ISNABL' => 'Isnabl',
			'TestId' => 'Test',
			'PUnit' => 'Punit',
			'PSymbol' => 'Psymbol',
			'PDType' => 'Pdtype',
			'Cost' => 'Cost',
			'SeqNo' => 'Seq No',
			'PCatId' => 'Pcat',
			'PId' => 'Pid',
			'TAT' => 'Tat',
			'FormVal' => 'Form Val',
			'AvgNeeded' => 'Avg Needed',
			'Formulation' => 'Formulation',
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
		$criteria->compare('ISNABL',$this->ISNABL);
		$criteria->compare('TestId',$this->TestId);
		$criteria->compare('PUnit',$this->PUnit,true);
		$criteria->compare('PSymbol',$this->PSymbol,true);
		$criteria->compare('PDType',$this->PDType,true);
		$criteria->compare('Cost',$this->Cost);
		$criteria->compare('SeqNo',$this->SeqNo);
		$criteria->compare('PCatId',$this->PCatId);
		$criteria->compare('PId',$this->PId);
		$criteria->compare('TAT',$this->TAT,true);
		$criteria->compare('FormVal',$this->FormVal);
		$criteria->compare('AvgNeeded',$this->AvgNeeded);
		$criteria->compare('Formulation',$this->Formulation,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Testobsparams the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
