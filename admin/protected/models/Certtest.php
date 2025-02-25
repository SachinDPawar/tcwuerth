<?php

/**
 * This is the model class for table "certtest".
 *
 * The followings are the available columns in table 'certtest':
 * @property string $Id
 * @property string $CSID
 * @property string $LabNo
 * @property string $BatchCode
 * @property string $HeatNo
 * @property string $RTID
 * @property integer $TMID
 * @property integer $SSID
 * @property string $ShowInCert
 * @property string $Remark
 * @property string $TUID
 *
 * The followings are the available model relations:
 * @property Certsections $cS
 * @property Rirtestdetail $rT
 * @property Certtestobs[] $certtestobs
 */
class Certtest extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'certtest';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CSID', 'required'),
			array('TMID, SSID', 'numerical', 'integerOnly'=>true),
			array('CSID, RTID, ShowInCert, TUID', 'length', 'max'=>20),
			array('LabNo, BatchCode, Remark', 'length', 'max'=>50),
			array('HeatNo', 'length', 'max'=>130),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, CSID, LabNo, BatchCode, HeatNo, RTID, TMID, SSID, ShowInCert, Remark, TUID', 'safe', 'on'=>'search'),
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
			'cS' => array(self::BELONGS_TO, 'Certsections', 'CSID'),
			'rT' => array(self::BELONGS_TO, 'Rirtestdetail', 'RTID'),
			'certtestobs' => array(self::HAS_MANY, 'Certtestobs', 'CTID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'CSID' => 'Csid',
			'LabNo' => 'Lab No',
			'BatchCode' => 'Batch Code',
			'HeatNo' => 'Heat No',
			'RTID' => 'Rtid',
			'TMID' => 'Tmid',
			'SSID' => 'Ssid',
			'ShowInCert' => 'Show In Cert',
			'Remark' => 'Remark',
			'TUID' => 'Tuid',
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
		$criteria->compare('CSID',$this->CSID,true);
		$criteria->compare('LabNo',$this->LabNo,true);
		$criteria->compare('BatchCode',$this->BatchCode,true);
		$criteria->compare('HeatNo',$this->HeatNo,true);
		$criteria->compare('RTID',$this->RTID,true);
		$criteria->compare('TMID',$this->TMID);
		$criteria->compare('SSID',$this->SSID);
		$criteria->compare('ShowInCert',$this->ShowInCert,true);
		$criteria->compare('Remark',$this->Remark,true);
		$criteria->compare('TUID',$this->TUID,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Certtest the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
