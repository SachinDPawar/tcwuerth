<?php

/**
 * This is the model class for table "certformdetails".
 *
 * The followings are the available columns in table 'certformdetails':
 * @property integer $Id
 * @property integer $CFSID
 * @property string $Param
 * @property integer $IsMajor
 * @property string $PType
 * @property string $Min
 * @property string $Max
 *
 * The followings are the available model relations:
 * @property Certformsec $cFS
 */
class Certformdetails extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'certformdetails';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CFSID, Param, PType', 'required'),
			array('CFSID, IsMajor', 'numerical', 'integerOnly'=>true),
			array('Param, PType', 'length', 'max'=>20),
			array('Min, Max', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, CFSID, Param, IsMajor, PType, Min, Max', 'safe', 'on'=>'search'),
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
			'cFS' => array(self::BELONGS_TO, 'Certformsec', 'CFSID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'CFSID' => 'Cfsid',
			'Param' => 'Param',
			'IsMajor' => 'Is Major',
			'PType' => 'Ptype',
			'Min' => 'Min',
			'Max' => 'Max',
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
		$criteria->compare('CFSID',$this->CFSID);
		$criteria->compare('Param',$this->Param,true);
		$criteria->compare('IsMajor',$this->IsMajor);
		$criteria->compare('PType',$this->PType,true);
		$criteria->compare('Min',$this->Min,true);
		$criteria->compare('Max',$this->Max,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Certformdetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
