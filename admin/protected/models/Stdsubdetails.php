<?php

/**
 * This is the model class for table "stdsubdetails".
 *
 * The followings are the available columns in table 'stdsubdetails':
 * @property string $Id
 * @property integer $PId
 * @property integer $TMID
 * @property string $TUID
 * @property string $IsMajor
 * @property integer $SubStdId
 * @property double $Cost
 * @property double $PermMin
 * @property double $PermMax
 * @property double $SpecMin
 * @property double $SpecMax
 * @property string $PUnit
 * @property integer $ISNABL
 * @property string $OId
 * @property string $OPId
 * @property string $OSID
 * @property string $OSSID
 *
 * The followings are the available model relations:
 * @property Tests $tU
 * @property Substandards $subStd
 * @property Testmethods $tM
 * @property Testobsparams $p
 */
class Stdsubdetails extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'stdsubdetails';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('PId, SubStdId', 'required'),
			array('PId, TMID, SubStdId, ISNABL', 'numerical', 'integerOnly'=>true),
			array('Cost, PermMin, PermMax, SpecMin, SpecMax', 'numerical'),
			array('TUID, IsMajor, OId, OPId, OSID, OSSID', 'length', 'max'=>20),
			array('PUnit', 'length', 'max'=>250),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, PId, TMID, TUID, IsMajor, SubStdId, Cost, PermMin, PermMax, SpecMin, SpecMax, PUnit, ISNABL, OId, OPId, OSID, OSSID', 'safe', 'on'=>'search'),
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
			'tU' => array(self::BELONGS_TO, 'Tests', 'TUID'),
			'subStd' => array(self::BELONGS_TO, 'Substandards', 'SubStdId'),
			'tM' => array(self::BELONGS_TO, 'Testmethods', 'TMID'),
			'p' => array(self::BELONGS_TO, 'Testobsparams', 'PId'),
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
			'TMID' => 'Tmid',
			'TUID' => 'Tuid',
			'IsMajor' => 'Is Major',
			'SubStdId' => 'Sub Std',
			'Cost' => 'Cost',
			'PermMin' => 'Perm Min',
			'PermMax' => 'Perm Max',
			'SpecMin' => 'Spec Min',
			'SpecMax' => 'Spec Max',
			'PUnit' => 'Punit',
			'ISNABL' => 'Isnabl',
			'OId' => 'Oid',
			'OPId' => 'Opid',
			'OSID' => 'Osid',
			'OSSID' => 'Ossid',
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
		$criteria->compare('TMID',$this->TMID);
		$criteria->compare('TUID',$this->TUID,true);
		$criteria->compare('IsMajor',$this->IsMajor,true);
		$criteria->compare('SubStdId',$this->SubStdId);
		$criteria->compare('Cost',$this->Cost);
		$criteria->compare('PermMin',$this->PermMin);
		$criteria->compare('PermMax',$this->PermMax);
		$criteria->compare('SpecMin',$this->SpecMin);
		$criteria->compare('SpecMax',$this->SpecMax);
		$criteria->compare('PUnit',$this->PUnit,true);
		$criteria->compare('ISNABL',$this->ISNABL);
		$criteria->compare('OId',$this->OId,true);
		$criteria->compare('OPId',$this->OPId,true);
		$criteria->compare('OSID',$this->OSID,true);
		$criteria->compare('OSSID',$this->OSSID,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Stdsubdetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
