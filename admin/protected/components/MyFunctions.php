<?php

class MyFunctions 

{


   public function example() {
	   
	   $value=2;
	    return $value;
   }
   
   	
public static  function checkapiAuth($secid,$op)
{
	
	$number="";
	$msg="";
	
	$headers = getallheaders();
	
    // Check if we have the USERNAME and PASSWORD HTTP headers set?
    if(!(isset($headers['Authorization']))) {
        // Error: Unauthorized
		$result=(object)array('number'=>401,'msg'=>"Error: token not present"); 
       return $result; 
    }
    else
	{
   
	
	 $authorizationHeader = $headers['Authorization'];
    $matches = array();
    if (preg_match('/Bearer (.+)/', $authorizationHeader, $matches)) {
        if (isset($matches[1])) {
            $token = $matches[1];
        }
    }
	
	$jwt = Yii::app()->JWT->decode($token);
    // Find the user
    $user=Users::model()->find(array('condition'=>'token = :token','params'=>array(':token'=>$token)));
	
	
	if(!empty($user))
	{
		if($user->Id ===$jwt->UID)
	{
		foreach($user->userapppermissions as $s)
										{
											//$access=(object)array('C'=>$s->C,'R'=>$s->R,'U'=>$s->U,'D'=>$s->D,'A'=>$s->A,'Ch'=>$s->Ch);
											if(
											($s->C===1 && $s->section->C)||
											($s->R===1 && $s->section->R)||
											($s->U===1 && $s->section->U)||
											($s->D===1 && $s->section->D)||
											($s->A===1 && $s->section->A)||
											($s->Ch===1 && $s->section->Ch) ||
											($s->Print===1 && $s->section->Print)||
											($s->SM===1 && $s->section->SM))
											{
												$apply=1;
												$groupsections[]=empty($s->section)?"":$s->section->Group;
											}
											else
											{
												$apply=0;
											}
											$appsections[$s->SectionId]=(object)array('Id'=>$s->Id,'Apply'=>$apply,'SectionId'=>(int)$s->SectionId,'Section'=>$s->section->Section,'Group'=>empty($s->section)?"":$s->section->Group,'C'=>$s->C,'R'=>$s->R,'U'=>$s->U,'SM'=>$s->SM,
										'D'=>$s->D,'A'=>$s->A,'Ch'=>$s->Ch,'Print'=>$s->Print);
											
											
										}
		if(!$appsections[$secid]->Apply)
		{
			$result=(object)array('number'=>401,'msg'=>"Error: User doesnot have permission"); 
			return $result;
			 
		}
		else
		{
			$result=(object)array('number'=>200,'UID'=>$user->Id,'msg'=>"Valid User");
			return  $result;
		}	
	}
else
{
	return $result=(object)array('number'=>401,'msg'=>"Error: Token Invalid"); 
	
}	
		
		
		
		
	}
    else  if($user===null) {
        // Error: Unauthorized
        
		 return $result=(object)array($number=>401,$msg=>"Error: User  invalid'"); 
		 
    } 

	}
}


  	
public static  function gettokenuser()
{
	
	$number="";
	$msg="";
	
	$headers = getallheaders();
	
    // Check if we have the USERNAME and PASSWORD HTTP headers set?
    if(!(isset($headers['Authorization']))) {
        // Error: Unauthorized
		$result=(object)array('number'=>401,'msg'=>"Error: token not present"); 
       return $result; 
    }
    else
	{
   
	
	 $authorizationHeader = $headers['Authorization'];
    $matches = array();
    if (preg_match('/Bearer (.+)/', $authorizationHeader, $matches)) {
        if (isset($matches[1])) {
            $token = $matches[1];
        }
    }
	
	$jwt = Yii::app()->JWT->decode($token);
    // Find the user
    $user=Users::model()->find(array('condition'=>'token = :token','params'=>array(':token'=>$token)));
	
	
	if(!empty($user))
	{
			if($user->Id ===$jwt->UID)
		{
			
				$result=(object)array('UID'=>$user->Id,'CID'=>$user->CID); 
				return $result;
		}
		
	}

		
		
		
		
	}
     

}

public static function findWhere($array, $criteria) {
    foreach ($array as $item) {
        $matches = true;
        foreach ($criteria as $key => $value) {
            if (!isset($item[$key]) || $item[$key] !== $value) {
                $matches = false;
                break;
            }
        }
        if ($matches) {
            return $item;
        }
    }
    return null; // Return null if no match is found
}

public static function getrirdata($d,$oparams, $tbparams, $dbparams)
							{
								$std="";
								$sub=null;
								if(isset($d->SSID))
								{
									$sub=Substandards::model()->findByPk($d->SSID);	
									
							//$md=$sub->stdsubdetails[0];
						$type="";//$sub->Type;
						$substandard="";
						
							$substandard=$sub->Grade." ".$sub->ExtraInfo;
						
						$std=$sub->std->Standard." ".$substandard;
								}
						$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
								
								$designation=$d->testedBy->Designation;
								
							$testsign=empty($d->testedBy->usersignuploads)?"":$d->testedBy->usersignuploads[0]->url;
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName."(".$designation.")";	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
								$designation=$d->approvedBy->Designation;
								$approvedsign=empty($d->approvedBy->usersignuploads)?"":$d->approvedBy->usersignuploads[0]->url;
								$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName."(".$designation.")";	
							}			
												
								
							
							
							$islab=false;
							$laburls=[];
							$labnames=[];
							if(!empty($d->rirtestlabs))
							{
								foreach($d->rirtestlabs as $l)
								{
									$lab=Labaccredit::model()->findByPk($l->LabId);
									if(!empty($lab))
									{
										$islab=true;
										$labnames[]=$lab->Name;
										$laburls[]=Labaccreditlogos::model()->find(array('condition'=>'labid=:labid','params'=>array(':labid'=>$l->LabId) ));
									}
								}
							}
							
							
							
							
							$nabloparams=[];
							$nonnabloparams=[];
							
