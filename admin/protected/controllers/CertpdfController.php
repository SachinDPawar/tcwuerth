<?php

class CertpdfController extends Controller
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
    
    public function actionView()
    {
				
			// Check if id was submitted via GET
			if(!isset($_GET['id']))
				$this->_sendResponse(500, 'Error: Parameter <b> id</b> is missing' );
		 
			switch($_GET['model'])
			{
				// Find respective model  
				case 'createpdf': 
				
				$c=Certbasic::model()->with('certtests')->findByPk($_GET['id']);
				if(!empty($c))
				{
						$certbasic="";
						$chemicals=array();
						$chobs=array();
						$certelements=array();
						$creference="";
						
						$tobs=array();
						$certtparams=array();
						$treference="";
						
						$iobs=array();
						$certiparams=array();
						$ireference="";
						
						$hobs=array();
						$certhparams=array();
						$hreference="";
						
						$pobs=array();
						$certpparams=array();
						$preference="";
						
						$tqobs=array();
						$certtqparams=array();
						$tqreference="";
						
						$wobs=array();
						$certwparams=array();
						$wreference="";
						
						$preparedby="";
							$preparedsign="";
							if(!empty($c->PreparedBy))
							{
							$preparedsign=empty($c->preparedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$c->preparedBy->usersignuploads[0]->name);
							$preparedby=$c->preparedBy->FName." ".$c->preparedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($c->ApprovedBy))
							{
							$approvedsign=empty($c->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$c->approvedBy->usersignuploads[0]->name);
							$approvedby=$c->approvedBy->FName." ".$c->approvedBy->LName;	
							}				

						$checkedby="";
							$checkedsign="";
							if(!empty($c->CheckedBy))
							{
							$checkedsign=empty($c->checkedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$c->checkedBy->usersignuploads[0]->name);
							$checkedby=$c->checkedBy->FName." ".$c->checkedBy->LName;	
							}		
						
							
						$basic=(object)array('Id'=>$c->Id,'CertDate'=>$c->CertDate,'ItemCode'=>$c->ItemCode,'Material'=>$c->Material,'PartDescription'=>$c->PartDescription,
						'PoDate'=>$c->PoDate,'PoLineItemNo'=>$c->PoLineItemNo,'PoNo'=>$c->PoNo,'PosNo'=>$c->PosNo,'Project'=>$c->Project,'ProjectNo'=>$c->ProjectNo,'Qty'=>$c->Qty,
						'RFICoNo'=>$c->RFICoNo,'RFIDate'=>$c->RFIDate,'RefStd'=>$c->RefStd,'SlNo'=>$c->SlNo,'TCNo'=>$c->TCNo,'TestPlanNo'=>$c->TestPlanNo,'CheckedSign'=>$checkedsign,'CheckedBy'=>$checkedby,
						'Customer'=>$c->customer->CustomerName,'TCFormat'=>$c->TCFormat,'Format'=>$c->Format,'PreparedBy'=>$preparedby,'PreparedSign'=>$preparedsign,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby);
						
						
							/*-----Chemical Composition-------------------------------*/
							
								 $cts=Certtest::model()->findAll(array('condition'=>'CertBasicId=:id AND Section="chemical" AND ShowInCert="true"',
										 'params'=>array(':id'=>$c->Id),));
																
								//	$cts=$c->certtests(array('condition'=>'Section="chemical" AND ShowInCert="true"'));
									if(!empty($cts))
									{
										 foreach($cts as $ct)
										{
																									
										$creference=(object)array('RefId'=>$ct->RefId,'References'=>$ct->References,'RefExtra'=>$ct->RefExtra,'Ref'=>$ct->Ref,'Remark'=>$ct->Remark);
																													
										$certelements=Certchelements::model()->findAll(array('condition'=>'CertTestId=:id AND IsMajor="true"',
										 'params'=>array(':id'=>$ct->Id),));
										 
										  $certchobbasics=Certchobbasic::model()->findAll(array('condition'=>'CertTestId=:id AND ShowInCert="true"',
										 'params'=>array(':id'=>$ct->Id),));
										 $chobs=array();
										 foreach($certchobbasics as $ch)
										 {
											 $observations=array();
											 foreach($certelements  as $o)
											 { 
											 
											 $cobe=Certchobservations::model()->find(array('condition'=>'ChObbasicId=:id AND CertCheleId=:eleid',
												'params'=>array(':id'=>$ch->Id,':eleid'=>$o->Id),));
												$observations[]=(object)array('Value'=>$cobe->Value,'Element'=>$cobe->certChele->Element);
											 
											 
											 
											 
												 // $observations[]=(object)array('CertCheleId'=>$v->CertCheleId,'ChObbasicId'=>$v->ChObbasicId,
												 // 'Id'=>$v->Id,'Value'=>$v->Value,'Element'=>$v->certChele->Element)	;
											 }
											$chobs[]=(object)array('Id'=>$ch->Id,'LabNo'=>$ch->LabNo,'HeatNo'=>$ch->HeatNo,'BatchCode'=>$ch->BatchCode,'Observations'=>$observations,'ShowInCert'=>$ch->ShowInCert,'TestId'=>$ch->TestId,'SeqNo'=>$ch->SeqNo,'Remark'=>$ch->Remark);	 
											 
										 }
										$chemicals[]=(object)array('Section'=>"Chemical",'Certtest'=>$creference,'Certelements'=>$certelements,'Chobbasic'=>$chobs);	
								//	$chemicals[]=(object)array('Certelements'=>$certelements,'HeatNo'=>$ch->receiptir->HeatNo,'LabNo'=>$ch->receiptir->LabNo,'BatchCode'=>$ch->receiptir->BatchCode.$bn,
											//		'Observations'=>$sds,'Remark'=>$ch->Remark,'RefId'=>$stdsub->Id,'Certtest'=>$creference,);
											
										}
										
									}
						
					 $ctt=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="tensile" AND ShowInCert="true"',
										 'params'=>array(':id'=>$c->Id),));
									

									//$ctt=$c->certtests(array('condition'=>'Section="tensile" AND ShowInCert="true"'));		
									
									if(!empty($ctt))
									{
																				
										$treference=(object)array('RefId'=>$ctt->RefId,'References'=>$ctt->References,'RefExtra'=>$ctt->RefExtra,'Ref'=>$ctt->Ref,'Remark'=>$ctt->Remark);
																													
										$oldcerttparams=Certtparams::model()->findAll(array('condition'=>'CertTestId=:id AND IsMajor="true" ',
										 'params'=>array(':id'=>$ctt->Id),));
										 foreach($oldcerttparams as $p)
										 {
											 //for lab
											 if($p->Parameter==='PS' || $p->Parameter==='UTS' || $p->Parameter==='EL'|| $p->Parameter==='RA' || $p->Parameter==='R' )
											{
											 $certtparams[]=(object)array('Id'=>$p->Id,'CertTestId'=>$p->CertTestId,'Parameter'=>$p->Parameter,'IsMajor'=>$p->IsMajor,
											 'Min'=>$p->Min,'Max'=>$p->Max,'Description'=>$p->param->Description,'ParamId'=>$p->ParamId);
											}
										 }
										 
										 $tobs=array();
										
										 foreach($ctt->certtobbasics as $t)
										 {
											 if($t->ShowInCert==="true")
											 {
											 $observations=array();
												 foreach($certtparams as $o)
												 {
													  $cobe=Certtobservations::model()->find(array('condition'=>'TObbasicId=:id AND CertTparamId=:eleid',
													'params'=>array(':id'=>$t->Id,':eleid'=>$o->Id),));
											 
													 if(!empty($cobe))
													 {
													 $p=$cobe->certTparam;
															 if($p->Parameter==='PS' || $p->Parameter==='UTS' || $p->Parameter==='EL'|| $p->Parameter==='RA' || $p->Parameter==='R' )
														{
															
															 $observations[]=(object)array('Value'=>$cobe->Value,'Parameter'=>$cobe->certTparam->Parameter);
														}
													 }
											 
											 
												 }
												 
												$tobs[]=(object)array('Id'=>$t->Id,'HeatNo'=>$t->HeatNo,'ShowInCert'=>$t->ShowInCert,'TestId'=>$t->TestId,'SeqNo'=>$t->SeqNo,'LabNo'=>$t->LabNo,'BatchCode'=>$t->BatchCode,'Observations'=>$observations,'Remark'=>$t->Remark);	 
												
											 }
											 
										 }
										
									
										
										
										
									}
						
						
								 $cti=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="impact"  AND ShowInCert="true"',
										 'params'=>array(':id'=>$c->Id),));	
												
									//$cti=$c->certtests(array('condition'=>'Section="impact" AND ShowInCert="true"'));	
									
									if(!empty($cti))
									{
																				
										$ireference=(object)array('RefId'=>$cti->RefId,'References'=>$cti->References,'RefExtra'=>$cti->RefExtra,'Ref'=>$cti->Ref,'Remark'=>$cti->Remark);
																													
										$certiparams=Certiparams::model()->find(array('condition'=>'CertTestId=:id',
										 'params'=>array(':id'=>$cti->Id),));
										
										 
										 $iobs=array();
										
										 foreach($cti->certiobservations as $i)
										 {
											 if($i->ShowInCert==="true")
											 {
											 
											$iobs[]=(object)array('Id'=>$i->Id,'HeatNo'=>$i->HeatNo,'BatchCode'=>$i->BatchCode,'LabNo'=>$i->LabNo,'ShowInCert'=>$i->ShowInCert,'TestId'=>$i->TestId,'SeqNo'=>$i->SeqNo,
											'Value1'=>$i->Value1,'Value2'=>$i->Value2,'Value3'=>$i->Value3,'Avg'=>$i->Avg,'Remark'=>$i->Remark);	 
											 }
										 }
										
										
									}
						
							 $cth=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="hardness" AND ShowInCert="true"',
										'params'=>array(':id'=>$c->Id),));	
										 
							//$cth=$c->certtests(array('condition'=>'Section="hardness" AND ShowInCert="true"'));											 
									
									if(!empty($cth))
									{
																				
										$hreference=(object)array('RefId'=>$cth->RefId,'References'=>$cth->References,'RefExtra'=>$cth->RefExtra,'Ref'=>$cth->Ref,'Remark'=>$cth->Remark);
																													
										$certhparams=Certhparams::model()->find(array('condition'=>'CertTestId=:id',
										 'params'=>array(':id'=>$cth->Id),));
										
										 
										 $hobs=array();
										
										 foreach($cth->certhobbasics as $h)
										 {
											 $observations=array();
											
												 $observations=Certhobservations::model()->findAll(array('condition'=>'HObbasicId=:id',
										 'params'=>array(':id'=>$h->Id),));
												 
											 
											$hobs[]=(object)array('Id'=>$h->Id,'HeatNo'=>$h->HeatNo,'LabNo'=>$h->LabNo,'BatchCode'=>$h->BatchCode,'ShowInCert'=>$h->ShowInCert,'ShowSurface'=>$h->ShowSurface,'ShowCore'=>$h->ShowCore,'TestId'=>$h->TestId,'SeqNo'=>$h->SeqNo,'Observations'=>$observations,'Remark'=>$h->Remark);	 
											 
										 }
										
									
										
										
										
									}
						
						$ctp=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="proofload"  AND ShowInCert="true"',
										 'params'=>array(':id'=>$c->Id),));	
												
									//$cti=$c->certtests(array('condition'=>'Section="impact" AND ShowInCert="true"'));	
									
									if(!empty($ctp))
									{
																				
										$preference=(object)array('RefId'=>$ctp->RefId,'References'=>$ctp->References,'RefExtra'=>$ctp->RefExtra,'Ref'=>$ctp->Ref,'Remark'=>$ctp->Remark);
																													
										$certpparams=Certpparams::model()->find(array('condition'=>'CertTestId=:id',
										 'params'=>array(':id'=>$ctp->Id),));
										
										 
										 $pobs=array();
										
										 foreach($ctp->certpobservations as $i)
										 {
											 if($i->ShowInCert==="true")
											 {
											 
											$pobs[]=(object)array('Id'=>$i->Id,'HeatNo'=>$i->HeatNo,'BatchCode'=>$i->BatchCode,'LabNo'=>$i->LabNo,'ShowInCert'=>$i->ShowInCert,'TestId'=>$i->TestId,'SeqNo'=>$i->SeqNo,
											'Value1'=>$i->Value1,'Remark'=>$i->Remark);	 
											 }
										 }
										
										
									}
						
						
						$cttq=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="tension"  AND ShowInCert="true"',
										 'params'=>array(':id'=>$c->Id),));	
												
									//$cti=$c->certtests(array('condition'=>'Section="impact" AND ShowInCert="true"'));	
									
									if(!empty($cttq))
									{
																				
										$tqreference=(object)array('RefId'=>$cttq->RefId,'References'=>$cttq->References,'RefExtra'=>$cttq->RefExtra,'Ref'=>$cttq->Ref,'Remark'=>$cttq->Remark);
																													
										$certtqparams=Certiparams::model()->find(array('condition'=>'CertTestId=:id',
										 'params'=>array(':id'=>$cttq->Id),));
										
										 
										 $tqobs=array();
										
										 foreach($cttq->certtqobservations as $i)
										 {
											 if($i->ShowInCert==="true")
											 {
											 
											$tqobs[]=(object)array('Id'=>$i->Id,'HeatNo'=>$i->HeatNo,'BatchCode'=>$i->BatchCode,'LabNo'=>$i->LabNo,'ShowInCert'=>$i->ShowInCert,'TestId'=>$i->TestId,'SeqNo'=>$i->SeqNo,
											'Torque'=>$i->Torque,'Force'=>$i->Force,'Coff_Friction'=>$i->Coff_Friction,'Remark'=>$i->Remark);	 
											 }
										 }
										
										
									}
						
						$ctw=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="wedge"  AND ShowInCert="true"',
										 'params'=>array(':id'=>$c->Id),));	
												
									//$cti=$c->certtests(array('condition'=>'Section="impact" AND ShowInCert="true"'));	
									
									if(!empty($ctw))
									{
																				
										$wreference=(object)array('RefId'=>$ctw->RefId,'References'=>$ctw->References,'RefExtra'=>$ctw->RefExtra,'Ref'=>$ctw->Ref,'Remark'=>$ctw->Remark);
																													
										
										
										 
										 $wobs=array();
										
										 foreach($ctw->certwobservations as $i)
										 {
											 if($i->ShowInCert==="true")
											 {
											 
											$wobs[]=(object)array('Id'=>$i->Id,'HeatNo'=>$i->HeatNo,'BatchCode'=>$i->BatchCode,'LabNo'=>$i->LabNo,'ShowInCert'=>$i->ShowInCert,'TestId'=>$i->TestId,'SeqNo'=>$i->SeqNo,
											'RequiredTS'=>$i->RequiredTS,'ObservedTS'=>$i->ObservedTS,'Remark'=>$i->Remark);	 
											 }
										 }
										
										
									}
						
										
						/**-----------------All Test-----------------**/
							
							$tensile=(object)array('Section'=>"Tensile",'Certtest'=>$treference,'Certtparams'=>$certtparams,'Tobbasic'=>$tobs);
							$impact=(object)array('Section'=>"Impact",'Certtest'=>$ireference,'Certiparams'=>$certiparams,'Observations'=>$iobs);	
							$hardness=(object)array('Section'=>"Hardness",'Certtest'=>$hreference,'Certhparams'=>$certhparams,'Hobbasic'=>$hobs);	
							$proof=(object)array('Section'=>"Proofload",'Certtest'=>$preference,'Certpparams'=>$certpparams,'Observations'=>$pobs);	
							$tension=(object)array('Section'=>"Tension",'Certtest'=>$tqreference,'Certtqparams'=>$certtqparams,'Observations'=>$tqobs);	
							$wedge=(object)array('Section'=>"Wedge",'Certtest'=>$wreference,'Certwparams'=>$certwparams,'Observations'=>$wobs);	
						//	$certsections=(object)array('Chemical'=>$chemicals,'Tensile'=>$tensile,'Impact'=>$impact,'Hardness'=>$hardness);
							$sections=(object)array('Chemical'=>$chemicals,'Tensile'=>$tensile,'Impact'=>$impact,'Hardness'=>$hardness,'Proofload'=>$proof,
							'Tension'=>$tension,'Wedge'=>$wedge);
							
							//$this->_sendResponse(401, CJSON::encode($sections));
							
						$extsections=Certsections::model()->findAll();
					$allextsections=array();
					foreach($extsections as $s)
					{
						$allextsections[]=(object)array('Id'=>$s->Id,'Section'=>$s->Section,'Selected'=>"false",'KeyWord'=>$s->KeyWord);
					}
					
				$nmreference="";
					$nmobs=array();
					$ndreference="";
					$ndobs=array();
					$odreference="";
					$odobs=array();
					$mpireference="";
					$mpiobs=array();
					$mereference="";
					$meobs=array();
					$hextreference="";
					$hextobs=(object)array('Type'=>"attach");;
					$streference="";
					$stobs=array();
					$stdelreference="";
					$stdelobs=(object)array('Type'=>"attach");
					$stdacroreference="";
					$stdacroobs=(object)array();
					$sthotreference="";
					$sthotobs=(object)array();
					$stpreference="";
					$stpobs=(object)array();
					
						if(!empty($c))
						{
							
								$ctnm=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="NM"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctnm))
								{
									$nmreference=(object)array('RefId'=>$ctnm->RefId,'References'=>$ctnm->References,'RefExtra'=>$ctnm->RefExtra,'Ref'=>$ctnm->Ref,'Remark'=>$ctnm->Remark);
									$nmobs=$ctnm->certnonmetallics;
									$allextsections[0]->Selected="true";
								}
								
								$ctme=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="ME"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctme))
								{
									$mereference=(object)array('RefId'=>$ctme->RefId,'References'=>$ctme->References,'RefExtra'=>$ctme->RefExtra,'Ref'=>$ctme->Ref,'Remark'=>$ctme->Remark);
									$meobs=$ctme->certmicroexaminations;
									$allextsections[1]->Selected="true";
								}
								
								$ctmpi=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="MPI"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctmpi))
								{
									$mpireference=(object)array('RefId'=>$ctmpi->RefId,'References'=>$ctmpi->References,'RefExtra'=>$ctmpi->RefExtra,'Ref'=>$ctmpi->Ref,'Remark'=>$ctmpi->Remark);
									$mpiobs=$ctmpi->certmpidetails;
									$allextsections[2]->Selected="true";
								}
								
								$cthext=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="H"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($cthext))
								{
									$obs=(object)array();
									$hextreference=(object)array('RefId'=>$cthext->RefId,'References'=>$cthext->References,'RefExtra'=>$cthext->RefExtra,'Ref'=>$cthext->Ref,'Remark'=>$cthext->Remark);
									if(empty($cthext->certexthardnesses))
									{
										$obs=(object)array('Type'=>"nonattach");
									}
									else
									{
										$obs=$cthext->certexthardnesses[0];
									}
									
									$hextobs=$obs;
									$allextsections[3]->Selected="true";
								}
								
								
								$ctnd=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="ND"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctnd))
								{
									$ndreference=(object)array('RefId'=>$ctnd->RefId,'References'=>$ctnd->References,'RefExtra'=>$ctnd->RefExtra,'Ref'=>$ctnd->Ref,'Remark'=>$ctnd->Remark);
									$ndobs=$ctnd->certnondestructives;
									$allextsections[4]->Selected="true";
								}
								
								$ctst=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="ST"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctst))
								{
									$streference=(object)array('RefId'=>$ctst->RefId,'References'=>$ctst->References,'RefExtra'=>$ctst->RefExtra,'Ref'=>$ctst->Ref,'Remark'=>$ctst->Remark);
									$stobs=$ctst->certsurfacetreats;
									$allextsections[5]->Selected="true";
								}
								
								$ctstd=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="STDELTA"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctstd))
								{
									$obs=(object)array();
									$stdelreference=(object)array('RefId'=>$ctstd->RefId,'References'=>$ctstd->References,'RefExtra'=>$ctstd->RefExtra,'Ref'=>$ctstd->Ref,'Remark'=>$ctstd->Remark);
									if(empty($ctstd->certsurfacetreatdeltas))
									{
										$obs=(object)array('Type'=>"nonattach",'CaotMin'=>"",'CaotMax'=>"",'SaltMin'=>"",'SaltMax'=>"",'AdhesionReq'=>"",
										'VisualReq'=>"",'Observations'=>"",'CaotRemark'=>"",'SaltRemark'=>"",'AdhesionRemark'=>"",'VisualRemark'=>"");
									}
									else
									{
										$obs=$ctstd->certsurfacetreatdeltas[0];
									}
									
									$stdelobs=$obs;
									$allextsections[6]->Selected="true";
								}
								
								$ctstda=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="STDA"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctstda))
								{
									$stdacroreference=(object)array('RefId'=>$ctstda->RefId,'References'=>$ctstda->References,'RefExtra'=>$ctstda->RefExtra,'Ref'=>$ctstda->Ref,'Remark'=>$ctstda->Remark);
									$obs=(object)array();
									if(empty($ctstda->certsurfacetreatdacros))
									{
										$obs=(object)array();
									}
									else
									{
										$obs=$ctstda->certsurfacetreatdacros[0];
									}
									$stdacroobs=$obs;
									$allextsections[7]->Selected="true";
								}
								
								$ctsthot=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="STHOT"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctsthot))
								{
									$sthotreference=(object)array('RefId'=>$ctsthot->RefId,'References'=>$ctsthot->References,'RefExtra'=>$ctsthot->RefExtra,'Ref'=>$ctsthot->Ref,'Remark'=>$ctsthot->Remark);
									$obs=(object)array();
									if(empty($ctsthot->certsurfacetreathots))
									{
										$obs=(object)array();
									}
									else
									{
										$obs=$ctsthot->certsurfacetreathots[0];
									}
									$sthotobs=$obs;
									$allextsections[8]->Selected="true";
								}
								
								$ctstp=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="STP"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctstp))
								{
									$stpreference=(object)array('RefId'=>$ctstp->RefId,'References'=>$ctstp->References,'RefExtra'=>$ctstp->RefExtra,'Ref'=>$ctstp->Ref,'Remark'=>$ctstp->Remark);
									$obs=(object)array();
									if(empty($ctstp->certsurfacetreatplats))
									{
										$obs=(object)array();
									}
									else
									{
										$obs=$ctstp->certsurfacetreatplats[0];
									}
									
									$stpobs=$obs;
									$allextsections[9]->Selected="true";
								}
														
								
								
								
								$ctod=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="OD"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctod))
								{
									$odreference=(object)array('RefId'=>$ctod->RefId,'References'=>$ctod->References,'RefExtra'=>$ctod->RefExtra,'Ref'=>$ctod->Ref,'Remark'=>$ctod->Remark);
									$odobs=$ctod->certotherdetails;
									$allextsections[10]->Selected="true";
								}
								
								
								
						}
					
						$nonmetallic=(object)array('Section'=>"NM",'Certtest'=>$nmreference,'Observations'=>$nmobs);
						$microex=(object)array('Section'=>"ME",'Certtest'=>$mereference,'Observations'=>$meobs);
						$mpidet=(object)array('Section'=>"MPI",'Certtest'=>$mpireference,'Observations'=>$mpiobs);
						$nondes=(object)array('Section'=>"ND",'Certtest'=>$ndreference,'Observations'=>$ndobs);
						$hard=(object)array('Section'=>"H",'Certtest'=>$hextreference,'Observations'=>$hextobs);
					$surtreat=(object)array('Section'=>"ST",'Certtest'=>$streference,'Observations'=>$stobs);
					$surtreatde=(object)array('Section'=>"STDELTA",'Certtest'=>$stdelreference,'Observations'=>$stdelobs);
					$surtreatda=(object)array('Section'=>"STDA",'Certtest'=>$stdacroreference,'Observations'=>$stdacroobs);
					$surtreathot=(object)array('Section'=>"STHOT",'Certtest'=>$sthotreference,'Observations'=>$sthotobs);
					$surtreatplat=(object)array('Section'=>"STP",'Certtest'=>$stpreference,'Observations'=>$stpobs);
					$othdet=(object)array('Section'=>"OD",'Certtest'=>$odreference,'Observations'=>$odobs);
					
						$extsections=(object)array('NM'=>$nonmetallic,'ND'=>$nondes,'OD'=>$othdet,'MPI'=>$mpidet,'H'=>$hard,'ME'=>$microex,
						'ST'=>$surtreat,'STDELTA'=>$surtreatde,'STDA'=>$surtreatda,'STHOT'=>$surtreathot,'STP'=>$surtreatplat);
					

						$attachments=array();

						$attachments=$c->certattachments;
						
						$cb=(object)array('basic'=>$basic,'sections'=>$sections,'extsections'=>$extsections,'allextsections'=>$allextsections,'attachments'=>$attachments);
					
				}
				
				
				
				
				
				
				$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				spl_autoload_register(array('YiiBase','autoload'));
				$pdf->setPageUnit('mm');
				// set document information
				$pdf->SetCreator(PDF_CREATOR);  
				$pdf->SetTitle($c->TCNo);      
				// remove default header/footer
				$pdf->setPrintHeader(false);
				$pdf->setPrintFooter(false);				
			   // $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "Selling Report -2013", "selling report for Jun- 2013");
			   // $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
			  //  $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
			  
			  // set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

				$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
				$pdf->SetMargins(10, 10, 5, true); 
				$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
				$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
				$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
				 $pdf->SetFont('times', '', 12);
				 
				 
	
				 
				$pdf->AddPage();
				$baseurl=Yii::app()->request->baseUrl;
				
				//Write the html
				// create some HTML content
		// define some HTML content with style
