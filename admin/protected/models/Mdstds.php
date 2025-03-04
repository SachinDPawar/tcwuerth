<?php

/**
 * This is the model class for table "mdstds".
 *
 * The followings are the available columns in table 'mdstds':
 * @property integer $Id
 * @property string $No
 * @property string $Type
 * @property string $Description
 * @property integer $Status
 * @property string $CreatedOn
 *
 * The followings are the available model relations:
 * @property Mdstdstests[] $mdstdstests
 * @property Mdstdsuploads[] $mdstdsuploads
 * @property Receiptir[] $receiptirs
 */
class Mdstds extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'mdstds';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('No, Type, CreatedOn', 'required'),
			array('Status', 'numerical', 'integerOnly'=>true),
			array('No, Description', 'length', 'max'=>150),
			array('Type', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, No, Type, Description, Status, CreatedOn', 'safe', 'on'=>'search'),
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
			'mdstdstests' => array(self::HAS_MANY, 'Mdstdstests', 'MTID'),
			'mdstdsuploads' => array(self::HAS_MANY, 'Mdstdsuploads', 'mdtdid'),
			'receiptirs' => array(self::HAS_MANY, 'Receiptir', 'MdsTdsId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'No' => 'No',
			'Type' => 'Type',
			'Description' => 'Description',
			'Status' => 'Status',
			'CreatedOn' => 'Created On',
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
		$criteria->compare('No',$this->No,true);
		$criteria->compare('Type',$this->Type,true);
		$criteria->compare('Description',$this->Description,true);
		$criteria->compare('Status',$this->Status);
		$criteria->compare('CreatedOn',$this->CreatedOn,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Mdstds the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