							// foreach($oparams as $o)
							// {
								// if($o->ISNABL)
								// {
									// $nabloparams[]=$o;
								// }
								// else
								// {
									
									// $nonnabloparams[]=$o;
								// }
							// }
							$img=[];		
							if(!empty($d->rirtestuploads))
							{
								$img=$d->rirtestuploads;
							}
							
							$tobsfeature=$d->test->testfeatures[0];
							$testmethod="";
							if(!empty($d->TMID))
							{
							$testmethod=Testmethods::model()->findByPk($d->TMID);
							}
							$rir=$d->rIR;
							$rirextra=empty($rir->rirextrases)?null:$rir->rirextrases[0];
							
							$tn=explode("-",$d->TestName);
							
							
							$qrimg=null;
							$pdfurl="";
							if($approvedby)
							{
								$qrimg=Yii::app()->params['base-url']."pdf/testreport/".$d->TestNo.".png";
								$pdfurl=Yii::app()->params['base-url']."pdf/testreport/".$d->rIR->LabNo."/".$d->TestNo."-".$tn[1].".pdf";
							}
							
							
							$rirob=(object)array('Id'=>$d->Id,'SampleName'=>$d->rIR->SampleName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'TNo'=>$tn[1],'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$rir->RefPurchaseOrder,
								'TestName'=>$d->TestName,'IsLab'=>$islab,'laburls'=>$laburls,'Labs'=>$labnames,
								'Customer'=>empty($d->rIR->customer)?null:$d->rIR->customer->Name,'CustomerId'=>$d->rIR->CustomerId,
								'Supplier'=>empty($d->rIR->supplier)?null:$d->rIR->supplier->Name,'SupplierId'=>$d->rIR->SupplierId,
								'CustEmail'=>empty($d->rIR->customer)?null:$d->rIR->customer->Email,
								'SampleDescription'=>$d->rIR->Description,'TUID'=>$d->TUID,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'TestDate'=>date('d-m-Y',strtotime($d->TestDate)),'TestType'=>$d->test->TType,
								'TestId'=>$d->TestId,'Standard'=>$std,'tobbasic'=>$tbparams,'dobbasic'=>$dbparams,'observations'=>$oparams,
								'ObsVertical'=>$tobsfeature->ObsVertical,'IsImg'=>$d->test->IsImg,'IsStd'=>$tobsfeature->IsStd,'IsTestMethod'=>$tobsfeature->IsTestMethod,
								'IsParamTM'=>$tobsfeature->IsParamTM,'TestMethod'=>empty($testmethod)?null:$testmethod->Method,
								'Note'=>$d->Note,'TNote'=>$d->TNote,'Industry'=>$rir->ind->Name,'QRImg'=>$qrimg,'pdfurl'=>$pdfurl,
								'StandardId'=>empty($sub)?null:$sub->StdId,'SSID'=>empty($sub)?null:$sub->Id,
								'BatchCode'=>$rir->BatchCode,'BatchNo'=>$rir->BatchNo,'ReceiptOn'=>date('d-m-Y',strtotime($d->CreationDate)),'TestNo'=>$d->TestNo,'Imgs'=>$img,
								'ReqDate'=>$d->ReqDate,'MaterialGrade'=>empty($rir->rirextrases)?null:$rirextra->Grade,'HeatNo'=>$rir->HeatNo,'HTBatchNo'=>"",
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,'ULRNo'=>$d->ULRNo,'ApprovedDate'=>$d->ApprovedDate,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>date('d-m-Y',strtotime($d->RevDate)),);
								