// $html = '<style>'.file_get_contents('css/bootstrap.min.css').'</style>';
// $html .= '<script>'.file_get_contents('js/jquery.min.js').'</script>';
// $html .= '<script>'.file_get_contents('js/bootstrap.min.js').'</script>';

$html = <<<EOF
		<!-- EXAMPLE OF CSS STYLE -->
		<style>
		table {
			background-color: #fff;
			font-size:7px;
			border-collapse: collapse;
			width: 100%;
    max-width: 100%;
		}
		.text-center{
			text-align:center;
		}
tr{
	line-height:11px;	
}
		td {
    vertical-align: middle;
    text-align:center;

}

table, th, td {
    border: 0.5px solid #7b7b7b;
}
		
		</style>
		<table  style="font-size:8px;" cellspacing="0" cellpadding="0"  >
		  <thead>
			<tr>
				<td rowspan="2" style="text-align:center;padding:2px;line-height:34px;" colspan="3">
			
				
					<img  src="$baseurl./../img/logo.jpg" align="middle" alt="RFI logo" style="width:26px;height:26px;margin-top:4px;"/>
				
				</td>
				<td colspan="13"  class="text-center" style="padding:4px;height:14px;line-height:14px;vertical-align:middle;">
					<p style="margin-top: 0px;margin-bottom: 0px;font-size:10px;"><b> RANDACK FASTENERS INDIA PVT. LTD.</b></p>
				</td>
			</tr>			
			<tr>
				<td colspan="13"  class="text-center" style="padding:2px;line-height:12px">
				Gat No 1197, Near Ghotawade Phata, Village Pirangut, Taluka Mulshi,Dist :Pune, 412108.
				</td>
			</tr>
			</thead>
				  <tbody>	
			<tr>
				<td colspan="16" class="text-center"  style="padding:4px;font-size:9px;line-height:12px;"><b> $c->TCFormat</b></td>
			</tr>			
			<tr style="line-height:11px;">
				<td colspan="8"  style="padding:2px;"><b>  Customer: $basic->Customer</b></td>
				<td colspan="4"  style="padding:2px;"><b>  T.C. No.: $basic->TCNo</b></td>
				<td colspan="4" style="padding:2px;"><b>  Date: $basic->CertDate</b></td>
			</tr>
			
			<tr>
				<td colspan="5"  style="padding:2px;"><b>  PO No: $basic->PoNo</b></td>
				<td colspan="3" style="padding:2px;"><b>  Date: $basic->PoDate</b></td>
				<td colspan="8" style="padding:2px;"><b>  Part Description: $basic->PartDescription</b></td>
			</tr>
			<tr>
				<td colspan="5" style="padding:2px;"><b>  RFI CO No: $basic->RFICoNo</b></td>
				<td colspan="3" style="padding:2px;"><b>  Date: $basic->RFIDate</b></td>
				<td colspan="8" style="padding:2px;"><b>  Ref Std./Drawing No.: $basic->RefStd</b></td>
			</tr>
			
			<tr>
				<td colspan="5" style="padding:2px;"><b>  Item Code: $basic->ItemCode</b></td>
				<td colspan="3" style="padding:2px;"><b>  Qty: $basic->Qty</b></td>
				<td colspan="8" style="padding:2px;"><b>  Material: $basic->Material</b></td>
			</tr>
