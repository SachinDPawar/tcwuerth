<?php

/**
 * This is the model class for table "mdstdstestobsdetails".
 *
 * The followings are the available columns in table 'mdstdstestobsdetails':
 * @property string $Id
 * @property integer $MTTID
 * @property integer $PID
 * @property string $SpecMin
 * @property string $SpecMax
 * @property string $Modified
 * @property string $Value
 *
 * The followings are the available model relations:
 * @property Mdstdstests $mTT
 * @property Testobsparams $p
 */
class Mdstdstestobsdetails extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mdstdstestobsdetails';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('MTTID, PID, SpecMin, SpecMax, Modified, Value', 'required'),
			array('MTTID, PID', 'numerical', 'integerOnly'=>true),
			array('SpecMin, SpecMax', 'length', 'max'=>30),
			array('Value', 'length', 'max'=>130),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, MTTID, PID, SpecMin, SpecMax, Modified, Value', 'safe', 'on'=>'search'),
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
			'mTT' => array(self::BELONGS_TO, 'Mdstdstests', 'MTTID'),
			'p' => array(self::BELONGS_TO, 'Testobsparams', 'PID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'MTTID' => 'Mttid',
			'PID' => 'Pid',
			'SpecMin' => 'Spec Min',
			'SpecMax' => 'Spec Max',
			'Modified' => 'Modified',
			'Value' => 'Value',
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
		$criteria->compare('MTTID',$this->MTTID);
		$criteria->compare('PID',$this->PID);
		$criteria->compare('SpecMin',$this->SpecMin,true);
		$criteria->compare('SpecMax',$this->SpecMax,true);
		$criteria->compare('Modified',$this->Modified,true);
		$criteria->compare('Value',$this->Value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Mdstdstestobsdetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
