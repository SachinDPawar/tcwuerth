<?php

/**
 * This is the model class for table "torqtensbasic".
 *
 * The followings are the available columns in table 'torqtensbasic':
 * @property string $Id
 * @property string $BatchCode
 * @property string $Description
 * @property string $RirTestId
 * @property string $Remark
 * @property string $CreationDate
 *
 * The followings are the available model relations:
 * @property Tortensobservations[] $tortensobservations
 */
class Torqtensbasic extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'torqtensbasic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('BatchCode, RirTestId, CreationDate', 'required'),
			array('BatchCode', 'length', 'max'=>50),
			array('RirTestId', 'length', 'max'=>20),
			array('Remark', 'length', 'max'=>30),
			array('Description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, BatchCode, Description, RirTestId, Remark, CreationDate', 'safe', 'on'=>'search'),
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
			'tortensobservations' => array(self::HAS_MANY, 'Tortensobservations', 'TTBId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'BatchCode' => 'Batch Code',
			'Description' => 'Description',
			'RirTestId' => 'Rir Test',
			'Remark' => 'Remark',
			'CreationDate' => 'Creation Date',
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
		$criteria->compare('BatchCode',$this->BatchCode,true);
		$criteria->compare('Description',$this->Description,true);
		$criteria->compare('RirTestId',$this->RirTestId,true);
		$criteria->compare('Remark',$this->Remark,true);
		$criteria->compare('CreationDate',$this->CreationDate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Torqtensbasic the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
