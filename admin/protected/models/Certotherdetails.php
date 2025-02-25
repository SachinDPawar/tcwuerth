<?php

/**
 * This is the model class for table "certotherdetails".
 *
 * The followings are the available columns in table 'certotherdetails':
 * @property string $Id
 * @property string $SeqNo
 * @property string $Parameter
 * @property string $Required
 * @property string $Observation
 * @property string $Remark
 * @property string $CreationDate
 * @property string $LastModified
 * @property string $ModifiedBy
 * @property string $CSID
 */
class Certotherdetails extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'certotherdetails';
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
			array('SeqNo, ModifiedBy, CSID', 'length', 'max'=>20),
			array('Remark', 'length', 'max'=>150),
			array('Parameter, Required, Observation, CreationDate, LastModified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, SeqNo, Parameter, Required, Observation, Remark, CreationDate, LastModified, ModifiedBy, CSID', 'safe', 'on'=>'search'),
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
			'Parameter' => 'Parameter',
			'Required' => 'Required',
			'Observation' => 'Observation',
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
		$criteria->compare('Parameter',$this->Parameter,true);
		$criteria->compare('Required',$this->Required,true);
		$criteria->compare('Observation',$this->Observation,true);
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
	 * @return Certotherdetails the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
