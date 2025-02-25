<?php

class CertapiController extends Controller
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
 
 
 public function getcertui($param)
 {
	 $basic=$param;
	
	 	$appset=Settings::model()->find();
						$pdfmsg ='	
			   <table class="table table-sm table-bordered" cellspacing="0" cellpadding="2" border="1" style="font-size:11px;border:1px solid #ddd;color:#000 !important;"  >
					<thead class="" >
                        <tr style="background:#fff;">
						<td colspan="2" style="background:#fff;" >
						  <a target="_blank" href="#">
                            <img src="'.Yii::app()->params['base-url'].'img/blacklogo.png" style="height:40px;">
                            </a>							    
						</td>
						<td colspan="8"  style="background:#fff;font-size:12px;"  class="company-details" >
						'.$appset->CompanyName.'<br>'.$appset->CompanyAddress.'
						</td>
						<td colspan="2" style="padding:2px;font-size:10px;text-align:right;">
						 <img src="'.$basic->QRImg.'" style="height:70px;">						
						</td> 
						</tr>
						</thead>';
						
				$pdfmsg .='<tbody>
				<tr>
					<td colspan="6"  style="padding:2px;border:1px solid #ddd;"><b>Customer: '.$basic->Customer.'</b></td>
					<td colspan="3"  style="padding:2px;border:1px solid #ddd;"><b>T.C. No.: '.$basic->TCNo.'</b></td>
					<td colspan="3" style="padding:2px;border:1px solid #ddd;"><b>Date: '.$basic->CertDate.'</b></td>
					</tr>
				<tr>
				<td   colspan="6"  style="padding:2px;border:1px solid #ddd;"><b>Sample Name</b>'.$basic->SampleName.'</td>
				<td  colspan="3"  style="padding:2px;border:1px solid #ddd;"><b>Sample Condition</b> '.$basic->SampleCondition.'</td>
				<td colspan="3"  style="padding:2px;border:1px solid #ddd;"><b>No. Of Samples</b> '.$basic->NoOfSamples.'</td>	
				</tr>';
				
				if($basic->certimg)
		{
			$pdfmsg .='	<tr ng-if="cert.basic.certimg">
				<td colspan="12">
				<img ng-src="{{cert.basic.certimg.url}}" style="height:100px;width:auto;">
				</td>
			</tr>';
			
		}
				
				
				
				$pdfmsg .='<tr>
			<td colspan="12" class="table-responsive" style="padding:0px;"> ';
				foreach($basic->certests as $chem)
			{
				$pdfmsg .='
			<table class="table table-bordered table-condensed" cellspacing="0" cellpadding="2" border="1" style="margin-bottom:5px;">
				
					<tr class="text-left active">
						<td >
						'.$chem->TestName.'	Standard Specification : '.$chem->Standard.'		
						</td>
					</tr>';
					
					$pdfmsg .='<tr class="text-left active">
					<td>';
					
					
				$pdfmsg .='	<table class="table table-bordered table-sm" cellspacing="0" cellpadding="2" border="1" style="margin-bottom:5px;font-size:10px;color:#000;">
					<tbody>
					<tr>	
					<td  colspan="3">Parameter</td>';
					
					foreach($chem->obbasic->conditions as $v)
					{
						$pdfmsg .='<td><label style="font-size:10px;">'.$v->Param.'  </label></td>';
					
					}
				
				$pdfmsg .='	</tr>';
					
					
			$pdfmsg .='<tr><td>#</td>
					<td>LabNo</td>
					<td>Min-Max</td>';
					foreach($chem->obbasic->conditions as $v)
					{					
						$pdfmsg .='<td>'.$v->Min.'-'.$v->Max.'</td>';
					}
				
				$pdfmsg .='	</tr>';
				
				
				
					$i=0;
					foreach($chem->obbasic->observations as $o)
					{	
						$i++;
						$pdfmsg .='<tr class="align-middle">';
						
						$pdfmsg .='<td>'.$i++.'</td>
						<td>'.$o->BatchCode.'/'.$o->LabNo.'</td>
						<td>'.$o->TestNo.'-'.$o->ObsNo.'</td>';
						
						foreach($o->ObsValues as $j)
						{
							$pdfmsg .='<td >'.$j->Value.'</td>';
						}
						
						$pdfmsg .='</tr>';
					}
					
					
					
				$pdfmsg .='</tbody></table>';
				
				$pdfmsg .='</td></tr>';
					
					
					
					
					$pdfmsg .='</table>';
			}
				
				$pdfmsg .='	</td>
			</tr>';
			
			$pdfmsg .='
			
<tr>
<td colspan="12" style="padding:4px;"><b>The results of tests are satisfactory.</b></td>
</tr>';
				
		
	$pdfmsg .='
			


	<tr style="border:none ;" class="mybg">
<td colspan="5"  style="padding:4px;border:none ;"> 
<span style="padding-left:20px;">Prepared By,</span>
<br>

			<img  src="'.$basic->PreparedSign.'"   style="height:40px;" alt=""><br>
			<label class="figure-caption text-center" >'.$basic->PreparedSign.' </label>



<br>
<span style="padding-left:20px;">Jr.Engineer - QA</span>
</td>
<td colspan="4"  style="padding:4px;border:none ;"> 
<span style="padding-left:20px;">Checked By,</span>
<br>
<figure class="figure" style="display:inherit;">
			<img  src="'.$basic->CheckedSign.'"  class="figure-img img-fluid rounded" style="height:40px;padding-left:20px;" alt=""/><br>
			<figcaption class="figure-caption text-center" >'.$basic->CheckedBy.'</figcaption></figure>

<br>
<span style="padding-left:20px;">Asst.Manager-QA</span>
</td>
<td colspan="3"  style="padding:4px;border:none ;">
<span style="padding-left:20px;"> Approved By,</span>
 <br>
<figure class="figure" style="display:inherit;">
			<img  src="'.$basic->ApprovedSign.'"  class="figure-img img-fluid rounded" style="height:40px;padding-left:20px;" alt=""/><br>
			<figcaption class="figure-caption text-center" >'.$basic->ApprovedBy.'</figcaption></figure>
<br>
<span style="padding-left:20px;">Manager Engineering</span>
</td>
</tr>	';	
		
		
$pdfmsg .='	</tbody>
			</table>';
				
return $pdfmsg;				
	 
 }
 
 

    // Actions
    public function actionList()
    {
				
			// Get the respective model instance
			switch($_GET['model'])
			{
				case 'certformats':
				
					try{
						$cfs=Certformats::model()->findAll();
						
						$allcfs=[];
						
						$allele=Testobsparams::model()->with('test')->findAll(['condition'=>'test.TUID="CHEM"']);
						
						foreach($cfs as $c)
						{
							// $rircfsec=Certformsec::model()->with('fS')->findAll(array('condition'=>'CFId= :cfid AND fs.rir ="rir"',
							// 'params'=>array(':cfid'=>$c->Id,)));
							
							
							
							// $nonrircfsec=Certformsec::model()->with('fS')->findAll(array('condition'=>'CFId= :cfid AND fs.rir !="rir"',
							// 'params'=>array(':cfid'=>$c->Id,)));
							
							
							$rircfsec=Certformsec::model()->with('fS')->findAll(array('condition'=>'CFId= :cfid ',
							'params'=>array(':cfid'=>$c->Id,)));
							
							
							$rircfd=array();
							$certformdet=[];
							$cfds=[];
							$detcfds=[];
							foreach($rircfsec as $r)
							{
								if($r->FSID==='1')
								{
									$parameters=[];
									foreach($r->certformdetails as $e)
									{
										if($e->PType==='Chemical')
										{
											$desc="";
											$ele=Testobsparams::model()->find(array('condition'=>'PSymbol =:sym',
												'params'=>array(':sym'=>$e->Param)));
											if(!empty($ele))
											{
												$desc=$ele->Parameter;
												$e->PID=$ele->PID;
												$e->save(false);
											}												
												
											
										}
										$parameters[]=(object)['Id'=>$e->Id,'PID'=>$e->PID,'PSymbol'=>$ele->PSymbol,'Parameter'=>$ele->Parameter,
										'IsMajor'=>$e->IsMajor,'Min'=>$e->Min,'Max'=>$e->Max,
										'PType'=>$e->PType,'CFSID'=>$e->CFSID,'Desc'=>$desc];
									}
									$certformdet[]=(object)['Id'=>$r->Id,'CFId'=>$r->CFId,'FSID'=>$r->FSID,'Keyword'=>$r->fS->Keyword,
									'Parameters'=>$parameters,'Section'=>$r->fS->Section];
									$cfds[]=$r->FSID;
									$detcfds[]=$r->fS->Section;
								}
								else if($r->FSID==='2')
								{
									$parameters=[];
									foreach($r->certformdetails as $e)
									{
										if($e->PType==='Tensile' )
										{
											$desc="";
											$ele=Tensileparams::model()->find(array('condition'=>'Parameter =:sym',
												'params'=>array(':sym'=>$e->Param)));
											if(!empty($ele))
											{
												$desc=$ele->Description;
											}												
												
											
										}
											if($e->PType==='Hardness' )
										{
											
											$desc="Hardness ".$e->Param;
										}
										
										if( $e->PType==='Impact' )
										{
											$desc="Impact";
											
										}
										
										if( $e->PType==='Proofload' )
										{
											$desc="Proofload";
											
										}
										
										if( $e->PType==='Torque' )
										{
											$desc="Torque Tension";
											
										}
										
										if( $e->PType==='Wedge' )
										{
											$desc="Wedge";
											
										}
										$parameters[]=(object)['Id'=>$e->Id,'PId'=>$e->Param,'IsMajor'=>$e->IsMajor,'Min'=>$e->Min,'Max'=>$e->Max,
										'PType'=>$e->PType,'CFSID'=>$e->CFSID,'Desc'=>$desc];
									}
									$certformdet[]=(object)['Id'=>$r->Id,'CFId'=>$r->CFId,'FSID'=>$r->FSID,'Keyword'=>$r->fS->Keyword,
									'Parameters'=>$parameters,'Section'=>$r->fS->Section];
									$cfds[]=$r->FSID;
									$detcfds[]=$r->fS->Section;
								}
								else
								{
									$certformdet[]=(object)['Id'=>$r->Id,'CFId'=>$r->CFId,'FSID'=>$r->FSID,'Keyword'=>$r->fS->Keyword,
									'Section'=>$r->fS->Section];
									$cfds[]=$r->FSID;
									$detcfds[]=$r->fS->Section;
								}
							}
							
							$allcfs[]=(object)array('Id'=>$c->Id,'FormatNo'=>$c->FormatNo,'detcfds'=>$detcfds,'CFDs'=>$cfds,'CertFormDetails'=>$certformdet);
							//certformsecs
							
						}
						
						
						foreach($allele as $e)
						{
							$elements[]=(object)array('PId'=>$e->Id,'PSymbol'=>$e->PSymbol,'TUID'=>"CHEM",'Parameter'=>$e->Parameter,'SpecMin'=>"",'SpecMax'=>"");
						}
						
						$tensile=Testobsparams::model()->with('test')->findAll(['condition'=>'test.TUID="TENSILE"']);;
						
						foreach($tensile as $t)
						{
							$mech[]=(object)['PId'=>$t->Id,'PSymbol'=>$t->PSymbol,'PType'=>"Tensile",'Parameter'=>$t->Parameter,];	
						}
						$mechparam=[];
						
						
						
						// $mech[]=(object)['PId'=>"I",'PType'=>"Impact",'Desc'=>"Impact",'Min'=>"",'Max'=>""];
						// $mech[]=(object)['PId'=>'HV','PType'=>"Hardness",'Desc'=>"Hardness Vickers(HV)",'Min'=>"",'Max'=>""];
						// $mech[]=(object)['PId'=>'HRC','PType'=>"Hardness",'Desc'=>"Hardness Rockwell(HRC)",'Min'=>"",'Max'=>""];
						// $mech[]=(object)['PId'=>'HBW','PType'=>"Hardness",'Desc'=>"Hardness Bridell (HBW)",'Min'=>"",'Max'=>""];
						// $mech[]=(object)['PId'=>"KN",'PType'=>"Proofload",'Desc'=>"Proofload",'Min'=>"",'Max'=>""];
						// $mech[]=(object)['PId'=>"TT",'PType'=>"Torque",'Desc'=>"Torque Tension",'Min'=>"",'Max'=>""];
						// $mech[]=(object)['PId'=>"W",'PType'=>"Wedge",'Desc'=>"Wedge",];
						
						
						$cc=(object)['parameters'=>$elements,];
						
						//$forsec=Formatsections::model()->findAll(array('condition'=>'rir="rir"'));
						
						$forsec=Formatsections::model()->findAll();
						foreach($forsec as $f)
						{
							$params=Testobsparams::model()->with('test')->findAll(['condition'=>'IsSpec=1 AND test.TUID=:tuid','params'=>[':tuid'=>$f->Keyword]]);
							
								$allformsec[]=(object)array('FSID'=>$f->Id,'Test'=>$f->Test,'Keyword'=>$f->Keyword,'Section'=>$f->Section,'Parameters'=>$params);
							
							
						}
						
						$nonforsec=[];//Formatsections::model()->findAll(array('condition'=>'rir !="rir"'));
						
						
						$result=(object)['allcfs'=>$allcfs,'allformsec'=>$allformsec,];
						$this->_sendResponse(200, CJSON::encode($result));
					}
					catch(Exception $e)
					{
						$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
					break;
					
					
				case 'cert'	:
					$totalitems=Certbasic::model()->count();
					//$totalitems=count($allcerts);
					
					if(isset($_GET['pl']))
				{
				$allcerts=Certbasic::model()->findAll(array(
    'order' => 'Id desc',
    'limit' => $_GET['pl'],
    'offset' => ($_GET['pn']-1)*$_GET['pl']
));
				}
					
					$models=array();
					foreach($allcerts as $c)
					{
						
						$certbasic="";
						$chemicals=array();
						$chobs=array();
						$certelements=array();
						$creference="";
						
					
						
						$preparedby="";
							$preparedsign="";
							if(!empty($c->PreparedBy))
							{
								$preparedsign=empty($c->preparedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$c->preparedBy->usersignuploads[0]->name);
								$preparedby=$c->preparedBy->FName." ".$c->preparedBy->LName;	
							}		
							
							$approvedby="";
							$approvedsign="";
							$qrimg="";
							
							if(!empty($c->ApprovedBy))
							{
							$approvedsign=empty($c->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$c->approvedBy->usersignuploads[0]->name);
							$approvedby=$c->approvedBy->FName." ".$c->approvedBy->LName;
							
								$qrimg=Yii::app()->params['base-url']."pdf/certificate/".$c->TCNo.".png";							
							}				

						$checkedby="";
							$checkedsign="";
							if(!empty($c->CheckedBy))
							{
							$checkedsign=empty($c->checkedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$c->checkedBy->usersignuploads[0]->name);
							$checkedby=$c->checkedBy->FName." ".$c->checkedBy->LName;	
							}		
							
						
						$certests=[];
							foreach($c->certsections as $cs)
							{
								foreach($cs->certtests as $ct)
								{
									$rt=$ct->rT;
									$obs=[];
									foreach($ct->certtestobs as $ob)
										{
											
											
										}
										$chemtests[]=(object)['SSID'=>$ct->SSID,'TMID'=>$ct->TMID,'BatchCode'=>$rt->rIR->BatchCode,
										'LabNo'=>$ct->LabNo,'HeatNo'=>$rt->rIR->HeatNo,'RTID'=>$ct->RTID,'TUID'=>$ct->TUID,'ShowInCert'=>$ct->ShowInCert,
										'Obs'=>$obs];
								}
								
								foreach($cs->certtestspecs as $cs)
								{
									
									
								}
								
							}
							
							
							// $chemicals=(object)array('Section'=>"Chemical",'Ref'=>$chemrefs);
						// $mechanicals=(object)array('Section'=>"Mechanical",'Ref'=>"",'Mechtests'=>"",'MechSpec'=>"");
							
							
							$certimg=Certattachments::model()->find(array('condition'=>'certid=:cert','params'=>array(':cert'=>$c->getPrimaryKey())));
							
						$laburls=[];
						$labnames=[];
						$basic=(object)array('Id'=>$c->Id,'CertDate'=>$c->CertDate,'obbasic'=>"",'certimg'=>$certimg,
						'TCNo'=>$c->TCNo,'CheckedSign'=>$checkedsign,'CheckedBy'=>$checkedby,'QRImg'=>$qrimg,'IsLab'=>1,'laburls'=>$laburls,
						'Labs'=>$labnames,'SampleName'=>$c->PartDescription,	'Customer'=>$c->cust->Name,
						'CustEmail'=>null,'certests'=>$certests,'TCFormat'=>$c->TCFormat,'basicextra'=>empty($c->certbasicextras)?null:$c->certbasicextras[0],
						'PreparedBy'=>$preparedby,'PreparedSign'=>$preparedsign,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby);
						
						
							
							
				
							
						
						
						$models[]=(object)array('basic'=>$basic);
					}
					//$this->_sendResponse(200, CJSON::encode($models));
					$data=(object)array('allcerts'=>$models,'totalitems'=>$totalitems);
						$this->_sendResponse(200, CJSON::encode($data));
				break;
				
				
	case 'certpredata':
	
					$allcusts=Customerinfo::model()->findAll();
					$formats=[];
					$formats[]=(object)['Id'=>"3.1",'TCFormat'=>" 3.1 CERTIFICATE AS PER EN 10204 "];
					$formats[]=(object)['Id'=>"3.2",'TCFormat'=>" 3.2 CERTIFICATE AS PER EN 10204 "];
					
						$extsections=Certextsections::model()->findAll();
					$allextsections=array();
					foreach($extsections as $s)
					{
						$keyword=$s->KeyWord;
						 switch ($keyword) {
        case 'NM':
					$nmobservations[]=(object)['HeatNo' => "", 'Parameter' => "", 'Required' => "", 'Observation' => "", 'Remark' => ""];
					$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$nmobservations,'delobservations'=>[]];
            break;

        case 'ME':
            $meobservations[]=(object)['BatchCode' => "", 'Parameter' => "", 'Required' => "", 'Observation' => "", 'Remark' => ""];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$meobservations,'delobservations'=>[]];
            break;

        case 'MPI':
			$mpiobs[]=(object)['Parameter' => "", 'Required' => "", 'Observation' => "", 'Remark' => ""];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$mpiobs,'delobservations'=>[]];
            break;

        case 'H':
			$hobs[]=(object)['Parameter' => "", 'BatchCode'=>"",'Requirement' => "", 'Observation' => "", 'Remark' => ""];;
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$hobs,'delobservations'=>[]];
            break;

        case 'ND':
			$ndobs[]=(object)['Parameter' => "", 'Required' => "", 'Observation' => "", 'Remark' => ""];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$ndobs,'delobservations'=>[]];
            break;

        case 'ST':
           $stobs[]=(object)['Painting' => "", 'Coat' => "", 'Remark' => ""];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$stobs,'delobservations'=>[]];
            break;

        case 'STDELTA':
            $deltaobs=(object)['Type'=>"nonattach"];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$deltaobs,'delobservations'=>[]];
            break;

        case 'STDELTA2':
            $delta2obs=(object)['Type'=>"nonattach"];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$delta2obs,'delobservations'=>[]];
            break;

        case 'STDA':
           $daobs=(object)[];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$daobs,'delobservations'=>[]];
            break;

        case 'STHOT':
            $hotobs=(object)[];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$hotobs,'delobservations'=>[]];
            break;

        case 'STP':
            $stpobs=(object)[];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$stpobs,'delobservations'=>[]];
            break;

        case 'OD':
            $odobs[]=(object)[ 'Parameter' => "", 'Required' => "", 'Observation' => "", 'Remark' => ""];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$odobs,'delobservations'=>[]];
            break;
			
		case 'TZC':
			 $tzcobs[]=(object)[  'Observation' => "", ];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$tzcobs,'delobservations'=>[]];
            break;
			
			case 'ICR':
			 $icrobs[]=(object)[  'Observation' => "", ];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$icrobs,'delobservations'=>[]];
            break;	
			
			
    }
						
						
						
						
						
						$allextsections[]=(object)array('Id'=>$s->Id,'Section'=>$s->Section,'Selected'=>0,'KeyWord'=>$s->KeyWord ,'Test'=>$test);
					}
					
					
					$certformats=Certformats::model()->findAll(['select'=>'Id,FormatNo','order'=>'Id Desc']);
					
					$certtypes=['Normal','Bushing'];
					
					$data=(object)array('allcusts'=>$allcusts,'allextsections'=>$allextsections,'allformats'=>$formats,'allcertformats'=>$certformats,'allcerttypes'=>$certtypes);
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
				$models=array();
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
				
				case 'viewcertformats':
				
					try{
						$c=Certformats::model()->findByPk($_GET['id']);
						
					
						
						$allele=Elements::model()->findAll();
						
						
							
							
							
							$rircfsec=Certformsec::model()->with('fS')->findAll(array('condition'=>'CFId= :cfid ',
							'params'=>array(':cfid'=>$c->Id,)));
							
							
							$rircfd=array();
							$certformdet=[];
							$cfds=[];
							$detcfds=[];
							foreach($rircfsec as $r)
							{
								if($r->FSID==='1')
								{
									$parameters=[];
									foreach($r->certformdetails as $e)
									{
										if($e->PType==='Chemical')
										{
											$desc="";
											$ele=Elements::model()->find(array('condition'=>'Symbol =:sym',
												'params'=>array(':sym'=>$e->Param)));
											if(!empty($ele))
											{
												$desc=$ele->Element;
											}												
												
											
										}
										$parameters[]=(object)['Id'=>$e->Id,'PId'=>$e->Param,'IsMajor'=>$e->IsMajor,
										'PType'=>$e->PType,'CFSID'=>$e->CFSID,'Desc'=>$desc];
									}
									$certformdet[]=(object)['Id'=>$r->Id,'CFId'=>$r->CFId,'FSID'=>$r->FSID,'Keyword'=>$r->fS->Keyword,
									'Parameters'=>$parameters,'Section'=>$r->fS->Section];
									$cfds[]=$r->FSID;
									$detcfds[]=$r->fS->Section;
								}
								else if($r->FSID==='2')
								{
									$parameters=[];
									foreach($r->certformdetails as $e)
									{
										if($e->PType==='Tensile' )
										{
											$desc="";
											$ele=Tensileparams::model()->find(array('condition'=>'Parameter =:sym',
												'params'=>array(':sym'=>$e->Param)));
											if(!empty($ele))
											{
												$desc=$ele->Description;
											}												
												
											
										}
											if($e->PType==='Hardness' )
										{
											
											$desc="Hardness ".$e->Param;
										}
										
										if( $e->PType==='Impact' )
										{
											$desc="Impact";
											
										}
										$parameters[]=(object)['Id'=>$e->Id,'PId'=>$e->Param,'IsMajor'=>$e->IsMajor,
										'PType'=>$e->PType,'CFSID'=>$e->CFSID,'Desc'=>$desc];
									}
									$certformdet[]=(object)['Id'=>$r->Id,'CFId'=>$r->CFId,'FSID'=>$r->FSID,'Keyword'=>$r->fS->Keyword,
									'Parameters'=>$parameters,'Section'=>$r->fS->Section];
									$cfds[]=$r->FSID;
									$detcfds[]=$r->fS->Section;
								}
								else
								{
									$certformdet[]=(object)['Id'=>$r->Id,'CFId'=>$r->CFId,'FSID'=>$r->FSID,'Keyword'=>$r->fS->Keyword,
									'Section'=>$r->fS->Section];
									$cfds[]=$r->FSID;
									$detcfds[]=$r->fS->Section;
								}
							}
							
							$allcfs=(object)array('Id'=>$c->Id,'FormatNo'=>$c->FormatNo,'detcfds'=>$detcfds,'CFDs'=>$cfds,'CertFormDetails'=>$certformdet);
							//certformsecs
							
						
						
						
						foreach($allele as $e)
						{
							$elements[]=(object)array('PId'=>$e->Symbol,'PType'=>"Chemical",'Desc'=>$e->Element);
						}
						$tensile=Tensileparams::model()->findAll();
						
						foreach($tensile as $t)
						{
							$mech[]=(object)['PId'=>$t->Parameter,'PType'=>"Tensile",'Desc'=>$t->Description,];	
						}
						$mechparam=[];
						
						
						
						$mech[]=(object)['PId'=>"I",'PType'=>"Impact",'Desc'=>"Impact",];
						$mech[]=(object)['PId'=>'HV','PType'=>"Hardness",'Desc'=>"Hardness Vickers(HV)"];
						$mech[]=(object)['PId'=>'HRC','PType'=>"Hardness",'Desc'=>"Hardness Rockwell(HRC)"];
						$mech[]=(object)['PId'=>'HBW','PType'=>"Hardness",'Desc'=>"Hardness Bridell (HBW)"];
						
						$cc=(object)['parameters'=>$elements,];
						
						//$forsec=Formatsections::model()->findAll(array('condition'=>'rir="rir"'));
						
						$forsec=Formatsections::model()->findAll();
						foreach($forsec as $f)
						{
							if($f->Keyword ==='CC')
							{
							$allformsec[]=(object)array('FSID'=>$f->Id,'Keyword'=>'CC','Section'=>$f->Section,'Parameters'=>$elements);	
							}
							else if($f->Keyword ==='MECH')
							{
								$allformsec[]=(object)array('FSID'=>$f->Id,'Keyword'=>'MECH','Section'=>$f->Section,'Parameters'=>$mech);
							}
							else 
							{
								$allformsec[]=(object)array('FSID'=>$f->Id,'Keyword'=>$f->Keyword,'Section'=>$f->Section);
							}
							
						}
						
						
						
						$result=(object)['FormatNo'=>$c->FormatNo,'allformsec'=>$allformsec,];
						$this->_sendResponse(200, CJSON::encode($allcfs));
					}
					catch(Exception $e)
					{
						$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
					break;				
				
				case 'certeditpredata':
				
					$certbasic=Certbasic::model()->findByPk($_GET['id']);
					
					$basicextra=Certbasicextra::model()->find(['condition'=>'CBID=:cbid','params'=>[':cbid'=>$certbasic->Id]]);
					
					
						$labnos=[];
						$cbrirs=json_decode($certbasic->Rirs);
						
						foreach($cbrirs as $d)
						{
							$labnos[]=$d->LabNo;
						}
					
					
					$criteria=new CDbCriteria;		
						$criteria->addInCondition('LabNo',$labnos);	

						
						$rirs=Receiptir::model()->findAll($criteria);
						
						
						function findWhere($array, $criteria) {
    foreach ($array as $item) {
        $matches = true;
        foreach ($criteria as $key => $value) {
            if (!isset($item->$key) || $item->$key !== $value) {
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



						
						$chemspec=[];
						$chemrefs=[];	

							$mechspec=[];
						$mechrefs=[];							
							foreach( $rirs as $r)
							{
								foreach($r->rirtestdetails as $d)
								{
									$incertest=Certtest::model()->with('cS')->find(['condition'=>'t.RTID=:rtid AND cS.CBID=:cbid','params'=>[':rtid'=>$d->Id,':cbid'=>$certbasic->Id]]);
									if(!empty($incertest))
									{
									
									if($d->Status==='complete')
									{
										switch($d->TUID)
										{
								
								
											case 'CHEM':
											$std="";
											$chemtests=[];
											if($d->test->testfeatures[0]->IsStd)
														{	
															
															$sub=null;
															if(isset($d->SSID))
															{
																$sub=Substandards::model()->findByPk($d->SSID);	
																
																$type="";//$sub->Type;
																$substandard="";															
																$substandard=$sub->Grade." ".$sub->ExtraInfo;															
																$std=$sub->std->Standard." ".$substandard;
															}		
														}
														
														
														$obs=array();											
												
												//---Observatory Parameters-----//								
																							
																	if(!empty($d->rirtestobs))
																	{														
																		
																			foreach($d->rirtestobs as $e)
																			{	
																			
																			$obsshowincert=0;
																			$certtestspecs=Certtestspecs::model()->with('cS')->find(['condition'=>'PID=:pid AND cS.CBID=:cbid AND t.ShowInCert=1',
																	'params'=>[':pid'=>$e->tP->Id,':cbid'=>$certbasic->Id]]);
																			
																			$specmin=$e->SpecMin;
																	$specmax=$e->SpecMax;
																if(!empty($certtestspecs))
																{
																	$obsshowincert=1;
																	$specmin=$certtestspecs->SpecMin;
																	$specmax=$certtestspecs->SpecMax;
																}
																			
																				$chemspec[$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'Parameter'=>$e->tP->Parameter,'PSymbol'=>$e->tP->PSymbol,
																				'SpecMin'=>$specmin,'SpecMax'=>$specmax,'ShowInCert'=>$obsshowincert];
																				$obs[$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'Value'=>$e->rirtestobsvalues[0]->Value];
																			}
																	}	
															
																 
																$certtest=Certtest::model()->with('cS')->find(['condition'=>'RTID=:rtid AND cS.CBID=:cbid AND t.ShowInCert=1',
																	'params'=>[':rtid'=>$d->Id,':cbid'=>$certbasic->Id]]);
																	if(!empty($certtest))
																	{
																if($d->Id===$certtest->RTID)
																{
																	
																$chemtests[]=(object)['SSID'=>$d->SSID,'TMID'=>$d->TMID,'BatchCode'=>$r->BatchCode,'BatchNo'=>$r->BatchNo,'Id'=>$certtest->Id,'CSID'=>$certtest->CSID,
																'LabNo'=>$certtest->LabNo,'HeatNo'=>$certtest->HeatNo,'TestName'=>$d->TestName,'TestInfo'=>$certtest->TestInfo,'Remark'=>$certtest->Remark,
																'ShowInCert'=>$certtest->ShowInCert,
																'RTID'=>$certtest->RTID,'Id'=>$certtest->Id,'TUID'=>$d->TUID,'Obs'=>$obs];
																
																}
																	}
													
											
											if(in_array($d->SSID, array_column($chemrefs, 'SSID')))
											{
												$criteria = ['SSID' => $d->SSID];
												$oldref = findWhere($chemrefs, $criteria);
												$oldref->chemtests[]=empty($chemtests)?null:$chemtests[0];
													//$chemrefs[]=(object)['SSID'=>$d->SSID,'Std'=>$std,'chemtests'=>$chemtests,'ChemSpec'=>$chemspec,];
											}
											else
											{
													$certsec=Certsections::model()->find(['condition'=>'SSID=:ssid AND CBID=:cbid AND Section="Chemical"',
																	'params'=>[':ssid'=>$d->SSID,':cbid'=>$certbasic->Id]]);
																	if(empty($certsec))
																	{
																		$chemrefs[]=(object)['SSID'=>$d->SSID,'TMID'=>$d->TMID,'Std'=>$std,'SName'=>"",'Reference'=>"",'chemtests'=>$chemtests,'ChemSpec'=>$chemspec,];
																	}
																	else
																	{
																		$chemrefs[]=(object)['SSID'=>$d->SSID,'TMID'=>$d->TMID,'Std'=>$std,'SName'=>$certsec->SName,'Reference'=>$certsec->Reference,'chemtests'=>$chemtests,'ChemSpec'=>$chemspec,];
																	}
																		
																	
												
											}
												
														

																	
																	
														
											break;
											
											
								case 'RBHARD':
								case 'RCHARD':
								case 'MVHARD':
								case 'VHARD':
								case 'BHARD':								
								case 'IMP':								
								case 'PROOF':
								case 'TENSILE':
											$std="";
											$mechtests=[];
											$mechspec=[];
											if($d->test->testfeatures[0]->IsStd)
														{	
															
															$sub=null;
															if(isset($d->SSID))
															{
																$sub=Substandards::model()->findByPk($d->SSID);	
																
																$type="";//$sub->Type;
																$substandard="";															
																$substandard=$sub->Grade." ".$sub->ExtraInfo;															
																$std=$sub->std->Standard." ".$substandard;
															}		
														}
														
														
																								
												
												//---Observatory Parameters-----//								
																							
																	
																
																	
																	if(in_array($r->LabNo, array_column($mechtests, 'LabNo')))
																	{
																		$criteria = ['LabNo' => $r->LabNo];
																		$oldref = findWhere($mechtests, $criteria);
																		$aobs=$oldref->Obs;
																		foreach($d->rirtestobs as $e)
																			{
																					if($e->tP->IsSpec)
																					{	
																						switch($d->TUID)
																						{
																							case 'IMP':	
																							$gettemp=Rirtestbasic::model()->with('tBP')->find(['condition'=>'tBP.PSymbol="Temp" AND RTID=:rtid',
																										'params'=>[':rtid'=>$d->Id]]);	
																							
																								// $avg=$avg/$tobparam->MR;
																								// $tobparam=Testobsparams::model()->findbyPk($e->TPID); 
																								$avg=0;
																								$impval=[];
																								foreach($e->rirtestobsvalues as $v)
																								{
																									$impval[]=$v->Value;
																									$avg=$avg+$v->Value;
																								}
																								
																								$avg=ROUND(($avg/count($impval)),2);
																								
																								$impdval=implode(",",$impval);
																								$impdval=$impdval.", Avg:".$avg;
																								
																								//$aobs[$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'Value'=>$impdval,];
																								$aobs[$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'Value'=>$impdval,'Temp'=>empty($gettemp)?null:$gettemp->BValue,'TempUnit'=>$gettemp->tBP->PUnit];
																						break;
																							
																						case 'RBHARD':
																						case 'RCHARD':
																						case 'MVHARD':
																						case 'VHARD':
																						case 'BHARD':
																								$aobs[$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'Value'=>json_decode($e->rirtestobsvalues[0]->Value)];
																								break;
																													
																						case 'PROOF':
																						
																						case 'TENSILE':
																						default:
																						$aobs[$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'Value'=>$e->rirtestobsvalues[0]->Value];
																						break;
																						
																						}
																				
																					}
																			}
																	}
																	else
																	{
																		$obs=array();	
																		if(!empty($d->rirtestobs))
																			{		
																				
																					foreach($d->rirtestobs as $e)
																					{		if($e->tP->IsSpec)
																					{		

																				$obsshowincert=0;
																			$certtestspecs=Certtestspecs::model()->with('cS')->find(['condition'=>'PID=:pid AND cS.CBID=:cbid AND t.ShowInCert=1',
																	'params'=>[':pid'=>$e->tP->Id,':cbid'=>$certbasic->Id]]);
																				if(!empty($certtestspecs))
																				{
																					$obsshowincert=1;
																					$mechspec[$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'Parameter'=>$e->tP->Parameter,'PSymbol'=>$e->tP->PSymbol,
																						'SpecMin'=>$certtestspecs->SpecMin,'SpecMax'=>$certtestspecs->SpecMax,'Temp'=>$certtestspecs->Temp,'ShowInCert'=>$obsshowincert,'TUID'=>$d->TUID];
																				}
																				else
																				{
																					$mechspec[$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'Parameter'=>$e->tP->Parameter,'PSymbol'=>$e->tP->PSymbol,
																						'SpecMin'=>$e->SpecMin,'SpecMax'=>$e->SpecMax,'ShowInCert'=>$obsshowincert,'TUID'=>$d->TUID];
																				}
																
															
																					
																						// $mechspec[$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'Parameter'=>$e->tP->Parameter,'PSymbol'=>$e->tP->PSymbol,
																						// 'SpecMin'=>$e->SpecMin,'SpecMax'=>$e->SpecMax,'ShowInCert'=>$obsshowincert,'TUID'=>$d->TUID];
																						
																					
																						//$obs[$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'Value'=>$e->rirtestobsvalues];
																						switch($d->TUID)
																						{
																							case 'IMP':	
																							$gettemp=Rirtestbasic::model()->with('tBP')->find(['condition'=>'tBP.PSymbol="Temp" AND RTID=:rtid',
																										'params'=>[':rtid'=>$d->Id]]);
																										
																							$cto=Certtestobs::model()->find(['condition'=>'CTID=:ctid AND PID=:pid','params'=>[':ctid'=>$incertest->Id,':pid'=>$e->tP->Id]]);			
																									$avg=0;
																									$impval=[];
																								foreach($e->rirtestobsvalues as $v)
																								{
																									$impval[]=$v->Value;
																									$avg=$avg+$v->Value;
																								}
																								
																								//$avg=$avg/count($impval);
																								$avg=ROUND(($avg/count($impval)),2);
																								$impdval=implode(",",$impval);
																								$impdval=$impdval.", Avg:".$avg;
																								
																								$obs[$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'Value'=>$impdval,'Temp'=>empty($gettemp)?null:$gettemp->BValue,'TempUnit'=>$gettemp->tBP->PUnit];
																								
																								
																						break;
																						
																						case 'RBHARD':
																						case 'RCHARD':
																						case 'MVHARD':
																						case 'VHARD':
																						case 'BHARD':	
																								$obs[$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'Value'=>json_decode($e->rirtestobsvalues[0]->Value)];
																								break;
																												
																						case 'PROOF':
																						case 'TENSILE':
																						
																						default:
																						$obs[$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'Value'=>$e->rirtestobsvalues[0]->Value];
																						break;
																						
																						}
																					}
																					}
																			}
																			

$isshowincert=0;
						$certtest=Certtest::model()->with('cS')->find(['condition'=>'RTID=:rtid AND cS.CBID=:cbid AND t.ShowInCert=1',
																							'params'=>[':rtid'=>$d->Id,':cbid'=>$certbasic->Id]]);
																if(!empty($certtest))
																{
																	$isshowincert=1;
																																		
																	
																		$mechtests[]=(object)['SSID'=>$d->SSID,'TMID'=>$d->TMID,'BatchCode'=>$r->BatchCode,'LabNo'=>$r->LabNo,'Id'=>$certtest->Id,'CSID'=>$certtest->CSID,
																		'HeatNo'=>$certtest->HeatNo,'TestName'=>$d->TestName,'Remark'=>empty($certtest)?null:$certtest->Remark,'BatchNo'=>$r->BatchNo,'ShowInCert'=>$isshowincert,
																			'TestInfo'=>empty($certtest)?null:$certtest->TestInfo,'RTID'=>$certtest->RTID,'TUID'=>$d->TUID,'Obs'=>$obs];
																}
																	
																	}
																	
																
													
													
												if (array_key_exists($d->TUID,$mechrefs))
												{													
											
													if(in_array($d->SSID, array_column($mechrefs[$d->TUID], 'SSID')))
													{
														$criteria = ['SSID' => $d->SSID,];
														$oldref = findWhere($mechrefs[$d->TUID], $criteria);
														$oldref->mechtests[]=empty($mechtests)?null:$mechtests[0];
															
													}
													else
													{
															$certsec=Certsections::model()->find(['condition'=>'SSID=:ssid AND CBID=:cbid AND Section="Mechanical"',
																	'params'=>[':ssid'=>$d->SSID,':cbid'=>$certbasic->Id]]);
																	
													$mechrefs[$d->TUID][]=(object)['Test'=>$d->TUID,'TestName'=>$d->test->TestName,'TMID'=>$d->TMID,'SSID'=>$d->SSID,'Std'=>$std,'SName'=>empty($certsec)?null:$certsec->SName,
													'Reference'=>empty($certsec)?null:$certsec->Reference,	
													'mechtests'=>$mechtests,'MechSpec'=>$mechspec,];
														
													}
												}
												else
												{
													$certsec=Certsections::model()->find(['condition'=>'SSID=:ssid AND CBID=:cbid AND Section="Mechanical"',
																	'params'=>[':ssid'=>$d->SSID,':cbid'=>$certbasic->Id]]);
													$mechrefs[$d->TUID][]=(object)['Test'=>$d->TUID,'TestName'=>$d->test->TestName,'TMID'=>$d->TMID,'SSID'=>$d->SSID,'Std'=>$std,'SName'=>empty($certsec)?null:$certsec->SName,
													'Reference'=>empty($certsec)?null:$certsec->Reference,													
													'mechtests'=>$mechtests,'MechSpec'=>$mechspec,];
												}
														

																	
																	
														
											break;
								
								default:
									
									
									
									$obobs=[];
										
									
										
										
										$testresults=empty($d->rirtestobs)?0:count($d->rirtestobs[0]->rirtestobsvalues);
										$observations=[];
										for($k=0; $k<$testresults;$k++)
										{
											$obsvalue=[];
											foreach($d->rirtestobs as $e)
											{
												$obsvalue[]=(object)array('Id'=>$e->Id,
													'PId'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'PDType'=>$e->tP->PDType,
													'TestId'=>$d->TestId,'ShowInCert'=>0,
													'Symbol'=>$e->tP->PSymbol,'Param'=>$e->tP->Parameter,
													'Value'=>$e->rirtestobsvalues[$k]->Value);
											
											}
											
											$observations[$k]=(object)array(
											'BatchCode'=>$d->rIR->BatchCode,
											'LabNo'=>$d->rIR->LabNo,'ShowInCert'=>0,
											'TestNo'=>$d->TestNo,
											'ObsNo'=>$k+1,
											'ObsValues'=>$obsvalue);
										}
									
										
													
												
												$cond=array();
								
									foreach($d->rirtestobs as $e)
									{
									
							$cond[]=(object)array('Id'=>$e->Id,'PId'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ShowInCert'=>1,
							'Permissible'=>"",'Symbol'=>$e->tP->PSymbol,'Param'=>$e->tP->Parameter,'Min'=>$e->SpecMin,'Max'=>$e->SpecMax,'RirTestId'=>$e->RTID);
											
									}	

$pos=(object)array('observations'=>$observations,'conditions'=>$cond);									
												
												
								break;
								
									}
									
									}
									
									}
								}
							}
						
				

						
						$externals=[];
						
						$externalcerts=Certsections::model()->findAll(['condition'=>'Section="External" AND CBID=:cbid','params'=>[':cbid'=>$certbasic->Id]]);
					

				
					
					$keywordToModel = [
    'NM' => 'Certnonmetallic',
    'ME' => 'Certmicroexamination',
    'MPI' => 'Certmpidetails',
    'H' => 'Certexthardness',
    'ND' => 'Certnondestructive',
    'ST' => 'Certsurfacetreat',
    'STDELTA' => 'Certsurfacetreatdelta',
    'STDELTA2' => 'Certsurfacetreatdelta',
    'STDA' => 'Certsurfacetreatdacro',
    'STHOT' => 'Certsurfacetreathot',
    'STP' => 'Certsurfacetreatplat',
    'OD' => 'Certotherdetails'
];
$extests=[];
foreach ($externalcerts as $ex) {
    $keyword = $ex->Keyword;
	
	switch($keyword)
				{
					case 'STDELTA':
					case 'STHOT':
					 // Check if the keyword exists in the array mapping
					if (isset($keywordToModel[$keyword])) {
						$modelClass = $keywordToModel[$keyword];
						$extobs = $modelClass::model()->find([
							'condition' => 'CSID=:csid',
							'params' => [':csid' => $ex->Id]
						]);
					} else {
						$extobs = (object)[]; // Default if no model is found for the keyword
					}
					break;
					
					default:
					 // Check if the keyword exists in the array mapping
					if (isset($keywordToModel[$keyword])) {
						$modelClass = $keywordToModel[$keyword];
						$extobs = $modelClass::model()->findAll([
							'condition' => 'CSID=:csid',
							'params' => [':csid' => $ex->Id]
						]);
					} else {
						$extobs = []; // Default if no model is found for the keyword
					}
					break;
					
				}

   

    // Construct the object to be stored
    $test = (object)[
        'SName' => $ex->SName,
        'Reference' => $ex->Reference,
        'Ref' => $ex->Ref,
        'Extra' => $ex->Extra,
        'Remark' => $ex->Remark,
        'Observations' => $extobs,
        'delobservations' => []
    ];

    // Add or update the entry in the $externals array
    $externals[$ex->Keyword] = (object) [
        'Id' => $ex->Id,
        'Section' => $ex->Section,
        'Selected' => 1,
        'KeyWord' => $ex->Keyword,
        'Test' => $test
    ];
	
	
	$extests[]=$ex->Keyword;
}

						
							
						$chemicals=(object)array('Section'=>"Chemical",'Ref'=>$chemrefs);
						$mechanicals=(object)array('Section'=>"Mechanical",'Ref'=>$mechrefs);
						
						
					$externals['ExtTests']=$extests;
						
							$sections=(object)array('chemicals'=>$chemicals,'mechanicals'=>$mechanicals,'externals'=>$externals);
							
							$allrirs=[];
							
							
							$basic=(object)['Id'=>$certbasic->Id,'CertDate'=>$certbasic->CertDate,'CustId'=>$certbasic->CustId];
							
							$certbasic->Rirs=json_decode($certbasic->Rirs);
							
							$cert=(object)['basic'=>$certbasic,'basicextra'=>$basicextra,'sections'=>$sections];
											
							$certdata=(object)array('allrirs'=>$allrirs,'basicinfo'=>$certbasic,);
							
							$allcusts=Customerinfo::model()->findAll();
					$formats=[];
					$formats[]=(object)['Id'=>"3.1",'TCFormat'=>" 3.1 CERTIFICATE AS PER EN 10204 "];
					$formats[]=(object)['Id'=>"3.2",'TCFormat'=>" 3.2 CERTIFICATE AS PER EN 10204 "];
					
							$extsections=Certextsections::model()->findAll();
					$allextsections=array();
					foreach($extsections as $s)
					{
						$keyword=$s->KeyWord;
						 switch ($keyword) {
        case 'NM':
					$nmobservations[]=(object)['HeatNo' => "", 'Parameter' => "", 'Required' => "", 'Observation' => "", 'Remark' => ""];
					$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$nmobservations,'delobservations'=>[]];
            break;

        case 'ME':
            $meobservations[]=(object)['BatchCode' => "", 'Parameter' => "", 'Required' => "", 'Observation' => "", 'Remark' => ""];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$meobservations,'delobservations'=>[]];
            break;

        case 'MPI':
			$mpiobs[]=(object)['Parameter' => "", 'Required' => "", 'Observation' => "", 'Remark' => ""];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$mpiobs,'delobservations'=>[]];
            break;

        case 'H':
			$hobs[]=(object)['Parameter' => "", 'BatchCode'=>"",'Requirement' => "", 'Observation' => "", 'Remark' => ""];;
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$hobs,'delobservations'=>[]];
            break;

        case 'ND':
			$ndobs[]=(object)['Parameter' => "", 'Required' => "", 'Observation' => "", 'Remark' => ""];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$ndobs,'delobservations'=>[]];
            break;

        case 'ST':
           $stobs[]=(object)['Painting' => "", 'Coat' => "", 'Remark' => ""];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$stobs,'delobservations'=>[]];
            break;

        case 'STDELTA':
            $deltaobs=(object)['Type'=>"nonattach"];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$deltaobs,'delobservations'=>[]];
            break;

        case 'STDELTA2':
            $delta2obs=(object)['Type'=>"nonattach"];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$delta2obs,'delobservations'=>[]];
            break;

        case 'STDA':
           $daobs=(object)[];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$daobs,'delobservations'=>[]];
            break;

        case 'STHOT':
            $hotobs=(object)[];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$hotobs,'delobservations'=>[]];
            break;

        case 'STP':
            $stpobs=(object)[];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$stpobs,'delobservations'=>[]];
            break;

        case 'OD':
            $odobs[]=(object)[ 'Parameter' => "", 'Required' => "", 'Observation' => "", 'Remark' => ""];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$odobs,'delobservations'=>[]];
            break;
			
		case 'TZC':
			 $tzcobs[]=(object)[  'Observation' => "", ];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$tzcobs,'delobservations'=>[]];
            break;
			
			case 'ICR':
			 $icrobs[]=(object)[  'Observation' => "", ];
			$test=(object)['Reference'=>"",'Ref'=>"",'Extra'=>"",'Remark'=>"",'Observations'=>$icrobs,'delobservations'=>[]];
            break;	
			
			
    }
						
						
						
						
						
						$allextsections[]=(object)array('Id'=>$s->Id,'Section'=>$s->Section,'Selected'=>0,'KeyWord'=>$s->KeyWord ,'Test'=>$test);
					}
					
					
					$certformats=Certformats::model()->findAll(['select'=>'Id,FormatNo','order'=>'Id Desc']);
					
					
						
							 $result=(object)array('cert'=>$cert,
							 'allcusts'=>$allcusts,'certdata'=>$certdata,
							 'allrirs'=>$allrirs,'allcusts'=>$allcusts,'allextsections'=>$allextsections,'allformats'=>$formats,'allcertformats'=>$certformats);
							 
							 
