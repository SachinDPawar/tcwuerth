<?php

/**
 * This is the model class for table "threadlapobs".
 *
 * The followings are the available columns in table 'threadlapobs':
 * @property string $Id
 * @property string $ThreadId
 * @property string $Parameter
 * @property string $Observation
 * @property string $Remark
 * @property integer $SrNo
 *
 * The followings are the available model relations:
 * @property Threadlapbasic $thread
 */
class Threadlapobs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'threadlapobs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ThreadId, Parameter', 'required'),
			array('SrNo', 'numerical', 'integerOnly'=>true),
			array('ThreadId', 'length', 'max'=>20),
			array('Parameter, Remark', 'length', 'max'=>250),
			array('Observation', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, ThreadId, Parameter, Observation, Remark, SrNo', 'safe', 'on'=>'search'),
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
			'thread' => array(self::BELONGS_TO, 'Threadlapbasic', 'ThreadId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'ThreadId' => 'Thread',
			'Parameter' => 'Parameter',
			'Observation' => 'Observation',
			'Remark' => 'Remark',
			'SrNo' => 'Sr No',
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
		$criteria->compare('ThreadId',$this->ThreadId,true);
		$criteria->compare('Parameter',$this->Parameter,true);
		$criteria->compare('Observation',$this->Observation,true);
		$criteria->compare('Remark',$this->Remark,true);
		$criteria->compare('SrNo',$this->SrNo);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Threadlapobs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