EOF;
			if($basic->Format=="3.2")
			{
$html .=<<<EOF
			<tr >
				<td colspan="8" style="padding:2px;"><b>  POLine Item No.: $basic->PoLineItemNo</b></td>
				<td colspan="8" style="padding:2px;"><b>  Test Plan No.: $basic->.TestPlanNo</b><span class=" text-danger pull-right fa fa-remove" ng-click="row1=false"></span></td>
			</tr>
			
			<tr >
				<td colspan="8" style="padding:2px;"><b>  Project: $basic->.Project</b></td>
				<td colspan="8" style="padding:2px;"><b>  Project No.: $basic->ProjectNo</b></td>
			</tr>
			
			<tr >
				
				<td colspan="8" style="padding:2px;"><b>  Pos. No.: $basic->PosNo</b></td>
				<td colspan="8" style="padding:2px;"><b>  Sl. No.: $basic->SlNo</b></td>
			</tr> 
EOF;
			}

if(!empty($sections->Chemical))	
{	
$html .=<<<EOF
				

<!------------Chemical Properties------------------>		
			<tr ><td colspan="16"  style="padding:0px;">
			<table cellspacing="0" cellpadding="0" style="font-size:7px;font-weight:normal;" >
EOF;
		foreach($sections->Chemical as $chem)
		{
			$certest=$chem->Certtest;
			
			
			$k=count($chem->Certelements);
			
			switch($k)
			{
				case 7:
							$a=4;$b=3;$c=1;$d=2;
				$m=1;$n=3;break;
				
				case 6:
				$a=4;$b=4;$c=1;$d=2;
				$m=1;$n=3;break;
				
				case 5:
				$a=5;$b=4;$c=1;$d=2;
				$m=1;$n=4;break;
				
				case 4:
				$a=5;$b=5;$c=1;$d=2;
				$m=1;$n=4;break;
				
				default:$a=4;$b=2;$c=1;$d=2;
			$m=1;$n=3;break;
				
			}
			
			
$html .=<<<EOF
					<tbody>
					<tr style="line-height:10px:4px;font-size:8px;">
						<td colspan="6" style="padding:4px;font-size:8px;"><b> Chemical Composition : </b></td>
						<td colspan="10" style="padding:4px;"><b> Reference STD:  $certest->References $certest->RefExtra </b></td>
					</tr>
					<tr class="text-center">
					<td colspan="$a"  style="padding:2px;">Elements </td>
					<td colspan="$b" style="padding:2px;">Heat No.</td>
EOF;
				//$i = 0;
				foreach($chem->Certelements as $ele)
				{
					//if (++$i == 9) break;
$html .=<<<EOF
					<td  colspan="$c" style="padding:2px;"> $ele->Element %</td>
EOF;
				}	
	
$html .=<<<EOF
					<td colspan="$d" rowspan="3" style="padding:2px;vertical-align:middle;">Remark</td>
					</tr>			
				
					<tr class="text-center">
					<td colspan="$m" style="vertical-align:middle;padding:2px;" rowspan="2">Sr.No.</td>
					<td colspan="$n" style="vertical-align:middle;padding:2px;" rowspan="2">Batch Code</td>
					<td colspan="$b"style="padding:2px;">Min</td>
EOF;
			
				$j = 0;
				foreach($chem->Certelements as $ele)
				{
					if (++$j == 9) break;
$html  .=<<<EOF
					<td colspan="$c" style="padding:2px;">$ele->Min</td>
EOF;
				}	
				
$html  .=<<<EOF
					</tr>
EOF;
$html  .=<<<EOF
					<tr class="text-center">
					<td colspan="$b"style="padding:2px;">Max</td>
EOF;

				//$n = 0;
				foreach($chem->Certelements as $mele)
				{		
					//if (++$n == 9) break;
				
$html  .=<<<EOF
					<td colspan="$c" style="padding:2px;">$mele->Max</td>
EOF;
				}
				
$html .=<<<EOF
				</tr>	
EOF;
				
				$k = 1;
				foreach($chem->Chobbasic as $ch)
				{		
							
$html  .=<<<EOF
				<tr class="text-center"   >
					<td colspan="$m" style="padding:2px;">$k</td>
					<td colspan="$n" style="padding:2px;">$ch->BatchCode </td>
					<td colspan="$b" style="padding:2px;">$ch->HeatNo</td>
EOF;
					//$j = 0;
					foreach($ch->Observations as $ele)
					{		
						//if (++$j == 9) break;
				
$html  .=<<<EOF
					<td colspan="$c" style="padding:2px;"  >$ele->Value  </td>
					
				
EOF;
					}
$html  .=<<<EOF
					<td colspan="$d" style="padding:2px;">$ch->Remark</td>
					</tr>
EOF;
					$k++;
				}	

		
	
$html .=<<<EOF
								
					</tbody>

EOF;
		}
$html .=<<<EOF
<tfoot>
EOF;
	if(!empty($sections->Chemical[0]->Certtest->Ref))
	{
		$certctest=$sections->Chemical[0]->Certtest;
$html .=<<<EOF
				<tr class="text-center"  >
					<td style="padding:2px;text-align:right;" colspan="2">Ref</td>
					<td  colspan="14" style="padding:2px;text-align:left;" >$certctest->Ref</td>
				</tr>
EOF;
	}
	if(!empty($sections->Chemical[0]->Certtest->Remark))
	{
		$certctest=$sections->Chemical[0]->Certtest;
$html .=<<<EOF
				<tr class="text-center" >
					<td style="padding:2px;" colspan="2">Remark</td>
					<td  colspan="14" style="padding:2px;" >$certctest->Remark</td>
				</tr>
EOF;
	}
$html .=<<<EOF
				</tfoot>	
EOF;
		
$html .=<<<EOF
</table>
			</td>
			</tr>

			
EOF;
}

$a=3;$b=2;$g=1;$c=1;$d=1;$e=1;$f=3;
$k=0;$p=0;$i=0;$h=0;
if(!empty($sections->Tensile->Certtest->RefId))
{
$k=count($sections->Tensile->Certtparams);
}
if(!empty($sections->Proofload->Certtest->RefId))
{
$p=1;
}
if(!empty($sections->Impact->Certtest->RefId))
{
$i=1;
}
if(!empty($sections->Hardness->Certtest->RefId))
{
$h=3;
}

$ch=$a+$b+$g+$k+$p+$i+$h;
//$this->_sendResponse(401, CJSON::encode($p));
switch($ch)
{
	case 15:
				$e=$e+1; 
				
			//	$this->_sendResponse(401, CJSON::encode($e));
				break;
	case 14:$b=$b+1;
				if($h===0)
				{$e=$e+1;}
				else
				{$f=$f+1;}
	break;
	case 13:$a=$a+1;
				if($h===0)
				{$b=$b+2;}
				else
				{$f=$f+1;
				$b=$b+1;}
			break;
			
	case 12:$b=$b+2;$a=$a+2; break;
	
	case 11:
				if($k===5)
				{
					$c=$c+1;
				}
				else if($k===4)
				{
					$c=$c+1;
					$b=$b+1;
				}
				else
				{
	$b=$b+1;$a=$a+1;$f=$f+1;$e=$e+1;
				}	break;
	
	case 10:$b=$b+2;
				if($k===0)
				{
					$f=$f+2;
					if($i===0)
					{
						$d=$d+2;
					}
					else
					{
						$e=$e+2;
					}
				}
				else
				{
					$c=$c+1;
				}
	break;
	
	case 9: $b=$b+2;$a=$a+1;$f=$f+4;break;
	
	case 8: $b=$b+3;$a=$a+3;$e=$e+1;$d=$d+1;break;
	
	case 7: $b=$b+3;$a=$a+3;
				if($i===0)
					{
						$d=$d+3;
					}
					else
					{
						$e=$e+3;
					}
	break;
	
	default:break;
}


if(!empty($sections->Hardness->Certtest->RefId))
{
	
$certtensile=$sections->Tensile->Certtest;
$certhardness=$sections->Hardness->Certtest;
//-----------------Mechanical Properties-----------------//		
$html .=<<<EOF
			<tr>
			<td colspan="16"  style="padding:0px;width:100%;">
		
			<table   style="font-size:7px;">		
				<tr style="line-height:10px;font-size:8px;">
					<td colspan="8" style="padding:4px;"><b> Mechanical Properties : </b></td>
					<td colspan="8" style="padding:4px;"><b> Reference STD: $certhardness->References $certhardness->RefExtra</b></td>
				</tr>

EOF;
$html .=<<<EOF
	<tr class="text-center">
					<td colspan="$a"  style="padding:2px;vertical-align:middle;">Parameters <i class="fa fa-long-arrow-right" ></i></td>
					<td colspan="$b" style="padding:2px;vertical-align:middle;">Heat No.</td>
EOF;
				$j = 0;
				foreach($sections->Tensile->Certtparams as $p)
				{
					//if (++$j == 5) break;
$html .=<<<EOF

	<td   colspan="$c"  style="padding:2px;vertical-align:middle;font-size:6px;" >$p->Description</td>
EOF;
					
					
				}

if(!empty($sections->Proofload->Certtest->RefId))
{
					
$certpparams=$sections->Proofload->Certpparams;			
$html .=<<<EOF
			<td  colspan="$d" style="padding:2px;vertical-align:middle;">Proofload </td>
			
EOF;
		
}
if(!empty($sections->Impact->Certtest->RefId))
{								
				
$certiparams=$sections->Impact->Certiparams;			
$html .=<<<EOF
			<td  colspan="$e" style="padding:2px;vertical-align:middle;">Impact <br> @ $certiparams->Temp</td>
EOF;
}			
if(!empty($sections->Hardness->Certtest->RefId))
{								
		
$html .=<<<EOF
				<td colspan="$f" style="padding:2px;">Hardness</td>
EOF;
}
$html .=<<<EOF
					<td rowspan="3" colspan="$g"  style="padding:2px;padding-left:5px;padding-right:5px;vertical-align:middle;">Remark</td>
				</tr>

EOF;
$m=$a-1;
$html .=<<<EOF
	<tr class="text-center">
					<td style="vertical-align:middle;padding:2px;" rowspan="2">Sr.No.</td>
					<td colspan="$m" style="vertical-align:middle;padding:2px;" rowspan="2">Batch Code</td>
					<td colspan="$b" style="padding:2px;">Min</td>
EOF;
			
				foreach($sections->Tensile->Certtparams as $p)
				{
					//if (++$j == 5) break;
$html .=<<<EOF
					
					<td colspan="$c" style="padding:2px;">$p->Min</td>	
EOF;
				}

if(!empty($sections->Proofload->Certtest->RefId))
{				
$certpparams=$sections->Proofload->Certpparams;	
//$this->_sendResponse(401, CJSON::encode($sections->Proofload));					
$html .=<<<EOF
					
					<td colspan="$d" style="padding:2px;">$certpparams->Min</td>	
EOF;
}		
$certhparams=$sections->Hardness->Certhparams;
if(!empty($sections->Impact->Certtest->RefId))
{		
$html .=<<<EOF
					<td colspan="$e" style="padding:2px;">$certiparams->Min</td>
EOF;
}
if(!empty($sections->Hardness->Certtest->RefId))
{
$html .=<<<EOF
				<td style="padding:2px;" colspan="$f" class="text-center" >$certhparams->Min</td>
EOF;
}
$html .=<<<EOF
			</tr>
EOF;

$html .=<<<EOF
<tr class="text-center">
					<td colspan="$b" style="padding:2px;">Max</td>
EOF;
				
				foreach($sections->Tensile->Certtparams as $p)
				{
					
$html .=<<<EOF
					<td  colspan="$c" style="padding:2px;" >$p->Max</td>
EOF;
				}
if(!empty($sections->Proofload->Certtest->RefId))
{				
$html .=<<<EOF
					
					<td  colspan="$d" style="padding:2px;">$certpparams->Max</td>	
EOF;
}
				
if(!empty($sections->Impact->Certtest->RefId))
{								
$html .=<<<EOF
				<td colspan="$e" style="padding:2px;">$certiparams->Max</td>
EOF;
}
if(!empty($sections->Hardness->Certtest->RefId))
{								
$html .=<<<EOF
				
				<td style="padding:2px;" colspan="$f" class="text-center" >$certhparams->Max</td>
EOF;
}
$html .=<<<EOF
				</tr>
EOF;

$tobbasic_count=count($sections->Tensile->Tobbasic);
$hobbasic_count=count($sections->Hardness->Hobbasic);
$iobbasic_count=count($sections->Impact->Observations);
$maxval=max($tobbasic_count,$hobbasic_count,$iobbasic_count);

$tobbasic=$sections->Tensile->Tobbasic;
$pobs=$sections->Proofload->Observations;
$iobs=$sections->Impact->Observations;
$hobbasic=$sections->Hardness->Hobbasic;

		for($v=0;$v<$maxval;$v++)
		{
		$tob=$tobbasic[$v];	
		$hob=$hobbasic[$v];	
		$index=$v+1;
$html .=<<<EOF
<tr class="text-center"   >
					<td colspan="1" style="padding:2px;">$index</td>
					<td colspan="$m" style="padding:2px;">$hob->BatchCode</td>
					<td colspan="$b" style="padding:2px;">$hob->HeatNo</td>
EOF;

if(!empty($sections->Tensile->Certtest->RefId))
{
				foreach($tob->Observations as $p)
				{
					
					$val=round($p->Value,2);
$html .=<<<EOF
					<td  colspan="$c" style="padding:2px;" >$val</td>
EOF;
				}

		}
	
				
if(!empty($sections->Proofload->Certtest->RefId))
{
	$pob=$pobs[$v];
$html .=<<<EOF
					<td colspan="$d" style="padding:2px;">$pob->Value1</td>
EOF;
					
}	
if(!empty($sections->Impact->Certtest->RefId))
{
$iob=$iobs[$v];							
				$avg=round($iob->Avg,2);
$html .=<<<EOF
					<td colspan="$e" style="padding:2px;">$iob->Value1,$iob->Value2,$iob->Value3 Avg:$avg</td>
EOF;
}

if(!empty($sections->Hardness->Certtest->RefId))
{
	
	$hob=$hobbasic[$v];
$html .=<<<EOF
					
				<td colspan="$f" >
				 <table   cellspacing="0" cellpadding="0">
EOF;
//$this->_sendResponse(401, CJSON::encode($hob));		
if($hob->ShowSurface==="true")
{
$html .=<<<EOF
							 
				<tr>
				
EOF;

if($hob->ShowCore==="true")
{
$html .=<<<EOF
	
	<td style="padding:2px;"   >Surface</td>
EOF;
	
}
				foreach($hob->Observations as $p)
				{
					
$html .=<<<EOF
			
				<td style="padding:2px;"  >$p->SValue</td>
				
EOF;
				}
$html .=<<<EOF
				
				</tr>
EOF;
}	

if($hob->ShowCore==="true")
{
$html .=<<<EOF
			<tr>				
EOF;

if($hob->ShowSurface==="true")
{
$html .=<<<EOF
	
	<td style="padding:2px;"   >Core</td>
EOF;
	
}			

				foreach($hob->Observations as $p)
				{
					
$html .=<<<EOF
			<td style="padding:2px;"  >$p->CValue</td>
EOF;

			}
				
$html .=<<<EOF
				
				</tr>
EOF;
}	

				
$html .=<<<EOF
				</table> 
				
				</td>
EOF;
}		
$html .=<<<EOF
		<td  colspan="$g" style="padding:2px;">
					$hob->Remark
				
					</td>
				</tr>

EOF;
		}
		
if(!empty($sections->Hardness->Certtest->Ref))
	{
		$certhtest=$sections->Hardness->Certtest;
$html .=<<<EOF
				<tr class="text-center"  >
					<td style="padding:2px;text-align:right;" colspan="2">Ref</td>
					<td  colspan="14" style="padding:2px;text-align:left;" >$certhtest->Ref</td>
				</tr>
EOF;
	}
	if(!empty($sections->Hardness->Certtest->Remark))
	{
		$certhtest=$sections->Hardness->Certtest;
$html .=<<<EOF
				<tr class="text-center" >
					<td style="padding:2px;" colspan="2">Remark</td>
					<td  colspan="14" style="padding:2px;" >$certhtest->Remark</td>
				</tr>
EOF;
	}		

$html .=<<<EOF
		</table>
		</td>
		</tr>
EOF;
}

