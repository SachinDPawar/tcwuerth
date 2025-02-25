<?php

/**
 * This is the model class for table "certexthardness".
 *
 * The followings are the available columns in table 'certexthardness':
 * @property string $Id
 * @property string $SeqNo
 * @property string $Type
 * @property string $Parameter
 * @property string $Requirement
 * @property string $Observation
 * @property string $Obs1
 * @property string $Obs2
 * @property string $Obs3
 * @property string $Obs4
 * @property string $Obs5
 * @property string $Remark
 * @property string $CreationDate
 * @property string $LastModified
 * @property string $ModifiedBy
 * @property string $CSID
 */
class Certexthardness extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'certexthardness';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CSID', 'required'),
			array('SeqNo, Type, Obs1, Obs2, Obs3, Obs4, Obs5, ModifiedBy, CSID', 'length', 'max'=>20),
			array('Parameter', 'length', 'max'=>50),
			array('Remark', 'length', 'max'=>150),
			array('Requirement, Observation, CreationDate, LastModified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, SeqNo, Type, Parameter, Requirement, Observation, Obs1, Obs2, Obs3, Obs4, Obs5, Remark, CreationDate, LastModified, ModifiedBy, CSID', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'SeqNo' => 'Seq No',
			'Type' => 'Type',
			'Parameter' => 'Parameter',
			'Requirement' => 'Requirement',
			'Observation' => 'Observation',
			'Obs1' => 'Obs1',
			'Obs2' => 'Obs2',
			'Obs3' => 'Obs3',
			'Obs4' => 'Obs4',
			'Obs5' => 'Obs5',
			'Remark' => 'Remark',
			'CreationDate' => 'Creation Date',
			'LastModified' => 'Last Modified',
			'ModifiedBy' => 'Modified By',
			'CSID' => 'Csid',
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
		$criteria->compare('SeqNo',$this->SeqNo,true);
		$criteria->compare('Type',$this->Type,true);
		$criteria->compare('Parameter',$this->Parameter,true);
		$criteria->compare('Requirement',$this->Requirement,true);
		$criteria->compare('Observation',$this->Observation,true);
		$criteria->compare('Obs1',$this->Obs1,true);
		$criteria->compare('Obs2',$this->Obs2,true);
		$criteria->compare('Obs3',$this->Obs3,true);
		$criteria->compare('Obs4',$this->Obs4,true);
		$criteria->compare('Obs5',$this->Obs5,true);
		$criteria->compare('Remark',$this->Remark,true);
		$criteria->compare('CreationDate',$this->CreationDate,true);
		$criteria->compare('LastModified',$this->LastModified,true);
		$criteria->compare('ModifiedBy',$this->ModifiedBy,true);
		$criteria->compare('CSID',$this->CSID,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Certexthardness the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
