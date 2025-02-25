<?php

/**
 * This is the model class for table "certformsec".
 *
 * The followings are the available columns in table 'certformsec':
 * @property integer $Id
 * @property integer $CFId
 * @property integer $FSID
 *
 * The followings are the available model relations:
 * @property Certformdetails[] $certformdetails
 * @property Certformats $cF
 * @property Formatsections $fS
 */
class Certformsec extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'certformsec';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CFId, FSID', 'required'),
			array('CFId, FSID', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, CFId, FSID', 'safe', 'on'=>'search'),
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
			'certformdetails' => array(self::HAS_MANY, 'Certformdetails', 'CFSID'),
			'cF' => array(self::BELONGS_TO, 'Certformats', 'CFId'),
			'fS' => array(self::BELONGS_TO, 'Formatsections', 'FSID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'CFId' => 'Cfid',
			'FSID' => 'Fsid',
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
		$criteria->compare('CFId',$this->CFId);
		$criteria->compare('FSID',$this->FSID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Certformsec the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
