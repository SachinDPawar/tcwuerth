<?php

/**
 * This is the model class for table "settings".
 *
 * The followings are the available columns in table 'settings':
 * @property integer $Id
 * @property string $LabNoStart
 * @property string $CompanyName
 * @property string $CompanyAddress
 * @property string $BatchCodeStart
 * @property string $LastBatchCode
 * @property string $LastTestNo
 * @property string $TCNoStart
 * @property string $BatchNoStart
 * @property string $DefaultNote
 * @property integer $IsTax
 * @property double $Tax
 * @property string $TaxLabel
 * @property string $QuoteVerifyEmails
 * @property integer $QVDays
 * @property string $Currency
 * @property integer $BranchId
 *
 * The followings are the available model relations:
 * @property Branches $branch
 */
class Settings extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('LabNoStart, TaxLabel', 'required'),
			array('IsTax, QVDays, BranchId', 'numerical', 'integerOnly'=>true),
			array('Tax', 'numerical'),
			array('LabNoStart, BatchCodeStart, TCNoStart', 'length', 'max'=>50),
			array('CompanyName, QuoteVerifyEmails', 'length', 'max'=>250),
			array('CompanyAddress', 'length', 'max'=>500),
			array('LastBatchCode, LastTestNo, Currency', 'length', 'max'=>20),
			array('BatchNoStart', 'length', 'max'=>100),
			array('TaxLabel', 'length', 'max'=>10),
			array('DefaultNote', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, LabNoStart, CompanyName, CompanyAddress, BatchCodeStart, LastBatchCode, LastTestNo, TCNoStart, BatchNoStart, DefaultNote, IsTax, Tax, TaxLabel, QuoteVerifyEmails, QVDays, Currency, BranchId', 'safe', 'on'=>'search'),
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
			'branch' => array(self::BELONGS_TO, 'Branches', 'BranchId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'LabNoStart' => 'Lab No Start',
			'CompanyName' => 'Company Name',
			'CompanyAddress' => 'Company Address',
			'BatchCodeStart' => 'Batch Code Start',
			'LastBatchCode' => 'Last Batch Code',
			'LastTestNo' => 'Last Test No',
			'TCNoStart' => 'Tcno Start',
			'BatchNoStart' => 'Batch No Start',
			'DefaultNote' => 'Default Note',
			'IsTax' => 'Is Tax',
			'Tax' => 'Tax',
			'TaxLabel' => 'Tax Label',
			'QuoteVerifyEmails' => 'Quote Verify Emails',
			'QVDays' => 'Qvdays',
			'Currency' => 'Currency',
			'BranchId' => 'Branch',
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
		$criteria->compare('LabNoStart',$this->LabNoStart,true);
		$criteria->compare('CompanyName',$this->CompanyName,true);
		$criteria->compare('CompanyAddress',$this->CompanyAddress,true);
		$criteria->compare('BatchCodeStart',$this->BatchCodeStart,true);
		$criteria->compare('LastBatchCode',$this->LastBatchCode,true);
		$criteria->compare('LastTestNo',$this->LastTestNo,true);
		$criteria->compare('TCNoStart',$this->TCNoStart,true);
		$criteria->compare('BatchNoStart',$this->BatchNoStart,true);
		$criteria->compare('DefaultNote',$this->DefaultNote,true);
		$criteria->compare('IsTax',$this->IsTax);
		$criteria->compare('Tax',$this->Tax);
		$criteria->compare('TaxLabel',$this->TaxLabel,true);
		$criteria->compare('QuoteVerifyEmails',$this->QuoteVerifyEmails,true);
		$criteria->compare('QVDays',$this->QVDays);
		$criteria->compare('Currency',$this->Currency,true);
		$criteria->compare('BranchId',$this->BranchId);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Settings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
