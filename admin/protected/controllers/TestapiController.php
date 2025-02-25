<?php

class ApiController extends Controller
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
 
 
 
public function actionLogin()
	{
		
		switch($_GET['model'])
		    {

			case 'user':	$model= new LoginForm;
					 break;				 
							 
			default:
			    $this->_sendResponse(501, 
				sprintf('Mode <b>login</b> is not implemented for model <b>%s</b>',
				$_GET['model']) );
				Yii::app()->end();
		    }

		
			 $post = file_get_contents("php://input");
                         $data = CJSON::decode($post, true);
		         $model->attributes = $data;

			

		// collect user input data
		if($model)
		{
			
			$model->RememberMe=true;
	
		
			// validate user input and redirect to the previous page if valid
				if($model->validate() && $model->login())
				{
				
				$u = Users::model()->findbyPk(Yii::app()->user->getId());
					if(!empty($u))
					{
						$user=(object)array('uid'=>$u->Id,'username'=>$u->Username,'roleid'=>$u->userinroles[0]->RoleId, 'role'=>$u->userinroles[0]->role->Role,'email'=>$u->Email);	
					
						$role=Yii::app()->user->role;
						
							Yii::app()->session['Username']=$user->username;
							//Yii::app()->session['Password']=$user->password;		
						
							$this->_sendResponse(200, CJSON::encode($user));	
						
					}
				else
					{
						 $this->_sendResponse(401, CJSON::encode(Yii::app()->user->getId()));
					}
				}
			else
				{
					
						$record=Users::model()->findByAttributes(array('Username'=>$model->Username));
        if($record===null)
           $this->_sendResponse(401, CJSON::encode($model));
        else if($record->Password!==($model->Password))
            $this->_sendResponse(401, CJSON::encode("Invalid Password"));
				
				}
		}
		else
		{
			 $this->_sendResponse(401, 'Error: UserName and Password is incorrect');
		}
		// display the login form
		
	}
 
    // Actions
    public function actionList()
    {
				
			// Get the respective model instance
			switch($_GET['model'])
			{
			
				case 'customer':
					$models=Customerinfo::model()->findAll();
				break;
				
				case 'droptemp':
					$models=Dropdowntemp::model()->findAll();
				break;
				
				
				case 'ssthr':
					$models=Ssthours::model()->findAll();
				break;

				case 'dropload':
					$loads=Dropdownload::model()->findAll();
					$allloads=array();
					foreach($loads as $l)
					{
						$allloads[]=(object)array('Id'=>$l->Id,'Load'=>$l->Load,'HtypeId'=>$l->HtypeId,'Htype'=>$l->htype->Type);
					}
					$htypes=Hardnesstype::model()->findAll();
					$data=(object)array('loads'=>$allloads,'htypes'=>$htypes);
					$this->_sendResponse(200, CJSON::encode($data));	
				break;

				case 'methods':
					$methods=Testmethods::model()->findAll();
					$allmethods=array();
					foreach($methods as $l)
					{
						if($l->TypeId !=0)
						{
							$type=Testtypes::model()->findByPk($l->TypeId);
								$allmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'TypeId'=>$l->TypeId,'Type'=>$type->Type);
						}
						else
						{
							$allmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName);
						}
					}
					$tests=Tests::model()->findAll();
					$types=Testtypes::model()->findAll();
					$data=(object)array('methods'=>$allmethods,'tests'=>$tests,'types'=>$types);
					$this->_sendResponse(200, CJSON::encode($data));	
				break;
			
				case 'users':
					$allusers=Users::model()->findAll();
					$models=array();
					foreach($allusers as $u)
					{
						$models[]=(object)array('Id'=>$u->Id,'FName'=>$u->FName,'MName'=>$u->MName,'LName'=>$u->LName,'Email'=>$u->Email,'ContactNo'=>$u->ContactNo,
						'Role'=>$u->userinroles[0]->role->Role,'Department'=>$u->Department);
					}
					$this->_sendResponse(200, CJSON::encode($models));		
				break;
				case 'preuserdata':
					$allusers=Users::model()->findAll();
					$models=array();
					foreach($allusers as $u)
					{
						$models[]=(object)array('Email'=>$u->Email);
					}
					
					$roles=Roles::model()->findAll();
					$data=(object)array('Users'=>$models,'Roles'=>$roles);
					$this->_sendResponse(200, CJSON::encode($data));		
				break;
				case 'mdsdata':
					$allmds=Mds::model()->findAll();
					$models=array();
					foreach($allmds as $m)
					{
						$models[]=(object)array('Id'=>$m->Id,'MdsNo'=>$m->MdsNo,'Material'=>$m->Material,'Standard'=>$m->Standard,'Size'=>$m->Size,
						'Remark'=>$m->Remark,'Uploads'=>$m->mdsuploads);
					}
					$this->_sendResponse(200, CJSON::encode($models));	
				break;
				
				case 'tdsdata':
					$allmds=Tds::model()->findAll();
					$models=array();
					foreach($allmds as $m)
					{
						$models[]=(object)array('Id'=>$m->Id,'TdsNo'=>$m->TdsNo,'Material'=>$m->Material,'Standard'=>$m->Standard,'Size'=>$m->Size,
						'Remark'=>$m->Remark,'Uploads'=>$m->tdsuploads);
					}
					$this->_sendResponse(200, CJSON::encode($models));	
				break;
				
				case 'mechpredata':
						$props=Mechproperties::model()->findAll();
						
						$allstds=Standards::model()->findAll();
						
						
							$allprops=array();			 
					foreach($props as $e)
					{
						$allprops[]=(object)array('MPId'=>$e->Id,'MechProperty'=>$e->MechProperty);
						
					}
					
					$data=(object)array('allprops'=>$allprops,'standards'=>$allstds);
					$this->_sendResponse(200, CJSON::encode($data));		
					
				break;
				case 'mechdata':
					$allmbs=Mechbasic::model()->findAll();
					
					$alldata=array();
					foreach($allmbs as $m)
					{
						
						
						$properties=array();
						if(!empty($m->mechdetails))
						{
						
						
						$type=$m->standard->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$m->Grade." ".$m->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$m->Class;
						}
						
						if($type==='ISO')
						{
							$substandard=$m->Class." ".$m->Property;
						}
						
						if($type==='ASTM')
						{
							$substandard=$m->Grade." ".$m->UNS." ".$m->Material;
						}
						
											
							$alldata[]=(object)array('Id'=>$m->Id,'Standard'=>$m->standard->Standard,'SubStandard'=>$substandard,'dm'=>$m->Diameter, 'details'=>$m->mechdetails[0]);
						}
						
					}
					
					$this->_sendResponse(200, CJSON::encode($alldata));				 
					break;
				
				
				
				case 'concntpredata':
						$ecrs=Elements::model()->findAll();
						
						$allstds=Standards::model()->findAll();
						
						
							$allecrs=array();			 
					foreach($ecrs as $e)
					{
						$allecrs[]=(object)array('ElementId'=>$e->Id,'Element'=>$e->Symbol,'Max'=>"",'Min'=>"");
						
					}
					
					$data=(object)array('allelements'=>$allecrs,'standards'=>$allstds);
					$this->_sendResponse(200, CJSON::encode($data));		
					
				break;
				case 'concntdata':
					$allecrs=Chemicalbasic::model()->findAll();
					
					$alldata=array();
					foreach($allecrs as $sub)
					{
						
						
						$elements=array();
						if(!empty($sub->chemicalcompositions))
						{
						foreach($sub->chemicalcompositions as $e)
						{
							$elements[]=(object)array('Id'=>$e->Id,'Element'=>$e->element->Symbol,'ElementId'=>$e->ElementId,'Min'=>$e->Min,'Max'=>$e->Max,
											'CBId'=>$e->CBId);
						}
						
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						
											
							$alldata[]=(object)array('Id'=>$sub->Id,'Standard'=>$sub->standard->Standard,'SubStandard'=>$substandard, 'Elements'=>$elements);
						}
						
					}
					
					$this->_sendResponse(200, CJSON::encode($alldata));				 
					break;
				
				case 'stdsubdata':
					$allsubstds=Stdsub::model()->findAll();
					
					$allsubs=array();
					foreach($allsubstds as $s)
					{
												
						$allsubs[]=(object)array('Id'=>$s->Id,'SubStandard'=>$s->SubStandard,'Standard'=>$s->standard->Standard,
							'StandardId'=>$s->StandardId,'Type'=>$s->standard->Type);
					}
					$this->_sendResponse(200, CJSON::encode($allsubs));				 
					
					break;
				
				case 'stddata':
					$models=Standards::model()->findAll();
							 
					
					break;
					
				case 'externaldata':
							$rtds=Rirexttestdetail::model()->findAll();
							
							foreach($rtds as $r)
							{
								$rirs[]=$r->ReceiptirId;
							}
							
							$rirs = array_unique($rirs);
										 $allrirs=array();
							foreach($rirs as $t)
							{			 
							
							$d=Receiptir::model()->findByPk($t);
							$mds="";$tds="";
							if($d->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->TdsNo);
								$tds=$t->TdsNo;
							}
							
								$extests=Rirexttestdetail::model()->findAll(array('condition'=>'ReceiptirId=:ID',
										 'params'=>array(':ID'=>$t),));
								
								$testname=$extests;			
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->RirNo,'PartName'=>$d->PartName,
								'LabNo'=>$d->LabNo,'RefPurchaseOrder'=>$d->RefPurchaseOrder,
								'Supplier'=>$d->Supplier,'GrinNo'=>$d->GrinNo,'Quantity'=>$d->Quantity,
								'HeatNo'=>$d->HeatNo,'BatchNo'=>$d->BatchNo,'NoType'=>$d->NoType,'MdsNo'=>$d->MdsNo,
								'TdsNo'=>$d->TdsNo,'Mds'=>$mds,'Tds'=>$tds,'TestNames'=>$testname,'Status'=>"pending",
								'BatchCode'=>$d->BatchCode,	'MaterialCondition'=>$d->MaterialCondition,'MaterialGrade'=>$d->MaterialGrade);

							}
							$this->_sendResponse(200, CJSON::encode($allrirs));	
							break;			 
																	 
					
				case 'proofloaddata':
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:ID',
										 'params'=>array(':ID'=>"8"),));
										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							$mds="";$tds="";
							if($d->receiptir->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->receiptir->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->receiptir->TdsNo);
								$tds=$t->TdsNo;
							}
							
						$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
							$md=$sub->mechdetails[0];
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						$std=$sub->standard->Standard." ".$substandard;
										 
										 
						
									
							// $impcond=(object)array('AtTemp'=>$iattemp,'Min'=>$imin,'Max'=>$imax,'Unit'=>$iunit);
							
							$impcond="";					
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->receiptir->RirNo,'PartName'=>$d->receiptir->PartName,'ReceiptirId'=>$d->ReceiptirId,
								'LabNo'=>$d->receiptir->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->receiptir->RefPurchaseOrder,'TestMethod'=>$d->TestMethod,
								'Supplier'=>$d->receiptir->Supplier,'GrinNo'=>$d->receiptir->GrinNo,'Quantity'=>$d->receiptir->Quantity,'Status'=>$d->Status,
								'HeatNo'=>$d->receiptir->HeatNo,'BatchNo'=>$d->receiptir->BatchNo,'NoType'=>$d->receiptir->NoType,'MdsNo'=>$d->receiptir->MdsNo,
								'TdsNo'=>$d->receiptir->TdsNo,'Mds'=>$mds,'Tds'=>$tds,'Standard'=>$std,'cond'=>$impcond,'observations'=>$d->proofloadbservations,
								'StandardId'=>$sub->StandardId,'SubStandardId'=>$sub->Id,'BatchCode'=>$d->receiptir->BatchCode,'Description'=>$d->Description,
								'MaterialCondition'=>$d->receiptir->MaterialCondition,'MaterialGrade'=>$d->receiptir->MaterialGrade,'ReqDate'=>$d->ReqDate);

							}
							$this->_sendResponse(200, CJSON::encode($allrirs));	
							break;
						
				
				case 'sstdata':
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:ID',
										 'params'=>array(':ID'=>"9"),));
										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							$mds="";$tds="";
							if($d->receiptir->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->receiptir->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->receiptir->TdsNo);
								$tds=$t->TdsNo;
							}
							
						$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
							$md=$sub->mechdetails[0];
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						$std=$sub->standard->Standard." ".$substandard;
										 
										 
						
									
							// $impcond=(object)array('AtTemp'=>$iattemp,'Min'=>$imin,'Max'=>$imax,'Unit'=>$iunit);
							
							$impcond="";					
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->receiptir->RirNo,'PartName'=>$d->receiptir->PartName,'ReceiptirId'=>$d->ReceiptirId,
								'LabNo'=>$d->receiptir->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->receiptir->RefPurchaseOrder,'TestMethod'=>$d->TestMethod,
								'Supplier'=>$d->receiptir->Supplier,'GrinNo'=>$d->receiptir->GrinNo,'Quantity'=>$d->receiptir->Quantity,'Status'=>$d->Status,
								'HeatNo'=>$d->receiptir->HeatNo,'BatchNo'=>$d->receiptir->BatchNo,'NoType'=>$d->receiptir->NoType,'MdsNo'=>$d->receiptir->MdsNo,
								'TdsNo'=>$d->receiptir->TdsNo,'Mds'=>$mds,'Tds'=>$tds,'Standard'=>$std,'cond'=>$impcond,'observations'=>$d->sstobservations,
								'StandardId'=>$sub->StandardId,'SubStandardId'=>$sub->Id,'BatchCode'=>$d->receiptir->BatchCode,'Description'=>$d->Description,
								'MaterialCondition'=>$d->receiptir->MaterialCondition,'MaterialGrade'=>$d->receiptir->MaterialGrade,'ReqDate'=>$d->ReqDate);

							}
							$this->_sendResponse(200, CJSON::encode($allrirs));	
							break;
					
					
				case 'tensiondata':
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:ID',
										 'params'=>array(':ID'=>"11"),));
										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							$mds="";$tds="";
							if($d->receiptir->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->receiptir->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->receiptir->TdsNo);
								$tds=$t->TdsNo;
							}
							
						$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
							$md=$sub->mechdetails[0];
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						$std=$sub->standard->Standard." ".$substandard;
										 
										 
						
									
							// $impcond=(object)array('AtTemp'=>$iattemp,'Min'=>$imin,'Max'=>$imax,'Unit'=>$iunit);
							
							$impcond="";					
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->receiptir->RirNo,'PartName'=>$d->receiptir->PartName,'ReceiptirId'=>$d->ReceiptirId,
								'LabNo'=>$d->receiptir->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->receiptir->RefPurchaseOrder,'TestMethod'=>$d->TestMethod,
								'Supplier'=>$d->receiptir->Supplier,'GrinNo'=>$d->receiptir->GrinNo,'Quantity'=>$d->receiptir->Quantity,'Status'=>$d->Status,
								'HeatNo'=>$d->receiptir->HeatNo,'BatchNo'=>$d->receiptir->BatchNo,'NoType'=>$d->receiptir->NoType,'MdsNo'=>$d->receiptir->MdsNo,
								'TdsNo'=>$d->receiptir->TdsNo,'Mds'=>$mds,'Tds'=>$tds,'Standard'=>$std,'cond'=>$impcond,'observations'=>$d->tortensobservations,
								'StandardId'=>$sub->StandardId,'SubStandardId'=>$sub->Id,'BatchCode'=>$d->receiptir->BatchCode,'Description'=>$d->Description,
								'MaterialCondition'=>$d->receiptir->MaterialCondition,'MaterialGrade'=>$d->receiptir->MaterialGrade,'ReqDate'=>$d->ReqDate);

							}
							$this->_sendResponse(200, CJSON::encode($allrirs));	
							break;
					
				
				case 'tensiledata':
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:ID',
										 'params'=>array(':ID'=>"10"),));
										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							$mds="";$tds="";
							if($d->receiptir->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->receiptir->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->receiptir->TdsNo);
								$tds=$t->TdsNo;
							}
							
						$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
							$md=$sub->mechdetails[0];
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						$std=$sub->standard->Standard." ".$substandard;
										 
						
						
												
						$tenscond=(object)array('F_Min'=>$md->F_Min,'F_Max'=>$md->F_Max,'EX_Min'=>$md->EX_Min,'EX_Max'=>$md->EX_Max,'TS_Min'=>$md->TS_Min,'TS_Max'=>$md->TS_Max,
						'EL_Min'=>$md->EL_Min,'EL_Max'=>$md->EL_Max,'RA_Min'=>$md->RA_Min,'RA_Max'=>$md->RA_Max,'YM_Min'=>$md->YM_Min,'YM_Max'=>$md->YM_Max,
						'YS_Min'=>$md->YS_Min,'YS_Max'=>$md->YS_Max,'PS_Min'=>$md->PS_Min,'PS_Max'=>$md->PS_Max,'UTS_Min'=>$md->UTS_Min,'UTS_Max'=>$md->UTS_Max,);
							
							
							
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->receiptir->RirNo,'PartName'=>$d->receiptir->PartName,'ReceiptirId'=>$d->ReceiptirId,
								'LabNo'=>$d->receiptir->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->receiptir->RefPurchaseOrder,'TestMethod'=>$d->TestMethod,
								'Supplier'=>$d->receiptir->Supplier,'GrinNo'=>$d->receiptir->GrinNo,'Quantity'=>$d->receiptir->Quantity,'Status'=>$d->Status,
								'HeatNo'=>$d->receiptir->HeatNo,'BatchNo'=>$d->receiptir->BatchNo,'NoType'=>$d->receiptir->NoType,'MdsNo'=>$d->receiptir->MdsNo,
								'TdsNo'=>$d->receiptir->TdsNo,'Mds'=>$mds,'Tds'=>$tds,'Standard'=>$std,'cond'=>$tenscond,'StandardId'=>$sub->StandardId,
								'SubStandardId'=>$sub->Id,'BatchCode'=>$d->receiptir->BatchCode,'Description'=>$d->Description,'properties'=>$d->tensileobservations,
								'MaterialCondition'=>$d->receiptir->MaterialCondition,'MaterialGrade'=>$d->receiptir->MaterialGrade,'ReqDate'=>$d->ReqDate);

							}
							$this->_sendResponse(200, CJSON::encode($allrirs));					 
								break;
								
			case 'impactdata':
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:ID',
										 'params'=>array(':ID'=>"3"),));
										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							$mds="";$tds="";
							if($d->receiptir->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->receiptir->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->receiptir->TdsNo);
								$tds=$t->TdsNo;
							}
							
						$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
							$md=$sub->mechdetails[0];
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						$std=$sub->standard->Standard." ".$substandard;
										 
										 
						
							$t=Dropdowntemp::model()->findByPk($d->TempId);
							
						$imin="";
