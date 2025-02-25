	<?
	
		foreach($mds as $md)
					{
						// $allmds[]=(object)array('Id'=>$m->Id,'MdsNo'=>$m->MdsNo,'Material'=>$m->Material,'Standard'=>$m->Standard,'Size'=>$m->Size,
						// 'Remark'=>$m->Remark,'Uploads'=>$m->mdsuploads);
						$carbdecarb=(object)array();
						$casedepth=(object)array();
						$chemical=(object)array();
						$grainsize=(object)array();
						
						$hardHV=(object)array();
						$hardMHV=(object)array();
						$hardHBW=(object)array();
						$hardHRB=(object)array();
						$hardHRC=(object)array();
						$hydrogen=(object)array();
						$impact=(object)array();						
						$inclusionk=(object)array();
						$inclusionw=(object)array();
						$microcase=(object)array();
						$microcoat=(object)array();
						$microdecarb=(object)array();
						$microstruct=(object)array();						
						$proofload=(object)array();
						$shear=(object)array();
						$tensile=(object)array();						
						$threadlap=(object)array();
						$tension=(object)array();
						$wedge=(object)array();
				
/*--------Carb-DE- Carb----------*/
					$dcarb=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="CDC" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dcarb))
					{		
						$d=$dcarb;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->tdsmechdetails;
						$cd=(object)array('Other'=>"");
				
						
								$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
						if(!empty($sub))	
						{
						$type=$sub->standard->Type;
						$substandard="";
								switch($type)
							{
								case 'EN':
								case 'DIN':
								case 'BS':
								case 'IS':							
												$substandard=$sub->Grade." ".$sub->Number." ( ".$sub->Diameter.") diameter";
											break;
								
								case 'ASTM':	$substandard=$sub->Grade." ( ".$sub->Diameter.") diameter";break;
								case 'SAE':  $substandard=$sub->Number." ( ".$sub->Diameter.") diameter";break;
								case 'A':$substandard=$sub->Grade." ".$sub->UNS." ( ".$sub->Material.") diameter";break;
								case 'DIN':$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;break;
								case 'ISO':$substandard=$sub->Class." ".$sub->Property." ( ".$sub->Diameter.") diameter";break;
																	
							}
							
							
						$std=$sub->standard->Standard." ".$substandard;
						}			 
						$carbdecarb=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'CDC'=>$cd);
					}	

/*--------Case Depth----------*/
					$dcase=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="CD" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dcase))
					{		
						$d=$dcase;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->tdsmechdetails;
						$cd=(object)array('Other'=>"");
				
						
								$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
						if(!empty($sub))	
						{
						$type=$sub->standard->Type;
						$substandard="";
								switch($type)
							{
								case 'EN':
								case 'DIN':
								case 'BS':
								case 'IS':							
												$substandard=$sub->Grade." ".$sub->Number." ( ".$sub->Diameter.") diameter";
											break;
								
								case 'ASTM':	$substandard=$sub->Grade." ( ".$sub->Diameter.") diameter";break;
								case 'SAE':  $substandard=$sub->Number." ( ".$sub->Diameter.") diameter";break;
								case 'A':$substandard=$sub->Grade." ".$sub->UNS." ( ".$sub->Material.") diameter";break;
								case 'DIN':$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;break;
								case 'ISO':$substandard=$sub->Class." ".$sub->Property." ( ".$sub->Diameter.") diameter";break;
																	
							}
							
							
						$std=$sub->standard->Standard." ".$substandard;
						}			 
						$casedepth=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'CDC'=>$cd);
					}					
						
			/*--------Chemical----------*/
							
						$sds=array();
						$usedele=array();
						$newels=array();
						$allecrs=array();
							$alles=Elements::model()->findAll();
							foreach($alles as $e)
							{
								$newels[]=$e->Id;								
							}
							
					$dchem=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="C" ',
							'params'=>array(':mdsid'=>$md->Id)));
					if(!empty($dchem))
					{		$d=$dchem;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$eles=$d->tdschemdetails;
						
							foreach($d->mdschemdetails as $e)
								{
									$sds[]=(object)array('ElementId'=>$e->ElementId,'Element'=>$e->Element,'Max'=>$e->Max,'Min'=>$e->Min);
								$usedele[]=$e->ElementId;	
							}
							

						 $ecrs=array_diff($newels,$usedele)	;
												
							foreach($ecrs as $ei)
							{
								$e=Elements::model()->findByPk($ei);
								$allecrs[]=(object)array('ElementId'=>$e->Id,'Element'=>$e->Symbol,'Max'=>"",'Min'=>"",'Value'=>"");
						
							}
						$sds=array_merge($sds,$allecrs);
						
						$sub=Chemicalbasic::model()->findByPk($d->SubStandardId);
							$std="";
						if(!empty($sub))	
						{
							$type=$sub->standard->Type;
							$substandard="";
						
								switch($type)
								{
									case 'EN':
									case 'DIN':
									case 'BS':
									case 'IS':							
												$substandard=$sub->Grade." ".$sub->Number;
												break;
									
									case 'ASTM':	$substandard=$sub->Grade;break;
									case 'SAE':  $substandard=$sub->Number;break;
									case 'A':
									case 'DIN':$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;break;
									case 'ISO':$substandard=$sub->Class." ".$sub->Property;break;											
									
								}
								
								$std=$sub->standard->Standard." ".$substandard;
						}
						$chemical=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'allelements'=>$sds);
					}
			

