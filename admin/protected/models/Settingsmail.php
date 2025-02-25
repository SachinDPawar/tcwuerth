<?php

/**
 * This is the model class for table "settingsmail".
 *
 * The followings are the available columns in table 'settingsmail':
 * @property integer $Id
 * @property string $Email
 * @property string $Password
 * @property string $Server
 * @property string $Port
 * @property string $Encrypt
 * @property string $Type
 * @property integer $ISMAIL
 * @property integer $LIVE
 * @property string $DemoIds
 * @property integer $CID
 */
class Settingsmail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'settingsmail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Email, Password, Server, Port, Encrypt', 'required'),
			array('ISMAIL, LIVE, CID', 'numerical', 'integerOnly'=>true),
			array('Email, Password, Server', 'length', 'max'=>250),
			array('Port, Encrypt, Type', 'length', 'max'=>20),
			array('DemoIds', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, Email, Password, Server, Port, Encrypt, Type, ISMAIL, LIVE, DemoIds, CID', 'safe', 'on'=>'search'),
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
			'Email' => 'Email',
			'Password' => 'Password',
			'Server' => 'Server',
			'Port' => 'Port',
			'Encrypt' => 'Encrypt',
			'Type' => 'Type',
			'ISMAIL' => 'Ismail',
			'LIVE' => 'Live',
			'DemoIds' => 'Demo Ids',
			'CID' => 'Cid',
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
		$criteria->compare('Email',$this->Email,true);
		$criteria->compare('Password',$this->Password,true);
		$criteria->compare('Server',$this->Server,true);
		$criteria->compare('Port',$this->Port,true);
		$criteria->compare('Encrypt',$this->Encrypt,true);
		$criteria->compare('Type',$this->Type,true);
		$criteria->compare('ISMAIL',$this->ISMAIL);
		$criteria->compare('LIVE',$this->LIVE);
		$criteria->compare('DemoIds',$this->DemoIds,true);
		$criteria->compare('CID',$this->CID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Settingsmail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