$imax="";
$iunit="";	
$iattemp=	$t->Temp;				
	$iunit="";						
						
							$imin=$md->I_Min;
							$imax=$md->I_Max;
						
												
							$impcond=(object)array('AtTemp'=>$iattemp,'Min'=>$imin,'Max'=>$imax,'Unit'=>$iunit);
							
												
												
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->receiptir->RirNo,'PartName'=>$d->receiptir->PartName,'ReceiptirId'=>$d->ReceiptirId,
								'LabNo'=>$d->receiptir->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->receiptir->RefPurchaseOrder,'TestMethod'=>$d->TestMethod,
								'Supplier'=>$d->receiptir->Supplier,'GrinNo'=>$d->receiptir->GrinNo,'Quantity'=>$d->receiptir->Quantity,'Status'=>$d->Status,
								'HeatNo'=>$d->receiptir->HeatNo,'BatchNo'=>$d->receiptir->BatchNo,'NoType'=>$d->receiptir->NoType,'MdsNo'=>$d->receiptir->MdsNo,
								'TdsNo'=>$d->receiptir->TdsNo,'Mds'=>$mds,'Tds'=>$tds,'Standard'=>$std,'cond'=>$impcond,'observations'=>$d->impactobservations,
								'StandardId'=>$sub->StandardId,'SubStandardId'=>$sub->Id,'BatchCode'=>$d->receiptir->BatchCode,'Description'=>$d->Description,
								'MaterialCondition'=>$d->receiptir->MaterialCondition,'MaterialGrade'=>$d->receiptir->MaterialGrade,'ReqDate'=>$d->ReqDate);

							}
							$this->_sendResponse(200, CJSON::encode($allrirs));				
								break;
				
								
				case 'hardnessdata':
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:ID',
										 'params'=>array(':ID'=>"2"),));
										 $allrirs=array();
							foreach($rtds as $d)
							{			 
							$mds="";$tds="";
							if($d->receiptir->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->receiptir->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->receiptir->TdsNo);
								$tds=$t->TdsNo;
							}
							
						$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
							$md=$sub->mechdetails[0];
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						$std=$sub->standard->Standard." ".$substandard;
										 
										 
						$h=Hardnesstype::model()->findByPk($d->HtypeId);
							$l=Dropdownload::model()->findByPk($d->LoadId);
							
						$hmin="";