/*--------Grain Size----------*/
					$dgrain=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="GS" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dgrain))
					{		
						$d=$dgrain;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->tdsmechdetails;
						$cd=(object)array('Other'=>"");
				
						
								//$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
						$grainsize=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'CDC'=>$cd);
					}	
			
			
		/*-------Hardness HV----------*/
					$dhardHV=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="H" AND HtypeId="2"',
							'params'=>array(':mdsid'=>$md->Id)));
					if(!empty($dhardHV))
					{
						$d=$dhardHV;
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
					$hdetails=$d->tdsmechdetails;
					$vic=(object)array('Min'=>"",'Max'=>"");
				
					foreach($hdetails as $k)
					{
						if($k->TestDetails==='HV')
						{
							$vic=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
						}
					
					}
					$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
						if(!empty($sub))	
						{
						$type=$sub->standard->Type;
						$substandard="";
							switch($type)
							{
								case 'EN':
								case 'DIN':
								case 'BS':
								case 'IS':							
												$substandard=$sub->Grade." ".$sub->Number." ( ".$sub->Diameter.") diameter";
											break;
								
								case 'ASTM':	$substandard=$sub->Grade." ( ".$sub->Diameter.") diameter";break;
								case 'SAE':  $substandard=$sub->Number." ( ".$sub->Diameter.") diameter";break;
								case 'A':$substandard=$sub->Grade." ".$sub->UNS." ( ".$sub->Material.") diameter";break;
								case 'DIN':$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;break;
								case 'ISO':$substandard=$sub->Class." ".$sub->Property." ( ".$sub->Diameter.") diameter";break;
																	
							}
							
							$std=$sub->standard->Standard." ".$substandard;
						}			 
								
					$hardHV=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
					'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'HV'=>$vic,);	
					}
					
		/*-------Hardness- Micro HV---------*/
					$dhardMHV=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="H" AND HtypeId="3"',
							'params'=>array(':mdsid'=>$md->Id)));
					if(!empty($dhardMHV))
					{
						$d=$dhardMHV;
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
					$hdetails=$d->tdsmechdetails;
					$mvic=(object)array('Min'=>"",'Max'=>"");
				
					foreach($hdetails as $k)
					{
						if($k->TestDetails==='MicroHV')
						{
							$mvic=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
						}
					
					}
					
					$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
						if(!empty($sub))	
						{
						$type=$sub->standard->Type;
						$substandard="";
							switch($type)
							{
								case 'EN':
								case 'DIN':
								case 'BS':
								case 'IS':							
												$substandard=$sub->Grade." ".$sub->Number." ( ".$sub->Diameter.") diameter";
											break;
								
								case 'ASTM':	$substandard=$sub->Grade." ( ".$sub->Diameter.") diameter";break;
								case 'SAE':  $substandard=$sub->Number." ( ".$sub->Diameter.") diameter";break;
								case 'A':$substandard=$sub->Grade." ".$sub->UNS." ( ".$sub->Material.") diameter";break;
								case 'DIN':$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;break;
								case 'ISO':$substandard=$sub->Class." ".$sub->Property." ( ".$sub->Diameter.") diameter";break;
																	
							}
													$std=$sub->standard->Standard." ".$substandard;
						}			 
								
					$hardMHV=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
					'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'MicroHV'=>$mvic,
					);	
					}
				/*-------Hardness-Brinell---------*/
					$dhardHBW=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="H" AND HtypeId="1"',
							'params'=>array(':mdsid'=>$md->Id)));
					if(!empty($dhardHBW))
					{
						$d=$dhardHBW;
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
					$hdetails=$d->tdsmechdetails;
						$brin=(object)array('Min'=>"",'Max'=>"");
					
					foreach($hdetails as $k)
					{
						
						if($k->TestDetails==='HBW')
						{
							$brin=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
						}
					
					}
					
					$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
						if(!empty($sub))	
						{
						$type=$sub->standard->Type;
						$substandard="";
							switch($type)
							{
								case 'EN':
								case 'DIN':
								case 'BS':
								case 'IS':							
												$substandard=$sub->Grade." ".$sub->Number." ( ".$sub->Diameter.") diameter";
											break;
								
								case 'ASTM':	$substandard=$sub->Grade." ( ".$sub->Diameter.") diameter";break;
								case 'SAE':  $substandard=$sub->Number." ( ".$sub->Diameter.") diameter";break;
								case 'A':$substandard=$sub->Grade." ".$sub->UNS." ( ".$sub->Material.") diameter";break;
								case 'DIN':$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;break;
								case 'ISO':$substandard=$sub->Class." ".$sub->Property." ( ".$sub->Diameter.") diameter";break;
																	
							}
													$std=$sub->standard->Standard." ".$substandard;
						}			 
								
					$hardHBW=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
					'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'HBW'=>$brin);	
					}
			/*-------Hardness-HRB---------*/
					$dhardHRB=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="H" AND HtypeId="4"',
							'params'=>array(':mdsid'=>$md->Id)));
					if(!empty($dhardHRB))
					{
						$d=$dhardHRB;
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
					$hdetails=$d->tdsmechdetails;
					$rb=(object)array('Min'=>"",'Max'=>"");
					
					foreach($hdetails as $k)
					{
						if($k->TestDetails==='HRB')
						{
							$rb=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
						}
						
					}
					
					$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
						if(!empty($sub))	
						{
						$type=$sub->standard->Type;
						$substandard="";
						
								switch($type)
							{
								case 'EN':
								case 'DIN':
								case 'BS':
								case 'IS':							
												$substandard=$sub->Grade." ".$sub->Number." ( ".$sub->Diameter.") diameter";
											break;
								
								case 'ASTM':	$substandard=$sub->Grade." ( ".$sub->Diameter.") diameter";break;
								case 'SAE':  $substandard=$sub->Number." ( ".$sub->Diameter.") diameter";break;
								case 'A':$substandard=$sub->Grade." ".$sub->UNS." ( ".$sub->Material.") diameter";break;
								case 'DIN':$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;break;
								case 'ISO':$substandard=$sub->Class." ".$sub->Property." ( ".$sub->Diameter.") diameter";break;
																	
							}
							
													$std=$sub->standard->Standard." ".$substandard;
						}			 
								
					$hardHRB=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
					'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'HRB'=>$rb);	
					}
			/*-------Hardness--HRC--------*/
					$dhardHRC=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="H" AND HtypeId="5"',
							'params'=>array(':mdsid'=>$md->Id)));
					if(!empty($dhardHRC))
					{
						$d=$dhardHRC;
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
					$hdetails=$d->tdsmechdetails;
						$rc=(object)array('Min'=>"",'Max'=>"");
					foreach($hdetails as $k)
					{
						if($k->TestDetails==='HRC')
						{
							$rc=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
						}
					}
						
							$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
						if(!empty($sub))	
						{
						$type=$sub->standard->Type;
						$substandard="";
							switch($type)
							{
								case 'EN':
								case 'DIN':
								case 'BS':
								case 'IS':							
												$substandard=$sub->Grade." ".$sub->Number." ( ".$sub->Diameter.") diameter";
											break;
								
								case 'ASTM':	$substandard=$sub->Grade." ( ".$sub->Diameter.") diameter";break;
								case 'SAE':  $substandard=$sub->Number." ( ".$sub->Diameter.") diameter";break;
								case 'A':$substandard=$sub->Grade." ".$sub->UNS." ( ".$sub->Material.") diameter";break;
								case 'DIN':$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;break;
								case 'ISO':$substandard=$sub->Class." ".$sub->Property." ( ".$sub->Diameter.") diameter";break;
																	
							}
							
							
						$std=$sub->standard->Standard." ".$substandard;
						}			 
					$hardHRC=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
					'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'HRC'=>$rc);	
					}
		
		/*--------Hydrogen----------*/
					$dhydro=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="HET" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dhydro))
					{		
						$d=$dhydro;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->tdsmechdetails;
						$hydro=(object)array('Other'=>"");
				
						foreach($idetails as $k)
						{
							if($k->TestDetails==='HET')
							{
								$hydro=(object)array('Other'=>$k->Other);
							}
							
						}
								$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
						if(!empty($sub))	
						{
						$type=$sub->standard->Type;
						$substandard="";
								switch($type)
							{
								case 'EN':
								case 'DIN':
								case 'BS':
								case 'IS':							
												$substandard=$sub->Grade." ".$sub->Number." ( ".$sub->Diameter.") diameter";
											break;
								
								case 'ASTM':	$substandard=$sub->Grade." ( ".$sub->Diameter.") diameter";break;
								case 'SAE':  $substandard=$sub->Number." ( ".$sub->Diameter.") diameter";break;
								case 'A':$substandard=$sub->Grade." ".$sub->UNS." ( ".$sub->Material.") diameter";break;
								case 'DIN':$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;break;
								case 'ISO':$substandard=$sub->Class." ".$sub->Property." ( ".$sub->Diameter.") diameter";break;
																	
							}
							
							
						$std=$sub->standard->Standard." ".$substandard;
						}			 
						$hydrogen=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'HET'=>$hydro);
					}					

		
		/*--------Impact----------*/
					$dimp=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="I" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dimp))
					{		
						$d=$dimp;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->tdsmechdetails;
						$imp=(object)array('Min'=>"",'Max'=>"");
				
						foreach($idetails as $k)
						{
							if($k->TestDetails==='I')
							{
								$imp=(object)array('Temp'=>$k->Temp,'Min'=>$k->Min,'Max'=>$k->Max);
							}
							
						}
						
						$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
						if(!empty($sub))	
						{
						$type=$sub->standard->Type;
						$substandard="";
								switch($type)
							{
								case 'EN':
								case 'DIN':
								case 'BS':
								case 'IS':							
												$substandard=$sub->Grade." ".$sub->Number." ( ".$sub->Diameter.") diameter";
											break;
								
								case 'ASTM':	$substandard=$sub->Grade." ( ".$sub->Diameter.") diameter";break;
								case 'SAE':  $substandard=$sub->Number." ( ".$sub->Diameter.") diameter";break;
								case 'A':$substandard=$sub->Grade." ".$sub->UNS." ( ".$sub->Material.") diameter";break;
								case 'DIN':$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;break;
								case 'ISO':$substandard=$sub->Class." ".$sub->Property." ( ".$sub->Diameter.") diameter";break;
																	
							}
							
							
						$std=$sub->standard->Standard." ".$substandard;
						}			 
								
						$impact=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'I'=>$imp);
					}
		
		/*--------Inclusion Rating K method----------*/
					$inck=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="IRK" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($inck))
					{		
						$d=$inck;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->tdsmechdetails;
						$irk=(object)array('Min'=>"",'Max'=>"");
				
						
								//$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
						$inclusionk=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'IRK'=>$irk);
					}	