								return $rirob;
							
							}




 public static function flattenArray($array, $prefix = '') {
        $flattened = [];

        foreach ($array as $key => $value) {
            $newKey =  $key;

            if (is_array($value) || is_object($value)) {
                // If it's an array, loop through its elements
                if (is_array($value)) {
                    foreach ($value as $item) {
                        if (is_array($item) || is_object($item)) {
                            // If the item is an array or object, flatten it
                            $flattened += self::flattenArray((array) $item, $newKey . '.');
                        } else {
                            // If it's a scalar value, just add it as is
                            $flattened[$newKey] = $item;
                        }
                    }
                } else {
                    // If it's an object, treat it as a flat key-value pair
                    $flattened += self::flattenArray((array) $value, $newKey . '.');
                }
            } else {
                // If it's a scalar value, just add it to the flattened result
                $flattened[$newKey] = $value;
            }
        }

        return $flattened;
    }
								
public static function getrirshortdata($d,$oparams, $tbparams, $dbparams)
							{
								$std="";
								$sub=null;
								if(isset($d->SSID))
								{
									$sub=Substandards::model()->findByPk($d->SSID);	
									
							//$md=$sub->stdsubdetails[0];
						$type="";//$sub->Type;
						$substandard="";
						
							$substandard=$sub->Grade." ".$sub->ExtraInfo;
						
						$std=$sub->std->Standard." ".$substandard;
								}
						$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
								
								$designation=$d->testedBy->Designation;
								
							$testsign=empty($d->testedBy->usersignuploads)?"":$d->testedBy->usersignuploads[0]->url;
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName."(".$designation.")";	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
								$designation=$d->approvedBy->Designation;
								$approvedsign=empty($d->approvedBy->usersignuploads)?"":$d->approvedBy->usersignuploads[0]->url;
								$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName."(".$designation.")";	
							}			
												
								
							
							
							$islab=false;
							$laburls=[];
							$labnames=[];
							if(!empty($d->rirtestlabs))
							{
								foreach($d->rirtestlabs as $l)
								{
									$lab=Labaccredit::model()->findByPk($l->LabId);
									if(!empty($lab))
									{
										$islab=true;
										$labnames[]=$lab->Name;
										$laburls[]=Labaccreditlogos::model()->find(array('condition'=>'labid=:labid','params'=>array(':labid'=>$l->LabId) ));
									}
								}
							}
							
							
							
							
							$nabloparams=[];
							$nonnabloparams=[];
							
							// foreach($oparams as $o)
							// {
								// if($o->ISNABL)
								// {
									// $nabloparams[]=$o;
								// }
								// else
								// {
									
									// $nonnabloparams[]=$o;
								// }
							// }
							$img=[];		
							if(!empty($d->rirtestuploads))
							{
								$img=$d->rirtestuploads;
							}
							
							$tobsfeature=$d->test->testfeatures[0];
							$testmethod="";
							if(!empty($d->TMID))
							{
							$testmethod=Testmethods::model()->findByPk($d->TMID);
							}
							$rir=$d->rIR;
							$rirextra=empty($rir->rirextrases)?null:$rir->rirextrases[0];
							
							$tn=explode("-",$d->TestName);
							
							
							$qrimg=null;
							$pdfurl="";
							if($approvedby)
							{
								$qrimg=Yii::app()->params['base-url']."pdf/testreport/".$d->TestNo.".png";
								$pdfurl=Yii::app()->params['base-url']."pdf/testreport/".$d->rIR->LabNo."/".$d->TestNo."-".$tn[1].".pdf";
							}
							
							
							$rirob=(object)array('Id'=>$d->Id,'RIRId'=>$d->RIRId,
								'TestNo'=>$d->TestNo,'LabNo'=>$d->rIR->LabNo,'BatchCode'=>$rir->BatchCode,'BatchNo'=>$rir->BatchNo,
								'TestName'=>$d->TestName,'TUID'=>$d->TUID,'SampleName'=>$d->rIR->SampleName,'HeatNo'=>$rir->HeatNo,'HTBatchNo'=>"",								
								'Customer'=>empty($d->rIR->customer)?null:$d->rIR->customer->Name,
								'Supplier'=>empty($d->rIR->supplier)?null:$d->rIR->supplier->Name,
								'Standard'=>$std,'TestMethod'=>empty($testmethod)?null:$testmethod->Method,
								'tobbasic'=>$tbparams,'dobbasic'=>$dbparams,'observations'=>$oparams,
								'Status'=>$d->Status,
								'ReceiptOn'=>date('d-m-Y',strtotime($d->CreationDate)),
								'ReqDate'=>$d->ReqDate,
								'TestDate'=>date('d-m-Y',strtotime($d->TestDate)),								
								'Remark'=>$d->Remark,
								'ULRNo'=>$d->ULRNo,
								'Imgs'=>$img,
								'TestedBy'=>$testedby,'ApprovedBy'=>$approvedby,'ApprovedDate'=>$d->ApprovedDate,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>date('d-m-Y',strtotime($d->RevDate)),);
								
								$flattenedData = MyFunctions::flattenArray($rirob);
								
								return $flattenedData;
							
							}
								

	
	
	public static function gettestui($rtds,$pdf)
	{
		
		$appset=Settingsfirm::model()->find();
		// $applogo=Applogos::model()->find();
		 $applogo=empty($applogo)?null:$applogo->url;
		
			
			$tuid=$rtds->TUID;
			switch($tuid)
			{
				case 'CHEM':	$msg=MyTestUI::getchemicalui($rtds,$pdf);break;
				
				case 'TENSILE':	$msg=MyTestUI::gettensileui($rtds,$pdf);break;
				
				case 'IMP':		$msg=MyTestUI::getimpactui($rtds,$pdf);break;
				
				case 'RBHARD':
				case 'RCHARD':
				case 'MVHARD':
				case 'VHARD':
				case 'BHARD':	$msg=MyTestUI::gethardnessui($rtds,$pdf);break;
				
				case 'PROOF': $msg=MyTestUI::getproofloadui($rtds,$pdf);break;
				
				case 'TORQ': $msg=MyTestUI::gettorqueui($rtds,$pdf);break;
				
				case 'CARBDC': $msg=MyTestUI::getcarbdecarbui($rtds,$pdf);break;
				
				case 'CASE': $msg=MyTestUI::getcaseui($rtds,$pdf);break;
				
				
				case 'MSTRUCT':  $msg=MyTestUI::getmicrostructui($rtds,$pdf); break;
				case 'GRAIN':  $msg=MyTestUI::getgrainsizeui($rtds,$pdf); break;
				
				case 'MDCARB':  $msg=MyTestUI::getmicrodecarbui($rtds,$pdf); break;
				case 'MCASE':  $msg=MyTestUI::getmicrocaseui($rtds,$pdf); break;
				case 'MCOAT':  $msg=MyTestUI::getmicrocoatui($rtds,$pdf); break;
				
				
				case 'THREAD':  $msg=MyTestUI::getthreadui($rtds,$pdf); break;
				case 'WEDGE':  $msg=MyTestUI::getwedgeui($rtds,$pdf); break;
				case 'HET':  $msg=MyTestUI::gethetui($rtds,$pdf); break;
				case 'SHEAR':  $msg=MyTestUI::getshearui($rtds,$pdf); break;
				case 'FULLBOLT':  $msg=MyTestUI::getfullboltui($rtds,$pdf); break;
				case 'IRK':  $msg=MyTestUI::getirkui($rtds,$pdf); break;
				case 'IRW':  $msg=MyTestUI::getirwui($rtds,$pdf); break;
				
				case 'MET':$msg=MyTestUI::getmicroetchui($rtds,$pdf); break;
				case 'BEND': $msg=MyTestUI::getbendui($rtds,$pdf); break;			
				
				
				default:$msg=MyTestUI::getdefaultui($rtds,$pdf); break;
				
				
			}
			
			
			
		
                  
				   
				   return $msg;
	}
 
 public static function sendmail($etype,$toemails,$maildata)
	{
		$data="";
		switch($etype)
		{
			case 'approve':
			
					//---add to user notification---//
									$not=new Notifications;
									$not->Notifications=$maildata->LabNo."/".$maildata->BatchCode." ".$maildata->TestName." -Test for approval ";
									$not->CreatedAt=date('Y-m-d H:i:s');
									$not->AppSecId=$maildata->SectionId; //--not found
									$not->save(false);
									$sentmails=['sachin@infocodec.com','gaganjosan@infocodec.com'];
									foreach($toemails as $e)
									{
										$u=Users::model()->find(array('condition'=>'Email=:email AND Status="1"',
												'params'=>array(':email'=>$e)));
												if(!empty($u))
												{
													$un=new Usernotifications;
													$un->NotId=$not->getPrimaryKey();
													$un->UserId=$u->Id;
													$un->Status=0;
													$un->save(false);
												}
												
												$sentmails[]=$e;
									}
											
											
										
			
			
					  $msg ='<table width="100%" cellspacing="0" cellpadding="0" border="0" align="left" style="max-width:800px;background:#fff;margin:0px;">
					  <tr><td style="padding:10px;font-size:15px;" >
					  <p>Dear Sir, </p>
					  <p> You got report for approval </h4></p>
						<table width="100%" cellspacing="0" cellpadding="0" border="0" align="left" style="max-width:800px;background:#fff;margin:0px;">
						<tr><td>Customer</td><td>'.$maildata->Customer.'</td></tr>
						<tr><td>Test</td><td>'.$maildata->TestName.'</td></tr>
						<tr><td>Lab No</td><td>'.$maildata->LabNo.'</td></tr>
						<tr><td>Tested On </td><td>'.$maildata->TestedOn.'</td></tr>
						<tr><td>Due Date</td><td>'.$maildata->ReqDate.'</td></tr>
						</table>
                         </td></tr>';
                  $msg .='</table>';
				  	$msg.='<p style="margin-top:10px;font-size:13px;"> To approve/reject test '.$maildata->TestName.' click below link
						<a style="padding:4px;min-height:40px;background:#006aca;color:#fff;"
						 href="'.Yii::app()->params['base-url'].'/#!/app/tests/'.$maildata->TestId.'/'.$maildata->SecId.'" target="_blank">
						  Approve / Reject
						  
						  </a>
						</p>';
				  $mset=Settingsmail::model()->find();

                  $mail = new YiiMailer;
                  $mail->IsSMTP();
                  $mail->setSmtp(  $mset->Server,  $mset->Port,  $mset->Encrypt ,true,  $mset->Email,  $mset->Password);
                  $mail->setFrom($mset->Email,Yii::app()->params['appname']);
                  $mail->setTo($sentmails);
                  $mail->setSubject('Pending Approval Test '.$maildata->TestName.' Test No- '.$maildata->LabNo);
                  $mail->setBody($msg);
                  $mail->SMTPDebug =3;

                  if($mail->send())
                  {
                    //$transaction->commit();
                    $data=(object)array('msg'=>"email sent to ".json_encode($sentmails)." Please check spam also.");
                    
                  }
                  else
                  {
                     $data=(object)array('msg'=>"email sending error to ");
                    
                   // $data=$mail->getError();
                  }					
			break;
		}
		
		return $data;
	}
 
 public static function parseTree($tid) {
		   $branch = array();
			$c=Industry::model()->find(array('condition'=>'Id=:id ',
														'params'=>array(':id'=>$tid)));
			if(empty($c))
			{
				return $branch;
			}
			else	
			{			
			foreach ($c->industries as $element) {
				
			if (!empty($element->ParentId)  ) {
		
			
				   $ch=(object)array('Id'=>$element->Id,'Name'=>$element->Name,'IsP'=>true,'HasSubInd'=>$element->HasSubInd,'ParentId'=>$element->ParentId,
				  'Children'=>MyFunctions::parseTree($element->Id),'Status'=>$element->Status,);
					$branch[] = $ch;
				}
			}

			return $branch;
			}
		}
		
		
 public static function getParentCat($parent_id,array &$parents = [])
				{

				if(empty($parent_id)) {
				return [];
				}

				$parent_category = Industry::model()->findByPk($parent_id);
				$parents[] = $parent_category->Name;

				if(!empty($parent_category->ParentId)) {
				MyFunctions::getParentCat($parent_category->ParentId,$parents);
				}

				return $parents;
				}



