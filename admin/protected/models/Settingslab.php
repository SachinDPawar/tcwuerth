<?php

/**
 * This is the model class for table "settingslab".
 *
 * The followings are the available columns in table 'settingslab':
 * @property integer $Id
 * @property string $BatchCodeStart
 * @property string $LastBatchCode
 * @property string $LastTestNo
 * @property string $TCNoStart
 * @property string $BatchNoStart
 * @property string $DefaultTestNote
 * @property integer $BranchId
 * @property string $TestNoStart
 * @property string $ULRNoStart
 * @property string $LastULRNo
 * @property string $LabNoStart
 * @property string $LastLabNo
 * @property string $QAlpha
 * @property integer $CID
 */
class Settingslab extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'settingslab';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('BranchId, CID', 'numerical', 'integerOnly'=>true),
			array('BatchCodeStart, TCNoStart', 'length', 'max'=>50),
			array('LastBatchCode, LastTestNo, TestNoStart, ULRNoStart, LastULRNo, LabNoStart, LastLabNo, QAlpha', 'length', 'max'=>20),
			array('BatchNoStart', 'length', 'max'=>100),
			array('DefaultTestNote', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, BatchCodeStart, LastBatchCode, LastTestNo, TCNoStart, BatchNoStart, DefaultTestNote, BranchId, TestNoStart, ULRNoStart, LastULRNo, LabNoStart, LastLabNo, QAlpha, CID', 'safe', 'on'=>'search'),
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
			'BatchCodeStart' => 'Batch Code Start',
			'LastBatchCode' => 'Last Batch Code',
			'LastTestNo' => 'Last Test No',
			'TCNoStart' => 'Tcno Start',
			'BatchNoStart' => 'Batch No Start',
			'DefaultTestNote' => 'Default Test Note',
			'BranchId' => 'Branch',
			'TestNoStart' => 'Test No Start',
			'ULRNoStart' => 'Ulrno Start',
			'LastULRNo' => 'Last Ulrno',
			'LabNoStart' => 'Lab No Start',
			'LastLabNo' => 'Last Lab No',
			'QAlpha' => 'Qalpha',
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
		$criteria->compare('BatchCodeStart',$this->BatchCodeStart,true);
		$criteria->compare('LastBatchCode',$this->LastBatchCode,true);
		$criteria->compare('LastTestNo',$this->LastTestNo,true);
		$criteria->compare('TCNoStart',$this->TCNoStart,true);
		$criteria->compare('BatchNoStart',$this->BatchNoStart,true);
		$criteria->compare('DefaultTestNote',$this->DefaultTestNote,true);
		$criteria->compare('BranchId',$this->BranchId);
		$criteria->compare('TestNoStart',$this->TestNoStart,true);
		$criteria->compare('ULRNoStart',$this->ULRNoStart,true);
		$criteria->compare('LastULRNo',$this->LastULRNo,true);
		$criteria->compare('LabNoStart',$this->LabNoStart,true);
		$criteria->compare('LastLabNo',$this->LastLabNo,true);
		$criteria->compare('QAlpha',$this->QAlpha,true);
		$criteria->compare('CID',$this->CID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Settingslab the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
