<?php

/**
 * This is the model class for table "certbasicextra".
 *
 * The followings are the available columns in table 'certbasicextra':
 * @property string $Id
 * @property string $PoNo
 * @property string $PoDate
 * @property string $RFICoNo
 * @property string $RFIDate
 * @property string $RefStd
 * @property string $ItemCode
 * @property string $Material
 * @property string $PoLineItemNo
 * @property string $TestPlanNo
 * @property string $Project
 * @property string $ProjectNo
 * @property string $PosNo
 * @property string $SlNo
 * @property string $Qty
 * @property string $LastModified
 * @property integer $FormatNo
 * @property string $CBID
 *
 * The followings are the available model relations:
 * @property Certbasic $cB
 */
class Certbasicextra extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'certbasicextra';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('LastModified, CBID', 'required'),
			array('FormatNo', 'numerical', 'integerOnly'=>true),
			array('PoNo, RFICoNo, ItemCode, Material, PoLineItemNo, TestPlanNo, PosNo, SlNo, Qty', 'length', 'max'=>50),
			array('RefStd, Project', 'length', 'max'=>250),
			array('ProjectNo', 'length', 'max'=>100),
			array('CBID', 'length', 'max'=>20),
			array('PoDate, RFIDate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, PoNo, PoDate, RFICoNo, RFIDate, RefStd, ItemCode, Material, PoLineItemNo, TestPlanNo, Project, ProjectNo, PosNo, SlNo, Qty, LastModified, FormatNo, CBID', 'safe', 'on'=>'search'),
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
			'cB' => array(self::BELONGS_TO, 'Certbasic', 'CBID'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'Id' => 'ID',
			'PoNo' => 'Po No',
			'PoDate' => 'Po Date',
			'RFICoNo' => 'Rfico No',
			'RFIDate' => 'Rfidate',
			'RefStd' => 'Ref Std',
			'ItemCode' => 'Item Code',
			'Material' => 'Material',
			'PoLineItemNo' => 'Po Line Item No',
			'TestPlanNo' => 'Test Plan No',
			'Project' => 'Project',
			'ProjectNo' => 'Project No',
			'PosNo' => 'Pos No',
			'SlNo' => 'Sl No',
			'Qty' => 'Qty',
			'LastModified' => 'Last Modified',
			'FormatNo' => 'Format No',
			'CBID' => 'Cbid',
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
		$criteria->compare('PoNo',$this->PoNo,true);
		$criteria->compare('PoDate',$this->PoDate,true);
		$criteria->compare('RFICoNo',$this->RFICoNo,true);
		$criteria->compare('RFIDate',$this->RFIDate,true);
		$criteria->compare('RefStd',$this->RefStd,true);
		$criteria->compare('ItemCode',$this->ItemCode,true);
		$criteria->compare('Material',$this->Material,true);
		$criteria->compare('PoLineItemNo',$this->PoLineItemNo,true);
		$criteria->compare('TestPlanNo',$this->TestPlanNo,true);
		$criteria->compare('Project',$this->Project,true);
		$criteria->compare('ProjectNo',$this->ProjectNo,true);
		$criteria->compare('PosNo',$this->PosNo,true);
		$criteria->compare('SlNo',$this->SlNo,true);
		$criteria->compare('Qty',$this->Qty,true);
		$criteria->compare('LastModified',$this->LastModified,true);
		$criteria->compare('FormatNo',$this->FormatNo);
		$criteria->compare('CBID',$this->CBID,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Certbasicextra the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