/*--------Inclusion Rating Worst method----------*/
					$incw=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="IRW" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($incw))
					{		
						$d=$incw;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->tdsmechdetails;
						$irw=(object)array('Min'=>"",'Max'=>"");
				
						
								//$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
						$inclusionw=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'IRW'=>$irw);
					}

/*--------MicroCase----------*/
					$dmicrocase=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="MCD" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dmicrocase))
					{		
						$d=$dmicrocase;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->tdsmechdetails;
						$ms=(object)array('Min'=>"",'Max'=>"");
				
								//$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
					
						$microcase=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'MS'=>$ms);
					}							

/*--------MicroCoating---------*/
					$dmicrocoat=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="MCT" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dmicrocoat))
					{		
						$d=$dmicrocoat;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->tdsmechdetails;
						$ms=(object)array('Min'=>"",'Max'=>"");
				
								//$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
					
						$microcoat=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'MS'=>$ms);
					}						
/*--------Micro Decarb-----------------------*/
					$dmicrodecarb=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="MDC" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dmicrodecarb))
					{		
						$d=$dmicrodecarb;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->tdsmechdetails;
						$ms=(object)array('Min'=>"",'Max'=>"");
				
								//$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
					
						$microdecarb=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'MS'=>$ms);
					}	
/*--------Microstruct----------*/
					$dmicrostruct=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="MS" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dmicrostruct))
					{		
						$d=$dmicrostruct;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->tdsmechdetails;
						$ms=(object)array('Min'=>"",'Max'=>"");
				
						
								//$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
							$microstruct=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'MS'=>$ms);
					}							


	/*--------Proof Load----------*/
					$dpl=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="PL" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dpl))
					{		
						$d=$dpl;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->tdsmechdetails;
						$pl=(object)array('Min'=>"",'Max'=>"");
				
						foreach($idetails as $k)
						{
							if($k->TestDetails==='PL')
							{
								$pl=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
							}
							
						}
								$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
						if(!empty($sub))	
						{
						$type=$sub->standard->Type;
						$substandard="";
								switch($type)
							{
								case 'EN':
								case 'DIN':
								case 'BS':
								case 'IS':							
												$substandard=$sub->Grade." ".$sub->Number." ( ".$sub->Diameter.") diameter";
											break;
								
								case 'ASTM':	$substandard=$sub->Grade." ( ".$sub->Diameter.") diameter";break;
								case 'SAE':  $substandard=$sub->Number." ( ".$sub->Diameter.") diameter";break;
								case 'A':$substandard=$sub->Grade." ".$sub->UNS." ( ".$sub->Material.") diameter";break;
								case 'DIN':$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;break;
								case 'ISO':$substandard=$sub->Class." ".$sub->Property." ( ".$sub->Diameter.") diameter";break;
																	
							}
							
							
						$std=$sub->standard->Standard." ".$substandard;
						}			 
						$proofload=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'PL'=>$pl);
					}					
		
			/*--------Shear Strength----------*/
					$dss=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="SS" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dss))
					{		
						$d=$dss;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->tdsmechdetails;
						$pl=(object)array('Min'=>"",'Max'=>"");
				
						
								$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
						if(!empty($sub))	
						{
						$type=$sub->standard->Type;
						$substandard="";
								switch($type)
							{
								case 'EN':
								case 'DIN':
								case 'BS':
								case 'IS':							
												$substandard=$sub->Grade." ".$sub->Number." ( ".$sub->Diameter.") diameter";
											break;
								
								case 'ASTM':	$substandard=$sub->Grade." ( ".$sub->Diameter.") diameter";break;
								case 'SAE':  $substandard=$sub->Number." ( ".$sub->Diameter.") diameter";break;
								case 'A':$substandard=$sub->Grade." ".$sub->UNS." ( ".$sub->Material.") diameter";break;
								case 'DIN':$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;break;
								case 'ISO':$substandard=$sub->Class." ".$sub->Property." ( ".$sub->Diameter.") diameter";break;
																	
							}
							
							
						$std=$sub->standard->Standard." ".$substandard;
						}			 
						$shear=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'PL'=>$pl);
					}					
		
					
		/*--------Tensile----------*/
					$dtens=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="T" ',
							'params'=>array(':mdsid'=>$md->Id)));
					if(!empty($dtens))
					{	
						$d=$dtens;
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
					$details=$d->tdsmechdetails;
					$ps=(object)array('Min'=>"",'Max'=>"");
					$uts=(object)array('Min'=>"",'Max'=>"");
					$el=(object)array('Min'=>"",'Max'=>"");
					$red=(object)array('Min'=>"",'Max'=>"");
					foreach($details as $k)
					{
						if($k->TestDetails==='PS')
						{
							$ps=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
						}
						if($k->TestDetails==='UTS')
						{
							$uts=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
						}
						if($k->TestDetails==='E')
						{
							$el=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
						}
						if($k->TestDetails==='R')
						{
							$red=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
						}
					}
						
							$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
								
						if(!empty($sub))	
						{
						$type=$sub->standard->Type;
						$substandard="";
							switch($type)
							{
								case 'EN':
								case 'DIN':
								case 'BS':
								case 'IS':							
												$substandard=$sub->Grade." ".$sub->Number." ( ".$sub->Diameter.") diameter";
											break;
								
								case 'ASTM':	$substandard=$sub->Grade." ( ".$sub->Diameter.") diameter";break;
								case 'SAE':  $substandard=$sub->Number." ( ".$sub->Diameter.") diameter";break;
								case 'A':$substandard=$sub->Grade." ".$sub->UNS." ( ".$sub->Material.") diameter";break;
								case 'DIN':$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;break;
								case 'ISO':$substandard=$sub->Class." ".$sub->Property." ( ".$sub->Diameter.") diameter";break;
																	
							}
							$std=$sub->standard->Standard." ".$substandard;
						}			 
										 
					$tensile=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
					'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'PS'=>$ps,'UTS'=>$uts,'E'=>$el,
					'R'=>$red);	
					}					
					
		/*--------tension----------*/
					$dtqen=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="TT" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dtqen))
					{		
						$d=$dtqen;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->tdsmechdetails;
						$tq=(object)array('Other'=>"",);
				
						foreach($idetails as $k)
						{
							if($k->TestDetails==='TT')
							{
								$tq=(object)array('Other'=>$k->Other);
							}
							
						}
								$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
						if(!empty($sub))	
						{
						$type=$sub->standard->Type;
						$substandard="";
								switch($type)
							{
								case 'EN':
								case 'DIN':
								case 'BS':
								case 'IS':							
												$substandard=$sub->Grade." ".$sub->Number." ( ".$sub->Diameter.") diameter";
											break;
								
								case 'ASTM':	$substandard=$sub->Grade." ( ".$sub->Diameter.") diameter";break;
								case 'SAE':  $substandard=$sub->Number." ( ".$sub->Diameter.") diameter";break;
								case 'A':$substandard=$sub->Grade." ".$sub->UNS." ( ".$sub->Material.") diameter";break;
								case 'DIN':$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;break;
								case 'ISO':$substandard=$sub->Class." ".$sub->Property." ( ".$sub->Diameter.") diameter";break;
																	
							}
							
							
						$std=$sub->standard->Standard." ".$substandard;
						}			 
						$tension=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'TT'=>$tq);
					}