if(!empty($sections->Tension->Certtest->RefId))
{
$html .=<<<EOF

<!--------------Torque Tension Rating-------------->
			<tr >
			<td colspan="16" >
			<table style="font-size:7px;">		
				<tr style="font-size:8px;">
					<td colspan="16" ><b> Torque Tension test results:</b></td>
					
				</tr>
				
				<tr style="text-align:center;">
					<td colspan="1" style="padding:2px;">Sr.No.</td>
					<td colspan="3" style="padding:2px;">Batch No.</td>
					<td colspan="4" style="padding:2px;">Applied torque in NM</td>
					<td colspan="3" style="padding:2px;">Force in KN</td>
					<td colspan="3" style="padding:2px;">Coefficient of friction( µ )</td>
					<td colspan="2" style="padding:2px;">Result</td>
				</tr>
EOF;

					$t=1;
					foreach($sections->Tension->Observations as $obv)
					{
						
$html .=<<<EOF
				
				<tr style="text-align:center;"  >
					<td colspan="1" style="padding:2px;">$t</td>
					<td colspan="3" style="padding:2px;">
					$obv->BatchCode
					</td>
					<td colspan="4" style="padding:2px;">
					$obv->Torque
					</td>
					<td colspan="3" style="padding:2px;">
					$obv->Force
					</td>
					<td colspan="3" style="padding:2px;">
					$obv->Coff_Friction
					</td>
					<td colspan="2" style="padding:2px;">$obv->Remark</td>
				</tr>
EOF;
					$t++;
					}
$html .=<<<EOF
				
				
				
			</table>
			</td>
			</tr>
			
EOF;
}

if(!empty($sections->Wedge->Certtest->RefId))
{

$html .=<<<EOF
			

<!--------------Wedge Test-------------->
			<tr >
			<td colspan="16" class="table-responsive" style="padding:0px;">
			<table style="font-size:7px;">		
				<tr style="font-size:8px;">
					<td colspan="16" style="padding:4px;"><b> Wedge test results:</b></td>
					
				</tr>
				
				<tr style="text-align:center;">
					<td colspan="1" style="padding:2px;">Sr.No.</td>
					<td colspan="4" style="padding:2px;">Batch No.</td>
					<td colspan="4" style="padding:2px;">Required Tensile strength under wedge load(Rm) Mpa
min.</td>
					<td colspan="4" style="padding:2px;">Observed Tensile strength under wedge load(Rm) Mpa</td>
					
					<td colspan="3" style="padding:2px;">Result</td>
				</tr>
EOF;
						$t=1;
					foreach($sections->Wedge->Observations as $obv)
					{
						
$html .=<<<EOF
					<tr style="text-align:center;" ng-repeat="item in cert.sections.Wedge.Observations" >
					<td colspan="1" style="padding:2px;">$t</td>
					<td colspan="4" style="padding:2px;">
					$obv->BatchCode
					</td>
					<td colspan="4" style="padding:2px;">
					$obv->RequiredTS
					</td>
					<td colspan="4" style="padding:2px;">
					$obv->ObservedTS
					</td>
					
					<td colspan="3" style="padding:2px;">$obv->Remark</td>
				</tr>
EOF;
					$t++;
					}
$html .=<<<EOF
					
				
				
			</table>
			</td>
			</tr>
EOF;
			
}

if($allextsections[0]->Selected==='true')
{
$html .=<<<EOF
<!--------------Non Metallic Inclusion Rating-------------->
			<tr>
			<td colspan="16" class="table-responsive" style="padding:0px;">
			<table  style="margin-bottom:0px;font-size:7px;">		
				<tr style="font-size:8px;">
					<td colspan="8" style="padding:4px;"><b> Non Metallic Inclusion Rating:</b></td>
					<td colspan="8" style="padding:4px;">
EOF;
				if(!empty($extsections->NM->Certtest->References))
				{
					$nmrefer=$extsections->NM->Certtest->References;
$html .=<<<EOF
					<b> Reference STD : $nmrefer</b>
EOF;
				}
$html .=<<<EOF
				
					</td>
				</tr>
				
				<tr style="text-align:center;">
					<td colspan="1" style="padding:2px;">Sr.No.</td>
					<td colspan="4" style="padding:2px;">Heat No.</td>
					<td colspan="3" style="padding:2px;">Parameter</td>
					<td colspan="3" style="padding:2px;">Required</td>
					<td colspan="3" style="padding:2px;">Observation</td>
					<td colspan="2" style="padding:2px;">Remark</td>
				</tr>
EOF;
				$n=1;
				foreach($extsections->NM->Observations as $o)
				{
$html .=<<<EOF
				
				<tr style="text-align:center;"  >
					<td colspan="1" style="padding:2px;">$n</td>
					<td colspan="4" style="padding:2px;">
					$o->HeatNo
					</td>
					<td colspan="3" style="padding:2px;">
					$o->Parameter
					</td>
					<td colspan="3" style="padding:2px;">
					$o->Required
					</td>
					<td colspan="3" style="padding:2px;">
					$o->Observation
					</td>
					<td colspan="2" style="padding:2px;">$o->Remark</td>
				</tr>
EOF;
				$n++;
				}
if(!empty($extsections->NM->Certtest->Ref))
				{	
				$nmref=$extsections->NM->Certtest->Ref;					
$html .=<<<EOF
				
				<tr class="text-center"  ng-if="cert.extsections.NM.Certtest.Ref">
					<td style="padding:2px;" colspan="3">Ref</td>
					<td  colspan="13" style="padding:2px;" >$nmref</td>
				</tr>
EOF;
				
				}
				if(!empty($extsections->NM->Certtest->Remark))
				{	
				$nmremark=$extsections->NM->Certtest->Remark;					
$html .=<<<EOF
				<tr class="text-center" ng-if="cert.extsections.NM.Certtest.Remark">
					<td style="padding:2px;" colspan="3">Remark</td>
					<td colspan="13" style="padding:2px;">$nmremark</td>
				</tr>
EOF;
				}
$html .=<<<EOF
				
			</table>
			</td>
			</tr>
EOF;
}


if($allextsections[1]->Selected==='true')
{
$html .=<<<EOF
<!-------------------Micro Examination------------------->				
			<tr ng-if="cert.allextsections[1].Selected==='true'">
			<td colspan="16" class="table-responsive" style="padding:0px;">
			<table  style="margin-bottom:0px;font-size:7px;">		
				<tr style="font-size:8px;">
					<td colspan="8" style="padding:4px;"><b> Micro Examination</b></td>
					<td colspan="8" style="padding:4px;">
EOF;
					
							if(!empty($extsections->ME->Certtest->References))
				{
					$merefer=$extsections->ME->Certtest->References;
$html .=<<<EOF
					<b> Reference STD:$merefer</b>
EOF;
				}
$html .=<<<EOF
					</td>
				</tr>
				
				<tr style="text-align:center;">
					<td colspan="1" style="padding:2px;">Sr.No.</td>
					<td colspan="3" style="padding:2px;">Batch Code</td>
					<td colspan="3" style="padding:2px;">Parameter</td>
					<td colspan="3" style="padding:2px;">Required</td>
					<td colspan="4" style="padding:2px;">Observation</td>
					<td colspan="2" style="padding:2px;">Result</td>
				</tr>
EOF;
				$n=1;
				foreach($extsections->ME->Observations as $o)
				{
$html .=<<<EOF
								
				
				<tr style="text-align:center;"  >
					<td colspan="1" style="padding:2px;">$n</td>
					<td colspan="3" style="padding:2px;">
						$o->BatchCode
					</td>
					<td colspan="3" style="padding:2px;">
						$o->Parameter
					</td>
					<td colspan="3" style="padding:2px;">
						$o->Required
					</td>
					<td colspan="4" style="padding:2px;">
						$o->Observation
					</td>
					<td colspan="2" style="padding:2px;">
						$o->Remark
					</td>
				</tr>
EOF;
				$n++;
				}
if(!empty($extsections->ME->Certtest->Ref))
				{	
				$meref=$extsections->ME->Certtest->Ref;					
$html .=<<<EOF
				
				<tr class="text-center"  ng-if="cert.extsections.ME.Certtest.Ref">
					<td style="padding:2px;" colspan="3">Ref</td>
					<td  colspan="13" style="padding:2px;" >$meref</td>
				</tr>
EOF;
				
				}
				if(!empty($extsections->ME->Certtest->Remark))
				{	
				$meremark=$extsections->ME->Certtest->Remark;					
$html .=<<<EOF
				<tr class="text-center" ng-if="cert.extsections.ME.Certtest.Remark">
					<td style="padding:2px;" colspan="3">Remark</td>
					<td colspan="13" style="padding:2px;">$meremark</td>
				</tr>
EOF;
				}
$html .=<<<EOF
		
			</table>
			</td>
			</tr>			
EOF;
}			
	
