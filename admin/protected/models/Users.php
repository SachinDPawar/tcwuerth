<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $Id
 * @property string $FName
 * @property string $MName
 * @property string $LName
 * @property string $Username
 * @property string $Email
 * @property string $Password
 * @property string $ContactNo
 * @property string $Department
 * @property string $CreationDate
 * @property integer $Status
 * @property string $token
 * @property integer $CID
 *
 * The followings are the available model relations:
 * @property Certbasic[] $certbasics
 * @property Certbasic[] $certbasics1
 * @property Certbasic[] $certbasics2
 * @property Certbasic[] $certbasics3
 * @property Userapppermission[] $userapppermissions
 * @property Userinbranches[] $userinbranches
 * @property Userinroles[] $userinroles
 * @property Usernotifications[] $usernotifications
 * @property Usersignuploads[] $usersignuploads
 */
class Users extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Username, Email, Password, CreationDate', 'required'),
			array('Status, CID', 'numerical', 'integerOnly'=>true),
			array('FName, MName, LName', 'length', 'max'=>50),
			array('Username, Email', 'length', 'max'=>255),
			array('ContactNo', 'length', 'max'=>30),
			array('Department', 'length', 'max'=>250),
			array('token', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, FName, MName, LName, Username, Email, Password, ContactNo, Department, CreationDate, Status, token, CID', 'safe', 'on'=>'search'),
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
			'certbasics' => array(self::HAS_MANY, 'Certbasic', 'PreparedBy'),
			'certbasics1' => array(self::HAS_MANY, 'Certbasic', 'ModifiedBy'),
			'certbasics2' => array(self::HAS_MANY, 'Certbasic', 'ApprovedBy'),
			'certbasics3' => array(self::HAS_MANY, 'Certbasic', 'CheckedBy'),
			'userapppermissions' => array(self::HAS_MANY, 'Userapppermission', 'UserId'),
			'userinbranches' => array(self::HAS_MANY, 'Userinbranches', 'UserId'),
			'userinroles' => array(self::HAS_MANY, 'Userinroles', 'UserId'),
			'usernotifications' => array(self::HAS_MANY, 'Usernotifications', 'UserId'),
			'usersignuploads' => array(self::HAS_MANY, 'Usersignuploads', 'userid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'FName' => 'Fname',
			'MName' => 'Mname',
			'LName' => 'Lname',
			'Username' => 'Username',
			'Email' => 'Email',
			'Password' => 'Password',
			'ContactNo' => 'Contact No',
			'Department' => 'Department',
			'CreationDate' => 'Creation Date',
			'Status' => 'Status',
			'token' => 'Token',
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
		$criteria->compare('FName',$this->FName,true);
		$criteria->compare('MName',$this->MName,true);
		$criteria->compare('LName',$this->LName,true);
		$criteria->compare('Username',$this->Username,true);
		$criteria->compare('Email',$this->Email,true);
		$criteria->compare('Password',$this->Password,true);
		$criteria->compare('ContactNo',$this->ContactNo,true);
		$criteria->compare('Department',$this->Department,true);
		$criteria->compare('CreationDate',$this->CreationDate,true);
		$criteria->compare('Status',$this->Status);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('CID',$this->CID);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
