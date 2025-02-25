<?php

/**
 * This is the model class for table "othobs".
 *
 * The followings are the available columns in table 'othobs':
 * @property string $Id
 * @property string $RirTestId
 * @property integer $PId
 * @property integer $TMId
 * @property string $Value
 * @property string $DL
 * @property string $Conformity
 *
 * The followings are the available model relations:
 * @property Testparams $p
 * @property Testmethods $tM
 * @property Rirtestdetail $rirTest
 */
class Othobs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'othobs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('RirTestId, PId', 'required'),
			array('PId, TMId', 'numerical', 'integerOnly'=>true),
			array('RirTestId, DL, Conformity', 'length', 'max'=>20),
			array('Value', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, RirTestId, PId, TMId, Value, DL, Conformity', 'safe', 'on'=>'search'),
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
			'p' => array(self::BELONGS_TO, 'Testparams', 'PId'),
			'tM' => array(self::BELONGS_TO, 'Testmethods', 'TMId'),
			'rirTest' => array(self::BELONGS_TO, 'Rirtestdetail', 'RirTestId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'RirTestId' => 'Rir Test',
			'PId' => 'Pid',
			'TMId' => 'Tmid',
			'Value' => 'Value',
			'DL' => 'Dl',
			'Conformity' => 'Conformity',
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
		$criteria->compare('RirTestId',$this->RirTestId,true);
		$criteria->compare('PId',$this->PId);
		$criteria->compare('TMId',$this->TMId);
		$criteria->compare('Value',$this->Value,true);
		$criteria->compare('DL',$this->DL,true);
		$criteria->compare('Conformity',$this->Conformity,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Othobs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