if($allextsections[2]->Selected==='true')
{
$html .=<<<EOF
	
<!-----------------MPI Details------------------>
			<tr ng-if="cert.allextsections[2].Selected==='true'">
			<td colspan="16" class="table-responsive" style="padding:0px;">
			<table  style="margin-bottom:0px;font-size:7px;">		
				<tr style="font-size:8px;">
					<td colspan="8" style="padding:4px;"><b> MPI Details as per P28A-AL-0203:</b></td>
					<td colspan="8" style="padding:4px;">
EOF;
					
						if(!empty($extsections->MPI->Certtest->References))
				{
					$mpirefer=$extsections->MPI->Certtest->References;
$html .=<<<EOF
					<b> Reference STD:$mpirefer</b>
EOF;
				}
$html .=<<<EOF
					</td>
				</tr>
				
				<tr style="text-align:center;">
					<td colspan="1" style="padding:2px;">Sr.No.</td>
					<td colspan="4" style="padding:2px;">Parameter</td>
					<td colspan="4" style="padding:2px;">Required</td>
					<td colspan="4" style="padding:2px;">Observation</td>
					<td colspan="3" style="padding:2px;">Result</td>
				</tr>
EOF;
	$n=1;
				foreach($extsections->MPI->Observations as $o)
				{
$html .=<<<EOF
				<tr style="text-align:center;" ng-repeat="item in cert.extsections.MPI.Observations">
					<td colspan="1" style="padding:2px;">$n</td>
					<td colspan="4" style="padding:2px;">
					$o->Parameter
					</td>
					<td colspan="4" style="padding:2px;">
					$o->Required
					</td>
					<td colspan="4" style="padding:2px;">
					$o->Observation
					</td>
					<td colspan="3" style="padding:2px;">$o->Remark</td>
				</tr>
EOF;
					$n++;
				}
				
if(!empty($extsections->MPI->Certtest->Ref))
				{	
				$mpiref=$extsections->MPI->Certtest->Ref;					
$html .=<<<EOF
				
				<tr class="text-center"  ng-if="cert.extsections.MPI.Certtest.Ref">
					<td style="padding:2px;" colspan="3">Ref</td>
					<td  colspan="13" style="padding:2px;" >$mpiref</td>
				</tr>
EOF;
				
				}
				if(!empty($extsections->MPI->Certtest->Remark))
				{	
				$mpiremark=$extsections->MPI->Certtest->Remark;					
$html .=<<<EOF
				<tr class="text-center" ng-if="cert.extsections.MPI.Certtest.Remark">
					<td style="padding:2px;" colspan="3">Remark</td>
					<td colspan="13" style="padding:2px;">$mpiremark</td>
				</tr>
EOF;
				}
$html .=<<<EOF
				
			</table>
			</td>
			</tr>
EOF;
}
if($allextsections[3]->Selected==='true')
{
	$hobs=$extsections->H->Observations;
$html .=<<<EOF
<!-------------------Hardness------------------->		
			<tr ng-if="cert.allextsections[3].Selected==='true'">
			<td colspan="16" class="table-responsive" style="padding:0px;">
			<table  style="margin-bottom:0px;font-size:7px;">		
				<tr style="font-size:8px;">
					<td colspan="8" style="padding:4px;"><b> Hardness</b></td>
					<td colspan="8" style="padding:4px;">
EOF;
				if(!empty($extsections->H->Certtest->References))
				{
					$hrefer=$extsections->H->Certtest->References;
$html .=<<<EOF
					<b> Reference STD : $hrefer</b>
EOF;
				}
				
$html .=<<<EOF
					</td>
				</tr>
				
				<tr style="text-align:center;">
					<td colspan="4" style="vertical-align: middle;padding:2px;" rowspan="2">$hobs->Parameter</td>
					<td colspan="4" style="padding:2px;">Requirement</td>
EOF;
			if($hobs->Type==='attach')
			{
$html .=<<<EOF
					
					<td colspan="6"  ng-if="cert.extsections.H.Observations.Type==='attach'"  style="vertical-align: middle;padding:2px;" >Observations</td>
EOF;
			}
			
			if($hobs->Type!='attach')
			{
$html .=<<<EOF
					
					<td colspan="2"  ng-if="cert.extsections.H.Observations.Type!='attach'" style="vertical-align: middle;padding:2px;" >1</td>
					<td colspan="2"  ng-if="cert.extsections.H.Observations.Type!='attach'" style="vertical-align: middle;padding:2px;" >2</td>
					<td colspan="2"   ng-if="cert.extsections.H.Observations.Type!='attach'" style="vertical-align: middle;padding:2px;" >3</td>
EOF;
			}
			
$html .=<<<EOF
					
					<td colspan="2" style="vertical-align: middle;padding:2px;" >Remark</td>
				</tr>
EOF;
$html .=<<<EOF
				
				<tr style="text-align:center;">
					<td colspan="4" style="vertical-align: middle;padding:2px;">$hobs->Requirement</td>
EOF;
			if($hobs->Type==='attach')
			{
$html .=<<<EOF
					
				<td ng-if="cert.extsections.H.Observations.Type==='attach'" colspan="6" style="vertical-align: middle;padding:2px;">$hobs->Observation</td>
EOF;
			}
			
			if($hobs->Type!='attach')
			{
$html .=<<<EOF
										
					
					<td colspan="2"  ng-if="cert.extsections.H.Observations.Type!='attach'" style="padding:2px;">
						$hobs->Obs1
					</td>
					<td colspan="2"  ng-if="cert.extsections.H.Observations.Type!='attach'" style="padding:2px;">
						$hobs->Obs2
					</td>
					<td colspan="2" ng-if="cert.extsections.H.Observations.Type!='attach'" style="padding:2px;">
						$hobs->Obs3
					</td>
EOF;
			}
			
$html .=<<<EOF
					
						
					
					<td colspan="2" style="vertical-align: middle;padding:2px;">$hobs->Remark</td>
				</tr>
EOF;
if(!empty($extsections->H->Certtest->Ref))
				{	
				$href=$extsections->H->Certtest->Ref;					
$html .=<<<EOF
				
				<tr class="text-center"  ng-if="cert.extsections.H.Certtest.Ref">
					<td style="padding:2px;" colspan="3">Ref</td>
					<td  colspan="13" style="padding:2px;" >$href</td>
				</tr>
EOF;
				
				}
				if(!empty($extsections->H->Certtest->Remark))
				{	
				$hremark=$extsections->H->Certtest->Remark;					
$html .=<<<EOF
				<tr class="text-center" ng-if="cert.extsections.H.Certtest.Remark">
					<td style="padding:2px;" colspan="3">Remark</td>
					<td colspan="13" style="padding:2px;">$hremark</td>
				</tr>
EOF;
				}
$html .=<<<EOF
				
			</table>
			</td>
			</tr>		
						
EOF;
}
if($allextsections[4]->Selected==='true')
{
$html .=<<<EOF
			
<!-------------------Non Destructive Testing------------------->		
			<tr >
			<td colspan="16" class="table-responsive" style="padding:0px;">
			<table  style="margin-bottom:0px;font-size:7px;">		
				<tr style="font-size:8px;">
					<td colspan="8" style="padding:4px;"><b> Non Destructive Testing</b></td>
					<td colspan="8" style="padding:4px;">
EOF;
		if(!empty($extsections->ND->Certtest->References))
				{
					$ndreferences=$extsections->ND->Certtest->References;
$html .=<<<EOF
					<b> Reference STD : $ndreferences</b>
EOF;
				}
$html .=<<<EOF
					
					</td>
				</tr>
				
				<tr style="text-align:center;">
					<td colspan="1" style="padding:2px;">Sr.No.</td>
					<td colspan="4" style="padding:2px;">Parameter</td>
					<td colspan="4" style="padding:2px;">Required</td>
					<td colspan="5" style="padding:2px;">Observation</td>
					<td colspan="2" style="padding:2px;">Remark</td>
				</tr>
EOF;
				$n=1;
				foreach($extsections->ND->Observations as $o)
				{
$html .=<<<EOF
							
				<tr style="text-align:center;" ng-repeat="item in cert.extsections.ND.Observations" >
					<td style="vertical-align: middle;padding:2px;" colspan="1">$n</td>
					<td colspan="4" style="padding:2px;">
						$o->Parameter
					</td>
					<td colspan="4" style="padding:2px;">
						$o->Required
					</td>
					<td colspan="5" style="padding:2px;">
						$o->Observation
					</td>
					<td style="vertical-align: middle;padding:2px;" colspan="2">$o->Remark</td>
				</tr>
EOF;
				$n++;
				}
				
if(!empty($extsections->ND->Certtest->Ref))
				{	
$ndref=$extsections->ND->Certtest->Ref;			
$html .=<<<EOF

				
				<tr class="text-center"  ng-if="cert.extsections.ND.Certtest.Ref">
					<td style="padding:2px;" colspan="3">Ref</td>
					<td  colspan="13" style="padding:2px;" >$ndref</td>
				</tr>
EOF;
				}	
if(!empty($extsections->ND->Certtest->Remark))
				{	
$ndremark=$extsections->ND->Certtest->Remark;			
$html .=<<<EOF
				<tr class="text-center" ng-if="cert.extsections.ND.Certtest.Remark">
					<td style="padding:2px;" colspan="3">Remark</td>
					<td colspan="13" style="padding:2px;">$ndremark</td>
				</tr>
EOF;
				}		
$html .=<<<EOF
			
			</table>
			</td>
			</tr>
	
EOF;
}
if($allextsections[5]->Selected==='true')
{
$html .=<<<EOF
<!-----------------Surface Treatment----------------->
			<tr ng-if="cert.allextsections[5].Selected==='true'">
			<td colspan="16" class="table-responsive" style="padding:0px;">
			<table  style="margin-bottom:0px;font-size:7px;">
				<tr style="font-size:8px;">
					<td colspan="8" style="padding:4px;"><b> Surface Treatment</b></td>
					<td colspan="8" style="padding:4px;">
EOF;
				if(!empty($extsections->ST->Certtest->References))
				{
					$strefer=$extsections->ST->Certtest->References;
$html .=<<<EOF
					<b> Reference STD : $strefer</b>
EOF;
				}
$html .=<<<EOF
					</td>
				</tr>
				<tbody>
						<tr style="text-align:center;">
							<td colspan="1" style="padding:2px;">Sr.No.</td>
							<td colspan="6" style="padding:2px;">Painting System specification</td>
							<td colspan="6" style="padding:2px;">Coat/Observation</td>
							<td colspan="3" style="padding:2px;">Remark</td>
						</tr>
EOF;
						
		$n=1;
				foreach($extsections->ST->Observations as $o)
				{
$html .=<<<EOF
						<tr style="text-align:center;" ng-repeat="item in cert.extsections.ST.Observations" >
							<td colspan="1" style="padding:2px;">
								$n
							</td>
							
							<td colspan="6" style="padding:2px;">
								$o->Painting
							</td>
							<td colspan="6" style="padding:2px;">
								$o->Coat
							</td>
							<td colspan="3" style="padding:2px;">
								$o->Remark
							</td>
						</tr>
EOF;
				$n++;
				}
if(!empty($extsections->ST->Certtest->Ref))
				{	
				$stref=$extsections->ST->Certtest->Ref;					
$html .=<<<EOF
				
				<tr class="text-center"  ng-if="cert.extsections.ST.Certtest.Ref">
					<td style="padding:2px;" colspan="3">Ref</td>
					<td  colspan="13" style="padding:2px;" >$stref</td>
				</tr>
EOF;
				
				}
				if(!empty($extsections->ST->Certtest->Remark))
				{	
				$stremark=$extsections->ST->Certtest->Remark;					
$html .=<<<EOF
				<tr class="text-center" ng-if="cert.extsections.ST.Certtest.Remark">
					<td style="padding:2px;" colspan="3">Remark</td>
					<td colspan="13" style="padding:2px;">$stremark</td>
				</tr>
EOF;
				}
$html .=<<<EOF
				</tbody>
			</table>
			</td>
			</tr>
EOF;
}
if($allextsections[6]->Selected==='true')
{
$html .=<<<EOF
<!---------------Delta Coating--------------->
			<tr ng-if="cert.allextsections[6].Selected==='true'">
			<td colspan="16" class="table-responsive" style="padding:0px;">
			<table class="table table-bordered " style="margin-bottom:0px;font-size:7px;">
				<tr style="font-size:8px;">
					<td colspan="8" style="padding:4px;"><b> Surface Treatment</b></td>
					
					<td colspan="8" style="padding:4px;">
EOF;
				if(!empty($extsections->STDELTA->Certtest->References))
				{
					$delrefer=$extsections->STDELTA->Certtest->References;
$html .=<<<EOF
					<b> Reference STD : $delrefer</b>
EOF;
				}
$html .=<<<EOF
					</td>
				</tr>
EOF;
			
$obs=$extsections->STDELTA->Observations;
	if($obs->Type==='attach')
	{
$html .=<<<EOF
				<tbody ng-if="cert.extsections.STDELTA.Observations.Type==='attach'">
				
					<tr >
						<td colspan="16" align="center" style="padding:2px;">
							$obs->Observations
						</td>
					</tr>
				</tbody>
EOF;
	}
	if($obs->Type!='attach')
	{
$html .=<<<EOF
		<!-------Delta-nonattached------->
				<tbody ng-if="cert.extsections.STDELTA.Observations.Type !='attach'">
					<tr class="text-center">
						<td  colspan="4" rowspan="2" style="padding:2px;vertical-align:middle;">Parameter</td>
						<td colspan="4" style="padding:2px;">Requirement</td>
						<td colspan="5" rowspan="2"  style="padding:2px;vertical-align:middle;">Observation</td>
						<td colspan="3" rowspan="2"  style="padding:2px;vertical-align:middle;">Remark</td>
					</tr>
					
					<tr class="text-center">
						<td colspan="2" style="padding:2px;">Min</td>
						<td colspan="2" style="padding:2px;">Max</td>
					</tr>
					
					<tr class="text-center">
						<td colspan="4" style="padding:2px;">
						Coating thickness µm
						</td>
						
						<td colspan="2" style="padding:2px;">
							$obs->CoatMin
						</td>
						
						<td colspan="2" style="padding:2px;">
							$obs->CoatMax
						</td>
						
						<td rowspan="4" colspan="5" style="padding:2px;">
							$obs->Observations
						</td>
						
						<td colspan="3" style="padding:2px;">
							$obs->CoatRemark
						</td>
					</tr>
					
					<tr class="text-center">
						<td colspan="4" style="padding:2px;">
						Salt Spray Test(Hrs.)
						</td>
						
						<td colspan="2" style="padding:2px;">
							$obs->SaltMin
						</td>
						
						<td colspan="2" style="padding:2px;">
							$obs->SaltMax
						</td>
					
						<td colspan="3" style="padding:2px;">
							$obs->SaltRemark
						</td>
					</tr>
					
					<tr class="text-center">
						<td colspan="4" style="padding:2px;">
						Adhesion		

						</td>
						
						<td colspan="4" style="padding:2px;">
							$obs->AdhesionReq
						</td>
						
						<td colspan="3" style="padding:2px;">
							$obs->AdhesionRemark
						</td>
					</tr>
					
					<tr class="text-center">
						<td colspan="4" style="padding:2px;">
							Visual Inspection
						</td>
						
						<td colspan="4" style="padding:2px;">
							$obs->VisualReq
						</td>
					
						<td colspan="3" style="padding:2px;">
							$obs->VisualRemark
						</td>
					</tr>
EOF;
	}
if(!empty($extsections->STDELTA->Certtest->Ref))
				{	
				$delref=$extsections->STDELTA->Certtest->Ref;					
$html .=<<<EOF
				
				<tr class="text-center"  ng-if="cert.extsections.STDELTA.Certtest.Ref">
					<td style="padding:2px;" colspan="2">Ref</td>
					<td  colspan="10" style="padding:2px;" >$delref</td>
				</tr>
EOF;
				
				}
				if(!empty($extsections->STDELTA->Certtest->Remark))
				{	
				$delremark=$extsections->STDELTA->Certtest->Remark;					
$html .=<<<EOF
				<tr class="text-center" ng-if="cert.extsections.STDELTA.Certtest.Remark">
					<td style="padding:2px;" colspan="3">Remark</td>
					<td colspan="13" style="padding:2px;">$delremark</td>
				</tr>
EOF;
				}
$html .=<<<EOF
				</tbody>
			</table>
			</td>
			</tr>
EOF;
}
if($allextsections[7]->Selected==='true')
{
	
	$obs=$extsections->STDA->Observations;
$html .=<<<EOF
		<!---------------Surface-Dacromet Coating--------------->	
			<tr ng-if="cert.allextsections[7].Selected==='true'">
			<td colspan="12" class="table-responsive" style="padding:0px;">
			<table  style="margin-bottom:0px;font-size:7px;">
			<tr style="font-size:8px;">
				<td colspan="6" style="padding:4px;"><b> Surface Treatment</b></td>
				<td colspan="6" style="padding:4px;">
				
EOF;
				if(!empty($extsections->STDA->Certtest->References))
				{
					$darefer=$extsections->STDA->Certtest->References;
$html .=<<<EOF
					<b> Reference STD : $darefer</b>
EOF;
				}
$html .=<<<EOF
				</td>
			</tr>
			<tbody>
				<tr class="text-center">
					<td colspan="4" rowspan="2"  style="padding:2px;vertical-align:middle;">Parameter</td>
					<td colspan="4" style="padding:2px;">Requirement</td>
					<td colspan="5" rowspan="2"  style="padding:2px;vertical-align:middle;">Observation</td>
					<td colspan="3" rowspan="2"  style="padding:2px;vertical-align:middle;">Remark</td>
				</tr>
				
				<tr class="text-center">
					<td colspan="2" style="padding:2px;">Min</td>
					<td colspan="2" style="padding:2px;">Max</td>
				</tr>
				
				<tr class="text-center">
					<td colspan="4" style="padding:2px;">
						Coating thickness µm
					</td>
					
					<td colspan="2" style="padding:2px;">
						$obs->CoatMin
					</td>
					
					<td colspan="2" style="padding:2px;">
						$obs->CoatMax
					</td>
					
					<td rowspan="4" colspan="5" style="padding:2px;">
						$obs->Observations
					</td>
					
					<td colspan="2" style="padding:2px;">
						$obs->CoatRemark
					</td>
				</tr>
				
				<tr class="text-center">
					<td colspan="4" style="padding:2px;">
						Salt Spray Test(Hrs.)
					</td>
					
					<td colspan="2" style="padding:2px;">
						$obs->SaltMin
					</td>
					
					<td colspan="2" style="padding:2px;">
						$obs->SaltMax
					</td>
				
					<td colspan="3" style="padding:2px;">
						$obs->SaltRemark
					</td>
				</tr>
				
				<tr class="text-center">
					<td colspan="4" style="padding:2px;">
						Adhesion
					</td>
					
					<td colspan="4" style="padding:2px;">
						$obs->AdhesionReq
					</td>
					
					<td colspan="3" style="padding:2px;">
						$obs->AdhesionRemark
					</td>
				</tr>
				
				<tr class="text-center">
					<td colspan="4" style="padding:2px;">
						Visual Inspection
					</td>
					
					<td colspan="4" style="padding:2px;">
						$obs->VisualReq
					</td>
				
					<td colspan="3" style="padding:2px;">
						$obs->VisualRemark
					</td>
				</tr>
EOF;

if(!empty($extsections->STDA->Certtest->Ref))
				{	
				$daref=$extsections->STDA->Certtest->Ref;					
$html .=<<<EOF
				
				<tr class="text-center"  ng-if="cert.extsections.STDA.Certtest.Ref">
					<td style="padding:2px;" colspan="3">Ref</td>
					<td  colspan="13" style="padding:2px;" >$daref</td>
				</tr>
EOF;
				
				}
				if(!empty($extsections->STDA->Certtest->Remark))
				{	
				$daremark=$extsections->STDA->Certtest->Remark;					
$html .=<<<EOF
				<tr class="text-center" ng-if="cert.extsections.STDA.Certtest.Remark">
					<td style="padding:2px;" colspan="3">Remark</td>
					<td colspan="13" style="padding:2px;">$daremark</td>
				</tr>
EOF;
				}
$html .=<<<EOF
					
				</tbody>
			</table>
			</td>
			</tr>
EOF;
}
if($allextsections[8]->Selected==='true')
{
	
	$obs=$extsections->STHOT->Observations;
	
$html .=<<<EOF
<!---------------Surface- Hot DIP Galvanizing--------------->
			<tr ng-if="cert.allextsections[8].Selected==='true'">
			<td colspan="12" class="table-responsive" style="padding:0px;">
			<table  style="margin-bottom:0px;font-size:7px;">
				<tr style="font-size:8px;">
					<td colspan="6" style="padding:4px;"><b> Surface Treatment </b></td>
					<td colspan="6" style="padding:4px;">
EOF;
				if(!empty($extsections->STHOT->Certtest->References))
				{
					$hotrefer=$extsections->STHOT->Certtest->References;
$html .=<<<EOF
					<b> Reference STD : $hotrefer</b>
EOF;
				}
$html .=<<<EOF
					</td>
				</tr>
				<tbody>
					<tr style="text-align:center;">
						
						<td colspan="4" rowspan="2" style="padding:2px;">Parameter</td>
						<td colspan="4" rowspan="2" style="padding:2px;">Required</td>
						<td colspan="5" style="padding:2px;">Observation</td>
						<td rowspan="3" style="padding:2px;">Remark</td>
					</tr>
					
					<tr class="text-center">
						<td style="padding:2px;">1</td>
						<td style="padding:2px;">2</td>
						<td style="padding:2px;">3</td>
						<td style="padding:2px;">4</td>
						<td style="padding:2px;">5</td>
					</tr>
					<tr class="text-center">
						<td colspan="4" style="padding:2px;">
							 Local coating Thickness
						</td>
						
						<td colspan="4" style="padding:2px;">
							$obs->ReqLocal
						</td>
						<td colspan="1" style="padding:2px;">
							$obs->Obs1
						</td>
						<td colspan="1" style="padding:2px;">
							$obs->Obs2
						</td>
						<td colspan="1" style="padding:2px;">
							$obs->Obs3
						</td>
						<td colspan="1" style="padding:2px;">
							$obs->Obs4
						</td>
						<td colspan="1" style="padding:2px;">
							$obs->Obs5
						</td>
						<td colspan="3" style="padding:2px;">
							$obs->LocalRemark
						</td>
					</tr>
					<tr class="text-center">
						<td colspan="4" style="padding:2px;">
							 Visual
						</td>
						
						<td colspan="4" style="padding:2px;">
							$obs->ReqVisual
						</td>
						<td colspan="5" style="padding:2px;">
							$obs->Observation
						</td>
						<td colspan="3" style="padding:2px;">
							$obs->VisualRemark
						</td>
					</tr>
EOF;
if(!empty($extsections->STHOT->Certtest->Ref))
				{	
				$hotref=$extsections->STHOT->Certtest->Ref;					
$html .=<<<EOF
				
				<tr class="text-center"  ng-if="cert.extsections.STHOT.Certtest.Ref">
					<td style="padding:2px;" colspan="3">Ref</td>
					<td  colspan="13" style="padding:2px;" >$hotref</td>
				</tr>
EOF;
				
				}
				if(!empty($extsections->STHOT->Certtest->Remark))
				{	
				$hotremark=$extsections->STHOT->Certtest->Remark;					
$html .=<<<EOF
				<tr class="text-center" ng-if="cert.extsections.STHOT.Certtest.Remark">
					<td style="padding:2px;" colspan="3">Remark</td>
					<td colspan="13" style="padding:2px;">$hotremark</td>
				</tr>
EOF;
				}
$html .=<<<EOF
				</tbody>
			</table>
			</td>
			</tr>
EOF;
}
if($allextsections[9]->Selected==='true')
{
$html .=<<<EOF
<!-------------------Surface-Plating------------------->		
			<tr ng-if="cert.allextsections[9].Selected==='true'">
			<td colspan="16" class="table-responsive" style="padding:0px;">
			<table  style="margin-bottom:0px;font-size:7px;">		
				<tr style="font-size:8px;">
					<td colspan="8" style="padding:4px;"><b> Surface Treatment</b></td>
					<td colspan="8" style="padding:4px;">
EOF;
				if(!empty($extsections->STP->Certtest->References))
				{
					$stprefer=$extsections->STP->Certtest->References;
$html .=<<<EOF
					<b> Reference STD : $stprefer</b>
EOF;
				}
$html .=<<<EOF
					</td>
				</tr>
				
				<tr style="text-align:center;">
					<td colspan="1" style="vertical-align: middle;padding:2px;" rowspan="2">Sr.No.</td>
					<td colspan="4" style="vertical-align: middle;padding:2px;" rowspan="2">Parameter</td>
					<td colspan="4" style="padding:2px;">Requirement</td>
					<td colspan="5" style="vertical-align: middle;padding:2px;" rowspan="2">Observation</td>
					<td colspan="2" style="vertical-align: middle;padding:2px;" rowspan="2">Remark</td>
				</tr>
				
				<tr style="text-align:center;">
					<td colspan="2" style="vertical-align: middle;padding:2px;">Min.</td>
					<td colspan="2" style="vertical-align: middle;padding:2px;">Max.</td>
				</tr>
EOF;
$o=$extsections->STP->Observations;
$n=1;
$html .=<<<EOF
				
				<tr style="text-align:center;">
					<td colspan="1" style="padding:2px;">$n</td>
					<td colspan="4" style="padding:2px;">Local coating Thickness</td>
					<td colspan="2" style="padding:2px;">
						$o->ReqMin
					</td>
					<td colspan="2" style="padding:2px;">
						$o->ReqMax
					</td>
					<td colspan="1" style="padding:2px;">
						$o->Obs1
					</td>
					<td colspan="1" style="padding:2px;">
						$o->Obs2
					</td>
					<td colspan="1" style="padding:2px;">
						$o->Obs3
					</td>
					<td colspan="1" style="padding:2px;">
						$o->Obs4
					</td>
					<td colspan="1" style="padding:2px;">
						$o->Obs5
					</td>
					<td colspan="2" style="vertical-align: middle;padding:2px;">$o->Remark</td>
				</tr>
EOF;
if(!empty($extsections->STP->Certtest->Ref))
				{	
				$stpref=$extsections->STP->Certtest->Ref;					
$html .=<<<EOF
				
				<tr class="text-center"  ng-if="cert.extsections.STP.Certtest.Ref">
					<td style="padding:2px;" colspan="3">Ref</td>
					<td  colspan="13" style="padding:2px;" >$stpref</td>
				</tr>
EOF;
				
				}
				if(!empty($extsections->STP->Certtest->Remark))
				{	
				$stpremark=$extsections->STP->Certtest->Remark;					
$html .=<<<EOF
				<tr class="text-center" ng-if="cert.extsections.STP.Certtest.Remark">
					<td style="padding:2px;" colspan="3">Remark</td>
					<td colspan="13" style="padding:2px;">$stpremark</td>
				</tr>
EOF;
				}
$html .=<<<EOF
				
			</table>
			</td>
			</tr>		
EOF;
}
if($allextsections[10]->Selected==='true')
{
$html .=<<<EOF
<!-------------------Other Details------------------->		
			<tr ng-if="cert.allextsections[10].Selected==='true'">
			<td colspan="16" class="table-responsive" style="padding:0px;">
			<table  style="margin-bottom:0px;font-size:7px;">		
				<tr Style="font-size:8px;">
					<td colspan="8" style="padding:4px;"><b> Other Details</b></td>
					<td colspan="8" style="padding:4px;">
EOF;
					
							if(!empty($extsections->OD->Certtest->References))
				{
					$odrefer=$extsections->OD->Certtest->References;
$html .=<<<EOF
					<b> Reference STD :$odrefer</b>
EOF;
				}
$html .=<<<EOF
					
					</td>
				</tr>
				
				<tr style="text-align:center;">
					<td colspan="1" style="padding:2px;">Sr.No.</td>
					<td colspan="4" style="padding:2px;">Parameter</td>
					<td colspan="4" style="padding:2px;">Required</td>
					<td colspan="4" style="padding:2px;">Observation</td>
					<td colspan="3" style="padding:2px;">Result</td>
				</tr>
EOF;
				$n=1;
				foreach($extsections->OD->Observations as $o)
				{
$html .=<<<EOF
									
				<tr style="text-align:center;"  ng-repeat="item in cert.extsections.OD.Observations" >
					<td colspan="1" style="padding:2px;">$n</td>
					<td colspan="4" style="padding:2px;">$o->Parameter</td>
					<td colspan="4" style="padding:2px;">
						$o->Required
					</td>
					<td colspan="4" style="padding:2px;">
						$o->Observation
					</td>
					<td colspan="3" style="vertical-align: middle;padding:2px;">$o->Remark</td>
				</tr>
EOF;
					$n++;
				}	
				
				if(!empty($extsections->OD->Certtest->Ref))
				{	
				$odref=$extsections->OD->Certtest->Ref;					
$html .=<<<EOF
				
				<tr class="text-center"  ng-if="cert.extsections.OD.Certtest.Ref">
					<td style="padding:2px;" colspan="3">Ref</td>
					<td  colspan="13" style="padding:2px;" >$odref</td>
				</tr>
EOF;
				
				}
				if(!empty($extsections->OD->Certtest->Remark))
				{	
				$odremark=$extsections->OD->Certtest->Remark;					
$html .=<<<EOF
				<tr class="text-center" ng-if="cert.extsections.OD.Certtest.Remark">
					<td style="padding:2px;" colspan="3">Remark</td>
					<td colspan="13" style="padding:2px;">$odremark</td>
				</tr>
EOF;
				}
$html .=<<<EOF
				
			</table>
			</td>
			</tr>		
EOF;
}