$hmax="";
$hunit="";	
$hload=	$l->Load;				
							
						if($d->HtypeId==='1')
						{
							$hmin=$md->HBW_Min;
							$hmax=$md->HBW_Max;
							$hunit="HBW";
						}
						if($d->HtypeId==='2')
						{
							$hmin=$md->HV_Min;$hmax=$md->HV_Max;$hunit="HV";
						}	
						if($d->HtypeId==='3')
						{
							$hmin=$md->MicroHV_Min;$hmax=$md->MicroHV_Max;$hunit="HV";
						}	
						if($d->HtypeId==='4')
						{
							$hmin=$md->HRB_Min;$hmax=$md->HRB_Max;$hunit="HRB";
						}	
						if($d->HtypeId==='5')
						{
							$hmin=$md->HRC_Min;$hmax=$md->HRC_Max;$hunit="HRC";
						}							
												
							$hardcond=(object)array('Hardness'=>$h->Type,'HtypeId'=>$d->HtypeId,'Load'=>$hload,'Min'=>$hmin,'Max'=>$hmax,'Unit'=>$hunit);
							
												
							
							
								
									$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->receiptir->RirNo,'PartName'=>$d->receiptir->PartName,'ReceiptirId'=>$d->ReceiptirId,
								'LabNo'=>$d->receiptir->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->receiptir->RefPurchaseOrder,'TestMethod'=>$d->TestMethod,
								'Supplier'=>$d->receiptir->Supplier,'GrinNo'=>$d->receiptir->GrinNo,'Quantity'=>$d->receiptir->Quantity,'Status'=>$d->Status,
								'HeatNo'=>$d->receiptir->HeatNo,'BatchNo'=>$d->receiptir->BatchNo,'NoType'=>$d->receiptir->NoType,'MdsNo'=>$d->receiptir->MdsNo,
								'TdsNo'=>$d->receiptir->TdsNo,'Mds'=>$mds,'Tds'=>$tds,'Standard'=>$std,'cond'=>$hardcond,'StandardId'=>$sub->StandardId,
								'SubStandardId'=>$sub->Id,'BatchCode'=>$d->receiptir->BatchCode,'Description'=>$d->Description,'observations'=>$d->hardobservations,
								'MaterialCondition'=>$d->receiptir->MaterialCondition,'MaterialGrade'=>$d->receiptir->MaterialGrade,'ReqDate'=>$d->ReqDate);

							}
							$this->_sendResponse(200, CJSON::encode($allrirs));				 
								break;
				
				
				case 'chemicaldata':
							$rtds=Rirtestdetail::model()->findAll(array('condition'=>'TestId=:ID',
										 'params'=>array(':ID'=>"1"),));
										 $allrirs=array();
							foreach($rtds as $d)
							{
								
								$sds=array();
									 
									foreach($d->spectrodetails as $e)
									{
										$cr=Chemicalcomposition::model()->find(array('condition'=>'CBId=:ID AND ElementId=:eid',
												 'params'=>array(':ID'=>$d->SubStandardId,':eid'=>$e->ElementId),));
										
										$sds[]=(object)array('Id'=>$e->Id,'ElementId'=>$e->ElementId,'Element'=>$cr->element->Symbol,'Min'=>$cr->Min,'Max'=>$cr->Max,'Value'=>$e->Value,
										'LimitOut'=>$e->LimitOut,'RirTestId'=>$e->RirTestId);
									}		
								
								
								
									$mds="";$tds="";
							if($d->receiptir->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->receiptir->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->receiptir->TdsNo);
								$tds=$t->TdsNo;
							}
							
						$sub=Chemicalbasic::model()->findByPk($d->SubStandardId);	
							
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						$std=$sub->standard->Standard." ".$substandard;
							
							
								
								
								$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->receiptir->RirNo,'PartName'=>$d->receiptir->PartName,'ReceiptirId'=>$d->ReceiptirId,
								'LabNo'=>$d->receiptir->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->receiptir->RefPurchaseOrder,
								'Supplier'=>$d->receiptir->Supplier,'GrinNo'=>$d->receiptir->GrinNo,'Quantity'=>$d->receiptir->Quantity,'Status'=>$d->Status,
								'HeatNo'=>$d->receiptir->HeatNo,'BatchNo'=>$d->receiptir->BatchNo,'NoType'=>$d->receiptir->NoType,'MdsNo'=>$d->receiptir->MdsNo,
								'TdsNo'=>$d->receiptir->TdsNo,'Mds'=>$mds,'Tds'=>$tds,'Standard'=>$std,'BatchCode'=>$d->receiptir->BatchCode,'spectrodetails'=>$sds,
								'StandardId'=>$sub->StandardId,'SubStandardId'=>$sub->Id,'Description'=>$d->Description,'Remark'=>$d->Remark,'LastModified'=>$d->LastModified,
								'MaterialCondition'=>$d->receiptir->MaterialCondition,'MaterialGrade'=>$d->receiptir->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'TestMethod'=>$d->TestMethod);

								
								
								
							}
							$this->_sendResponse(200, CJSON::encode($allrirs));				 
								break;
								
				
				case 'stdpredata':
									
						$models=Stdtype::model()->findAll();
								
					//	$model=(object)array('tests'=>$tests);		
							
						break;
						
				case 'stdsubpredata':
							$models=array();		
						$allstds=Standards::model()->findAll();
							foreach($allstds as $s)
							{							
					$models[]=(object)array('Id'=>$s->Id,'Standard'=>$s->Standard,'Type'=>$s->Type);		
							}		
						$this->_sendResponse(200, CJSON::encode($models));										
						break;
								
				case 'rirdata':
					$rirs=Receiptir::model()->findAll();
					$allrirs=array();
					foreach($rirs as $r)
					{
						$allrirs[]=(object)array('Id'=>$r->Id,'RirNo'=>$r->RirNo,'Supplier'=>$r->Supplier,
						'PartName'=>$r->PartName,'MaterialCondition'=>$r->MaterialCondition,'MdsNo'=>$r->MdsNo,'TdsNo'=>$r->TdsNo,
						'GrinNo'=>$r->GrinNo,'Quantity'=>$r->Quantity,'BatchNo'=>$r->BatchNo,
						'HeatNo'=>$r->HeatNo,'RefPurchaseOrder'=>$r->RefPurchaseOrder,'MaterialGrade'=>$r->MaterialGrade,'LabNo'=>$r->LabNo,'alltests'=>$r->rirtestdetails);
					}
					
					
					$data=(object)array('allrirs'=>$allrirs);
						$this->_sendResponse(200, CJSON::encode($data));	
					break;
				
				case 'rirpredata':
					$alltests=Tests::model()->findAll();
					
					$allexttests=Externaltests::model()->findAll();
					$tests=array();
					foreach($alltests as $b)
					{
						$tests[]=(object)array('Id'=>$b->Id,'TestName'=>$b->TestName,'Keyword'=>$b->Keyword,'Applicable'=>"false",'Method'=>"",'ReportNo'=>"",'ReqDate'=>"");
					}
					
					$standards=Standards::model()->findAll();
					$allstds=array();
					$csubstd=array();
					$msubstd=array();
					foreach($standards as $s)
					{
						
						foreach($s->chemicalbasics as $c)
						{
						
						$type=$s->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$c->Grade." ".$c->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$c->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$c->Class." ".$c->Property;
						}
						
						$csubstd[]=(object)array('Id'=>$c->Id,'SubStandard'=>$substandard,'StandardId'=>$s->Id,'Standard'=>$s->Standard);
						}
						
						
						foreach($s->mechbasics as $m)
						{
						
						$type=$s->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$m->Grade." ".$m->Number." ( ".$m->Diameter.") diameter";
						}
						if($type==='ASTM')
						{
							$substandard=$m->Grade." ( ".$m->Diameter.") diameter";
						}
						
						if($type==='ISO')
						{
							$substandard=$m->Class." ".$m->Property." ( ".$m->Diameter.") diameter";
						}
						
						$msubstd[]=(object)array('Id'=>$m->Id,'SubStandard'=>$substandard,'StandardId'=>$s->Id,'Standard'=>$s->Standard,);
						}
						
						$allstds[]=(object)array('Id'=>$s->Id,'Standard'=>$s->Standard);
					}
					
					$methods=Testmethods::model()->findAll();
					$alltestmethods=array();
					foreach($methods as $l)
					{
						if($l->TypeId !=0)
						{
							$type=Testtypes::model()->findByPk($l->TypeId);
								$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'TypeId'=>$l->TypeId,'Type'=>$type->Type);
						}
						else
						{
							$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName);
						}
					}
					
					
					
					$htypes=Hardnesstype::model()->findAll();
					$dropload=Dropdownload::model()->findAll();
					$droptemps=Dropdowntemp::model()->findAll();
					$mdsnos=Mds::model()->findAll();
					$tdsnos=Tds::model()->findAll();
					$ssthours=Ssthours::model()->findAll();
					
					$data=(object)array('alltests'=>$tests,'standards'=>$allstds,'Csubstandards'=>$csubstd,'Msubstandards'=>$msubstd,'Htypes'=>$htypes,
					'Hardloads'=>$dropload,'Impacttemps'=>$droptemps,'mdsnos'=>$mdsnos,'tdsnos'=>$tdsnos,'allexttests'=>$allexttests,'ssthours'=>$ssthours,
					'testmethods'=>$alltestmethods);
						$this->_sendResponse(200, CJSON::encode($data));	
					break;
				
				case 'users':
					$models = Users::model()->findAll();
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
				
				
			case 'edituser':
					$allusers=Users::model()->findAll(array('condition'=>'Id !=:uid ',
										 'params'=>array(':uid'=>$_GET['id']),));
					$models=array();
					foreach($allusers as $u)
					{
						$models[]=(object)array('Email'=>$u->Email);
					}
					$u =Users::model()->findByPk($_GET['id']);
					$user=(object)array('Id'=>$u->Id,'Email'=>$u->Email,'ContactNo'=>$u->ContactNo,'FName'=>$u->FName,'MName'=>$u->MName,'LName'=>$u->LName,
					'Role'=>$u->userinroles[0]->RoleId);
					$roles=Roles::model()->findAll();
					$model=(object)array('User'=>$user,'Users'=>$models,'Roles'=>$roles);
						
				break;	
				
				
				
			case 'mdsedit':
					$mds =Mds::model()->findByPk($_GET['id']);
					
					$model=(object)array('mds'=>$mds);
			break;
			
			case 'tdsedit':
					$tds =Tds::model()->findByPk($_GET['id']);
					
					$model=(object)array('tds'=>$tds);
			break;
			
			case 	'mechedit':
						$standards=Standards::model()->findAll();
						$mech=Mechbasic::model()->findByPk($_GET['id']);
						$details=array();
					
						
						$mech=(object)array('basic'=>$mech,'details'=>$mech->mechdetails[0]);
					
					
					
						$model=(object)array('standards'=>$standards,'mech'=>$mech);
						
						break;

			case 	'concntedit':
						$standards=Standards::model()->findAll();
						$stdsub=Chemicalbasic::model()->findByPk($_GET['id']);
						$elements=array();
						foreach($stdsub->chemicalcompositions as $e)
						{
							$elements[]=(object)array('Id'=>$e->Id,'Element'=>$e->element->Symbol,'ElementId'=>$e->ElementId,'Min'=>$e->Min,'Max'=>$e->Max,
											'CBId'=>$e->CBId);
						}
					
						
						$range=(object)array('Id'=>$stdsub->Id,'StandardId'=>$stdsub->StandardId,'Grade'=>$stdsub->Grade,'Number'=>$stdsub->Number,
						'Class'=>$stdsub->Class,'Property'=>$stdsub->Property,'allelements'=>$elements,'Type'=>$stdsub->Type);
					
					
					
						$model=(object)array('standards'=>$standards,'range'=>$range);
						
						break;

			case 'stdsubedit':
					$std = Stdsub::model()->findByPk($_GET['id']);
					
					$allstds=Standards::model()->findAll();
					
				
					$model=(object)array('std'=>$std,'allstds'=>$allstds);
				break;			
						
			case 'stdedit':
					$std = Standards::model()->findByPk($_GET['id']);
					// $tests=array();
					// foreach($s->standardsfortests as $sft)
					// {
						// $tests[]=$sft->test;
					// }
					$alltypes=Stdtype::model()->findAll();
					
					//$std=(object)array('Id'=>$s->Id,'Standard'=>$s->Standard,'Description'=>$s->Description,'Type'=>$alltypes);
				
					$model=(object)array('std'=>$std,'alltypes'=>$alltypes);
				break;
				
				
