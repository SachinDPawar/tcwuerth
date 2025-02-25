<?php

class NewapiController extends Controller
{
    // Members
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers 
     */
    Const APPLICATION_ID = 'ASCCPE';
 
    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';
    /**
     * @return array action filters
     */
    public function filters()
    {
            return array();
    }
 
 
 
    // Actions
    public function actionList()
    {
				
			// Get the respective model instance
			switch($_GET['model'])
			{
				
				case 'winclratingdata':
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4
	 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7 OR TestId=:id8 OR TestId=:id9  OR TestId=:idw',
										 'params'=>array(':id1'=>"59",':id2'=>"60",':id3'=>"61",':id4'=>"166",':id5'=>"167",':id6'=>"168",
											':id7'=>"169",':id8'=>"170",':id9'=>"171",':idw'=>"62"),));
						if(!empty($rtds))
						{							
							$totalitems=count($rtds);				 
										 
						if(isset($_GET['pl']))
						{
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4
	 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7 OR TestId=:id8 OR TestId=:id9  OR TestId=:idw',
							  'order' => 'Id desc',
								'limit' => $_GET['pl'],
								'offset' => ($_GET['pn']-1)*$_GET['pl'],
								'params'=>array(':id1'=>"59",':id2'=>"60",':id3'=>"61",':id4'=>"166",':id5'=>"167",':id6'=>"168",
											':id7'=>"169",':id8'=>"170",':id9'=>"171",':idw'=>"62"),));
										 
						}		

										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							
							
						
										 
								if(empty($d->winclratbasics))
						{
							$obbasic=(object)array();	
						}
						else
						{
							$ob=$d->winclratbasics[0];
							
							$observations=$ob->winclratobs;
							$obbasic=(object)array('Id'=>$ob->Id,'RirTestId'=>$ob->RirTestId,'Sample'=>$ob->Sample,'Orientation'=>$ob->Orientation,
							'Magnification'=>$ob->Magnification,'TestReportNo'=>$ob->TestReportNo,'PolSampSize'=>$ob->PolSampSize,
							'PlantLoc'=>$ob->PlantLoc,'TestTemp'=>$ob->TestTemp,'Observations'=>$observations,'ObsRemark'=>$ob->ObsRemark,
							'AvgThinA'=>$ob->AvgThinA,'AvgThickA'=>$ob->AvgThickA,'AvgThinB'=>$ob->AvgThinB,'AvgThickB'=>$ob->AvgThickB,
							'AvgThinC'=>$ob->AvgThinC,'AvgThickC'=>$ob->AvgThickC,'AvgThinD'=>$ob->AvgThinD,'AvgThickD'=>$ob->AvgThickD);					
						}			 
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
							$img="";		
							if(!empty($d->testuploads))
							{
								$img=$d->testuploads[0];
							}
															$testno=1;
							$testno=substr($d->TestName, -1);
							if($testno !='1' && $testno !='2' && $testno !='3' && $testno !='4' && $testno !='5' && $testno !='6' && $testno !='7' && $testno !='8' && $testno !='9' && $testno !='W' )
							{
								$testno=1;
							}				
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'obbasic'=>$obbasic,'Note'=>$d->Note,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'Imgs'=>$img,'TestNo'=>$testno,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,'ULRNo'=>$d->ULRNo,'ApprovedDate'=>$d->ApprovedDate,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>$d->RevDate);

							}
							
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));
						}
						else
						{
							 $allrirs=array();
							 $totalitems=0;
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));
						}
							break;
							
				case 'kinclratingdata':
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4
	 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7 OR TestId=:id8 OR TestId=:id9  OR TestId=:idw',
										 'params'=>array(':id1'=>"55",':id2'=>"56",':id3'=>"57",':id4'=>"160",':id5'=>"161",':id6'=>"162",
											':id7'=>"163",':id8'=>"164",':id9'=>"165",':idw'=>"58"),));
						if(!empty($rtds))
						{							
							$totalitems=count($rtds);				 
										 
						if(isset($_GET['pl']))
						{
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4
	 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7 OR TestId=:id8 OR TestId=:id9  OR TestId=:idw',
							  'order' => 'Id desc',
								'limit' => $_GET['pl'],
								'offset' => ($_GET['pn']-1)*$_GET['pl'],
								'params'=>array(':id1'=>"55",':id2'=>"56",':id3'=>"57",':id4'=>"160",':id5'=>"161",':id6'=>"162",
											':id7'=>"163",':id8'=>"164",':id9'=>"165",':idw'=>"58"),));
										 
						}		

										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							
							
						
										 
								if(empty($d->kinclratbasics))
						{
							$obbasic=(object)array();	
						}
						else
						{
							
							$observations=array();
							$b=$d->kinclratbasics[0];
							if(!empty($b->kinclratobsbasics))
							{
								foreach($b->kinclratobsbasics as $obb)
								{
									$obs=$obb->kinclratobsobs;
									$observations[]=(object)array('Id'=>$obb->Id,'KIRBId'=>$obb->KIRBId,'SpecNo'=>$obb->SpecNo,
									'AreaPol'=>$obb->AreaPol,'obs'=>$obs);
								}
								//$observations=$b->kinclratobs;	
							}
							

							$obbasic=(object)array('Id'=>$b->Id,'RirTestId'=>$b->RirTestId,'Sample'=>$b->Sample,'Orientation'=>$b->Orientation,
							'Etchant'=>$b->Etchant,'Magnification'=>$b->Magnification,'TestReportNo'=>$b->TestReportNo,'PolSampSize'=>$b->PolSampSize,
							'PlantLoc'=>$b->PlantLoc,'TestTemp'=>$b->TestTemp,'Total'=>$b->Total,'SSTotalS'=>$b->SSTotalS,'SSTotalO'=>$b->SSTotalO,
							'TotalK3S'=>$b->TotalK3S,'TotalK3O'=>$b->TotalK3O,'OverAllTotK3'=>$b->OverAllTotK3,'ObsRemark'=>$b->ObsRemark,
							'Observations'=>$observations);	
					
						}			 
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
							
							$img="";							
							if(!empty($d->testuploads))
							{
								$img=$d->testuploads[0];
							}
															$testno=1;
							$testno=substr($d->TestName, -1);
							if($testno !='1' && $testno !='2' && $testno !='3' && $testno !='4' && $testno !='5' && $testno !='6' && $testno !='7' && $testno !='8' && $testno !='9'&& $testno !='W' )
							{
								$testno=1;
							}				
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'obbasic'=>$obbasic,'Note'=>$d->Note,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'Imgs'=>$img,'TestNo'=>$testno,'ULRNo'=>$d->ULRNo,'ApprovedDate'=>$d->ApprovedDate,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>$d->RevDate);

							}
							
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));
						}
						else
						{
							 $allrirs=array();
							 $totalitems=0;
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));
						}
							break;

							
				case 'microcoatdata':
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4
	 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7 OR TestId=:id8 OR TestId=:id9  OR TestId=:idw',
										 'params'=>array(':id1'=>"43",':id2'=>"44",':id3'=>"45",':id4'=>"178",':id5'=>"179",':id6'=>"180",
											':id7'=>"181",':id8'=>"182",':id9'=>"183",':idw'=>"46"),));
						if(!empty($rtds))
						{							
							$totalitems=count($rtds);				 
										 
						if(isset($_GET['pl']))
						{
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4
	 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7 OR TestId=:id8 OR TestId=:id9  OR TestId=:idw',
							  'order' => 'Id desc',
								'limit' => $_GET['pl'],
								'offset' => ($_GET['pn']-1)*$_GET['pl'],
								'params'=>array(':id1'=>"43",':id2'=>"44",':id3'=>"45",':id4'=>"178",':id5'=>"179",':id6'=>"180",
											':id7'=>"181",':id8'=>"182",':id9'=>"183",':idw'=>"46"),));
										 
						}		

										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							
							
						
										 
								if(empty($d->microcoatbasics))
						{
							$obbasic=(object)array();	
						}
						else
						{
							
							$obbasic=$d->microcoatbasics[0];							
						}			 
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
							
									$img="";
							if(!empty($d->testuploads))
							{
								$img=$d->testuploads[0];
							}
									
															$testno=1;
							$testno=substr($d->TestName, -1);
							if($testno !='1' && $testno !='2' && $testno !='3' && $testno !='4' && $testno !='5' && $testno !='6' && $testno !='7' && $testno !='8' && $testno !='9'&& $testno !='W' )
							{
								$testno=1;
							}							
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'obbasic'=>$obbasic,'Note'=>$d->Note,'ULRNo'=>$d->ULRNo,'ApprovedDate'=>$d->ApprovedDate,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'Imgs'=>$img,'TestNo'=>$testno,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>$d->RevDate);

							}
							
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));
						}
						else
						{
							 $allrirs=array();
							 $totalitems=0;
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));
						}
							break;
				
				case 'microcasedepthdata':
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4
	 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7 OR TestId=:id8 OR TestId=:id9  OR TestId=:idw',
										 'params'=>array(':id1'=>"47",':id2'=>"48",':id3'=>"49",':id4'=>"172",':id5'=>"173",':id6'=>"174",
											':id7'=>"175",':id8'=>"176",':id9'=>"177",':idw'=>"50"),));
						if(!empty($rtds))
						{							
							$totalitems=count($rtds);				 
										 
						if(isset($_GET['pl']))
						{
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4
	 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7 OR TestId=:id8 OR TestId=:id9  OR TestId=:idw',
							  'order' => 'Id desc',
								'limit' => $_GET['pl'],
								'offset' => ($_GET['pn']-1)*$_GET['pl'],
								'params'=>array(':id1'=>"47",':id2'=>"48",':id3'=>"49",':id4'=>"172",':id5'=>"173",':id6'=>"174",
											':id7'=>"175",':id8'=>"176",':id9'=>"177",':idw'=>"50"),));
										 
						}		

										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							
							
						
										 
								if(empty($d->microcasebasics))
						{
							$obbasic=(object)array();	
						}
						else
						{
							
							$obbasic=$d->microcasebasics[0];							
						}			 
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}	

							$img="";							
									
							if(!empty($d->testuploads))
							{
								$img=$d->testuploads[0];
							}
															$testno=1;
							$testno=substr($d->TestName, -1);
							if($testno !='1' && $testno !='2' && $testno !='3' && $testno !='4' && $testno !='5' && $testno !='6' && $testno !='7' && $testno !='8' && $testno !='9'&& $testno !='W' )
							{
								$testno=1;
							}				
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'obbasic'=>$obbasic,'Note'=>$d->Note,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'Imgs'=>$img,'TestNo'=>$testno,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,'ULRNo'=>$d->ULRNo,'ApprovedDate'=>$d->ApprovedDate,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>$d->RevDate);

							}
							
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));
						}
						else
						{
							 $allrirs=array();
							 $totalitems=0;
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));
						}
							break;
							
				case 'microdecarbdata':
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4
	 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7 OR TestId=:id8 OR TestId=:id9  OR TestId=:idw',
										 'params'=>array(':id1'=>"51",':id2'=>"52",':id3'=>"53",':id4'=>"190",':id5'=>"191",':id6'=>"192",
											':id7'=>"193",':id8'=>"194",':id9'=>"195",':idw'=>"54"),));
						if(!empty($rtds))
						{							
							$totalitems=count($rtds);				 
										 
						if(isset($_GET['pl']))
						{
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4
	 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7 OR TestId=:id8 OR TestId=:id9  OR TestId=:idw',
							  'order' => 'Id desc',
								'limit' => $_GET['pl'],
								'offset' => ($_GET['pn']-1)*$_GET['pl'],
								'params'=>array(':id1'=>"51",':id2'=>"52",':id3'=>"53",':id4'=>"190",':id5'=>"191",':id6'=>"192",
											':id7'=>"193",':id8'=>"194",':id9'=>"195",':idw'=>"54"),));
										 
						}		

										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							
							
						
										 
								if(empty($d->microdecarbbasics))
						{
							$obbasic=(object)array();	
						}
						else
						{
							
							$obbasic=$d->microdecarbbasics[0];							
						}			 
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
								
										$img="";
							if(!empty($d->testuploads))
							{
								$img=$d->testuploads[0];
							}
																$testno=1;
							$testno=substr($d->TestName, -1);
							if($testno !='1' && $testno !='2' && $testno !='3' && $testno !='4' && $testno !='5' && $testno !='6' && $testno !='7' && $testno !='8' && $testno !='9'&& $testno !='W' )
							{
								$testno=1;
							}			
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'obbasic'=>$obbasic,'Note'=>$d->Note,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'Imgs'=>$img,'TestNo'=>$testno,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,'ULRNo'=>$d->ULRNo,'ApprovedDate'=>$d->ApprovedDate,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>$d->RevDate);

							}
							
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));
						}
						else
						{
							 $allrirs=array();
							 $totalitems=0;
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));
						}
							break;
				
				case 'microstructdata':
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4
	 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7 OR TestId=:id8 OR TestId=:id9  OR TestId=:idw',
										 'params'=>array(':id1'=>"35",':id2'=>"36",':id3'=>"37",':id4'=>"154",':id5'=>"155",':id6'=>"156",
											':id7'=>"157",':id8'=>"158",':id9'=>"159",':idw'=>"38"),));
						if(!empty($rtds))
						{							
							$totalitems=count($rtds);				 
										 
						if(isset($_GET['pl']))
						{
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4
	 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7 OR TestId=:id8 OR TestId=:id9  OR TestId=:idw',
							  'order' => 'Id desc',
								'limit' => $_GET['pl'],
								'offset' => ($_GET['pn']-1)*$_GET['pl'],
								'params'=>array(':id1'=>"35",':id2'=>"36",':id3'=>"37",':id4'=>"154",':id5'=>"155",':id6'=>"156",
											':id7'=>"157",':id8'=>"158",':id9'=>"159",':idw'=>"38"),));
										 
						}		

										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							
						
										 
								if(empty($d->microstructbasics))
						{
							$obbasic=(object)array();	
						}
						else
						{
							
							$obbasic=$d->microstructbasics[0];							
						}			 
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
							
							$img="";
							if(!empty($d->testuploads))
							{
								$img=$d->testuploads[0];
							}
							
														$testno=1;
							$testno=substr($d->TestName, -1);
							if($testno !='1' && $testno !='2' && $testno !='3' && $testno !='4' && $testno !='5' && $testno !='6' && $testno !='7' && $testno !='8' && $testno !='9'&& $testno !='W' )
							{
								$testno=1;
							}			
									
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'obbasic'=>$obbasic,'Note'=>$d->Note,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'Imgs'=>$img,'TestNo'=>$testno,'ULRNo'=>$d->ULRNo,'ApprovedDate'=>$d->ApprovedDate,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>$d->RevDate);

							}
							
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));
						}
						else
						{
							 $allrirs=array();
							 $totalitems=0;
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));
						}
							break;
							
				case 'grainsizedata':
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4
	 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7 OR TestId=:id8 OR TestId=:id9  OR TestId=:idw',
										 'params'=>array(':id1'=>"39",':id2'=>"40",':id3'=>"41",':id4'=>"148",':id5'=>"149",':id6'=>"150",
											':id7'=>"151",':id8'=>"152",':id9'=>"153",':idw'=>"42"),));
						if(!empty($rtds))
						{				 
							$totalitems=count($rtds);				 
										 
						if(isset($_GET['pl']))
						{
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4
	 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7 OR TestId=:id8 OR TestId=:id9  OR TestId=:idw',
							  'order' => 'Id desc',
								'limit' => $_GET['pl'],
								'offset' => ($_GET['pn']-1)*$_GET['pl'],
								'params'=>array(':id1'=>"39",':id2'=>"40",':id3'=>"41",':id4'=>"148",':id5'=>"149",':id6'=>"150",
											':id7'=>"151",':id8'=>"152",':id9'=>"153",':idw'=>"42"),));
										 
						}		

										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							
							
						
										 
								if(empty($d->grainsizebasics))
						{
							$obbasic=(object)array();	
						}
						else
						{
							
							$obbasic=$d->grainsizebasics[0];
							
						}			 
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
							$img="";		
							if(!empty($d->testuploads))
							{
								$img=$d->testuploads[0];
							}
								
							$testno=1;
							$testno=substr($d->TestName, -1);
							if($testno !='1' && $testno !='2' && $testno !='3' && $testno !='4' && $testno !='5' && $testno !='6' && $testno !='7' && $testno !='8' && $testno !='9'&& $testno !='W' )
							{
								$testno=1;
							}											
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'obbasic'=>$obbasic,'Note'=>$d->Note,'ULRNo'=>$d->ULRNo,'ApprovedDate'=>$d->ApprovedDate,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'Imgs'=>$img,'TestNo'=>$testno,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>$d->RevDate);

							}
							
							$allobsunit=array();
					$allobsunit[]=(object)array('Id'=>'1','Unit'=>"ASTM grain size no.");
					$allobsunit[]=(object)array('Id'=>'2','Unit'=>"ISO grain size no.");
							
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems,'allobsunit'=>$allobsunit);
							$this->_sendResponse(200, CJSON::encode($data));
						}
						else
						{
							 $allrirs=array();
							 $totalitems=0;
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));
						}
							
							break;

				
			
				case 'threaddata':
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4
	 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7 OR TestId=:id8 OR TestId=:id9  OR TestId=:idw',
										 'params'=>array(':id1'=>"63",':id2'=>"64",':id3'=>"65",':id4'=>"184",':id5'=>"185",':id6'=>"186",
											':id7'=>"187",':id8'=>"188",':id9'=>"189",':idw'=>"66"),));
						if(!empty($rtds))
						{				 
							$totalitems=count($rtds);				 
										 
						if(isset($_GET['pl']))
						{
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4
	 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7 OR TestId=:id8 OR TestId=:id9  OR TestId=:idw',
							  'order' => 'Id desc',
								'limit' => $_GET['pl'],
								'offset' => ($_GET['pn']-1)*$_GET['pl'],
								'params'=>array(':id1'=>"63",':id2'=>"64",':id3'=>"65",':id4'=>"184",':id5'=>"185",':id6'=>"186",
											':id7'=>"187",':id8'=>"188",':id9'=>"189",':idw'=>"66"),));
										 
						}		

										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							
						
						
								if(empty($d->threadlapbasics))
						{
							$obbasic=(object)array();	
						}
						else
						{
							$ob=$d->threadlapbasics[0];
							$obbasic=(object)array('Id'=>$ob->Id,'RirTestId'=>$ob->RirTestId,'Sample'=>$ob->Sample,'Equipment'=>$ob->Equipment,
							'Orientation'=>$ob->Orientation,'Etchant'=>$ob->Etchant,'Magnification'=>$ob->Magnification,'PolSampSize'=>$ob->PolSampSize,
							'PlantLoc'=>$ob->PlantLoc,'Thobs'=>$ob->threadlapobs,'ObsRemark'=>$ob->ObsRemark);
							
						}		