$html .=<<<EOF
<!---------Footer--------------------------------------->
<tr>
<td colspan="16" style="padding:4px;"><b> The results of tests are satisfactory.</b></td>
</tr>
<tr style="border:none ;" class="mybg">
<td colspan="2"  style="padding:4px;border:none ;"> </td>
<td colspan="4"  style="padding:4px;border:none ;"> 
<span style="padding-left:20px;">Prepared By,</span>
<br>
<figure class="figure" style="display:inherit;">
			<img  src="$basic->PreparedSign"  class="figure-img img-fluid rounded" style="height:40px;width:auto;padding-left:20px;" alt=""/>
			<figcaption class="figure-caption text-center" ><br>$basic->PreparedBy</figcaption>

<br>
<span style="padding-left:20px;">Jr.Engineer - QA</span>
</td>
<td colspan="4"  style="padding:4px;border:none ;"> 
<span style="padding-left:20px;">Checked By,</span>
<br>
<figure class="figure" style="display:inherit;">
			<img  src="$basic->CheckedSign"  class="figure-img img-fluid rounded" style="height:40px;width:auto;padding-left:20px;" alt=""/>
			<figcaption class="figure-caption text-center" ><br>$basic->CheckedBy</figcaption></figure>

<br>
<span style="padding-left:20px;">Asst.Manager-QA</span>
</td>
<td colspan="4"  style="padding:4px;border:none ;">
<span style="padding-left:20px;"> Approved By,</span>
 <br>