$this->_sendResponse(200, CJSON::encode($result));							
				
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
			case 'addcertformat': $model=new Certformats;  break;
			case 'getrirs':
				case 'loadcertsects':
					$model = new Receiptir;                    
					break;
				
				case 'certbasicadd':
					$model = new Certbasic;                    
					break;
				
					case 'searchcert':$model =new Certbasic;break;
						   
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
			   case 'getrirs':
			      case 'addcertformat':break;
			  
			   case 'loadcertsects': break;
			   			     case 'searchcert':break;
			   case 'certbasicadd':
					
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
			   
			   case 'getrirs':
				try{
					
					$rirs=Receiptir::model()->findAll(['order'=>'Id Desc']);//array('condition'=>'CustomerId=:custid','params'=>array(':custid'=>$model->Id)));
					$allrirs=array();
					foreach($rirs as $r)
					{					
						$allrirs[]=(object)array('Id'=>$r->Id,'SampleName'=>$r->SampleName,'BatchCode'=>$r->BatchCode,'BatchNo'=>$r->BatchNo,'LabNo'=>$r->LabNo);
					}
					
					
					$data=(object)array('allrirs'=>$allrirs,);
					$this->_sendResponse(200, CJSON::encode($data));
				
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
				
			     case 'addcertformat':
				 $transaction=$model->dbConnection->beginTransaction();
					try{
						
						
						$model->FormatNo=$data['FormatNo'];
						$model->CreatedOn=date('Y-m-d H:i:s');
						$model->save(false);
						
						$cfid=$model->getPrimaryKey();
						
						
						foreach($data['CertFormDetails'] as $d)
						{
							
							$cfs=new Certformsec;
							$cfs->CFId=$cfid;
							$cfs->FSID=$d['FSID'];
							$cfs->save(false);
							
							if(isset($d['Parameters']) )
							{
							foreach($d['Parameters'] as $p)
							{
								
								
								$cfd=new CertFormDetails;
								$cfd->CFSID=$cfs->getPrimaryKey();
								$cfd->Param=$p['PId'];
								$cfd->PType=$p['PType'];
								$cfd->IsMajor=isset($p['IsMajor'])?$p['IsMajor']:0;
								$cfd->Min=$p['Min'];
								$cfd->Max=$p['Max'];
								$cfd->save(false);
								
								
							}
							}
						}
						
						$transaction->commit();
						
						$this->_sendResponse(200, CJSON::encode("saved successfully"));
						
					}
					catch(Exception $e)
					{
						$transaction->rollback();
						$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
				 
				 
				 break;
				 
			
			   case 'searchcert':
			   
			   
					$allcerts=Certbasic::model()->findAll();
					$totalitems=count($allcerts);
					
				
						$allcerts=Certbasic::model()->with('customer')->findAll(array(
						
			'order' => 't.Id desc',
		   'limit' => 30,		   
			'condition'=>'customer.Name LIKE :cn OR TCNo LIKE :tcno',
			'params'=>array(':cn'=>'%'.$data['text'].'%',':tcno'=>'%'.$data['text'].'%')
		));
						
					
					
					$totalitems=Certbasic::model()->count();
					//$totalitems=count($allcerts);
					
				
					
					$models=array();
					foreach($allcerts as $c)
					{
						
						$certbasic="";
						$chemicals=array();
						$chobs=array();
						$certelements=array();
						$creference="";
						
					
						
						$preparedby="";
							$preparedsign="";
							if(!empty($c->PreparedBy))
							{
								$preparedsign=empty($c->preparedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$c->preparedBy->usersignuploads[0]->name);
								$preparedby=$c->preparedBy->FName." ".$c->preparedBy->LName;	
							}		
							
							$approvedby="";
							$approvedsign="";
							$qrimg="";
							if(!empty($c->ApprovedBy))
							{
							$approvedsign=empty($c->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$c->approvedBy->usersignuploads[0]->name);
							$approvedby=$c->approvedBy->FName." ".$c->approvedBy->LName;
$qrimg=Yii::app()->params['base-url']."pdf/certificate/".$c->TCNo.".png";							
							}				

						$checkedby="";
							$checkedsign="";
							if(!empty($c->CheckedBy))
							{
							$checkedsign=empty($c->checkedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$c->checkedBy->usersignuploads[0]->name);
							$checkedby=$c->checkedBy->FName." ".$c->checkedBy->LName;	
							}		
						
						$certests=[];
							foreach($c->certtests as $ct)
							{
								
								$rtest=Rirtestdetail::model()->findByPk($ct->RirTestId);
								$obbasic=(object)array();
									$cond=array();
									
									$certestobs=$ct->certtestobs(array('condition'=>'ShowInCert=1'));
								foreach($certestobs as $ob)
								{
									$param=Testobsparams::model()->findByPk($ob->PId);
									$testmethod="";
									if(!empty($param))
									{
									$cond[]=(object)array('CTId'=>$ob->CTId,'PId'=>$ob->PId,'Min'=>$ob->Min,'Max'=>$ob->Max,
									'TMId'=>$ob->TMId,'TestMethod'=>$testmethod,'ShowInCert'=>$ob->ShowInCert,
									'Param'=>$param->Parameter,'Symbol'=>$param->PSymbol);
									}
								}
								
								$testresults=empty($ct->certtestobs)?0:count($ct->certtestobs[0]->certtestobsvalues);
										$observations=[];
										for($k=0; $k<$testresults;$k++)
										{
											$obsvalue=[];
											$certestobs=$ct->certtestobs(array('ShowInCert=1'));
											foreach($certestobs as $e)
											{
												if($e->ShowInCert===1)
												{
												$obsvalue[]=(object)array('Id'=>$e->Id,
													'PId'=>$e->PId,'PUnit'=>$e->p->PUnit,'PDType'=>$e->p->PDType,
													'TestId'=>$ct->TestId,'ShowInCert'=>$e->ShowInCert,
													'PSym'=>$e->p->PSymbol,'TMId'=>$e->TMId,'Param'=>$e->p->Parameter,
													'Value'=>$e->certtestobsvalues[$k]->Value);
												}
											
											}
											if($ct->certtestobs[0]->certtestobsvalues[$k]->ShowInCert===1)
												{
											$observations[$k]=(object)array('TestNo'=>$ct->certtestobs[0]->certtestobsvalues[$k]->TestNo,
											'ObsNo'=>$ct->certtestobs[0]->certtestobsvalues[$k]->ObsNo,
											'BatchCode'=>$rtest->rIR->BatchCode,'LabNo'=>$rtest->rIR->LabNo,
											'ShowInCert'=>$ct->certtestobs[0]->certtestobsvalues[$k]->ShowInCert,'ObsValues'=>$obsvalue);
												}
										}
								
								
								$pos=(object)array('basic'=>$obbasic,'observations'=>$observations,'conditions'=>$cond);	
								
								
								$test=Tests::model()->find(array('condition'=>'Id =:key','params'=>array(':key'=>$ct->TestId)));
								$certests[]=(object)['TestId'=>$ct->TestId,'TestName'=>$test->TestName,'Standard'=>$ct->Standard,'ShowInCert'=>$ct->ShowInCert,'BatchCode'=>$rtest->rIR->BatchCode,'LabNo'=>$rtest->rIR->LabNo,
								'Remark'=>$ct->Remark,'obbasic'=>$pos];
							}
							
							
							
							
							
							$certimg=Certattachments::model()->find(array('condition'=>'certid=:cert','params'=>array(':cert'=>$c->getPrimaryKey())));
							
						
						$basic=(object)array('Id'=>$c->Id,'CertDate'=>$c->CertDate,'obbasic'=>$obbasic,'certimg'=>$certimg,
						'TCNo'=>$c->TCNo,'CheckedSign'=>$checkedsign,'CheckedBy'=>$checkedby,'QRImg'=>$qrimg,
						'Customer'=>$c->customer->Name,'CustEmail'=>$c->customer->Email,'certests'=>$certests,'TCFormat'=>$c->TCFormat,
						'PreparedBy'=>$preparedby,'PreparedSign'=>$preparedsign,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby);
						
						
							
							
				
							
						
						
						$models[]=(object)array('basic'=>$basic);
					}
					
					$result=(object)array('allcerts'=>$models,'totalitems'=>$totalitems);
						$this->_sendResponse(200, CJSON::encode($result));
					
				break;
			   
			   case 'certbasicadd':
			    $transaction=$model->dbConnection->beginTransaction();
				try
					{
						//$this->_sendResponse(401, CJSON::encode($data['sections']));
						$set=Settingslab::model()->findByPk("1");
						$allcerts=Certbasic::model()->findAll();
						$lastrecord = Certbasic::model()->find(array('order'=>'Id DESC'));
						
						$model->TCFormat=$data['basic']['TCFormat'];
						$model->CertType=$data['basic']['CertType'];
						$model->TCNo=$data['basic']['TCNo'];
						$model->TCRevNo=$data['basic']['TCRevNo'];
						$model->CertDate=date('Y-m-d',strtotime($data['basic']['CertDate']));
						$model->CustId=$data['basic']['CustId'];
											
						
						$model->PartDescription=$data['basic']['PartDescription'];
						$model->Rirs=json_encode($data['basic']['Rirs']);
						$model->LastModified=date('Y-m-d H:i:s');
						$model->CreationDate=date('Y-m-d H:i:s');
						$model->FormatNo=isset($data['basic']['FormatNo'])?$data['basic']['FormatNo']:null;
						$model->save(false);
						
						
						$cbx=new Certbasicextra;
						
			
foreach($data['basicextra'] as $var=>$value)
											  {
												// Does the model have this attribute? If not raise an error
												if($cbx->hasAttribute($var))
													$cbx->$var = $value;
											  }		
$cbx->CBID=$model->getPrimaryKey();
$cbx->save(false);
						
						
						
						
						$uir=Userapppermission::model()->find(array('condition'=>'UserId =:uid AND SectionId="9"',
							'params'=>array(':uid'=>$data['PreparedBy']),));
							if(!empty($uir))
							{
								if($uir->C)
								{
									$model->PreparedBy=$data['PreparedBy'];	
								}
							}		
						
						
						$model->save(false);
						$secs=$data['sections'];
						
						$chems=$secs['chemicals'];
						if(!empty($chems))
						{
							foreach($chems['Ref'] as $s)
							{
								if($model->CertType==='Normal' || $model->CertType==='Bushing')
								{
									$sec=Certsections::model()->find(['condition'=>'CBID=:cbid   AND Section="Chemical"',
									'params'=>[':cbid'=>$model->getPrimaryKey(),]]);
								}
								if($model->CertType==='Assembly')
								{
									$sec=Certsections::model()->find(['condition'=>'CBID=:cbid AND SSID=:ssid  AND Section="Chemical"',
									'params'=>[':cbid'=>$model->getPrimaryKey(),':ssid'=>$s['SSID']]]);
								}
									
								
									if(empty($sec))
									{
										$sec=new Certsections;
										$sec->CBID=$model->getPrimaryKey();
										$sec->SSID=$s['SSID'];
										$sec->Section="Chemical";
										$sec->Extra=isset($s['Extra'])?$s['Extra']:null;
										$sec->Reference=isset($s['Reference'])?$s['Reference']:null;
										$sec->SName=isset($s['SName'])?$s['SName']:null;
										$sec->save(false);	
									}
								
									
									
									
									foreach($s['chemtests'] as $t)
									{
										if(isset($t['ShowInCert']) && $t['ShowInCert'])
										{
										$ct=new Certtest;
										$ct->CSID=$sec->getPrimaryKey();
										$ct->LabNo=$t['LabNo'];
										$ct->HeatNo=$t['HeatNo'];
										$ct->TestName=$t['TestName'];
										$ct->TestInfo=isset($t['TestInfo'])?$t['TestInfo']:null;;
										$ct->RTID=$t['RTID'];
										$ct->SSID=$t['SSID'];
										$ct->TMID=isset($t['TMID'])?$t['TMID']:null;
										$ct->ShowInCert=isset($t['ShowInCert'])?$t['ShowInCert']:0;
										$ct->Reference=isset($t['Reference'])?$t['Reference']:null;
										$ct->Remark=isset($t['Remark'])?$t['Remark']:null;
										$ct->TUID=$t['TUID'];
										$ct->save(false);	
										
											foreach($t['Obs'] as $o)
											{
												
												$speccriteria = ['PID' => $o['PID'],'ShowInCert'=>1];
												$oldref = MyFunctions::findWhere($s['ChemSpec'], $speccriteria);
												
												if(!empty($oldref))
												{
												
												$to=new Certtestobs;
												$to->CTID=$ct->getPrimaryKey();
												$to->PID=$o['PID'];
												$to->ShowInCert=1;
												$to->Value=isset($o['Value'])?$o['Value']:null;
												$to->save(false);
												}
												
											}
										}											
										
									}
									
								//----test Specifications		
									foreach($s['ChemSpec'] as $o)
									{
										if(isset($o['ShowInCert']) && $o['ShowInCert'])
										{
										$to=new Certtestspecs;
										$to->CSID=$sec->getPrimaryKey();
										$to->TUID='CHEM';//$t['TUID'];
										$to->PID=$o['PID'];
										$to->ShowInCert=isset($o['ShowInCert'])?$o['ShowInCert']:0;
										$to->SpecMin=isset($o['SpecMin'])?$o['SpecMin']:null;
										$to->SpecMax=isset($o['SpecMax'])?$o['SpecMax']:null;
										$to->save(false);
										}
										
									}
								
							}
						}
						$mechs=$secs['mechanicals'];
						if(!empty($mechs))
						{
						foreach($mechs['Ref'] as $sr)
							{
							foreach($sr as $s)
							{
								if($model->CertType==='Normal' || $model->CertType==='Bushing')
								{
									$sec=Certsections::model()->find(['condition'=>'CBID=:cbid AND Section="Mechanical"',
									'params'=>[':cbid'=>$model->getPrimaryKey(),]]);
								}
								
								if($model->CertType==='Assembly')
								{
									$sec=Certsections::model()->find(['condition'=>'CBID=:cbid AND SSID=:ssid  AND Section="Mechanical"',
									'params'=>[':cbid'=>$model->getPrimaryKey(),':ssid'=>$s['SSID']]]);
								}
								
								
									if(empty($sec))
									{
									$sec=new Certsections;
									$sec->CBID=$model->getPrimaryKey();
									$sec->SSID=isset($s['SSID'])?$s['SSID']:null;
									$sec->Section="Mechanical";
									$sec->Extra=isset($s['Extra'])?$s['Extra']:null;
									$sec->Reference=isset($s['Reference'])?$s['Reference']:null;
									$sec->SName=isset($s['SName'])?$s['SName']:null;
									$sec->save(false);	
									}
									
									foreach($s['mechtests'] as $t)
									{
										if(isset($t['ShowInCert']) && $t['ShowInCert'])
										{
										$ct=new Certtest;
										$ct->CSID=$sec->getPrimaryKey();
										$ct->LabNo=$t['LabNo'];
										$ct->HeatNo=$t['HeatNo'];
										$ct->RTID=$t['RTID'];
										$ct->SSID=$t['SSID'];
										$ct->TMID=isset($t['TMID'])?$t['TMID']:null;
										$ct->TestInfo=isset($t['TestInfo'])?$t['TestInfo']:null;
										$ct->ShowInCert=isset($t['ShowInCert'])?$t['ShowInCert']:0;
										$ct->Reference=isset($t['Reference'])?$t['Reference']:null;
										$ct->Temp=isset($t['Temp'])?$t['Temp']:null;
										$ct->Remark=isset($t['Remark'])?$t['Remark']:null;
										$ct->TUID=$t['TUID'];
										$ct->save(false);	
										
											foreach($t['Obs'] as $o)
											{
												$speccriteria = ['PID' => $o['PID'],'ShowInCert'=>1];
												$oldref = MyFunctions::findWhere($s['MechSpec'], $speccriteria);
												
												if(!empty($oldref))
												{
												$to=new Certtestobs;
												$to->CTID=$ct->getPrimaryKey();
												$to->PID=$o['PID'];
												$to->ShowInCert=1;
												if (isset($o['Value'])) {
													if (is_array($o['Value'])) {
														$to->Value = json_encode($o['Value']);
													} else {
														$to->Value = $o['Value'];
													}
												} else {
													$to->Value = null; // Assign null if $o['Value'] is not set
												}
												
												
														$to->Temp = isset($o['Temp'])?$o['Temp']:null;
												
												$to->save(false);
												}
												
											}	

										}											
										
									}
									
								//----test Specifications		
									foreach($s['MechSpec'] as $o)
									{
										if(isset($o['ShowInCert']) && $o['ShowInCert'])
										{
										$to=new Certtestspecs;
										$to->CSID=$sec->getPrimaryKey();
										$to->TUID=$o['TUID'];
										$to->PID=$o['PID'];
										$to->ShowInCert=isset($o['ShowInCert'])?$o['ShowInCert']:0;
										$to->SpecMin=isset($o['SpecMin'])?$o['SpecMin']:null;
										$to->SpecMax=isset($o['SpecMax'])?$o['SpecMax']:null;
										$to->Temp=isset($o['Temp'])?$o['Temp']:null;
										$to->TempUnit=isset($o['TempUnit'])?$o['TempUnit']:null;
										$to->save(false);
										}
										
									}
								
							}
							}
						
						}
						$externals=$secs['externals'];
						if(array_key_exists('NM', $externals))
						{
						if($externals['NM']['Selected'])
						{
							 $nonmetallic=$externals['NM']['Test'];							 
									                                
									if(!empty($nonmetallic['Observations']))
										{
																					
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'NM'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
										
                                
										if(!empty($nonmetallic))
										{
											foreach($nonmetallic as $var=>$value)
											  {
												// Does the model have this attribute? If not raise an error
												if($ct->hasAttribute($var))
													$ct->$var = $value;
											  }		
										}									  
								
										$ct->Section="External";
										$ct->Keyword="NM";
										$ct->CBID=$model->getPrimaryKey();
										$ct->SName=isset($nonmetallic['SName'])?$nonmetallic['SName']:null;
										$ct->save(false);
                                
										if(!empty($nonmetallic['delobservations']))
										{
											foreach($nonmetallic['delobservations'] as $db)
											{
												$dob=Certnonmetallic::model()->findByPk($db['Id']);
												if(!empty($dob))
												{
													$dob->delete();
												}
											}
										}
											
											$i=1;
											foreach($nonmetallic['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certnonmetallic;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certnonmetallic::model()->findByPk($cb['Id']);
												}
												$cob->SeqNo=$i;
												$cob->HeatNo=empty($cb['HeatNo'])?"":$cb['HeatNo'];
												$cob->Parameter=$cb['Parameter'];
												$cob->Required=$cb['Required'];
												$cob->Observation=$cb['Observation'];
												$cob->Remark=$cb['Remark'];
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
									}
										
								
								
								
						}
						
						}	
						
						
						if(array_key_exists('MPI', $externals))
						{
						if($externals['MPI']['Selected'])
						{
							 $mpi=$externals['MPI']['Test'];
							 	if(!empty($mpi['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'MPI'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($mpi))
												{
													foreach($mpi as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="MPI";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($mpi['SName'])?$mpi['SName']:null;
												$ct->save(false);
										
												if(!empty($mpi['delobservations']))
												{
													foreach($mpi['delobservations'] as $db)
													{
														$dob=Certmpidetails::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
												$i=1;
											foreach($mpi['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certmpidetails;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certmpidetails::model()->findByPk($cb['Id']);
												}
												$cob->SeqNo=$i;
												$cob->Parameter=$cb['Parameter'];
												$cob->Required=$cb['Required'];
												$cob->Observation=$cb['Observation'];
												$cob->Remark=$cb['Remark'];
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
											
											
										}


							 
						}
						}
						
						if(array_key_exists('ME', $externals))
						{
						if($externals['ME']['Selected'])
						{
							 $me=$externals['ME']['Test'];	
							 	if(!empty($me['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'ME'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($me))
												{
													foreach($me as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="ME";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($me['SName'])?$me['SName']:null;
												$ct->save(false);
										
												if(!empty($me['delobservations']))
												{
													foreach($me['delobservations'] as $db)
													{
														$dob=Certmicroexamination::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
											$i=1;
											foreach($me['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certmicroexamination;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certmicroexamination::model()->findByPk($cb['Id']);
												}
												$cob->SeqNo=$i;
												$cob->BatchCode=empty($cb['BatchCode'])?"":$cb['BatchCode'];
												$cob->Parameter=$cb['Parameter'];
												$cob->Required=$cb['Required'];
												$cob->Observation=$cb['Observation'];
												$cob->Remark=$cb['Remark'];
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
										}


							 
						}
						}
						
						if(array_key_exists('H', $externals))
						{
						if($externals['H']['Selected'])
						{
							 $hext=$externals['H']['Test'];	
							 	if(!empty($hext['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'H'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($hext))
												{
													foreach($hext as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="H";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($hext['SName'])?$hext['SName']:null;
												$ct->save(false);
										
												if(!empty($hext['delobservations']))
												{
													foreach($hext['delobservations'] as $db)
													{
														$dob=Certexthardness::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
												$i=1;
											
												//$cb=$hext['Observations'];
											foreach($hext['Observations'] as $cb)
											{											
												if(empty($cb['Id']))
												{
												   
														$cob=new Certexthardness;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certexthardness::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
										}
											
											
											
										}


						}
						}
						
						
						if(array_key_exists('ND', $externals))
						{
						if($externals['ND']['Selected'])
						{
							 $nondes=$externals['ND']['Test'];	
							 	if(!empty($nondes['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'ND'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($nondes))
												{
													foreach($nondes as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="ND";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($nondes['SName'])?$nondes['SName']:null;
												$ct->save(false);
										
												if(!empty($nondes['delobservations']))
												{
													foreach($nondes['delobservations'] as $db)
													{
														$dob=Certnondestructive::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
												$i=1;
											foreach($nondes['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certnondestructive;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certnondestructive::model()->findByPk($cb['Id']);
												}
												$cob->SeqNo=$i;
												$cob->Parameter=$cb['Parameter'];
												$cob->Required=$cb['Required'];
												$cob->Observation=$cb['Observation'];
												$cob->Remark=$cb['Remark'];
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
											
											
										}


						}
						}
						
						if(array_key_exists('ST', $externals))
						{
						if($externals['ST']['Selected'])
						{
							$streat=$externals['ST']['Test'];	
								if(!empty($streat['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'ST'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($streat))
												{
													foreach($streat as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="ST";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($streat['SName'])?$streat['SName']:null;
												$ct->save(false);
										
												if(!empty($streat['delobservations']))
												{
													foreach($streat['delobservations'] as $db)
													{
														$dob=Certsurfacetreat::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
											$i=1;
											foreach($streat['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certsurfacetreat;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certsurfacetreat::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
										}


						}
						}
						
						if(array_key_exists('STDELTA', $externals))
						{
						if($externals['STDELTA']['Selected'])
						{
							$stdel=$externals['STDELTA']['Test'];	
								if(!empty($stdel['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'STDELTA'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($stdel))
												{
													foreach($stdel as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="STDELTA";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($stdel['SName'])?$stdel['SName']:null;
												$ct->save(false);
										
												if(!empty($stdel['delobservations']))
												{
													foreach($stdel['delobservations'] as $db)
													{
														$dob=Certsurfacetreatdelta::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
												$i=1;
											
												$cb=$stdel['Observations'];
												if(empty($cb['Id']))
												{
												   
														$cob=new Certsurfacetreatdelta;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certsurfacetreatdelta::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											
											
											
											
										}


						}
						}
						
						if(array_key_exists('STDA', $externals))
						{
						if($externals['STDA']['Selected'])
						{
							$stda=$externals['STDA']['Test'];	
								if(!empty($stda['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'STDA'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($stda))
												{
													foreach($stda as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="STDA";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($stda['SName'])?$stda['SName']:null;
												$ct->save(false);
										
												if(!empty($stda['delobservations']))
												{
													foreach($stda['delobservations'] as $db)
													{
														$dob=Certsurfacetreatdacro::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
										$i=1;
											
												$cb=$stda['Observations'];
												if(empty($cb['Id']))
												{
												   
														$cob=new Certsurfacetreatdacro;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certsurfacetreatdacro::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											
											
											
										}


						}
						}
						
						if(array_key_exists('STHOT', $externals))
						{
						if($externals['STHOT']['Selected'])
						{
							$sthot=$externals['STHOT']['Test'];
							if(!empty($sthot['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'STHOT'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($sthot))
												{
													foreach($sthot as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="STHOT";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($sthot['SName'])?$sthot['SName']:null;
												$ct->save(false);
										
												if(!empty($sthot['delobservations']))
												{
													foreach($sthot['delobservations'] as $db)
													{
														$dob=Certsurfacetreathot::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
											$i=1;
											
												$cb=$sthot['Observations'];
												if(empty($cb['Id']))
												{
												   
														$cob=new Certsurfacetreathot;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certsurfacetreathot::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											
											
										}

							
						}
						}
						
						if(array_key_exists('STP', $externals))
						{
						if($externals['STP']['Selected'])
						{
							$stp=$externals['STP']['Test'];	
								if(!empty($stp['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'STP'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($stp))
												{
													foreach($stp as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="STP";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($stp['SName'])?$stp['SName']:null;
												$ct->save(false);
										
												if(!empty($stp['delobservations']))
												{
													foreach($stp['delobservations'] as $db)
													{
														$dob=Certsurfacetreatplat::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
												$i=1;
											
												$cb=$stp['Observations'];
												if(empty($cb['Id']))
												{
												   
														$cob=new Certsurfacetreatplat;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certsurfacetreatplat::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											
											
											
										}


						}
						}
						
						if(array_key_exists('OD', $externals))
						{
						if($externals['OD']['Selected'])
						{
							$othdet=$externals['OD']['Test'];	
								if(!empty($othdet['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'OD'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($othdet))
												{
													foreach($othdet as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="OD";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($othdet['SName'])?$othdet['SName']:null;
												$ct->save(false);
										
												if(!empty($othdet['delobservations']))
												{
													foreach($othdet['delobservations'] as $db)
													{
														$dob=Certotherdetails::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
											$i=1;
											foreach($othdet['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certotherdetails;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certotherdetails::model()->findByPk($cb['Id']);
												}
												$cob->SeqNo=$i;
												$cob->Parameter=$cb['Parameter'];
												$cob->Required=$cb['Required'];
												$cob->Observation=$cb['Observation'];
												$cob->Remark=$cb['Remark'];
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
											
											
										}


						}
						}
						
						
						if(array_key_exists('STDELTA2', $externals))
						{
						if($externals['STDELTA2']['Selected'])
						{
							$stdel2=$externals['STDELTA2']['Test'];	
								if(!empty($stdel2['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'STDELTA2'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($stdel2))
												{
													foreach($stdel2 as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="STDELTA2";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($stdel2['SName'])?$stdel2['SName']:null;
												$ct->save(false);
										
												if(!empty($stdel2['delobservations']))
												{
													foreach($stdel2['delobservations'] as $db)
													{
														$dob=Certsurfacetreatdelta::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
											$i=1;
											
												$cb=$stdel2['Observations'];
												if(empty($cb['Id']))
												{
												   
														$cob=new Certsurfacetreatdelta;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certsurfacetreatdelta::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											
											
											
										}


						}
						}
						
						if(array_key_exists('TZC', $externals))
						{
						if($externals['TZC']['Selected'])
						{
							$tcz=$externals['TZC']['Test'];	
								if(!empty($tcz['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'TZC'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($tcz))
												{
													foreach($tcz as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="TZC";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($tcz['SName'])?$tcz['SName']:null;
												$ct->save(false);
										
												if(!empty($tcz['delobservations']))
												{
													foreach($tcz['delobservations'] as $db)
													{
														$dob=Certsurfacetreatdelta::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
										
											
												$cb=$tcz['Observations'];
												if(empty($cb['Id']))
												{
												   
														$cob=new Certmisctest;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreatedOn=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certmisctest::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												
												$cob->save(false);
												
											
											
											
										}


						}
						}
						
						
						
						
						
						
						$transaction->commit();
						$this->_sendResponse(200, CJSON::encode($model->Id));
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
			   
			   
			   break;
			   
			    

			   
			   case 'loadcertsects':
			     $transaction=$model->dbConnection->beginTransaction();
				try
					{
						
						
						$labnos=[];
						$certtype=$data['certtype'];
						
						foreach($data['bcs'] as $d)
						{
							$labnos[]=$d['LabNo'];
						}
						
						//$rirs=Receiptir::model()->findAll();
						//$this->_sendResponse(401, CJSON::encode($data));
						$criteria=new CDbCriteria;	
						//$criteria->condition='t.Status = "Completed"';
                		 
							
						 $criteria->addInCondition('LabNo',$labnos);						
						$rirs=Receiptir::model()->findAll($criteria);
						
						
						function findWhere($array, $criteria) {
    foreach ($array as $item) {
        $matches = true;
        foreach ($criteria as $key => $value) {
            if (!isset($item->$key) || $item->$key !== $value) {
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




						
						
						$chemspec=[];
						$chemrefs=[];	
							$chemtests=[];	
							
							
						if($certtype==='Normal')
						{
							$tenspec=(object)[];
							$impspec=(object)[];
							$proofspec=(object)[];
							$hardspec=(object)[];
							
						$mechspec=[];//['TENSILE'=>$tenspec,'IMPACT'=>$impspec,'PROOF'=>$proofspec,'HARDNESS'=>$hardspec];
						$mechrefs=[];
						$mechtests=[];
						}
						else
						{
							
							$tenspec=(object)[];
							$impspec20=(object)[];
							$impspec40=(object)[];
							$proofspec=(object)[];
							$hardspec=(object)[];
							
						$mechspec=[];//'TENSILE'=>$tenspec,'IMPACT20'=>$impspec20,'IMPACT40'=>$impspec40,'PROOF'=>$proofspec,'HARDNESS'=>$hardspec];
						$mechrefs=[];
						$mechtests=[];
						}

						
							foreach( $rirs as $r)
							{
								$allrtds=Rirtestdetail::model()->findAll(['condition'=>'Status="complete" AND RIRId=:ririd','params'=>[':ririd'=>$r->Id]]);
								
								foreach($allrtds as $d)
								{
											$std="";											
											if($d->test->testfeatures[0]->IsStd)
												{																
													$sub=null;
													if(isset($d->SSID))
													{
														$sub=Substandards::model()->findByPk($d->SSID);																	
														$type="";//$sub->Type;
														$substandard="";															
														$substandard=$sub->Grade." ".$sub->ExtraInfo;															
														$std=$sub->std->Standard." ".$substandard;
													}		
												}
														
														
										switch($d->TUID)
										{								
								
											case 'CHEM': 
													try{				
															$chemspec=[];
						
							$chemtests=[];														
														$obs=array();												
												//---Observatory Parameters-----//								
																							
																	if(!empty($d->rirtestobs))
																	{																			
																			foreach($d->rirtestobs as $e)
																			{																	
																				$chemspec[$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'Parameter'=>$e->tP->Parameter,'PSymbol'=>$e->tP->PSymbol,'SpecMin'=>$e->SpecMin,'SpecMax'=>$e->SpecMax];
																				$obs[$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'Value'=>$e->rirtestobsvalues[0]->Value];
																			}
																	}																	
																	
																$chemtests[]=(object)['SSID'=>$d->SSID,'TMID'=>$d->TMID,'BatchCode'=>$r->BatchCode,'BatchNo'=>$r->BatchNo,
																'LabNo'=>$r->LabNo,'HeatNo'=>$r->HeatNo,'TestName'=>$d->TestName,'TestInfo'=>"",'Remark'=>"OK",'ShowInCert'=>1,
																'RTID'=>$d->Id,'TUID'=>$d->TUID,'Obs'=>$obs];													
											
											if(in_array($d->SSID, array_column($chemrefs, 'SSID')))
											{
												$criteria = ['SSID' => $d->SSID];
												$oldref = findWhere($chemrefs, $criteria);
												$oldref->chemtests[]=$chemtests[0];													
											}
											else
											{
												$chemrefs[]=(object)['SSID'=>$d->SSID,'TMID'=>$d->TMID,'Std'=>$std,'chemtests'=>$chemtests,'ChemSpec'=>$chemspec,];
											}
													}
													catch(Exception $e)
													{
														$this->_sendResponse(401, CJSON::encode($e->getMessage()));
													}
											break;
											
												case 'TENSILE':	
												case 'RBHARD':
												case 'RCHARD':
												case 'MVHARD':
												case 'VHARD':
												case 'BHARD':														
												case 'PROOF':	
												case 'IMP':	
												try{
														$mechspec=[];					
														$mechtests=[];													
														$obs=array();	
																							
																	if(!empty($d->rirtestobs))
																	{
																			foreach($d->rirtestobs as $e)
																			{		
																				if($e->tP->IsSpec)
																					{
																						$mkey=$d->TUID."".$e->tP->PSymbol;
																						$tkey=$d->TUID;
																					//for specification	
																						switch($d->TUID)
																						{
																							case 'IMP':
																							if($certtype==='Bushing')
																									{
																										$gettemp=Rirtestbasic::model()->with('tBP')->find(['condition'=>'tBP.PSymbol="Temp" AND RTID=:rtid',
																										'params'=>[':rtid'=>$d->Id]]);																										
																										$mkey=$d->TUID."".$e->tP->PSymbol."".abs($gettemp->BValue);
																										$tkey=$d->TUID."".abs($gettemp->BValue);
																										
																										if (!array_key_exists($mkey,$mechspec))
																										{
																												$mechspec[$mkey]=(object)['PID'=>$e->tP->Id,'Parameter'=>$e->tP->Parameter,'PSymbol'=>$e->tP->PSymbol,'PUnit'=>$e->tP->PUnit,
																						'SpecMin'=>$e->SpecMin,'SpecMax'=>$e->SpecMax,'Tkey'=>$tkey,'TUID'=>$tkey,'Temp'=>$gettemp->BValue,'TempUnit'=>$gettemp->tBP->PUnit];
																										}
																									}
																									else
																									{
																										$gettemp=Rirtestbasic::model()->with('tBP')->find(['condition'=>'tBP.PSymbol="Temp" AND RTID=:rtid',
																										'params'=>[':rtid'=>$d->Id]]);	
																										$mechspec[$mkey]=(object)['PID'=>$e->tP->Id,'Parameter'=>$e->tP->Parameter,'PSymbol'=>$e->tP->PSymbol,
																										'PUnit'=>$e->tP->PUnit,
																				'SpecMin'=>$e->SpecMin,'SpecMax'=>$e->SpecMax,'TUID'=>$d->TUID,'Temp'=>$gettemp->BValue,'TempUnit'=>$gettemp->tBP->PUnit];
																									}
																								
																							break;
																							
																							default:
																							$mkey=$d->TUID."".$e->tP->PSymbol;
																						$tkey=$d->TUID;
																							if (!array_key_exists($mkey,$mechspec))
																										{
																				$mechspec[$mkey]=(object)['PID'=>$e->tP->Id,'Parameter'=>$e->tP->Parameter,'PSymbol'=>$e->tP->PSymbol,
																				'SpecMin'=>$e->SpecMin,'SpecMax'=>$e->SpecMax,'TUID'=>$d->TUID];
																										}
																				
																						}	
																						
																				//--for test																				
																				switch($d->TUID)
																						{
																							
																						case 'IMP':
																							//--test seperate
																							//$aobs=[];
																								if($certtype==='Bushing')
																									{
																										$gettemp=Rirtestbasic::model()->with('tBP')->find(['condition'=>'tBP.PSymbol="Temp" AND RTID=:rtid',
																										'params'=>[':rtid'=>$d->Id]]);
																										
																										
																								$avg=0;
																								$impval=[];
																								foreach($e->rirtestobsvalues as $v)
																								{
																									$impval[]=$v->Value;
																									$avg=$avg+$v->Value;
																								}
																								
																								$avg=$avg/count($impval);
																								$avg=round($avg,2);
																								$impdval=implode(",",$impval);
																								$impdval=$impdval.", Avg:".$avg;
																								//$mkey=$e->tP->PSymbol."".$gettemp->BValue;
																								
																												
																								$obs[$mkey]=(object)['PID'=>$e->tP->Id,'Value'=>$impdval,'TUID'=>$d->TUID,'Temp'=>empty($gettemp)?null:$gettemp->BValue,'TempUnit'=>$gettemp->tBP->PUnit];
																									
																										
																									}
																									else
																									{
																							
																									$gettemp=Rirtestbasic::model()->with('tBP')->find(['condition'=>'tBP.PSymbol="Temp" AND RTID=:rtid',
																										'params'=>[':rtid'=>$d->Id]]);
																								$avg=0;
																								$impval=[];
																								foreach($e->rirtestobsvalues as $v)
																								{
																									$impval[]=$v->Value;
																									$avg=$avg+$v->Value;
																								}
																								
																								$avg=$avg/count($impval);
																								$avg=round($avg,2);
																								$impdval=implode(",",$impval);
																								$impdval=$impdval.", Avg:".$avg;
																								
																								$obs[$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'Value'=>$impdval,'Temp'=>empty($gettemp)?null:$gettemp->BValue,'TempUnit'=>$gettemp->tBP->PUnit];
																									
																									
																									
																									}
																			
																							
																							
																						break;	
																							
																							
																						case 'RBHARD':
																						case 'RCHARD':
																						case 'MVHARD':
																						case 'VHARD':
																						case 'BHARD':
																						//$aobs=[];
																								$obs[$tkey.$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'TUID'=>$d->TUID,'Value'=>json_decode($e->rirtestobsvalues[0]->Value)];
																								
																								break;
																													
																						case 'PROOF':																						
																						case 'TENSILE':
																						
																						$obs[$tkey.$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'TUID'=>$d->TUID,'Value'=>round((float)$e->rirtestobsvalues[0]->Value,2)];
																						break;
																						default:
																						//$aobs=[];
																							$obs[$tkey.$e->tP->PSymbol]=(object)['PID'=>$e->tP->Id,'TUID'=>$d->TUID,'Value'=>$e->rirtestobsvalues[0]->Value];
																							
																						break;
																						
																						}
																						
																				
																					}
																			}
																	}	
																	
																	// Assigning TestInfo based on TestName
        $testInfo = '';
        if (preg_match('/\bImpact-\d\b|\bTensile-\d\b/', $d->TestName)) {
            $number = substr($d->TestName, -1);  // Get the last character (1, 2, or 3)
            switch ($number) {
                case '1':
				case '4':
                    $testInfo = 'Top';
                    break;
                case '2':
				case '5':
                    $testInfo = 'Middle';
                    break;
                case '3':
				case '6':
                    $testInfo = 'Bottom';
                    break;
                default:
                    $testInfo = '';  // Default if no match found
            }
        }
																	
																	$mechtests[]=(object)['SSID'=>$d->SSID,'TMID'=>$d->TMID,'BatchCode'=>$r->BatchCode,'BatchNo'=>$r->BatchNo,
																'LabNo'=>$r->LabNo,'HeatNo'=>$r->HeatNo,'TestName'=>$d->TestName,'TestInfo'=>$testInfo,'Remark'=>"OK",'ShowInCert'=>1,
																'RTID'=>$d->Id,'TUID'=>$tkey,'Obs'=>$obs];		
																
																//--for ref
																switch($d->TUID)
																						{
																							case 'IMP':
																							
																							if($certtype==='Bushing')
																									{
																										$gettemp=Rirtestbasic::model()->with('tBP')->find(['condition'=>'tBP.PSymbol="Temp" AND RTID=:rtid',
																										'params'=>[':rtid'=>$d->Id]]);
																										$tuid=$d->TUID.abs($gettemp->BValue);
																										
													if (array_key_exists($tuid,$mechrefs))
													{	
																
													if(in_array($d->SSID, array_column($mechrefs[$tuid], 'SSID')))
													{
														$criteria = ['Test' => $tuid,];
														$oldref = findWhere($mechrefs[$tuid], $criteria);
														$oldref->mechtests[]=$mechtests[0];
															
													}
													else
													{
															
													$mechrefs[$tuid][]=(object)['Test'=>$tuid,'TestName'=>$d->test->TestName,'TMID'=>$d->TMID,'SSID'=>$d->SSID,'Std'=>$std,'mechtests'=>$mechtests,'MechSpec'=>$mechspec,];
														
													}
													}
													else
													{
														$mechrefs[$tuid][]=(object)['Test'=>$tuid,'TestName'=>$d->test->TestName,'TMID'=>$d->TMID,'SSID'=>$d->SSID,'Std'=>$std,'mechtests'=>$mechtests,'MechSpec'=>$mechspec,];
													}
															
																									}
																									else
																									{
																										if (array_key_exists($d->TUID,$mechrefs))
													{	
																
													if(in_array($d->SSID, array_column($mechrefs[$d->TUID], 'SSID')))
													{
														$criteria = ['SSID' => $d->SSID,];
														$oldref = findWhere($mechrefs[$d->TUID], $criteria);
														$oldref->mechtests[]=$mechtests[0];
															
													}
													else
													{
															
													$mechrefs[$d->TUID][]=(object)['Test'=>$d->TUID,'TestName'=>$d->test->TestName,'TMID'=>$d->TMID,'SSID'=>$d->SSID,'Std'=>$std,'mechtests'=>$mechtests,'MechSpec'=>$mechspec,];
														
													}
													}
													else
													{
														$mechrefs[$d->TUID][]=(object)['Test'=>$d->TUID,'TestName'=>$d->test->TestName,'TMID'=>$d->TMID,'SSID'=>$d->SSID,'Std'=>$std,'mechtests'=>$mechtests,'MechSpec'=>$mechspec,];
													}
																										
																									}
																									break;
																							
																							default:
																
													if (array_key_exists($d->TUID,$mechrefs))
													{	
																
													if(in_array($d->SSID, array_column($mechrefs[$d->TUID], 'SSID')))
													{
														$criteria = ['SSID' => $d->SSID,];
														$oldref = findWhere($mechrefs[$d->TUID], $criteria);
														$oldref->mechtests[]=$mechtests[0];
															
													}
													else
													{
															
													$mechrefs[$d->TUID][]=(object)['Test'=>$d->TUID,'TestName'=>$d->test->TestName,'TMID'=>$d->TMID,'SSID'=>$d->SSID,'Std'=>$std,'mechtests'=>$mechtests,'MechSpec'=>$mechspec,];
														
													}
													}
													else
													{
														$mechrefs[$d->TUID][]=(object)['Test'=>$d->TUID,'TestName'=>$d->test->TestName,'TMID'=>$d->TMID,'SSID'=>$d->SSID,'Std'=>$std,'mechtests'=>$mechtests,'MechSpec'=>$mechspec,];
													}
																						}		
													
													}
													catch(Exception $e)
													{
														$this->_sendResponse(401, CJSON::encode($e->getMessage()));
													}
											break;			
											
								
								default:
									
									$obobs=[];
										
										$testresults=empty($d->rirtestobs)?0:count($d->rirtestobs[0]->rirtestobsvalues);
										$observations=[];
										for($k=0; $k<$testresults;$k++)
										{
											$obsvalue=[];
											foreach($d->rirtestobs as $e)
											{
												$obsvalue[]=(object)array('Id'=>$e->Id,
													'PId'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'PDType'=>$e->tP->PDType,
													'TestId'=>$d->TestId,'ShowInCert'=>0,
													'Symbol'=>$e->tP->PSymbol,'Param'=>$e->tP->Parameter,
													'Value'=>$e->rirtestobsvalues[$k]->Value);
											
											}
											
											$observations[$k]=(object)array(
											'BatchCode'=>$d->rIR->BatchCode,
											'LabNo'=>$d->rIR->LabNo,'ShowInCert'=>0,
											'TestNo'=>$d->TestNo,
											'ObsNo'=>$k+1,
											'ObsValues'=>$obsvalue);
										}		
												
												$cond=array();
								
									foreach($d->rirtestobs as $e)
									{
									
							$cond[]=(object)array('Id'=>$e->Id,'PId'=>$e->TPID,'PUnit'=>$e->tP->PUnit,'ShowInCert'=>1,
							'Permissible'=>"",'Symbol'=>$e->tP->PSymbol,'Param'=>$e->tP->Parameter,'Min'=>$e->SpecMin,'Max'=>$e->SpecMax,'RirTestId'=>$e->RTID);
											
									}	

$pos=(object)array('observations'=>$observations,'conditions'=>$cond);									
												
												
								break;
								
									}
									
									
							 
								}
							}
							
							
						$chemicals=(object)array('Section'=>"Chemical",'Ref'=>$chemrefs);
						$mechanicals=(object)array('Section'=>"Mechanical",'Ref'=>$mechrefs);
						$externals=(object)array('Section'=>"External");
						
							$data=(object)array('chemicals'=>$chemicals,'mechanicals'=>$mechanicals,'externals'=>$externals);
							$this->_sendResponse(200, CJSON::encode($data));	
				}
				catch(Exception $e)
				{
					$this->_sendResponse(500, CJSON::encode($e->getMessage()));
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
				case 'getcertinfo':$model=Certbasic::model()->findByPk($_GET['id']); 		break;
				case 'sendcertmail':$model=Certbasic::model()->findByPk($_GET['id']); 		break;
				// Find respective model
				 case 'custrirs':$model=Customerinfo::model()->findByPk($_GET['id']);break;
				case 'editcertformat': $model=Certformats::model()->findByPk($_GET['id']);break;
				
					case 'approvecert':		$model=Certbasic::model()->findByPk($_GET['id']); 		break;
				
				case 'checkcert':		$model=Certbasic::model()->findByPk($_GET['id']); 		break;
				
				
				case 'certbasicupdate':
					$model = Certbasic::model()->findByPk($_GET['id']);                    
					break;
				case 'certchemupdate':
					$model = Certbasic::model()->findByPk($_GET['id']);                    
					break;
				case 'certextraupdate':
					$model = Certbasic::model()->findByPk($_GET['id']);                    
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
				case 'getcertinfo':
				case 'sendcertmail':
				 case 'custrirs':
			case 'editcertformat': $data=$put_vars;break;
			case 'approvecert':$data=$put_vars;break;
			case 'checkcert':$data=$put_vars;break;
			
			 case 'certextraupdate':$data=$put_vars;break;	
				
		 case 'certchemupdate':$data=$put_vars;break;		
				
		 case 'certbasicupdate':
				$data=$put_vars;
				foreach($data as $var=>$value)
				  {
					// Does the model have this attribute? If not raise an error
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
				case 'getcertinfo':
				try{
					
					
					function isJson($string) {
    json_decode($string);
    return (json_last_error() === JSON_ERROR_NONE);
}

					$cb=$model;
						$orderedKeys = [];
							$preparedby="";
							$preparedsign="";
							if(!empty($cb->PreparedBy))
							{
								$preparedsign=empty($cb->preparedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$cb->preparedBy->usersignuploads[0]->name);
								$preparedby=$cb->preparedBy->FName." ".$cb->preparedBy->LName;	
							}		
							
							$approvedby="";
							$approvedsign="";
							$qrimg="";
							
							if(!empty($cb->ApprovedBy))
							{
							$approvedsign=empty($cb->approvedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$cb->approvedBy->usersignuploads[0]->name);
							$approvedby=$cb->approvedBy->FName." ".$cb->approvedBy->LName;
							
								$qrimg=Yii::app()->params['base-url']."pdf/certificate/".$cb->TCNo.".png";							
							}				

						$checkedby="";
							$checkedsign="";
							if(!empty($cb->CheckedBy))
							{
							$checkedsign=empty($cb->checkedBy->usersignuploads)?"":(Yii::app()->params['imgurl']."signuploads/files/".$cb->checkedBy->usersignuploads[0]->name);
							$checkedby=$cb->checkedBy->FName." ".$cb->checkedBy->LName;	
							}		
							
							
							
							function findWhere($array, $criteria) {
    foreach ($array as $item) {
        $matches = true;
        foreach ($criteria as $key => $value) {
            if (!isset($item->$key) || $item->$key !== $value) {
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

						
						$certests=[];
						$chemrefs=[];
						$mechrefs=[];
						$extsections=array();
						$externals=[];
						
							foreach($cb->certsections as $cs)
							{
								$sec=$cs->Section;
								switch($sec)
								{
									case 'Chemical':
											foreach($cs->certtests as $ct)
												{
													$chemspec=[];	$chemtests=[];
													$rt=$ct->rT;
													$obs=[];										
													$std="";
													$sub=null;
															if(isset($ct->SSID))
															{
																$sub=Substandards::model()->findByPk($ct->SSID);																
																$type="";//$sub->Type;
																$substandard="";															
																$substandard=$sub->Grade;															
																$std=$sub->std->Standard." ".$substandard;
															}	
															
															foreach($cs->certtestspecs as $sp)
															{
																if($sp->ShowInCert)
																{
																$chemspec[$sp->p->PSymbol]=(object)['PID'=>$sp->PID,'Parameter'=>$sp->p->Parameter,'ShowInCert'=>$sp->ShowInCert,'PSymbol'=>$sp->p->PSymbol,'SpecMin'=>$sp->SpecMin,'SpecMax'=>$sp->SpecMax];
																}
															}															
															
															foreach($ct->certtestobs as $ob)
															{
																if(in_array($ob->p->PSymbol, array_column($chemspec, 'PSymbol')))
																{
																	$obs[$ob->p->PSymbol]=(object)['PID'=>$ob->PID,'Value'=>$ob->Value];
																}																
															}

									$chemtests[]=(object)['SSID'=>$ct->SSID,'TMID'=>$ct->TMID,'BatchCode'=>$rt->rIR->BatchCode,'BatchNo'=>$rt->rIR->BatchNo,
									'LabNo'=>$ct->LabNo,'HeatNo'=>$ct->HeatNo,'TestName'=>$ct->TestName,'TestInfo'=>$ct->TestInfo,
																'RTID'=>$ct->RTID,'TUID'=>$ct->TUID,'Obs'=>$obs,'Remark'=>$ct->Remark];
										
													if(in_array($rt->SSID, array_column($chemrefs, 'SSID')))
													{
														$criteria = ['SSID' => $rt->SSID];
														$oldref = findWhere($chemrefs, $criteria);
														$oldref->chemtests[]=$chemtests[0];													
													}
													else
													{
														$chemrefs[]=(object)['SSID'=>$rt->SSID,'Std'=>$std,'SName'=>$cs->SName,'Reference'=>$cs->Reference,'chemtests'=>$chemtests,'ChemSpec'=>$chemspec,];
													}
												}
								
									
									break;
									
case 'Mechanical':
    $mechtestsIndex = []; // Use LabNo as key for fast lookup
    $mechrefsIndex = [];  // Use SSID as key for fast lookup

    // Prepare mechanical specs based on certtestspecs and ShowInCert flag
    $mechspec = [];
	$orderedKeys = []; // This will store the ordered keys for the later insertion in mechtest->Obs

    foreach ($cs->certtestspecs as $sp) {
        if ($sp->ShowInCert) {
            $tuid = $sp->TUID;
            $tempKey = null;
			$tempKey = isset($sp->Temp) ? $sp->Temp : null;
            $ssymbol = match ($tuid) {
                'IMP' => 'Impact @'.$sp->Temp."°C",
                'IMP20' => 'Impact +20°C',
                'IMP40' => 'Impact -40°C',
                'TENSILE' => $sp->p->Parameter,  // Ensure TENSILE is properly handled
                'VHARD' => 'Vickers Hardness',
                'MVHARD' => 'Micro Vickers Hardness',
                'BHARD' => 'Brinell Hardness',
                'RCHARD', 'RBHARD' => 'Rockwell Hardness',
                'PROOF' => 'ProofLoad( KN )',
                default => $sp->p->PSymbol,
            };

            // Handle grouping by TUID and PSymbol
            if ($cb->CertType === 'Bushing') {
                if (in_array($tuid, ['IMP', 'IMP20', 'IMP40'])) {
                    $tempKey = isset($sp->Temp) ? $sp->Temp : null;
                    $mkey = $sp->p->test->TUID . "" . $sp->p->PSymbol . "" . abs($tempKey);
                } else {
                    $mkey = $tuid . "" . $sp->p->PSymbol;
                }
            } else {
				$tempKey = isset($sp->Temp) ? $sp->Temp : null;
                $mkey = $tuid . "" . $sp->p->PSymbol;
            }

            // Store mechanical specification for fast lookup
            $mechspec[$mkey] = (object)[
                'PID' => $sp->PID,
                'Parameter' => $sp->p->Parameter,
                'ShowInCert' => $sp->ShowInCert,
                'PSymbol' => $sp->p->PSymbol,
                'SpecMin' => $sp->SpecMin,
                'SpecMax' => $sp->SpecMax,
                'SSymbol' => $ssymbol,
                'Temp' => $tempKey,
                'PUnit' => $sp->p->PUnit,
                'TUID' => $sp->p->test->TUID
            ];
			
			 // Add the key to the ordered list for later use
        $orderedKeys[] = $mkey;
        }
    }

    // Now prepare mechtests based on certtests
    foreach ($cs->certtests as $ct) {
        $rt = $ct->rT;
        $obs = []; // Initialize observations array for each certtest
        $std = "";

        // Check if SSID exists and fetch the substandard
        if (isset($rt->SSID)) {
            $sub = Substandards::model()->findByPk($rt->SSID);
            if ($sub) {
                $substandard = $sub->Grade . " " . $sub->ExtraInfo;
                $std = $sub->std->Standard . " " . $substandard;
            }
        }

        $labNo = $rt->rIR->LabNo;

        // Check if the LabNo is already in mechtestsIndex
        if (!isset($mechtestsIndex[$labNo])) {
            // Create new mechtests entry for this LabNo if not already present
            foreach ($ct->certtestobs as $ob) {

                // Handle Bushing specific logic
                if ($cb->CertType === 'Bushing') {
                    if ($ob->p->test->TUID === 'IMP') {
                        $tempKey = isset($ob->Temp) ? $ob->Temp : 'NoTemp'; // Handle missing temperature
                        // Group observations based on TUID, PSymbol, Temp, and TestInfo
                        $groupKey = $ob->p->test->TUID . $ob->p->PSymbol . abs($tempKey);
                    } else {
                        $groupKey = $ob->p->test->TUID . $ob->p->PSymbol;
                    }
                } else {
                    // For non-Bushing cases, group by TUID and PSymbol
                    $groupKey = $ob->p->test->TUID . $ob->p->PSymbol;
                }

                // Ensure the groupKey exists and append the observation
                if (array_key_exists($mkey, $mechspec)) {
                    if (!isset($obs[$groupKey])) {
                        $obs[$groupKey] = []; // Initialize the group if not set
                    }

                    // Append observation
                    $newObs = (object)[
                        'CTID' => $ob->CTID,
                        'PID' => $ob->PID,
                        'Value' => isJson($ob->Value) ? json_decode($ob->Value) : $ob->Value,
                        'TUID' => $ob->p->test->TUID,
                        'TestInfo' => $ct->TestInfo,
                        'Temp' => $ob->Temp ?? '0', // Default to '0' if no Temp
                    ];

                    // Add to specific TestInfo if Bushing, else add to the general group
                    if ($cb->CertType === 'Bushing') {
                        $obs[$groupKey][$ct->TestInfo][] = $newObs;
                    } else {
                        $obs[$groupKey][] = $newObs;
                    }
                }
            }

            // Add the test entry to the mechtestsIndex
            $mechtestsIndex[$labNo] = (object)[
                'SSID' => $ct->SSID,
                'TMID' => $ct->TMID,
                'BatchCode' => $rt->rIR->BatchCode,
                'BatchNo' => $rt->rIR->BatchNo,
                'LabNo' => $labNo,
                'HeatNo' => $ct->HeatNo,
                'RTID' => $rt->Id,
                'Obs' => $obs
            ];
        } else {
            // Else part: LabNo already exists in mechtestsIndex, append new observations
            foreach ($ct->certtestobs as $ob) {
                // Similar grouping logic as above
                if ($cb->CertType === 'Bushing') {
                    if ($ob->p->test->TUID === 'IMP') {
                        $tempKey = isset($ob->Temp) ? $ob->Temp : 'NoTemp'; 
                        $groupKey = $ob->p->test->TUID . $ob->p->PSymbol . abs((int)$tempKey);
                    } else {
                        $groupKey = $ob->p->test->TUID . $ob->p->PSymbol;
                    }
                } else {
                    $groupKey = $ob->p->test->TUID . $ob->p->PSymbol;
                }

                // Ensure the groupKey exists and append the observation
                if (array_key_exists($mkey, $mechspec)) {
                    if (!isset($mechtestsIndex[$labNo]->Obs[$groupKey])) {
                        $mechtestsIndex[$labNo]->Obs[$groupKey] = []; // Initialize group if not set
                    }

                    // Append new observation
                    $newObs = (object)[
                        'CTID' => $ob->CTID,
                        'PID' => $ob->PID,
                        'Value' => isJson($ob->Value) ? json_decode($ob->Value) : $ob->Value,
                        'TUID' => $ob->p->test->TUID,
                        'TestInfo' => $ct->TestInfo,
                        'Temp' => $ob->Temp ?? '0',
                    ];

                    if ($cb->CertType === 'Bushing') {
                        $mechtestsIndex[$labNo]->Obs[$groupKey][$ct->TestInfo][] = $newObs;
                    } else {
                        $mechtestsIndex[$labNo]->Obs[$groupKey][] = $newObs;
                    }
                }
            }
        }
    }

    // Now group mechanical tests by SSID
    foreach ($mechtestsIndex as $labNo => $mechtest) {
        $ssid = ($cb->CertType === 'Normal' || $cb->CertType === 'Bushing') ? 1 : $mechtest->RTID;

        if (!isset($mechrefsIndex[$ssid])) {
            $mechrefsIndex[$ssid] = (object)[
                'SSID' => $ssid,
                'Std' => $std,
                'SName' => $cs->SName,
                'Reference' => $cs->Reference,
                'mechtests' => [$mechtest],
                'MechSpec' => $mechspec
            ];
        } else {
            // Merge tests for the same SSID
            $existingRef = &$mechrefsIndex[$ssid];
            $existingRef->mechtests[] = $mechtest;
        }
    }

    // Convert mechrefsIndex back to array for final output
    $mechrefs = array_values($mechrefsIndex);
    break;





																
									
									case 'External':
									
											//--individual--
											
											
					
					
						$keyword=$cs->Keyword;
						 switch ($keyword) {
        case 'NM':
					$nmobservations=Certnonmetallic::model()->findAll(['condition'=>'CSID=:csid','params'=>[':csid'=>$cs->Id]]);
					$test=(object)['Reference'=>$cs->Reference,'SName'=>$cs->SName,'Ref'=>$cs->Ref,'Extra'=>$cs->Extra,'Remark'=>$cs->Remark,'Observations'=>$nmobservations,'delobservations'=>[]];
            break;

        case 'ME':
            $meobservations=Certmicroexamination::model()->findAll(['condition'=>'CSID=:csid','params'=>[':csid'=>$cs->Id]]);
			$test=(object)['Reference'=>$cs->Reference,'SName'=>$cs->SName,'Ref'=>$cs->Ref,'Extra'=>$cs->Extra,'Remark'=>$cs->Remark,'Observations'=>$meobservations,'delobservations'=>[]];
            break;

        case 'MPI':
			$mpiobs=Certmpidetails::model()->findAll(['condition'=>'CSID=:csid','params'=>[':csid'=>$cs->Id]]);
			$test=(object)['Reference'=>$cs->Reference,'SName'=>$cs->SName,'Ref'=>$cs->Ref,'Extra'=>$cs->Extra,'Remark'=>$cs->Remark,'Observations'=>$mpiobs,'delobservations'=>[]];
            break;

        case 'H':
			$hobs=Certexthardness::model()->findAll(['condition'=>'CSID=:csid','params'=>[':csid'=>$cs->Id]]);;
			$test=(object)['Reference'=>$cs->Reference,'SName'=>$cs->SName,'Ref'=>$cs->Ref,'Extra'=>$cs->Extra,'Remark'=>$cs->Remark,'Observations'=>$hobs,'delobservations'=>[]];
            break;

        case 'ND':
			$ndobs=Certnondestructive::model()->findAll(['condition'=>'CSID=:csid','params'=>[':csid'=>$cs->Id]]);;
			$test=(object)['Reference'=>$cs->Reference,'SName'=>$cs->SName,'Ref'=>$cs->Ref,'Extra'=>$cs->Extra,'Remark'=>$cs->Remark,'Observations'=>$ndobs,'delobservations'=>[]];
            break;

        case 'ST':
           $stobs=Certsurfacetreat::model()->findAll(['condition'=>'CSID=:csid','params'=>[':csid'=>$cs->Id]]);;
			$test=(object)['Reference'=>$cs->Reference,'SName'=>$cs->SName,'Ref'=>$cs->Ref,'Extra'=>$cs->Extra,'Remark'=>$cs->Remark,'Observations'=>$stobs,'delobservations'=>[]];
            break;

        case 'STDELTA':
            $deltaobs=Certsurfacetreatdelta::model()->find(['condition'=>'CSID=:csid','params'=>[':csid'=>$cs->Id]]);;;
			$test=(object)['Reference'=>$cs->Reference,'SName'=>$cs->SName,'Ref'=>$cs->Ref,'Extra'=>$cs->Extra,'Remark'=>$cs->Remark,'Observations'=>$deltaobs,'delobservations'=>[]];
            break;

        case 'STDELTA2':
            $delta2obs=Certsurfacetreatdelta::model()->find(['condition'=>'CSID=:csid','params'=>[':csid'=>$cs->Id]]);;;
			$test=(object)['Reference'=>$cs->Reference,'SName'=>$cs->SName,'Ref'=>$cs->Ref,'Extra'=>$cs->Extra,'Remark'=>$cs->Remark,'Observations'=>$delta2obs,'delobservations'=>[]];
            break;

        case 'STDA':
           $daobs=Certsurfacetreatdacro::model()->find(['condition'=>'CSID=:csid','params'=>[':csid'=>$cs->Id]]);;;
			$test=(object)['Reference'=>$cs->Reference,'SName'=>$cs->SName,'Ref'=>$cs->Ref,'Extra'=>$cs->Extra,'Remark'=>$cs->Remark,'Observations'=>$daobs,'delobservations'=>[]];
            break;

        case 'STHOT':
            $hotobs=Certsurfacetreathot::model()->find(['condition'=>'CSID=:csid','params'=>[':csid'=>$cs->Id]]);;;
			$test=(object)['Reference'=>$cs->Reference,'SName'=>$cs->SName,'Ref'=>$cs->Ref,'Extra'=>$cs->Extra,'Remark'=>$cs->Remark,'Observations'=>$hotobs,'delobservations'=>[]];
            break;

        case 'STP':
            $stpobs=Certsurfacetreatplat::model()->find(['condition'=>'CSID=:csid','params'=>[':csid'=>$cs->Id]]);
			$test=(object)['Reference'=>$cs->Reference,'SName'=>$cs->SName,'Ref'=>$cs->Ref,'Extra'=>$cs->Extra,'Remark'=>$cs->Remark,'Observations'=>$stpobs,'delobservations'=>[]];
            break;

        case 'OD':
            $odobs=Certotherdetails::model()->findAll(['condition'=>'CSID=:csid','params'=>[':csid'=>$cs->Id]]);
			$test=(object)['Reference'=>$cs->Reference,'SName'=>$cs->SName,'Ref'=>$cs->Ref,'Extra'=>$cs->Extra,'Remark'=>$cs->Remark,'Observations'=>$odobs,'delobservations'=>[]];
            break;
			
			
			 case 'TZC':
            $odobs=Certmisctest::model()->findAll(['condition'=>'CSID=:csid','params'=>[':csid'=>$cs->Id]]);
			$test=(object)['Reference'=>$cs->Reference,'SName'=>$cs->SName,'Ref'=>$cs->Ref,'Extra'=>$cs->Extra,'Remark'=>$cs->Remark,'Observations'=>$odobs,'delobservations'=>[]];
            break;
			
			 case 'ICR':
            $odobs=Certmisctest::model()->findAll(['condition'=>'CSID=:csid','params'=>[':csid'=>$cs->Id]]);
			$test=(object)['Reference'=>$cs->Reference,'SName'=>$cs->SName,'Ref'=>$cs->Ref,'Extra'=>$cs->Extra,'Remark'=>$cs->Remark,'Observations'=>$odobs,'delobservations'=>[]];
            break;
    }
						
						
						
						
						
						$externals[$keyword]=(object)array('Selected'=>1,'Keyword'=>$keyword ,'Test'=>$test);
					
					
									
									break;
								}
								
								
								
							}
							
							
							
							
							$orderedKeys = array_unique($orderedKeys);
							
							 $chemicals=(object)array('Section'=>"Chemical",'Ref'=>$chemrefs);
						 $mechanicals=(object)array('Section'=>"Mechanical",'Ref'=>$mechrefs,'orderedKeys'=>$orderedKeys);
						 //$externals=(object)array('Section'=>"External",'Ref'=>$mechrefs);
						 $cfno="";
							if(!is_null($cb->FormatNo))
							{
								$cfno=Certformats::model()->findByPk($cb->FormatNo);
								$cfno=$cfno->FormatNo;
							}
							
							$certimg=Certattachments::model()->findAll(array('condition'=>'certid=:cert','params'=>array(':cert'=>$cb->getPrimaryKey())));
							
						$laburls=[];
						$labnames=[];
						$basic=(object)array('Id'=>$cb->Id,'CertDate'=>$cb->CertDate,'obbasic'=>"",'certimgs'=>$certimg,'CertType'=>$cb->CertType,
						'TCNo'=>$cb->TCNo,'TCRevNo'=>$cb->TCRevNo,'CheckedSign'=>$checkedsign,'CheckedBy'=>$checkedby,'QRImg'=>$qrimg,'IsLab'=>1,'laburls'=>$laburls,'FormatNo'=>$cfno,
						'Labs'=>$labnames,'PartDescription'=>$cb->PartDescription,'Customer'=>$cb->cust->Name,'CustEmail'=>null,'certests'=>$certests,'TCFormat'=>$cb->TCFormat,
						'chemicals'=>$chemicals,'mechanicals'=>$mechanicals,'externals'=>$externals,'basicextra'=>empty($cb->certbasicextras)?null:$cb->certbasicextras[0],
						'PreparedBy'=>$preparedby,'PreparedSign'=>$preparedsign,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby);
						
						
							
							
					$certinfo=(object)['basic'=>$basic];
					$this->_sendResponse(200, CJSON::encode($certinfo));
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
				
				case 'sendcertmail':
				try{
					
						$c=$model;
						
						
$sentmails=$data['MailTo'];
							$i=$model;
							
								
							
							
							$msg='Certificate PDF Attached';
						
							
					
						  $mset=Settingsmail::model()->find();

                  $mail = new YiiMailer;
                  $mail->IsSMTP();
                  
				  
				  
 
 $file_path = yii::app()->basePath."/../../pdf/certificate/".$i->TCNo.".pdf";       
 //$swiftAttachment = Swift_Attachment::fromPath($file_path);              
 $mail->setAttachment($file_path);
 
 $mail->setSmtp(  $mset->Server,  $mset->Port,  $mset->Encrypt ,true,  $mset->Email,  $mset->Password);
                  $mail->setFrom($mset->Email,Yii::app()->params['appname']);
                  $mail->setTo($sentmails);
                  $mail->setSubject('Certificate '.$i->TCNo);
                  $mail->setBody($msg);
                  $mail->SMTPDebug =3;

                  if($mail->send())
                  {
                    //$transaction->commit();
                    $result=(object)array('msg'=>"Email sent");
                    
                  }
                  else
                  {
                    
                     $result=$mail->getError();
                  }		

						 $this->_sendResponse(200, CJSON::encode( $result));
				
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
				
				 case 'custrirs':
				try{
					
					$rirs=Receiptir::model()->findAll();//array('condition'=>'CustomerId=:custid','params'=>array(':custid'=>$model->Id)));
					$allrirs=array();
					foreach($rirs as $r)
					{
						
						
						$allrirs[]=(object)array('Id'=>$r->Id,
						'SampleName'=>$r->SampleName,
						'BatchCode'=>$r->BatchCode,'LabNo'=>$r->LabNo);
					}
					
					
					$data=(object)array('allrirs'=>$allrirs,);
					$this->_sendResponse(200, CJSON::encode($data));
				
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
				case 'editcertformat':	$transaction=$model->dbConnection->beginTransaction();
				try
					{
						
						$model->FormatNo=$data['FormatNo'];
						//$model->CreatedOn=date('Y-m-d H:i:s');
						$model->save(false);
						
						foreach($data['delcfd'] as $e)
						{
							$certformsec=Certformsec::model()->find(array('condition'=>'CFId =:cfid AND FSID=:fsid',
												'params'=>array(':cfid'=>$model->Id,':fsid'=>$e)));
							if(!empty($certformsec))
							{
								$certformsec->delete();
							}								
						}
						
						$cfid=$model->getPrimaryKey();
						
						
						foreach($data['CertFormDetails'] as $d)
						{
							$cfs=Certformsec::model()->find(array('condition'=>'CFId =:cfid AND FSID=:fsid',
												'params'=>array(':cfid'=>$model->Id,':fsid'=>$d['FSID'])));
							if(empty($cfs))
							{								
								$cfs=new Certformsec;
							}
							$cfs->CFId=$cfid;
							$cfs->FSID=$d['FSID'];
							$cfs->save(false);
							
							if(isset($d['Parameters']) )
							{
								foreach($d['Parameters'] as $p)
								{
									
									$cfd=CertFormDetails::model()->find(array('condition'=>'CFSID = :cfsid AND Param=:param',
														'params'=>array(':cfsid'=>$cfs->getPrimaryKey(),':param'=>$p['PId'])));
									if(empty($cfd))
									{									
										$cfd=new CertFormDetails;
										$cfd->CFSID=$cfs->getPrimaryKey();									
									}
									
									$cfd->Param=$p['PId'];
									$cfd->PType=$p['PType'];
									$cfd->IsMajor=isset($p['IsMajor'])?$p['IsMajor']:0;
									$cfd->Min=isset($p['Min'])?$p['Min']:"";
									$cfd->Max=isset($p['Max'])?$p['Max']:"";
									$cfd->save(false);								
									
								}
							}
						}
						
						$transaction->commit();
						$this->_sendResponse(200, CJSON::encode($model->certformsecs));
					}
					catch(Exception $e)
					{
						$transaction->rollback();
						$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
					break;
				
					case 'checkcert':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
							

						$uir=Userapppermission::model()->find(array('condition'=>'UserId =:uid AND SectionId="9"',
							'params'=>array(':uid'=>$data['CheckedBy']),));
							if(!empty($uir))
							{
								if($uir->Ch)
								{
									$model->CheckedBy=$data['CheckedBy'];	
								}
							}					

							
							// $uir=Userinroles::model()->find(array('condition'=>'UserId =:uid ',
							// 'params'=>array(':uid'=>$data['ApprovedBy']),));
							// if(!empty($uir))
							// {
								// if($uir->RoleId==='7')
								// {
									// $model->CheckedBy=$data['CheckedBy'];	
								// }
							// }		
							
						
						$model->save(false);
						
												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($data));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($data));
					}
			   
							break;		
				
				
					case 'approvecert':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						
						$uir=Userapppermission::model()->find(array('condition'=>'UserId =:uid AND SectionId="9"',
							'params'=>array(':uid'=>$data['ApprovedBy']),));
							if(!empty($uir))
							{
								if($uir->A)
								{
									$model->ApprovedBy=$data['ApprovedBy'];	
								}
							}					
									
												
						
						$model->save(false);
						
						/*
						$c=$model;
						$certbasic="";
						$chemicals=array();
						$chobs=array();
						$certelements=array();
						$creference="";
						
					
						
						$preparedby="";
							$preparedsign="";
							if(!empty($c->PreparedBy))
							{
								if(count($c->preparedBy->usersignuploads)>0)
								{
								$filename1=str_replace(' ', '%20', Yii::app()->params['imgurl']."signuploads/files/".$c->preparedBy->usersignuploads[0]->name);
								$preparedsign=$filename1;
								}
								$preparedby=$c->preparedBy->FName." ".$c->preparedBy->LName;	
							}		
							
							$approvedby="";
							$approvedsign="";
							if(!empty($c->ApprovedBy))
							{
								if(count($c->approvedBy->usersignuploads)>0)
								{
								$filename2=str_replace(' ', '%20', Yii::app()->params['imgurl']."signuploads/files/".$c->approvedBy->usersignuploads[0]->name);
								
							$approvedsign=empty($c->approvedBy->usersignuploads)?"":$filename2;
								}
							$approvedby=$c->approvedBy->FName." ".$c->approvedBy->LName;	
							}				

						$checkedby="";
							$checkedsign="";
							if(!empty($c->CheckedBy))
							{
								if(count($c->checkedBy->usersignuploads)>0)
								{
								$filename3=str_replace(' ', '%20', Yii::app()->params['imgurl']."signuploads/files/".$c->checkedBy->usersignuploads[0]->name);
								
							$checkedsign=empty($c->checkedBy->usersignuploads)?"":$filename3;
								}
							$checkedby=$c->checkedBy->FName." ".$c->checkedBy->LName;	
							}		
						
						$certests=[];
							foreach($c->certtests as $ct)
							{
								
								$rtest=Rirtestdetail::model()->findByPk($ct->RirTestId);
								$obbasic=(object)array();
									$cond=array();
									
									$certestobs=$ct->certtestobs(array('condition'=>'ShowInCert=1'));
								foreach($certestobs as $ob)
								{
									$param=Testobsparams::model()->findByPk($ob->PId);
									$testmethod="";
									if(!empty($param))
									{
									$cond[]=(object)array('CTId'=>$ob->CTId,'PId'=>$ob->PId,'Min'=>$ob->Min,'Max'=>$ob->Max,
									'TMId'=>$ob->TMId,'TestMethod'=>$testmethod,'ShowInCert'=>$ob->ShowInCert,
									'Param'=>$param->Parameter,'Symbol'=>$param->PSymbol);
									}
								}
								
								$testresults=empty($ct->certtestobs)?0:count($ct->certtestobs[0]->certtestobsvalues);
										$observations=[];
										for($k=0; $k<$testresults;$k++)
										{
											$obsvalue=[];
											$certestobs=$ct->certtestobs(array('ShowInCert=1'));
											foreach($certestobs as $e)
											{
												if($e->ShowInCert===1)
												{
												$obsvalue[]=(object)array('Id'=>$e->Id,
													'PId'=>$e->PId,'PUnit'=>$e->p->PUnit,'PDType'=>$e->p->PDType,
													'TestId'=>$ct->TestId,'ShowInCert'=>$e->ShowInCert,
													'PSym'=>$e->p->PSymbol,'TMId'=>$e->TMId,'Param'=>$e->p->Parameter,
													'Value'=>$e->certtestobsvalues[$k]->Value);
												}
											
											}
											if($ct->certtestobs[0]->certtestobsvalues[$k]->ShowInCert===1)
												{
											$observations[$k]=(object)array('TestNo'=>$ct->certtestobs[0]->certtestobsvalues[$k]->TestNo,
											'ObsNo'=>$ct->certtestobs[0]->certtestobsvalues[$k]->ObsNo,
											'BatchCode'=>$rtest->rIR->BatchCode,'LabNo'=>$rtest->rIR->LabNo,
											'ShowInCert'=>$ct->certtestobs[0]->certtestobsvalues[$k]->ShowInCert,'ObsValues'=>$obsvalue);
												}
										}
								
								
								$pos=(object)array('basic'=>$obbasic,'observations'=>$observations,'conditions'=>$cond);	
								
								
								$test=Tests::model()->find(array('condition'=>'Id =:key','params'=>array(':key'=>$ct->TestId)));
								$certests[]=(object)['TestId'=>$ct->TestId,'TestName'=>$test->TestName,'Standard'=>$ct->Standard,'ShowInCert'=>$ct->ShowInCert,'BatchCode'=>$rtest->rIR->BatchCode,'LabNo'=>$rtest->rIR->LabNo,
								'Remark'=>$ct->Remark,'obbasic'=>$pos];
							}
							
							
							$certimg=Certattachments::model()->find(array('condition'=>'certid=:cert','params'=>array(':cert'=>$c->getPrimaryKey())));
							
							$rtd=Rirtestdetail::model()->findByPk($c->certtests[0]->RirTestId);
							
						$brcp=Receiptir::model()->findByPk($rtd->RIRId);
							
							
$url=Yii::app()->params['base-url']."pdf/certificate/".$c->TCNo.".pdf";
$qrimg=Yii::app()->params['base-url']."pdf/certificate/".$c->TCNo.".png";

Yii::app()->qrcode->create($url,yii::app()->basePath."/../../pdf/certificate/".$c->TCNo.".png");
						
								
						$basic=(object)array('Id'=>$c->Id,'CertDate'=>$c->CertDate,'obbasic'=>$obbasic,'certimg'=>$certimg,
						'TCNo'=>$c->TCNo,'CheckedSign'=>$checkedsign,'CheckedBy'=>$checkedby,
						'SampleName'=>$brcp->SampleName,'Industry'=>implode(" - ",array_reverse( MyFunctions::getParentCat($brcp->IndId))),	'SampleCondition'=>$brcp->SampleCondition,	'NoOfSamples'=>$brcp->NoOfSamples,					
						'Customer'=>$c->customer->Name,'certests'=>$certests,'TCFormat'=>$c->TCFormat,'QRImg'=>$qrimg,
						'PreparedBy'=>$preparedby,'PreparedSign'=>$preparedsign,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby);
						
						$pdfmsg=$this->getcertui($basic);
						
						
						
						


						
								  require_once(yii::app()->basePath . '/extensions/tcpdf/tcpdf.php');
				   $pdf = new 
  MYPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT, true,'UTF-8',false);
 $pdf->AddPage();

 $pdf->writeHTML($pdfmsg, true, false, true, false, '');
  $pdf->SetPrintHeader(false);
 $pdf->SetPrintFooter(false);
 $pdf->Output ( yii::app()->basePath."/../../pdf/certificate/".$c->TCNo.".pdf", 'F' ); 
 
 //$file_path = yii::app()->basePath."/../pdf/".$i->TCNo.".pdf";   
 */
 
 
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode("approved"));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($data));
					}
			   
							break;		
				
				
				case 'certextraupdate':
					$transaction=$model->dbConnection->beginTransaction();
                
                        try
                            {     
								
								foreach($data['Allextsections'] as $ex)
								{
									if($ex['Selected']==="false")
									{
										 $ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section=:sec',
                                    'params'=>array(':cbid'=>$_GET['id'],':sec'=>$ex['KeyWord']),));
										if(!empty($ct))
                                        {
                                            $ct->delete();
                                        }
									}
									
								}



							
                                $nonmetallic=$data['NM'];
								if(!empty($nonmetallic) && $data['Allextsections'][0]['Selected']==='true' )
                                {
									                                
									if(!empty($nonmetallic['Observations']))
									{
																					
										if(!empty($nonmetallic['Certtest']['Id']))
										{
											$ct=Certtest::model()->findByPk($nonmetallic['Certtest']['Id']);    
										}
										else
										{
											$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="NM"',
											'params'=>array(':cbid'=>$_GET['id']),));
											if(empty($ct))
											{
											$ct=new Certtest;
											}
										}
                                
										if(!empty($nonmetallic['Certtest']))
										{
											foreach($nonmetallic['Certtest'] as $var=>$value)
											  {
												// Does the model have this attribute? If not raise an error
												if($ct->hasAttribute($var))
													$ct->$var = $value;
											  }		
										}									  
								
										$ct->Section="NM";
										$ct->CertBasicId=$_GET['id'];
										$ct->save(false);
                                
										if(!empty($nonmetallic['delobservations']))
										{
											foreach($nonmetallic['delobservations'] as $db)
											{
												$dob=Certnonmetallic::model()->findByPk($db['Id']);
												if(!empty($dob))
												{
													$dob->delete();
												}
											}
										}
											
											$i=1;
											foreach($nonmetallic['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certnonmetallic;
														$cob->CertTestId=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certnonmetallic::model()->findByPk($cb['Id']);
												}
												$cob->SeqNo=$i;
												$cob->HeatNo=empty($cb['HeatNo'])?"":$cb['HeatNo'];
												$cob->Parameter=$cb['Parameter'];
												$cob->Required=$cb['Required'];
												$cob->Observation=$cb['Observation'];
												$cob->Remark=$cb['Remark'];
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
									}
										
								}
								
								  $microex=$data['ME'];
								if(!empty($microex) && $data['Allextsections'][1]['Selected']==='true' )
                                {
									                                
									if(!empty($microex['Observations']))
									{
																					
										if(!empty($microex['Certtest']['Id']))
										{
											$ct=Certtest::model()->findByPk($microex['Certtest']['Id']);    
										}
										else
										{
											$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="ME"',
											'params'=>array(':cbid'=>$_GET['id']),));
											if(empty($ct))
											{
											$ct=new Certtest;
											}
										}
                                
										if(!empty($microex['Certtest']))
										{
											foreach($microex['Certtest'] as $var=>$value)
											  {
												// Does the model have this attribute? If not raise an error
												if($ct->hasAttribute($var))
													$ct->$var = $value;
											  }		
										}									  
								
										$ct->Section="ME";
										$ct->CertBasicId=$_GET['id'];
										$ct->save(false);
                                
										if(!empty($microex['delobservations']))
										{
											foreach($microex['delobservations'] as $db)
											{
												$dob=Certmicroexamination::model()->findByPk($db['Id']);
												if(!empty($dob))
												{
													$dob->delete();
												}
											}
										}
											
											$i=1;
											foreach($microex['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certmicroexamination;
														$cob->CertTestId=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certmicroexamination::model()->findByPk($cb['Id']);
												}
												$cob->SeqNo=$i;
												$cob->BatchCode=empty($cb['BatchCode'])?"":$cb['BatchCode'];
												$cob->Parameter=$cb['Parameter'];
												$cob->Required=$cb['Required'];
												$cob->Observation=$cb['Observation'];
												$cob->Remark=$cb['Remark'];
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
									}
										
								}
								
								 $mpi=$data['MPI'];
								if(!empty($mpi) && $data['Allextsections'][2]['Selected']==='true' )
                                {
									                                
									if(!empty($mpi['Observations']))
									{
																					
										if(!empty($mpi['Certtest']['Id']))
										{
											$ct=Certtest::model()->findByPk($mpi['Certtest']['Id']);    
										}
										else
										{
											$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="MPI"',
											'params'=>array(':cbid'=>$_GET['id']),));
											if(empty($ct))
											{
											$ct=new Certtest;
											}
										}
                                
										if(!empty($mpi['Certtest']))
										{
											foreach($mpi['Certtest'] as $var=>$value)
											  {
												// Does the model have this attribute? If not raise an error
												if($ct->hasAttribute($var))
													$ct->$var = $value;
											  }		
										}									  
								
										$ct->Section="MPI";
										$ct->CertBasicId=$_GET['id'];
										$ct->save(false);
                                
										if(!empty($mpi['delobservations']))
										{
											foreach($mpi['delobservations'] as $db)
											{
												$dob=Certmpidetails::model()->findByPk($db['Id']);
												if(!empty($dob))
												{
													$dob->delete();
												}
											}
										}
											
											$i=1;
											foreach($mpi['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certmpidetails;
														$cob->CertTestId=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certmpidetails::model()->findByPk($cb['Id']);
												}
												$cob->SeqNo=$i;
												$cob->Parameter=$cb['Parameter'];
												$cob->Required=$cb['Required'];
												$cob->Observation=$cb['Observation'];
												$cob->Remark=$cb['Remark'];
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
									}
										
								}
								
								 $hext=$data['H'];
								if(!empty($hext) && $data['Allextsections'][3]['Selected']==='true' )
                                {
									                                
									if(!empty($hext['Observations']))
									{
																					
										if(!empty($hext['Certtest']['Id']))
										{
											$ct=Certtest::model()->findByPk($hext['Certtest']['Id']);    
										}
										else
										{
											$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="H"',
											'params'=>array(':cbid'=>$_GET['id']),));
											if(empty($ct))
											{
											$ct=new Certtest;
											}
										}
                                
										if(!empty($hext['Certtest']))
										{
											foreach($hext['Certtest'] as $var=>$value)
											  {
												// Does the model have this attribute? If not raise an error
												if($ct->hasAttribute($var))
													$ct->$var = $value;
											  }		
										}									  
								
										$ct->Section="H";
										$ct->CertBasicId=$_GET['id'];
										$ct->save(false);
                                
										if(!empty($hext['delobservations']))
										{
											foreach($hext['delobservations'] as $db)
											{
												$dob=Certexthardness::model()->findByPk($db['Id']);
												if(!empty($dob))
												{
													$dob->delete();
												}
											}
										}
											
											$i=1;
											
												$cb=$hext['Observations'];
												if(empty($cb['Id']))
												{
												   
														$cob=new Certexthardness;
														$cob->CertTestId=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certexthardness::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												
										
											
									}
										
								}
								
								
								  $nondes=$data['ND'];
								if(!empty($nondes) && $data['Allextsections'][4]['Selected']==='true' )
                                {
									                                
									if(!empty($nondes['Observations']))
									{
																					
										if(!empty($nondes['Certtest']['Id']))
										{
											$ct=Certtest::model()->findByPk($nondes['Certtest']['Id']);    
										}
										else
										{
											$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="ND"',
											'params'=>array(':cbid'=>$_GET['id']),));
											if(empty($ct))
											{
											$ct=new Certtest;
											}
										}
                                
										if(!empty($nondes['Certtest']))
										{
											foreach($nondes['Certtest'] as $var=>$value)
											  {
												// Does the model have this attribute? If not raise an error
												if($ct->hasAttribute($var))
													$ct->$var = $value;
											  }		
										}									  
								
										$ct->Section="ND";
										$ct->CertBasicId=$_GET['id'];
										$ct->save(false);
                                
										if(!empty($nondes['delobservations']))
										{
											foreach($nondes['delobservations'] as $db)
											{
												$dob=Certnondestructive::model()->findByPk($db['Id']);
												if(!empty($dob))
												{
													$dob->delete();
												}
											}
										}
											
											$i=1;
											foreach($nondes['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certnondestructive;
														$cob->CertTestId=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certnondestructive::model()->findByPk($cb['Id']);
												}
												$cob->SeqNo=$i;
												$cob->Parameter=$cb['Parameter'];
												$cob->Required=$cb['Required'];
												$cob->Observation=$cb['Observation'];
												$cob->Remark=$cb['Remark'];
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
									}
										
								}
								
								 $streat=$data['ST'];
								if(!empty($streat) && $data['Allextsections'][5]['Selected']==='true' )
                                {
									                                
									if(!empty($streat['Observations']))
									{
																					
										if(!empty($streat['Certtest']['Id']))
										{
											$ct=Certtest::model()->findByPk($streat['Certtest']['Id']);    
										}
										else
										{
											$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="ST"',
											'params'=>array(':cbid'=>$_GET['id']),));
											if(empty($ct))
											{
											$ct=new Certtest;
											}
										}
                                
										if(!empty($streat['Certtest']))
										{
											foreach($streat['Certtest'] as $var=>$value)
											  {
												// Does the model have this attribute? If not raise an error
												if($ct->hasAttribute($var))
													$ct->$var = $value;
											  }		
										}									  
								
										$ct->Section="ST";
										$ct->CertBasicId=$_GET['id'];
										$ct->save(false);
                                
										if(!empty($streat['delobservations']))
										{
											foreach($streat['delobservations'] as $db)
											{
												$dob=Certsurfacetreat::model()->findByPk($db['Id']);
												if(!empty($dob))
												{
													$dob->delete();
												}
											}
										}
											
											$i=1;
											foreach($streat['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certsurfacetreat;
														$cob->CertTestId=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certsurfacetreat::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
									}
										
								}
								
								 $stdel=$data['STDELTA'];
								if(!empty($stdel) && $data['Allextsections'][6]['Selected']==='true' )
                                {
									                                
									if(!empty($stdel['Observations']))
									{
																					
										if(!empty($stdel['Certtest']['Id']))
										{
											$ct=Certtest::model()->findByPk($stdel['Certtest']['Id']);    
										}
										else
										{
											$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="STDELTA"',
											'params'=>array(':cbid'=>$_GET['id']),));
											if(empty($ct))
											{
											$ct=new Certtest;
											}
										}
                                
										if(!empty($stdel['Certtest']))
										{
											foreach($stdel['Certtest'] as $var=>$value)
											  {
												// Does the model have this attribute? If not raise an error
												if($ct->hasAttribute($var))
													$ct->$var = $value;
											  }		
										}									  
								
										$ct->Section="STDELTA";
										$ct->CertBasicId=$_GET['id'];
										$ct->save(false);
                                
										if(!empty($stdel['delobservations']))
										{
											foreach($stdel['delobservations'] as $db)
											{
												$dob=Certsurfacetreatdelta::model()->findByPk($db['Id']);
												if(!empty($dob))
												{
													$dob->delete();
												}
											}
										}
											
											$i=1;
											
												$cb=$stdel['Observations'];
												if(empty($cb['Id']))
												{
												   
														$cob=new Certsurfacetreatdelta;
														$cob->CertTestId=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certsurfacetreatdelta::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											
											
									}
										
								}
								
								 $stda=$data['STDA'];
								if(!empty($stda) && $data['Allextsections'][7]['Selected']==='true' )
                                {
									                                
									if(!empty($stda['Observations']))
									{
																					
										if(!empty($stda['Certtest']['Id']))
										{
											$ct=Certtest::model()->findByPk($stda['Certtest']['Id']);    
										}
										else
										{
											$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="STDA"',
											'params'=>array(':cbid'=>$_GET['id']),));
											if(empty($ct))
											{
											$ct=new Certtest;
											}
										}
                                
										if(!empty($stda['Certtest']))
										{
											foreach($stda['Certtest'] as $var=>$value)
											  {
												// Does the model have this attribute? If not raise an error
												if($ct->hasAttribute($var))
													$ct->$var = $value;
											  }		
										}									  
								
										$ct->Section="STDA";
										$ct->CertBasicId=$_GET['id'];
										$ct->save(false);
                                
										if(!empty($stda['delobservations']))
										{
											foreach($stda['delobservations'] as $db)
											{
												$dob=Certsurfacetreatdacro::model()->findByPk($db['Id']);
												if(!empty($dob))
												{
													$dob->delete();
												}
											}
										}
											
											$i=1;
											
												$cb=$stda['Observations'];
												if(empty($cb['Id']))
												{
												   
														$cob=new Certsurfacetreatdacro;
														$cob->CertTestId=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certsurfacetreatdacro::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											
											
									}
										
								}
								
								 $sthot=$data['STHOT'];
								if(!empty($sthot) && $data['Allextsections'][8]['Selected']==='true' )
                                {
									                                
									if(!empty($sthot['Observations']))
									{
																					
										if(!empty($sthot['Certtest']['Id']))
										{
											$ct=Certtest::model()->findByPk($sthot['Certtest']['Id']);    
										}
										else
										{
											$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="STHOT"',
											'params'=>array(':cbid'=>$_GET['id']),));
											if(empty($ct))
											{
											$ct=new Certtest;
											}
										}
                                
										if(!empty($sthot['Certtest']))
										{
											foreach($sthot['Certtest'] as $var=>$value)
											  {
												// Does the model have this attribute? If not raise an error
												if($ct->hasAttribute($var))
													$ct->$var = $value;
											  }		
										}									  
								
										$ct->Section="STHOT";
										$ct->CertBasicId=$_GET['id'];
										$ct->save(false);
                                
										if(!empty($sthot['delobservations']))
										{
											foreach($sthot['delobservations'] as $db)
											{
												$dob=Certsurfacetreathot::model()->findByPk($db['Id']);
												if(!empty($dob))
												{
													$dob->delete();
												}
											}
										}
											
											$i=1;
											
												$cb=$sthot['Observations'];
												if(empty($cb['Id']))
												{
												   
														$cob=new Certsurfacetreathot;
														$cob->CertTestId=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certsurfacetreathot::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											
											
									}
										
								}
								
								 $stp=$data['STP'];
								if(!empty($stp) && $data['Allextsections'][9]['Selected']==='true' )
                                {
									                                
									if(!empty($stp['Observations']))
									{
																					
										if(!empty($stp['Certtest']['Id']))
										{
											$ct=Certtest::model()->findByPk($stp['Certtest']['Id']);    
										}
										else
										{
											$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="STP"',
											'params'=>array(':cbid'=>$_GET['id']),));
											if(empty($ct))
											{
											$ct=new Certtest;
											}
										}
                                
										if(!empty($stp['Certtest']))
										{
											foreach($stp['Certtest'] as $var=>$value)
											  {
												// Does the model have this attribute? If not raise an error
												if($ct->hasAttribute($var))
													$ct->$var = $value;
											  }		
										}									  
								
										$ct->Section="STP";
										$ct->CertBasicId=$_GET['id'];
										$ct->save(false);
                                
										if(!empty($stp['delobservations']))
										{
											foreach($stp['delobservations'] as $db)
											{
												$dob=Certsurfacetreatplat::model()->findByPk($db['Id']);
												if(!empty($dob))
												{
													$dob->delete();
												}
											}
										}
											
											$i=1;
											
												$cb=$stp['Observations'];
												if(empty($cb['Id']))
												{
												   
														$cob=new Certsurfacetreatplat;
														$cob->CertTestId=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certsurfacetreatplat::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											//}
											
									}
										
								}
								
								
								 $othdet=$data['OD'];
								if(!empty($othdet) && $data['Allextsections'][10]['Selected']==='true' )
                                {
									                                
									if(!empty($othdet['Observations']))
									{
																					
										if(!empty($othdet['Certtest']['Id']))
										{
											$ct=Certtest::model()->findByPk($othdet['Certtest']['Id']);    
										}
										else
										{
											$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="OD"',
											'params'=>array(':cbid'=>$_GET['id']),));
											if(empty($ct))
											{
											$ct=new Certtest;
											}
										}
                                
										if(!empty($othdet['Certtest']))
										{
											foreach($othdet['Certtest'] as $var=>$value)
											  {
												// Does the model have this attribute? If not raise an error
												if($ct->hasAttribute($var))
													$ct->$var = $value;
											  }		
										}									  
								
										$ct->Section="OD";
										$ct->CertBasicId=$_GET['id'];
										$ct->save(false);
                                
										if(!empty($othdet['delobservations']))
										{
											foreach($othdet['delobservations'] as $db)
											{
												$dob=Certotherdetails::model()->findByPk($db['Id']);
												if(!empty($dob))
												{
													$dob->delete();
												}
											}
										}
											
											$i=1;
											foreach($othdet['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certotherdetails;
														$cob->CertTestId=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certotherdetails::model()->findByPk($cb['Id']);
												}
												$cob->SeqNo=$i;
												$cob->Parameter=$cb['Parameter'];
												$cob->Required=$cb['Required'];
												$cob->Observation=$cb['Observation'];
												$cob->Remark=$cb['Remark'];
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
									}
										
								}
								
								
								
								
								
                                    $transaction->commit();
                                    $this->_sendResponse(200, CJSON::encode($data));
                                        
                            }
                            catch(Exception $e)
                            {
                                $transaction->rollback();
					
							}			
				$this->_sendResponse(401, CJSON::encode("not saved1"));
				
				break;
				
				
				
			 case 'certchemupdate':
							$transaction=$model->dbConnection->beginTransaction();
								try
									{
											$newallrirs=$data['Rirs'];
											$oldlabnos=array();
											$newlabnos=array();
											
											$oldallrirs=Certrirs::model()->findAll(array('condition'=>'CertBasicId=:cbid',
												'params'=>array(':cbid'=>$_GET['id']),));
												
											foreach($oldallrirs as $o)
											{
												$oldlabnos[]=$o->LabNo;
											}											
											
											foreach($newallrirs as $o)
											{
												$newlabnos[]=$o['LabNo'];
											}		
											
											
											

											$dellabnos=array_values(array_diff($oldlabnos,$newlabnos));	
											if(!empty($dellabnos))
											{
												foreach($dellabnos as $d)
												{
												foreach($model->certtests as $t)
												{
												
												//--chemical delete
												$cob=Certchobbasic::model()->find(array('condition'=>'CertTestId=:ctid AND LabNo=:ln',
												'params'=>array(':ctid'=>$t->Id,':ln'=>$d),));
												
												if(!empty($cob))
												{
													$num=$cob->delete();
												}
												
												//--tensile delete
												$cob=Certtobbasic::model()->find(array('condition'=>'CertTestId=:ctid AND LabNo=:ln',
												'params'=>array(':ctid'=>$t->Id,':ln'=>$d),));
												
												if(!empty($cob))
												{
													$num=$cob->delete();
												}
												
												//--impact delete
												$cob=Certiobservations::model()->find(array('condition'=>'CertTestId=:ctid AND LabNo=:ln',
												'params'=>array(':ctid'=>$t->Id,':ln'=>$d),));
												
												if(!empty($cob))
												{
													$num=$cob->delete();
												}
												
												//--hardness delete
												$cob=Certhobbasic::model()->find(array('condition'=>'CertTestId=:ctid AND LabNo=:ln',
												'params'=>array(':ctid'=>$t->Id,':ln'=>$d),));
												
												if(!empty($cob))
												{
													$num=$cob->delete();
												}
												
												}
												
												$dcr=Certrirs::model()->find(array('condition'=>'CertBasicId=:cbid AND LabNo=:ln',
												'params'=>array(':cbid'=>$_GET['id'],':ln'=>$d),));
												if(!empty($dcr))
												{
													//$this->_sendResponse(401, CJSON::encode($dcr));
													$num=$dcr->delete();
												}
												
												}
											}
											
											
											$rirs=array();
											foreach($newlabnos as $o)
											{
												$r=Receiptir::model()->find(array('condition'=>'LabNo=:ln',
												'params'=>array(':ln'=>$o),));
												$rirs[]=$r;
											}		
											
											
											foreach($rirs as $r)
											{
												$fcr=Certrirs::model()->find(array('condition'=>'RirId=:rid AND CertBasicId=:cbid',
												'params'=>array(':rid'=>$r['Id'],':cbid'=>$_GET['id']),));
												
												if(empty($fcr))
												{
													$fcr=new Certrirs;
													$fcr->CertBasicId=$_GET['id'];
													$fcr->LabNo=$r['LabNo'];
													$fcr->RirId=$r['Id'];
													$fcr->save(false);
												}
												
												
											}
											
											
									/*--------Chemical--------------*/		
																						
										$chems=$data['Chemical'];
										if(!empty($chems))
										{
											foreach($chems as $chem)
											{
												if(!empty($chem['Certtest']['Id']))
												{
													$ct=Certtest::model()->findByPk($chem['Certtest']['Id']);	
												}
												else
												{
													$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="chemical" AND RefId=:rfid',
													'params'=>array(':cbid'=>$_GET['id'],':rfid'=>$chem['Certtest']['RefId']),));
													if(empty($ct))
													{
													$ct=new Certtest;
													}
												}
											
												$ct->Section="chemical";
												$ct->CertBasicId=$_GET['id'];
												$ct->References=$chem['Certtest']['References'];
												$ct->RefId=$chem['Certtest']['RefId'];
												$ct->RefExtra=empty($chem['Certtest']['RefExtra'])?"":$chem['Certtest']['RefExtra'];
												$ct->Ref=empty($chem['Certtest']['Ref'])?"":$chem['Certtest']['Ref'];
												$ct->Remark=empty($chem['Certtest']['Remark'])?"":$chem['Certtest']['Remark'];
												$ct->save(false);
											
											
											
											if(!empty($chem['Chobbasic']))
											{
												foreach($chem['Certelements'] as $e)
												{
													if(empty($e['Id']))
													{
														$cce=Certchelements::model()->find(array('condition'=>'CertTestId=:ctid AND Element=:el',
												'params'=>array(':ctid'=>$ct->getPrimaryKey(),':el'=>$e['Element']),));
														if(empty($cce))
														{
																$cce=new Certchelements;
																$cce->CertTestId=$ct->getPrimaryKey();
														}
													}
													else
													{
														$cce=Certchelements::model()->findByPk($e['Id']);
													}
													
													$cce->Element=$e['Element'];
													$cce->ElementId=$e['ElementId'];
													$cce->IsMajor=$e['IsMajor'];
													$cce->Max=$e['Max'];
													$cce->Min=$e['Min'];
													$cce->save(false);
												}
												
											
									
												foreach($chem['Chobbasic'] as $cb)
												{
													// $cb=$chem;
													if(empty($cb['Id']))
													{
														$cob=Certchobbasic::model()->find(array('condition'=>'CertTestId=:ctid AND LabNo=:ln AND TestId=:tid ',
												'params'=>array(':ctid'=>$ct->getPrimaryKey(),':ln'=>$cb['LabNo'],':tid'=>$cb['TestId']),));
														if(empty($cob))
														{
															$cob=new Certchobbasic;
															$cob->CertTestId=$ct->getPrimaryKey();
														}
														
													}
													else
													{
														$cob=Certchobbasic::model()->findByPk($cb['Id']);
													}
													$cob->HeatNo=empty($cb['HeatNo'])?"":$cb['HeatNo'];
													$cob->LabNo=$cb['LabNo'];
														$cob->TestId=$cb['TestId'];
														$cob->SeqNo=empty($cb['SeqNo'])?"":$cb['SeqNo'];
													$cob->BatchCode=$cb['BatchCode'];
													$cob->Remark=$cb['Remark'];
													$cob->ShowInCert=$cb['ShowInCert'];
													$cob->LastModified=date('Y-m-d H:i:s');
													$cob->save(false);
													
													if($cob->ShowInCert==="true")
													{
														$ct->ShowInCert="true";
														$ct->save(false);
													}
													
													$i=0;
													foreach($cb['Observations'] as $o)
													{
														$ceid=Certchelements::model()->find(array('condition'=>'CertTestId=:ctid AND Element=:el',
										 'params'=>array(':ctid'=>$ct->getPrimaryKey(),':el'=>$o['Element']),));
														if(empty($o['Id']))
														{
															
															
															if(!empty($ceid))
															{
															$cobv=Certchobservations::model()->find(array('condition'=>'ChObbasicId=:chbid AND CertCheleId=:elid',
												'params'=>array(':chbid'=>$cob->getPrimaryKey(),':elid'=>$ceid->Id),));
															}
														if(empty($cobv))
														{
															$cobv=new Certchobservations;
															$cobv->ChObbasicId=$cob->getPrimaryKey();
														}
															
															
															
														}
														else
														{
															$cobv=Certchobservations::model()->findByPk($o['Id']);
															
														}
														
															
															
																 
															 if(!empty($ceid))
															 {
																
																	$cobv->CertCheleId=$ceid->Id;
																			$cobv->Value=$o['Value'];
																			$cobv->save(false);
															 }
															 $i++;
													}
											}
												
											}
											else
											{
												$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="chemical" AND RefId=:rfid',
													'params'=>array(':cbid'=>$_GET['id'],':rfid'=>$chem['Certtest']['RefId']),));
												if(!empty($ct))
												{
													$ct->delete();
												}
										
											}
										}
										}
									/*------------------Mechanical-Tensile------------------*/	
																					
										$tensile=$data['Tensile'];
										if(!empty($tensile['Certtest']['RefId']))
										{
											
											if(!empty($tensile['Certtest']['Id']))
											{
												$ct=Certtest::model()->findByPk($tensile['Certtest']['Id']);	
											}
											else
											{
												$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="tensile"',
												'params'=>array(':cbid'=>$_GET['id']),));
												if(empty($ct))
												{
												$ct=new Certtest;
												}
											}
											
											$ct->Section="tensile";
											$ct->CertBasicId=$_GET['id'];
											$ct->References=$tensile['Certtest']['References'];
											$ct->RefId=$tensile['Certtest']['RefId'];
											$ct->RefExtra=empty($tensile['Certtest']['RefExtra'])?"":$tensile['Certtest']['RefExtra'];
											$ct->Ref=empty($tensile['Certtest']['Ref'])?"":$tensile['Certtest']['Ref'];
											$ct->Remark=empty($tensile['Certtest']['Remark'])?"":$tensile['Certtest']['Remark'];
											$ct->ShowInCert="false";
											$ct->save(false);
											
											
											
											if(!empty($tensile['Tobbasic']))
											{
												foreach($tensile['Certtparams'] as $e)
												{
													if(empty($e['Id']))
													{
														$ctp=Certtparams::model()->find(array('condition'=>'CertTestId=:ctid AND Parameter=:el',
												'params'=>array(':ctid'=>$ct->getPrimaryKey(),':el'=>$e['Parameter']),));
														if(empty($ctp))
														{
																$ctp=new Certtparams;
																$ctp->CertTestId=$ct->getPrimaryKey();
														}
													}
													else
													{
														$ctp=Certtparams::model()->findByPk($e['Id']);
													}
													$ctp->ParamId=$e['ParamId'];
													$ctp->Parameter=$e['Parameter'];
													$ctp->IsMajor=$e['IsMajor'];
													$ctp->Max=$e['Max'];
													$ctp->Min=$e['Min'];
													$ctp->save(false);
												}
												
											
											
												foreach($tensile['Tobbasic'] as $cb)
												{
													if(empty($cb['Id']))
													{
														$cob=Certtobbasic::model()->find(array('condition'=>'CertTestId=:ctid AND LabNo=:ln AND TestId=:tid AND SeqNo=:sn',
												'params'=>array(':ctid'=>$ct->getPrimaryKey(),':ln'=>$cb['LabNo'],':tid'=>$cb['TestId'],':sn'=>$cb['SeqNo']),));
														if(empty($cob))
														{
															$cob=new Certtobbasic;
															$cob->CertTestId=$ct->getPrimaryKey();
														}
														
													}
													else
													{
														$cob=Certtobbasic::model()->findByPk($cb['Id']);
													}
													$cob->HeatNo=empty($cb['HeatNo'])?"":$cb['HeatNo'];
													$cob->LabNo=$cb['LabNo'];
													$cob->TestId=$cb['TestId'];
														$cob->SeqNo=empty($cb['SeqNo'])?"":$cb['SeqNo'];
													$cob->BatchCode=$cb['BatchCode'];
													$cob->Remark=$cb['Remark'];
													$cob->ShowInCert=$cb['ShowInCert'];
													$cob->LastModified=date('Y-m-d H:i:s');
													$cob->save(false);
													
													if($cob->ShowInCert==="true")
													{
														$ct->ShowInCert="true";
														$ct->save(false);
													}
													
													$i=0;
													foreach($cb['Observations'] as $o)
													{
														$ceid=Certtparams::model()->find(array('condition'=>'CertTestId=:ctid AND Parameter=:el',
										 'params'=>array(':ctid'=>$ct->getPrimaryKey(),':el'=>$o['Parameter']),));
														if(empty($o['Id']))
														{
															
															
															if(!empty($ceid))
															{
															$cobv=Certtobservations::model()->find(array('condition'=>'TObbasicId=:chbid AND CertTparamId=:elid',
												'params'=>array(':chbid'=>$cob->getPrimaryKey(),':elid'=>$ceid->Id),));
															}
														if(empty($cobv))
														{
															$cobv=new Certtobservations;
															$cobv->TObbasicId=$cob->getPrimaryKey();
														}
															
															
															
														}
														else
														{
															$cobv=Certtobservations::model()->findByPk($o['Id']);
															
														}
														
															
															
																 
															 if(!empty($ceid))
															 {
																
																	$cobv->CertTparamId=$ceid->Id;
																			$cobv->Value=$o['Value'];
																			$cobv->save(false);
															 }
															 $i++;
													}
												}
												
											}	
										}
										else
										{
											$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="tensile"',
												'params'=>array(':cbid'=>$_GET['id']),));
												if(!empty($ct))
												{
													$ct->delete();
												}
										}
										/*------------------Mechanical-Impact------------------*/	
																					
										$impact=$data['Impact'];
										if(!empty($impact['Certtest']['RefId']))
										{
											if(!empty($impact['Certtest']['Id']))
											{
												$ct=Certtest::model()->findByPk($impact['Certtest']['Id']);	
											}
											else
											{
												$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="impact"',
												'params'=>array(':cbid'=>$_GET['id']),));
												if(empty($ct))
												{
												$ct=new Certtest;
												}
											}
											
											if(!empty($impact['Certtest']['References']))
											{
											$ct->Section="impact";
											$ct->CertBasicId=$_GET['id'];
											$ct->References=empty($impact['Certtest']['References'])?"":$impact['Certtest']['References'];
											$ct->RefId=$impact['Certtest']['RefId'];
											$ct->RefExtra=empty($impact['Certtest']['RefExtra'])?"":$impact['Certtest']['RefExtra'];
											$ct->Ref=empty($impact['Certtest']['Ref'])?"":$impact['Certtest']['Ref'];
											$ct->Remark=empty($impact['Certtest']['Remark'])?"":$impact['Certtest']['Remark'];
												$ct->ShowInCert="false";
											$ct->save(false);
											}
											
											
											if(!empty($impact['Observations']))
											{
												
													if(empty($impact['Certiparams']['Id']))
													{
														$cip=Certiparams::model()->find(array('condition'=>'CertTestId=:ctid ',
												'params'=>array(':ctid'=>$ct->getPrimaryKey()),));
														if(empty($cip))
														{
																$cip=new Certiparams;
																$cip->CertTestId=$ct->getPrimaryKey();
														}
													}
													else
													{
														$cip=Certiparams::model()->findByPk($impact['Certiparams']['Id']);
													}
												
													$cip->Temp=$impact['Certiparams']['Temp'];
													$cip->Max=$impact['Certiparams']['Max'];
													$cip->Min=$impact['Certiparams']['Min'];
													$cip->save(false);
												
												
											
											
												foreach($impact['Observations'] as $cb)
												{
													if(empty($cb['Id']))
													{
														$cob=Certiobservations::model()->find(array('condition'=>'CertTestId=:ctid AND LabNo=:ln AND TestId=:tid AND SeqNo=:sn',
												'params'=>array(':ctid'=>$ct->getPrimaryKey(),':ln'=>$cb['LabNo'],':tid'=>$cb['TestId'],':sn'=>$cb['SeqNo']),));
														if(empty($cob))
														{
															$cob=new Certiobservations;
															$cob->CertTestId=$ct->getPrimaryKey();
														}
														
													}
													else
													{
														$cob=Certiobservations::model()->findByPk($cb['Id']);
													}
													$cob->HeatNo=empty($cb['HeatNo'])?"":$cb['HeatNo'];
													$cob->LabNo=$cb['LabNo'];
													$cob->TestId=$cb['TestId'];
														$cob->SeqNo=empty($cb['SeqNo'])?"":$cb['SeqNo'];
													$cob->ShowInCert=$cb['ShowInCert'];
													$cob->BatchCode=$cb['BatchCode'];
													$cob->Value1=$cb['Value1'];
													$cob->Value2=$cb['Value2'];
													$cob->Value3=$cb['Value3'];
													$cob->Avg=$cb['Avg'];
													$cob->Remark=$cb['Remark'];
													$cob->LastModified=date('Y-m-d H:i:s');
													$cob->save(false);
													
													if($cob->ShowInCert==="true")
													{
														$ct->ShowInCert="true";
														$ct->save(false);
													}
												}
												
											}	
											
										}
										else
										{
											$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="impact"',
												'params'=>array(':cbid'=>$_GET['id']),));
												if(!empty($ct))
												{
													$ct->delete();
												}
										}
										
									/*------------------Mechanical-Hardness------------------*/	
																					
										$hardness=$data['Hardness'];
										if(!empty($hardness['Certtest']['RefId']))
										{	
											if(!empty($hardness['Certtest']['Id']))
											{
												$ct=Certtest::model()->findByPk($hardness['Certtest']['Id']);	
											}
											else
											{
												$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="hardness"',
												'params'=>array(':cbid'=>$_GET['id']),));
												if(empty($ct))
												{
												$ct=new Certtest;
												}
											}
											
											$ct->Section="hardness";
											$ct->CertBasicId=$_GET['id'];
											$ct->References=$hardness['Certtest']['References'];
											$ct->RefId=$hardness['Certtest']['RefId'];
											$ct->RefExtra=empty($hardness['Certtest']['RefExtra'])?"":$hardness['Certtest']['RefExtra'];
											$ct->Ref=empty($hardness['Certtest']['Ref'])?"":$hardness['Certtest']['Ref'];
											$ct->Remark=empty($hardness['Certtest']['Remark'])?"":$hardness['Certtest']['Remark'];
												$ct->ShowInCert="false";
											$ct->save(false);
											
											
											
											if(!empty($hardness['Hobbasic']))
											{
												
													$e=$hardness['Certhparams'];
													if(empty($e['Id']))
													{
														$chp=Certhparams::model()->find(array('condition'=>'CertTestId=:ctid',
												'params'=>array(':ctid'=>$ct->getPrimaryKey()),));
														if(empty($chp))
														{
																$chp=new Certhparams;
																$chp->CertTestId=$ct->getPrimaryKey();
														}
													}
													else
													{
														$chp=Certhparams::model()->findByPk($e['Id']);
													}
													
													$chp->Hardness=$e['Hardness'];
													//$ctp->Temp=$e['Temp'];
													$chp->Max=$e['Max'];
													$chp->Min=$e['Min'];
													$chp->HUnit=$e['HUnit'];
													$chp->save(false);
												
												
											
											
												foreach($hardness['Hobbasic'] as $cb)
												{
													if(empty($cb['Id']))
													{
														$cob=Certhobbasic::model()->find(array('condition'=>'CertTestId=:ctid AND LabNo=:ln AND TestId=:tid AND SeqNo=:sn',
												'params'=>array(':ctid'=>$ct->getPrimaryKey(),':ln'=>$cb['LabNo'],':tid'=>$cb['TestId'],':sn'=>$cb['SeqNo']),));
														if(empty($cob))
														{
															$cob=new Certhobbasic;
															$cob->CertTestId=$ct->getPrimaryKey();
														}
														
													}
													else
													{
														$cob=Certhobbasic::model()->findByPk($cb['Id']);
													}
													$cob->HeatNo=empty($cb['HeatNo'])?"":$cb['HeatNo'];
													$cob->LabNo=$cb['LabNo'];
													$cob->TestId=$cb['TestId'];
														$cob->SeqNo=empty($cb['SeqNo'])?"":$cb['SeqNo'];
													$cob->BatchCode=$cb['BatchCode'];
													$cob->Remark=$cb['Remark'];
													$cob->ShowInCert=$cb['ShowInCert'];
													$cob->ShowSurface=$cb['ShowSurface'];
													$cob->ShowCore=$cb['ShowCore'];
													$cob->LastModified=date('Y-m-d H:i:s');
													$cob->save(false);
													
													if($cob->ShowInCert==="true")
													{
														$ct->ShowInCert="true";
														$ct->save(false);
													}
													
													foreach($cb['obs'] as $o)
													{
														
														if(empty($o['Id']))
														{
															 $cobv=Certhobservations::model()->find(array('condition'=>'HObbasicId=:hbid AND HId=:ln',
															'params'=>array(':hbid'=>$cob->getPrimaryKey(),':ln'=>$o['HId']),));
															if(empty($cobv))
															{																
															$cobv=new Certhobservations;
															$cobv->HObbasicId=$cob->getPrimaryKey();
															$cobv->HId=$o['HId'];
															}
														
														}
														else
														{
															
															$cobv=Certhobservations::model()->find(array('condition'=>'HObbasicId=:hbid AND Id=:ln',
												'params'=>array(':hbid'=>$cob->getPrimaryKey(),':ln'=>$o['Id']),));
															
														}
														
																	
																	$cobv->SValue=empty($o['SValue'])?"":$o['SValue'];
																	$cobv->CValue=empty($o['CValue'])?"":$o['CValue'];
																	$cobv->save(false);
															 
															
													}
												}
												
											}	
										}	
										else
										{
											$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="hardness"',
												'params'=>array(':cbid'=>$_GET['id']),));
												if(!empty($ct))
												{
													$ct->delete();
												}
										}
										
									/*------------------Mechanical-Proofload------------------*/	
																					
										$proof=$data['Proofload'];
										if(!empty($proof['Certtest']['RefId']))
										{
											if(!empty($proof['Certtest']['Id']))
											{
												$ct=Certtest::model()->findByPk($impact['Certtest']['Id']);	
											}
											else
											{
												$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="proofload"',
												'params'=>array(':cbid'=>$_GET['id']),));
												if(empty($ct))
												{
												$ct=new Certtest;
												}
											}
											
											if(!empty($proof['Certtest']['References']))
											{
											$ct->Section="proofload";
											$ct->CertBasicId=$_GET['id'];
											$ct->References=empty($proof['Certtest']['References'])?"":$proof['Certtest']['References'];
											$ct->RefId=$proof['Certtest']['RefId'];
											$ct->RefExtra=empty($proof['Certtest']['RefExtra'])?"":$proof['Certtest']['RefExtra'];
											$ct->Ref=empty($proof['Certtest']['Ref'])?"":$proof['Certtest']['Ref'];
											$ct->Remark=empty($proof['Certtest']['Remark'])?"":$proof['Certtest']['Remark'];
												$ct->ShowInCert="false";
											$ct->save(false);
											}
											
											
											if(!empty($proof['Observations']))
											{
												
													if(empty($proof['Certpparams']['Id']))
													{
														$cpp=Certpparams::model()->find(array('condition'=>'CertTestId=:ctid ',
												'params'=>array(':ctid'=>$ct->getPrimaryKey()),));
														if(empty($cpp))
														{
																$cpp=new Certpparams;
																$cpp->CertTestId=$ct->getPrimaryKey();
														}
													}
													else
													{
														$cpp=Certpparams::model()->findByPk($proof['Certpparams']['Id']);
													}
												
													//$cip->Temp=$proof['Certpparams']['Temp'];
													$cpp->Max=empty($proof['Certpparams']['Max'])?"":$proof['Certpparams']['Max'];
													$cpp->Min=empty($proof['Certpparams']['Min'])?"":$proof['Certpparams']['Min'];
													$cpp->save(false);
												
												
											
											
												foreach($proof['Observations'] as $cb)
												{
													if(empty($cb['Id']))
													{
														$cob=Certpobservations::model()->find(array('condition'=>'CertTestId=:ctid AND LabNo=:ln AND TestId=:tid AND SeqNo=:sn',
												'params'=>array(':ctid'=>$ct->getPrimaryKey(),':ln'=>$cb['LabNo'],':tid'=>$cb['TestId'],':sn'=>$cb['SeqNo']),));
														if(empty($cob))
														{
															$cob=new Certpobservations;
															$cob->CertTestId=$ct->getPrimaryKey();
														}
														
													}
													else
													{
														$cob=Certpobservations::model()->findByPk($cb['Id']);
													}
													$cob->HeatNo=empty($cb['HeatNo'])?"":$cb['HeatNo'];
													$cob->LabNo=$cb['LabNo'];
													$cob->TestId=$cb['TestId'];
														$cob->SeqNo=empty($cb['SeqNo'])?"":$cb['SeqNo'];
													$cob->ShowInCert=$cb['ShowInCert'];
													$cob->BatchCode=$cb['BatchCode'];
													$cob->Value1=$cb['Value1'];
													// $cob->Value2=$cb['Value2'];
													// $cob->Value3=$cb['Value3'];
													// $cob->Avg=$cb['Avg'];
													$cob->Remark=$cb['Remark'];
													$cob->LastModified=date('Y-m-d H:i:s');
													$cob->save(false);
													
													if($cob->ShowInCert==="true")
													{
														$ct->ShowInCert="true";
														$ct->save(false);
													}
												}
												
											}	
											
										}
										else
										{
											$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="proofload"',
												'params'=>array(':cbid'=>$_GET['id']),));
												if(!empty($ct))
												{
													$ct->delete();
												}
										}
											
									/*------------------Mechanical-Torque tension------------------*/	
																					
										$tension=$data['Tension'];
										if(!empty($tension['Certtest']['RefId']))
										{
											if(!empty($tension['Certtest']['Id']))
											{
												$ct=Certtest::model()->findByPk($impact['Certtest']['Id']);	
											}
											else
											{
												$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="tension"',
												'params'=>array(':cbid'=>$_GET['id']),));
												if(empty($ct))
												{
												$ct=new Certtest;
												}
											}
											
											if(!empty($tension['Certtest']['References']))
											{
											$ct->Section="tension";
											$ct->CertBasicId=$_GET['id'];
											$ct->References=empty($tension['Certtest']['References'])?"":$tension['Certtest']['References'];
											$ct->RefId=$tension['Certtest']['RefId'];
											$ct->RefExtra=empty($tension['Certtest']['RefExtra'])?"":$tension['Certtest']['RefExtra'];
											$ct->Ref=empty($tension['Certtest']['Ref'])?"":$tension['Certtest']['Ref'];
											$ct->Remark=empty($tension['Certtest']['Remark'])?"":$tension['Certtest']['Remark'];
												$ct->ShowInCert="false";
											$ct->save(false);
											}
											
											
											if(!empty($tension['Observations']))
											{
												
													
												
											
											
												foreach($tension['Observations'] as $cb)
												{
													if(empty($cb['Id']))
													{
														$cob=Certtqobservations::model()->find(array('condition'=>'CertTestId=:ctid AND LabNo=:ln AND TestId=:tid AND SeqNo=:sn',
												'params'=>array(':ctid'=>$ct->getPrimaryKey(),':ln'=>$cb['LabNo'],':tid'=>$cb['TestId'],':sn'=>$cb['SeqNo']),));
														if(empty($cob))
														{
															$cob=new Certtqobservations;
															$cob->CertTestId=$ct->getPrimaryKey();
														}
														
													}
													else
													{
														$cob=Certtqobservations::model()->findByPk($cb['Id']);
													}
													$cob->HeatNo=empty($cb['HeatNo'])?"":$cb['HeatNo'];
													$cob->LabNo=$cb['LabNo'];
													$cob->TestId=$cb['TestId'];
														$cob->SeqNo=empty($cb['SeqNo'])?"":$cb['SeqNo'];
													$cob->ShowInCert=$cb['ShowInCert'];
													$cob->BatchCode=$cb['BatchCode'];
													$cob->Torque=$cb['Torque'];
													$cob->Force=$cb['Force'];
													$cob->Coff_Friction=$cb['Coff_Friction'];
													// $cob->Avg=$cb['Avg'];
													$cob->Remark=$cb['Remark'];
													$cob->LastModified=date('Y-m-d H:i:s');
													$cob->save(false);
													
													if($cob->ShowInCert==="true")
													{
														$ct->ShowInCert="true";
														$ct->save(false);
													}
												}
												
											}	
											
										}
										else
										{
											$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="tension"',
												'params'=>array(':cbid'=>$_GET['id']),));
												if(!empty($ct))
												{
													$ct->delete();
												}
										}
											
												/*------------------Mechanical-Wedge------------------*/	
																					
										$wedge=$data['Wedge'];
										if(!empty($wedge['Certtest']['RefId']))
										{
											if(!empty($wedge['Certtest']['Id']))
											{
												$ct=Certtest::model()->findByPk($impact['Certtest']['Id']);	
											}
											else
											{
												$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="wedge"',
												'params'=>array(':cbid'=>$_GET['id']),));
												if(empty($ct))
												{
												$ct=new Certtest;
												}
											}
											
											if(!empty($wedge['Certtest']['References']))
											{
											$ct->Section="wedge";
											$ct->CertBasicId=$_GET['id'];
											$ct->References=empty($wedge['Certtest']['References'])?"":$wedge['Certtest']['References'];
											$ct->RefId=$wedge['Certtest']['RefId'];
											$ct->RefExtra=empty($wedge['Certtest']['RefExtra'])?"":$wedge['Certtest']['RefExtra'];
											$ct->Ref=empty($wedge['Certtest']['Ref'])?"":$wedge['Certtest']['Ref'];
											$ct->Remark=empty($wedge['Certtest']['Remark'])?"":$wedge['Certtest']['Remark'];
												$ct->ShowInCert="false";
											$ct->save(false);
											}
											
											
											if(!empty($wedge['Observations']))
											{
												
													// if(empty($wedge['Certiparams']['Id']))
													// {
														// $cip=Certpparams::model()->find(array('condition'=>'CertTestId=:ctid ',
												// 'params'=>array(':ctid'=>$ct->getPrimaryKey()),));
														// if(empty($cip))
														// {
																// $cip=new Certiparams;
																// $cip->CertTestId=$ct->getPrimaryKey();
														// }
													// }
													// else
													// {
														// $cip=Certpparams::model()->findByPk($wedge['Certpparams']['Id']);
													// }
												
													// //$cip->Temp=$proof['Certpparams']['Temp'];
													// $cip->Max=$wedge['Certpparams']['Max'];
													// $cip->Min=$wedge['Certpparams']['Min'];
													// $cip->save(false);
												
												
											
											
												foreach($wedge['Observations'] as $cb)
												{
													if(empty($cb['Id']))
													{
														$cob=Certwobservations::model()->find(array('condition'=>'CertTestId=:ctid AND LabNo=:ln AND TestId=:tid AND SeqNo=:sn',
												'params'=>array(':ctid'=>$ct->getPrimaryKey(),':ln'=>$cb['LabNo'],':tid'=>$cb['TestId'],':sn'=>$cb['SeqNo']),));
														if(empty($cob))
														{
															$cob=new Certwobservations;
															$cob->CertTestId=$ct->getPrimaryKey();
														}
														
													}
													else
													{
														$cob=Certwobservations::model()->findByPk($cb['Id']);
													}
													$cob->HeatNo=empty($cb['HeatNo'])?"":$cb['HeatNo'];
													$cob->LabNo=$cb['LabNo'];
													$cob->TestId=$cb['TestId'];
														$cob->SeqNo=empty($cb['SeqNo'])?"":$cb['SeqNo'];
													$cob->ShowInCert=$cb['ShowInCert'];
													$cob->BatchCode=$cb['BatchCode'];
													
													$cob->ObservedTS=$cb['ObservedTS'];
													$cob->RequiredTS=$cb['RequiredTS'];
													// $cob->Value2=$cb['Value2'];
													// $cob->Value3=$cb['Value3'];
													// $cob->Avg=$cb['Avg'];
													$cob->Remark=$cb['Remark'];
													$cob->LastModified=date('Y-m-d H:i:s');
													$cob->save(false);
													
													if($cob->ShowInCert==="true")
													{
														$ct->ShowInCert="true";
														$ct->save(false);
													}
												}
												
											}	
											
										}
										else
										{
											$ct=Certtest::model()->find(array('condition'=>'CertBasicId=:cbid AND Section="wedge"',
												'params'=>array(':cbid'=>$_GET['id']),));
												if(!empty($ct))
												{
													$ct->delete();
												}
										}
											
										
										
										/*---------------end-----------------------------*/
											$transaction->commit();
											$this->_sendResponse(200, CJSON::encode($data));
										
									}
									catch(Exception $e)
									{
										
											  $transaction->rollback();
											$this->_sendResponse(401, CJSON::encode($e));
									}			
				$this->_sendResponse(401, CJSON::encode("not saved1"));
				
				break;
				
		 case 'certbasicupdate':
		     $transaction=$model->dbConnection->beginTransaction();
				try
					{
						//$this->_sendResponse(401, CJSON::encode($data['sections']));
						$set=Settingslab::model()->findByPk("1");		
					
						
						$model->TCFormat=$data['basic']['TCFormat'];
						$model->TCNo=$data['basic']['TCNo'];
						$model->TCRevNo=$data['basic']['TCRevNo'];
						$model->CertDate=date('Y-m-d',strtotime($data['basic']['CertDate']));
						$model->CustId=$data['basic']['CustId'];
						$model->CertType=$data['basic']['CertType'];					
						
						$model->PartDescription=$data['basic']['PartDescription'];
						$model->Rirs=json_encode($data['basic']['Rirs']);
						$model->LastModified=date('Y-m-d H:i:s');
						$model->FormatNo=isset($data['basic']['FormatNo'])?$data['basic']['FormatNo']:null;
						$model->save(false);
						
						
						$cbx=Certbasicextra::model()->find(['condition'=>'CBID=:cbid','params'=>[':cbid'=>$model->getPrimaryKey()]]);
						if(empty($cbx))
						{
							$cbx=new Certbasicextra;
							$cbx->CBID=$model->getPrimaryKey();
						}						
			
						foreach($data['basicextra'] as $var=>$value)
						{
						// Does the model have this attribute? If not raise an error
						if($cbx->hasAttribute($var))
							$cbx->$var = $value;
						}
						$cbx->save(false);
						
						$uir=Userapppermission::model()->find(array('condition'=>'UserId =:uid AND SectionId="9"',
							'params'=>array(':uid'=>$data['PreparedBy']),));
							if(!empty($uir))
							{
								if($uir->C)
								{
									$model->PreparedBy=$data['PreparedBy'];	
								}
							}		
						
						
						$model->save(false);
						
						$secs=$data['sections'];						
						$chems=$secs['chemicals'];
						if(!empty($chems))
						{
						
							foreach($chems['Ref'] as $s)
							{
								
								if($model->CertType==='Normal' || $model->CertType==='Bushing')
								{
									$sec=Certsections::model()->find(['condition'=>'CBID=:cbid   AND Section="Chemical"',
									'params'=>[':cbid'=>$model->getPrimaryKey(),]]);
								}
								if($model->CertType==='Assembly')
								{
									$sec=Certsections::model()->find(['condition'=>'CBID=:cbid AND SSID=:ssid  AND Section="Chemical"',
									'params'=>[':cbid'=>$model->getPrimaryKey(),':ssid'=>$s['SSID']]]);
								}
								
								
									if(empty($sec))
									{
										$sec=new Certsections;
										$sec->CBID=$model->getPrimaryKey();
										$sec->SSID=$s['SSID'];
										$sec->Section="Chemical";
										
									}
								
									$sec->Extra=isset($s['Extra'])?$s['Extra']:null;
										$sec->Reference=isset($s['Reference'])?$s['Reference']:null;
										$sec->SName=isset($s['SName'])?$s['SName']:null;
										$sec->save(false);	
									
									
									foreach($s['chemtests'] as $t)
									{
										if(isset($t['ShowInCert']) && $t['ShowInCert'])
										{
											if(isset($t['Id']))
											{
												$ct=Certtest::model()->findByPk($t['Id']);
											}
											else
											{
												$ct=Certtest::model()->find(['condition'=>'CSID=:csid AND RTID=:rtid',
												'params'=>[':csid'=>$sec->getPrimaryKey(),':rtid'=>$t['RTID']]]);
												if(empty($ct))
												{									
													
																								
													$ct=new Certtest;
													$ct->CSID=$sec->getPrimaryKey();
													$ct->LabNo=$t['LabNo'];
													$ct->HeatNo=$t['HeatNo'];
													$ct->TestInfo=isset($t['TestInfo'])?$t['TestInfo']:null;
													
													$ct->TestName=$t['TestName'];
													$ct->RTID=$t['RTID'];
													$ct->SSID=$t['SSID'];
													$ct->TMID=isset($t['TMID'])?$t['TMID']:null;													
													$ct->TUID=$t['TUID'];
													$ct->save(false);
												}
											}
										
											$ct->HeatNo=$t['HeatNo'];
											$ct->TestInfo=isset($t['TestInfo'])?$t['TestInfo']:null;
											$ct->Reference=isset($t['Reference'])?$t['Reference']:null;
											$ct->ShowInCert=isset($t['ShowInCert'])?$t['ShowInCert']:0;
											$ct->Remark=isset($t['Remark'])?$t['Remark']:null;										
											$ct->save(false);	
										
											foreach($t['Obs'] as $o)
											{
												
												$speccriteria = ['PID' => $o['PID'],'ShowInCert'=>1];
												$oldref = MyFunctions::findWhere($s['ChemSpec'], $speccriteria);
												
												
												
												if(!empty($oldref))
												{
													$to=Certtestobs::model()->find(['condition'=>'CTID=:ctid AND PID=:pid',
													'params'=>[':ctid'=>$ct->getPrimaryKey(),':pid'=>$o['PID']]]);
												
													if(empty($to))
													{
														$to=new Certtestobs;
														$to->CTID=$ct->getPrimaryKey();
														$to->PID=$o['PID'];
														$to->Value=isset($o['Value'])?$o['Value']:null;
													}											
											
													$to->ShowInCert=1;													
													$to->save(false);
												}
												
											}
										}											
										
									}
									
								//----test Specifications		
									foreach($s['ChemSpec'] as $o)
									{
										if(isset($o['ShowInCert']) && $o['ShowInCert'])
										{
											$tspec=Certtestspecs::model()->find(['condition'=>'CSID=:csid AND PID=:pid',
												'params'=>[':csid'=>$sec->getPrimaryKey(),':pid'=>$o['PID']]]);
												if(empty($tspec))
												{
													$tspec=new Certtestspecs;
													$tspec->CSID=$sec->getPrimaryKey();
													$tspec->TUID='CHEM';//$t['TUID'];
													$tspec->PID=$o['PID'];
													$tspec->SpecMin=isset($o['SpecMin'])?$o['SpecMin']:null;
													$tspec->SpecMax=isset($o['SpecMax'])?$o['SpecMax']:null;
												}												
											
											$tspec->ShowInCert=isset($o['ShowInCert'])?$o['ShowInCert']:0;											
											$tspec->save(false);
										}
										
									}
								
							}
						
						}
						
						$mechs=$secs['mechanicals'];
						if(!empty($mechs))
						{
						foreach($mechs['Ref'] as $sr)
							{
								
								
								
										
							foreach($sr as $s)
							{
									
									
									if($model->CertType==='Normal' || $model->CertType==='Bushing')
								{
									$sec=Certsections::model()->find(['condition'=>'CBID=:cbid AND Section="Mechanical"',
									'params'=>[':cbid'=>$model->getPrimaryKey(),]]);
								}
								
								if($model->CertType==='Assembly')
								{
									$sec=Certsections::model()->find(['condition'=>'CBID=:cbid AND SSID=:ssid  AND Section="Mechanical"',
									'params'=>[':cbid'=>$model->getPrimaryKey(),':ssid'=>$s['SSID']]]);
								}
									
									if(empty($sec))
									{
										$sec=new Certsections;
										$sec->CBID=$model->getPrimaryKey();
										$sec->SSID=isset($s['SSID'])?$s['SSID']:null;
										$sec->Section="Mechanical";									
									}
									
									$sec->Extra=isset($s['Extra'])?$s['Extra']:null;
									$sec->Reference=isset($s['Reference'])?$s['Reference']:null;
									$sec->SName=isset($s['SName'])?$s['SName']:null;
									$sec->save(false);	
									
									foreach($s['mechtests'] as $t)
									{
										
												
										if(isset($t['ShowInCert']) && $t['ShowInCert'])
										{
												if($model->CertType==='Bushing' )
												{
														$ct=Certtest::model()->find(['condition'=>'CSID=:csid AND RTID=:rtid AND TUID=:tuid',
												'params'=>[':csid'=>$sec->getPrimaryKey(),':rtid'=>$t['RTID'],':tuid'=>$t['TUID']]]);
												}
												else
												{
													$ct=Certtest::model()->find(['condition'=>'CSID=:csid AND RTID=:rtid ',
												'params'=>[':csid'=>$sec->getPrimaryKey(),':rtid'=>$t['RTID']]]);
												}
											
												
												if(empty($ct))
												{
													$ct=new Certtest;
													$ct->CSID=$sec->getPrimaryKey();
													$ct->LabNo=$t['LabNo'];													
													$ct->RTID=$t['RTID'];
													$ct->SSID=$t['SSID'];
													$ct->TMID=isset($t['TMID'])?$t['TMID']:null;																								
													$ct->TUID=$t['TUID'];
													
												}
												
													$ct->HeatNo=$t['HeatNo'];
													$ct->TestInfo=isset($t['TestInfo'])?$t['TestInfo']:null;		
													$ct->ShowInCert=isset($t['ShowInCert'])?$t['ShowInCert']:0;
													$ct->Reference=isset($t['Reference'])?$t['Reference']:null;
													$ct->Remark=isset($t['Remark'])?$t['Remark']:null;
													$ct->save(false);		
										
									
										
										foreach($t['Obs'] as $o)
											{
												$speccriteria = ['PID' => $o['PID'],'ShowInCert'=>1];
												$oldref = MyFunctions::findWhere($s['MechSpec'], $speccriteria);
												
												if(!empty($oldref))
												{
													$to=Certtestobs::model()->find(['condition'=>'CTID=:ctid AND PID=:pid',
													'params'=>[':ctid'=>$ct->getPrimaryKey(),':pid'=>$o['PID']]]);
													if(empty($to))
													{
														$to=new Certtestobs;
													$to->CTID=$ct->getPrimaryKey();
													$to->PID=$o['PID'];
														
													}
													
													$to->ShowInCert=1;
													if (isset($o['Value'])) {
														if (is_array($o['Value'])) {
															$to->Value = json_encode($o['Value']);
														} else {
															$to->Value = $o['Value'];
														}
													} else {
														$to->Value = null; // Assign null if $o['Value'] is not set
													}
												
												
														$to->Temp = isset($o['Temp'])?$o['Temp']:null;
												
													$to->save(false);
													if(!is_null($to->Temp))
													{
														$ct->Temp=$to->Temp;
														$ct->save(false);
													}
												}
												
											}	
										
												

										}	

									}
													//----test Specifications		
									foreach($s['MechSpec'] as $o)
									{
										if(isset($o['ShowInCert']) && $o['ShowInCert'])
										{
											
												$tspec=Certtestspecs::model()->find(['condition'=>'CSID=:csid AND PID=:pid AND TUID=:tuid',
												'params'=>[':csid'=>$sec->getPrimaryKey(),':pid'=>$o['PID'],':tuid'=>$o['TUID']]]);
												if(empty($tspec))
												{
													
													$tspec=new Certtestspecs;
													$tspec->CSID=$sec->getPrimaryKey();
													$tspec->PID=$o['PID'];
												}
												$tspec->TUID=$o['TUID'];
										$tspec->ShowInCert=isset($o['ShowInCert'])?$o['ShowInCert']:0;
										$tspec->SpecMin=isset($o['SpecMin'])?$o['SpecMin']:null;
										$tspec->SpecMax=isset($o['SpecMax'])?$o['SpecMax']:null;
										$tspec->Temp=isset($o['Temp'])?$o['Temp']:null;
										$tspec->TempUnit=isset($o['TempUnit'])?$o['TempUnit']:null;
													
										
										$tspec->save(false);
										}
										else
										{
											$tspec=Certtestspecs::model()->find(['condition'=>'CSID=:csid AND PID=:pid',
												'params'=>[':csid'=>$sec->getPrimaryKey(),':pid'=>$o['PID']]]);
												if(!empty($tspec))
												{									
													
													$tspec->ShowInCert=isset($o['ShowInCert'])?$o['ShowInCert']:0;													
													$tspec->save(false);
												}
										}
										
									}
										
									
									
							
								
							}
							}
						
						}
						
						
											//$this->_sendResponse(401, CJSON::encode("reached externa"));
						
						$externals=$secs['externals'];
						if(array_key_exists('NM', $externals))
						{
						if($externals['NM']['Selected'])
						{
							 $nonmetallic=$externals['NM']['Test'];							 
									                                
									if(!empty($nonmetallic['Observations']))
										{
																					
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'NM'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
										
                                
										if(!empty($nonmetallic))
										{
											foreach($nonmetallic as $var=>$value)
											  {
												// Does the model have this attribute? If not raise an error
												if($ct->hasAttribute($var))
													$ct->$var = $value;
											  }		
										}									  
								
										$ct->Section="External";
										$ct->Keyword="NM";
										$ct->CBID=$model->getPrimaryKey();
										$ct->SName=isset($nonmetallic['SName'])?$nonmetallic['SName']:null;
										$ct->save(false);
                                
										if(!empty($nonmetallic['delobservations']))
										{
											foreach($nonmetallic['delobservations'] as $db)
											{
												$dob=Certnonmetallic::model()->findByPk($db['Id']);
												if(!empty($dob))
												{
													$dob->delete();
												}
											}
										}
											
											$i=1;
											foreach($nonmetallic['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certnonmetallic;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certnonmetallic::model()->findByPk($cb['Id']);
												}
												$cob->SeqNo=$i;
												$cob->HeatNo=empty($cb['HeatNo'])?"":$cb['HeatNo'];
												$cob->Parameter=$cb['Parameter'];
												$cob->Required=$cb['Required'];
												$cob->Observation=$cb['Observation'];
												$cob->Remark=$cb['Remark'];
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
									}
										
								
								
								
						}
						
						}	
						
						
						if(array_key_exists('MPI', $externals))
						{
						if($externals['MPI']['Selected'])
						{
							 $mpi=$externals['MPI']['Test'];
							 	if(!empty($mpi['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'MPI'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($mpi))
												{
													foreach($mpi as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="MPI";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($mpi['SName'])?$mpi['SName']:null;
												$ct->save(false);
										
												if(!empty($mpi['delobservations']))
												{
													foreach($mpi['delobservations'] as $db)
													{
														$dob=Certmpidetails::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
												$i=1;
											foreach($mpi['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certmpidetails;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certmpidetails::model()->findByPk($cb['Id']);
												}
												$cob->SeqNo=$i;
												$cob->Parameter=$cb['Parameter'];
												$cob->Required=$cb['Required'];
												$cob->Observation=$cb['Observation'];
												$cob->Remark=$cb['Remark'];
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
											
											
										}


							 
						}
						}
						
						if(array_key_exists('ME', $externals))
						{
						if($externals['ME']['Selected'])
						{
							 $me=$externals['ME']['Test'];	
							 	if(!empty($me['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'ME'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($me))
												{
													foreach($me as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="ME";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($me['SName'])?$me['SName']:null;
												$ct->save(false);
										
												if(!empty($me['delobservations']))
												{
													foreach($me['delobservations'] as $db)
													{
														$dob=Certmicroexamination::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
											$i=1;
											foreach($me['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certmicroexamination;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certmicroexamination::model()->findByPk($cb['Id']);
												}
												$cob->SeqNo=$i;
												$cob->BatchCode=empty($cb['BatchCode'])?"":$cb['BatchCode'];
												$cob->Parameter=$cb['Parameter'];
												$cob->Required=$cb['Required'];
												$cob->Observation=$cb['Observation'];
												$cob->Remark=$cb['Remark'];
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
										}


							 
						}
						}
						
						if(array_key_exists('H', $externals))
						{
						if($externals['H']['Selected'])
						{
							 $hext=$externals['H']['Test'];	
							 	if(!empty($hext['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'H'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($hext))
												{
													foreach($hext as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="H";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($hext['SName'])?$hext['SName']:null;
												$ct->save(false);
										
												if(!empty($hext['delobservations']))
												{
													foreach($hext['delobservations'] as $db)
													{
														$dob=Certexthardness::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
												$i=1;
											
											//	$cb=$hext['Observations'];
												foreach($hext['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certexthardness;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certexthardness::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
											}
											
											
										}


						}
						}
						
						
						if(array_key_exists('ND', $externals))
						{
						if($externals['ND']['Selected'])
						{
							 $nondes=$externals['ND']['Test'];	
							 	if(!empty($nondes['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'ND'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($nondes))
												{
													foreach($nondes as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="ND";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($nondes['SName'])?$nondes['SName']:null;
												$ct->save(false);
										
												if(!empty($nondes['delobservations']))
												{
													foreach($nondes['delobservations'] as $db)
													{
														$dob=Certnondestructive::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
												$i=1;
											foreach($nondes['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certnondestructive;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certnondestructive::model()->findByPk($cb['Id']);
												}
												$cob->SeqNo=$i;
												$cob->Parameter=$cb['Parameter'];
												$cob->Required=$cb['Required'];
												$cob->Observation=$cb['Observation'];
												$cob->Remark=$cb['Remark'];
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
											
											
										}


						}
						}
						
						if(array_key_exists('ST', $externals))
						{
						if($externals['ST']['Selected'])
						{
							$streat=$externals['ST']['Test'];	
								if(!empty($streat['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'ST'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($streat))
												{
													foreach($streat as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="ST";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($streat['SName'])?$streat['SName']:null;
												$ct->save(false);
										
												if(!empty($streat['delobservations']))
												{
													foreach($streat['delobservations'] as $db)
													{
														$dob=Certsurfacetreat::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
											$i=1;
											foreach($streat['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certsurfacetreat;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certsurfacetreat::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
										}


						}
						}
						
						if(array_key_exists('STDELTA', $externals))
						{
						if($externals['STDELTA']['Selected'])
						{
							$stdel=$externals['STDELTA']['Test'];	
								if(!empty($stdel['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'STDELTA'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($stdel))
												{
													foreach($stdel as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="STDELTA";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($stdel['SName'])?$stdel['SName']:null;
												$ct->save(false);
										
												if(!empty($stdel['delobservations']))
												{
													foreach($stdel['delobservations'] as $db)
													{
														$dob=Certsurfacetreatdelta::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
												$i=1;
											
												$cb=$stdel['Observations'];
												if(empty($cb['Id']))
												{
												   
														$cob=new Certsurfacetreatdelta;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certsurfacetreatdelta::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											
											
											
											
										}


						}
						}
						
						if(array_key_exists('STDA', $externals))
						{
						if($externals['STDA']['Selected'])
						{
							$stda=$externals['STDA']['Test'];	
								if(!empty($stda['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'STDA'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($stda))
												{
													foreach($stda as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="STDA";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($stda['SName'])?$stda['SName']:null;
												$ct->save(false);
										
												if(!empty($stda['delobservations']))
												{
													foreach($stda['delobservations'] as $db)
													{
														$dob=Certsurfacetreatdacro::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
										$i=1;
											
												$cb=$stda['Observations'];
												if(empty($cb['Id']))
												{
												   
														$cob=new Certsurfacetreatdacro;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certsurfacetreatdacro::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											
											
											
										}


						}
						}
						
						if(array_key_exists('STHOT', $externals))
						{
						if($externals['STHOT']['Selected'])
						{
							$sthot=$externals['STHOT']['Test'];
							if(!empty($sthot['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'STHOT'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($sthot))
												{
													foreach($sthot as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="STHOT";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($sthot['SName'])?$sthot['SName']:null;
												$ct->save(false);
										
												if(!empty($sthot['delobservations']))
												{
													foreach($sthot['delobservations'] as $db)
													{
														$dob=Certsurfacetreathot::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
											$i=1;
											
												$cb=$sthot['Observations'];
												if(empty($cb['Id']))
												{
												   
														$cob=new Certsurfacetreathot;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certsurfacetreathot::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											
											
										}

							
						}
						}
						
						if(array_key_exists('STP', $externals))
						{
						if($externals['STP']['Selected'])
						{
							$stp=$externals['STP']['Test'];	
								if(!empty($stp['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'STP'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($stp))
												{
													foreach($stp as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="STP";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($stp['SName'])?$stp['SName']:null;
												$ct->save(false);
										
												if(!empty($stp['delobservations']))
												{
													foreach($stp['delobservations'] as $db)
													{
														$dob=Certsurfacetreatplat::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
												$i=1;
											
												$cb=$stp['Observations'];
												if(empty($cb['Id']))
												{
												   
														$cob=new Certsurfacetreatplat;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certsurfacetreatplat::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											
											
											
										}


						}
						}
						
						if(array_key_exists('OD', $externals))
						{
						if($externals['OD']['Selected'])
						{
							$othdet=$externals['OD']['Test'];	
								if(!empty($othdet['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'OD'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($othdet))
												{
													foreach($othdet as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="OD";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($othdet['SName'])?$othdet['SName']:null;
												$ct->save(false);
										
												if(!empty($othdet['delobservations']))
												{
													foreach($othdet['delobservations'] as $db)
													{
														$dob=Certotherdetails::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
											$i=1;
											foreach($othdet['Observations'] as $cb)
											{
												if(empty($cb['Id']))
												{
												   
														$cob=new Certotherdetails;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certotherdetails::model()->findByPk($cb['Id']);
												}
												$cob->SeqNo=$i;
												$cob->Parameter=$cb['Parameter'];
												$cob->Required=$cb['Required'];
												$cob->Observation=$cb['Observation'];
												$cob->Remark=$cb['Remark'];
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											}
											
											
											
										}


						}
						}
						
						
						if(array_key_exists('STDELTA2', $externals))
						{
						if($externals['STDELTA2']['Selected'])
						{
							$stdel2=$externals['STDELTA2']['Test'];	
								if(!empty($stdel2['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'STDELTA2'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($stdel2))
												{
													foreach($stdel2 as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="STDELTA2";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($stdel2['SName'])?$stdel2['SName']:null;
												$ct->save(false);
										
												if(!empty($stdel2['delobservations']))
												{
													foreach($stdel2['delobservations'] as $db)
													{
														$dob=Certsurfacetreatdelta::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
											$i=1;
											
												$cb=$stdel2['Observations'];
												if(empty($cb['Id']))
												{
												   
														$cob=new Certsurfacetreatdelta;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreationDate=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certsurfacetreatdelta::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												$cob->SeqNo=$i;
												$cob->LastModified=date('Y-m-d H:i:s');
												$cob->save(false);
												$i++;
											
											
											
										}


						}
						}
						
						
						
						if(array_key_exists('TZC', $externals))
						{
						if($externals['TZC']['Selected'])
						{
							$tcz=$externals['TZC']['Test'];	
								if(!empty($tcz['Observations']))
										{												
										
											$ct=Certsections::model()->find(array('condition'=>'CBID=:cbid AND Section="External" AND Keyword=:key',
											'params'=>array(':cbid'=>$model->getPrimaryKey(),':key'=>'TZC'),));
											if(empty($ct))
											{
												$ct=new Certsections;
											}
											
											
												if(!empty($tcz))
												{
													foreach($tcz as $var=>$value)
													  {
														// Does the model have this attribute? If not raise an error
														if($ct->hasAttribute($var))
															$ct->$var = $value;
													  }		
												}									  
										
												$ct->Section="External";
												$ct->Keyword="TZC";
												$ct->CBID=$model->getPrimaryKey();
												$ct->SName=isset($tcz['SName'])?$tcz['SName']:null;
												$ct->save(false);
										
												if(!empty($tcz['delobservations']))
												{
													foreach($tcz['delobservations'] as $db)
													{
														$dob=Certsurfacetreatdelta::model()->findByPk($db['Id']);
														if(!empty($dob))
														{
															$dob->delete();
														}
													}
												}
												
										
											
												$cb=$tcz['Observations'];
												if(empty($cb['Id']))
												{
												   
														$cob=new Certmisctest;
														$cob->CSID=$ct->getPrimaryKey();
														 $cob->CreatedOn=date('Y-m-d H:i:s');
											   
												}
												else
												{
													$cob=Certmisctest::model()->findByPk($cb['Id']);
												}
												
												foreach($cb as $var=>$value)
											  {
												if($cob->hasAttribute($var))
													$cob->$var = $value;
											  }		
												
												$cob->save(false);
												
											
											
											
										}


						}
						}
						
						
						$transaction->commit();
						$this->_sendResponse(200, CJSON::encode($model->Id));
					}
					catch(Exception $e)
					{
						$this->_sendResponse(401, CJSON::encode($e->getMessage()));
							  $transaction->rollback();
							
					}
			   
			   
			   break;
			   	
		 
			default:
			if($model->save())
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
				
				case 'delcert':
					$model = Certbasic::model()->findByPk($_GET['id']);                    
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

private function _checkAuth($secid,$op)
{
			$authmsg=MyFunctions::checkapiAuth($secid,$op);					
							if($authmsg->number !=200)
							{
								$this->_sendResponse(401, CJSON::encode($authmsg->msg));
							}
}
	
}

?>