case 'externaleditdata':
							$rextds=Rirexttestdetail::model()->findAll(array('condition'=>'ReceiptirId=:rno',
										 'params'=>array(':rno'=>$_GET['id']),));
							$r=Receiptir::model()->findByPk($_GET['id']);			
							
							$mds="";$tds="";
							if($r->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($r->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($r->TdsNo);
								$tds=$t->TdsNo;
							}
							
						
								
							$proofcond="";
							
								$basic=(object)array('Id'=>$r->Id,'RirNo'=>$r->RirNo,'PartName'=>$r->PartName,'LabNo'=>$r->LabNo,
								'RefPurchaseOrder'=>$r->RefPurchaseOrder,'Supplier'=>$r->Supplier,'GrinNo'=>$r->GrinNo,'Quantity'=>$r->Quantity,
								'HeatNo'=>$r->HeatNo,'BatchNo'=>$r->BatchNo,'NoType'=>$r->NoType,'MdsNo'=>$r->MdsNo,'TdsNo'=>$r->TdsNo,
								'Mds'=>$mds,'Tds'=>$tds,'MaterialCondition'=>$r->MaterialCondition,'MaterialGrade'=>$r->MaterialGrade,
								'BatchCode'=>$r->BatchCode,'ExternalTests'=>$rextds);
						
								
					$model=(object)array('basic'=>$basic);		
							
						break;	
				
case 'proofeditdata':
							$d=Rirtestdetail::model()->find(array('condition'=>'TestId=:ID AND ReceiptirId=:rno',
										 'params'=>array(':ID'=>"8",':rno'=>$_GET['id']),));
										
							
						$sds=array();
							

$mds="";$tds="";
							if($d->receiptir->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->receiptir->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->receiptir->TdsNo);
								$tds=$t->TdsNo;
							}
							
						$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
							
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						$std=$sub->standard->Standard." ".$substandard;
							
							$md=$sub->mechdetails[0];
								
							$proofcond="";
							
								$basic=(object)array('Id'=>$d->Id,'RirNo'=>$d->receiptir->RirNo,'PartName'=>$d->receiptir->PartName,
								'LabNo'=>$d->receiptir->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->receiptir->RefPurchaseOrder,
								'Supplier'=>$d->receiptir->Supplier,'GrinNo'=>$d->receiptir->GrinNo,'Quantity'=>$d->receiptir->Quantity,'Remark'=>$d->Remark,
								'HeatNo'=>$d->receiptir->HeatNo,'BatchNo'=>$d->receiptir->BatchNo,'NoType'=>$d->receiptir->NoType,'MdsNo'=>$d->receiptir->MdsNo,
								'TdsNo'=>$d->receiptir->TdsNo,'Mds'=>$mds,'Tds'=>$tds,'Standard'=>$std,'StandardId'=>$sub->StandardId,'SubStandardId'=>$sub->Id,
								'MaterialCondition'=>$d->receiptir->MaterialCondition,'MaterialGrade'=>$d->receiptir->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'BatchCode'=>$d->receiptir->BatchCode,'Description'=>$d->Description,'observations'=>$d->proofloadbservations,'TestMethod'=>$d->TestMethod);
						
								
					$model=(object)array('basic'=>$basic, 'cond'=>$proofcond);		
							
						break;	
				
case 'ssteditdata':
							$d=Rirtestdetail::model()->find(array('condition'=>'TestId=:ID AND ReceiptirId=:rno',
										 'params'=>array(':ID'=>"9",':rno'=>$_GET['id']),));
										
							
						$sds=array();
							

$mds="";$tds="";
							if($d->receiptir->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->receiptir->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->receiptir->TdsNo);
								$tds=$t->TdsNo;
							}
							
						$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
							
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						$std=$sub->standard->Standard." ".$substandard;
							
							$md=$sub->mechdetails[0];
						$obbasic="";
						if(empty($d->sstbasicinfos))
						{
							$obbasic=(object)array('TestDuration'=>$d->MaxSstHrs." Hrs");	
						}
						else
						{
							$obbasic=$d->sstbasicinfos[0];		
						}						
							$sstcond="";
							$interval=24; 
							$drows=($d->MaxSstHrs)/$interval;
							
								$basic=(object)array('Id'=>$d->Id,'RirNo'=>$d->receiptir->RirNo,'PartName'=>$d->receiptir->PartName,
								'LabNo'=>$d->receiptir->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->receiptir->RefPurchaseOrder,
								'Supplier'=>$d->receiptir->Supplier,'GrinNo'=>$d->receiptir->GrinNo,'Quantity'=>$d->receiptir->Quantity,'Remark'=>$d->Remark,
								'HeatNo'=>$d->receiptir->HeatNo,'BatchNo'=>$d->receiptir->BatchNo,'NoType'=>$d->receiptir->NoType,'MdsNo'=>$d->receiptir->MdsNo,
								'TdsNo'=>$d->receiptir->TdsNo,'Mds'=>$mds,'Tds'=>$tds,'Standard'=>$std,'StandardId'=>$sub->StandardId,'SubStandardId'=>$sub->Id,
								'MaterialCondition'=>$d->receiptir->MaterialCondition,'MaterialGrade'=>$d->receiptir->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'Duration'=>$d->MaxSstHrs,'drows'=>$drows,'interval'=>$interval,'obbasic'=>$obbasic,
								'BatchCode'=>$d->receiptir->BatchCode,'Description'=>$d->Description,'observations'=>$d->sstobservations,'TestMethod'=>$d->TestMethod);
						
								
					$model=(object)array('basic'=>$basic, 'cond'=>$sstcond);		
							
						break;		
				
case 'tensioneditdata':
							$d=Rirtestdetail::model()->find(array('condition'=>'TestId=:ID AND ReceiptirId=:rno',
										 'params'=>array(':ID'=>"11",':rno'=>$_GET['id']),));
										
							
						$sds=array();
							

$mds="";$tds="";
							if($d->receiptir->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->receiptir->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->receiptir->TdsNo);
								$tds=$t->TdsNo;
							}
							
						$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
							
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						$std=$sub->standard->Standard." ".$substandard;
							
							$md=$sub->mechdetails[0];
						
										
												
							$impcond="";
							
							
								$basic=(object)array('Id'=>$d->Id,'RirNo'=>$d->receiptir->RirNo,'PartName'=>$d->receiptir->PartName,
								'LabNo'=>$d->receiptir->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->receiptir->RefPurchaseOrder,
								'Supplier'=>$d->receiptir->Supplier,'GrinNo'=>$d->receiptir->GrinNo,'Quantity'=>$d->receiptir->Quantity,'Remark'=>$d->Remark,
								'HeatNo'=>$d->receiptir->HeatNo,'BatchNo'=>$d->receiptir->BatchNo,'NoType'=>$d->receiptir->NoType,'MdsNo'=>$d->receiptir->MdsNo,
								'TdsNo'=>$d->receiptir->TdsNo,'Mds'=>$mds,'Tds'=>$tds,'Standard'=>$std,'StandardId'=>$sub->StandardId,'SubStandardId'=>$sub->Id,
								'MaterialCondition'=>$d->receiptir->MaterialCondition,'MaterialGrade'=>$d->receiptir->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'BatchCode'=>$d->receiptir->BatchCode,'Description'=>$d->Description,'observations'=>$d->tortensobservations,'TestMethod'=>$d->TestMethod);
						
								
					$model=(object)array('basic'=>$basic, 'cond'=>$impcond);		
							
						break;					
				
			
case 'tensilepredata':
								$d=Rirtestdetail::model()->find(array('condition'=>'TestId=:ID AND ReceiptirId=:rno',
										 'params'=>array(':ID'=>"10",':rno'=>$_GET['id']),));
										
						$mds="";$tds="";
							if($d->receiptir->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->receiptir->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->receiptir->TdsNo);
								$tds=$t->TdsNo;
							}
							
						$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
							
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						$std=$sub->standard->Standard." ".$substandard;
						
						$md=$sub->mechdetails[0];
						
							
							$tenscond=(object)array('F_Min'=>$md->F_Min,'F_Max'=>$md->F_Max,'EX_Min'=>$md->EX_Min,'EX_Max'=>$md->EX_Max,'TS_Min'=>$md->TS_Min,'TS_Max'=>$md->TS_Max,
						'EL_Min'=>$md->EL_Min,'EL_Max'=>$md->EL_Max,'RA_Min'=>$md->RA_Min,'RA_Max'=>$md->RA_Max,'YM_Min'=>$md->YM_Min,'YM_Max'=>$md->YM_Max,
						'YS_Min'=>$md->YS_Min,'YS_Max'=>$md->YS_Max,'PS_Min'=>$md->PS_Min,'PS_Max'=>$md->PS_Max,'UTS_Min'=>$md->UTS_Min,'UTS_Max'=>$md->UTS_Max,);
						
												
								$basic=(object)array('Id'=>$d->Id,'RirNo'=>$d->receiptir->RirNo,'PartName'=>$d->receiptir->PartName,
								'LabNo'=>$d->receiptir->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->receiptir->RefPurchaseOrder,
								'Supplier'=>$d->receiptir->Supplier,'GrinNo'=>$d->receiptir->GrinNo,'Quantity'=>$d->receiptir->Quantity,'Remark'=>$d->Remark,
								'HeatNo'=>$d->receiptir->HeatNo,'BatchNo'=>$d->receiptir->BatchNo,'NoType'=>$d->receiptir->NoType,'MdsNo'=>$d->receiptir->MdsNo,
								'TdsNo'=>$d->receiptir->TdsNo,'Mds'=>$mds,'Tds'=>$tds,'Standard'=>$std,'StandardId'=>$sub->StandardId,'SubStandardId'=>$sub->Id,
								'MaterialCondition'=>$d->receiptir->MaterialCondition,'MaterialGrade'=>$d->receiptir->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'BatchCode'=>$d->receiptir->BatchCode,'Description'=>$d->Description,'properties'=>$d->tensileobservations,'TestMethod'=>$d->TestMethod);

							$tensile="";
								
						$model=(object)array('basic'=>$basic,'cond'=>$tenscond);	
							
						break;
						
			
				case 'impacteditdata':
							$d=Rirtestdetail::model()->find(array('condition'=>'TestId=:ID AND ReceiptirId=:rno',
										 'params'=>array(':ID'=>"3",':rno'=>$_GET['id']),));
										
							
						$sds=array();
							

$mds="";$tds="";
							if($d->receiptir->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->receiptir->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->receiptir->TdsNo);
								$tds=$t->TdsNo;
							}
							
						$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
							
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						$std=$sub->standard->Standard." ".$substandard;
							
							$md=$sub->mechdetails[0];
						
							
							$l=Dropdowntemp::model()->findByPk($d->TempId);
							
						$hmin="";
$hmax="";
$hunit="";	
$hload=	$l->Temp;				
							
					
							$hmin=$md->I_Min;
							$hmax=$md->I_Max;
							$hunit="";
											
												
							$impcond=(object)array('AtTemp'=>$hload,'Min'=>$hmin,'Max'=>$hmax,'Unit'=>$hunit);
							
												
							//$elements=Elements::model()->findAll();
							
							
								$basic=(object)array('Id'=>$d->Id,'RirNo'=>$d->receiptir->RirNo,'PartName'=>$d->receiptir->PartName,
								'LabNo'=>$d->receiptir->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->receiptir->RefPurchaseOrder,
								'Supplier'=>$d->receiptir->Supplier,'GrinNo'=>$d->receiptir->GrinNo,'Quantity'=>$d->receiptir->Quantity,'Remark'=>$d->Remark,
								'HeatNo'=>$d->receiptir->HeatNo,'BatchNo'=>$d->receiptir->BatchNo,'NoType'=>$d->receiptir->NoType,'MdsNo'=>$d->receiptir->MdsNo,
								'TdsNo'=>$d->receiptir->TdsNo,'Mds'=>$mds,'Tds'=>$tds,'Standard'=>$std,'StandardId'=>$sub->StandardId,'SubStandardId'=>$sub->Id,
								'MaterialCondition'=>$d->receiptir->MaterialCondition,'MaterialGrade'=>$d->receiptir->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'BatchCode'=>$d->receiptir->BatchCode,'Description'=>$d->Description,'observations'=>$d->impactobservations,'TestMethod'=>$d->TestMethod);
						
								
					$model=(object)array('basic'=>$basic, 'cond'=>$impcond);		
							
						break;	
			

			case 'hardnesseditdata':
							$d=Rirtestdetail::model()->find(array('condition'=>'TestId=:ID AND ReceiptirId=:rno',
										 'params'=>array(':ID'=>"2",':rno'=>$_GET['id']),));
										
							
						$sds=array();
							

