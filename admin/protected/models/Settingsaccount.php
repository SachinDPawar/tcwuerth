<?php

/**
 * This is the model class for table "settingsaccount".
 *
 * The followings are the available columns in table 'settingsaccount':
 * @property integer $Id
 * @property string $QAlpha
 * @property string $QYear
 * @property integer $QVDays
 * @property string $QuoteNote
 * @property string $LastQuoteNo
 * @property integer $IsTax
 * @property double $Tax
 * @property string $TaxLabel
 * @property string $Currency
 * @property string $IVDays
 * @property string $InvoiceNote
 * @property string $HSN
 * @property integer $BranchId
 * @property integer $CID
 */
class Settingsaccount extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'settingsaccount';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('TaxLabel', 'required'),
			array('QVDays, IsTax, BranchId, CID', 'numerical', 'integerOnly'=>true),
			array('Tax', 'numerical'),
			array('QAlpha, QYear, LastQuoteNo, Currency, IVDays, HSN', 'length', 'max'=>20),
			array('TaxLabel', 'length', 'max'=>10),
			array('QuoteNote, InvoiceNote', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, QAlpha, QYear, QVDays, QuoteNote, LastQuoteNo, IsTax, Tax, TaxLabel, Currency, IVDays, InvoiceNote, HSN, BranchId, CID', 'safe', 'on'=>'search'),
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
			'QAlpha' => 'Qalpha',
			'QYear' => 'Qyear',
			'QVDays' => 'Qvdays',
			'QuoteNote' => 'Quote Note',
			'LastQuoteNo' => 'Last Quote No',
			'IsTax' => 'Is Tax',
			'Tax' => 'Tax',
			'TaxLabel' => 'Tax Label',
			'Currency' => 'Currency',
			'IVDays' => 'Ivdays',
			'InvoiceNote' => 'Invoice Note',
			'HSN' => 'Hsn',
			'BranchId' => 'Branch',
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
		$criteria->compare('QAlpha',$this->QAlpha,true);
		$criteria->compare('QYear',$this->QYear,true);
		$criteria->compare('QVDays',$this->QVDays);
		$criteria->compare('QuoteNote',$this->QuoteNote,true);
		$criteria->compare('LastQuoteNo',$this->LastQuoteNo,true);
		$criteria->compare('IsTax',$this->IsTax);
		$criteria->compare('Tax',$this->Tax);
		$criteria->compare('TaxLabel',$this->TaxLabel,true);
		$criteria->compare('Currency',$this->Currency,true);
		$criteria->compare('IVDays',$this->IVDays,true);
		$criteria->compare('InvoiceNote',$this->InvoiceNote,true);
		$criteria->compare('HSN',$this->HSN,true);
		$criteria->compare('BranchId',$this->BranchId);
		$criteria->compare('CID',$this->CID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Settingsaccount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
