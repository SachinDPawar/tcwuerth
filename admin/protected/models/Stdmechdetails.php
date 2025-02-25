<?php

/**
 * This is the model class for table "stdmechdetails".
 *
 * The followings are the available columns in table 'stdmechdetails':
 * @property string $Id
 * @property string $MBId
 * @property integer $PId
 * @property string $Min
 * @property string $Max
 * @property integer $IsMajor
 *
 * The followings are the available model relations:
 * @property Stdmechbasic $mB
 * @property Mechparams $p
 */
class Stdmechdetails extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stdmechdetails';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('MBId, PId', 'required'),
			array('PId, IsMajor', 'numerical', 'integerOnly'=>true),
			array('MBId, Min, Max', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, MBId, PId, Min, Max, IsMajor', 'safe', 'on'=>'search'),
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
			'mB' => array(self::BELONGS_TO, 'Stdmechbasic', 'MBId'),
			'p' => array(self::BELONGS_TO, 'Mechparams', 'PId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'MBId' => 'Mbid',
			'PId' => 'Pid',
			'Min' => 'Min',
			'Max' => 'Max',
			'IsMajor' => 'Is Major',
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
		$criteria->compare('MBId',$this->MBId,true);
		$criteria->compare('PId',$this->PId);
		$criteria->compare('Min',$this->Min,true);
		$criteria->compare('Max',$this->Max,true);
		$criteria->compare('IsMajor',$this->IsMajor);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stdmechdetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
