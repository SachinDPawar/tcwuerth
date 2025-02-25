<?php

/**
 * This is the model class for table "certsurfacetreatplat".
 *
 * The followings are the available columns in table 'certsurfacetreatplat':
 * @property string $Id
 * @property string $SeqNo
 * @property string $Parameter
 * @property string $ReqMin
 * @property string $ReqMax
 * @property string $Obs1
 * @property string $Obs2
 * @property string $Obs3
 * @property string $Obs4
 * @property string $Obs5
 * @property string $Obs6
 * @property string $Obs7
 * @property string $Obs8
 * @property string $Obs9
 * @property string $Obs10
 * @property string $Remark
 * @property string $CreationDate
 * @property string $LastModified
 * @property string $ModifiedBy
 * @property string $CSID
 */
class Certsurfacetreatplat extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'certsurfacetreatplat';
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
			array('SeqNo, Obs6, Obs7, Obs8, Obs9, Obs10, ModifiedBy, CSID', 'length', 'max'=>20),
			array('ReqMin, ReqMax, Obs1, Obs2, Obs3, Obs4, Obs5', 'length', 'max'=>50),
			array('Remark', 'length', 'max'=>150),
			array('Parameter, CreationDate, LastModified', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('Id, SeqNo, Parameter, ReqMin, ReqMax, Obs1, Obs2, Obs3, Obs4, Obs5, Obs6, Obs7, Obs8, Obs9, Obs10, Remark, CreationDate, LastModified, ModifiedBy, CSID', 'safe', 'on'=>'search'),
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
			'Parameter' => 'Parameter',
			'ReqMin' => 'Req Min',
			'ReqMax' => 'Req Max',
			'Obs1' => 'Obs1',
			'Obs2' => 'Obs2',
			'Obs3' => 'Obs3',
			'Obs4' => 'Obs4',
			'Obs5' => 'Obs5',
			'Obs6' => 'Obs6',
			'Obs7' => 'Obs7',
			'Obs8' => 'Obs8',
			'Obs9' => 'Obs9',
			'Obs10' => 'Obs10',
			'Remark' => 'Remark',
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
		$criteria->compare('Parameter',$this->Parameter,true);
		$criteria->compare('ReqMin',$this->ReqMin,true);
		$criteria->compare('ReqMax',$this->ReqMax,true);
		$criteria->compare('Obs1',$this->Obs1,true);
		$criteria->compare('Obs2',$this->Obs2,true);
		$criteria->compare('Obs3',$this->Obs3,true);
		$criteria->compare('Obs4',$this->Obs4,true);
		$criteria->compare('Obs5',$this->Obs5,true);
		$criteria->compare('Obs6',$this->Obs6,true);
		$criteria->compare('Obs7',$this->Obs7,true);
		$criteria->compare('Obs8',$this->Obs8,true);
		$criteria->compare('Obs9',$this->Obs9,true);
		$criteria->compare('Obs10',$this->Obs10,true);
		$criteria->compare('Remark',$this->Remark,true);
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
	 * @return Certsurfacetreatplat the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