<figure class="figure" style="display:inherit;">
			<img  src="$basic->ApprovedSign"  class="figure-img img-fluid rounded" style="height:40px;width:auto;padding-left:20px;" alt=""/>
			<figcaption class="figure-caption text-center" ><br>$basic->ApprovedBy</figcaption></figure>
<br>
<span style="padding-left:20px;">Manager Engineering</span>
</td>
<td colspan="2"  style="padding:4px;border:none ;"> </td>
</tr>	

EOF;




			
		$html .=<<<EOF
		</tbody></table>
EOF;

        //Convert the Html to a pdf document
        $pdf->writeHTML($html, true, false, true, false, '');
 
        //$header = array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)'); //TODO:you can change this Header information according to your need.Also create a Dynamic Header.
 
        // data loading
    //  $data = array();//$pdf->LoadData(Yii::getPathOfAlias('ext.tcpdf').DIRECTORY_SEPARATOR.'table_data_demo.txt'); //This is the example to load a data from text file. You can change here code to generate a Data Set from your model active Records. Any how we need a Data set Array here.
        // print colored table
    //    $pdf->ColoredTable($header, $data);
        // reset pointer to the last page
        $pdf->lastPage();
 
        //Close and output PDF document
        $pdf->Output(Yii::app()->basePath.'/../samplepdfs/TCs\/'.$basic->TCNo.'.pdf', 'F');
        //Yii::app()->end();
		$var="done";
 $this->_sendResponse(200, CJSON::encode($var));
				break;
				
				case 'exportpdf':
				
					$c=Certbasic::model()->findByPk($_GET['id']);
						$attachments=array();

						$attachments=$c->certattachments;
						// Yii::import('application.vendors.*');
						// include 'PDFMerger/PDFMerger.php';

						$pdf = new PDFMerger;
				$pdf->addPDF(Yii::app()->basePath.'/../samplepdfs/TCs/'.$c->TCNo.'.pdf', 'all');
				foreach($attachments as $a)
				{
				$pdf->addPDF(Yii::app()->basePath.'/../../images/certattachments/files/'.$a->name, 'all');
				}
						
					$pdf->merge('file', Yii::app()->basePath.'/../../exportpdfs/'.$c->TCNo.'.pdf');
						$var="done";
							$this->_sendResponse(200, CJSON::encode($var));
					break;
				
				case 'certeditpredata':
				
					$c=Certbasic::model()->findByPk($_GET['id']);
					$certbasic="";
						$chemicals=array();
						$chobs=array();
						$certelements=array();
						$creference="";
						$exrirs=array();
						
						
						$tobs=array();
						$certtparams=array();
						$treference=(object)array('RefId'=>"");
						
						$iobs=array();
						$certiparams=array();
						$ireference=(object)array('RefId'=>"");
						
						$hobs=array();
						$certhparams=array();
						$hreference=(object)array('RefId'=>"");
						
						$pobs=array();
						$certpparams=array();
						$preference=(object)array('RefId'=>"");
						
						$tqobs=array();
						$certtqparams=array();
						$tqreference=(object)array('RefId'=>"");
						
						$wobs=array();
						$certwparams=array();
						$wreference=(object)array('RefId'=>"");
						
						
					if(!empty($c))
					{
						$format=(object)array('Format'=>$c->Format,'TCFormat'=>$c->TCFormat);
						$certbasic=(object)array('Id'=>$c->Id,'CertDate'=>$c->CertDate,'ItemCode'=>$c->ItemCode,'Material'=>$c->Material,'PartDescription'=>$c->PartDescription,
						'PoDate'=>$c->PoDate,'PoLineItemNo'=>$c->PoLineItemNo,'PoNo'=>$c->PoNo,'PosNo'=>$c->PosNo,'Project'=>$c->Project,'ProjectNo'=>$c->ProjectNo,'Qty'=>$c->Qty,
						'RFICoNo'=>$c->RFICoNo,'RFIDate'=>$c->RFIDate,'RefStd'=>$c->RefStd,'SlNo'=>$c->SlNo,'TCNo'=>$c->TCNo,'TestPlanNo'=>$c->TestPlanNo,
						'Customer'=>$c->customer->CustomerName,'CustomerId'=>$c->CustomerId,'Format'=>$format);
						
						
						
						foreach($c->certrirs as $r)
						{
							$exrirs[]=Receiptir::model()->findByPk($r->RirId);
						}
							/*-----Chemical Composition-------------------------------*/
							
								$cts=Certtest::model()->findAll(array('condition'=>'CertBasicId=:id AND Section="chemical"',
										 'params'=>array(':id'=>$c->Id),));
																
									
									if(!empty($cts))
									{
										 foreach($cts as $ct)
										{
																				
										$creference=(object)array('RefId'=>$ct->RefId,'References'=>$ct->References,'RefExtra'=>$ct->RefExtra,'Ref'=>$ct->Ref,'Remark'=>$ct->Remark);
																													
										$certelements=Certchelements::model()->findAll(array('condition'=>'CertTestId=:id',
										 'params'=>array(':id'=>$ct->Id),));
										 
										 $chobs=array();
										 foreach($ct->certchobbasics as $ch)
										 {
											 $observations=array();
											 foreach($ch->certchobservations as $v)
											 {
												 $observations[]=(object)array('CertCheleId'=>$v->CertCheleId,'ChObbasicId'=>$v->ChObbasicId,
												 'Id'=>$v->Id,'Value'=>$v->Value,'Element'=>$v->certChele->Element)	;
											 }
											 $test=Tests::model()->findByPk($ch->TestId);
											$chobs[]=(object)array('Id'=>$ch->Id,'LabNo'=>$ch->LabNo,'HeatNo'=>$ch->HeatNo,'BatchCode'=>$ch->BatchCode,'Observations'=>$observations,'ShowInCert'=>$ch->ShowInCert,'TestId'=>$ch->TestId,'TestName'=>$test->TestName,'SeqNo'=>$ch->SeqNo,'Remark'=>$ch->Remark);	 
											 
										 }
										$chemicals[]=(object)array('Section'=>"Chemical",'Certtest'=>$creference,'Certelements'=>$certelements,'Chobbasic'=>$chobs);	
								//	$chemicals[]=(object)array('Certelements'=>$certelements,'HeatNo'=>$ch->receiptir->HeatNo,'LabNo'=>$ch->receiptir->LabNo,'BatchCode'=>$ch->receiptir->BatchCode.$bn,
											//		'Observations'=>$sds,'Remark'=>$ch->Remark,'RefId'=>$stdsub->Id,'Certtest'=>$creference,);
										
										}
										
									}
						
						$ctt=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="tensile"',
										 'params'=>array(':id'=>$c->Id),));
														
									
									if(!empty($ctt))
									{
																				
										$treference=(object)array('RefId'=>$ctt->RefId,'References'=>$ctt->References,'RefExtra'=>$ctt->RefExtra,'Ref'=>$ctt->Ref,'Remark'=>$ctt->Remark);
																													
										$oldcerttparams=Certtparams::model()->findAll(array('condition'=>'CertTestId=:id ',
										 'params'=>array(':id'=>$ctt->Id),));
										 foreach($oldcerttparams as $p)
										 {
											 //for lab
											 if($p->Parameter==='PS' || $p->Parameter==='UTS' || $p->Parameter==='EL'|| $p->Parameter==='RA' || $p->Parameter==='R' )
											{
											 $certtparams[]=(object)array('Id'=>$p->Id,'CertTestId'=>$p->CertTestId,'Parameter'=>$p->Parameter,'IsMajor'=>$p->IsMajor,
											 'Min'=>$p->Min,'Max'=>$p->Max,'Description'=>$p->param->Description,'ParamId'=>$p->ParamId);
											}
										 }
										 
										 $tobs=array();
										
										 foreach($ctt->certtobbasics as $t)
										 {
											 $observations=array();
											 foreach($certtparams as $o)
											 {
												  $cobe=Certtobservations::model()->find(array('condition'=>'TObbasicId=:id AND CertTparamId=:eleid',
										 'params'=>array(':id'=>$t->Id,':eleid'=>$o->Id),));
										 
										 if(!empty($cobe))
										 {
										 $p=$cobe->certTparam;
												 if($p->Parameter==='PS' || $p->Parameter==='UTS' || $p->Parameter==='EL'|| $p->Parameter==='RA' || $p->Parameter==='R' )
											{
												
												 $observations[]=(object)array('Value'=>$cobe->Value,'Parameter'=>$cobe->certTparam->Parameter);
											}
										 }
										 
										 
											 }
											 $test=Tests::model()->findByPk($t->TestId);
											$tobs[]=(object)array('Id'=>$t->Id,'HeatNo'=>$t->HeatNo,'ShowInCert'=>$t->ShowInCert,'TestId'=>$t->TestId,'TestName'=>$test->TestName,'SeqNo'=>$t->SeqNo,'LabNo'=>$t->LabNo,'BatchCode'=>$t->BatchCode,'Observations'=>$observations,'Remark'=>$t->Remark);	 
											 
										 }
										
									
										
										
										
									}
						
						
								$cti=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="impact"',
										 'params'=>array(':id'=>$c->Id),));								
									
									if(!empty($cti))
									{
																				
										$ireference=(object)array('RefId'=>$cti->RefId,'References'=>$cti->References,'RefExtra'=>$cti->RefExtra,'Ref'=>$cti->Ref,'Remark'=>$cti->Remark);
																													
										$certiparams=Certiparams::model()->find(array('condition'=>'CertTestId=:id',
										 'params'=>array(':id'=>$cti->Id),));
										
										 
										 $iobs=array();
										
										 foreach($cti->certiobservations as $i)
										 {
											 $test=Tests::model()->findByPk($i->TestId);
											$iobs[]=(object)array('Id'=>$i->Id,'HeatNo'=>$i->HeatNo,'BatchCode'=>$i->BatchCode,'LabNo'=>$i->LabNo,'ShowInCert'=>$i->ShowInCert,'TestId'=>$i->TestId,'TestName'=>$test->TestName,'SeqNo'=>$i->SeqNo,
											'Value1'=>$i->Value1,'Value2'=>$i->Value2,'Value3'=>$i->Value3,'Avg'=>$i->Avg,'Remark'=>$i->Remark);	 
											 
										 }
										
										
									}
						
							$cth=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="hardness"',
										 'params'=>array(':id'=>$c->Id),));								
									
									if(!empty($cth))
									{
																				
										$hreference=(object)array('RefId'=>$cth->RefId,'References'=>$cth->References,'RefExtra'=>$cth->RefExtra,'Ref'=>$cth->Ref,'Remark'=>$cth->Remark);
																													
										$certhparams=Certhparams::model()->find(array('condition'=>'CertTestId=:id',
										 'params'=>array(':id'=>$cth->Id),));
										
										 
										 $hobs=array();
										
										 foreach($cth->certhobbasics as $h)
										 {
											 $observations=array();
											
												 $observations=Certhobservations::model()->findAll(array('condition'=>'HObbasicId=:id',
										 'params'=>array(':id'=>$h->Id),));
												 
											 $test=Tests::model()->findByPk($h->TestId);
											$hobs[]=(object)array('Id'=>$h->Id,'HeatNo'=>$h->HeatNo,'LabNo'=>$h->LabNo,'BatchCode'=>$h->BatchCode,'ShowInCert'=>$h->ShowInCert,'TestId'=>$h->TestId,'TestName'=>$test->TestName,'SeqNo'=>$h->SeqNo,'obs'=>$observations,'Remark'=>$h->Remark);	 
											 
										 }
										
									
										
										
										
									}
									
									
									$ctp=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="proofload"',
										 'params'=>array(':id'=>$c->Id),));								
									
									if(!empty($ctp))
									{
																				
										$preference=(object)array('RefId'=>$ctp->RefId,'References'=>$ctp->References,'RefExtra'=>$ctp->RefExtra,'Ref'=>$ctp->Ref,'Remark'=>$ctp->Remark);
																													
										$certpparams=Certiparams::model()->find(array('condition'=>'CertTestId=:id',
										 'params'=>array(':id'=>$ctp->Id),));
										
										 
										 $pobs=array();
										
										 foreach($ctp->certpobservations as $i)
										 {
											 $test=Tests::model()->findByPk($i->TestId);
											$pobs[]=(object)array('Id'=>$i->Id,'HeatNo'=>$i->HeatNo,'BatchCode'=>$i->BatchCode,'LabNo'=>$i->LabNo,'ShowInCert'=>$i->ShowInCert,'TestId'=>$i->TestId,'TestName'=>$test->TestName,'SeqNo'=>$i->SeqNo,
											'Value1'=>$i->Value1,'Remark'=>$i->Remark);	 
											 
										 }
										
										
									}
									
									$cttq=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="tension"',
										 'params'=>array(':id'=>$c->Id),));								
									
									if(!empty($cttq))
									{
																				
										$tqreference=(object)array('RefId'=>$cttq->RefId,'References'=>$cttq->References,'RefExtra'=>$cttq->RefExtra,'Ref'=>$cttq->Ref,'Remark'=>$cttq->Remark);
																													
										$certtqparams=Certiparams::model()->find(array('condition'=>'CertTestId=:id',
										 'params'=>array(':id'=>$cttq->Id),));
										
										 
										 $tqobs=array();
										
										 foreach($cttq->certtqobservations as $i)
										 {
											 $test=Tests::model()->findByPk($i->TestId);
											$tqobs[]=(object)array('Id'=>$i->Id,'HeatNo'=>$i->HeatNo,'BatchCode'=>$i->BatchCode,'LabNo'=>$i->LabNo,'ShowInCert'=>$i->ShowInCert,'TestId'=>$i->TestId,'SeqNo'=>$i->SeqNo,
											'Torque'=>$i->Torque,'Force'=>$i->Force,'Coff_Friction'=>$i->Coff_Friction,'Remark'=>$i->Remark,'TestName'=>$test->TestName);	 
											 
										 }
										
										
									}
									
									$ctw=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="wedge"',
										 'params'=>array(':id'=>$c->Id),));								
									
									if(!empty($ctw))
									{
																				
										$wreference=(object)array('RefId'=>$ctw->RefId,'References'=>$ctw->References,'RefExtra'=>$ctw->RefExtra,'Ref'=>$ctw->Ref,'Remark'=>$ctw->Remark);
																													
										// $certpparams=Certiparams::model()->find(array('condition'=>'CertTestId=:id',
										 // 'params'=>array(':id'=>$ctp->Id),));
										
										 
										 $wobs=array();
										
										 foreach($ctw->certwobservations as $i)
										 {
											$test=Tests::model()->findByPk($i->TestId); 
											$wobs[]=(object)array('Id'=>$i->Id,'HeatNo'=>$i->HeatNo,'BatchCode'=>$i->BatchCode,'LabNo'=>$i->LabNo,'ShowInCert'=>$i->ShowInCert,'TestId'=>$i->TestId,'SeqNo'=>$i->SeqNo,
											'RequiredTS'=>$i->RequiredTS,'ObservedTS'=>$i->ObservedTS,'Remark'=>$i->Remark,'TestName'=>$test->TestName);	 
											 
										 }
										
										
									}
						
						
					}
				
										
						/**-----------------All Test-----------------**/
							
							$tensile=(object)array('Section'=>"Tensile",'Certtest'=>$treference,'Certtparams'=>$certtparams,'Tobbasic'=>$tobs);
							$impact=(object)array('Section'=>"Impact",'Certtest'=>$ireference,'Certiparams'=>$certiparams,'Observations'=>$iobs);	
							$hardness=(object)array('Section'=>"Hardness",'Certtest'=>$hreference,'Certhparams'=>$certhparams,'Hobbasic'=>$hobs);	
							$proof=(object)array('Section'=>"Proofload",'Certtest'=>$preference,'Certpparams'=>$certpparams,'Observations'=>$pobs);	
							$tension=(object)array('Section'=>"Tension",'Certtest'=>$tqreference,'Certtqparams'=>$certtqparams,'Observations'=>$tqobs);	
							$wedge=(object)array('Section'=>"Wedge",'Certtest'=>$wreference,'Certwparams'=>$certwparams,'Observations'=>$wobs);	
							
							
							$certsections=(object)array('Chemical'=>$chemicals,'Tensile'=>$tensile,'Impact'=>$impact,'Hardness'=>$hardness,'Proofload'=>$proof,'Tension'=>$tension,'Wedge'=>$wedge);
							
						$customers=Customerinfo::model()->findAll();
					
					$rirs=Receiptir::model()->findAll();
					$allrirs=array();
					foreach($rirs as $r)
					{
						
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
						
						$allrirs[]=(object)array('Id'=>$r->Id,'RirNo'=>$r->RirNo,'Supplier'=>$r->Supplier,
						'PartName'=>$r->PartName,'MaterialCondition'=>$r->MaterialCondition,'MdsNo'=>$r->MdsNo,'TdsNo'=>$r->TdsNo,
						'GrinNo'=>$r->GrinNo,'Quantity'=>$r->Quantity,'BatchNo'=>$r->BatchNo,'BatchCode'=>$r->BatchCode,'Mds'=>$mds,'Tds'=>$tds,'NoType'=>$r->NoType,
						'HeatNo'=>$r->HeatNo,'RefPurchaseOrder'=>$r->RefPurchaseOrder,'MaterialGrade'=>$r->MaterialGrade,'LabNo'=>$r->LabNo);
					}
					
					$extsections=Certsections::model()->findAll();
					$allextsections=array();
					foreach($extsections as $s)
					{
						$allextsections[]=(object)array('Id'=>$s->Id,'Section'=>$s->Section,'Selected'=>"false",'KeyWord'=>$s->KeyWord);
					}
					
					
						$nmreference="";
					$nmobs=array();
					$ndreference="";
					$ndobs=array();
					$odreference="";
					$odobs=array();
					$mpireference="";
					$mpiobs=array();
					$mereference="";
					$meobs=array();
					$hextreference="";
					$hextobs=(object)array('Type'=>"attach");;
					$streference="";
					$stobs=array();
					$stdelreference="";
					$stdelobs=(object)array('Type'=>"attach");
					$stdacroreference="";
					$stdacroobs=(object)array();
					$sthotreference="";
					$sthotobs=(object)array();
					$stpreference="";
					$stpobs=(object)array();
					
						if(!empty($c))
						{
							
								$ctnm=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="NM"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctnm))
								{
									$nmreference=(object)array('RefId'=>$ctnm->RefId,'References'=>$ctnm->References,'RefExtra'=>$ctnm->RefExtra,'Ref'=>$ctnm->Ref,'Remark'=>$ctnm->Remark);
									$nmobs=$ctnm->certnonmetallics;
									$allextsections[0]->Selected="true";
								}
								
								$ctme=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="ME"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctme))
								{
									$mereference=(object)array('RefId'=>$ctme->RefId,'References'=>$ctme->References,'RefExtra'=>$ctme->RefExtra,'Ref'=>$ctme->Ref,'Remark'=>$ctme->Remark);
									$meobs=$ctme->certmicroexaminations;
									$allextsections[1]->Selected="true";
								}
								
								$ctmpi=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="MPI"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctmpi))
								{
									$mpireference=(object)array('RefId'=>$ctmpi->RefId,'References'=>$ctmpi->References,'RefExtra'=>$ctmpi->RefExtra,'Ref'=>$ctmpi->Ref,'Remark'=>$ctmpi->Remark);
									$mpiobs=$ctmpi->certmpidetails;
									$allextsections[2]->Selected="true";
								}
								
								$cthext=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="H"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($cthext))
								{
									$obs=(object)array();
									$hextreference=(object)array('RefId'=>$cthext->RefId,'References'=>$cthext->References,'RefExtra'=>$cthext->RefExtra,'Ref'=>$cthext->Ref,'Remark'=>$cthext->Remark);
									if(empty($cthext->certexthardnesses))
									{
										$obs=(object)array('Type'=>"nonattach");
									}
									else
									{
										$obs=$cthext->certexthardnesses[0];
									}
									
									$hextobs=$obs;
									$allextsections[3]->Selected="true";
								}
								
								
								$ctnd=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="ND"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctnd))
								{
									$ndreference=(object)array('RefId'=>$ctnd->RefId,'References'=>$ctnd->References,'RefExtra'=>$ctnd->RefExtra,'Ref'=>$ctnd->Ref,'Remark'=>$ctnd->Remark);
									$ndobs=$ctnd->certnondestructives;
									$allextsections[4]->Selected="true";
								}
								
								$ctst=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="ST"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctst))
								{
									$streference=(object)array('RefId'=>$ctst->RefId,'References'=>$ctst->References,'RefExtra'=>$ctst->RefExtra,'Ref'=>$ctst->Ref,'Remark'=>$ctst->Remark);
									$stobs=$ctst->certsurfacetreats;
									$allextsections[5]->Selected="true";
								}
								
								$ctstd=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="STDELTA"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctstd))
								{
									$obs=(object)array();
									$stdelreference=(object)array('RefId'=>$ctstd->RefId,'References'=>$ctstd->References,'RefExtra'=>$ctstd->RefExtra,'Ref'=>$ctstd->Ref,'Remark'=>$ctstd->Remark);
									if(empty($ctstd->certsurfacetreatdeltas))
									{
										$obs=(object)array('Type'=>"nonattach",'CaotMin'=>"",'CaotMax'=>"",'SaltMin'=>"",'SaltMax'=>"",'AdhesionReq'=>"",
										'VisualReq'=>"",'Observations'=>"",'CaotRemark'=>"",'SaltRemark'=>"",'AdhesionRemark'=>"",'VisualRemark'=>"");
									}
									else
									{
										$obs=$ctstd->certsurfacetreatdeltas[0];
									}
									
									$stdelobs=$obs;
									$allextsections[6]->Selected="true";
								}
								
								$ctstda=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="STDA"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctstda))
								{
									$stdacroreference=(object)array('RefId'=>$ctstda->RefId,'References'=>$ctstda->References,'RefExtra'=>$ctstda->RefExtra,'Ref'=>$ctstda->Ref,'Remark'=>$ctstda->Remark);
									$obs=(object)array();
									if(empty($ctstda->certsurfacetreatdacros))
									{
										$obs=(object)array();
									}
									else
									{
										$obs=$ctstda->certsurfacetreatdacros[0];
									}
									$stdacroobs=$obs;
									$allextsections[7]->Selected="true";
								}
								
								$ctsthot=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="STHOT"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctsthot))
								{
									$sthotreference=(object)array('RefId'=>$ctsthot->RefId,'References'=>$ctsthot->References,'RefExtra'=>$ctsthot->RefExtra,'Ref'=>$ctsthot->Ref,'Remark'=>$ctsthot->Remark);
									$obs=(object)array();
									if(empty($ctsthot->certsurfacetreathots))
									{
										$obs=(object)array();
									}
									else
									{
										$obs=$ctsthot->certsurfacetreathots[0];
									}
									$sthotobs=$obs;
									$allextsections[8]->Selected="true";
								}
								
								$ctstp=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="STP"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctstp))
								{
									$stpreference=(object)array('RefId'=>$ctstp->RefId,'References'=>$ctstp->References,'RefExtra'=>$ctstp->RefExtra,'Ref'=>$ctstp->Ref,'Remark'=>$ctstp->Remark);
									$obs=(object)array();
									if(empty($ctstp->certsurfacetreatplats))
									{
										$obs=(object)array();
									}
									else
									{
										$obs=$ctstp->certsurfacetreatplats[0];
									}
									
									$stpobs=$obs;
									$allextsections[9]->Selected="true";
								}
														
								
								
								
								$ctod=Certtest::model()->find(array('condition'=>'CertBasicId=:id AND Section="OD"',
									'params'=>array(':id'=>$c->Id),));
																	
								if(!empty($ctod))
								{
									$odreference=(object)array('RefId'=>$ctod->RefId,'References'=>$ctod->References,'RefExtra'=>$ctod->RefExtra,'Ref'=>$ctod->Ref,'Remark'=>$ctod->Remark);
									$odobs=$ctod->certotherdetails;
									$allextsections[10]->Selected="true";
								}
								
								
								
						}
					
						$nonmetallic=(object)array('Section'=>"NM",'Certtest'=>$nmreference,'Observations'=>$nmobs);
						$microex=(object)array('Section'=>"ME",'Certtest'=>$mereference,'Observations'=>$meobs);
						$mpidet=(object)array('Section'=>"MPI",'Certtest'=>$mpireference,'Observations'=>$mpiobs);
						$nondes=(object)array('Section'=>"ND",'Certtest'=>$ndreference,'Observations'=>$ndobs);
						$hard=(object)array('Section'=>"H",'Certtest'=>$hextreference,'Observations'=>$hextobs);
					$surtreat=(object)array('Section'=>"ST",'Certtest'=>$streference,'Observations'=>$stobs);
					$surtreatde=(object)array('Section'=>"STDELTA",'Certtest'=>$stdelreference,'Observations'=>$stdelobs);
					$surtreatda=(object)array('Section'=>"STDA",'Certtest'=>$stdacroreference,'Observations'=>$stdacroobs);
					$surtreathot=(object)array('Section'=>"STHOT",'Certtest'=>$sthotreference,'Observations'=>$sthotobs);
					$surtreatplat=(object)array('Section'=>"STP",'Certtest'=>$stpreference,'Observations'=>$stpobs);
					$othdet=(object)array('Section'=>"OD",'Certtest'=>$odreference,'Observations'=>$odobs);
					
						$certextsections=(object)array('NM'=>$nonmetallic,'ND'=>$nondes,'OD'=>$othdet,'MPI'=>$mpidet,'H'=>$hard,'ME'=>$microex,
						'ST'=>$surtreat,'STDELTA'=>$surtreatde,'STDA'=>$surtreatda,'STHOT'=>$surtreathot,'STP'=>$surtreatplat);
						
					
					$data=(object)array('customers'=>$customers,'allrirs'=>$allrirs,'basic'=>$certbasic,'sections'=>$certsections,'rirs'=>$exrirs,
					'allextsections'=>$allextsections,'extsections'=>$certextsections);
					$this->_sendResponse(200, CJSON::encode($data));
				break;
				
					
				default:
					$this->_sendResponse(501, sprintf(
						'Mode <b> view</b> is not implemented for model <b> %s</b>',
						$_GET['model']) );
					Yii::app()->end();
			}
			// Did we find the requested model? If not, raise an error
			if(is_null($model))
				$this->_sendResponse(404, 'No Item found with id '.$_GET['id']);
			else
				$this->_sendResponse(200, CJSON::encode($model));
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