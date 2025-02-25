<?php

/**
 * This is the model class for table "mdstdstests".
 *
 * The followings are the available columns in table 'mdstdstests':
 * @property integer $Id
 * @property integer $MTID
 * @property integer $TID
 * @property string $TUID
 * @property integer $SSDID
 * @property integer $TMID
 * @property integer $Freq
 * @property string $CreatedOn
 *
 * The followings are the available model relations:
 * @property Mdstdstestbasedetails[] $mdstdstestbasedetails
 * @property Mdstdstestobsdetails[] $mdstdstestobsdetails
 * @property Tests $t
 * @property Mdstds $mT
 * @property Testmethods $tM
 * @property Substandards $sSD
 */
class Mdstdstests extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mdstdstests';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('MTID, TID, TUID, Freq, CreatedOn', 'required'),
			array('MTID, TID, SSDID, TMID, Freq', 'numerical', 'integerOnly'=>true),
			array('TUID', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, MTID, TID, TUID, SSDID, TMID, Freq, CreatedOn', 'safe', 'on'=>'search'),
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
			'mdstdstestbasedetails' => array(self::HAS_MANY, 'Mdstdstestbasedetails', 'MTTID'),
			'mdstdstestobsdetails' => array(self::HAS_MANY, 'Mdstdstestobsdetails', 'MTTID'),
			't' => array(self::BELONGS_TO, 'Tests', 'TID'),
			'mT' => array(self::BELONGS_TO, 'Mdstds', 'MTID'),
			'tM' => array(self::BELONGS_TO, 'Testmethods', 'TMID'),
			'sSD' => array(self::BELONGS_TO, 'Substandards', 'SSDID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'MTID' => 'Mtid',
			'TID' => 'Tid',
			'TUID' => 'Tuid',
			'SSDID' => 'Ssdid',
			'TMID' => 'Tmid',
			'Freq' => 'Freq',
			'CreatedOn' => 'Created On',
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
		$criteria->compare('MTID',$this->MTID);
		$criteria->compare('TID',$this->TID);
		$criteria->compare('TUID',$this->TUID,true);
		$criteria->compare('SSDID',$this->SSDID);
		$criteria->compare('TMID',$this->TMID);
		$criteria->compare('Freq',$this->Freq);
		$criteria->compare('CreatedOn',$this->CreatedOn,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Mdstdstests the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
