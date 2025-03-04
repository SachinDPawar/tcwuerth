<?php

/**
 * This is the model class for table "torqtensobservations".
 *
 * The followings are the available columns in table 'torqtensobservations':
 * @property string $Id
 * @property string $TTBId
 * @property string $Torque
 * @property string $Force
 * @property string $Coff_Friction
 * @property string $Status
 * @property integer $SeqNo
 *
 * The followings are the available model relations:
 * @property Torqtensbasicinfo $tTB
 */
class Torqtensobservations extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'torqtensobservations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('TTBId', 'required'),
			array('SeqNo', 'numerical', 'integerOnly'=>true),
			array('TTBId', 'length', 'max'=>20),
			array('Torque, Force, Coff_Friction', 'length', 'max'=>50),
			array('Status', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, TTBId, Torque, Force, Coff_Friction, Status, SeqNo', 'safe', 'on'=>'search'),
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
			'tTB' => array(self::BELONGS_TO, 'Torqtensbasicinfo', 'TTBId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'TTBId' => 'Ttbid',
			'Torque' => 'Torque',
			'Force' => 'Force',
			'Coff_Friction' => 'Coff Friction',
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
		$criteria->compare('TTBId',$this->TTBId,true);
		$criteria->compare('Torque',$this->Torque,true);
		$criteria->compare('Force',$this->Force,true);
		$criteria->compare('Coff_Friction',$this->Coff_Friction,true);
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
	 * @return Torqtensobservations the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
