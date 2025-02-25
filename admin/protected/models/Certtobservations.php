<?php

/**
 * This is the model class for table "certtobservations".
 *
 * The followings are the available columns in table 'certtobservations':
 * @property string $Id
 * @property string $TObbasicId
 * @property string $CertTparamId
 * @property string $Value
 *
 * The followings are the available model relations:
 * @property Certtparams $certTparam
 * @property Certtobbasic $tObbasic
 */
class Certtobservations extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'certtobservations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('TObbasicId, CertTparamId, Value', 'required'),
			array('TObbasicId, CertTparamId', 'length', 'max'=>20),
			array('Value', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, TObbasicId, CertTparamId, Value', 'safe', 'on'=>'search'),
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
			'certTparam' => array(self::BELONGS_TO, 'Certtparams', 'CertTparamId'),
			'tObbasic' => array(self::BELONGS_TO, 'Certtobbasic', 'TObbasicId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'TObbasicId' => 'Tobbasic',
			'CertTparamId' => 'Cert Tparam',
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
		$criteria->compare('TObbasicId',$this->TObbasicId,true);
		$criteria->compare('CertTparamId',$this->CertTparamId,true);
		$criteria->compare('Value',$this->Value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Certtobservations the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
