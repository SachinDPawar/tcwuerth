<?php

/**
 * This is the model class for table "stdsub".
 *
 * The followings are the available columns in table 'stdsub':
 * @property string $Id
 * @property string $StandardId
 * @property string $SubStandard
 * @property string $Applicable
 *
 * The followings are the available model relations:
 * @property Chemicalcomposition[] $chemicalcompositions
 * @property Standards $standard
 */
class Stdsub extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stdsub';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('StandardId', 'required'),
			array('StandardId', 'length', 'max'=>20),
			array('Applicable', 'length', 'max'=>10),
			array('SubStandard', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, StandardId, SubStandard, Applicable', 'safe', 'on'=>'search'),
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
			'chemicalcompositions' => array(self::HAS_MANY, 'Chemicalcomposition', 'StdsubId'),
			'standard' => array(self::BELONGS_TO, 'Standards', 'StandardId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'StandardId' => 'Standard',
			'SubStandard' => 'Sub Standard',
			'Applicable' => 'Applicable',
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
		$criteria->compare('StandardId',$this->StandardId,true);
		$criteria->compare('SubStandard',$this->SubStandard,true);
		$criteria->compare('Applicable',$this->Applicable,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stdsub the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
