<?php

/**
 * This is the model class for table "certtestspecs".
 *
 * The followings are the available columns in table 'certtestspecs':
 * @property string $Id
 * @property string $CSID
 * @property integer $PID
 * @property string $Parameter
 * @property string $Min
 * @property string $Max
 * @property integer $ShowInCert
 *
 * The followings are the available model relations:
 * @property Certsections $cS
 * @property Testobsparams $p
 */
class Certtestspecs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'certtestspecs';
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
			array('PID, ShowInCert', 'numerical', 'integerOnly'=>true),
			array('CSID, Min, Max', 'length', 'max'=>20),
			array('Parameter', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, CSID, PID, Parameter, Min, Max, ShowInCert', 'safe', 'on'=>'search'),
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
			'CSID' => 'Csid',
			'PID' => 'Pid',
			'Parameter' => 'Parameter',
			'Min' => 'Min',
			'Max' => 'Max',
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
		$criteria->compare('CSID',$this->CSID,true);
		$criteria->compare('PID',$this->PID);
		$criteria->compare('Parameter',$this->Parameter,true);
		$criteria->compare('Min',$this->Min,true);
		$criteria->compare('Max',$this->Max,true);
		$criteria->compare('ShowInCert',$this->ShowInCert);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Certtestspecs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
