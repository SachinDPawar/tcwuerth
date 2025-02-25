<?php

/**
 * This is the model class for table "testmethods".
 *
 * The followings are the available columns in table 'testmethods':
 * @property integer $Id
 * @property integer $TestId
 * @property string $Method
 * @property string $Extra
 * @property integer $IndId
 *
 * The followings are the available model relations:
 * @property Rirtestdetail[] $rirtestdetails
 * @property Rirtestobs[] $rirtestobs
 * @property Tests $test
 */
class Testmethods extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'testmethods';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('TestId, Method', 'required'),
			array('TestId, IndId', 'numerical', 'integerOnly'=>true),
			array('Method', 'length', 'max'=>300),
			array('Extra', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, TestId, Method, Extra, IndId', 'safe', 'on'=>'search'),
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
			'rirtestdetails' => array(self::HAS_MANY, 'Rirtestdetail', 'TestMethodId'),
			'rirtestobs' => array(self::HAS_MANY, 'Rirtestobs', 'TMID'),
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
			'TestId' => 'Test',
			'Method' => 'Method',
			'Extra' => 'Extra',
			'IndId' => 'Ind',
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
		$criteria->compare('TestId',$this->TestId);
		$criteria->compare('Method',$this->Method,true);
		$criteria->compare('Extra',$this->Extra,true);
		$criteria->compare('IndId',$this->IndId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Testmethods the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
