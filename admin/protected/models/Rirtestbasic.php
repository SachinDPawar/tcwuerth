<?php

/**
 * This is the model class for table "rirtestbasic".
 *
 * The followings are the available columns in table 'rirtestbasic':
 * @property integer $Id
 * @property string $RTID
 * @property integer $TBPID
 * @property string $BValue
 * @property string $CreatedOn
 *
 * The followings are the available model relations:
 * @property Rirtestdetail $rT
 * @property Testbasicparams $tBP
 */
class Rirtestbasic extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rirtestbasic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('RTID', 'required'),
			array('TBPID', 'numerical', 'integerOnly'=>true),
			array('RTID', 'length', 'max'=>20),
			array('BValue', 'length', 'max'=>250),
			array('CreatedOn', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, RTID, TBPID, BValue, CreatedOn', 'safe', 'on'=>'search'),
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
			'rT' => array(self::BELONGS_TO, 'Rirtestdetail', 'RTID'),
			'tBP' => array(self::BELONGS_TO, 'Testbasicparams', 'TBPID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'RTID' => 'Rtid',
			'TBPID' => 'Tbpid',
			'BValue' => 'Bvalue',
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
		$criteria->compare('RTID',$this->RTID,true);
		$criteria->compare('TBPID',$this->TBPID);
		$criteria->compare('BValue',$this->BValue,true);
		$criteria->compare('CreatedOn',$this->CreatedOn,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Rirtestbasic the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
