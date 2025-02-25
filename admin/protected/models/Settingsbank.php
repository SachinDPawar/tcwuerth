<?php

/**
 * This is the model class for table "settingsbank".
 *
 * The followings are the available columns in table 'settingsbank':
 * @property integer $Id
 * @property string $BankName
 * @property string $AccountName
 * @property string $Nature
 * @property string $BranchName
 * @property string $AccountNo
 * @property string $IFSC
 * @property string $Swift
 * @property integer $BranchId
 * @property integer $CID
 */
class Settingsbank extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'settingsbank';
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
			array('BankName, AccountName, Nature, BranchName', 'length', 'max'=>250),
			array('AccountNo, IFSC, Swift', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, BankName, AccountName, Nature, BranchName, AccountNo, IFSC, Swift, BranchId, CID', 'safe', 'on'=>'search'),
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
			'BankName' => 'Bank Name',
			'AccountName' => 'Account Name',
			'Nature' => 'Nature',
			'BranchName' => 'Branch Name',
			'AccountNo' => 'Account No',
			'IFSC' => 'Ifsc',
			'Swift' => 'Swift',
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
		$criteria->compare('BankName',$this->BankName,true);
		$criteria->compare('AccountName',$this->AccountName,true);
		$criteria->compare('Nature',$this->Nature,true);
		$criteria->compare('BranchName',$this->BranchName,true);
		$criteria->compare('AccountNo',$this->AccountNo,true);
		$criteria->compare('IFSC',$this->IFSC,true);
		$criteria->compare('Swift',$this->Swift,true);
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
	 * @return Settingsbank the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
