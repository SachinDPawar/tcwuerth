<?php

/**
 * This is the model class for table "certsections".
 *
 * The followings are the available columns in table 'certsections':
 * @property string $Id
 * @property string $CBID
 * @property string $Section
 * @property string $Keyword
 * @property integer $SSID
 * @property integer $TMID
 * @property string $Reference
 * @property string $Ref
 * @property string $Remark
 * @property string $Extra
 *
 * The followings are the available model relations:
 * @property Certnonmetallic[] $certnonmetallics
 * @property Certbasic $cB
 * @property Certtest[] $certtests
 * @property Certtestspecs[] $certtestspecs
 */
class Certsections extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'certsections';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CBID', 'required'),
			array('SSID, TMID', 'numerical', 'integerOnly'=>true),
			array('CBID, Keyword', 'length', 'max'=>20),
			array('Section', 'length', 'max'=>50),
			array('Remark', 'length', 'max'=>30),
			array('Reference, Ref, Extra', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, CBID, Section, Keyword, SSID, TMID, Reference, Ref, Remark, Extra', 'safe', 'on'=>'search'),
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
			'certnonmetallics' => array(self::HAS_MANY, 'Certnonmetallic', 'CSID'),
			'cB' => array(self::BELONGS_TO, 'Certbasic', 'CBID'),
			'certtests' => array(self::HAS_MANY, 'Certtest', 'CSID'),
			'certtestspecs' => array(self::HAS_MANY, 'Certtestspecs', 'CSID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'CBID' => 'Cbid',
			'Section' => 'Section',
			'Keyword' => 'Keyword',
			'SSID' => 'Ssid',
			'TMID' => 'Tmid',
			'Reference' => 'Reference',
			'Ref' => 'Ref',
			'Remark' => 'Remark',
			'Extra' => 'Extra',
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
		$criteria->compare('CBID',$this->CBID,true);
		$criteria->compare('Section',$this->Section,true);
		$criteria->compare('Keyword',$this->Keyword,true);
		$criteria->compare('SSID',$this->SSID);
		$criteria->compare('TMID',$this->TMID);
		$criteria->compare('Reference',$this->Reference,true);
		$criteria->compare('Ref',$this->Ref,true);
		$criteria->compare('Remark',$this->Remark,true);
		$criteria->compare('Extra',$this->Extra,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Certsections the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
