<?php

/**
 * This is the model class for table "certrirs".
 *
 * The followings are the available columns in table 'certrirs':
 * @property string $Id
 * @property string $CertBasicId
 * @property string $RirTestId
 * @property string $RirId
 *
 * The followings are the available model relations:
 * @property Certbasic $certBasic
 * @property Receiptir $rir
 */
class Certrirs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'certrirs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('CertBasicId, RirTestId, RirId', 'required'),
			array('CertBasicId, RirTestId, RirId', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, CertBasicId, RirTestId, RirId', 'safe', 'on'=>'search'),
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
			'certBasic' => array(self::BELONGS_TO, 'Certbasic', 'CertBasicId'),
			'rir' => array(self::BELONGS_TO, 'Receiptir', 'RirId'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'CertBasicId' => 'Cert Basic',
			'RirTestId' => 'Rir Test',
			'RirId' => 'Rir',
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
		$criteria->compare('CertBasicId',$this->CertBasicId,true);
		$criteria->compare('RirTestId',$this->RirTestId,true);
		$criteria->compare('RirId',$this->RirId,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Certrirs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