public static function getquoteui($paramquote)
{
	$appset=Settingsfirm::model()->find();
						$i=$paramquote;
							$qdetails=[];
							foreach($i->quotationdetails as $d)
							{
								$testcond=Testconditions::model()->findByPk($d->TestCondId);
								$testcondition=$testcond->Name;
								
								$test=Tests::model()->findByPk($d->TestId);
								$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
								
								if(empty($d->SubStdId))
								{
									
									$substdname="";
									$substd="";
								}
								else
								{
									$substd=Substandards::model()->findByPk($d->SubStdId);
								$substdname=$substd->std->Standard." - ".$substd->Grade;
								}
								
								$plan=null;
								
								if(!empty($d->PlanId))
								{
									$pl=Stdsubplans::model()->findByPk($d->PlanId);
									$parameters=[];
									foreach($pl->stdsubplandetails as $pd)
									{
										$parameters[]=(object)['Parameter'=>$pd->sSD->p->Parameter,'PSymbol'=>$pd->sSD->p->PSymbol,
						'PCatId'=>$pd->sSD->p->PCatId,'CatName'=>empty($pd->sSD->p->pCat)?"":$pd->sSD->p->pCat->CatName,];
										
									}
									
									$plan=(object)array('Name'=>$pl->Name,'Parameters'=>$parameters);
								}
								else if(!empty($d->SubStdId))
								{
									$sds=[];
									$stdsubdets=$substd->stdsubdetails;
									foreach($stdsubdets as $f)
									{
											$sds[]=(object)['Parameter'=>$f->p->Parameter,'PSymbol'=>$f->p->PSymbol,
							'PCatId'=>$f->p->PCatId,'CatName'=>empty($f->p->pCat)?"":$f->p->pCat->CatName,];
							
							;
									}
									
									$plan=(object)array('Name'=>"",'Parameters'=>$sds);
								}
								
								
							
								$qdetails[]=(object)array('Id'=>$d->Id,'QId'=>$d->QId,'IndId'=>$d->IndId,
								'PIndId'=>$d->PIndId,'SubIndId'=>$d->SubIndId,'SampleName'=>$d->SampleName,
								'SampleWeight'=>$d->SampleWeight,'TAT'=>$d->TAT,
								'Industry'=>implode(" - ",array_reverse(MyFunctions::getParentCat($d->IndId))),'TestCondId'=>$d->TestCondId,'TestId'=>$d->TestId,
								'Plan'=>$plan,'PlanName'=>"",
								'testcondition'=>$testcondition,'TestName'=>$test->TestName,'SubStdId'=>$d->SubStdId,
								'SubStdName'=>$substdname,'TestMethod'=>empty($testmethod)?null:$testmethod->Method,
								'TestMethodId'=>$d->TestMethodId,'Price'=>$d->Price,'Qty'=>$d->Qty,'Tax'=>$d->Tax,'Total'=>$d->Total,
								);
							}
							
							$assignuser="";
							$quote=(object)['Id'=>$i->Id,'QNo'=>$i->QNo,'Customer'=>$i->cust,'CustId'=>$i->CustId,
							'QDate'=>$i->QDate,'ValidDate'=>$i->ValidDate,'TotTax'=>$i->TotTax,'Tax'=>$i->Tax,
							'SubTotal'=>$i->SubTotal,'Discount'=>($i->SubTotal*$i->Discount)/100,'Total'=>$i->Total,'Note'=>$i->Note,'Status'=>$i->Status,
							'CreatedBy'=>$i->CreatedBy,'CreatorName'=>"",'AssignUser'=>$assignuser,'AssignedTo'=>$i->AssignedTo,
							'ApprovedBy'=>'','VerifiedBy'=>'',
							'Details'=>$qdetails];
	$msg= 	
						'<style>'.file_get_contents(Yii::app()->params['base-url'].'css/invoice.css').'</style>';	
						
						 $applogo=Applogos::model()->find();
		 $applogo=empty($applogo)?null:$applogo->url;
						
						$msg.='<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Test</title>
   
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet" /> 
	
	<link href="css/mystyle.css" rel="stylesheet"> 
	
  </head>
  <body>
    <div  class="invoice overflow-auto" style="border-top: 0px solid #3989c6 !important;">  <main> ';
						$msg.='
						
           <table class="table " width="100%" cellspacing="0" cellpadding="4" border="0" align="left" style="background:#fff !important;max-width:800px;border-top: 0px solid #3989c6 !important;font-size:14px;">
			<thead style="margin-bottom:0px;background-color:#fff !important;border-top: 0px solid #3989c6 !important;" >
			<tr class="contacts " style="border-bottom:2px solid #3f51b5 !important;" >
			<td colspan="3" style="padding:10px;font-size:12px;vertical-align:middle;border-bottom:2px solid #3f51b5 !important;">
			<div> <img src="'.$applogo.'" style="height:50px;float: left;"> <div class="">'.$appset->Name.'</div><div class="">'.$appset->Address.'</div></div></td>
			<td style="background:#fff;font-size:14px;border-width:0px;text-align:left;border-bottom:2px solid #3f51b5 !important;"  colspan="2" class="company-details" ></td>
			</tr> 
			
			<tr class="invoice-to" >
			<th  cellpadding="4" colspan="3" style="padding:10px;text-align:left;"> 
						QUOTATION TO: <br>
                        <span class="to" style="font-weight:600;margin-bottom:0px;margin-top:0px;">'.$i->cust->Name.'</span><br>
                        <span class="to" style="margin-bottom:0px;">'.$i->cust->Address.'</span><br>
                        <span class="to" style="margin-bottom:0px;">'.$i->cust->Email.'</span></th>
						<th   colspan="2" style="padding:10px;"><span class="to" style="font-size:14px;font-weight:700;margin-bottom:0px;color:#3f51b5;letter-spacing:0px;"> QNO.'.$i->QNo.'</span><br>
                      <span class="to" style="font-size:12px;margin-bottom:0px;">Issue Date: '.date('d-m-Y',strtotime($i->QDate)).'</span><br>
                     <span class="to" style="font-size:12px;margin-bottom:0px;">Valid Till:  '.date('d-m-Y',strtotime($i->ValidDate)).'</span> </th>
						</tr></thead> '; 
						$msg.='<tr >
                            <td style="width:25px;text-align: left;background-color:#eceff1; border-bottom: 1px solid #fff;">#</td>
                            <td style=" width:250px;text-align: left;background-color:#eceff1 ;border-bottom: 1px solid #fff;" class="text-left">DESCRIPTION</td>
                            <td style=" text-align: left;border-bottom: 1px solid #fff;background-color:#eceff1 ;"  class="text-right">PRICE</td>
                            <td style="width:35px; text-align: left;padding: 15px;background-color:#eceff1 ;border-bottom: 1px solid #fff;"  class="text-right">QTY</td>
                            <td style=" text-align: right;border-bottom: 1px solid #fff;background-color:#eceff1 ;" class="text-right">TOTAL</td>
                        </tr>      ';
					$idx=1;
					foreach($qdetails as $d)
					{
						
					$msg.='<tr style="border-bottom:1px solid #777 ;">
                            <td style="text-align: left;  padding: 5px;width:25px;border-bottom:1px solid #ddd !important;" class="no">'.$idx.'</td>
                            <td style="color:#000;text-align: left; padding: 5px;width:250px;background-color:#eceff1;border-bottom:1px solid #ddd !important;" class="unit text-left">	
							<table class="table-borderless" cellpadding="0" style="font-size:11px;">
							
							<tr ><td  style="padding:4px;" class=" fw-bold p-0"  >Industry : '.$d->Industry.'</td></tr>
							 
							<tr  ><td style="padding:4px;" class="  fw-bold">Test Condition : '.$d->testcondition.'</td></tr>
							<tr  ><td style="padding:4px;" class="  fw-bold">Sample Name : '.$d->SampleName.' - '.$d->SampleWeight.' TAT:'.$d->TAT.'</td></tr>
							<tr><td style="padding:4px;" class="  fw-bold">Test : '.$d->TestName.'</td></tr>
							<tr><td style="padding:4px;" class="  fw-bold">Standard : '.isset($d->SubStdName)?$d->SubStdName:null.'</td></tr>
							<tr><td style="padding:4px;" class="  fw-bold">TestMethod : '.$d->TestMethod.'</td></tr>
							<tr><td style="padding:4px;" class="  fw-bold">Plan : '.$d->PlanName.'</td></tr>
							<tr><td style="padding:4px;" class="  fw-bold">Parameters : </td></tr>
							</table>
							
							</td>
                            <td style="text-align: left; padding: 5px;background-color:#ddd;border-bottom:1px solid #ddd !important;" class="unit text-right"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>
 '.$d->Price.'</td>
                            <td style="width:35px;text-align: left; padding: 5px;background-color:#eceff1 ;border-bottom:1px solid #ddd !important;" class="tax text-right">'.$d->Qty.'</td>
                            <td style="text-align: right; padding: 10px;background-color: #3989c6;border-bottom:1px solid #ddd !important; color: #fff;" class="total text-right"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>'.number_format($d->Total, 2, '.', '').'</td>
                        </tr>';
						$idx++;
					}
					
                        
                   $msg.=' 
                    
                        <tr style="padding:10px;font-size: 14px;">
                            <td style="padding:10px;" colspan="2"></td>
                            <td style="padding:10px;" colspan="2">SUBTOTAL</td>
                            <td style="padding:10px;text-align: right;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>
 '.number_format($i->SubTotal, 2, '.', '').'</td>
                        </tr>';
						if($i->Discount>0)
						{
					$msg.=	'<tr style="padding:10px;font-size: 14px;">
                            <td style="padding:10px;" colspan="2"></td>
                            <td style="padding:10px;" colspan="2">DISCOUNT</td>
                            <td style="padding:10px;text-align: right;" ><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>
 '.number_format($i->Discount, 2, '.', '').'</td>
                        </tr>';
						}
                       
						$msg.='  <tr style="padding:10px;font-size: 14px;">
                            <td style="padding:10px;" colspan="2"></td>
                            <td style="padding:10px;" colspan="2">TAX</td>
                            <td style="padding:10px;text-align: right;" ><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>
 '.number_format($i->TotTax, 2, '.', '').'</td>
                        </tr>
                        <tr style=" color: #3f51b5;font-size: 15px;border-top: 1px solid #3989c6;padding:10px;border-bottom: 1px solid #3989c6 !important;">
                            <td style="padding:10px;border-bottom: 1px solid #3989c6;" colspan="2"></td>
                            <td style="padding:10px;border-bottom: 1px solid #3989c6;background:#E1F5FE;" colspan="2">GRAND TOTAL</td>
                            <td style="padding:10px;text-align: right;border-bottom: 1px solid #3989c6;background:#E1F5FE;" ><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>
 '.round($i->Total,0).'</td>
                        </tr>
                   ';
						
						$msg.='<tr><td colspan="12" style="padding:10px;"> <div class="thanks">Thank you!</div>
                <div class="notices" style="font-size:12px;">
                    <div>NOTICE:</div>
                    <div class="notice" style="font-size:10px;" >'.$i->Note.'</div>
                </div></td></tr>';
						$msg.='</table></main></div></body></html>';
	
	return $msg;
}
 
 
 
 
 

}