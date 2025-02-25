<?php

/**
 * This is the model class for table "stdchemdetails".
 *
 * The followings are the available columns in table 'stdchemdetails':
 * @property string $Id
 * @property integer $PId
 * @property string $PSym
 * @property double $Min
 * @property string $Max
 * @property string $IsMajor
 * @property string $CBId
 *
 * The followings are the available model relations:
 * @property Elements $p
 * @property Stdchembasic $cB
 */
class Stdchemdetails extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stdchemdetails';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('PId, CBId', 'required'),
			array('PId', 'numerical', 'integerOnly'=>true),
			array('Min', 'numerical'),
			array('PSym', 'length', 'max'=>10),
			array('Max, IsMajor, CBId', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, PId, PSym, Min, Max, IsMajor, CBId', 'safe', 'on'=>'search'),
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
			'p' => array(self::BELONGS_TO, 'Elements', 'PId'),
			'cB' => array(self::BELONGS_TO, 'Stdchembasic', 'CBId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'PId' => 'Pid',
			'PSym' => 'Psym',
			'Min' => 'Min',
			'Max' => 'Max',
			'IsMajor' => 'Is Major',
			'CBId' => 'Cbid',
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
		$criteria->compare('PId',$this->PId);
		$criteria->compare('PSym',$this->PSym,true);
		$criteria->compare('Min',$this->Min);
		$criteria->compare('Max',$this->Max,true);
		$criteria->compare('IsMajor',$this->IsMajor,true);
		$criteria->compare('CBId',$this->CBId,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stdchemdetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
