<?php

/**
 * This is the model class for table "sstobservations".
 *
 * The followings are the available columns in table 'sstobservations':
 * @property string $Id
 * @property string $SstBasicId
 * @property string $Duration
 * @property string $OnDate
 * @property string $White
 * @property string $NoWhite
 * @property string $Red
 * @property string $NoRed
 * @property string $Status
 * @property integer $SeqNo
 *
 * The followings are the available model relations:
 * @property Sstbasicinfo $sstBasic
 */
class Sstobservations extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sstobservations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('SstBasicId', 'required'),
			array('SeqNo', 'numerical', 'integerOnly'=>true),
			array('SstBasicId, White, NoWhite, Red, NoRed', 'length', 'max'=>20),
			array('Duration', 'length', 'max'=>250),
			array('Status', 'length', 'max'=>100),
			array('OnDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, SstBasicId, Duration, OnDate, White, NoWhite, Red, NoRed, Status, SeqNo', 'safe', 'on'=>'search'),
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
			'sstBasic' => array(self::BELONGS_TO, 'Sstbasicinfo', 'SstBasicId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'SstBasicId' => 'Sst Basic',
			'Duration' => 'Duration',
			'OnDate' => 'On Date',
			'White' => 'White',
			'NoWhite' => 'No White',
			'Red' => 'Red',
			'NoRed' => 'No Red',
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

		$criteria->compare('Id',$this->Id,true);
		$criteria->compare('SstBasicId',$this->SstBasicId,true);
		$criteria->compare('Duration',$this->Duration,true);
		$criteria->compare('OnDate',$this->OnDate,true);
		$criteria->compare('White',$this->White,true);
		$criteria->compare('NoWhite',$this->NoWhite,true);
		$criteria->compare('Red',$this->Red,true);
		$criteria->compare('NoRed',$this->NoRed,true);
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
	 * @return Sstobservations the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
