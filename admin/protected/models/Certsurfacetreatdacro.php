<?php

/**
 * This is the model class for table "certsurfacetreatdacro".
 *
 * The followings are the available columns in table 'certsurfacetreatdacro':
 * @property string $Id
 * @property string $SeqNo
 * @property string $CoatMin
 * @property string $CoatMax
 * @property string $SaltMin
 * @property string $SaltMax
 * @property string $AdhesionReq
 * @property string $VisualReq
 * @property string $Observations
 * @property string $CoatRemark
 * @property string $SaltRemark
 * @property string $AdhesionRemark
 * @property string $VisualRemark
 * @property string $CreationDate
 * @property string $LastModified
 * @property string $ModifiedBy
 * @property string $CSID
 */
class Certsurfacetreatdacro extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'certsurfacetreatdacro';
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
			array('SeqNo, CoatMin, CoatMax, SaltMin, SaltMax, ModifiedBy, CSID', 'length', 'max'=>20),
			array('AdhesionReq, VisualReq', 'length', 'max'=>150),
			array('Observations', 'length', 'max'=>500),
			array('CoatRemark, SaltRemark, AdhesionRemark, VisualRemark', 'length', 'max'=>50),
			array('CreationDate, LastModified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, SeqNo, CoatMin, CoatMax, SaltMin, SaltMax, AdhesionReq, VisualReq, Observations, CoatRemark, SaltRemark, AdhesionRemark, VisualRemark, CreationDate, LastModified, ModifiedBy, CSID', 'safe', 'on'=>'search'),
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
			'CoatMin' => 'Coat Min',
			'CoatMax' => 'Coat Max',
			'SaltMin' => 'Salt Min',
			'SaltMax' => 'Salt Max',
			'AdhesionReq' => 'Adhesion Req',
			'VisualReq' => 'Visual Req',
			'Observations' => 'Observations',
			'CoatRemark' => 'Coat Remark',
			'SaltRemark' => 'Salt Remark',
			'AdhesionRemark' => 'Adhesion Remark',
			'VisualRemark' => 'Visual Remark',
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
		$criteria->compare('CoatMin',$this->CoatMin,true);
		$criteria->compare('CoatMax',$this->CoatMax,true);
		$criteria->compare('SaltMin',$this->SaltMin,true);
		$criteria->compare('SaltMax',$this->SaltMax,true);
		$criteria->compare('AdhesionReq',$this->AdhesionReq,true);
		$criteria->compare('VisualReq',$this->VisualReq,true);
		$criteria->compare('Observations',$this->Observations,true);
		$criteria->compare('CoatRemark',$this->CoatRemark,true);
		$criteria->compare('SaltRemark',$this->SaltRemark,true);
		$criteria->compare('AdhesionRemark',$this->AdhesionRemark,true);
		$criteria->compare('VisualRemark',$this->VisualRemark,true);
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
	 * @return Certsurfacetreatdacro the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
