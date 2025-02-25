<?php

/**
 * This is the model class for table "winclratobs".
 *
 * The followings are the available columns in table 'winclratobs':
 * @property string $Id
 * @property string $WIRId
 * @property integer $Idx
 * @property string $ThinA
 * @property string $ThinB
 * @property string $ThinC
 * @property string $ThinD
 * @property string $ThickA
 * @property string $ThickB
 * @property string $ThickC
 * @property string $ThickD
 *
 * The followings are the available model relations:
 * @property Winclratbasic $wIR
 */
class Winclratobs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'winclratobs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Id, WIRId', 'required'),
			array('Idx', 'numerical', 'integerOnly'=>true),
			array('Id, WIRId', 'length', 'max'=>20),
			array('ThinA, ThinB, ThinC, ThinD, ThickA, ThickB, ThickC, ThickD', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, WIRId, Idx, ThinA, ThinB, ThinC, ThinD, ThickA, ThickB, ThickC, ThickD', 'safe', 'on'=>'search'),
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
			'wIR' => array(self::BELONGS_TO, 'Winclratbasic', 'WIRId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'WIRId' => 'Wirid',
			'Idx' => 'Idx',
			'ThinA' => 'Thin A',
			'ThinB' => 'Thin B',
			'ThinC' => 'Thin C',
			'ThinD' => 'Thin D',
			'ThickA' => 'Thick A',
			'ThickB' => 'Thick B',
			'ThickC' => 'Thick C',
			'ThickD' => 'Thick D',
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
		$criteria->compare('WIRId',$this->WIRId,true);
		$criteria->compare('Idx',$this->Idx);
		$criteria->compare('ThinA',$this->ThinA,true);
		$criteria->compare('ThinB',$this->ThinB,true);
		$criteria->compare('ThinC',$this->ThinC,true);
		$criteria->compare('ThinD',$this->ThinD,true);
		$criteria->compare('ThickA',$this->ThickA,true);
		$criteria->compare('ThickB',$this->ThickB,true);
		$criteria->compare('ThickC',$this->ThickC,true);
		$criteria->compare('ThickD',$this->ThickD,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Winclratobs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
