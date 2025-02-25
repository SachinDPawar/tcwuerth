<?php

/**
 * This is the model class for table "othbasic".
 *
 * The followings are the available columns in table 'othbasic':
 * @property integer $Id
 * @property string $RirTestId
 * @property integer $BParamId
 * @property string $BValue
 *
 * The followings are the available model relations:
 * @property Othparams $bParam
 * @property Rirtestdetail $rirTest
 */
class Othbasic extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'othbasic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('RirTestId', 'required'),
			array('BParamId', 'numerical', 'integerOnly'=>true),
			array('RirTestId', 'length', 'max'=>20),
			array('BValue', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, RirTestId, BParamId, BValue', 'safe', 'on'=>'search'),
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
			'bParam' => array(self::BELONGS_TO, 'Othparams', 'BParamId'),
			'rirTest' => array(self::BELONGS_TO, 'Rirtestdetail', 'RirTestId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'RirTestId' => 'Rir Test',
			'BParamId' => 'Bparam',
			'BValue' => 'Bvalue',
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
		$criteria->compare('RirTestId',$this->RirTestId,true);
		$criteria->compare('BParamId',$this->BParamId);
		$criteria->compare('BValue',$this->BValue,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Othbasic the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