$mds="";$tds="";
							if($d->receiptir->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->receiptir->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->receiptir->TdsNo);
								$tds=$t->TdsNo;
							}
							
						$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
							
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						$std=$sub->standard->Standard." ".$substandard;
							
							$md=$sub->mechdetails[0];
						
							$h=Hardnesstype::model()->findByPk($d->HtypeId);
							$l=Dropdownload::model()->findByPk($d->LoadId);
							
						$hmin="";
$hmax="";
$hunit="";	
$hload=	$l->Load;				
							
						if($d->HtypeId==='1')
						{
							$hmin=$md->HBW_Min;
							$hmax=$md->HBW_Max;
							$hunit="HBW";
						}
						if($d->HtypeId==='2')
						{
							$hmin=$md->HV_Min;$hmax=$md->HV_Max;$hunit="HV";
						}	
						if($d->HtypeId==='3')
						{
							$hmin=$md->MicroHV_Min;$hmax=$md->MicroHV_Max;$hunit="HV";
						}	
						if($d->HtypeId==='4')
						{
							$hmin=$md->HRB_Min;$hmax=$md->HRB_Max;$hunit="HRB";
						}	
						if($d->HtypeId==='5')
						{
							$hmin=$md->HRC_Min;$hmax=$md->HRC_Max;$hunit="HRC";
						}							
												
							$hardcond=(object)array('Hardness'=>$h->Type,'HtypeId'=>$d->HtypeId,'Load'=>$hload,'Min'=>$hmin,'Max'=>$hmax,'Unit'=>$hunit);
							
												
							//$elements=Elements::model()->findAll();
							
							
								$basic=(object)array('Id'=>$d->Id,'RirNo'=>$d->receiptir->RirNo,'PartName'=>$d->receiptir->PartName,
								'LabNo'=>$d->receiptir->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->receiptir->RefPurchaseOrder,
								'Supplier'=>$d->receiptir->Supplier,'GrinNo'=>$d->receiptir->GrinNo,'Quantity'=>$d->receiptir->Quantity,
								'HeatNo'=>$d->receiptir->HeatNo,'BatchNo'=>$d->receiptir->BatchNo,'NoType'=>$d->receiptir->NoType,'MdsNo'=>$d->receiptir->MdsNo,
								'TdsNo'=>$d->receiptir->TdsNo,'Mds'=>$mds,'Tds'=>$tds,'Standard'=>$std,'StandardId'=>$sub->StandardId,'SubStandardId'=>$sub->Id,
								'MaterialCondition'=>$d->receiptir->MaterialCondition,'MaterialGrade'=>$d->receiptir->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'BatchCode'=>$d->receiptir->BatchCode,'Description'=>$d->Description,'observations'=>$d->hardobservations,
								'TestMethod'=>$d->TestMethod,'Remark'=>$d->Remark,);
								
						
						
								
					$model=(object)array('basic'=>$basic,'cond'=>$hardcond);		
							
						break;	
				
			
			case 'chemicaleditdata':
							$d=Rirtestdetail::model()->find(array('condition'=>'TestId=:ID AND ReceiptirId=:rno',
										 'params'=>array(':ID'=>"1",':rno'=>$_GET['id']),));
										
							
						$sds=array();
							
											 
								foreach($d->spectrodetails as $e)
								{
									$cr=Chemicalcomposition::model()->find(array('condition'=>'CBId=:ID AND ElementId=:eid',
											 'params'=>array(':ID'=>$d->SubStandardId,':eid'=>$e->ElementId),));
									
									$sds[]=(object)array('Id'=>$e->Id,'ElementId'=>$e->ElementId,'Element'=>$cr->element->Symbol,'Min'=>$cr->Min,'Max'=>$cr->Max,'Value'=>$e->Value,
									'LimitOut'=>$e->LimitOut,'RirTestId'=>$e->RirTestId);
								}	
														
								
							$mds="";$tds="";
							if($d->receiptir->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->receiptir->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->receiptir->TdsNo);
								$tds=$t->TdsNo;
							}
							
						$sub=Chemicalbasic::model()->findByPk($d->SubStandardId);	
							
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						$std=$sub->standard->Standard." ".$substandard;
							
							
												
							//$elements=Elements::model()->findAll();
							
							
								$basic=(object)array('Id'=>$d->Id,'RirNo'=>$d->receiptir->RirNo,'PartName'=>$d->receiptir->PartName,
								'LabNo'=>$d->receiptir->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->receiptir->RefPurchaseOrder,
								'Supplier'=>$d->receiptir->Supplier,'GrinNo'=>$d->receiptir->GrinNo,'Quantity'=>$d->receiptir->Quantity,
								'HeatNo'=>$d->receiptir->HeatNo,'BatchNo'=>$d->receiptir->BatchNo,'NoType'=>$d->receiptir->NoType,'MdsNo'=>$d->receiptir->MdsNo,
								'TdsNo'=>$d->receiptir->TdsNo,'Mds'=>$mds,'Tds'=>$tds,'Standard'=>$std,'StandardId'=>$sub->StandardId,'SubStandardId'=>$sub->Id,
								'MaterialCondition'=>$d->receiptir->MaterialCondition,'MaterialGrade'=>$d->receiptir->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'BatchCode'=>$d->receiptir->BatchCode,'Description'=>$d->Description,'Remark'=>$d->Remark,'TestMethod'=>$d->TestMethod);
								
						
						
								
					$model=(object)array('Basic'=>$basic,'Elements'=>$sds);		
							
						break;	
						
												
				case 'chemicalpredata':
							$d=Rirtestdetail::model()->find(array('condition'=>'TestId=:ID AND ReceiptirId=:rno',
										 'params'=>array(':ID'=>"1",':rno'=>$_GET['id']),));
									
							$mds="";$tds="";
							if($d->receiptir->NoType ==='mds')
							{
								$m=Mds::model()->findByPk($d->receiptir->MdsNo);
								$mds=$m->MdsNo;
							}
							else
							{
								$t=Tds::model()->findByPk($d->receiptir->TdsNo);
								$tds=$t->TdsNo;
							}
							
						$sub=Chemicalbasic::model()->findByPk($d->SubStandardId);	
							
						$type=$sub->standard->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$sub->Grade." ".$sub->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$sub->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$sub->Class." ".$sub->Property;
						}
						$std=$sub->standard->Standard." ".$substandard;
							
							
												
							//$elements=Elements::model()->findAll();
							
							
								$basic=(object)array('Id'=>$d->Id,'RirNo'=>$d->receiptir->RirNo,'PartName'=>$d->receiptir->PartName,
								'LabNo'=>$d->receiptir->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->receiptir->RefPurchaseOrder,
								'Supplier'=>$d->receiptir->Supplier,'GrinNo'=>$d->receiptir->GrinNo,'Quantity'=>$d->receiptir->Quantity,
								'HeatNo'=>$d->receiptir->HeatNo,'BatchNo'=>$d->receiptir->BatchNo,'NoType'=>$d->receiptir->NoType,'MdsNo'=>$d->receiptir->MdsNo,
								'TdsNo'=>$d->receiptir->TdsNo,'Mds'=>$mds,'Tds'=>$tds,'Standard'=>$std,'StandardId'=>$sub->StandardId,'SubStandardId'=>$sub->Id,
								'MaterialCondition'=>$d->receiptir->MaterialCondition,'MaterialGrade'=>$d->receiptir->MaterialGrade,'ReqDate'=>$d->ReqDate,
								'BatchCode'=>$d->receiptir->BatchCode,'TestMethod'=>$d->TestMethod);

							
						
					$allecrs=array();			 
					foreach($sub->chemicalcompositions as $e)
					{
						$allecrs[]=(object)array('ElementId'=>$e->ElementId,'Element'=>$e->element->Symbol,'Max'=>$e->Max,'Min'=>$e->Min,'Value'=>"");
					}
					
								
						$model=(object)array('Basic'=>$basic,'Elements'=>$allecrs);		
							
						break;
				
				
				
				case 'riredit':
				$r = Receiptir::model()->findByPk($_GET['id']);
				
				$standards=Standards::model()->findAll();
					$allstds=array();
					$csubstd=array();
					$msubstd=array();
					foreach($standards as $s)
					{
						
						foreach($s->chemicalbasics as $c)
						{
						
						$type=$s->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$c->Grade." ".$c->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$c->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$c->Class." ".$c->Property;
						}
						
						$csubstd[]=(object)array('Id'=>$c->Id,'SubStandard'=>$substandard,'StandardId'=>$s->Id,'Standard'=>$s->Standard);
						}
						
						
						foreach($s->mechbasics as $m)
						{
						
						$type=$s->Type;
						$substandard="";
						if($type==='EN')
						{
							$substandard=$m->Grade." ".$m->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$m->Grade;
						}
						
						if($type==='ISO')
						{
							$substandard=$m->Class." ".$m->Property;
						}
						
						$msubstd[]=(object)array('Id'=>$m->Id,'SubStandard'=>$substandard,'StandardId'=>$s->Id,'Standard'=>$s->Standard);
						}
						
						$allstds[]=(object)array('Id'=>$s->Id,'Standard'=>$s->Standard);
					}
					
					$rexttests=Rirexttestdetail::model()->findAll(array('condition'=>'ReceiptirId=:rir',
										 'params'=>array(':rir'=>$r->Id),));
					$exttests=array();					 
					foreach($rexttests as $rt)
					{
						$exttests[]=Externaltests::model()->findByPk($rt['ExtTestId']);
					}
					
					$htypes=Hardnesstype::model()->findAll();
					$dropload=Dropdownload::model()->findAll();
					$droptemps=Dropdowntemp::model()->findAll();
					$mdsnos=Mds::model()->findAll();
					$tdsnos=Tds::model()->findAll();
					$ssthrs=Ssthours::model()->findAll();
					$allexttests=Externaltests::model()->findAll();
					
					$methods=Testmethods::model()->findAll();
					$alltestmethods=array();
					foreach($methods as $l)
					{
						if($l->TypeId !=0)
						{
							$type=Testtypes::model()->findByPk($l->TypeId);
								$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName,'TypeId'=>$l->TypeId,'Type'=>$type->Type);
						}
						else
						{
							$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,'Test'=>$l->test->TestName);
						}
					}
					
					
					$predata=(object)array('standards'=>$allstds,'Csubstandards'=>$csubstd,'Msubstandards'=>$msubstd,'Htypes'=>$htypes,'ssthours'=>$ssthrs,
					'Hardloads'=>$dropload,'Impacttemps'=>$droptemps,'mdsnos'=>$mdsnos,'tdsnos'=>$tdsnos,'allexttests'=>$allexttests,'testmethods'=>$alltestmethods);
				
					$alltests=Tests::model()->findAll();

				$otest=array();
				foreach($alltests as $t)
				{
				$otest[]=$t->Id;
				}

				$ntest=array();
				$nedittest=array();
				foreach($r->rirtestdetails as $rt)
						{
							$ntest[]=$rt->TestId;
							$testmethod=Testmethods::model()->findByPk($rt->TestMethodId);
							$nedittest[]=(object)array('Id'=>$rt->Id,'TestName'=>$rt->TestName,'TestId'=>$rt->TestId,'TestMethod'=>$testmethod,'Applicable'=>$rt->Applicable,'ExtraInfo'=>$rt->ExtraInfo,
							'StandardId'=>$rt->StandardId,'SubStandardId'=>$rt->SubStandardId,'HtypeId'=>$rt->HtypeId,'LoadId'=>$rt->LoadId,'TempId'=>$rt->TempId,
							'ReqDate'=>$rt->ReqDate,'MaxSstHrsId'=>$rt->MaxSstHrsId);
						}
				$darry=array_diff($otest, $ntest);
				
			
				$tests=array();
					foreach($darry as $d)
					{
						$b=Tests::model()->findByPk($d);
						
						$tests[]=(object)array('Id'=>$b->Id,'TestName'=>$b->TestName,'TestId'=>$b->Id,'Keyword'=>$b->Keyword,'Applicable'=>"false",'TestMethod'=>"",'ExtraInfo'=>"");
					}
				$alltests=array_merge($nedittest,$tests);
				
				$rir=(object)array('Id'=>$r->Id,'RirNo'=>$r->RirNo,'Supplier'=>$r->Supplier,'TdsNo'=>$r->TdsNo,'NoType'=>$r->NoType,
						'PartName'=>$r->PartName,'MaterialCondition'=>$r->MaterialCondition,'MdsNo'=>$r->MdsNo,'GrinNo'=>$r->GrinNo,'Quantity'=>$r->Quantity,
						'HeatNo'=>$r->HeatNo,'RefPurchaseOrder'=>$r->RefPurchaseOrder,'BatchNo'=>isset($r->BatchNo)?$r->BatchNo:"",
						'MaterialGrade'=>$r->MaterialGrade,'LabNo'=>$r->LabNo,'alltests'=>$alltests,'ExtTest'=>$exttests);
						
			$model=(object)array('rir'=>$rir,'predata'=>$predata);
				break;
				case 'subject':
					$model = Subject::model()->findByPk($_GET['id']);
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
				// Get an instance of the respective model
				
				case 'adduser':$model=new Users;break;
				
				case 'customer':$model=new Customerinfo;break;
				
				case 'method':$model=new Testmethods;break;
				
				case 'ssthr':	$model=new Ssthours;break;
				
				case 'droptemp':	$model=new Dropdowntemp;break;
				
				case 'dropload':	$model=new Dropdownload;break;
				
				case 'mdsadddata':	$model=new Mds;break;
				
				case 'tdsadddata':	$model=new Tds;break;
				
							
			
							
				case 'mechadddata':	$model=new Mechbasic;break;
				
				case 'concntadddata':	$model=new Chemicalbasic;break;
				
				case 'stdadddata':	$model=new Standards;break;
				
			
								
				case 'riradd':		$model=new Receiptir;break;
				
				case 'elementsbystd':	$model=new Standards;break;
				
			
				
				  
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
				case 'concntadddata':	break;
			   
				case 'mechadddata':		break;
			 
				
			   
				   
				case 'elementsbystd':	break;
			   
				case 'adduser':
							   foreach($data as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($model->hasAttribute($var))
										$model->$var = $value;
								  }					
									break;
									
				case 'stdadddata':
							   foreach($data as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($model->hasAttribute($var))
										$model->$var = $value;
								  }					
									break;
			   	
				case 'mdsadddata':
								foreach($data as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($model->hasAttribute($var))
										$model->$var = $value;
								  }					
									break;
				case 'tdsadddata':
							   foreach($data as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($model->hasAttribute($var))
										$model->$var = $value;
								  }					
								break;
			   
			   case 'riradd':
						foreach($data as $var=>$value)
				  {
					// Does the model have this attribute? If not raise an error
					if($model->hasAttribute($var))
						$model->$var = $value;
				  }					
					break;
			   
			
			   						
					 
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
			   
			   case 'customer':
			   $transaction=$model->dbConnection->beginTransaction();
				try
					{
						$model->CreationDate=date('Y-m-d H:i:s');
						$model->save(false);
						
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode("success"));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e));
					}
			   
				break;
			   
			   	case 'method':
			   $transaction=$model->dbConnection->beginTransaction();
				try
					{
						
						$model->save(false);
						
						
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode("success"));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e));
					}
			   
				break;

			   
			   	case 'adduser':
			   $transaction=$model->dbConnection->beginTransaction();
				try
					{
						$model->Username=$model->Email;
						$model->CreationDate=date('Y-m-d H:i:s');
						$model->save(false);
						
						$uir=new Userinroles;
						$uir->UserId=$model->getPrimaryKey();
						$uir->RoleId=$data['Role'];
						$uir->save(false);
																		
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode("success"));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e));
					}
			   
				break;

			   
			   
				case 'mdsadddata':
			   $transaction=$model->dbConnection->beginTransaction();
				try
					{
						
						$model->save(false);
																		
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($model));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e));
					}
			   
				break;

				
				case 'tdsadddata':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						
						$model->save(false);
																		
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($model));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e));
					}
			   
				break;		
					
			   		   
				
			   
			     case 'stdadddata':
			   $transaction=$model->dbConnection->beginTransaction();
				try
					{
						
						$model->save(false);
																		
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($data));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e));
					}
			   
				break;
				
				
				
	case 'mechadddata':
					 
					 $transaction=$model->dbConnection->beginTransaction();
					try
					{
						$model->StandardId=$data['basic']['StandardId'];
						$model->Type=$data['basic']['Type'];
						$model->Grade=isset($data['basic']['Grade'])?$data['basic']['Grade']:"";
						$model->Number=isset($data['basic']['Number'])?$data['basic']['Number']:"";
						$model->Class=isset($data['basic']['Class'])?$data['basic']['Class']:"";
						$model->Property=isset($data['basic']['Property'])?$data['basic']['Property']:"";
						$model->UNS=isset($data['basic']['UNS'])?$data['basic']['UNS']:"";
						$model->Material=isset($data['basic']['Material'])?$data['basic']['Material']:"";
						$model->Diameter=isset($data['basic']['Diameter'])?$data['basic']['Diameter']:"";
						$model->CreationDate=date('Y-m-d H:i:s');
						
						$model->save(false);
						
						$md=new Mechdetails;
						foreach($data['details'] as $var=>$value)
				  {
					// Does the model have this attribute? If not raise an error
					if($md->hasAttribute($var))
						$md->$var = $value;
				  }				
								
					$md->MBId=$model->getPrimaryKey();
					$md->LastModified=date('Y-m-d H:i:s');
					$md->save(false);
									
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($data));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e));
					}
			   
							
			   break;
			   case 'concntadddata':
			   $transaction=$model->dbConnection->beginTransaction();
				try
					{
						$model->StandardId=$data['StandardId'];
						$model->Type=$data['Type'];
						if($data['Type']==='EN')
						{
							$model->Grade=$data['Grade'];
							$model->Number=$data['Number'];
						}
						if($data['Type']==='ASTM')
						{
							$model->Grade=$data['Grade'];
						
						}
						
						if($data['Type']==='ISO')
						{
							$model->Class=$data['Class'];
							$model->Property=isset($data['Property'])?$data['Property']:"";
						}
							$model->save(false);
						
						
						
						foreach($data['allelements'] as $e)
						{
							$r=new Chemicalcomposition;
							$r->ElementId=$e['ElementId'];
							$r->Min=$e['Min'];
							$r->Max=$e['Max'];
							$r->CBId=$model->getPrimaryKey();
							$r->save(false);
						}
							$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($data));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							
					}
			   
							break;
							
			   case 'elementsbystd':
			
			   $std=Chemicalbasic::model()->find(array('condition'=>'Id=:std',
										 'params'=>array(':std'=>$data['std']),));
				if(!empty($std))
				{
					
					$allecrs=array();			 
					foreach($std->chemicalcompositions as $e)
					{
						$allecrs[]=(object)array('ElementId'=>$e->ElementId,'Element'=>$e->element->Symbol,'Max'=>$e->Max,'Min'=>$e->Min,'Value'=>"");
					}
						$this->_sendResponse(200, CJSON::encode($allecrs));					
				}	
				else
				{$this->_sendResponse(401, CJSON::encode("no such standards"));}				
			 
			   break;
				
				
				case 'riradd':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						
						$allrir=Receiptir::model()->findAll();
						$lastrecord = Receiptir::model()->find(array('order'=>'Id DESC'));
						$model->CreationDate=date('Y-m-d H:i:s');
						$model->BCGenerated="false";
						
						
						if($model->save(false))
						{
							
							
							$labno=0;
						if(empty($allrir))
						{
							$set=Settings::model()->findByPk("1");
							$labno=$set->LabNoStart;
							
						}
						else
						{	
							
							
							$lastlabno = $lastrecord->LabNo;
							$lastno =  substr($lastlabno, 1);
							$alpha =  substr($lastlabno, 0, -4);
							$newlabno=$lastno+1;
							if($newlabno>9999)
							{
								$alpha++;
								$newlabno="0001";
							}
							$newlabno=str_pad($newlabno, 4, "0", STR_PAD_LEFT);
							$labno=$alpha.$newlabno;
							//$this->_sendResponse(401, CJSON::encode($labno));
						}	
						
						$model->RirNo="RIR"+$model->getPrimaryKey();
						$model->LabNo=$labno;
						$model->save(false);
						
						
							foreach($data['alltests'] as $t)
							{
								if($t['Applicable']==='true')
								{
									$rtds=new Rirtestdetail;
									$rtds->ReceiptirId=$model->getPrimaryKey();
									$rtds->RirNo=$model->RirNo;
									$rtds->TestId=$t['Id'];
									$rtds->TestName=$t['TestName'];
									$rtds->TestMethod=$t['TestMethod']['Method'];
									$rtds->TestMethodId=$t['TestMethod']['Id'];
									$rtds->Applicable=$t['Applicable'];
									$rtds->StandardId=$t['StandardId'];
									$rtds->SubStandardId=$t['SubStandardId'];
									$rtds->ExtraInfo=isset($t['ExtraInfo'])?$t['ExtraInfo']:"";
									$rtds->HtypeId=isset($t['HtypeId'])?$t['HtypeId']:"";
									$rtds->LoadId=isset($t['LoadId'])?$t['LoadId']:"";
									$rtds->TempId=isset($t['TempId'])?$t['TempId']:"";
									if(isset($t['MaxSstHrsId']))
									{
										$rtds->MaxSstHrsId=isset($t['MaxSstHrsId'])?$t['MaxSstHrsId']:"";
										$hr=Ssthours::model()->findByPk($t['MaxSstHrsId']);
										$rtds->MaxSstHrs=$hr->Hours;
									}
									
									
									$rtds->ReqDate=$t['ReqDate'];
									$rtds->LastModified=date('Y-m-d H:i:s');
									$rtds->save(false);
								}
							}
							
							
							foreach($data['ExtTest'] as $t)
							{
								
									$rtds=new Rirexttestdetail;
									$rtds->ReceiptirId=$model->getPrimaryKey();
									$rtds->RirNo=$model->RirNo;
									$rtds->ExtTestId=$t['Id'];
									$rtds->TestName=$t['TestName'];
									$rtds->Applicable="true";
									$rtds->Status="pending";
									$rtds->ExtraInfo=isset($t['ExtraInfo'])?$t['ExtraInfo']:"";
									//$rtds->ReqDate=$t['ReqDate'];
									$rtds->LastModified=date('Y-m-d H:i:s');
									$rtds->save(false);
								
							}
							
							
							
							$transaction->commit();
							$this->_sendResponse(200, CJSON::encode("success"));
						}
						else
						{
							
						}
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							
					}
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
				// Find respective model
				case 'customer':		$model = Customerinfo::model()->findByPk($_GET['id']); 		break;
				
				case 'droptemp':		$model = Dropdowntemp::model()->findByPk($_GET['id']);      break;
					
				case 'method':			$model = Testmethods::model()->findByPk($_GET['id']);     	break;	
					
				case 'ssthr':			$model = Ssthours::model()->findByPk($_GET['id']);     		break;	
					
				case 'dropload':		$model = Dropdownload::model()->findByPk($_GET['id']);      break;
					
				case 'edituser':		$model = User::model()->findByPk($_GET['id']);      		break;
				
				case 'mdsedit':			$model = Mds::model()->findByPk($_GET['id']);      			break;
					
				case 'tdsedit':			$model = Tds::model()->findByPk($_GET['id']);      			break;
				
				case 'proofupdate':		$model=Rirtestdetail::model()->findByPk($_GET['id']); 		break;	
				
				case 'sstupdate':		$model=Rirtestdetail::model()->findByPk($_GET['id']); 		break;	
				
				case 'tensionupdate':	$model=Rirtestdetail::model()->findByPk($_GET['id']); 		break;	
				
				case 'impactupdate':	$model=Rirtestdetail::model()->findByPk($_GET['id']);		break;
					
				case 'tensileupdate':	$model=Rirtestdetail::model()->findByPk($_GET['id']);		break;	
					
				case 'hardnessupdate':	$model=Rirtestdetail::model()->findByPk($_GET['id']);		break;
				
				case 'chemicalupdate':	$model=Rirtestdetail::model()->findByPk($_GET['id']);		break;
				
				case 'stdedit':
					$model = Standards::model()->findByPk($_GET['id']);      
					break;
					
				case 'stdsubedit':
					$model = Stdsub::model()->findByPk($_GET['id']);      
					break;
					
				case 'stdsubedit':
					$model = Standards::model()->findByPk($_GET['id']);      
					break;	
					
					case 'mechedit':
				$model = Mechbasic::model()->findByPk($_GET['id']);      
					break;	
					
				case 'concntedit':
				$model = Chemicalbasic::model()->findByPk($_GET['id']);      
					break;
				case 'riredit':
					$model = Receiptir::model()->findByPk($_GET['id']);                    
					break;
					
				case 'users':
					$model = Users::model()->findByPk($_GET['id']);                    
					break;
					
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
			
			case 'mdsedit': $data=$put_vars;break;
			
			case 'tdsedit': $data=$put_vars;break;
			
			case 'proofupdate':$data=$put_vars;break;
			case 'sstupdate':$data=$put_vars;break;
			case 'impactupdate':$data=$put_vars;break;
			case 'tensileupdate':$data=$put_vars;break;
			case 'tensionupdate':$data=$put_vars;break;
			case 'hardnessupdate':$data=$put_vars;break;
			case 'chemicalupdate':$data=$put_vars;break;
			
			case 'stdedit': $data=$put_vars;break;
			
			case 'edituser':foreach($put_vars as $var=>$value) {
									// Does model have this attribute? If not, raise an error
									if($model->hasAttribute($var))
										$model->$var = $value;
					}		
					break;
			
			case 'mechedit':		$data=$put_vars;
						foreach($put_vars['basic'] as $var=>$value) {
												// Does model have this attribute? If not, raise an error
												if($model->hasAttribute($var))
													$model->$var = $value;
								}		
					break;	
			
			case 'concntedit':		$data=$put_vars;
						foreach($put_vars as $var=>$value) {
												// Does model have this attribute? If not, raise an error
												if($model->hasAttribute($var))
													$model->$var = $value;
								}		
					break;	
			
			case 'examfinish':		$data=$put_vars;	break;
				
			case 'riredit':
					$data=$put_vars;  
					foreach($put_vars as $var=>$value) {
									// Does model have this attribute? If not, raise an error
									if($model->hasAttribute($var))
										$model->$var = $value;
					}		
					break;
		 
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
				
				case 'edituser':
					$transaction=$model->dbConnection->beginTransaction();
								try
									{
										
										$model->Username=$model->Email;
										$model->save(false);
										
										$uir=$model->userinroles[0];
										$uir->RoleId=$put_vars['Role'];
										$uir->save();
										
											$transaction->commit();
											$this->_sendResponse(200, CJSON::encode($data));
										
									}
									catch(Exception $e)
									{
										
											  $transaction->rollback();
											
									}		
				
						break;
				case 'mdsedit':
					$transaction=$model->dbConnection->beginTransaction();
								try
									{
										$model->MdsNo=$data['MdsNo'];
										$model->Material=$data['Material'];
										$model->Standard=$data['Standard'];
										$model->Size=$data['Size'];
										$model->Remark=$data['Remark'];
										$model->save(false);
										
										
											$transaction->commit();
											$this->_sendResponse(200, CJSON::encode($data));
										
									}
									catch(Exception $e)
									{
										
											  $transaction->rollback();
											
									}		
				break;
				
				
				case 'tdsedit':
					$transaction=$model->dbConnection->beginTransaction();
								try
									{
										$model->TdsNo=$data['TdsNo'];
										$model->Material=$data['Material'];
										$model->Standard=$data['Standard'];
										$model->Size=$data['Size'];
										$model->Remark=$data['Remark'];
										$model->save(false);
										
										
											$transaction->commit();
											$this->_sendResponse(200, CJSON::encode($data));
										
									}
									catch(Exception $e)
									{
										
											  $transaction->rollback();
											
									}		
				break;
				
				case 'proofupdate':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						$model->Remark=$data['basic']['Remark'];;
						$model->LastModified=date('Y-m-d H:i:s');
						$model->Status="complete";
						$model->save(false);
						
						
						
						
						foreach($data['basic']['observations'] as $d)
						{
							
							//$d=$data['basic']['observations'];
							if(!empty($d['Id']) || isset($d['Id']))
							{
								$sd=Proofloadbservations::model()->findByPk($d['Id']);
							}
							else
							{
								$sd=new Proofloadbservations;
								$sd->RirTestId=$model->getPrimaryKey();
								
							}	
							
							$sd->Required=$d['Required'];
							$sd->Applied=$d['Applied'];
							$sd->Extension=$d['Extension'];
							$sd->Temperature=$d['Temperature'];
						
							$sd->save(false);
							
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
				
				case 'sstupdate':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						//$model->BatchCode=$data['basic']['BatchCode'];
						//$model->Description=isset($data['basic']['Description'])?$data['basic']['Description']:"";
						$model->Remark="OK";
						$model->LastModified=date('Y-m-d H:i:s');
						$model->Status="complete";
						$model->save(false);
						
						
						if(empty($data['basic']['obbasic']['Id']))
						{
							$ob=new Sstbasicinfo;
						}
						else
						{
							$ob=Sstbasicinfo::model()->findByPk($data['basic']['obbasic']['Id']);
						}
						foreach($data['basic']['obbasic'] as $var=>$value) {
				// Does model have this attribute? If not, raise an error
				if($ob->hasAttribute($var))
					$ob->$var = $value;
				
						}
						$ob->RirTestId=$model->getPrimaryKey();
						$ob->save(false);
						
						foreach($data['basic']['observations'] as $d)
						{
							
							
							if(!empty($d['Id']) || isset($d['Id']))
							{
								$sd=Sstobservations::model()->findByPk($d['Id']);
							}
							else
							{
								$sd=new Sstobservations;
								$sd->RirTestId=$model->getPrimaryKey();
								$sd->SstBasicId=$ob->getPrimaryKey();
							}	
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($sd->hasAttribute($var))
										$sd->$var = $value;
								  }		
							
						
							$sd->save(false);
							
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
				
				case 'tensionupdate':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						//$model->BatchCode=$data['basic']['BatchCode'];
						//$model->Description=isset($data['basic']['Description'])?$data['basic']['Description']:"";
						$model->Remark=$data['basic']['Remark'];
						$model->LastModified=date('Y-m-d H:i:s');
						$model->Status="complete";
						$model->save(false);
						
						
						foreach($data['delobservations'] as $d)
						{
							$sd=Tortensobservations::model()->findByPk($d['Id']);
								if(!empty($sd))
								{
								$sd->delete();
								}
						}
						
						foreach($data['basic']['observations'] as $d)
						{
							
							
							if(!empty($d['Id']) || isset($d['Id']))
							{
								$sd=Tortensobservations::model()->findByPk($d['Id']);
							}
							else
							{
								$sd=new Tortensobservations;
								$sd->RirTestId=$model->getPrimaryKey();
							}	
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($sd->hasAttribute($var))
										$sd->$var = $value;
								  }		
							
						if(!empty($d['Force']) )
							{
							$sd->save(false);
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
				
				
				case 'tensileupdate':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						//$model->BatchCode=$data['basic']['BatchCode'];
						//$model->Description=isset($data['basic']['Description'])?$data['basic']['Description']:"";
						$model->Remark=$data['basic']['Remark'];
						$model->LastModified=date('Y-m-d H:i:s');
						$model->Status="complete";
						$model->save(false);
						
						foreach($data['basic']['properties'] as $d)
						{
							
							
							if(!empty($d['Id']) || isset($d['Id']))
							{
								$sd=Tensileobservations::model()->findByPk($d['Id']);
							}
							else
							{
								$sd=new Tensileobservations;
								$sd->RirTestId=$model->getPrimaryKey();
							}	
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($sd->hasAttribute($var))
										$sd->$var = $value;
								  }		
							
							// $sd->F=isset($d['F'])?$d['F']:"";
							// $sd->EX=isset($d['F'])?$d['F']:"";
							// $sd->TS=isset($d['F'])?$d['F']:"";
							// $sd->EL=isset($d['F'])?$d['F']:"";
							// $sd->RA=isset($d['F'])?$d['F']:"";
							// $sd->YM=isset($d['F'])?$d['F']:"";
							// $sd->YS=isset($d['F'])?$d['F']:"";
							// $sd->PS=isset($d['F'])?$d['F']:"";
							// $sd->UTS=isset($d['F'])?$d['F']:"";
							$sd->save(false);
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
				
				case 'impactupdate':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						//$model->BatchCode=$data['basic']['BatchCode'];
						//$model->Description=isset($data['basic']['Description'])?$data['basic']['Description']:"";
						$model->Remark=$data['basic']['Remark'];
						$model->LastModified=date('Y-m-d H:i:s');
						$model->Status="complete";
						$model->save(false);
						
						foreach($data['basic']['observations'] as $d)
						{
							
							
							if(!empty($d['Id']) || isset($d['Id']))
							{
								$sd=Impactobservations::model()->findByPk($d['Id']);
							}
							else
							{
								$sd=new Impactobservations;
								$sd->RirTestId=$model->getPrimaryKey();
							}	
							
							
							$sd->Value1=$d['Value1'];
							$sd->Value2=$d['Value2'];
							$sd->Value3=$d['Value3'];
							$sd->Avg=($d['Value1']+$d['Value2']+$d['Value3'])/3;
							$sd->save(false);
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
				
				 case 'hardnessupdate':
						$transaction=$model->dbConnection->beginTransaction();
				try
					{
						
						
						//$model->BatchCode=$data['basic']['BatchCode'];
						//$model->Description=isset($data['basic']['Description'])?$data['basic']['Description']:"";
						$model->Remark=$data['basic']['Remark'];
						$model->LastModified=date('Y-m-d H:i:s');
						$model->Status="complete";
						$model->save(false);
						
						foreach($data['basic']['observations'] as $d)
						{
							if(!empty($d['Id']) || isset($d['Id']))
							{
							$sd=Hardobservations::model()->findByPk($d['Id']);
							}
							else
							{
								$sd=new Hardobservations;
								$sd->RirTestId=$model->getPrimaryKey();
							}
							$sd->Value=$d['Value'];
							$sd->Status=$d['Status'];
							$sd->save(false);
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
				
				 case 'chemicalupdate':
						$transaction=$model->dbConnection->beginTransaction();
				try
					{
						$stdsub=Chemicalbasic::model()->findByPk($data['basic']['SubStandardId']);
						
									
						
						//$model->BatchCode=$data['basic']['BatchCode'];
						//	$model->Description=isset($data['basic']['Description'])?$data['basic']['Description']:"";
						$model->Remark=$data['basic']['Remark'];
						$model->LastModified=date('Y-m-d H:i:s');
						$model->Status="complete";
						$model->save(false);
						
						foreach($data['basic']['SpectroDetails'] as $d)
						{
							if(isset($d['Id']) && !empty($d['Id']))
							{
								$sd=Spectrodetails::model()->findByPk($d['Id']);
								
								if(empty($sd))
								{
									$sd=new Spectrodetails;
									$sd->RirTestId=$model->getPrimaryKey();
									$sd->ElementId=$d['ElementId'];
									
								}
							}
							else
							{
								$sd=new Spectrodetails;
								$sd->RirTestId=$model->getPrimaryKey();
								$sd->ElementId=$d['ElementId'];
							}
							
							$sd->Value=$d['Value'];
							$sd->save(false);
						}
						
						
						// $rir=$model->receiptir;
						// if($rir->BCGenerated==='false')
						// {
							// foreach($rir->rirtestdetails as $td)
							// {
								// $td->BatchCode=$model->BatchCode;
								// $td->save(false);
							// }
							
							// $rir->BCGenerated="true";
							// $rir->save(false);
						// }
						
						
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($data));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($data));
					}
			   
							break;
				
				case 'stdedit':
							$transaction=$model->dbConnection->beginTransaction();
										try
											{
												$model->Standard=$data['Standard'];
												$model->Type=$data['Type'];
												$model->Description=isset($data['Description'])?$data['Description']:"";
												$model->save(false);
												
												
													$transaction->commit();
													$this->_sendResponse(200, CJSON::encode($data));
												
											}
											catch(Exception $e)
											{
												
													  $transaction->rollback();
													
											}		break;
				
				case 'mechedit':
						 $transaction=$model->dbConnection->beginTransaction();
										try
											{
												$model->save(false);
												
												$md=Mechdetails::model()->find(array('condition'=>'MBId=:ID',
										 'params'=>array(':ID'=>$model->Id),));
											 foreach($put_vars['details'] as $var=>$value) 
											 {
										// Does model have this attribute? If not, raise an error
															if($md->hasAttribute($var))
																$md->$var = $value;
											}		
												
												$md->LastModified=date('Y-m-d H:i:s');
												$md->save();
												
													$transaction->commit();
													$this->_sendResponse(200, CJSON::encode($data));
												
											}
											catch(Exception $e)
											{
												
													  $transaction->rollback();
													
											}				
			//	$this->_sendResponse(200, CJSON::encode($data));
						break;
				
				
				case 'concntedit':
						 $transaction=$model->dbConnection->beginTransaction();
										try
											{
												$model->save(false);
												foreach($data['allelements'] as $e)
												{
													$r=Chemicalcomposition::model()->findByPk($e['Id']);
													//$r->ElementId=$e['ElementId'];
													$r->Min=$e['Min'];
													$r->Max=$e['Max'];
													//$r->StandardId=$data['selected'];
													$r->save(false);
												}
													$transaction->commit();
													$this->_sendResponse(200, CJSON::encode($data));
												
											}
											catch(Exception $e)
											{
												
													  $transaction->rollback();
													
											}				
			//	$this->_sendResponse(200, CJSON::encode($data));
						break;
				
				case 'riredit':
					$transaction=$model->dbConnection->beginTransaction();
				try
					{
						//$model->CreationDate=date('Y-m-d H:i:s');
						if($model->save(false))
						{
							foreach($data['alltests'] as $t)
							{
								if($t['Applicable']==='true')
								{
									$rtds=Rirtestdetail::model()->find(array('condition'=>'TestId=:id  AND ReceiptirId=:rir',
										 'params'=>array(':id'=>$t['TestId'],':rir'=>$model->Id),));
									if(empty($rtds))
									{
										$rtds=new Rirtestdetail;
										$rtds->TestId=$t['Id'];
									}
									else
									{
									
										$rtds->TestId=$t['TestId'];
									}
									$rtds->ReceiptirId=$model->getPrimaryKey();
									$rtds->RirNo=$model->RirNo;
									
									$rtds->TestName=$t['TestName'];
									$rtds->Applicable=$t['Applicable'];
										$rtds->TestMethod=$t['TestMethod']['Method'];
									$rtds->TestMethodId=$t['TestMethod']['Id'];
									$rtds->StandardId=$t['StandardId'];
									$rtds->SubStandardId=$t['SubStandardId'];
									$rtds->ExtraInfo=isset($t['ExtraInfo'])?$t['ExtraInfo']:"";
									$rtds->HtypeId=isset($t['HtypeId'])?$t['HtypeId']:"";
									$rtds->LoadId=isset($t['LoadId'])?$t['LoadId']:"";
									$rtds->TempId=isset($t['TempId'])?$t['TempId']:"";
									$rtds->ReqDate=$t['ReqDate'];
									if(isset($t['MaxSstHrsId']))
									{
										$rtds->MaxSstHrsId=isset($t['MaxSstHrsId'])?$t['MaxSstHrsId']:"";
										$hr=Ssthours::model()->findByPk($t['MaxSstHrsId']);
										$rtds->MaxSstHrs=$hr->Hours;
									}	

										
									$rtds->save(false);
								}
							}
							
							
							foreach($data['ExtTest'] as $t)
							{
								$rextds=Rirexttestdetail::model()->find(array('condition'=>'ExtTestId=:id  AND ReceiptirId=:rir',
										 'params'=>array(':id'=>$t['Id'],':rir'=>$model->Id),));
									if(empty($rextds))
									{
										$rextds=new Rirexttestdetail;
										$rextds->ExtTestId=$t['Id'];
									}
									
									
									$rextds->ReceiptirId=$model->getPrimaryKey();
									$rextds->RirNo=$model->RirNo;
									$rextds->TestName=$t['TestName'];
									$rextds->Applicable="true";
									$rextds->ExtraInfo=isset($t['ExtraInfo'])?$t['ExtraInfo']:"";
									//$rtds->ReqDate=$t['ReqDate'];
									$rextds->Status="pending";
									$rextds->LastModified=date('Y-m-d H:i:s');
									$rextds->save(false);
								
							}
							
							
							
							$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($data['ExtTest']));
						}
						else
						{
							
						}
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							
					}                    
					break;
				
				
		 case 'examfinish':
		 
			$tm=0;
			$ca=0;
				foreach($data as $a)
				{
					$ans=new Examanswers;
					$ans->qid=$a['qid'];
					$ans->qtypeid=$a['qtypeid'];
					$ans->diffid=$a['diffid'];
					$ans->subid=$a['subid'];
					$ans->subbasketid=$a['subbasketid'];
					$ans->seqno=$a['seqno'];
					$ans->tmarks=$a['tmarks'];
					$ans->createdon=date('Y-m-d h:i:s');
					$ans->erid=$model->id;
					$q=Questions::model()->findByPk($ans->qid);
					if($ans->qtypeid==='1')
					{
						$arr=array();
						$arr[]=$a['answer'];
						$ans->answer=CJSON::encode($arr);
						$cans=$q->qoptions[0]->answer;
						
						if($cans===$ans->answer)
						{
							$ans->evaluate="correct";
							$ans->marks=$q->qoptions[0]->marks;
							$ca++;
						}
						else
						{
							$ans->evaluate="wrong";
							$ans->marks=0;
						}
						
					}
					
					if($ans->qtypeid==='2')
					{
						$ans->answer=$a['answer'];
						$cans=$q->qtruefalses[0]->truefalse;
						if($cans===$ans->answer)
						{
							$ans->evaluate="correct";
							$ans->marks=$q->qtruefalses[0]->marks;
							$ca++;
						}
						else
						{
							$ans->evaluate="wrong";
							$ans->marks=0;
						}
					}
					
					if($ans->qtypeid==='3')
					{
						$ans->answer=$a['answer'];
												
							$ans->evaluate="manual";
						$ans->marks=0;
					}
					if($ans->qtypeid==='4')
					{
							$ans->answer=$a['answer'];
							$ans->evaluate="manual";
							$ans->marks=0;
					}
					if($ans->qtypeid==='5')
					{
							$ans->answer=$a['answer'];
							$ans->evaluate="correct";
							$ca++;
							if(empty($ans->answer))
							{
								$ans->marks=0;
							}
							else
							{
								if($a['answer']==='1')
								{
									$ans->marks=$q->qvariables[0]->marks1;
								}
								if($a['answer']==='2')
								{
									$ans->marks=$q->qvariables[0]->marks2;
								}
								if($a['answer']==='3')
								{
									$ans->marks=$q->qvariables[0]->marks3;
								}
								if($a['answer']==='4')
								{
									$ans->marks=$q->qvariables[0]->marks4;
								}
								if($a['answer']==='5')
								{
									$ans->marks=$q->qvariables[0]->marks5;
								}
							}
					}
					
					$tm=$tm+$ans->marks;
					
					
					$ans->save(false);
				}
				
				$eo=Examorders::model()->findByPk($model->eoid);
				$eo->status="submit";
				$eo->save();
				
				$model->tcorrectans=$ca;
				$model->obtainedmarks=$tm;
				$model->finalizedtime=date('Y-m-d h:i:s');
				$model->save(false);
				
		 $this->_sendResponse(200, CJSON::encode($model));
			break;
			default:
			if($model->save(false))
				$this->_sendResponse(200, CJSON::encode($model));
			else
				// prepare the error $msg
				// see actionCreate
				// ...
				$msg="errot";
				$this->_sendResponse(500, $msg );
			}
    }
	
	
    public function actionDelete()
    {
				switch($_GET['model'])
			{
				// Load the respective model
				
				case 'customer':
					$model = Customerinfo::model()->findByPk($_GET['id']);      
					break;
				
				case 'droptemp':
					$model = Dropdowntemp::model()->findByPk($_GET['id']);      
					break;
					
					case 'method':
					$model = Testmethods::model()->findByPk($_GET['id']);      
					break;	
					
				case 'ssthr':
					$model = Ssthours::model()->findByPk($_GET['id']);      
					break;	
					
				case 'dropload':
					$model = Dropdownload::model()->findByPk($_GET['id']);      
					break;	
				case 'concntdelete':
				
					break;
				
				case 'deluser':
					$model = Users::model()->findByPk($_GET['id']);                    
					break;
				case 'subject':
					$model = Subject::model()->findByPk($_GET['id']);                    
					break;
				case 'question':
					$model = Question::model()->findByPk($_GET['id']);                    
					break;
                case 'answer':
					$model = Answer::model()->findByPk($_GET['id']);                    
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