//	$this->_sendResponse(401, CJSON::encode($d->threadlapbasics));			 						
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
							$img="";		
							if(!empty($d->testuploads))
							{
								$img=$d->testuploads;
							}
															$testno=1;
							$testno=substr($d->TestName, -1);
							if($testno !='1' && $testno !='2' && $testno !='3' && $testno !='4' && $testno !='5' && $testno !='6' && $testno !='7' && $testno !='8' && $testno !='9'&& $testno !='W' )
							{
								$testno=1;
							}				
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'obbasic'=>$obbasic,'Note'=>$d->Note,'ULRNo'=>$d->ULRNo,'ApprovedDate'=>$d->ApprovedDate,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'Imgs'=>$img,'TestNo'=>$testno,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>$d->RevDate);

							}
							
							
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));
						}
						else
						{
							 $allrirs=array();
							 $totalitems=0;
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));
						}
							
							break;

				
				
				
				
				default:
					// Model not implemented error
					$this->_sendResponse(501, sprintf(
						'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
						$_GET['model']) );
					Yii::app()->end();
			}
			// Did we get some results?
			if(empty($models)) {
				// No
				$this->_sendResponse(200, 
						CJSON::encode($models) );
			} else {
				// Prepare response
				$rows = array();
				foreach($models as $model)
					$rows[] = $model->attributes;
				// Send the response
				$this->_sendResponse(200, CJSON::encode($rows));
			}
    }
	
	
    public function actionView()
    {
				
			// Check if id was submitted via GET
			if(!isset($_GET['id']))
				$this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing' );
		 
			switch($_GET['model'])
			{
				// Find respective model 
			case 'winclratingeditdata':
							
						$d=Rirtestdetail::model()->findByPk($_GET['id']);				
				if(!empty($d))
				{					
						$sds=array();
							

						
							
						
						$observations=array();
						
						if(empty($d->winclratbasics))
						{
							$obbasic=(object)array('PlantLoc'=>"Pirangut",'Observations'=>$observations);	
						}
						else
						{
							$ob=$d->winclratbasics[0];
							
							$observations=$ob->winclratobs;
							$obbasic=(object)array('Id'=>$ob->Id,'RirTestId'=>$ob->RirTestId,'Sample'=>$ob->Sample,'Orientation'=>$ob->Orientation,
							'Magnification'=>$ob->Magnification,'TestReportNo'=>$ob->TestReportNo,'PolSampSize'=>$ob->PolSampSize,
							'PlantLoc'=>$ob->PlantLoc,'TestTemp'=>$ob->TestTemp,'Observations'=>$observations,'ObsRemark'=>$ob->ObsRemark,
							'AvgThinA'=>$ob->AvgThinA,'AvgThickA'=>$ob->AvgThickA,'AvgThinB'=>$ob->AvgThinB,'AvgThickB'=>$ob->AvgThickB,
							'AvgThinC'=>$ob->AvgThinC,'AvgThickC'=>$ob->AvgThickC,'AvgThinD'=>$ob->AvgThinD,'AvgThickD'=>$ob->AvgThickD);							
						}		
							
							$allmagni=Magnification::model()->findAll();
							
								$basic=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'Note'=>$d->Note,'TestName'=>$d->TestName,'TestId'=>$d->TestId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestDate'=>$d->TestDate,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,						
							'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 
								   
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'obbasic'=>$obbasic,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method);
						
						$methods=Testmethods::model()->findAll();
					$alltestmethods=array();
					foreach($methods as $l)
					{
						if($l->test->Keyword==='IRW')
						{
							if($l->TypeId !=0)
							{
								$type=Testtypes::model()->findByPk($l->TypeId);
									$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'TypeId'=>$l->TypeId,'Type'=>$type->Type,'Keyword'=>$l->test->Keyword);
							}
							else
							{
								$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'Keyword'=>$l->test->Keyword);
							}
						}
					}
					$allobsunit=array();
					$allobsunit[]=(object)array('Id'=>'1','Unit'=>"ASTM grain size no.");
					$allobsunit[]=(object)array('Id'=>'2','Unit'=>"ISO grain size no.");
								
					$model=(object)array('basic'=>$basic,'magnifications'=>$allmagni,'testmethods'=>$alltestmethods,'allobsunit'=>$allobsunit);		
				}		
						break;	

			case 'kinclratingeditdata':
							
						$d=Rirtestdetail::model()->findByPk($_GET['id']);				
				if(!empty($d))
				{					
						$sds=array();
							

						
							
						$observations=array();
							
								if(empty($d->kinclratbasics))
						{
							$obbasic=(object)array('PlantLoc'=>"Pirangut",'Observations'=>$observations);	
						}
						else
						{
							
							
							$b=$d->kinclratbasics[0];
							if(!empty($b->kinclratobsbasics))
							{
								foreach($b->kinclratobsbasics as $obb)
								{
									$obs=$obb->kinclratobsobs;
									$observations[]=(object)array('Id'=>$obb->Id,'KIRBId'=>$obb->KIRBId,'SpecNo'=>$obb->SpecNo,
									'AreaPol'=>$obb->AreaPol,'obs'=>$obs);
								}
								//$observations=$b->kinclratobs;	
							}
							

							$obbasic=(object)array('Id'=>$b->Id,'RirTestId'=>$b->RirTestId,'Sample'=>$b->Sample,'Orientation'=>$b->Orientation,
							'Etchant'=>$b->Etchant,'Magnification'=>$b->Magnification,'TestReportNo'=>$b->TestReportNo,'PolSampSize'=>$b->PolSampSize,
							'PlantLoc'=>$b->PlantLoc,'TestTemp'=>$b->TestTemp,'Total'=>$b->Total,'SSTotalS'=>$b->SSTotalS,'SSTotalO'=>$b->SSTotalO,
							'TotalK3S'=>$b->TotalK3S,'TotalK3O'=>$b->TotalK3O,'OverAllTotK3'=>$b->OverAllTotK3,'ObsRemark'=>$b->ObsRemark,
							'Observations'=>$observations);	
						}		
							
							$allmagni=Magnification::model()->findAll();
							
								$basic=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'Note'=>$d->Note,'TestName'=>$d->TestName,'TestId'=>$d->TestId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestDate'=>$d->TestDate,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,						
							'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 
								   
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'obbasic'=>$obbasic,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method);
						
						$methods=Testmethods::model()->findAll();
					$alltestmethods=array();
					foreach($methods as $l)
					{
						if($l->test->Keyword==='IRK')
						{
							if($l->TypeId !=0)
							{
								$type=Testtypes::model()->findByPk($l->TypeId);
									$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'TypeId'=>$l->TypeId,'Type'=>$type->Type,'Keyword'=>$l->test->Keyword);
							}
							else
							{
								$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'Keyword'=>$l->test->Keyword);
							}
						}
					}
					$allobsunit=array();
					$allobsunit[]=(object)array('Id'=>'1','Unit'=>"ASTM grain size no.");
					$allobsunit[]=(object)array('Id'=>'2','Unit'=>"ISO grain size no.");
								
					$model=(object)array('basic'=>$basic,'magnifications'=>$allmagni,'testmethods'=>$alltestmethods,'allobsunit'=>$allobsunit);		
				}		
						break;							
				
				
			case 'microcoateditdata':
							
						$d=Rirtestdetail::model()->findByPk($_GET['id']);				
				if(!empty($d))
				{					
						$sds=array();
							

							
							
						
							
								if(empty($d->microcoatbasics))
						{
							$obbasic=(object)array('PlantLoc'=>"Pirangut",'Indenter'=>"Diamond",'Load'=>"0.3 Kgf");	
						}
						else
						{
							
						$obbasic=$d->microcoatbasics[0];							
						}		
							
							$allmagni=Magnification::model()->findAll();
							
								$basic=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'Note'=>$d->Note,'TestName'=>$d->TestName,'TestId'=>$d->TestId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestDate'=>$d->TestDate,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,						
							'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 
								   
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'obbasic'=>$obbasic,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method);
						
						$methods=Testmethods::model()->findAll();
					$alltestmethods=array();
					foreach($methods as $l)
					{
						if($l->test->Keyword==='MCT')
						{
							if($l->TypeId !=0)
							{
								$type=Testtypes::model()->findByPk($l->TypeId);
									$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'TypeId'=>$l->TypeId,'Type'=>$type->Type,'Keyword'=>$l->test->Keyword);
							}
							else
							{
								$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'Keyword'=>$l->test->Keyword);
							}
						}
					}
					$allobsunit=array();
					$allobsunit[]=(object)array('Id'=>'1','Unit'=>"ASTM grain size no.");
					$allobsunit[]=(object)array('Id'=>'2','Unit'=>"ISO grain size no.");
								
					$model=(object)array('basic'=>$basic,'magnifications'=>$allmagni,'testmethods'=>$alltestmethods,'allobsunit'=>$allobsunit);		
				}		
						break;	
						
			case 'microcasedeptheditdata':
							
						$d=Rirtestdetail::model()->findByPk($_GET['id']);				
				if(!empty($d))
				{					
						$sds=array();
							

							
						
							
								if(empty($d->microcasebasics))
						{
							$obbasic=(object)array('PlantLoc'=>"Pirangut",'Indenter'=>"Diamond",'Load'=>"0.3 Kgf");	
						}
						else
						{
							
						$obbasic=$d->microcasebasics[0];							
						}		
							
							$allmagni=Magnification::model()->findAll();
							
								$basic=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'Note'=>$d->Note,'TestName'=>$d->TestName,'TestId'=>$d->TestId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestDate'=>$d->TestDate,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,						
							'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 
								   
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'obbasic'=>$obbasic,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method);
						
						$methods=Testmethods::model()->findAll();
					$alltestmethods=array();
					foreach($methods as $l)
					{
						if($l->test->Keyword==='MCD')
						{
							if($l->TypeId !=0)
							{
								$type=Testtypes::model()->findByPk($l->TypeId);
									$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'TypeId'=>$l->TypeId,'Type'=>$type->Type,'Keyword'=>$l->test->Keyword);
							}
							else
							{
								$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'Keyword'=>$l->test->Keyword);
							}
						}
					}
					$allobsunit=array();
					$allobsunit[]=(object)array('Id'=>'1','Unit'=>"ASTM grain size no.");
					$allobsunit[]=(object)array('Id'=>'2','Unit'=>"ISO grain size no.");
								
					$model=(object)array('basic'=>$basic,'magnifications'=>$allmagni,'testmethods'=>$alltestmethods,'allobsunit'=>$allobsunit);		
				}		
						break;	

			case 'microdecarbeditdata':
							
						$d=Rirtestdetail::model()->findByPk($_GET['id']);				
				if(!empty($d))
				{					
						$sds=array();
							

							
						
							
								if(empty($d->microdecarbbasics))
						{
							$obbasic=(object)array('PlantLoc'=>"Pirangut",'Indenter'=>"Diamond",'Load'=>"0.3 Kgf");	
						}
						else
						{
							
						$obbasic=$d->microdecarbbasics[0];							
						}		
							
							$allmagni=Magnification::model()->findAll();
							
								$basic=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'Note'=>$d->Note,'TestName'=>$d->TestName,'TestId'=>$d->TestId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestDate'=>$d->TestDate,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,						
							'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 
								   
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'obbasic'=>$obbasic,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method);
						
						$methods=Testmethods::model()->findAll();
					$alltestmethods=array();
					foreach($methods as $l)
					{
						if($l->test->Keyword==='MDC')
						{
							if($l->TypeId !=0)
							{
								$type=Testtypes::model()->findByPk($l->TypeId);
									$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'TypeId'=>$l->TypeId,'Type'=>$type->Type,'Keyword'=>$l->test->Keyword);
							}
							else
							{
								$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'Keyword'=>$l->test->Keyword);
							}
						}
					}
					$allobsunit=array();
					$allobsunit[]=(object)array('Id'=>'1','Unit'=>"ASTM grain size no.");
					$allobsunit[]=(object)array('Id'=>'2','Unit'=>"ISO grain size no.");
								
					$model=(object)array('basic'=>$basic,'magnifications'=>$allmagni,'testmethods'=>$alltestmethods,'allobsunit'=>$allobsunit);		
				}		
						break;							

				
			case 'microstructeditdata':
							
						$d=Rirtestdetail::model()->findByPk($_GET['id']);				
				if(!empty($d))
				{					
						$sds=array();
							

							
							
						
							
								if(empty($d->microstructbasics))
						{
							$obbasic=(object)array('PlantLoc'=>"Pirangut",'Indenter'=>"Diamond",'Load'=>"0.3 Kgf");	
						}
						else
						{
							
						$obbasic=$d->microstructbasics[0];							
						}		
							
							$allmagni=Magnification::model()->findAll();
							
								$basic=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'Note'=>$d->Note,'TestName'=>$d->TestName,'TestId'=>$d->TestId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestDate'=>$d->TestDate,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,						
							'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 
								   
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'obbasic'=>$obbasic,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method);
						
						$methods=Testmethods::model()->findAll();
					$alltestmethods=array();
					foreach($methods as $l)
					{
						if($l->test->Keyword==='MS')
						{
							if($l->TypeId !=0)
							{
								$type=Testtypes::model()->findByPk($l->TypeId);
									$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'TypeId'=>$l->TypeId,'Type'=>$type->Type,'Keyword'=>$l->test->Keyword);
							}
							else
							{
								$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'Keyword'=>$l->test->Keyword);
							}
						}
					}
					
								
					$model=(object)array('basic'=>$basic,'magnifications'=>$allmagni,'testmethods'=>$alltestmethods);		
				}		
						break;							
							
				
			
				
			case 'grainsizeeditdata':
							// $d=Rirtestdetail::model()->find(array('condition'=>'TestId=:ID AND Id=:rno',
										 // 'params'=>array(':ID'=>"12",':rno'=>$_GET['id']),));
						$d=Rirtestdetail::model()->findByPk($_GET['id']);				
				if(!empty($d))
				{			
						$sds=array();
							

							
							
						
							
								if(empty($d->grainsizebasics))
						{
							$obbasic=(object)array('PlantLoc'=>"Pirangut",'Indenter'=>"Diamond",'Load'=>"0.3 Kgf");	
						}
						else
						{
							
						$obbasic=$d->grainsizebasics[0];							
						}		
							
							$allmagni=Magnification::model()->findAll();
							
								$basic=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'Note'=>$d->Note,'TestName'=>$d->TestName,'TestId'=>$d->TestId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestDate'=>$d->TestDate,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,						
							'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 
								   
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'obbasic'=>$obbasic,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method);
						
						$methods=Testmethods::model()->findAll();
					$alltestmethods=array();
					foreach($methods as $l)
					{
						if($l->test->Keyword==='GS')
						{
							if($l->TypeId !=0)
							{
								$type=Testtypes::model()->findByPk($l->TypeId);
									$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'TypeId'=>$l->TypeId,'Type'=>$type->Type,'Keyword'=>$l->test->Keyword);
							}
							else
							{
								$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'Keyword'=>$l->test->Keyword);
							}
						}
					}
					$allobsunit=array();
					$allobsunit[]=(object)array('Id'=>'1','Unit'=>"ASTM grain size no.");
					$allobsunit[]=(object)array('Id'=>'2','Unit'=>"ISO grain size no.");
								
					$model=(object)array('basic'=>$basic,'magnifications'=>$allmagni,'testmethods'=>$alltestmethods,'allobsunit'=>$allobsunit);		
				}		
						break;							
						
			
	case 'threadeditdata':
							// $d=Rirtestdetail::model()->find(array('condition'=>'TestId=:ID AND Id=:rno',
										 // 'params'=>array(':ID'=>"12",':rno'=>$_GET['id']),));
						$d=Rirtestdetail::model()->findByPk($_GET['id']);				
				if(!empty($d))
				{			
						$sds=array();
							

							
						
							$obs=array();
						if(empty($d->threadlapbasics))
						{
							$obs[]=(object)array('SrNo'=>1,'Parameter'=>"Flow Line",'Observation'=>"",'Remark'=>"");
							$obs[]=(object)array('SrNo'=>2,'Parameter'=>"Root Imperfection",'Observation'=>"",'Remark'=>"");
							$obs[]=(object)array('SrNo'=>3,'Parameter'=>"Laps",'Observation'=>"",'Remark'=>"");
							$obs[]=(object)array('SrNo'=>4,'Parameter'=>"Other Observation",'Observation'=>"",'Remark'=>"");
							$obs[]=(object)array('SrNo'=>5,'Parameter'=>"Height of Thread lap",'Observation'=>"",'Remark'=>"");
							$obbasic=(object)array('PlantLoc'=>"Pirangut",'Indenter'=>"Diamond",'Load'=>"0.3 Kgf",'Thobs'=>$obs);	
						}
						else
						{
							
						
						$ob=$d->threadlapbasics[0];
							$obbasic=(object)array('Id'=>$ob->Id,'RirTestId'=>$ob->RirTestId,'Sample'=>$ob->Sample,'Equipment'=>$ob->Equipment,
							'Orientation'=>$ob->Orientation,'Etchant'=>$ob->Etchant,'Magnification'=>$ob->Magnification,'PolSampSize'=>$ob->PolSampSize,
							'PlantLoc'=>$ob->PlantLoc,'Thobs'=>$ob->threadlapobs,'ObsRemark'=>$ob->ObsRemark);
						}		
							
							$allmagni=Magnification::model()->findAll();
							
								$basic=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'Note'=>$d->Note,'TestName'=>$d->TestName,'TestId'=>$d->TestId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestDate'=>$d->TestDate,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,						
							'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 
								   
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'obbasic'=>$obbasic,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method);
						
						$methods=Testmethods::model()->findAll();
					$alltestmethods=array();
					foreach($methods as $l)
					{
						if($l->test->Keyword==='GS')
						{
							if($l->TypeId !=0)
							{
								$type=Testtypes::model()->findByPk($l->TypeId);
									$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'TypeId'=>$l->TypeId,'Type'=>$type->Type,'Keyword'=>$l->test->Keyword);
							}
							else
							{
								$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'Keyword'=>$l->test->Keyword);
							}
						}
					}
					$allobsunit=array();
					$allobsunit[]=(object)array('Id'=>'1','Unit'=>"ASTM grain size no.");
					$allobsunit[]=(object)array('Id'=>'2','Unit'=>"ISO grain size no.");
								
					$model=(object)array('basic'=>$basic,'magnifications'=>$allmagni,'testmethods'=>$alltestmethods,'allobsunit'=>$allobsunit);		
				}		
						break;							
						
							
				
					
				default:
					$this->_sendResponse(501, sprintf(
						'Mode <b>view</b> is not implemented for model <b>%s</b>',
						$_GET['model']) );
					Yii::app()->end();
			}
			// Did we find the requested model? If not, raise an error
			if(is_null($model))
				$this->_sendResponse(404, 'No Item found with id '.$_GET['id']);
			else
				$this->_sendResponse(200, CJSON::encode($model));
    }
	
	
    public function actionCreate()
    {
				
			switch($_GET['model'])
			{
								
				case 'searchcarb':$model =new Receiptir;break;
				
				case 'searchgrainsize':$model =new Receiptir;break;
				case 'searchthread':$model =new Receiptir;break;
				case 'searchwinclrating':$model =new Receiptir;break;
				case 'searchkinclrating':$model =new Receiptir;break;
				case 'searchmicrostruct':$model =new Receiptir;break;
				case 'searchmicrocoat':$model =new Receiptir;break;
				case 'searchmicrocase':$model =new Receiptir;break;
				case 'searchmicrodecarb':$model =new Receiptir;break;
							
				default:
					$this->_sendResponse(501, 
						sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>',
						$_GET['model']) );
						Yii::app()->end();
			}
			$json = file_get_contents('php://input'); //$GLOBALS['HTTP_RAW_POST_DATA'] is not preferred: http://www.php.net/manual/en/ini.core.php#ini.always-populate-raw-post-data
            $data = CJSON::decode($json,true);//value decode in array format
	        //$this->_sendResponse(200, CJSON::encode($json));
	        switch($_GET['model'])
           {
			    
							
				 case 'searchcarb':break;
				 case 'searchgrainsize':break;
				case 'searchthread':break;
				case 'searchwinclrating':break;
				case 'searchkinclrating':break;
				case 'searchmicrostruct':break;
				case 'searchmicrocoat':break;
				case 'searchmicrocase':break;
				case 'searchmicrodecarb':break;
			   
			   default:
			// Try to assign POST values to attributes
			   foreach($data as $var=>$value) 
			 {
				// Does the model have this attribute? If not raise an error
				if($model->hasAttribute($var))
					$model->$var = $value;
				else
					$this->_sendResponse(500, 
						sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var,
						$_GET['model']) );
             }
		   }
		   
		   switch($_GET['model'])
           {
			   case 'searchmicrostruct':
			   	$totalitems=0;				 
										  $strbatchcode=$data['text'];
										  $strbatchno=$data['text'];
										 if(strstr($data['text'], '-'))
										 {
											 $words = explode('-', $data['text']);
											 if(!empty($words))
											 {
												$strbatchcode=$words[0];
												$strbatchno=$words[1];
											 }
											 
											 $rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.BatchCode = :bc AND receiptir.BatchNo = :bn )',
								'params'=>array(':id1'=>"35",':id2'=>"36",':id3'=>"37",':id4'=>"154",':id5'=>"155",':id6'=>"156",
											':id7'=>"157",':id8'=>"158",':id9'=>"159",':idw'=>"38",':bc'=>$strbatchcode,':bn'=>$strbatchno,
								),));
											 
										 }
										 else
										 {
											 $rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.PartName LIKE :pn OR receiptir.LabNo LIKE :ln OR receiptir.HeatNo LIKE :hn OR receiptir.BatchCode LIKE :bc OR receiptir.BatchNo LIKE :bn OR receiptir.MdsNo LIKE :mdsno OR receiptir.TdsNo LIKE :tdsno OR receiptir.NoType LIKE :noty)',
								'params'=>array(':id1'=>"35",':id2'=>"36",':id3'=>"37",':id4'=>"154",':id5'=>"155",':id6'=>"156",
											':id7'=>"157",':id8'=>"158",':id9'=>"159",':idw'=>"38",':pn'=>'%'.$data['text'].'%',':ln'=>'%'.$data['text'].'%',':hn'=>'%'.$data['text'].'%',':bc'=>'%'.$data['text'].'%',':bn'=>'%'.$data['text'].'%',
								':mdsno'=>'%'.$data['text'].'%',':tdsno'=>'%'.$data['text'].'%',':noty'=>'%'.$data['text'].'%'),));
										 }
		
							 $allrirs=array();
							foreach($rtds as $d)
							{			 
							$mds="";$tds="";
							if($d->rIR->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->rIR->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->rIR->TdsNo);
								$tds=$t->TdsNo;
							}
							
						
										 
								if(empty($d->microstructbasics))
						{
							$obbasic=(object)array();	
						}
						else
						{
							
							$obbasic=$d->microstructbasics[0];							
						}			 
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
							
							$img="";
							if(!empty($d->testuploads))
							{
								$img=$d->testuploads[0];
							}
							
														$testno=1;
							$testno=substr($d->TestName, -1);
							if($testno !='1' && $testno !='2' && $testno !='3' && $testno !='4' && $testno !='5' && $testno !='6' && $testno !='7' && $testno !='8' && $testno !='9'&& $testno !='W' )
							{
								$testno=1;
							}			
									
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'obbasic'=>$obbasic,'Note'=>$d->Note,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'Imgs'=>$img,'TestNo'=>$testno,'ULRNo'=>$d->ULRNo,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,'ApprovedDate'=>$d->ApprovedDate,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>$d->RevDate);

							}
							
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));
						
						break;
				
			   
						
						
			    case 'searchgrainsize':
							$totalitems=0;				 
										  $strbatchcode=$data['text'];
										  $strbatchno=$data['text'];
										 if(strstr($data['text'], '-'))
										 {
											 $words = explode('-', $data['text']);
											 if(!empty($words))
											 {
												$strbatchcode=$words[0];
												$strbatchno=$words[1];
											 }
											 
											 $rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.BatchCode = :bc AND receiptir.BatchNo = :bn )',
								'params'=>array(':id1'=>"39",':id2'=>"40",':id3'=>"41",':id4'=>"148",':id5'=>"149",':id6'=>"150",
											':id7'=>"151",':id8'=>"152",':id9'=>"153",':idw'=>"42",':bc'=>$strbatchcode,':bn'=>$strbatchno,
								),));
											 
										 }
										 else
										 {
											 $rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.PartName LIKE :pn OR receiptir.LabNo LIKE :ln OR receiptir.HeatNo LIKE :hn OR receiptir.BatchCode LIKE :bc OR receiptir.BatchNo LIKE :bn OR receiptir.MdsNo LIKE :mdsno OR receiptir.TdsNo LIKE :tdsno OR receiptir.NoType LIKE :noty)',
								'params'=>array(':id1'=>"39",':id2'=>"40",':id3'=>"41",':id4'=>"148",':id5'=>"149",':id6'=>"150",
											':id7'=>"151",':id8'=>"152",':id9'=>"153",':idw'=>"42",':pn'=>'%'.$data['text'].'%',':ln'=>'%'.$data['text'].'%',':hn'=>'%'.$data['text'].'%',':bc'=>'%'.$data['text'].'%',':bn'=>'%'.$data['text'].'%',
								':mdsno'=>'%'.$data['text'].'%',':tdsno'=>'%'.$data['text'].'%',':noty'=>'%'.$data['text'].'%'),));
										 }
		
						
									 $allrirs=array();
								if(!empty($rtds))
						{				 
							foreach($rtds as $d)
							{			 
							$mds="";$tds="";
							if($d->rIR->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->rIR->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->rIR->TdsNo);
								$tds=$t->TdsNo;
							}
							
						
										 
								if(empty($d->grainsizebasics))
						{
							$obbasic=(object)array();	
						}
						else
						{
							
							$obbasic=$d->grainsizebasics[0];
							
						}			 
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
							$img="";		
							if(!empty($d->testuploads))
							{
								$img=$d->testuploads[0];
							}
								
							$testno=1;
							$testno=substr($d->TestName, -1);
							if($testno !='1' && $testno !='2' && $testno !='3' && $testno !='4' && $testno !='5' && $testno !='6' && $testno !='7' && $testno !='8' && $testno !='9'&& $testno !='W' )
							{
								$testno=1;
							}											
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'obbasic'=>$obbasic,'Note'=>$d->Note,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'Imgs'=>$img,'TestNo'=>$testno,'ULRNo'=>$d->ULRNo,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,'ApprovedDate'=>$d->ApprovedDate,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>$d->RevDate);

							}
							
							$allobsunit=array();
					$allobsunit[]=(object)array('Id'=>'1','Unit'=>"ASTM grain size no.");
					$allobsunit[]=(object)array('Id'=>'2','Unit'=>"ISO grain size no.");
							
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems,'allobsunit'=>$allobsunit);
							$this->_sendResponse(200, CJSON::encode($data));
						}
						else
						{
							 $allrirs=array();
							 $totalitems=0;
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));
						}
						
						break;
				
				
				
		
				case 'searchthread':
				$totalitems=0;				 
										  $strbatchcode=$data['text'];
										  $strbatchno=$data['text'];
										 if(strstr($data['text'], '-'))
										 {
											 $words = explode('-', $data['text']);
											 if(!empty($words))
											 {
												$strbatchcode=$words[0];
												$strbatchno=$words[1];
											 }
											 
											 $rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.BatchCode = :bc AND receiptir.BatchNo = :bn )',
								'params'=>array(':id1'=>"63",':id2'=>"64",':id3'=>"65",':id4'=>"184",':id5'=>"185",':id6'=>"186",
											':id7'=>"187",':id8'=>"188",':id9'=>"189",':idw'=>"66",':bc'=>$strbatchcode,':bn'=>$strbatchno,
								),));
											 
										 }
										 else
										 {
											 $rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.PartName LIKE :pn OR receiptir.LabNo LIKE :ln OR receiptir.HeatNo LIKE :hn OR receiptir.BatchCode LIKE :bc OR receiptir.BatchNo LIKE :bn OR receiptir.MdsNo LIKE :mdsno OR receiptir.TdsNo LIKE :tdsno OR receiptir.NoType LIKE :noty)',
								'params'=>array(':id1'=>"63",':id2'=>"64",':id3'=>"65",':id4'=>"184",':id5'=>"185",':id6'=>"186",
											':id7'=>"187",':id8'=>"188",':id9'=>"189",':idw'=>"66",':pn'=>'%'.$data['text'].'%',':ln'=>'%'.$data['text'].'%',':hn'=>'%'.$data['text'].'%',':bc'=>'%'.$data['text'].'%',':bn'=>'%'.$data['text'].'%',
								':mdsno'=>'%'.$data['text'].'%',':tdsno'=>'%'.$data['text'].'%',':noty'=>'%'.$data['text'].'%'),));
										 }
		
						
										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							$mds="";$tds="";
							if($d->rIR->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->rIR->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->rIR->TdsNo);
								$tds=$t->TdsNo;
							}
							
						
						
								if(empty($d->threadlapbasics))
						{
							$obbasic=(object)array();	
						}
						else
						{
							$ob=$d->threadlapbasics[0];
							$obbasic=(object)array('Id'=>$ob->Id,'RirTestId'=>$ob->RirTestId,'Sample'=>$ob->Sample,'Equipment'=>$ob->Equipment,
							'Orientation'=>$ob->Orientation,'Etchant'=>$ob->Etchant,'Magnification'=>$ob->Magnification,'PolSampSize'=>$ob->PolSampSize,
							'PlantLoc'=>$ob->PlantLoc,'Thobs'=>$ob->threadlapobs,'ObsRemark'=>$ob->ObsRemark);
							
						}		