/*--------Threadlap----------*/
					$dthread=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="THL" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dthread))
					{		
						$d=$dthread;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->tdsmechdetails;
						$tl=(object)array('Other'=>"");
				
						foreach($idetails as $k)
						{
							if($k->TestDetails==='THL')
							{
								$tl=(object)array('Other'=>$k->Other);
							}
							
						}
								$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
						if(!empty($sub))	
						{
						$type=$sub->standard->Type;
						$substandard="";
								switch($type)
							{
								case 'EN':
								case 'DIN':
								case 'BS':
								case 'IS':							
												$substandard=$sub->Grade." ".$sub->Number." ( ".$sub->Diameter.") diameter";
											break;
								
								case 'ASTM':	$substandard=$sub->Grade." ( ".$sub->Diameter.") diameter";break;
								case 'SAE':  $substandard=$sub->Number." ( ".$sub->Diameter.") diameter";break;
								case 'A':$substandard=$sub->Grade." ".$sub->UNS." ( ".$sub->Material.") diameter";break;
								case 'DIN':$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;break;
								case 'ISO':$substandard=$sub->Class." ".$sub->Property." ( ".$sub->Diameter.") diameter";break;
																	
							}
							
							
						$std=$sub->standard->Standard." ".$substandard;
						}			 
						$threadlap=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'THL'=>$tl);
					}		
		
		/*-------Wedge---------*/
					$dwedge=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="W" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dwedge))
					{		
						$d=$dwedge;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->tdsmechdetails;
						$pl=(object)array('Min'=>"",'Max'=>"");
				
						
								$sub=Mechbasic::model()->findByPk($d->SubStandardId);	
								$std="";
						if(!empty($sub))	
						{
						$type=$sub->standard->Type;
						$substandard="";
								switch($type)
							{
								case 'EN':
								case 'DIN':
								case 'BS':
								case 'IS':							
												$substandard=$sub->Grade." ".$sub->Number." ( ".$sub->Diameter.") diameter";
											break;
								
								case 'ASTM':	$substandard=$sub->Grade." ( ".$sub->Diameter.") diameter";break;
								case 'SAE':  $substandard=$sub->Number." ( ".$sub->Diameter.") diameter";break;
								case 'A':$substandard=$sub->Grade." ".$sub->UNS." ( ".$sub->Material.") diameter";break;
								case 'DIN':$substandard=$sub->Grade." ".$sub->UNS." ".$sub->Material;break;
								case 'ISO':$substandard=$sub->Class." ".$sub->Property." ( ".$sub->Diameter.") diameter";break;
																	
							}
							
							
						$std=$sub->standard->Standard." ".$substandard;
						}			 
						$wedge=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,'Standard'=>$std,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'PL'=>$pl);
					}					
			
		
		
		/*--------------------------------*/
				
					$allmds[]=(object)array('Id'=>$md->Id,'TdsNo'=>$md->TdsNo,'Material'=>$md->Material,'Standard'=>$md->Standard,'Size'=>$md->Size,
						'Remark'=>$md->Remark,'carbdecarb'=>$carbdecarb,'casedepth'=>$casedepth,'chemical'=>$chemical,'grainsize'=>$grainsize,
						'hardHV'=>$hardHV,'hardMHV'=>$hardMHV,'hardHBW'=>$hardHBW,'hardHRB'=>$hardHRB,'hardHRC'=>$hardHRC,'hydrogen'=>$hydrogen,
						'impact'=>$impact,'inclusionk'=>$inclusionk,'inclusionw'=>$inclusionw,'microcase'=>$microcase,'microcoat'=>$microcoat,
						'microdecarb'=>$microdecarb,'microstruct'=>$microstruct,'proofload'=>$proofload,'shear'=>$shear,'tensile'=>$tensile,
						'tension'=>$tension,'threadlap'=>$threadlap,'wedge'=>$wedge,'Uploads'=>$md->mdsuploads
						);
						
						
						
					
					}
					