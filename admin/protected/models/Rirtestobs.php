<?php

/**
 * This is the model class for table "rirtestobs".
 *
 * The followings are the available columns in table 'rirtestobs':
 * @property string $Id
 * @property string $RTID
 * @property integer $TPID
 * @property integer $TMID
 * @property string $Min
 * @property string $Max
 * @property string $Value
 * @property string $DL
 * @property string $Conformity
 *
 * The followings are the available model relations:
 * @property Rirtestdetail $rT
 * @property Testmethods $tM
 * @property Testobsparams $tP
 * @property Rirtestobsvalue[] $rirtestobsvalues
 */
class Rirtestobs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rirtestobs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('RTID, TPID', 'required'),
			array('TPID, TMID', 'numerical', 'integerOnly'=>true),
			array('RTID, DL, Conformity', 'length', 'max'=>20),
			array('Min, Max', 'length', 'max'=>30),
			array('Value', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, RTID, TPID, TMID, Min, Max, Value, DL, Conformity', 'safe', 'on'=>'search'),
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
			'tM' => array(self::BELONGS_TO, 'Testmethods', 'TMID'),
			'tP' => array(self::BELONGS_TO, 'Testobsparams', 'TPID'),
			'rirtestobsvalues' => array(self::HAS_MANY, 'Rirtestobsvalue', 'RTOBID'),
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
			'TPID' => 'Tpid',
			'TMID' => 'Tmid',
			'Min' => 'Min',
			'Max' => 'Max',
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
		$criteria->compare('RTID',$this->RTID,true);
		$criteria->compare('TPID',$this->TPID);
		$criteria->compare('TMID',$this->TMID);
		$criteria->compare('Min',$this->Min,true);
		$criteria->compare('Max',$this->Max,true);
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
	 * @return Rirtestobs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	// public function behaviors()
	// {
		// return array(
			// 'LoggableBehavior'=>
				// 'application.modules.auditTrail.behaviors.LoggableBehavior',
		// );
	// }
}