//	$this->_sendResponse(401, CJSON::encode($d->threadlapbasics));			 						
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
							$img="";		
							if(!empty($d->testuploads))
							{
								$img=$d->testuploads;
							}
															$testno=1;
							$testno=substr($d->TestName, -1);
							if($testno !='1' && $testno !='2' && $testno !='3' && $testno !='4' && $testno !='5' && $testno !='6' && $testno !='7' && $testno !='8' && $testno !='9'&& $testno !='W' )
							{
								$testno=1;
							}				
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'obbasic'=>$obbasic,'Note'=>$d->Note,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'Imgs'=>$img,'TestNo'=>$testno,'ULRNo'=>$d->ULRNo,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,'ApprovedDate'=>$d->ApprovedDate,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>$d->RevDate);

							}
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));	
				
				break;
				
				
				
				
				case 'searchwinclrating':
				$totalitems=0;				 
										  $strbatchcode=$data['text'];
										  $strbatchno=$data['text'];
										 if(strstr($data['text'], '-'))
										 {
											 $words = explode('-', $data['text']);
											 if(!empty($words))
											 {
												$strbatchcode=$words[0];
												$strbatchno=$words[1];
											 }
											 
											 $rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.BatchCode = :bc AND receiptir.BatchNo = :bn )',
								'params'=>array(':id1'=>"59",':id2'=>"60",':id3'=>"61",':id4'=>"166",':id5'=>"167",':id6'=>"168",
											':id7'=>"169",':id8'=>"170",':id9'=>"171",':idw'=>"62",':bc'=>$strbatchcode,':bn'=>$strbatchno,
								),));
											 
										 }
										 else
										 {
											 $rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.PartName LIKE :pn OR receiptir.LabNo LIKE :ln OR receiptir.HeatNo LIKE :hn OR receiptir.BatchCode LIKE :bc OR receiptir.BatchNo LIKE :bn OR receiptir.MdsNo LIKE :mdsno OR receiptir.TdsNo LIKE :tdsno OR receiptir.NoType LIKE :noty)',
								'params'=>array(':id1'=>"59",':id2'=>"60",':id3'=>"61",':id4'=>"166",':id5'=>"167",':id6'=>"168",
											':id7'=>"169",':id8'=>"170",':id9'=>"171",':idw'=>"62",':pn'=>'%'.$data['text'].'%',':ln'=>'%'.$data['text'].'%',':hn'=>'%'.$data['text'].'%',':bc'=>'%'.$data['text'].'%',':bn'=>'%'.$data['text'].'%',
								':mdsno'=>'%'.$data['text'].'%',':tdsno'=>'%'.$data['text'].'%',':noty'=>'%'.$data['text'].'%'),));
										 }
		
						
										 $allrirs=array();
						foreach($rtds as $d)
							{			 
							$mds="";$tds="";
							if($d->rIR->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->rIR->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->rIR->TdsNo);
								$tds=$t->TdsNo;
							}
							
						
										 
								if(empty($d->winclratbasics))
						{
							$obbasic=(object)array();	
						}
						else
						{
							$ob=$d->winclratbasics[0];
							
							$observations=$ob->winclratobs;
							$obbasic=(object)array('Id'=>$ob->Id,'RirTestId'=>$ob->RirTestId,'Sample'=>$ob->Sample,'Orientation'=>$ob->Orientation,
							'Magnification'=>$ob->Magnification,'TestReportNo'=>$ob->TestReportNo,'PolSampSize'=>$ob->PolSampSize,
							'PlantLoc'=>$ob->PlantLoc,'TestTemp'=>$ob->TestTemp,'Observations'=>$observations,'ObsRemark'=>$ob->ObsRemark,
							'AvgThinA'=>$ob->AvgThinA,'AvgThickA'=>$ob->AvgThickA,'AvgThinB'=>$ob->AvgThinB,'AvgThickB'=>$ob->AvgThickB,
							'AvgThinC'=>$ob->AvgThinC,'AvgThickC'=>$ob->AvgThickC,'AvgThinD'=>$ob->AvgThinD,'AvgThickD'=>$ob->AvgThickD);					
						}			 
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
							$img="";		
							if(!empty($d->testuploads))
							{
								$img=$d->testuploads[0];
							}
															$testno=1;
							$testno=substr($d->TestName, -1);
							if($testno !='1' && $testno !='2' && $testno !='3' && $testno !='4' && $testno !='5' && $testno !='6' && $testno !='7' && $testno !='8' && $testno !='9' && $testno !='W' )
							{
								$testno=1;
							}				
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'obbasic'=>$obbasic,'Note'=>$d->Note,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'Imgs'=>$img,'TestNo'=>$testno,'ULRNo'=>$d->ULRNo,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,'ApprovedDate'=>$d->ApprovedDate,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>$d->RevDate);

							}
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));	
				
				break;
				
				
				case 'searchkinclrating':$totalitems=0;				 
										  $strbatchcode=$data['text'];
										  $strbatchno=$data['text'];
										 if(strstr($data['text'], '-'))
										 {
											 $words = explode('-', $data['text']);
											 if(!empty($words))
											 {
												$strbatchcode=$words[0];
												$strbatchno=$words[1];
											 }
											 
											 $rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.BatchCode = :bc AND receiptir.BatchNo = :bn )',
								'params'=>array(':id1'=>"55",':id2'=>"56",':id3'=>"57",':id4'=>"160",':id5'=>"161",':id6'=>"162",
											':id7'=>"163",':id8'=>"164",':id9'=>"165",':idw'=>"58",':bc'=>$strbatchcode,':bn'=>$strbatchno,
								),));
											 
										 }
										 else
										 {
											 $rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.PartName LIKE :pn OR receiptir.LabNo LIKE :ln OR receiptir.HeatNo LIKE :hn OR receiptir.BatchCode LIKE :bc OR receiptir.BatchNo LIKE :bn OR receiptir.MdsNo LIKE :mdsno OR receiptir.TdsNo LIKE :tdsno OR receiptir.NoType LIKE :noty)',
								'params'=>array(':id1'=>"55",':id2'=>"56",':id3'=>"57",':id4'=>"160",':id5'=>"161",':id6'=>"162",
											':id7'=>"163",':id8'=>"164",':id9'=>"165",':idw'=>"58",':pn'=>'%'.$data['text'].'%',':ln'=>'%'.$data['text'].'%',':hn'=>'%'.$data['text'].'%',':bc'=>'%'.$data['text'].'%',':bn'=>'%'.$data['text'].'%',
								':mdsno'=>'%'.$data['text'].'%',':tdsno'=>'%'.$data['text'].'%',':noty'=>'%'.$data['text'].'%'),));
										 }
		
						
										 $allrirs=array();
				foreach($rtds as $d)
							{			 
							$mds="";$tds="";
							if($d->rIR->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->rIR->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->rIR->TdsNo);
								$tds=$t->TdsNo;
							}
							
						
										 
								if(empty($d->kinclratbasics))
						{
							$obbasic=(object)array();	
						}
						else
						{
							
							$observations=array();
							$b=$d->kinclratbasics[0];
							if(!empty($b->kinclratobsbasics))
							{
								foreach($b->kinclratobsbasics as $obb)
								{
									$obs=$obb->kinclratobsobs;
									$observations[]=(object)array('Id'=>$obb->Id,'KIRBId'=>$obb->KIRBId,'SpecNo'=>$obb->SpecNo,
									'AreaPol'=>$obb->AreaPol,'obs'=>$obs);
								}
								//$observations=$b->kinclratobs;	
							}
							

							$obbasic=(object)array('Id'=>$b->Id,'RirTestId'=>$b->RirTestId,'Sample'=>$b->Sample,'Orientation'=>$b->Orientation,
							'Etchant'=>$b->Etchant,'Magnification'=>$b->Magnification,'TestReportNo'=>$b->TestReportNo,'PolSampSize'=>$b->PolSampSize,
							'PlantLoc'=>$b->PlantLoc,'TestTemp'=>$b->TestTemp,'Total'=>$b->Total,'SSTotalS'=>$b->SSTotalS,'SSTotalO'=>$b->SSTotalO,
							'TotalK3S'=>$b->TotalK3S,'TotalK3O'=>$b->TotalK3O,'OverAllTotK3'=>$b->OverAllTotK3,'ObsRemark'=>$b->ObsRemark,
							'Observations'=>$observations);	
					
						}			 
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
							
							$img="";							
							if(!empty($d->testuploads))
							{
								$img=$d->testuploads[0];
							}
															$testno=1;
							$testno=substr($d->TestName, -1);
							if($testno !='1' && $testno !='2' && $testno !='3' && $testno !='4' && $testno !='5' && $testno !='6' && $testno !='7' && $testno !='8' && $testno !='9'&& $testno !='W' )
							{
								$testno=1;
							}				
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'obbasic'=>$obbasic,'Note'=>$d->Note,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'Imgs'=>$img,'TestNo'=>$testno,'ULRNo'=>$d->ULRNo,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,'ApprovedDate'=>$d->ApprovedDate,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>$d->RevDate);

							}
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));	
				
				break;
				
				
				case 'searchmicrocoat':$totalitems=0;				 
										  $strbatchcode=$data['text'];
										  $strbatchno=$data['text'];
										 if(strstr($data['text'], '-'))
										 {
											 $words = explode('-', $data['text']);
											 if(!empty($words))
											 {
												$strbatchcode=$words[0];
												$strbatchno=$words[1];
											 }
											 
											 $rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.BatchCode = :bc AND receiptir.BatchNo = :bn )',
								'params'=>array(':id1'=>"43",':id2'=>"44",':id3'=>"45",':id4'=>"178",':id5'=>"179",':id6'=>"180",
											':id7'=>"181",':id8'=>"182",':id9'=>"183",':idw'=>"46",':bc'=>$strbatchcode,':bn'=>$strbatchno,
								),));
											 
										 }
										 else
										 {
											 $rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.PartName LIKE :pn OR receiptir.LabNo LIKE :ln OR receiptir.HeatNo LIKE :hn OR receiptir.BatchCode LIKE :bc OR receiptir.BatchNo LIKE :bn OR receiptir.MdsNo LIKE :mdsno OR receiptir.TdsNo LIKE :tdsno OR receiptir.NoType LIKE :noty)',
								'params'=>array(':id1'=>"43",':id2'=>"44",':id3'=>"45",':id4'=>"178",':id5'=>"179",':id6'=>"180",
											':id7'=>"181",':id8'=>"182",':id9'=>"183",':idw'=>"46",':pn'=>'%'.$data['text'].'%',':ln'=>'%'.$data['text'].'%',':hn'=>'%'.$data['text'].'%',':bc'=>'%'.$data['text'].'%',':bn'=>'%'.$data['text'].'%',
								':mdsno'=>'%'.$data['text'].'%',':tdsno'=>'%'.$data['text'].'%',':noty'=>'%'.$data['text'].'%'),));
										 }
		
						
										 $allrirs=array();
								foreach($rtds as $d)
							{			 
							$mds="";$tds="";
							if($d->rIR->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->rIR->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->rIR->TdsNo);
								$tds=$t->TdsNo;
							}
							
						
										 
								if(empty($d->microcoatbasics))
						{
							$obbasic=(object)array();	
						}
						else
						{
							
							$obbasic=$d->microcoatbasics[0];							
						}			 
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
							
									$img="";
							if(!empty($d->testuploads))
							{
								$img=$d->testuploads[0];
							}
									
															$testno=1;
							$testno=substr($d->TestName, -1);
							if($testno !='1' && $testno !='2' && $testno !='3' && $testno !='4' && $testno !='5' && $testno !='6' && $testno !='7' && $testno !='8' && $testno !='9'&& $testno !='W' )
							{
								$testno=1;
							}							
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'obbasic'=>$obbasic,'Note'=>$d->Note,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'Imgs'=>$img,'TestNo'=>$testno,'ULRNo'=>$d->ULRNo,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,'ApprovedDate'=>$d->ApprovedDate,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>$d->RevDate);

							}
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));	
				
				break;
				
				
				case 'searchmicrocase':$totalitems=0;				 
										  $strbatchcode=$data['text'];
										  $strbatchno=$data['text'];
										 if(strstr($data['text'], '-'))
										 {
											 $words = explode('-', $data['text']);
											 if(!empty($words))
											 {
												$strbatchcode=$words[0];
												$strbatchno=$words[1];
											 }
											 
											 $rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.BatchCode = :bc AND receiptir.BatchNo = :bn )',
								'params'=>array(':id1'=>"47",':id2'=>"48",':id3'=>"49",':id4'=>"172",':id5'=>"173",':id6'=>"174",
											':id7'=>"175",':id8'=>"176",':id9'=>"177",':idw'=>"50",':bc'=>$strbatchcode,':bn'=>$strbatchno,
								),));
											 
										 }
										 else
										 {
											 $rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.PartName LIKE :pn OR receiptir.LabNo LIKE :ln OR receiptir.HeatNo LIKE :hn OR receiptir.BatchCode LIKE :bc OR receiptir.BatchNo LIKE :bn OR receiptir.MdsNo LIKE :mdsno OR receiptir.TdsNo LIKE :tdsno OR receiptir.NoType LIKE :noty)',
								'params'=>array(':id1'=>"47",':id2'=>"48",':id3'=>"49",':id4'=>"172",':id5'=>"173",':id6'=>"174",
											':id7'=>"175",':id8'=>"176",':id9'=>"177",':idw'=>"50",':pn'=>'%'.$data['text'].'%',':ln'=>'%'.$data['text'].'%',':hn'=>'%'.$data['text'].'%',':bc'=>'%'.$data['text'].'%',':bn'=>'%'.$data['text'].'%',
								':mdsno'=>'%'.$data['text'].'%',':tdsno'=>'%'.$data['text'].'%',':noty'=>'%'.$data['text'].'%'),));
										 }
		
						
										 $allrirs=array();
						foreach($rtds as $d)
							{			 
							$mds="";$tds="";
							if($d->rIR->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->rIR->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->rIR->TdsNo);
								$tds=$t->TdsNo;
							}
							
						
										 
								if(empty($d->microcasebasics))
						{
							$obbasic=(object)array();	
						}
						else
						{
							
							$obbasic=$d->microcasebasics[0];							
						}			 
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}	

							$img="";							
									
							if(!empty($d->testuploads))
							{
								$img=$d->testuploads[0];
							}
															$testno=1;
							$testno=substr($d->TestName, -1);
							if($testno !='1' && $testno !='2' && $testno !='3' && $testno !='4' && $testno !='5' && $testno !='6' && $testno !='7' && $testno !='8' && $testno !='9'&& $testno !='W' )
							{
								$testno=1;
							}				
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'obbasic'=>$obbasic,'Note'=>$d->Note,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'Imgs'=>$img,'TestNo'=>$testno,'ULRNo'=>$d->ULRNo,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,'ApprovedDate'=>$d->ApprovedDate,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>$d->RevDate);

							}
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));	
				
				break;
				
				
				case 'searchmicrodecarb':$totalitems=0;				 
										  $strbatchcode=$data['text'];
										  $strbatchno=$data['text'];
										 if(strstr($data['text'], '-'))
										 {
											 $words = explode('-', $data['text']);
											 if(!empty($words))
											 {
												$strbatchcode=$words[0];
												$strbatchno=$words[1];
											 }
											 
											 $rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.BatchCode = :bc AND receiptir.BatchNo = :bn )',
								'params'=>array(':id1'=>"51",':id2'=>"52",':id3'=>"53",':id4'=>"190",':id5'=>"191",':id6'=>"192",
											':id7'=>"193",':id8'=>"194",':id9'=>"195",':idw'=>"54",':bc'=>$strbatchcode,':bn'=>$strbatchno,
								),));
											 
										 }
										 else
										 {
											 $rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.PartName LIKE :pn OR receiptir.LabNo LIKE :ln OR receiptir.HeatNo LIKE :hn OR receiptir.BatchCode LIKE :bc OR receiptir.BatchNo LIKE :bn OR receiptir.MdsNo LIKE :mdsno OR receiptir.TdsNo LIKE :tdsno OR receiptir.NoType LIKE :noty)',
								'params'=>array(':id1'=>"51",':id2'=>"52",':id3'=>"53",':id4'=>"190",':id5'=>"191",':id6'=>"192",
											':id7'=>"193",':id8'=>"194",':id9'=>"195",':idw'=>"54",':pn'=>'%'.$data['text'].'%',':ln'=>'%'.$data['text'].'%',':hn'=>'%'.$data['text'].'%',':bc'=>'%'.$data['text'].'%',':bn'=>'%'.$data['text'].'%',
								':mdsno'=>'%'.$data['text'].'%',':tdsno'=>'%'.$data['text'].'%',':noty'=>'%'.$data['text'].'%'),));
										 }
		
						
										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							$mds="";$tds="";
							if($d->rIR->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->rIR->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->rIR->TdsNo);
								$tds=$t->TdsNo;
							}
							
						
										 
								if(empty($d->microdecarbbasics))
						{
							$obbasic=(object)array();	
						}
						else
						{
							
							$obbasic=$d->microdecarbbasics[0];							
						}			 
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
								
										$img="";
							if(!empty($d->testuploads))
							{
								$img=$d->testuploads[0];
							}
																$testno=1;
							$testno=substr($d->TestName, -1);
							if($testno !='1' && $testno !='2' && $testno !='3' && $testno !='4' && $testno !='5' && $testno !='6' && $testno !='7' && $testno !='8' && $testno !='9'&& $testno !='W' )
							{
								$testno=1;
							}			
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'obbasic'=>$obbasic,'Note'=>$d->Note,
								'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'Imgs'=>$img,'TestNo'=>$testno,'ULRNo'=>$d->ULRNo,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,'ApprovedDate'=>$d->ApprovedDate,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>$d->RevDate);

							}
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));	
				
				break;
				
				
				
			   
			    case 'searchcarb':
										 
							$totalitems=0;				 
										 
						
							$rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.PartName LIKE :pn OR receiptir.LabNo LIKE :ln OR receiptir.HeatNo LIKE :hn OR receiptir.BatchCode LIKE :bc OR receiptir.BatchNo LIKE :bn OR receiptir.MdsNo LIKE :mdsno OR receiptir.TdsNo LIKE :tdsno OR receiptir.NoType LIKE :noty)',
								'params'=>array(':ida'=>"12",':idb'=>"29",':idc'=>"30",':idd'=>"31",':pn'=>'%'.$data['text'].'%',':ln'=>'%'.$data['text'].'%',':hn'=>'%'.$data['text'].'%',':bc'=>'%'.$data['text'].'%',':bn'=>'%'.$data['text'].'%',
								':mdsno'=>'%'.$data['text'].'%',':tdsno'=>'%'.$data['text'].'%',':noty'=>'%'.$data['text'].'%'),));
		
						
										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							$mds="";$tds="";
							if($d->rIR->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->rIR->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->rIR->TdsNo);
								$tds=$t->TdsNo;
							}
							
						$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
							$md=$sub->mechdetails[0];
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN' || $type==='DIN' || $type==='BS' || $type==='IS')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='SAE' )
						{
							$substandard=$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						if($type==='A' )
						{
							$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;
						}
						$std=$sub->standard->Standard." ".$substandard;
										 
								if(empty($d->carbobservations))
						{
							$observations=(object)array();	
						}
						else
						{
							
							$observations=$d->carbobservations[0];							
						}			 
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
							// $impcond=(object)array('AtTemp'=>$iattemp,'Min'=>$imin,'Max'=>$imax,'Unit'=>$iunit);
							
							$impcond="";					
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'Standard'=>$std,'cond'=>$impcond,'observations'=>$observations,'Note'=>$d->Note,
								'StandardId'=>$sub->StandardId,'SubStandardId'=>$sub->Id,'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'ULRNo'=>$d->ULRNo,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,'ApprovedDate'=>$d->ApprovedDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby);

							}
							
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));	
				
				break;
				
				 case 'searchthread':
										 
							$totalitems=0;				 
										 $strbatchcode=$data['text'];
										  $strbatchno=$data['text'];
										 if(strstr($data['text'], '-'))
										 {
											 $words = explode('-', $data['text']);
											 if(!empty($words))
											 {
												$strbatchcode=$words[0];
												$strbatchno=$words[1];
											 }
											 
											 $rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.BatchCode = :bc AND receiptir.BatchNo = :bn )',
								'params'=>array(':id1'=>"12",':id2'=>"29",':id3'=>"30",':id4'=>"31",':id5'=>"79",':id6'=>"80",':id7'=>"81",':id8'=>"82",':id9'=>"83",':idw'=>"84",':bc'=>$strbatchcode,':bn'=>$strbatchno,
								),));
											 
										 }
										 else
										 {
											 
										
						
							$rtds=Rirtestdetail::model()->with('receiptir')->findAll(array(
								'order' => 't.Id desc',
								'limit' =>'75',// $data['pageSize'],
								
							 	'condition'=>'(TestId=:id1 OR TestId=:id2 OR TestId=:id3 OR TestId=:id4 OR TestId=:id5 OR TestId=:id6 OR TestId=:id7
								OR TestId=:id8 OR TestId=:id9 OR TestId=:idw) AND (receiptir.PartName LIKE :pn OR receiptir.LabNo LIKE :ln OR receiptir.HeatNo LIKE :hn OR receiptir.BatchCode LIKE :bc OR receiptir.BatchNo LIKE :bn OR receiptir.MdsNo LIKE :mdsno OR receiptir.TdsNo LIKE :tdsno OR receiptir.NoType LIKE :noty)',
								'params'=>array(':ida'=>"63",':idb'=>"64",':idc'=>"65",':idd'=>"66",':pn'=>'%'.$data['text'].'%',':ln'=>'%'.$data['text'].'%',':hn'=>'%'.$data['text'].'%',':bc'=>'%'.$data['text'].'%',':bn'=>'%'.$data['text'].'%',
								':mdsno'=>'%'.$data['text'].'%',':tdsno'=>'%'.$data['text'].'%',':noty'=>'%'.$data['text'].'%'),));
										 }
						
										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							$mds="";$tds="";
							if($d->rIR->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->rIR->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->rIR->TdsNo);
								$tds=$t->TdsNo;
							}
							
						$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
							$md=$sub->mechdetails[0];
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN' || $type==='DIN' || $type==='BS' || $type==='IS')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='SAE' )
						{
							$substandard=$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						if($type==='A' )
						{
							$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;
						}
						$std=$sub->standard->Standard." ".$substandard;
										 
								if(empty($d->threadlapbasics))
						{
							$observations=(object)array();	
						}
						else
						{
							
							$observations=$d->carbobservations[0];							
						}			 
						
							$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->testedBy->usersignuploads[0]->name);
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$d->approvedBy->usersignuploads[0]->name);
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
							}			
							// $impcond=(object)array('AtTemp'=>$iattemp,'Min'=>$imin,'Max'=>$imax,'Unit'=>$iunit);
							
							$impcond="";					
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->rIR->RirNo,'PartName'=>$d->rIR->PartName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,'TestMethodId'=>$d->TestMethodId,'TestMethod'=>$d->testMethod->Method,'TestName'=>$d->TestName,
								'TCNo'=>$d->rIR->TCNo,'InvoiceNo'=>$d->rIR->InvoiceNo,'InvoiceDate'=>$d->rIR->InvoiceDate,'CustomerId'=>$d->rIR->CustomerId,'Customer'=>$d->rIR->customer->CustomerName,'RouteCardNo'=>$d->rIR->RouteCardNo,			
								'Supplier'=>$d->rIR->Supplier,'GrinNo'=>$d->rIR->GrinNo,'Quantity'=>$d->rIR->Quantity,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'HeatNo'=>$d->rIR->HeatNo,'BatchNo'=>$d->rIR->BatchNo,'NoType'=>$d->rIR->NoType, 'TestDate'=>$d->TestDate,
								   'Standard'=>$std,'cond'=>$impcond,'observations'=>$observations,'Note'=>$d->Note,
								'StandardId'=>$sub->StandardId,'SubStandardId'=>$sub->Id,'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'ULRNo'=>$d->ULRNo,
								'MaterialCondition'=>$d->rIR->MaterialCondition,'MaterialGrade'=>$d->rIR->MaterialGrade,'ReqDate'=>$d->ReqDate,'ApprovedDate'=>$d->ApprovedDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby);

							}
							
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));	
				
				break;
	
				
			   
			   		
								         
				    default:
			// Try to save the model
			if($model->save())
				$this->_sendResponse(200, CJSON::encode($model));
			else {
				// Errors occurred
				$msg = "<h1>Error</h1>";
				$msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
				$msg .= "<ul>";
				foreach($model->errors as $attribute=>$attr_errors) {
					$msg .= "<li>Attribute: $attribute</li>";
					$msg .= "<ul>";
					foreach($attr_errors as $attr_error)
						$msg .= "<li>$attr_error</li>";
					$msg .= "</ul>";
				}
				$msg .= "</ul>";
				$this->_sendResponse(500, $msg );
			}
		 }
		
    }
	
	
    public function actionUpdate()
    {
				 // Parse the PUT parameters. This didn't work: parse_str(file_get_contents('php://input'), $put_vars);
			$json = file_get_contents('php://input'); //$GLOBALS['HTTP_RAW_POST_DATA'] is not preferred: http://www.php.net/manual/en/ini.core.php#ini.always-populate-raw-post-data
			$put_vars = CJSON::decode($json,true);  //true means use associative array
		 
			switch($_GET['model'])
			{
				case 'winclratingupdate':		$model=Rirtestdetail::model()->findByPk($_GET['id']); 		break;
				case 'kinclratingupdate':		$model=Rirtestdetail::model()->findByPk($_GET['id']); 		break;
				case 'microcoatupdate':		$model=Rirtestdetail::model()->findByPk($_GET['id']); 		break;
				
				case 'microcasedepthupdate':$model=Rirtestdetail::model()->findByPk($_GET['id']); 		break;
				
				case 'microdecarbupdate':	$model=Rirtestdetail::model()->findByPk($_GET['id']); 		break;
				
				case 'microstructupdate':	$model=Rirtestdetail::model()->findByPk($_GET['id']); 		break;	
				
				case 'grainsizeupdate':		$model=Rirtestdetail::model()->findByPk($_GET['id']); 		break;
				case 'threadupdate':		$model=Rirtestdetail::model()->findByPk($_GET['id']); 		break;
				
				
					
									
				default:
					$this->_sendResponse(501, 
						sprintf( 'Error: Mode <b>update</b> is not implemented for model <b>%s</b>',
						$_GET['model']) );
					Yii::app()->end();
			}
			// Did we find the requested model? If not, raise an error
			if($model === null)
				$this->_sendResponse(400, 
						sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
						$_GET['model'], $_GET['id']) );
		 
		 switch($_GET['model'])
			{
			
			case 'winclratingupdate':		$data=$put_vars;break;
			case 'kinclratingupdate':		$data=$put_vars;break;
			
			case 'microcoatupdate':		$data=$put_vars;break;
				
				case 'microcasedepthupdate':$data=$put_vars;break;
				
				case 'microdecarbupdate':	$data=$put_vars;break;
				
				case 'microstructupdate':	$data=$put_vars;break;	
			case 'grainsizeupdate':$data=$put_vars;break;
			
			case 'threadupdate':$data=$put_vars;break;
						
					 
			default :
			// Try to assign PUT parameters to attributes
			foreach($put_vars as $var=>$value) {
				// Does model have this attribute? If not, raise an error
				if($model->hasAttribute($var))
					$model->$var = $value;
				else {
					$this->_sendResponse(500, 
						sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>',
						$var, $_GET['model']) );
				}
			}
			
			
			}
			// Try to save the model
			
			
			 switch($_GET['model'])
			{
				
				case 'winclratingupdate':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						$remark="Failed";
						$status="pending";
						
						if($data['basic']['Remark'] ==='Passed')
							{
								$remark="Passed";
							}
						
						if($remark==='Failed')
						{
							$status="failed";
						}							
						if($remark==='Passed')
						{
							$status="complete";
						}
							
													
							$uir=Userapppermission::model()->find(array('condition'=>'UserId =:uid AND SectionId="3"',
							'params'=>array(':uid'=>$data['ModifiedBy']),));
							if(!empty($uir))
							{
								if($uir->C==='true')
								{
									$model->TestedBy=$data['ModifiedBy'];	
								}
							}		
							
						$model->TestDate=$data['basic']['TestDate'];
						$model->Note=$data['basic']['Note'];
						$model->Remark=$remark;
						$model->LastModified=date('Y-m-d H:i:s');
						$model->ModifiedBy=$data['ModifiedBy'];
						$model->Status=$status;
						$model->RevNo=empty($model->RevNo)?$model->test->RevNo:$model->RevNo;
						$model->RevDate=empty($model->RevDate)?$model->test->RevDate:$model->RevDate;
						$model->FormatNo=empty($model->FormatNo)?$model->test->FormatNo:$model->FormatNo;
						$model->save(false);
						
						if(!empty($data['basic']['delobs']))
						{
							
							 foreach($data['basic']['delobs'] as $ob)
								  {
									if(!empty($ob['Id']))
									{
										$wob=Winclratbasic::model()->findByPk($ob['Id']);
										if(!empty($wob))
										{
											$wob->delete();
										}
									}
								  }		
							
						}
						
						$d=$data['basic']['obbasic'];
							
						if(!empty($d['Id']) || isset($d['Id']))
						{
								$sd=Winclratbasic::model()->findByPk($d['Id']);
						}
						else
						{
								$sd=new Winclratbasic;
								$sd->RirTestId=$model->getPrimaryKey();
								
						}	
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($sd->hasAttribute($var))
										$sd->$var = $value;
								  }		
							
						
							$sd->save(false);
							
							
							if(!empty($d['Observations']))
							{
								$avgthina=0;$avgthinb=0;$avgthinc=0;
								$avgthind=0;$avgthicka=0;$avgthickb=0;
								$avgthickc=0;$avgthickd=0;
								$sumthina=0;$sumthinb=0;$sumthinc=0;
								$sumthind=0;$sumthicka=0;$sumthickb=0;
								$sumthickc=0;$sumthickd=0;
								$counter=0;
								
								
								foreach($d['Observations'] as $o)
								{
									if(!empty($o['Id']) || isset($o['Id']))
											{
													$wob=Winclratobs::model()->findByPk($o['Id']);
											}
											else
											{
													$wob=new Winclratobs;
													$wob->WIRId=$sd->getPrimaryKey();													
											}		
									
											
												 foreach($o as $var=>$value)
												{
													// Does the model have this attribute? If not raise an error
													if($wob->hasAttribute($var))
													{
														$wob->$var = $value;
														
													}
													
													if($var==='ThinA')
													{
														$sumthina=$value+$sumthina;
													}
													if($var==='ThinB')
													{
														$sumthinb=$value+$sumthinb;
													}
													if($var==='ThinC')
													{
														$sumthinc=$value+$sumthinc;
													}
													if($var==='ThinD')
													{
														$sumthind=$value+$sumthind;
													}
													if($var==='ThickA')
													{
														$sumthicka=$value+$sumthicka;
													}
													if($var==='ThickB')
													{
														$sumthickb=$value+$sumthickb;
													}
													if($var==='ThickC')
													{
														$sumthickc=$value+$sumthickc;
													}
													if($var==='ThickD')
													{
														$sumthickd=$value+$sumthickd;
													}
														
												 }	
												 	$wob->save(false);
										 	
									$counter++;	
								}
							}
							
						/*$sd->AvgThinA=round(($sumthina/$counter),4);
						$sd->AvgThinB=round(($sumthinb/$counter),4);
						$sd->AvgThinC=round(($sumthinc/$counter),4);
						$sd->AvgThinD=round(($sumthind/$counter),4);
						$sd->AvgThickA=round(($sumthicka/$counter),4);
						$sd->AvgThickB=round(($sumthickb/$counter),4);
						$sd->AvgThickC=round(($sumthickc/$counter),4);
						$sd->AvgThickD=round(($sumthickd/$counter),4);		*/				
							
						$sd->save(false);
												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($data));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($data));
					}
			   
							break;	
							
			case 'kinclratingupdate':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						$remark="Failed";
						$status="pending";
						
						if($data['basic']['Remark'] ==='Passed')
							{
								$remark="Passed";
							}
						
						if($remark==='Failed')
						{
							$status="failed";
						}							
						if($remark==='Passed')
						{
							$status="complete";
						}
							
													
							$uir=Userapppermission::model()->find(array('condition'=>'UserId =:uid AND SectionId="3"',
							'params'=>array(':uid'=>$data['ModifiedBy']),));
							if(!empty($uir))
							{
								if($uir->C==='true')
								{
									$model->TestedBy=$data['ModifiedBy'];	
								}
							}		
							
						$model->TestDate=$data['basic']['TestDate'];
						$model->Note=$data['basic']['Note'];
						$model->Remark=$remark;
						$model->LastModified=date('Y-m-d H:i:s');
						$model->ModifiedBy=$data['ModifiedBy'];
						$model->Status=$status;
						$model->RevNo=empty($model->RevNo)?$model->test->RevNo:$model->RevNo;
						$model->RevDate=empty($model->RevDate)?$model->test->RevDate:$model->RevDate;
						$model->FormatNo=empty($model->FormatNo)?$model->test->FormatNo:$model->FormatNo;
						$model->save(false);
						
						
						if(!empty($data['basic']['delobs']))
						{
							
							 foreach($data['basic']['delobs'] as $ob)
								  {
									if(!empty($ob['Id']))
									{
										$kob=Kinclratobsbasic::model()->findByPk($ob['Id']);
										if(!empty($kob))
										{
											$kob->delete();
										}
									}
								  }		
							
						}
						
						$d=$data['basic']['obbasic'];
							
						if(!empty($d['Id']) || isset($d['Id']))
						{
								$sd=Kinclratbasic::model()->findByPk($d['Id']);
						}
						else
						{
								$sd=new Kinclratbasic;
								$sd->RirTestId=$model->getPrimaryKey();
								
						}	
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($sd->hasAttribute($var))
										$sd->$var = $value;
								  }		
							
						
							$sd->save(false);
							
							
							if(!empty($d['Observations']))
							{
								
								foreach($d['Observations'] as $o)
								{
									if(!empty($o['Id']) || isset($o['Id']))
											{
													$kob=Kinclratobsbasic::model()->findByPk($o['Id']);
											}
											else
											{
													$kob=new Kinclratobsbasic;
													$kob->KIRBId=$sd->getPrimaryKey();													
											}		
									
											$kob->SpecNo=$o['SpecNo'];
											$kob->AreaPol=$o['AreaPol'];
											$kob->save(false);
									
											foreach($o['obs'] as $i)
											{
								
												if(!empty($i['Id']) || isset($i['Id']))
												{
													$kobs=Kinclratobsobs::model()->findByPk($i['Id']);
												}
												else
												{
													$kobs=new Kinclratobsobs;
													$kobs->KIRObsBId=$kob->getPrimaryKey();													
												}				
										
										
												 foreach($i as $var=>$value)
												{
												
													// Does the model have this attribute? If not raise an error
													if($kobs->hasAttribute($var))
														$kobs->$var = $value;
												  }	
												  	$kobs->save(false);
										  }										  
									
								
								

										
								}
							}
							
						
						
												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($data));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($data));
					}
			   
							break;	
				
			case 'microcoatupdate':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						$remark="Failed";
						$status="pending";
						
						if($data['basic']['Remark'] ==='Passed')
							{
								$remark="Passed";
							}
						
						if($remark==='Failed')
						{
							$status="failed";
						}							
						if($remark==='Passed')
						{
							$status="complete";
						}
							
													
							$uir=Userapppermission::model()->find(array('condition'=>'UserId =:uid AND SectionId="3"',
							'params'=>array(':uid'=>$data['ModifiedBy']),));
							if(!empty($uir))
							{
								if($uir->C==='true')
								{
									$model->TestedBy=$data['ModifiedBy'];	
								}
							}		
							
						$model->TestDate=$data['basic']['TestDate'];
						$model->Note=$data['basic']['Note'];
						$model->Remark=$remark;
						$model->LastModified=date('Y-m-d H:i:s');
						$model->ModifiedBy=$data['ModifiedBy'];
						$model->Status=$status;
						$model->RevNo=empty($model->RevNo)?$model->test->RevNo:$model->RevNo;
						$model->RevDate=empty($model->RevDate)?$model->test->RevDate:$model->RevDate;
						$model->FormatNo=empty($model->FormatNo)?$model->test->FormatNo:$model->FormatNo;
						$model->save(false);
						
						$d=$data['basic']['obbasic'];
							
						if(!empty($d['Id']) || isset($d['Id']))
						{
								$sd=Microcoatbasic::model()->findByPk($d['Id']);
						}
						else
						{
								$sd=new Microcoatbasic;
								$sd->RirTestId=$model->getPrimaryKey();
								
						}	
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($sd->hasAttribute($var))
										$sd->$var = $value;
								  }		
							
						
							$sd->save(false);
							
						
						
												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($data));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($data));
					}
			   
							break;	
							
				case 'microcasedepthupdate':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						$remark="Failed";
						$status="pending";
						
						if($data['basic']['Remark'] ==='Passed')
							{
								$remark="Passed";
							}
						
						if($remark==='Failed')
						{
							$status="failed";
						}							
						if($remark==='Passed')
						{
							$status="complete";
						}
							
													
							$uir=Userapppermission::model()->find(array('condition'=>'UserId =:uid AND SectionId="3"',
							'params'=>array(':uid'=>$data['ModifiedBy']),));
							if(!empty($uir))
							{
								if($uir->C==='true')
								{
									$model->TestedBy=$data['ModifiedBy'];	
								}
							}		
							
						$model->TestDate=$data['basic']['TestDate'];
						$model->Note=$data['basic']['Note'];
						$model->Remark=$remark;
						$model->LastModified=date('Y-m-d H:i:s');
						$model->ModifiedBy=$data['ModifiedBy'];
						$model->Status=$status;
						$model->RevNo=empty($model->RevNo)?$model->test->RevNo:$model->RevNo;
						$model->RevDate=empty($model->RevDate)?$model->test->RevDate:$model->RevDate;
						$model->FormatNo=empty($model->FormatNo)?$model->test->FormatNo:$model->FormatNo;
						$model->save(false);
						
						$d=$data['basic']['obbasic'];
							
						if(!empty($d['Id']) || isset($d['Id']))
						{
								$sd=Microcasebasic::model()->findByPk($d['Id']);
						}
						else
						{
								$sd=new Microcasebasic;
								$sd->RirTestId=$model->getPrimaryKey();
								
						}	
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($sd->hasAttribute($var))
										$sd->$var = $value;
								  }		
							
						
							$sd->save(false);
							
						
						
												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($data));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($data));
					}
			   
							break;	
				case 'microdecarbupdate':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						$remark="Failed";
						$status="pending";
						
						if($data['basic']['Remark'] ==='Passed')
							{
								$remark="Passed";
							}
						
						if($remark==='Failed')
						{
							$status="failed";
						}							
						if($remark==='Passed')
						{
							$status="complete";
						}
							
													
							$uir=Userapppermission::model()->find(array('condition'=>'UserId =:uid AND SectionId="3"',
							'params'=>array(':uid'=>$data['ModifiedBy']),));
							if(!empty($uir))
							{
								if($uir->C==='true')
								{
									$model->TestedBy=$data['ModifiedBy'];	
								}
							}		
							
						$model->TestDate=$data['basic']['TestDate'];
						$model->Note=$data['basic']['Note'];
						$model->Remark=$remark;
						$model->LastModified=date('Y-m-d H:i:s');
						$model->ModifiedBy=$data['ModifiedBy'];
						$model->Status=$status;
						$model->RevNo=empty($model->RevNo)?$model->test->RevNo:$model->RevNo;
						$model->RevDate=empty($model->RevDate)?$model->test->RevDate:$model->RevDate;
						$model->FormatNo=empty($model->FormatNo)?$model->test->FormatNo:$model->FormatNo;
						$model->save(false);
						
						$d=$data['basic']['obbasic'];
							
						if(!empty($d['Id']) || isset($d['Id']))
						{
								$sd=Microdecarbbasic::model()->findByPk($d['Id']);
						}
						else
						{
								$sd=new Microdecarbbasic;
								$sd->RirTestId=$model->getPrimaryKey();
								
						}	
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($sd->hasAttribute($var))
										$sd->$var = $value;
								  }		
							
						
							$sd->save(false);
							
						
						
												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($data));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($data));
					}
			   
							break;	
				case 'microstructupdate':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						$remark="Failed";
						$status="pending";
						
						if($data['basic']['Remark'] ==='Passed')
							{
								$remark="Passed";
							}
						
						if($remark==='Failed')
						{
							$status="failed";
						}							
						if($remark==='Passed')
						{
							$status="complete";
						}
							
													
							$uir=Userapppermission::model()->find(array('condition'=>'UserId =:uid AND SectionId="3"',
							'params'=>array(':uid'=>$data['ModifiedBy']),));
							if(!empty($uir))
							{
								if($uir->C==='true')
								{
									$model->TestedBy=$data['ModifiedBy'];	
								}
							}		
							
						$model->TestDate=$data['basic']['TestDate'];
						$model->Note=$data['basic']['Note'];
						$model->Remark=$remark;
						$model->LastModified=date('Y-m-d H:i:s');
						$model->ModifiedBy=$data['ModifiedBy'];
						$model->Status=$status;
						$model->RevNo=empty($model->RevNo)?$model->test->RevNo:$model->RevNo;
						$model->RevDate=empty($model->RevDate)?$model->test->RevDate:$model->RevDate;
						$model->FormatNo=empty($model->FormatNo)?$model->test->FormatNo:$model->FormatNo;
						$model->save(false);
						
						$d=$data['basic']['obbasic'];
							
						if(!empty($d['Id']) || isset($d['Id']))
						{
								$sd=Microstructbasic::model()->findByPk($d['Id']);
						}
						else
						{
								$sd=new Microstructbasic;
								$sd->RirTestId=$model->getPrimaryKey();
								
						}	
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($sd->hasAttribute($var))
										$sd->$var = $value;
								  }		
							
						
							$sd->save(false);
							
						
						
												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($data));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($data));
					}
			   
							break;	
								
						
				
			case 'grainsizeupdate':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						$remark="Failed";
						$status="pending";
						
						if($data['basic']['Remark'] ==='Passed')
							{
								$remark="Passed";
							}
						
						if($remark==='Failed')
						{
							$status="failed";
						}							
						if($remark==='Passed')
						{
							$status="complete";
						}
							
													
							$uir=Userapppermission::model()->find(array('condition'=>'UserId =:uid AND SectionId="3"',
							'params'=>array(':uid'=>$data['ModifiedBy']),));
							if(!empty($uir))
							{
								if($uir->C==='true')
								{
									$model->TestedBy=$data['ModifiedBy'];	
								}
							}		
							
						$model->TestDate=$data['basic']['TestDate'];
						$model->Note=$data['basic']['Note'];
						$model->Remark=$remark;
						$model->LastModified=date('Y-m-d H:i:s');
						$model->ModifiedBy=$data['ModifiedBy'];
						$model->Status=$status;
						$model->RevNo=empty($model->RevNo)?$model->test->RevNo:$model->RevNo;
						$model->RevDate=empty($model->RevDate)?$model->test->RevDate:$model->RevDate;
						$model->FormatNo=empty($model->FormatNo)?$model->test->FormatNo:$model->FormatNo;
						$model->save(false);
						
						$d=$data['basic']['obbasic'];
							
						if(!empty($d['Id']) || isset($d['Id']))
						{
								$sd=Grainsizebasic::model()->findByPk($d['Id']);
						}
						else
						{
								$sd=new Grainsizebasic;
								$sd->RirTestId=$model->getPrimaryKey();
								
						}	
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($sd->hasAttribute($var))
										$sd->$var = $value;
								  }		
							
						
							$sd->save(false);
							
						
						
												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($data));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($data));
					}
			   
							break;	
			
	case 'threadupdate':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						$remark="Failed";
						$status="pending";
						
						if($data['basic']['Remark'] ==='Passed')
							{
								$remark="Passed";
							}
						
						if($remark==='Failed')
						{
							$status="failed";
						}							
						if($remark==='Passed')
						{
							$status="complete";
						}
							
													
							$uir=Userapppermission::model()->find(array('condition'=>'UserId =:uid AND SectionId="42"',
							'params'=>array(':uid'=>$data['ModifiedBy']),));
							if(!empty($uir))
							{
								if($uir->C==='true')
								{
									$model->TestedBy=$data['ModifiedBy'];	
								}
							}		
							
						$model->TestDate=$data['basic']['TestDate'];
						$model->Note=$data['basic']['Note'];
						$model->Remark=$remark;
						$model->LastModified=date('Y-m-d H:i:s');
						$model->ModifiedBy=$data['ModifiedBy'];
						$model->Status=$status;
						$model->RevNo=empty($model->RevNo)?$model->test->RevNo:$model->RevNo;
						$model->RevDate=empty($model->RevDate)?$model->test->RevDate:$model->RevDate;
						$model->FormatNo=empty($model->FormatNo)?$model->test->FormatNo:$model->FormatNo;
						$model->save(false);
						
						
						$d=$data['basic']['obbasic'];
							
						if(!empty($d['Id']) || isset($d['Id']))
						{
								$sd=Threadlapbasic::model()->findByPk($d['Id']);
						}
						else
						{
								$sd=new Threadlapbasic;
								$sd->RirTestId=$model->getPrimaryKey();
								
						}	
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($sd->hasAttribute($var))
										$sd->$var = $value;
								  }		
							
						
							$sd->save(false);
							
							
							
							
						$ob=$data['basic']['obbasic']['Thobs'];
						//$this->_sendResponse(401, CJSON::encode($ob));
						
						foreach($ob as $o)
						{
							
						if(!empty($o['Id']) || isset($o['Id']))
						{
								$obs=Threadlapobs::model()->findByPk($o['Id']);
						}
						else
						{
								$obs=new Threadlapobs;
								$obs->ThreadId=$sd->getPrimaryKey();
								
						}	
							//$this->_sendResponse(401, CJSON::encode("ob1"));
							 foreach($o as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($obs->hasAttribute($var))
										$obs->$var = $value;
								  }		
							
						
							$obs->save(false);
							
							//$this->_sendResponse(401, CJSON::encode("ob"));
						}	
						
												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($data));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($data));
					}
			   
							break;	
				
			
		 
			default:
			if($model->save(false))
				$this->_sendResponse(200, CJSON::encode($model));
			else
				// prepare the error $msg
				// see actionCreate
				// ...
				$msg="error";
				$this->_sendResponse(500, $msg );
			}
    }
	
	
    public function actionDelete()
    {
				switch($_GET['model'])
			{
				// Load the respective model
				
				
				
				case 'droptest':
					$model = Rirtestdetail::model()->findByPk($_GET['id']);                    
					break;
								
					
				default:
					$this->_sendResponse(501, 
						sprintf('Error: Mode <b>delete</b> is not implemented for model <b>%s</b>',
						$_GET['model']) );
					Yii::app()->end();
			}
			// Was a model found? If not, raise an error
			if($model === null)
				$this->_sendResponse(400, 
						sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
						$_GET['model'], $_GET['id']) );
		 
			// Delete the model
			$num = $model->delete();
			if($num>0)
				$this->_sendResponse(200, $num);    //this is the only way to work with backbone
			else
				$this->_sendResponse(500, 
						sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
						$_GET['model'], $_GET['id']) );
    }
	
	
	private function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
{
    // set the status
    $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
    header($status_header);
    // and the content type
    header('Content-type: ' . $content_type);
 
    // pages with body are easy
    if($body != '')
    {
        // send the body
        echo $body;
    }
    // we need to create the body if none is passed
    else
    {
        // create some body messages
        $message = '';
 
        // this is purely optional, but makes the pages a little nicer to read
        // for your users.  Since you won't likely send a lot of different status codes,
        // this also shouldn't be too ponderous to maintain
        switch($status)
        {
            case 401:
                $message = 'You must be authorized to view this page.';
                break;
            case 404:
                $message = 'The requested URL ' . $_SERVER['REQUEST_URI'] . ' was not found.';
                break;
            case 500:
                $message = 'The server encountered an error processing your request.';
                break;
            case 501:
                $message = 'The requested method is not implemented.';
                break;
        }
 
        // servers don't always have a signature turned on 
        // (this is an apache directive "ServerSignature On")
        $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];
 
        // this should be templated in a real-world solution
        $body = '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>' . $status . ' ' . $this->_getStatusCodeMessage($status) . '</title>
</head>
<body>
    <h1>' . $this->_getStatusCodeMessage($status) . '</h1>
    <p>' . $message . '</p>
    <hr />
    <address>' . $signature . '</address>
</body>
</html>';
 
        echo $body;
    }
    Yii::app()->end();
}
	
	
	
	       private function _getStatusCodeMessage($status)
{
    // these could be stored in a .ini file and loaded
    // via parse_ini_file()... however, this will suffice
    // for an example
    $codes = Array(
        200 => 'OK',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
    );
    return (isset($codes[$status])) ? $codes[$status] : '';
}


private function _checkAuth()
{
    // Check if we have the USERNAME and PASSWORD HTTP headers set?
    if(!(isset($_SERVER['HTTP_X_USERNAME']) and isset($_SERVER['HTTP_X_PASSWORD']))) {
        // Error: Unauthorized
        $this->_sendResponse(401);
    }
    $username = $_SERVER['HTTP_X_USERNAME'];
    $password = $_SERVER['HTTP_X_PASSWORD'];
    // Find the user
    $user=User::model()->find('LOWER(username)=?',array(strtolower($username)));
    if($user===null) {
        // Error: Unauthorized
        $this->_sendResponse(401, 'Error: User Name is invalid');
    } else if(!$user->validatePassword($password)) {
        // Error: Unauthorized
        $this->_sendResponse(401, 'Error: User Password is invalid');
    }
}
	
}

?>
