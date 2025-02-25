<?php

/**
 * This is the model class for table "formulation".
 *
 * The followings are the available columns in table 'formulation':
 * @property integer $Id
 * @property string $Formula
 * @property integer $TestId
 * @property integer $PId
 *
 * The followings are the available model relations:
 * @property Formuladts[] $formuladts
 * @property Tests $test
 * @property Testobsparams $p
 */
class Formulation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'formulation';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Formula, TestId', 'required'),
			array('TestId, PId', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Formula, TestId, PId', 'safe', 'on'=>'search'),
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
			'formuladts' => array(self::HAS_MANY, 'Formuladts', 'FId'),
			'test' => array(self::BELONGS_TO, 'Tests', 'TestId'),
			'p' => array(self::BELONGS_TO, 'Testobsparams', 'PId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'Formula' => 'Formula',
			'TestId' => 'Test',
			'PId' => 'Pid',
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
		$criteria->compare('Formula',$this->Formula,true);
		$criteria->compare('TestId',$this->TestId);
		$criteria->compare('PId',$this->PId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Formulation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
