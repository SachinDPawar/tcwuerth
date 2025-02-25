<?php

class MyTestdata 

{
	
	
	public static function gettestdata($d)
	{
									
				$tuid=$d->TUID;
							switch($tuid)
							{
								
								
									case 'IMP':
									if(true)
											{
												
												//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												//---Observatory Parameters-----//
												
												
												
												
													$mdstdsid=$d->rIR->MdsTdsId;
												
													$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
														'params'=>[':mtid'=>$mdstdsid,':tid'=>$d->TestId]]);
									
												
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
														if($d->test->testfeatures[0]->IsStd)
														{
															foreach($d->rirtestobs as $e)
															{
																
																
																
																	if(!empty($mdstdstest))
																	{
																		$mdcr=Mdstdstestobsdetails::model()->find(array(
																'condition'=>'MTTID=:mttid AND PID=:pid',
																 'params'=>array(':mttid'=>$mdstdstest->Id,':pid'=>$e->TPID),));
																 
																	 if(!empty($mdcr))
																		{
																			$specmin=$mdcr->SpecMin." min";
																		}
																		
																	}
																	
																$cr=Stdsubdetails::model()->find(array(
																'condition'=>'SubStdId=:ID AND PId=:eid',
																 'params'=>array(':ID'=>$d->SSID,':eid'=>$e->TPID),));
																
if (is_null($e->SpecMin)) {
    if (!empty($mdcr)) {
        $specmin = !is_null($mdcr->SpecMin) 
            ? $mdcr->SpecMin : (!empty($cr) && !is_null($cr->SpecMin) 
                ? $cr->SpecMin : "");
    } else {
        $specmin = (!empty($cr) && !is_null($cr->SpecMin)) 
            ? $cr->SpecMin : "";
    }
} else {
    $specmin = !is_null($e->SpecMin) ? $e->SpecMin : "";
}
																
	if (is_null($e->SpecMax)) {
    if (!empty($mdcr)) {
        $specmax = !is_null($mdcr->SpecMax) 
            ? $mdcr->SpecMax  : (!empty($cr) && !is_null($cr->SpecMax) 
                ? $cr->SpecMax : "");
    } else {
        $specmax = (!empty($cr) && !is_null($cr->SpecMax)) 
            ? $cr->SpecMax   : "";
    }
} else {
    $specmax = !is_null($e->SpecMax) ? $e->SpecMax : "";
}					
																
															
																
																
																 if(!empty($cr))
																 {
																	$tobparam=$cr->p;
																 }
																 else
																 {
																	$tobparam=Testobsparams::model()->findbyPk($e->TPID); 
																 }
										
																	if(!empty($cr))
																	{										
																	
																	
																	$obsvalues=[];
																	$avg=0;
																	if(empty($e->rirtestobsvalues))
																	{
																		for($im=0;$im<$tobparam->MR;$im++)
																		{
																		$obsvalues[]=(object)array('Value'=>"");
																		}
																	}
																	else
																	{
																		foreach($e->rirtestobsvalues as $rob)
																		{
																			
																		$obsvalues[]=(object)array('Id'=>$rob->Id,'RTOBID'=>$rob->RTOBID,
												'Value'=>$rob->Value,);
												$avg=$avg+(float)$rob->Value;
																		}
																	}
																	
																	$avg=$avg/$tobparam->MR;
																	
																	
																	
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->PUnit,
																	'PSymbol'=>$tobparam->PSymbol,'Param'=>$tobparam->Parameter,'SpecMin'=>$specmin,
																	'SpecMax'=>$specmax,'Values'=>$e->rirtestobsvalues,'PCatId'=>$tobparam->PCatId,																	
																	'CatName'=>empty($tobparam->pCat)?"":$tobparam->pCat->CatName,'IsSpec'=>$e->tP->IsSpec,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,'Avg'=>$avg,
																	'PDType'=>$tobparam->PDType,
																		);
																
																	
																	

																	}
																	else
																	{
																		
																	$obsvalues=[];
																	$avg=0;
																	if(empty($e->rirtestobsvalues))
																	{
																		for($im=0;$im<$tobparam->MR;$im++)
																		{
																		$obsvalues[]=(object)array('Value'=>"");
																		}
																	}
																	else
																	{
																		foreach($e->rirtestobsvalues as $rob)
																		{
																			
																		$obsvalues[]=(object)array('Id'=>$rob->Id,'RTOBID'=>$rob->RTOBID,
												'Value'=>$rob->Value,);
												$avg=$avg+(float)$rob->Value;
																			
																		}
																	}
																	
																	$avg=$avg/$tobparam->MR;
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->PUnit,
																	'PSymbol'=>$tobparam->PSymbol,'Param'=>$tobparam->Parameter,'SpecMin'=>null,
																	'SpecMax'=>null,'Values'=>$e->rirtestobsvalues,'PCatId'=>$tobparam->PCatId,																	
																	'CatName'=>empty($tobparam->pCat)?"":$tobparam->pCat->CatName,'IsSpec'=>$e->tP->IsSpec,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,'Avg'=>$avg,
																	'PDType'=>$tobparam->PDType,
																		);
																
																	}
														}					
														}
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											return $rtds;
											}
											
								break;
								
								
								
								case 'IRW':
								if(true)
											{
												
											
												//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												
												
												//---Observatory Parameters-----//
												
												
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
														
															foreach($d->rirtestobs as $e)
															{
																$observations=[];
																$p=Testobsparams::model()->findByPk($e->TPID);
															
																	if(!empty($p))
																	{
																			if(empty($e->rirtestobsvalues))
																			{
																				$avgThinA=0;$avgThinB=0;$avgThinC=0;$avgThinD=0;
																	$avgThickA=0;$avgThickB=0;$avgThickC=0;$avgThickD=0;
																				$observations[]=(object)['ThinA'=>$avgThinA,'ThinB'=>$avgThinB,'ThinC'=>$avgThinC,'ThinD'=>$avgThinD,
																			'ThickA'=>$avgThickA,'ThickB'=>$avgThickB,'ThickC'=>$avgThickC,'ThickD'=>$avgThickD,];
																			}
																			else
																			{
																				if(!is_null($e->rirtestobsvalues[0]->Value))
																	{	
																		$obsvals=json_decode($e->rirtestobsvalues[0]->Value);															
																		if(count($obsvals)>0)
																		{	
																	$avgThinA=0;$avgThinB=0;$avgThinC=0;$avgThinD=0;
																	$avgThickA=0;$avgThickB=0;$avgThickC=0;$avgThickD=0;
																			
																			foreach($obsvals as $v)
																			{
																			$observations[]=(object)['ThinA'=>$v->ThinA,'ThinB'=>$v->ThinB,'ThinC'=>$v->ThinC,'ThinD'=>$v->ThinD,
																			'ThickA'=>$v->ThickA,'ThickB'=>$v->ThickB,'ThickC'=>$v->ThickC,'ThickD'=>$v->ThickD,];
																			$avgThinA=$avgThinA+(float)$v->ThinA;
																			$avgThinB=$avgThinB+(float)$v->ThinB;
																			$avgThinC=$avgThinC+(float)$v->ThinC;
																			$avgThinD=$avgThinD+(float)$v->ThinD;
																			
																			$avgThickA=$avgThickA+(float)$v->ThickA;
																			$avgThickB=$avgThickB+(float)$v->ThickB;
																			$avgThickC=$avgThickC+(float)$v->ThickC;
																			$avgThickD=$avgThickD+(float)$v->ThickD;
																			}
																			
																			$num=count($obsvals);
																			$avgThinA=$avgThinA/$num;$avgThinB=$avgThinB/$num;$avgThinC=$avgThinC/$num;$avgThinD=$avgThinD/$num;
																			$avgThickA=$avgThickA/$num;	$avgThickB=$avgThickB/$num;	$avgThickC=$avgThickC/$num;	$avgThickD=$avgThickD/$num;
																			
																			$observations[]=(object)['ThinA'=>$avgThinA,'ThinB'=>$avgThinB,'ThinC'=>$avgThinC,'ThinD'=>$avgThinD,
																			'ThickA'=>$avgThickA,'ThickB'=>$avgThickB,'ThickC'=>$avgThickC,'ThickD'=>$avgThickD,];
																		}
																	}
																	
																
																			}
																
																	
																	

																	}
																	
															}					
														
																													
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											
												return $rtds;
											}
								
								
								break;
								
								case 'IRK':
								if(true)
											{
												
												//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												
												//---Observatory Parameters-----//
												
												
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														$observations=[];
														
															foreach($d->rirtestobs as $e)
															{
																
																
																	
																
																
																
																$p=Testobsparams::model()->findByPk($e->TPID);
															$key=$p->PSymbol;
																	if(!empty($p))
																	{	
																if($key==='Obs')
																{
																	$obsvalues=[];
																	foreach($e->rirtestobsvalues as $rob)
																		{
																			$rv=empty($rob->Value)?[]:json_decode($rob->Value);
																			if(!empty($rv))
																			{
																				
																			$obsvalues[]=$rv;
																			}
																	
																			
																		}
																	
																		$obsvals=$obsvalues;//empty($e->rirtestobsvalues)?[]:($e->rirtestobsvalues);
																	
																		
																}
																else
																{
																$obsvals=empty($e->rirtestobsvalues)?null:$e->rirtestobsvalues;															
																}		
																			
																			
																$observations[$key]=(object)['Id'=>$e->Id,'TPID'=>$e->TPID,
																'PSymbol'=>$key,'Values'=>$obsvals
																			];
																		
																	
																	
																
																	
																	

																	}
																	
															}					
														
																													
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											return $rtds;
											
											}
								
								
								break;
								
								
								case 'CASE':
								
								if(true)
											{
												
											//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												
												//---Observatory Parameters-----//
												
												
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
														if($d->test->testfeatures[0]->IsStd)
														{
															foreach($d->rirtestobs as $e)
															{
																$cr=Stdsubdetails::model()->find(array(
																'condition'=>'SubStdId=:ID AND PId=:eid',
																 'params'=>array(':ID'=>$d->SSID,':eid'=>$e->TPID),));
										
																	if(!empty($cr))
																	{										
																		$act="";
																	if($e->tP->PDType==='N')
																	{											
																		$act=$cr->SpecMin."-".$cr->SpecMax;
																	}
																	$perm="";
																	if($e->tP->PDType==='N')
																	{											
																		$perm=$cr->PermMin."-".$cr->PermMax;
																	}
																	
																																																			
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$cr->p->Parameter,'SpecMin'=>$cr->SpecMin,
																	'SpecMax'=>$cr->SpecMax,'Values'=>empty($e->rirtestobsvalues)?[]:json_decode($e->rirtestobsvalues[0]->Value),'PCatId'=>$e->tP->PCatId,
																	
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,'Permissible'=>$perm,'Acceptable'=>$act,
																		);
																
																	
																	

																	}
																	else
																	{
																		$p=Testobsparams::model()->findByPk($e->TPID);
																		if(!empty($p))
																		{										
																			
																		
																		$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																		'PSymbol'=>$e->tP->PSymbol,'Param'=>$p->Parameter,'Values'=>empty($e->rirtestobsvalues)?[]:json_decode($e->rirtestobsvalues[0]->Value),'PCatId'=>$e->tP->PCatId,
																		'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																		'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																		'PDType'=>$e->tP->PDType,
																			);
																	
																		
																		

																		}
																	}
																	
														}					
													}
														else
														{
															foreach($d->rirtestobs as $e)
															{
																
																$p=Testobsparams::model()->findByPk($e->TPID);
																	if(!empty($p))
																	{										
																		
																	
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$p->Parameter,'Values'=>json_decode($e->rirtestobsvalues[0]->Value),'PCatId'=>$e->tP->PCatId,
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,
																		);
																
																	
																	

																	}
																	
														}					
														
														}															
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											
											return $rtds;
											}
								
								
								break;
								
								case 'VHARD':
								case 'RCHARD':
								case 'RBHARD':
								case 'MVHARD':
								case 'BHARD':
									if(true)
											{
												
											//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												
												//---Observatory Parameters-----//
													$mdstdsid=$d->rIR->MdsTdsId;
												
												$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$d->TestId]]);
									
												
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
														if($d->test->testfeatures[0]->IsStd)
														{
															foreach($d->rirtestobs as $e)
															{
																
																$specmin=$e->SpecMin;
																$specmax=$e->SpecMax;
																if(empty($specmin) || empty($specmax) )
																{ 
																	if(!empty($mdstdstest))
																	{
																		$cr=Mdstdstestobsdetails::model()->find(array(
																'condition'=>'MTTID=:mttid AND PID=:pid',
																 'params'=>array(':mttid'=>$mdstdstest->Id,':pid'=>$e->TPID),));
																 
																	 if(!empty($cr))
																		{
																			$specmin=empty($cr->SpecMin)?null:$cr->SpecMin;
																			$specmax=empty($cr->SpecMax)?null:$cr->SpecMax;
																		}
																		
																	}
																	
										
																}	
																
																
																if(empty($e->rirtestobsvalues))
																	{
																	
																		$allobs=[];
																		$allobs[]=(object)['SValue'=>"",'CValue'=>""];
																		$values=$allobs;
																	}
																	else
																	{
																		$values=json_decode($e->rirtestobsvalues[0]->Value);
																	}
										
																
																	
																	
																																		
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$e->tP->Parameter,'SpecMin'=>$specmin,
																	'SpecMax'=>$specmax,'Values'=>$values,'PCatId'=>$e->tP->PCatId,
																	
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,
																		);
																
																	
																	

																
																	
																	
														}					
													}
													else
													{
															foreach($d->rirtestobs as $e)
															{
																
																$p=Testobsparams::model()->findByPk($e->TPID);
																	if(!empty($p))
																	{										
																		
																	
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$p->Parameter,'Values'=>json_decode($e->rirtestobsvalues[0]->Value),'PCatId'=>$e->tP->PCatId,
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,
																		);
																
																	
																	

																	}
																	
														}					
														
														}															
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											return $rtds;
											}
															
								break;
								
								case 'TORQ':
									if(true)
											{
												
												//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												
												//---Observatory Parameters-----//
												
												
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
														if($d->test->testfeatures[0]->IsStd)
														{
															foreach($d->rirtestobs as $e)
															{
																
																
																
																$cr=Stdsubdetails::model()->find(array(
																'condition'=>'SubStdId=:ID AND PId=:eid',
																 'params'=>array(':ID'=>$d->SSID,':eid'=>$e->TPID),));
										
																	if(!empty($cr))
																	{										
																		$act="";
																	if($e->tP->PDType==='N')
																	{											
																		$act=$cr->SpecMin."-".$cr->SpecMax;
																	}
																	$perm="";
																	if($e->tP->PDType==='N')
																	{											
																		$perm=$cr->PermMin."-".$cr->PermMax;
																	}
																	
																	
																foreach($e->rirtestobsvalues as $v)
																{
																		$observations[][$e->tP->PSymbol]=$v->Value;
																
																}
																
																
																
																	
																	

																	}
																	else
																	{
																		$p=Testobsparams::model()->findByPk($e->TPID);
																		if(!empty($p))
																		{									
																			foreach($e->rirtestobsvalues as $v)
																			{
																			$observations[0][$e->tP->PSymbol][]=(object)['Value'=>$v->Value];
																			
																			}
																
																		
																		// $observations[][$e->tP->PSymbol]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																		// 'PSymbol'=>$e->tP->PSymbol,'Param'=>$p->Parameter,'Values'=>$e->rirtestobsvalues,'PCatId'=>$e->tP->PCatId,
																		// 'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																		// 'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																		// 'PDType'=>$e->tP->PDType,
																			// );
																	
																		
																		

																		}
																	}
																	
														}					
													}
														else
														{
															foreach($d->rirtestobs as $e)
															{
																
																$p=Testobsparams::model()->findByPk($e->TPID);
																	if(!empty($p))
																	{										
																		
																	
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$p->Parameter,'Values'=>$e->rirtestobsvalues,'PCatId'=>$e->tP->PCatId,
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,
																		);
																
																	
																	

																	}
																	
														}					
														
														}															
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											return $rtds;
											}
								break;
								
								case 'MCOAT':
								case 'MDCARB':
								case 'MCASE':
									if(true)
											{
												
												
												//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												
												//---Observatory Parameters-----//
												
												
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
															foreach($d->rirtestobs as $e)
															{
																
																$p=Testobsparams::model()->findByPk($e->TPID);
															
																	if(!empty($p))
																	{	
																if(!is_null($e->rirtestobsvalues))
																	{	
																		
																	$avg=0;
																			
																			foreach($e->rirtestobsvalues as $v)
																			{
																			$observations[]=(object)['Value'=>$v->Value];
																			$avg=$avg+(float)$v->Value;
																			
																			}
																			
																			if(count($e->rirtestobsvalues)>0)
																			{
																				$num=count($e->rirtestobsvalues);
																			$avg=$avg/$num;
																			}
																			
																			
																			$observations[]=(object)['Value'=>$avg];
																		}
																	}
																	
												
																	
															}					
														
															
														
														
																			
														
																												
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											return $rtds;
											
											}
								break;
								
								case 'BEND':
								case 'WEDGE':
								case 'THREAD':
								case 'PROOF':
								case 'TENSILE':									
								case 'CHEM':
									if(true)
											{
												
												//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												
												
												//---Observatory Parameters-----//
												
												$mdstdsid=$d->rIR->MdsTdsId;
												
												$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$d->TestId]]);
									
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
														if($d->test->testfeatures[0]->IsStd)
														{
															foreach($d->rirtestobs as $e)
															{
																$specmin=empty($e->SpecMin)?null:$e->SpecMin;
																$specmax=empty($e->SpecMax)?null:$e->SpecMax;
																// if(empty($specmin) || empty($specmax) )
																// { 
																	// if(!empty($mdstdstest))
																	// {
																		// $cr=Mdstdstestobsdetails::model()->find(array(
																// 'condition'=>'MTTID=:mttid AND PID=:pid',
																 // 'params'=>array(':mttid'=>$mdstdstest->Id,':pid'=>$e->TPID),));
																 
																	 // if(!empty($cr))
																		// {
																			// $specmin=empty($cr->SpecMin)?null:$cr->SpecMin;
																			// $specmax=empty($cr->SpecMax)?null:$cr->SpecMax;
																		// }
																		
																	// }
																	
										
																// }	
																$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																		'PSymbol'=>$e->tP->PSymbol,'Param'=>$e->tP->Parameter,
																		'Values'=>$e->rirtestobsvalues,'IsSpec'=>$e->tP->IsSpec,
																		'PCatId'=>$e->tP->PCatId,'SpecMin'=>$specmin,'SpecMax'=>$specmax,
																		'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																		'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																		'PDType'=>$e->tP->PDType,
																			);	
																	
															}					
														}
														else
														{
															foreach($d->rirtestobs as $e)
															{
																
																$p=Testobsparams::model()->findByPk($e->TPID);
																	if(!empty($p))
																	{										
																		
																	
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$p->Parameter,'Values'=>$e->rirtestobsvalues,'PCatId'=>$e->tP->PCatId,
																	'SpecMin'=>$e->SpecMin,'SpecMax'=>$e->SpecMax,'IsSpec'=>$e->tP->IsSpec,
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,
																		);
																
																	
																	

																	}
																	
														}					
														
														}															
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											return $rtds;
											
											}
								break;
								
								
								case 'GRAIN':
								
								if(true)
											{
												
											//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												
												//---Observatory Parameters-----//
													$mdstdsid=$d->rIR->MdsTdsId;
												
												$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$d->TestId]]);
									
												
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
														if($d->test->testfeatures[0]->IsStd)
														{
															foreach($d->rirtestobs as $e)
															{
																
																$specmin=$e->SpecMin;
																$specmax=$e->SpecMax;
																if(empty($specmin) || empty($specmax) )
																{ 
																	if(!empty($mdstdstest))
																	{
																		$cr=Mdstdstestobsdetails::model()->find(array(
																'condition'=>'MTTID=:mttid AND PID=:pid',
																 'params'=>array(':mttid'=>$mdstdstest->Id,':pid'=>$e->TPID),));
																 
																	 if(!empty($cr))
																		{
																			$specmin=empty($cr->SpecMin)?null:$cr->SpecMin;
																			$specmax=empty($cr->SpecMax)?null:$cr->SpecMax;
																		}
																		
																	}
																	
										
																}	
																
																
																if(empty($e->rirtestobsvalues))
																	{
																	
																		$allobs=[];
																		$allobs[]=(object)['Value'=>""];
																		$values=$allobs;
																	}
																	else
																	{
																		$values=json_decode($e->rirtestobsvalues);
																	}
										
																
																	
																	
																																		
																	$observations[$e->tP->PSymbol]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$e->tP->Parameter,'SpecMin'=>$specmin,
																	'SpecMax'=>$specmax,'Values'=>$values,'PCatId'=>$e->tP->PCatId,
																	
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,
																		);
																
																	
																	

																
																	
																	
														}					
													}
													else
													{
														
															foreach($d->rirtestobs as $e)
															{
																
																$p=Testobsparams::model()->findByPk($e->TPID);
																	if(!empty($p))
																	{	

																	
																			$observations[$e->tP->PSymbol]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$p->Parameter,'Values'=>empty($e->rirtestobsvalues)?[]:($e->rirtestobsvalues),'PCatId'=>$e->tP->PCatId,
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,
																		);
																			
																																	

																	}
																	
															}					
														
														}															
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											return $rtds;
											}
															
								break;
								
								
								default:
									if(true)
											{
												
												//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												
												//---Observatory Parameters-----//
												
													$mdstdsid=$d->rIR->MdsTdsId;
												
												$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$d->TestId]]);
									
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
														if($d->test->testfeatures[0]->IsStd)
														{
															foreach($d->rirtestobs as $e)
															{
																$cr=Stdsubdetails::model()->find(array(
																'condition'=>'SubStdId=:ID AND PId=:eid',
																 'params'=>array(':ID'=>$d->SSID,':eid'=>$e->TPID),));
										
																	if(!empty($cr))
																	{										
																		$act="";
																	if($e->tP->PDType==='N')
																	{											
																		$act=$cr->SpecMin."-".$cr->SpecMax;
																	}
																	$perm="";
																	if($e->tP->PDType==='N')
																	{											
																		$perm=$cr->PermMin."-".$cr->PermMax;
																	}
																	
																	$specmin=empty($e->SpecMin)?null:$e->SpecMin;
																	$specmax=empty($e->SpecMax)?null:$e->SpecMax;
																	
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$cr->p->Parameter,'SpecMin'=>$specmin,
																	'SpecMax'=>$specmax,'Values'=>$e->rirtestobsvalues,'PCatId'=>$e->tP->PCatId,
																	'IsSpec'=>$e->tP->IsSpec,
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,'Permissible'=>$perm,'Acceptable'=>$act,
																		);
																
																	
																	

																	}
																	else
																	{
																		$p=Testobsparams::model()->findByPk($e->TPID);
																		if(!empty($p))
																		{										
																			
																		
																		$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																		'PSymbol'=>$e->tP->PSymbol,'Param'=>$p->Parameter,'Values'=>$e->rirtestobsvalues,'PCatId'=>$e->tP->PCatId,
																		'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,'IsSpec'=>$e->tP->IsSpec,
																		'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																		'PDType'=>$e->tP->PDType,
																			);
																	
																		
																		

																		}
																	}
																	
														}					
													}
														else
														{
															foreach($d->rirtestobs as $e)
															{
																
																$p=Testobsparams::model()->findByPk($e->TPID);
																	if(!empty($p))
																	{										
																		
																	
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$p->Parameter,'Values'=>$e->rirtestobsvalues,'PCatId'=>$e->tP->PCatId,
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,'IsSpec'=>$e->tP->IsSpec,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,
																		);
																
																	
																	

																	}
																	
														}					
														
														}															
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											
											return $rtds;
											}
								break;
								
								
								
								
							}
			
		
	}
	
	
	
	public static function getreporttestdata($d)
	{
									
				$tuid=$d->TUID;
							switch($tuid)
							{
								
								
									case 'IMP':
									if(true)
											{
												
												//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												//---Observatory Parameters-----//
												
												
												
												
													$mdstdsid=$d->rIR->MdsTdsId;
												
													$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
														'params'=>[':mtid'=>$mdstdsid,':tid'=>$d->TestId]]);
									
												
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
														if($d->test->testfeatures[0]->IsStd)
														{
															foreach($d->rirtestobs as $e)
															{
																
																
																
																	if(!empty($mdstdstest))
																	{
																		$mdcr=Mdstdstestobsdetails::model()->find(array(
																'condition'=>'MTTID=:mttid AND PID=:pid',
																 'params'=>array(':mttid'=>$mdstdstest->Id,':pid'=>$e->TPID),));
																 
																	 if(!empty($mdcr))
																		{
																			$specmin=$mdcr->SpecMin." min";
																		}
																		
																	}
																	
																$cr=Stdsubdetails::model()->find(array(
																'condition'=>'SubStdId=:ID AND PId=:eid',
																 'params'=>array(':ID'=>$d->SSID,':eid'=>$e->TPID),));
																
if (is_null($e->SpecMin)) {
    if (!empty($mdcr)) {
        $specmin = !is_null($mdcr->SpecMin) 
            ? $mdcr->SpecMin : (!empty($cr) && !is_null($cr->SpecMin) 
                ? $cr->SpecMin : "");
    } else {
        $specmin = (!empty($cr) && !is_null($cr->SpecMin)) 
            ? $cr->SpecMin : "";
    }
} else {
    $specmin = !is_null($e->SpecMin) ? $e->SpecMin : "";
}
																
	if (is_null($e->SpecMax)) {
    if (!empty($mdcr)) {
        $specmax = !is_null($mdcr->SpecMax) 
            ? $mdcr->SpecMax  : (!empty($cr) && !is_null($cr->SpecMax) 
                ? $cr->SpecMax : "");
    } else {
        $specmax = (!empty($cr) && !is_null($cr->SpecMax)) 
            ? $cr->SpecMax   : "";
    }
} else {
    $specmax = !is_null($e->SpecMax) ? $e->SpecMax : "";
}					
																
															
																
																
																 if(!empty($cr))
																 {
																	$tobparam=$cr->p;
																 }
																 else
																 {
																	$tobparam=Testobsparams::model()->findbyPk($e->TPID); 
																 }
										
																	if(!empty($cr))
																	{										
																	
																	
																	$obsvalues=[];
																	$avg=0;
																	if(empty($e->rirtestobsvalues))
																	{
																		for($im=0;$im<$tobparam->MR;$im++)
																		{
																		$obsvalues[]=(object)array('Value'=>"");
																		}
																	}
																	else
																	{
																		foreach($e->rirtestobsvalues as $rob)
																		{
																			
																		$obsvalues[]=(object)array('Id'=>$rob->Id,'RTOBID'=>$rob->RTOBID,
												'Value'=>$rob->Value,);
												$avg=$avg+(float)$rob->Value;
																		}
																	}
																	
																	$avg=$avg/$tobparam->MR;
																	
																	
																	
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->PUnit,
																	'PSymbol'=>$tobparam->PSymbol,'Param'=>$tobparam->Parameter,'SpecMin'=>$specmin,
																	'SpecMax'=>$specmax,'Values'=>$e->rirtestobsvalues,'PCatId'=>$tobparam->PCatId,																	
																	'CatName'=>empty($tobparam->pCat)?"":$tobparam->pCat->CatName,'IsSpec'=>$e->tP->IsSpec,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,'Avg'=>$avg,
																	'PDType'=>$tobparam->PDType,
																		);
																
																	
																	

																	}
																	else
																	{
																		
																	$obsvalues=[];
																	$avg=0;
																	if(empty($e->rirtestobsvalues))
																	{
																		for($im=0;$im<$tobparam->MR;$im++)
																		{
																		$obsvalues[]=(object)array('Value'=>"");
																		}
																	}
																	else
																	{
																		foreach($e->rirtestobsvalues as $rob)
																		{
																			
																		$obsvalues[]=(object)array('Id'=>$rob->Id,'RTOBID'=>$rob->RTOBID,
												'Value'=>$rob->Value,);
												$avg=$avg+(float)$rob->Value;
																			
																		}
																	}
																	
																	$avg=$avg/$tobparam->MR;
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->PUnit,
																	'PSymbol'=>$tobparam->PSymbol,'Param'=>$tobparam->Parameter,'SpecMin'=>null,
																	'SpecMax'=>null,'Values'=>$e->rirtestobsvalues,'PCatId'=>$tobparam->PCatId,																	
																	'CatName'=>empty($tobparam->pCat)?"":$tobparam->pCat->CatName,'IsSpec'=>$e->tP->IsSpec,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,'Avg'=>$avg,
																	'PDType'=>$tobparam->PDType,
																		);
																
																	}
														}					
														}
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											return $rtds;
											}
											
								break;
								
								
								
								case 'IRW':
								if(true)
											{
												
											
												//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												
												
												//---Observatory Parameters-----//
												
												
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
														
															foreach($d->rirtestobs as $e)
															{
																$observations=[];
																$p=Testobsparams::model()->findByPk($e->TPID);
															
																	if(!empty($p))
																	{
																			if(empty($e->rirtestobsvalues))
																			{
																				$avgThinA=0;$avgThinB=0;$avgThinC=0;$avgThinD=0;
																	$avgThickA=0;$avgThickB=0;$avgThickC=0;$avgThickD=0;
																				$observations[]=(object)['ThinA'=>$avgThinA,'ThinB'=>$avgThinB,'ThinC'=>$avgThinC,'ThinD'=>$avgThinD,
																			'ThickA'=>$avgThickA,'ThickB'=>$avgThickB,'ThickC'=>$avgThickC,'ThickD'=>$avgThickD,];
																			}
																			else
																			{
																				if(!is_null($e->rirtestobsvalues[0]->Value))
																	{	
																		$obsvals=json_decode($e->rirtestobsvalues[0]->Value);															
																		if(count($obsvals)>0)
																		{	
																	$avgThinA=0;$avgThinB=0;$avgThinC=0;$avgThinD=0;
																	$avgThickA=0;$avgThickB=0;$avgThickC=0;$avgThickD=0;
																			
																			foreach($obsvals as $v)
																			{
																			$observations[]=(object)['ThinA'=>$v->ThinA,'ThinB'=>$v->ThinB,'ThinC'=>$v->ThinC,'ThinD'=>$v->ThinD,
																			'ThickA'=>$v->ThickA,'ThickB'=>$v->ThickB,'ThickC'=>$v->ThickC,'ThickD'=>$v->ThickD,];
																			$avgThinA=$avgThinA+(float)$v->ThinA;
																			$avgThinB=$avgThinB+(float)$v->ThinB;
																			$avgThinC=$avgThinC+(float)$v->ThinC;
																			$avgThinD=$avgThinD+(float)$v->ThinD;
																			
																			$avgThickA=$avgThickA+(float)$v->ThickA;
																			$avgThickB=$avgThickB+(float)$v->ThickB;
																			$avgThickC=$avgThickC+(float)$v->ThickC;
																			$avgThickD=$avgThickD+(float)$v->ThickD;
																			}
																			
																			$num=count($obsvals);
																			$avgThinA=$avgThinA/$num;$avgThinB=$avgThinB/$num;$avgThinC=$avgThinC/$num;$avgThinD=$avgThinD/$num;
																			$avgThickA=$avgThickA/$num;	$avgThickB=$avgThickB/$num;	$avgThickC=$avgThickC/$num;	$avgThickD=$avgThickD/$num;
																			
																			$observations[]=(object)['ThinA'=>$avgThinA,'ThinB'=>$avgThinB,'ThinC'=>$avgThinC,'ThinD'=>$avgThinD,
																			'ThickA'=>$avgThickA,'ThickB'=>$avgThickB,'ThickC'=>$avgThickC,'ThickD'=>$avgThickD,];
																		}
																	}
																	
																
																			}
																
																	
																	

																	}
																	
															}					
														
																													
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											
												return $rtds;
											}
								
								
								break;
								
								case 'IRK':
								if(true)
											{
												
												//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												
												//---Observatory Parameters-----//
												
												
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														$observations=[];
														
															foreach($d->rirtestobs as $e)
															{
																
																
																	
																
																
																
																$p=Testobsparams::model()->findByPk($e->TPID);
															$key=$p->PSymbol;
																	if(!empty($p))
																	{	
																if($key==='Obs')
																{
																	$obsvalues=[];
																	foreach($e->rirtestobsvalues as $rob)
																		{
																			$rv=empty($rob->Value)?[]:json_decode($rob->Value);
																			if(!empty($rv))
																			{
																				
																			$obsvalues[]=$rv;
																			}
																	
																			
																		}
																	
																		$obsvals=$obsvalues;//empty($e->rirtestobsvalues)?[]:($e->rirtestobsvalues);
																	
																		
																}
																else
																{
																$obsvals=empty($e->rirtestobsvalues)?null:$e->rirtestobsvalues;															
																}		
																			
																			
																$observations[$key]=(object)['Id'=>$e->Id,'TPID'=>$e->TPID,
																'PSymbol'=>$key,'Values'=>$obsvals
																			];
																		
																	
																	
																
																	
																	

																	}
																	
															}					
														
																													
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											return $rtds;
											
											}
								
								
								break;
								
								
								case 'CASE':
								
								if(true)
											{
												
											//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												
												//---Observatory Parameters-----//
												
												
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
														if($d->test->testfeatures[0]->IsStd)
														{
															foreach($d->rirtestobs as $e)
															{
																$cr=Stdsubdetails::model()->find(array(
																'condition'=>'SubStdId=:ID AND PId=:eid',
																 'params'=>array(':ID'=>$d->SSID,':eid'=>$e->TPID),));
										
																	if(!empty($cr))
																	{										
																		$act="";
																	if($e->tP->PDType==='N')
																	{											
																		$act=$cr->SpecMin."-".$cr->SpecMax;
																	}
																	$perm="";
																	if($e->tP->PDType==='N')
																	{											
																		$perm=$cr->PermMin."-".$cr->PermMax;
																	}
																	
																																																			
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$cr->p->Parameter,'SpecMin'=>$cr->SpecMin,
																	'SpecMax'=>$cr->SpecMax,'Values'=>empty($e->rirtestobsvalues)?[]:json_decode($e->rirtestobsvalues[0]->Value),'PCatId'=>$e->tP->PCatId,
																	
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,'Permissible'=>$perm,'Acceptable'=>$act,
																		);
																
																	
																	

																	}
																	else
																	{
																		$p=Testobsparams::model()->findByPk($e->TPID);
																		if(!empty($p))
																		{										
																			
																		
																		$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																		'PSymbol'=>$e->tP->PSymbol,'Param'=>$p->Parameter,'Values'=>empty($e->rirtestobsvalues)?[]:json_decode($e->rirtestobsvalues[0]->Value),'PCatId'=>$e->tP->PCatId,
																		'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																		'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																		'PDType'=>$e->tP->PDType,
																			);
																	
																		
																		

																		}
																	}
																	
														}					
													}
														else
														{
															foreach($d->rirtestobs as $e)
															{
																
																$p=Testobsparams::model()->findByPk($e->TPID);
																	if(!empty($p))
																	{										
																		
																	
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$p->Parameter,'Values'=>json_decode($e->rirtestobsvalues[0]->Value),'PCatId'=>$e->tP->PCatId,
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,
																		);
																
																	
																	

																	}
																	
														}					
														
														}															
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											
											return $rtds;
											}
								
								
								break;
								
								case 'VHARD':
								case 'RCHARD':
								case 'RBHARD':
								case 'MVHARD':
								case 'BHARD':
									if(true)
											{
												
											//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												
												//---Observatory Parameters-----//
													$mdstdsid=$d->rIR->MdsTdsId;
												
												$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$d->TestId]]);
									
												
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
														if($d->test->testfeatures[0]->IsStd)
														{
															foreach($d->rirtestobs as $e)
															{
																
																$specmin=$e->SpecMin;
																$specmax=$e->SpecMax;
																if(empty($specmin) || empty($specmax) )
																{ 
																	if(!empty($mdstdstest))
																	{
																		$cr=Mdstdstestobsdetails::model()->find(array(
																'condition'=>'MTTID=:mttid AND PID=:pid',
																 'params'=>array(':mttid'=>$mdstdstest->Id,':pid'=>$e->TPID),));
																 
																	 if(!empty($cr))
																		{
																			$specmin=empty($cr->SpecMin)?null:$cr->SpecMin;
																			$specmax=empty($cr->SpecMax)?null:$cr->SpecMax;
																		}
																		
																	}
																	
										
																}	
																
																
																if(empty($e->rirtestobsvalues))
																	{
																	
																		$allobs=[];
																		$allobs[]=(object)['SValue'=>"",'CValue'=>""];
																		$values=$allobs;
																	}
																	else
																	{
																		$values=json_decode($e->rirtestobsvalues[0]->Value);
																	}
										
																
																	
																	
																																		
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$e->tP->Parameter,'SpecMin'=>$specmin,
																	'SpecMax'=>$specmax,'Values'=>$values,'PCatId'=>$e->tP->PCatId,
																	
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,
																		);
																
																	
																	

																
																	
																	
														}					
													}
													else
													{
															foreach($d->rirtestobs as $e)
															{
																
																$p=Testobsparams::model()->findByPk($e->TPID);
																	if(!empty($p))
																	{										
																		
																	
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$p->Parameter,'Values'=>json_decode($e->rirtestobsvalues[0]->Value),'PCatId'=>$e->tP->PCatId,
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,
																		);
																
																	
																	

																	}
																	
														}					
														
														}															
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											return $rtds;
											}
															
								break;
								
								case 'TORQ':
									if(true)
											{
												
												//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												
												//---Observatory Parameters-----//
												
												
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
														if($d->test->testfeatures[0]->IsStd)
														{
															foreach($d->rirtestobs as $e)
															{
																
																
																
																$cr=Stdsubdetails::model()->find(array(
																'condition'=>'SubStdId=:ID AND PId=:eid',
																 'params'=>array(':ID'=>$d->SSID,':eid'=>$e->TPID),));
										
																	if(!empty($cr))
																	{										
																		$act="";
																	if($e->tP->PDType==='N')
																	{											
																		$act=$cr->SpecMin."-".$cr->SpecMax;
																	}
																	$perm="";
																	if($e->tP->PDType==='N')
																	{											
																		$perm=$cr->PermMin."-".$cr->PermMax;
																	}
																	
																	
																foreach($e->rirtestobsvalues as $v)
																{
																		$observations[][$e->tP->PSymbol]=$v->Value;
																
																}
																
																
																
																	
																	

																	}
																	else
																	{
																		$p=Testobsparams::model()->findByPk($e->TPID);
																		if(!empty($p))
																		{									
																			foreach($e->rirtestobsvalues as $v)
																			{
																			$observations[0][$e->tP->PSymbol][]=(object)['Value'=>$v->Value];
																			
																			}
																
																		
																		// $observations[][$e->tP->PSymbol]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																		// 'PSymbol'=>$e->tP->PSymbol,'Param'=>$p->Parameter,'Values'=>$e->rirtestobsvalues,'PCatId'=>$e->tP->PCatId,
																		// 'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																		// 'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																		// 'PDType'=>$e->tP->PDType,
																			// );
																	
																		
																		

																		}
																	}
																	
														}					
													}
														else
														{
															foreach($d->rirtestobs as $e)
															{
																
																$p=Testobsparams::model()->findByPk($e->TPID);
																	if(!empty($p))
																	{										
																		
																	
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$p->Parameter,'Values'=>$e->rirtestobsvalues,'PCatId'=>$e->tP->PCatId,
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,
																		);
																
																	
																	

																	}
																	
														}					
														
														}															
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											return $rtds;
											}
								break;
								
								case 'MCOAT':
								case 'MDCARB':
								case 'MCASE':
									if(true)
											{
												
												
												//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												
												//---Observatory Parameters-----//
												
												
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
															foreach($d->rirtestobs as $e)
															{
																
																$p=Testobsparams::model()->findByPk($e->TPID);
															
																	if(!empty($p))
																	{	
																if(!is_null($e->rirtestobsvalues))
																	{	
																		
																	$avg=0;
																			
																			foreach($e->rirtestobsvalues as $v)
																			{
																			$observations[]=(object)['Value'=>$v->Value];
																			$avg=$avg+(float)$v->Value;
																			
																			}
																			
																			if(count($e->rirtestobsvalues)>0)
																			{
																				$num=count($e->rirtestobsvalues);
																			$avg=$avg/$num;
																			}
																			
																			
																			$observations[]=(object)['Value'=>$avg];
																		}
																	}
																	
												
																	
															}					
														
															
														
														
																			
														
																												
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											return $rtds;
											
											}
								break;
								
								case 'BEND':
								case 'WEDGE':
								case 'THREAD':
								case 'PROOF':
								case 'TENSILE':									
								case 'CHEM':
									if(true)
											{
												
												//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=!is_null($bp->BValue)?date('d-m-Y',strtotime($bp->BValue)):null;
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array($bp->tBP->Parameter=>$bvalue ." ".$bp->tBP->PUnit,);
														}
														else
														{
															$downbasicparams[]=(object)array($bp->tBP->Parameter=>$bvalue ." ".$bp->tBP->PUnit,);
														}
													 
													}
												}
												
												
												
												//---Observatory Parameters-----//
												
												$mdstdsid=$d->rIR->MdsTdsId;
												
												$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$d->TestId]]);
									
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
														if($d->test->testfeatures[0]->IsStd)
														{
															foreach($d->rirtestobs as $e)
															{
																$specmin=empty($e->SpecMin)?"-":$e->SpecMin;
																$specmax=empty($e->SpecMax)?"-":$e->SpecMax;
																// if(empty($specmin) || empty($specmax) )
																// { 
																	// if(!empty($mdstdstest))
																	// {
																		// $cr=Mdstdstestobsdetails::model()->find(array(
																// 'condition'=>'MTTID=:mttid AND PID=:pid',
																 // 'params'=>array(':mttid'=>$mdstdstest->Id,':pid'=>$e->TPID),));
																 
																	 // if(!empty($cr))
																		// {
																			// $specmin=empty($cr->SpecMin)?null:$cr->SpecMin;
																			// $specmax=empty($cr->SpecMax)?null:$cr->SpecMax;
																		// }
																		
																	// }
																	
										
																// }	
																
																$val=!empty($e->rirtestobsvalues)?$e->rirtestobsvalues[0]->Value :" - ";
																$observations[]=(object)array($e->tP->PSymbol." (".$e->tP->PUnit.")"=>$val);
																
																			if($e->tP->IsSpec)
																			{
																				$observations[]=(object)array($e->tP->PSymbol." (SpecMin)"=>$specmin,);	
																				$observations[]=(object)array($e->tP->PSymbol." (SpecMax)"=>$specmax);	
																			}																
																	
															}					
														}
														else
														{
															foreach($d->rirtestobs as $e)
															{
																
																$p=Testobsparams::model()->findByPk($e->TPID);
																	if(!empty($p))
																	{										
																		
																	$specmin=empty($e->SpecMin)?"-":$e->SpecMin;
																$specmax=empty($e->SpecMax)?"-":$e->SpecMax;
																$val=!empty($e->rirtestobsvalues)?$e->rirtestobsvalues[0]->Value :" - ";
																$observations[]=(object)array($e->tP->PSymbol." (".$e->tP->PUnit.")"=>$val);
																
																			if($e->tP->IsSpec)
																			{
																				$observations[]=(object)array($e->tP->PSymbol." (SpecMin)"=>$specmin,);	
																				$observations[]=(object)array($e->tP->PSymbol." (SpecMax)"=>$specmax);	
																			}		
																	
																
																	
																	

																	}
																	
														}					
														
														}															
													}		
												
									 
												$rtds= MyFunctions::getrirshortdata($d,$observations,$topbasicparams,$downbasicparams);	
											return $rtds;
											
											}
								break;
								
								
								case 'GRAIN':
								
								if(true)
											{
												
											//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												
												//---Observatory Parameters-----//
													$mdstdsid=$d->rIR->MdsTdsId;
												
												$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$d->TestId]]);
									
												
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
														if($d->test->testfeatures[0]->IsStd)
														{
															foreach($d->rirtestobs as $e)
															{
																
																$specmin=$e->SpecMin;
																$specmax=$e->SpecMax;
																if(empty($specmin) || empty($specmax) )
																{ 
																	if(!empty($mdstdstest))
																	{
																		$cr=Mdstdstestobsdetails::model()->find(array(
																'condition'=>'MTTID=:mttid AND PID=:pid',
																 'params'=>array(':mttid'=>$mdstdstest->Id,':pid'=>$e->TPID),));
																 
																	 if(!empty($cr))
																		{
																			$specmin=empty($cr->SpecMin)?null:$cr->SpecMin;
																			$specmax=empty($cr->SpecMax)?null:$cr->SpecMax;
																		}
																		
																	}
																	
										
																}	
																
																
																if(empty($e->rirtestobsvalues))
																	{
																	
																		$allobs=[];
																		$allobs[]=(object)['Value'=>""];
																		$values=$allobs;
																	}
																	else
																	{
																		$values=json_decode($e->rirtestobsvalues);
																	}
										
																
																	
																	
																																		
																	$observations[$e->tP->PSymbol]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$e->tP->Parameter,'SpecMin'=>$specmin,
																	'SpecMax'=>$specmax,'Values'=>$values,'PCatId'=>$e->tP->PCatId,
																	
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,
																		);
																
																	
																	

																
																	
																	
														}					
													}
													else
													{
														
															foreach($d->rirtestobs as $e)
															{
																
																$p=Testobsparams::model()->findByPk($e->TPID);
																	if(!empty($p))
																	{	

																	
																			$observations[$e->tP->PSymbol]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$p->Parameter,'Values'=>empty($e->rirtestobsvalues)?[]:($e->rirtestobsvalues),'PCatId'=>$e->tP->PCatId,
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,
																		);
																			
																																	

																	}
																	
															}					
														
														}															
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											return $rtds;
											}
															
								break;
								
								
								default:
									if(true)
											{
												
												//---Basic Params-----//
												 $topbasicparams=[];
												  $downbasicparams=[];
												if(empty($d->rirtestbasics))
												{
														 $topbasicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														$key=$bp->tBP->PSymbol;
														$bvalue=$bp->BValue;
														if($bp->tBP->PDType==='D')
														{
															$bvalue=date('d-m-Y',strtotime($bp->BValue));
														}
														
														if($bp->tBP->Position==='Top')
														{
															$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
														else
														{
															$downbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bvalue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
														}
													 
													}
												}
												
												
												//---Observatory Parameters-----//
												
													$mdstdsid=$d->rIR->MdsTdsId;
												
												$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$d->TestId]]);
									
												$observations=array();	
												
													if(empty($d->rirtestobs))
													{
														$observations=array();
																										
													}
													else
													{
														
														if($d->test->testfeatures[0]->IsStd)
														{
															foreach($d->rirtestobs as $e)
															{
																$cr=Stdsubdetails::model()->find(array(
																'condition'=>'SubStdId=:ID AND PId=:eid',
																 'params'=>array(':ID'=>$d->SSID,':eid'=>$e->TPID),));
										
																	if(!empty($cr))
																	{										
																		$act="";
																	if($e->tP->PDType==='N')
																	{											
																		$act=$cr->SpecMin."-".$cr->SpecMax;
																	}
																	$perm="";
																	if($e->tP->PDType==='N')
																	{											
																		$perm=$cr->PermMin."-".$cr->PermMax;
																	}
																	
																	$specmin=empty($cr->SpecMin)?null:$cr->SpecMin." min";
																	$specmax=empty($cr->SpecMax)?null:$cr->SpecMax." max";
																	
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$cr->p->Parameter,'SpecMin'=>$specmin,
																	'SpecMax'=>$specmax,'Values'=>$e->rirtestobsvalues,'PCatId'=>$e->tP->PCatId,
																	'IsSpec'=>$e->tP->IsSpec,
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,'Permissible'=>$perm,'Acceptable'=>$act,
																		);
																
																	
																	

																	}
																	else
																	{
																		$p=Testobsparams::model()->findByPk($e->TPID);
																		if(!empty($p))
																		{										
																			
																		
																		$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																		'PSymbol'=>$e->tP->PSymbol,'Param'=>$p->Parameter,'Values'=>$e->rirtestobsvalues,'PCatId'=>$e->tP->PCatId,
																		'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,'IsSpec'=>$e->tP->IsSpec,
																		'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																		'PDType'=>$e->tP->PDType,
																			);
																	
																		
																		

																		}
																	}
																	
														}					
													}
														else
														{
															foreach($d->rirtestobs as $e)
															{
																
																$p=Testobsparams::model()->findByPk($e->TPID);
																	if(!empty($p))
																	{										
																		
																	
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ISNABL'=>$e->tP->ISNABL,
																	'PSymbol'=>$e->tP->PSymbol,'Param'=>$p->Parameter,'Values'=>$e->rirtestobsvalues,'PCatId'=>$e->tP->PCatId,
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,'IsSpec'=>$e->tP->IsSpec,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,
																		);
																
																	
																	

																	}
																	
														}					
														
														}															
													}		
												
									 
												$rtds= MyFunctions::getrirdata($d,$observations,$topbasicparams,$downbasicparams);	
											
											return $rtds;
											}
								break;
								
								
								
								
							}
			
		
	}

}