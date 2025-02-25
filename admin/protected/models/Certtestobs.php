<?php

/**
 * This is the model class for table "certtestobs".
 *
 * The followings are the available columns in table 'certtestobs':
 * @property string $Id
 * @property string $CTID
 * @property integer $PID
 * @property string $Value
 * @property integer $ShowInCert
 *
 * The followings are the available model relations:
 * @property Testobsparams $p
 * @property Certtest $cT
 */
class Certtestobs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'certtestobs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CTID, PID', 'required'),
			array('PID, ShowInCert', 'numerical', 'integerOnly'=>true),
			array('CTID', 'length', 'max'=>20),
			array('Value', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, CTID, PID, Value, ShowInCert', 'safe', 'on'=>'search'),
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
			'p' => array(self::BELONGS_TO, 'Testobsparams', 'PID'),
			'cT' => array(self::BELONGS_TO, 'Certtest', 'CTID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'CTID' => 'Ctid',
			'PID' => 'Pid',
			'Value' => 'Value',
			'ShowInCert' => 'Show In Cert',
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
		$criteria->compare('CTID',$this->CTID,true);
		$criteria->compare('PID',$this->PID);
		$criteria->compare('Value',$this->Value,true);
		$criteria->compare('ShowInCert',$this->ShowInCert);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Certtestobs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
