<?php

class SettingapiController extends Controller
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
				
				case 'dropdowns':
				$this->_checkAuth(1,'R');
				$u=MyFunctions::gettokenuser();
				$dropdowns=Dropdowns::model()->findAll(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
				$categories=Listcategory::model()->findAll(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
				$result=(object)array('dropdowns'=>$dropdowns,'categories'=>$categories);
				$this->_sendResponse(200, CJSON::encode($result));
				break;
				
				case 'testconditions':
				$this->_checkAuth(1,'R');
						$models=$testconditions=Testconditions::model()->findAll();
				break;
				
				
				
				case 'attachcategory':	$models=Attachcategory::model()->findAll();	break;
			
			case 'exttestsetting':	$models=Externaltests::model()->findAll();	break; 
			
		case 'getcustomers':
				$this->_checkAuth(1,'R');
				$u=MyFunctions::gettokenuser();
					$customers=Customerinfo::model()->findAll(['condition'=>'CID=:cid','order'=>'Id Desc',
	'params'=>[':cid'=>$u->CID]]);
					$allcustomers=[];
					foreach($customers as $c)
					{
						$user=null;
						if(!empty($c->UserId))
						{
						$user=Users::model()->findByPk($c->UserId);
						}
						
						$allcustomers[]=(object)array('Id'=>$c->Id,'Name'=>$c->Name,'Email'=>$c->Email,
						'GSTIN'=>$c->GSTIN,'UserId'=>$c->UserId,'User'=>empty($user)?null:$user->FName,
						'Addresses'=>$c->custaddresses,'DelAddresses'=>[]);
					}
						$industries=Industry::model()->findAll(array('condition'=>'ParentId is null AND CID=:cid',
	'params'=>[':cid'=>$u->CID]));
						$users=Users::model()->with(['userinroles'=>['condition'=>'RoleId !="3"']])->findAll(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
						
						foreach($users as $u)
						{
							$role=$u->userinroles[0];
							$allusers[]=(object)['Id'=>$u->Id,'Name'=>$u->FName,'Role'=>$role->role->Role];
						}
					$result=(object)array('customers'=>$allcustomers,'industries'=>$industries,'allusers'=>$allusers);
				$this->_sendResponse(200, CJSON::encode($result));
				break;
				
				
				case 'getsuppliers':
				$this->_checkAuth(1,'R');
				$u=MyFunctions::gettokenuser();
					$models=Suppliers::model()->findAll(['condition'=>'CID=:cid','order'=>'Id Desc',
	'params'=>[':cid'=>$u->CID]]);
				break;
				
				

				case 'methods':
				$this->_checkAuth(2,'R');
				$u=MyFunctions::gettokenuser();
					$methods=Testmethods::model()->findAll(array('order'=>'Id Desc','condition'=>'CID=:cid',
					'params'=>[':cid'=>$u->CID]));
					$allmethods=array();
					foreach($methods as $l)
					{
						
							$allmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'TestId'=>$l->TestId,
							'Test'=>$l->test->TestName,'IndId'=>$l->IndId,'Industry'=>implode(" - ",array_reverse( MyFunctions::getParentCat($l->IndId))));
						
					}
					
					///////////Parent
				
				
				
					$tests=Tests::model()->findAll(array('condition'=>'Status="1" AND CID=:cid',
	'params'=>[':cid'=>$u->CID]));
					$industries=Industry::model()->findAll(array('condition'=>'HasSubInd=0 AND Status=1 AND CID=:cid',
	'params'=>[':cid'=>$u->CID]));
				$allindustries=[];
				foreach($industries as $c)
							{
								$parents =  MyFunctions::getParentCat($c->Id);//array();
									$allindustries[]=(object)array('Id'=>$c->Id,'Name'=>$c->Name,'ParentId'=>$c->ParentId,
									'FParent'=>end($parents),
									'PTree'=>implode(" - ",array_reverse( MyFunctions::getParentCat($c->Id))));
									
							}
				
					$data=(object)array('methods'=>$allmethods,'tests'=>$tests,'allindustries'=>$allindustries);
					$this->_sendResponse(200, CJSON::encode($data));	
				break;
			
				case 'users':
				
				$this->_checkAuth(1,'R');
				$u=MyFunctions::gettokenuser();
					$allusers=Users::model()->findAll(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					$models=array();
					
					foreach($allusers as $u)
					{
						
						$roleid="";
					$rolename="";
					foreach($u->userinroles as $r)
					{
						$roleid=$r->RoleId;
						$rolename=$r->role->Role;
					}
					
					$ubrannames=[];
					foreach($u->userinbranches as $b)
					{
						
						$ubrannames[]=$b->branch->Name;
					}
					
						$models[]=(object)array('Id'=>$u->Id,'FName'=>$u->FName,'MName'=>$u->MName,'LName'=>$u->LName,
						'Email'=>$u->Email,'ContactNo'=>$u->ContactNo,'Signature'=>empty($u->usersignuploads)?null:$u->usersignuploads[0],
						'Branches'=>$ubrannames,'Designation'=>$u->Designation,
						'RoleName'=>$rolename,'Department'=>$u->Department,'Username'=>$u->Username);
					}
					$this->_sendResponse(200, CJSON::encode($models));		
				break;
				case 'preuserdata':
				$u=MyFunctions::gettokenuser();
					$allusers=Users::model()->findAll(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					$models=array();
					foreach($allusers as $ud)
					{
						$models[]=(object)array('Email'=>$ud->Email);
					}
					
					
					$allsections=Appsections::model()->findAll(array('condition'=>'Status=1'));
					foreach($allsections as $s)
					{
						$appsections[]=(object)array('SectionId'=>$s->Id,'Section'=>$s->Section,'Description'=>$s->Description,
						'Group'=>$s->Group,'C'=>0,'R'=>0,'U'=>0,'D'=>0,'A'=>0,'Ch'=>0,'Print'=>0,'SM'=>0,
						'IsC'=>$s->C,'IsR'=>$s->R,'IsU'=>$s->U,'IsSM'=>$s->SM,'IsD'=>$s->D,'IsA'=>$s->A,'IsCh'=>$s->Ch,'IsPrint'=>$s->Print,);
					}


					$roles=Roles::model()->findAll();
										
										foreach($roles as $r)
										{
											$rappsections=array();
											$rsections=array();
					
					if(!empty($r->roleapppermissions))
					{
						
						foreach($r->roleapppermissions as $s)
						{
							$rsections[]=$s->SectionId;
							$rappsections[]=(object)array('Id'=>$s->Id,'SectionId'=>$s->SectionId,'Section'=>$s->section->Section,'Group'=>$s->section->Group,'Description'=>$s->section->Description,
							'IsC'=>$s->section->C,'IsR'=>$s->section->R,'IsU'=>$s->section->U,'IsSM'=>$s->section->SM,'IsD'=>$s->section->D,'IsA'=>$s->section->A,'IsCh'=>$s->section->Ch,'IsPrint'=>$s->section->Print,
							'C'=>$s->C,'R'=>$s->R,'U'=>$s->U,'D'=>$s->D,'A'=>$s->A,'Ch'=>$s->Ch,'Print'=>$s->Print,'SM'=>$s->SM);
						}
						
						
						foreach($allsections as $s)
						{
							if(!in_array($s->Id,$rsections))
							{
								$rappsections[]=(object)array('SectionId'=>$s->Id,'Section'=>$s->Section,'Group'=>$s->Group,
								'Description'=>$s->Description,'IsC'=>$s->C,'IsR'=>$s->R,'IsU'=>$s->U,'IsSM'=>$s->SM,'IsD'=>$s->D,'IsA'=>$s->A,'IsCh'=>$s->Ch,'IsPrint'=>$s->Print,
								'C'=>0,'R'=>0,'U'=>0,'D'=>0,'A'=>0,'Ch'=>0,'Print'=>0,'SM'=>0);
							}
							
						}
						
					}
				
					
					$allroles[]=(object)array('Id'=>$r->Id,'Role'=>$r->Role,'Appsections'=>$rappsections);
					
										}
					
					$branches=Branches::model()->findAll();
										
										$allbranches=array();
					foreach($branches as $b)
					{
						$allbranches[]=(object)array('BranchId'=>$b->Id,'Name'=>$b->Name);
					}
					$data=(object)array('Users'=>$models,'Roles'=>$allroles,'allbranches'=>$allbranches,'Appsections'=>$appsections);
					$this->_sendResponse(200, CJSON::encode($data));

					
				break;
				
				
				
										
				case 'stdpredata':
						$u=MyFunctions::gettokenuser();			
						$models=Standards::model()->findAll(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
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
				
				case 'substdparams':
				try{
					
					$substd=Substandards::model()->findByPk($_GET['id']);
					
					
					$testparams=[];
					foreach($substd->stdsubdetails as $s)
					{
					
						if($s->p->IsSpec)
						{
							$p=$s->p;
							$testparams[]=(object)['Parameter'=>$p->Parameter,'PSymbol'=>$p->PSymbol,'PID'=>$s->PId,'SpecMin'=>$s->SpecMin,'SpecMax'=>$s->SpecMax,
							'TUID'=>$s->TUID];
						}
					}
					
					$result=(object)['testparams'=>$testparams];
					$this->_sendResponse(200, CJSON::encode($result));
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;	
				
				case 'getteststds':
				try{
					
					
					$test=Tests::model()->find(['condition'=>'TUID=:tuid','params'=>[':tuid'=>$_GET['id']]]);
				$tuid=$test->TUID;
					
					$u=MyFunctions::gettokenuser();
					$subtests=Substdtests::model()->with('sS')->findAll(['condition'=>'TID=:tid AND sS.CID=:cid',
					'params'=>[':tid'=>$test->Id,':cid'=>$u->CID]]);
				
$allsubstds=[];
					
				if(empty($subtests))
				{
					$substdtests=Substdtests::model()->with('sS')->findAll(['condition'=>'sS.CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					$allsubstds=[];
						foreach($substdtests as $sd)
						{
							$substd=Substandards::model()->findByPk($sd->SSID);
							$allsubstds[]=(object)['Id'=>$substd->Id,'Name'=>$substd->std->Standard.' '.$substd->Grade,
							];
						}
				}
				else
				{
					$allsubstds=[];
					
					foreach($subtests as $st)
					{
						
					$s=$st->sS;
						
							$allsubstds[]=(object)['Id'=>$s->Id,'Name'=>$s->std->Standard." ".$s->Grade,];
						
						
					}
				}
				
				
				
					
					$alltestmethods=Testmethods::model()->findAll(['condition'=>'TUID=:tuid AND CID=:cid',
	'params'=>[':tuid'=>$tuid,':cid'=>$u->CID]]);
					
					$result=(object)['allsubstds'=>$allsubstds,'TUID'=>$tuid,'alltestmethods'=>$alltestmethods];
					
					$this->_sendResponse(200, CJSON::encode($result));
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;	
				
				case 'getindtests': 
				$u=MyFunctions::gettokenuser();
						$tests=Tests::model()->findAll(array('condition'=>'IndId=:ind AND CID=:cid',
						'params'=>array(':ind'=>$_GET['id'],':cid'=>$u->CID)));
						$result=(object)['tests'=>$tests];
				$this->_sendResponse(200, CJSON::encode($result));
				break;
				
			case 'getnotifications':
			try{
				$u=Users::model()->findByPk($_GET['id']);
				if(!empty($u))
				{
					$notis=$u->usernotifications(array('condition'=>'Status="0"','order'=>'Id Desc'));
					$allnotis=[];
					if(!empty($notis))
					{
					foreach($notis as $n)
					{
						$datetime1=date_create(date('Y-m-d H:i:s'));
						$datetime2=date_create($n->not->CreatedAt);
						$interval =date_diff($datetime1, $datetime2);
						$min = $interval->days * 24 * 60;
				$min += $interval->h * 60;
				$min += $interval->i;
				
				$ap=Appsections::model()->findByPk($n->not->AppSecId);
				if(!empty($ap))
				{
						$allnotis[]=(object)array('Id'=>$n->Id,'NotId'=>$n->NotId,'Min'=>$min,'Notification'=>$n->not->Notifications,'Appsec'=>$ap->Section,'CreatedAt'=>date(' F j Y h:i:s A',strtotime($n->not->CreatedAt)),'Status'=>$n->Status);
				}
					}
					}
				}
				else
				{
					$allnotis=[];
				}
				$result=(object)['allnotis'=>$allnotis];
				$this->_sendResponse(200, CJSON::encode($result));
			}
			catch(Exception $e)
			{
				$this->_sendResponse(401, CJSON::encode($e->getMessage()));
			}
			break;	
			case 'getpermission':
			try{
			
			$u=Users::model()->findByPk($_GET['id']);
			$appsections=array();
			$groupsections=array();
			$groups=array();
			
								if(!empty($u))
								{
									if(empty($u->userapppermissions))
									{
										
										$allsections=Appsections::model()->findAll();
										foreach($allsections as $s)
										{
											$access=(object)array('C'=>0,'R'=>0,'U'=>0,'D'=>0,'A'=>0,'Ch'=>0,'Print'=>0,'SM'=>0);
											$appsections[$s->Id]=(object)array('SectionId'=>$s->Id,'Section'=>$s->Section,'Group'=>$s->Group, 'Access'=>$access);
										}
									}
									else
									{
										
										foreach($u->userapppermissions as $s)
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
									}
								}
								foreach(array_values(array_unique($groupsections)) as $g)
								{
									$groups[$g]=(object)array('Apply'=>1);
								}
								
								 $set=Settingsfirm::model()->find();
								 $applogo=Firmlogos::model()->find();//array('condition'=>'setid =:setid','params'=>array(':setid'=>$set->Id)));
								 $appset=(object)['Name'=>$set->Name,'Address'=>$set->Address,
								 'Applogo'=>empty($applogo)?null:$applogo->url
								 ];
								
								 $tests=Tests::model()->findAll(array('condition'=>' Status=1 AND CID=:cid ',
								 'params'=>[':cid'=>$u->CID]));
								
								$alltest=[];
									foreach($tests as $t)
									{	
									$aps=Appsections::model()->find(array('condition'=>'Others =:oth','params'=>[':oth'=>$t->Id]));
									if (array_key_exists($aps->Id,$appsections))
									{
										
										if(!empty($aps) )
										{
											if($appsections[$aps->Id]->Apply)
											{
											$parents = MyFunctions::getParentCat($t->IndId);											

											$alltest[end($parents)][]=(object)['icon'=>$t->icon,'name'=>$t->TestName,'tid'=>$t->Id,'sid'=>$aps->Id,'FParent'=>end($parents),];	
											}	
										}		
									}										
									}								
								
	
									$model=(object)array('permissions'=>$appsections,'groups'=>$groups,'appset'=>$appset,'tests'=>$alltest);
								
			}
			catch(Exception $e)
			{
				$this->_sendResponse(401, CJSON::encode($e->getMessage()));
			}
			break;
									
									
			
				
			case 'getsettings':
					//$sets =Settings::model()->findAll();
					$u=MyFunctions::gettokenuser();
					$firmset =Settingsfirm::model()->find(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					$vault =Settingsbank::model()->find(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);					
					$account =Settingsaccount::model()->find(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);					
					$labset =Settingslab::model()->find(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
		$certset =Settingslab::model()->find(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					
					
					$allcurrency=['₹','$'];
					$alllocs=Branches::model()->findAll();
					$model=(object)array('firmset'=>$firmset,'vault'=>$vault,'branchid'=>$u->CID,'account'=>$account,
					'labset'=>$labset,'certset'=>$certset,'allcurrency'=>$allcurrency,'alllocs'=>$alllocs);
			break;	
				
			case 'getuser':
					$allusers=Users::model()->findAll(array('condition'=>'Id !=:uid ',
										 'params'=>array(':uid'=>$_GET['id']),));
					$models=array();
					foreach($allusers as $u)
					{
						$models[]=(object)array('Email'=>$u->Email,'Username'=>$u->Username);
					}
					$u =Users::model()->findByPk($_GET['id']);
					
					$appsections=array();
					$sections=array();
					
					if(!empty($u->userapppermissions))
					{
						
						foreach($u->userapppermissions as $s)
						{
							$sections[]=$s->SectionId;
							$appsections[]=(object)array('Id'=>$s->Id,'SectionId'=>$s->SectionId,'Section'=>$s->section->Section,
							'Description'=>$s->section->Description,
							'Group'=>$s->section->Group,'C'=>$s->C,'R'=>$s->R,'U'=>$s->U,'D'=>$s->D,'A'=>$s->A,'Ch'=>$s->Ch,
							'Print'=>$s->Print,'SM'=>$s->SM,
							'IsC'=>$s->section->C,'IsR'=>$s->section->R,'IsU'=>$s->section->U,'IsSM'=>$s->section->SM,'IsD'=>$s->section->D,'IsA'=>$s->section->A,'IsCh'=>$s->section->Ch,'IsPrint'=>$s->section->Print,);
						}
						
						$allsections=Appsections::model()->findAll(array('condition'=>'Status="1"'));
						foreach($allsections as $s)
						{
							if(!in_array($s->Id,$sections))
							{
								$appsections[]=(object)array('SectionId'=>$s->Id,'Section'=>$s->Section,
								'Description'=>$s->Description,'Group'=>$s->Group,'C'=>0,'R'=>0,'U'=>0,'D'=>0,'A'=>0,'Ch'=>0,'Print'=>0,'SM'=>0,
								'IsC'=>$s->C,'IsR'=>$s->R,'IsU'=>$s->U,'IsSM'=>$s->SM,'IsD'=>$s->D,'IsA'=>$s->A,'IsCh'=>$s->Ch,'IsPrint'=>$s->Print,);
							}
							
						}
						
					}
					else
					{
						$allsections=Appsections::model()->findAll();
						foreach($allsections as $s)
						{
							$appsections[]=(object)array('SectionId'=>$s->Id,'Section'=>$s->Section,'Description'=>$s->Description,'Group'=>$s->Group,'C'=>0,'R'=>0,'U'=>0,'D'=>0,'A'=>0,'Ch'=>0,'Print'=>0,'SM'=>0,
								'IsC'=>$s->C,'IsR'=>$s->R,'IsU'=>$s->U,'IsSM'=>$s->SM,'IsD'=>$s->D,'IsA'=>$s->A,'IsCh'=>$s->Ch,'IsPrint'=>$s->Print,);
							
							
						}
						
					}
					
					$roleid="";
					$rolename="";
					foreach($u->userinroles as $r)
					{
						$roleid=$r->RoleId;
						$rolename=$r->role->Role;
					}
					
					$ubrans=array();
					$ubrannames=[];
					foreach($u->userinbranches as $b)
					{
						$ubrans[]=$b->BranchId;
						$ubrannames[]=$b->branch->Name;
					}
					
					$user=(object)array('Id'=>$u->Id,'Email'=>$u->Email,'Username'=>$u->Username,'ContactNo'=>$u->ContactNo,'FName'=>$u->FName,'MName'=>$u->MName,'LName'=>$u->LName,'Branches'=>$ubrans,'DelBranches'=>[],'ubrannames'=>$ubrannames,
					'RoleName'=>$rolename,'RoleId'=>$roleid,'Department'=>$u->Department,'Designation'=>$u->Designation,'Appsections'=>$appsections);
					$roles=Roles::model()->findAll();
										
										foreach($roles as $r)
										{
											$rappsections=array();
											$rsections=array();
					
					if(!empty($r->roleapppermissions))
					{
						
						foreach($r->roleapppermissions as $s)
						{
							$rsections[]=$s->SectionId;
							$rappsections[]=(object)array('Id'=>$s->Id,'SectionId'=>$s->SectionId,'Section'=>$s->section->Section,'Description'=>$s->section->Description,
							'Group'=>$s->section->Group,'C'=>$s->C,'R'=>$s->R,'U'=>$s->U,'D'=>$s->D,'A'=>$s->A,'Ch'=>$s->Ch,
							'Print'=>$s->Print,'SM'=>$s->SM,
							'IsC'=>$s->section->C,'IsR'=>$s->section->R,'IsU'=>$s->section->U,'IsSM'=>$s->section->SM,'IsD'=>$s->section->D,'IsA'=>$s->section->A,'IsCh'=>$s->section->Ch,'IsPrint'=>$s->section->Print,);
						}
						
						
					
						
					}
				
					
					$allroles[]=(object)array('Id'=>$r->Id,'Role'=>$r->Role,'Appsections'=>$rappsections);
					
										}
										
										$branches=Branches::model()->findAll();
										
										$allbranches=array();
					foreach($branches as $b)
					{
						$allbranches[]=(object)array('BranchId'=>$b->Id,'Name'=>$b->Name);
					}
						
					$model=(object)array('User'=>$user,'Users'=>$models,'Roles'=>$allroles,'allbranches'=>$allbranches);
						
				break;	
				
				
				
			case 'mdsedit':
			
					
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
						if($type==='EN' || $type==='DIN' || $type==='BS' || $type==='IS')
						{
							$substandard=$c->Grade." ".$c->Number;
						}
						if($type==='SAE' )
						{
							$substandard=$c->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$c->Grade;
						}
						if($type==='ISO')
						{
							$substandard=$c->Class." ".$c->Property;
						}
						if($type==='A' )
						{
							$substandard=$c->Grade." ".$c->UNS." ".$c->Material;
							$substandard=$c->Grade." ".$c->UNS." ".$c->Material;
						}
						
						$csubstd[]=(object)array('Id'=>$c->Id,'SubStandard'=>$substandard,'StandardId'=>$s->Id,'Standard'=>$s->Standard);
						}
						
						
						foreach($s->mechbasics as $m)
						{
						
						$type=$s->Type;
						$substandard="";
						if($type==='EN' || $type==='DIN' || $type==='BS' || $type==='IS')
						{
							$substandard=$m->Grade." ".$m->Number." ( ".$m->Diameter.") diameter";
						}
						if($type==='SAE' )
						{
							$substandard=$m->Number." ( ".$m->Diameter.") diameter";
						}
						if($type==='ASTM')
						{
							$substandard=$m->Grade." ( ".$m->Diameter.") diameter";
						}
						if($type==='A' )
						{
							$substandard=$m->Grade." ".$m->UNS." ".$m->Material." ( ".$m->Diameter.") diameter";
						}						
						if($type==='ISO')
						{
							$substandard=$m->Class." ".$m->Property." ( ".$m->Diameter.") diameter";
						}
						
						$msubstd[]=(object)array('Id'=>$m->Id,'SubStandard'=>$substandard,'StandardId'=>$s->Id,'Standard'=>$s->Standard,);
						}
						
						$allstds[]=(object)array('Id'=>$s->Id,'Standard'=>$s->Standard);
					}
					
			
			
					$md =Mds::model()->findByPk($_GET['id']);
					
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
					$dcarb=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="CDC" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dcarb))
					{		
						$d=$dcarb;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->mdsmechdetails;
						$cd=(object)array('Other'=>"");
				
						// foreach($idetails as $k)
						// {
							// if($k->TestDetails==='CDC')
							// {
								// $cd=(object)array('Other'=>$k->Other);
							// }
							
						// }
								
						$carbdecarb=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'CDC'=>$cd);
					}	

/*--------Casedepth----------*/
					$dcase=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="CD" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dcase))
					{		
						$d=$dcase;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						
						$cd=(object)array('Other'=>"");
												
						$casedepth=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,);
					}	



					/*--------Chemical----------*/
							// $ecrs=Elements::model()->findAll();
						// $allecrs=array();			 
						// foreach($ecrs as $e)
						// {
							// $allecrs[]=(object)array('ElementId'=>$e->Id,'Element'=>$e->Symbol,'Max'=>"",'Min'=>"");
						
						// }
						$sds=array();
						$usedele=array();
						$newels=array();
						$allecrs=array();
							$alles=Elements::model()->findAll();
							foreach($alles as $e)
							{
								$newels[]=$e->Id;								
							}
							
					$dchem=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="C" ',
							'params'=>array(':mdsid'=>$md->Id)));
					if(!empty($dchem))
					{		$d=$dchem;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$eles=$d->mdschemdetails;
					
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
						// if(empty($sds))
						// {
							// foreach($alles as $e)
							// {
								// $sds[]=(object)array('ElementId'=>$e->Id,'Element'=>$e->Symbol,'Max'=>"",'Min'=>"",'Value'=>"");;								
							// }
						// }
						
						$chemical=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'allelements'=>$sds);
					}
					else
					{
						foreach($alles as $e)
							{
								$sds[]=(object)array('ElementId'=>$e->Id,'Element'=>$e->Symbol,'Max'=>"",'Min'=>"",'Value'=>"");;								
							}
						$chemical=(object)array('allelements'=>$sds);
						
					}
										
		
/*--------Grain Size----------*/
					$dgrain=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="GS" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dgrain))
					{		
						$d=$dgrain;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						
						$cd=(object)array('Other'=>"");
												
						$grainsize=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,);
					}


			/*-------Hardness HV----------*/
					$dhardHV=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="H" AND HtypeId="2"',
							'params'=>array(':mdsid'=>$md->Id)));
					if(!empty($dhardHV))
					{
						$d=$dhardHV;
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
					$hdetails=$d->mdsmechdetails;
					$vic=(object)array('LoadId'=>"",'Min'=>"",'Max'=>"");
				
					foreach($hdetails as $k)
					{
						if($k->TestDetails==='HV')
						{
							$vic=(object)array('LoadId'=>$k->LoadId,'Min'=>$k->Min,'Max'=>$k->Max);
						}
					
					}
								
					$hardHV=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
					'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'HV'=>$vic,);	
					}
					else
					{
						
					}
					
		/*-------Hardness- Micro HV---------*/
					$dhardMHV=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="H" AND HtypeId="3"',
							'params'=>array(':mdsid'=>$md->Id)));
					if(!empty($dhardMHV))
					{
						$d=$dhardMHV;
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
					$hdetails=$d->mdsmechdetails;
					$mvic=(object)array('LoadId'=>"",'Min'=>"",'Max'=>"");
				
					foreach($hdetails as $k)
					{
						if($k->TestDetails==='MicroHV')
						{
							$mvic=(object)array('LoadId'=>$k->LoadId,'Min'=>$k->Min,'Max'=>$k->Max);
						}
					
					}
								
					$hardMHV=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
					'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'MicroHV'=>$mvic,
					);	
					}
				/*-------Hardness-Brinell---------*/
					$dhardHBW=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="H" AND HtypeId="1"',
							'params'=>array(':mdsid'=>$md->Id)));
					if(!empty($dhardHBW))
					{
						$d=$dhardHBW;
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
					$hdetails=$d->mdsmechdetails;
						$brin=(object)array('LoadId'=>"",'Min'=>"",'Max'=>"");
					
					foreach($hdetails as $k)
					{
						
						if($k->TestDetails==='HBW')
						{
							$brin=(object)array('LoadId'=>$k->LoadId,'Min'=>$k->Min,'Max'=>$k->Max);
						}
					
					}
								
					$hardHBW=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
					'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'HBW'=>$brin);	
					}
			/*-------Hardness-HRB---------*/
					$dhardHRB=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="H" AND HtypeId="4"',
							'params'=>array(':mdsid'=>$md->Id)));
					if(!empty($dhardHRB))
					{
						$d=$dhardHRB;
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
					$hdetails=$d->mdsmechdetails;
					$rb=(object)array('LoadId'=>"",'Min'=>"",'Max'=>"");
					
					foreach($hdetails as $k)
					{
						if($k->TestDetails==='HRB')
						{
							$rb=(object)array('LoadId'=>$k->LoadId,'Min'=>$k->Min,'Max'=>$k->Max);
						}
						
					}
								
					$hardHRB=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
					'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'HRB'=>$rb);	
					}
			/*-------Hardness--HRC--------*/
					$dhardHRC=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="H" AND HtypeId="5"',
							'params'=>array(':mdsid'=>$md->Id)));
					if(!empty($dhardHRC))
					{
						$d=$dhardHRC;
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
					$hdetails=$d->mdsmechdetails;
						$rc=(object)array('LoadId'=>"",'Min'=>"",'Max'=>"");
					foreach($hdetails as $k)
					{
						if($k->TestDetails==='HRC')
						{
							$rc=(object)array('LoadId'=>$k->LoadId,'Min'=>$k->Min,'Max'=>$k->Max);
						}
					}
								
					$hardHRC=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
					'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'HRC'=>$rc);	
					}
		
		/*--------Hydrogen----------*/
					$dhydro=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="HET" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dhydro))
					{		
						$d=$dhydro;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->mdsmechdetails;
						$hydro=(object)array('Other'=>"");
				
					
								
						$hydrogen=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications);
					}					

		/*--------Impact----------*/
					$dimp=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="I" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dimp))
					{		
						$d=$dimp;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->mdsmechdetails;
						$imp=(object)array('Min'=>"",'Max'=>"",'TempId'=>"");
				
						foreach($idetails as $k)
						{
							if($k->TestDetails==='I')
							{
								$imp=(object)array('TempId'=>$k->TempId,'Min'=>$k->Min,'Max'=>$k->Max);
							}
							
						}
								
						$impact=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'I'=>$imp);
					}
		
			/*--------Inclusion Rating K method----------*/
					$inck=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="IRK" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($inck))
					{		
						$d=$inck;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->mdsmechdetails;
						$irk=(object)array('Min'=>"",'Max'=>"");
				
						foreach($idetails as $k)
						{
							if($k->TestDetails==='IRK')
							{
								$irk=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
							}
							
						}
								
						$inclusionk=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'IRK'=>$irk);
					}	

/*--------Inclusion Rating Worst method----------*/
					$incw=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="IRW" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($incw))
					{		
						$d=$incw;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->mdsmechdetails;
						$irw=(object)array('Min'=>"",'Max'=>"");
				
						foreach($idetails as $k)
						{
							if($k->TestDetails==='IRW')
							{
								$irw=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
							}
							
						}
								
						$inclusionw=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'IRW'=>$irw);
					}											

		/*--------Micro case ----------*/
					$dmicrocase=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="MCD" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dmicrocase))
					{		
						$d=$dmicrocase;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->mdsmechdetails;
						$ms=(object)array('Min'=>"",'Max'=>"");
				
						
								
						$microcase=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'MS'=>$ms);
					}							
/*--------Micro coating----------*/
					$dmicrocoat=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="MCT" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dmicrocoat))
					{		
						$d=$dmicrocoat;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
					
						$ms=(object)array('Min'=>"",'Max'=>"");
				
						
								
						$microcoat=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'MS'=>$ms);
					}							
/*--------Microstruct----------*/
					$dmicrodecarb=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="MDC" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dmicrodecarb))
					{		
						$d=$dmicrodecarb;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->mdsmechdetails;
						$ms=(object)array('Min'=>"",'Max'=>"");
				
						
								
						$microdecarb=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'MS'=>$ms);
					}							
/*--------Microstruct----------*/
					$dmicrostruct=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="MS" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dmicrostruct))
					{		
						$d=$dmicrostruct;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->mdsmechdetails;
						$ms=(object)array('Min'=>"",'Max'=>"");
				
						// foreach($idetails as $k)
						// {
							// if($k->TestDetails==='MS')
							// {
								// $ms=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
							// }
							
						// }
								
						$microstruct=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'MS'=>$ms);
					}							

		/*--------Proof Load----------*/
					$dpl=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="PL" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dpl))
					{		
						$d=$dpl;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->mdsmechdetails;
						$pl=(object)array('Min'=>"",'Max'=>"");
				
						foreach($idetails as $k)
						{
							if($k->TestDetails==='PL')
							{
								$pl=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
							}
							
						}
								
						$proofload=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'PL'=>$pl);
					}					
		
		/*--------Shear Strength----------*/
					$dss=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="SS" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dss))
					{		
						$d=$dss;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->mdsmechdetails;
						$ss=(object)array('Min'=>"",'Max'=>"");
				
						foreach($idetails as $k)
						{
							if($k->TestDetails==='SS')
							{
								$ss=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
							}
							
						}
								
						$shear=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'SS'=>$ss);
					}					
		
			/*--------Tensile----------*/
					$dtens=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="T" ',
							'params'=>array(':mdsid'=>$md->Id)));
					if(!empty($dtens))
					{	
						$d=$dtens;
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
					$details=$d->mdsmechdetails;
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
								
					$tensile=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
					'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'PS'=>$ps,'UTS'=>$uts,'E'=>$el,
					'R'=>$red);	
					}					
			
/*--------Threadlap----------*/
					$dthread=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="THL" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dthread))
					{		
						$d=$dthread;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->mdsmechdetails;
						$tl=(object)array('Other'=>"");
				
						foreach($idetails as $k)
						{
							if($k->TestDetails==='THL')
							{
								$tl=(object)array('Other'=>$k->Other);
							}
							
						}
								
						$threadlap=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'THL'=>$tl);
					}		
			

		/*--------Torque tension----------*/
					$dtqen=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="TT" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dtqen))
					{		
						$d=$dtqen;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->mdsmechdetails;
						$tq=(object)array('Min'=>"",'Max'=>'');
				
						foreach($idetails as $k)
						{
							if($k->TestDetails==='TT')
							{
								$tq=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
							}
							
						}
								
						$tension=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'TT'=>$tq);
					}
	
	/*--------Wedge----------*/
					$dwedge=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="W" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dwedge))
					{		
						$d=$dwedge;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->mdsmechdetails;
						$w=(object)array('Other'=>"");
				
						// foreach($idetails as $k)
						// {
							// if($k->TestDetails==='THL')
							// {
								// $tl=(object)array('Other'=>$k->Other);
							// }
							
						// }
								
						$wedge=(object)array('Id'=>$d->Id,'MdsId'=>$d->MdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'W'=>$w);
					}		

	/*--------------------------------*/
				
					$mds=(object)array('Id'=>$md->Id,'MdsNo'=>$md->MdsNo,'Material'=>$md->Material,'Standard'=>$md->Standard,'Size'=>$md->Size,
						'Remark'=>$md->Remark,'carbdecarb'=>$carbdecarb,'casedepth'=>$casedepth,'chemical'=>$chemical,
						'grainsize'=>$grainsize,'hardHV'=>$hardHV,'hardMHV'=>$hardMHV,'hardHBW'=>$hardHBW,'hardHRB'=>$hardHRB,'hardHRC'=>$hardHRC,
						'hydrogen'=>$hydrogen,'impact'=>$impact,'inclusionk'=>$inclusionk,'inclusionw'=>$inclusionw,'microcase'=>$microcase,
						'microcoat'=>$microcoat,'microdecarb'=>$microdecarb,'microstruct'=>$microstruct,'proofload'=>$proofload,'shear'=>$shear,
						'tensile'=>$tensile,'tension'=>$tension,'threadlap'=>$threadlap,'wedge'=>$wedge,
						
						);
						
						
						
						
							
					
						$dropload=Dropdownload::model()->findAll();
					$droptemps=Dropdowntemp::model()->findAll();
					
					$methods=Testmethods::model()->findAll();
					$alltestmethods=array();
					foreach($methods as $l)
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
					
					$data=(object)array('allelements'=>$allecrs,'standards'=>$allstds,'Csubstandards'=>$csubstd,'Msubstandards'=>$msubstd,
						'testmethods'=>$alltestmethods,'Hardloads'=>$dropload,'Impacttemps'=>$droptemps,'mds'=>$mds);
						$this->_sendResponse(200, CJSON::encode($data));
					
			break;
			
			case 'tdsedit':
				

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
						if($type==='EN' || $type==='DIN' || $type==='BS' || $type==='IS')
						{
							$substandard=$c->Grade." ".$c->Number;
						}
						if($type==='SAE' )
						{
							$substandard=$c->Number;
						}
						if($type==='ASTM')
						{
							$substandard=$c->Grade;
						}
						if($type==='ISO')
						{
							$substandard=$c->Class." ".$c->Property;
						}
						if($type==='A' )
						{
							$substandard=$c->Grade." ".$c->UNS." ".$c->Material;
						}
						
						$csubstd[]=(object)array('Id'=>$c->Id,'SubStandard'=>$substandard,'StandardId'=>$s->Id,'Standard'=>$s->Standard);
						}
						
						
						foreach($s->mechbasics as $m)
						{
						
						$type=$s->Type;
						$substandard="";
						if($type==='EN' || $type==='DIN' || $type==='BS' || $type==='IS')
						{
							$substandard=$m->Grade." ".$m->Number." ( ".$m->Diameter.") diameter";
						}
						if($type==='SAE' )
						{
							$substandard=$m->Number." ( ".$m->Diameter.") diameter";
						}
						if($type==='ASTM')
						{
							$substandard=$m->Grade." ( ".$m->Diameter.") diameter";
						}
						if($type==='A' )
						{
							$substandard=$m->Grade." ".$m->UNS." ".$m->Material." ( ".$m->Diameter.") diameter";
						}						
						if($type==='ISO')
						{
							$substandard=$m->Class." ".$m->Property." ( ".$m->Diameter.") diameter";
						}
						
						$msubstd[]=(object)array('Id'=>$m->Id,'SubStandard'=>$substandard,'StandardId'=>$s->Id,'Standard'=>$s->Standard,);
						}
						
						$allstds[]=(object)array('Id'=>$s->Id,'Standard'=>$s->Standard);
					}
					
			
			
					$md =Tds::model()->findByPk($_GET['id']);
					
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
						$tensile=(object)array('PS'=>(object)array('Min'=>"",'Max'=>""));
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
				
						// foreach($idetails as $k)
						// {
							// if($k->TestDetails==='CDC')
							// {
								// $cd=(object)array('Other'=>$k->Other);
							// }
							
						// }
								
						$carbdecarb=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'CDC'=>$cd);
					}	

/*--------Casedepth----------*/
					$dcase=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="CD" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dcase))
					{		
						$d=$dcase;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						
						$cd=(object)array('Other'=>"");
												
						$casedepth=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,);
					}	



					/*--------Chemical----------*/
							// $ecrs=Elements::model()->findAll();
						// $allecrs=array();			 
						// foreach($ecrs as $e)
						// {
							// $allecrs[]=(object)array('ElementId'=>$e->Id,'Element'=>$e->Symbol,'Max'=>"",'Min'=>"");
						
						// }
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
						
							foreach($d->tdschemdetails as $e)
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

						
						$chemical=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'allelements'=>$sds);
					}
					else
					{
						foreach($alles as $e)
							{
								$sds[]=(object)array('ElementId'=>$e->Id,'Element'=>$e->Symbol,'Max'=>"",'Min'=>"",'Value'=>"");;								
							}
						$chemical=(object)array('allelements'=>$sds);
						
					}
							
										
		
/*--------Grain Size----------*/
					$dgrain=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="GS" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dgrain))
					{		
						$d=$dgrain;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						
						$cd=(object)array('Other'=>"");
												
						$grainsize=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,);
					}


			/*-------Hardness HV----------*/
					$dhardHV=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="H" AND HtypeId="2"',
							'params'=>array(':mdsid'=>$md->Id)));
					if(!empty($dhardHV))
					{
						$d=$dhardHV;
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
					$hdetails=$d->tdsmechdetails;
					$vic=(object)array('LoadId'=>"",'Min'=>"",'Max'=>"");
				
					foreach($hdetails as $k)
					{
						if($k->TestDetails==='HV')
						{
							$vic=(object)array('LoadId'=>$k->LoadId,'Min'=>$k->Min,'Max'=>$k->Max);
						}
					
					}
								
					$hardHV=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
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
					$mvic=(object)array('LoadId'=>"",'Min'=>"",'Max'=>"");
				
					foreach($hdetails as $k)
					{
						if($k->TestDetails==='MicroHV')
						{
							$mvic=(object)array('LoadId'=>$k->LoadId,'Min'=>$k->Min,'Max'=>$k->Max);
						}
					
					}
								
					$hardMHV=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
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
						$brin=(object)array('LoadId'=>"",'Min'=>"",'Max'=>"");
					
					foreach($hdetails as $k)
					{
						
						if($k->TestDetails==='HBW')
						{
							$brin=(object)array('LoadId'=>$k->LoadId,'Min'=>$k->Min,'Max'=>$k->Max);
						}
					
					}
								
					$hardHBW=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
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
					$rb=(object)array('LoadId'=>"",'Min'=>"",'Max'=>"");
					
					foreach($hdetails as $k)
					{
						if($k->TestDetails==='HRB')
						{
							$rb=(object)array('LoadId'=>$k->LoadId,'Min'=>$k->Min,'Max'=>$k->Max);
						}
						
					}
								
					$hardHRB=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
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
						$rc=(object)array('LoadId'=>"",'Min'=>"",'Max'=>"");
					foreach($hdetails as $k)
					{
						if($k->TestDetails==='HRC')
						{
							$rc=(object)array('LoadId'=>$k->LoadId,'Min'=>$k->Min,'Max'=>$k->Max);
						}
					}
								
					$hardHRC=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
					'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'HRC'=>$rc);	
					}
		
		/*--------Hydrogen----------*/
					$dhydro=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="HET" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dhydro))
					{		
						$d=$dhydro;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->tdsmechdetails;
						$hydro=(object)array('Other'=>"");
				
					
								
						$hydrogen=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications);
					}					

		/*--------Impact----------*/
					$dimp=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="I" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dimp))
					{		
						$d=$dimp;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->tdsmechdetails;
						$imp=(object)array('TempId'=>"",'Min'=>"",'Max'=>"");
				
						foreach($idetails as $k)
						{
							if($k->TestDetails==='I')
							{
								$imp=(object)array('TempId'=>$k->TempId,'Min'=>$k->Min,'Max'=>$k->Max);
							}
							
						}
								
						$impact=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'I'=>$imp);
					}
		
			/*--------Inclusion Rating K method----------*/
					$inck=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="IRK" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($inck))
					{		
						$d=$inck;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->tdsmechdetails;
						$irk=(object)array('Min'=>"",'Max'=>"");
				
						foreach($idetails as $k)
						{
							if($k->TestDetails==='IRK')
							{
								$irk=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
							}
							
						}
								
						$inclusionk=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'IRK'=>$irk);
					}	

/*--------Inclusion Rating Worst method----------*/
					$incw=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="IRW" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($incw))
					{		
						$d=$incw;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->tdsmechdetails;
						$irw=(object)array('Min'=>"",'Max'=>"");
				
						foreach($idetails as $k)
						{
							if($k->TestDetails==='IRW')
							{
								$irw=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
							}
							
						}
								
						$inclusionw=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'IRW'=>$irw);
					}											

		/*--------Micro case ----------*/
					$dmicrocase=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="MCD" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dmicrocase))
					{		
						$d=$dmicrocase;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->tdsmechdetails;
						$ms=(object)array('Min'=>"",'Max'=>"");
				
						
								
						$microcase=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'MS'=>$ms);
					}							
/*--------Micro coating----------*/
					$dmicrocoat=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="MCT" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dmicrocoat))
					{		
						$d=$dmicrocoat;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
					
						$ms=(object)array('Min'=>"",'Max'=>"");
				
						
								
						$microcoat=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'MS'=>$ms);
					}							
/*--------Microstruct----------*/
					$dmicrodecarb=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="MDC" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dmicrodecarb))
					{		
						$d=$dmicrodecarb;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						//$idetails=$d->tdsmechdetails;
						$ms=(object)array('Min'=>"",'Max'=>"");
				
						
								
						$microdecarb=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'MS'=>$ms);
					}							
/*--------Microstruct----------*/
					$dmicrostruct=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="MS" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dmicrostruct))
					{		
						$d=$dmicrostruct;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->tdsmechdetails;
						$ms=(object)array('Min'=>"",'Max'=>"");
				
						// foreach($idetails as $k)
						// {
							// if($k->TestDetails==='MS')
							// {
								// $ms=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
							// }
							
						// }
								
						$microstruct=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
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
								
						$proofload=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'PL'=>$pl);
					}					
		
		/*--------Shear Strength----------*/
					$dss=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="SS" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dss))
					{		
						$d=$dss;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->tdsmechdetails;
						$ss=(object)array('Min'=>"",'Max'=>"");
				
						foreach($idetails as $k)
						{
							if($k->TestDetails==='SS')
							{
								$ss=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
							}
							
						}
								
						$shear=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'SS'=>$ss);
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
								
					$tensile=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
					'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'PS'=>$ps,'UTS'=>$uts,'E'=>$el,
					'R'=>$red);	
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
								
						$threadlap=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'THL'=>$tl);
					}		
			

		/*--------Torque tension----------*/
					$dtqen=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="TT" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dtqen))
					{		
						$d=$dtqen;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->tdsmechdetails;
						$tq=(object)array('Min'=>"",'Max'=>'');
				
						foreach($idetails as $k)
						{
							if($k->TestDetails==='TT')
							{
								$tq=(object)array('Min'=>$k->Min,'Max'=>$k->Max);
							}
							
						}
								
						$tension=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'TT'=>$tq);
					}
	
	/*--------Wedge----------*/
					$dwedge=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="W" ',
							'params'=>array(':mdsid'=>$md->Id)));
							
					if(!empty($dwedge))
					{		
						$d=$dwedge;				
						$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
						$idetails=$d->tdsmechdetails;
						$w=(object)array('Other'=>"");
				
						// foreach($idetails as $k)
						// {
							// if($k->TestDetails==='THL')
							// {
								// $tl=(object)array('Other'=>$k->Other);
							// }
							
						// }
								
						$wedge=(object)array('Id'=>$d->Id,'TdsId'=>$d->TdsId,'MDTest'=>$d->MDTest,'StandardId'=>$d->StandardId,'SubStandardId'=>$d->SubStandardId,
						'TestMethod'=>$testmethod,'Frequency'=>$d->Frequency,'Specifications'=>$d->Specifications,'W'=>$w);
					}		

	/*--------------------------------*/
				
					$tds=(object)array('Id'=>$md->Id,'TdsNo'=>$md->TdsNo,'Material'=>$md->Material,'Standard'=>$md->Standard,'Size'=>$md->Size,
						'Remark'=>$md->Remark,'carbdecarb'=>$carbdecarb,'casedepth'=>$casedepth,'chemical'=>$chemical,
						'grainsize'=>$grainsize,'hardHV'=>$hardHV,'hardMHV'=>$hardMHV,'hardHBW'=>$hardHBW,'hardHRB'=>$hardHRB,'hardHRC'=>$hardHRC,
						'hydrogen'=>$hydrogen,'impact'=>$impact,'inclusionk'=>$inclusionk,'inclusionw'=>$inclusionw,'microcase'=>$microcase,
						'microcoat'=>$microcoat,'microdecarb'=>$microdecarb,'microstruct'=>$microstruct,'proofload'=>$proofload,'shear'=>$shear,
						'tensile'=>$tensile,'tension'=>$tension,'threadlap'=>$threadlap,'wedge'=>$wedge,
						
						);
						
										
						
					$dropload=Dropdownload::model()->findAll();
					$droptemps=Dropdowntemp::model()->findAll();		
					
					
					
					$methods=Testmethods::model()->findAll();
					$alltestmethods=array();
					foreach($methods as $l)
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
					
					$data=(object)array('allelements'=>$allecrs,'standards'=>$allstds,'Csubstandards'=>$csubstd,'Msubstandards'=>$msubstd,
						'testmethods'=>$alltestmethods,'Hardloads'=>$dropload,'Impacttemps'=>$droptemps,'tds'=>$tds);
						$this->_sendResponse(200, CJSON::encode($data));
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
						$usedele=array();
						$newels=array();
						
						foreach($stdsub->chemicalcompositions as $e)
						{
							$elements[]=(object)array('Id'=>$e->Id,'Element'=>$e->element->Symbol,'ElementId'=>$e->ElementId,'Min'=>$e->Min,'Max'=>$e->Max,
											'CBId'=>$e->CBId);
							$usedele[]=$e->ElementId;				
						}
						
						$alles=Elements::model()->findAll();
						foreach($alles as $e)
					{
						$newels[]=$e->Id;
						
					}
							 $ecrs=array_diff($newels,$usedele)	;
						
							$allecrs=array();			 
					foreach($ecrs as $ei)
					{
						$e=Elements::model()->findByPk($ei);
						$allecrs[]=(object)array('ElementId'=>$e->Id,'Element'=>$e->Symbol,'Max'=>"",'Min'=>"");
						
					}
						$elements=array_merge($elements,$allecrs);
						$range=(object)array('Id'=>$stdsub->Id,'StandardId'=>$stdsub->StandardId,'Grade'=>$stdsub->Grade,'Number'=>$stdsub->Number,
						'Class'=>$stdsub->Class,'Property'=>$stdsub->Property,'allelements'=>$elements,'Type'=>$stdsub->Type);
					
					
					
						$model=(object)array('standards'=>$standards,'range'=>$range);
						
						break;

								
			case 'stdedit':
					$std = Standards::model()->findByPk($_GET['id']);
					
					$alltypes=Indutype::model()->findAll();
								
					$model=(object)array('std'=>$std,'alltypes'=>$alltypes);
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
					case 'searchmdstds': $model=new Mdstds;break;
				case 'searchsubstd':$model=new Substandards;break;
				
				
				case 'adddropdown':$model=new Dropdowns;break;
				case 'mdstdsadd':$model=new Mdstds;break;
				
				case 'testcondadd':$model=new Testconditions;break;
				case 'tparamadd': $model=new Testobsparams;break;
				case 'exttestsetting':	$model=new Externaltests;  break;
				
				case 'attachcategory':	$model=new Attachcategory;  break;
				
				 case 'checkpath' : $model=new Settings; break;
				case 'adduser':$model=new Users;break;
				case 'customeradd':$model=new Customerinfo;break;
				case 'customer':$model=new Customerinfo;break;
				case 'addsupplier':$model=new Suppliers;break;
				
				case 'methodadd':$model=new Testmethods;break;
				
				case 'ssthr':	$model=new Ssthours;break;
				
				case 'droptemp':	$model=new Dropdowntemp;break;
				case 'hardequip':	$model=new Hardnessequip;break;
				case 'hardindent':	$model=new Hardnessindent;break;
				
				
				case 'dropload':	$model=new Dropdownload;break;
				
				case 'mdsadddata':	$model=new Mds;break;
				
				case 'tdsadddata':	$model=new Tds;break;
			
				case 'substdadd':	$model=new Substandards;break;
				
				case 'stdadddata':	$model=new Standards;break;
				
			
				  
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
			   case 'searchmdstds': 
			 case 'searchsubstd':
				case 'substdadd':	break;
			 
				
			 
			  case 'checkpath' :break;
			  case 'customeradd':
			    case 'testcondadd':
				  case 'mdstdsadd':
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
				case 'methodadd':					
				case 'tdsadddata':
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
			   
			   
			   
			   			case 'searchmdstds':
				try{
					
						$text=$data['text'];
					//$u=MyFunctions::gettokenuser();
					
					$totalitems=25;
					
					$allmdstds=[];
					$mdstds=Mdstds::model()->findAll(['order'=>'Id Desc',
					'condition'=>'No LIKE :no OR Type LIKE :no OR Description LIKE :no ',
					'params'=>[':no'=>'%'.$text.'%',],
					'limit' =>25,
					 ]);
					 
					 
					 
					foreach($mdstds as $md)
					{
						$mdstdstests=[];
						foreach($md->mdstdstests as $t)
						{
							$tobparams=[];
							foreach($t->mdstdstestobsdetails as $d)
								{
									
									$tobparams[]=(object)['Id'=>$d->Id,'MTTID'=>$d->MTTID,
									'PID'=>$d->PID,'Parameter'=>$d->p->Parameter,'SpecMin'=>$d->SpecMin,'SpecMax'=>$d->SpecMax,
									];
								}
							
							$tbaseparams=[];
							foreach($t->mdstdstestbasedetails as $d)
								{
									
									$tbaseparams[]=(object)['Id'=>$d->Id,'MTTID'=>$d->MTTID,
									'PID'=>$d->PID,'Parameter'=>$d->p->Parameter,'Value'=>$d->Value,
									];
								}
							
							$std=empty($t->sSD)?null:$t->sSD->std->Standard." ".$t->sSD->Grade;
							
							
							$mdstdstests[]=(object)['Id'=>$t->Id,'MTID'=>$t->MTID,'TID'=>$t->TID,
							'TUID'=>$t->TUID,'SSDID'=>$t->SSDID,'TMID'=>$t->TMID,'Frequency'=>$t->Freq,
							'Method'=>$t->tM->Method,'TestName'=>$t->t->TestName,'Std'=>$std,
							'tobsparams'=>$tobparams,'tbaseparams'=>$tbaseparams
							];
							
						}
						
						
						$allmdstds[]=(object)['Id'=>$md->Id,'Type'=>$md->Type,'No'=>$md->No,
						'Description'=>$md->Description,'allmdstdstests'=>$mdstdstests, 'Uploads'=>$md->mdstdsuploads];
					}
					
					
					$result=(object)['allmdstds'=>$allmdstds,'totalitems'=>$totalitems];
					$this->_sendResponse(200, CJSON::encode($result));	
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
				}
				break;
				
			   case 'searchsubstd':
						try
						{
						
						$text=$data['text'];
					
					$allecrs=Substandards::model()->with('std')->findAll(array(
					'order'=>'t.Id Desc',
					'condition'=>'Grade LIKE :grade OR Standard LIKE :std',
					'params'=>[':grade'=>'%'.$text.'%',':std'=>'%'.$text.'%'],
					'limit' => 25,
    
	));
					
					$allsubdata=array();
					foreach($allecrs as $sub)
					{
						
						
						$params=array();
						$oldparams=array();
						$newparams=array();
						if(!empty($sub->stdsubdetails))
						{
								$testids=[];
					$testnames=[];
							$subdetails=$sub->stdsubdetails;
						foreach($subdetails as $e)
						{
							$tn=Tests::model()->find(['condition'=>'TUID =:tuid','params'=>[':tuid'=>$e->TUID]]);
							if($e->p->IsSpec)
							{
							$params[]=(object)array('Id'=>$e->Id,'PDType'=>$e->p->PDType,
							'PId'=>$e->PId,
							'PermMin'=>(float)$e->PermMin,'PermMax'=>(float)$e->PermMax,
							'SpecMin'=>$e->SpecMin,'SpecMax'=>$e->SpecMax,
											
							'IsMajor'=>$e->IsMajor,'Parameter'=>$e->p->Parameter,'PUnit'=>$e->p->PUnit,
							'PCatId'=>$e->p->PCatId,'CatName'=>empty($e->p->pCat)?"":$e->p->pCat->CatName,'Cost'=>$e->Cost,
							'TMID'=>$e->TMID,'TUID'=>$e->TUID,'TestName'=>$tn->TestName,
							'TestMethod'=>empty($e->TMID)?null:$e->tM->Method,
											'SubStdId'=>$e->SubStdId);
							}
							$oldparams[]=$e->PId;					
						}
						
					
					$substdtests=Substdtests::model()->findAll(['condition'=>'SSID=:ssid','params'=>[':ssid'=>$sub->Id]]);
				
					foreach($substdtests as $t)
					{
						$testids[]=$t->TID;
						$testnames[]=$t->t->TestName;
					}
					
							$allsubdata[]=(object)array('Id'=>$sub->Id,'StdId'=>$sub->StdId,'IndId'=>$sub->std->IndId,
							'TestIds'=>$testids,'Testnames'=>$testnames,
							'Industry'=>implode(" - ",array_reverse(MyFunctions::getParentCat($sub->std->IndId))),'Standard'=>$sub->std->Standard,
							'Grade'=>$sub->Grade,'ExtraInfo'=>$sub->ExtraInfo, 'Parameters'=>$params);
						}
						
					}
					
					
					
							$result=(object)['allsubdata'=>$allsubdata];
							$this->_sendResponse(200, CJSON::encode($result));
						}
						catch(Exception $e)
						{						
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
						}
						break;
			   
			   case 'mdstdsadd':
			    $transaction=$model->dbConnection->beginTransaction();
					try
					{
						$u=MyFunctions::gettokenuser();
						
						$model->CID=$u->CID;
						$model->CreatedOn=date('Y-m-d H:i:s');
						$model->save(false);
						
							foreach($data['allmdstdstests'] as $t)
							{
								$mt=new Mdstdstests;
								$mt->MTID=$model->getPrimaryKey();
								$mt->TID=$t['TID'];
								$mt->TUID=$t['TUID'];
								$mt->SSDID=$t['SSDID'];
								$mt->TMID=$t['TMID'];
								$mt->Freq=$t['Frequency'];
								$mt->CreatedOn=date('Y-m-d H:i:s');
								$mt->save(false);
								foreach($t['tbaseparams'] as $tb)
								{
									$mtbds=new Mdstdstestbasedetails;
									$mtbds->MTTID=$mt->getPrimaryKey();
									$mtbds->PID=$tb['PID'];
									$mtbds->Value=$tb['Value'];
									
									$mtbds->Modified=date('Y-m-d H:i:s');;
									$mtbds->save(false);
								}
								foreach($t['tobsparams'] as $tp)
								{
									$mtds=new Mdstdstestobsdetails;
									$mtds->MTTID=$mt->getPrimaryKey();
									$mtds->PID=$tp['PID'];
									$mtds->SpecMin=$tp['SpecMin'];
									$mtds->SpecMax=$tp['SpecMax'];
									$mtds->Modified=date('Y-m-d H:i:s');;
									$mtds->save(false);
								}
							}
							$transaction->commit();
					$this->_sendResponse(200, CJSON::encode($model->getPrimaryKey()));
					}
					catch(Exception $e)
					{
						$transaction->rollback();
						$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
					break;
					
			   case 'testcondadd':
			    $transaction=$model->dbConnection->beginTransaction();
				try
					{
						
						$model->save(false);
						
						$transaction->commit();
						$testconds=Testconditions::model()->findAll();
							$this->_sendResponse(200, CJSON::encode($testconds));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
			   
				break;
				
			   
			    case 'checkpath' :
			   if(empty($data['Path']))
				{
					$this->_sendResponse(401, CJSON::encode("NO Path set"));
				}
				else
				{	
				$file=$data['Path'];
				//$file="D:\\sachin\Dat Reports\carbdecarb";
				if(file_exists($file))
				{
					$this->_sendResponse(200, CJSON::encode("file location found"));
				}
				else
				{
					$this->_sendResponse(401, CJSON::encode("incorrect file location: ".$file));
				}
				
				}
			   break;
			   
			   case 'customeradd':
			   case 'customer':
			  $transaction=$model->dbConnection->beginTransaction();
				try
					{
						$u=MyFunctions::gettokenuser();
						
						$model->CID=$u->CID;
						$model->CreatedOn=date('Y-m-d H:i:s');
						$model->save(false);
						
						
					/*	$user=Users::model()->with(['userinroles'=>['condition'=>'RoleId ="3"']])->find(array('condition'=>'Email=:email ','params'=>[':email'=>$model->Email],'together'=>true));
						
						if(empty($user))
						{
							$user=new Users;
							$user->FName=$model->Name;
							$user->Email=$model->Email;
							$user->Username=$model->Email;
							$user->Status=1;
							$newpwd='reset@1234';
							// A higher "cost" is more secure but consumes more processing power
							$cost = 10;
						// Create a random salt
						// Create a random salt
									if (version_compare(PHP_VERSION, '7.0.0') >= 0) 
									{
										$salt = strtr(base64_encode(random_bytes(16)), '+', '.');
									}
									else
									{
										$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
									}
						// Prefix information about the hash so PHP knows how to verify it later.
						// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
						$salt =sprintf("$2a$%02d$", $cost) . $salt;
						// Value:
						// $2a$10$eImiTXuWVxfM37uY4JANjQ==

						// Hash the password with the salt
						$hashpwd = crypt($newpwd, $salt);
								
						
						$user->Password=$hashpwd;
						
						$user->CreationDate=date('Y-m-d H:i:s');
						$user->save(false);
						
						$uir=new Userinroles;
						$uir->UserId=$user->getPrimaryKey();
						$uir->RoleId=3;
						$uir->save(false);
						
						
						$raps=Roleapppermission::model()->findAll(array('condition'=>'RoleId=:role','params'=>[':role'=>3]));
						foreach($raps as $s)
						{
							$uapp=new Userapppermission;
							$uapp->UserId=$user->getPrimaryKey();
							$uapp->SectionId=$s->SectionId;
							$uapp->C=$s->C;
							$uapp->R=$s->R;
							$uapp->U=$s->U;
							$uapp->D=$s->D;
							$uapp->A=$s->A;
							$uapp->Ch=$s->Ch;							
							$uapp->Print=$s->Print;
							$uapp->SM=$s->SM;
							$uapp->LastModified=date('Y-m-d H:i:s');
							$uapp->save(false);
							
						}
						
						
					}*/
						
						foreach($data['Addresses'] as $ad)
						{
							$cadd=new Custaddresses;
							$cadd->CustId=$model->getPrimaryKey();
							$cadd->Name=$ad['Name'];
							$cadd->Email=$ad['Email'];
							$cadd->ContactNo=$ad['ContactNo'];
							$cadd->ContactPerson=$ad['ContactPerson'];
							$cadd->Address=$ad['Address'];
							$cadd->save(false);
						}
							
						
						$transaction->commit();
						$customers=Customerinfo::model()->findAll();
						
					$allcustomers=[];
					foreach($customers as $c)
					{
						$allcustomers[]=(object)['Id'=>$c->Id,'Name'=>$c->Name,'Email'=>$c->Email,'Addresses'=>$c->custaddresses];
					}
						$result=(object)['CustomerId'=>$model->getPrimaryKey(),'customers'=>$allcustomers];
							$this->_sendResponse(200, CJSON::encode($result));
						
					}
					catch(Exception $e)
					{
						$msg=$e->getMessage();
						$substring='Duplicate';
						if(str_contains($msg, $substring) )
						{
							$errmsg="Email already exists.";
						}
						else
						{
							$errmsg=$msg;
						}
							  $transaction->rollback();
							$this->_sendResponse(500, CJSON::encode($errmsg));
					}
			   
				break;
				
				 case 'addsupplier':
			   $transaction=$model->dbConnection->beginTransaction();
				try
					{
						$u=MyFunctions::gettokenuser();
						
						$model->CID=$u->CID;
						$model->CreatedOn=date('Y-m-d H:i:s');
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
			   
			   	case 'methodadd':
			   $transaction=$model->dbConnection->beginTransaction();
				try
					{
						$test=Tests::model()->findByPk($data['TestId']);
						$u=MyFunctions::gettokenuser();
						
						$model->CID=$u->CID;
						$model->TUID=$test->TUID;
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
						$u=MyFunctions::gettokenuser();
						//$model->Username=$model->Email;
						
						$newpwd=$data['Password'];
						// A higher "cost" is more secure but consumes more processing power
						$cost = 10;
						// Create a random salt
						// Create a random salt
									if (version_compare(PHP_VERSION, '7.0.0') >= 0) 
									{
										$salt = strtr(base64_encode(random_bytes(16)), '+', '.');
									}
									else
									{
										$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
									}
						// Prefix information about the hash so PHP knows how to verify it later.
						// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
						$salt =sprintf("$2a$%02d$", $cost) . $salt;
						// Value:
						// $2a$10$eImiTXuWVxfM37uY4JANjQ==

						// Hash the password with the salt
						$hashpwd = crypt($newpwd, $salt);
								
						$model->Username=$data['Email'];
						$model->Designation=$data['Designation'];
						$model->Password=$hashpwd;
						$model->CID=$u->CID;
						$model->CreationDate=date('Y-m-d H:i:s');
						$model->save(false);
						
						$uir=new Userinroles;
						$uir->UserId=$model->getPrimaryKey();
						$uir->RoleId=$data['RoleId'];
						$uir->save(false);
						
						foreach($data['Appsections'] as $s)
						{
							if(empty($s['Id']))
							{
								$uapp=new Userapppermission;
							}
							else
							{
								$uapp=Userapppermission::model()->findByPk($s['Id']);
							}
							
							$uapp->UserId=$model->getPrimaryKey();
							$uapp->SectionId=$s['SectionId'];
							$uapp->C=$s['C'];
							$uapp->R=$s['R'];
							$uapp->U=$s['U'];
							$uapp->D=$s['D'];
							$uapp->A=$s['A'];
							$uapp->Ch=$s['Ch'];							
							$uapp->Print=$s['Print'];
							$uapp->SM=$s['SM'];
							$uapp->LastModified=date('Y-m-d H:i:s');
							$uapp->save(false);
							
						}
						
							foreach($data['Branches'] as $b)
										{
												$ub=Userinbranches::model()->find(array('condition'=>'BranchId=:bid ANd UserId=:uid',
													'params'=>array(':bid'=>$b,':uid'=>$model->getPrimaryKey())));
											if(empty($ub))
											{
												$ub=new Userinbranches;
												$ub->UserId=$model->getPrimaryKey();
											}
											
											$ub->BranchId=$b;
											$ub->save(false);
										}
																		
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($model->getPrimaryKey()));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
			   
				break;

			   
			   
				case 'mdsadddata':
			   $transaction=$model->dbConnection->beginTransaction();
				try
					{
						
						$model->save(false);
						
				/*------------------Carb-De-Carb--------------*/				
						if($data['carbdecarb'])
						{
							$d=$data['carbdecarb'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="CDC"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="CDC";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							
						}
						
				/*-------------Case Depth--------------*/				
						if($data['casedepth'])
						{
							$d=$data['casedepth'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="CD"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="CD";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							
						}
						
						
				/*----------Chemical----------*/							
						if($data['chemical'])
						{
							$chem=$data['chemical'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="C"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($chem as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="C";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($chem['TestMethod']['Method'])?$chem['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($chem['TestMethod']['Id'])?$chem['TestMethod']['Id']:"";
							$mdstest->save(false);	

							foreach($chem['allelements'] as $e)
							{
								$td=Mdschemdetails::model()->find(array('condition'=>'MdsTestId=:mtid AND ElementId=:elid',
										'params'=>array(':mtid'=>$mdstest->getPrimaryKey(),':elid'=>$e['ElementId'])));
								if(empty($td))
								{
									$td=new Mdschemdetails;
								}									
								$td->Min=$e['Min'];
								$td->Max=$e['Max'];
								$td->Element=$e['Element'];
								$td->ElementId=$e['ElementId'];
								$td->MdsTestId=$mdstest->getPrimaryKey();
								$td->save(false);
								
							}							
							
							
						}
				
				/*-------Grain Size--------------*/				
						if($data['grainsize'])
						{
							$d=$data['grainsize'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="GS"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="GS";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							
						}
						
				
			
				/*----Hardness-HV---------*/				
						if($data['hardHV'])
						{
							$hardHV=$data['hardHV'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="H" AND HtypeId="2"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
								$mdstest->MdsId=$model->getPrimaryKey();
								$mdstest->MDTest="H";
								$mdstest->HtypeId="2";
								$mdstest->CreatedOn=date('Y-m-d H:i:s');
							}
														
							 foreach($hardHV as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
													
							
							$mdstest->TestMethod=isset($hardHV['TestMethod']['Method'])?$hardHV['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($hardHV['TestMethod']['Id'])?$hardHV['TestMethod']['Id']:"";
							$mdstest->save(false);	
							$d=$hardHV;
							if(!empty($d['HV']))
							{
							
							$hv=Mdsmechdetails::model()->find(array('condition'=>'MdsTestId=:id AND TestDetails="HV"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($hv))
							{
								$hv=new Mdsmechdetails;
								$hv->MdsTestId=$mdstest->getPrimaryKey();
								$hv->TestDetails="HV";
							}
														
								$hv->Min=$hardHV['HV']['Min'];
								$hv->Max=$hardHV['HV']['Max'];
								$hv->save(false);
							}	
							
													
						}
						
				/*----Hardness-MicroHV---------*/				
						if($data['hardMHV'])
						{
							$hardMHV=$data['hardMHV'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="H" AND HtypeId="3"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
								$mdstest->MdsId=$model->getPrimaryKey();
								$mdstest->MDTest="H";
								$mdstest->HtypeId="3";
								$mdstest->CreatedOn=date('Y-m-d H:i:s');
							}
														
							 foreach($hardMHV as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
													
							
							$mdstest->TestMethod=isset($hardMHV['TestMethod']['Method'])?$hardMHV['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($hardMHV['TestMethod']['Id'])?$hardMHV['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							if(!empty($hardMHV['MicroHV']))
							{
								$microhv=Mdsmechdetails::model()->find(array('condition'=>'MdsTestId=:id AND TestDetails="MicroHV"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($microhv))
							{
								$microhv=new Mdsmechdetails;
								$microhv->MdsTestId=$mdstest->getPrimaryKey();
								$microhv->TestDetails="MicroHV";
							}
																
								$microhv->LoadId=$hardMHV['MicroHV']['LoadId'];
								$microhv->Min=$hardMHV['MicroHV']['Min'];
								$microhv->Max=$hardMHV['MicroHV']['Max'];
								$microhv->save(false);
							}
							
													
						}
						
				/*----Hardness-HBW---------*/				
						if($data['hardHBW'])
						{
							$hardHBW=$data['hardHBW'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="H" AND HtypeId="1"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
								$mdstest->MdsId=$model->getPrimaryKey();
								$mdstest->MDTest="H";
								$mdstest->HtypeId="1";
								$mdstest->CreatedOn=date('Y-m-d H:i:s');
							}
														
							 foreach($hardHBW as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
													
							
							$mdstest->TestMethod=isset($hardHBW['TestMethod']['Method'])?$hardHBW['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($hardHBW['TestMethod']['Id'])?$hardHBW['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							if(!empty($hardHBW['HBW']))
							{
							$hbw=Mdsmechdetails::model()->find(array('condition'=>'MdsTestId=:id AND TestDetails="HBW"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($hbw))
							{
								$hbw=new Mdsmechdetails;
								$hbw->MdsTestId=$mdstest->getPrimaryKey();
								$hbw->TestDetails="HBW";
							}
								$hbw->LoadId=$hardHBW['HBW']['LoadId'];								
								$hbw->Min=$hardHBW['HBW']['Min'];
								$hbw->Max=$hardHBW['HBW']['Max'];
								$hbw->save(false);
								
							}
							
							
						}
						
				/*----Hardness-HRB---------*/				
						if($data['hardHRB'])
						{
							$hardHRB=$data['hardHRB'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="H" AND HtypeId="4"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
								$mdstest->MdsId=$model->getPrimaryKey();
								$mdstest->MDTest="H";
								$mdstest->HtypeId="4";
								$mdstest->CreatedOn=date('Y-m-d H:i:s');
							}
														
							 foreach($hardHRB as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
													
							
							$mdstest->TestMethod=isset($hardHRB['TestMethod']['Method'])?$hardHRB['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($hardHRB['TestMethod']['Id'])?$hardHRB['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
								if(!empty($hardHRB['HRB']))
							{
								$hrb=Mdsmechdetails::model()->find(array('condition'=>'MdsTestId=:id AND TestDetails="HRB"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($hrb))
							{
								$hrb=new Mdsmechdetails;
								$hrb->MdsTestId=$mdstest->getPrimaryKey();
								$hrb->TestDetails="HRB";
							}
								$hrb->LoadId=$hardHRB['HRB']['LoadId'];								
								$hrb->Min=$hardHRB['HRB']['Min'];
								$hrb->Max=$hardHRB['HRB']['Max'];
								$hrb->save(false);
								
							}
							
							
						}
						
				/*----Hardness-HRC--------*/				
						if($data['hardHRC'])
						{
							$hardHRC=$data['hardHRC'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="H" AND HtypeId="5"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
								$mdstest->MdsId=$model->getPrimaryKey();
								$mdstest->MDTest="H";
								$mdstest->HtypeId="5";
								$mdstest->CreatedOn=date('Y-m-d H:i:s');
							}
														
							 foreach($hardHRC as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
													
							
							$mdstest->TestMethod=isset($hardHRC['TestMethod']['Method'])?$hardHRC['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($hardHRC['TestMethod']['Id'])?$hardHRC['TestMethod']['Id']:"";
							$mdstest->save(false);	
								
							if(!empty($hardHRC['HRC']))
							{		
								$hrc=Mdsmechdetails::model()->find(array('condition'=>'MdsTestId=:id AND TestDetails="HRC"',
								'params'=>array(':id'=>$mdstest->getPrimaryKey())));
								if(empty($hrc))
								{
									$hrc=new Mdsmechdetails;
									$hrc->MdsTestId=$mdstest->getPrimaryKey();
									$hrc->TestDetails="HRC";
								}
									
								$hrc->LoadId=$hardHRC['HRC']['LoadId'];				
								$hrc->Min=$hardHRC['HRC']['Min'];
								$hrc->Max=$hardHRC['HRC']['Max'];
								$hrc->save(false);
							}
							
						}
					
	/*-------Hydrogen Embiterment---------------------*/				
						if($data['hydrogen'])
						{
							$d=$data['hydrogen'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="HET"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="HET";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
									
							
						}
											
		/*----Impact----------*/				
						if($data['impact'])
						{
							$imp=$data['impact'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="I"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($imp as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="I";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($imp['TestMethod']['Method'])?$imp['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($imp['TestMethod']['Id'])?$imp['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							if(!empty($imp['I']))
							{
							$imps=Mdsmechdetails::model()->find(array('condition'=>'MdsTestId=:id AND TestDetails="I"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($imps))
							{
								$imps=new Mdsmechdetails;
								$imps->MdsTestId=$mdstest->getPrimaryKey();
								$imps->TestDetails="I";
							}
																
								$imps->Min=$imp['I']['Min'];
								$imps->Max=$imp['I']['Max'];
								$imps->TempId=$imp['I']['TempId'];
								$imps->save(false);
							}
								
							
							
						}
						
		
						
			/*-------Inclusion Rating K Method--------------*/				
						if($data['inclusionk'])
						{
							$d=$data['inclusionk'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="IRK"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="IRK";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
						
							
						}	
			
/*-------Inclusion Rating Worst Method--------------*/				
						if($data['inclusionw'])
						{
							$d=$data['inclusionw'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="IRW"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="IRW";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
								
							
						}	
					
/*-------Micro case depth--------------*/				
						if($data['microcase'])
						{
							$d=$data['microcase'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="MCD"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="MCD";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
						
								
							
							
						}
						
/*-------Micro Coating--------------*/				
						if($data['microcoat'])
						{
							$d=$data['microcoat'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="MCT"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="MCT";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
						
								
							
							
						}
						
/*-------Micro Decarb--------------*/				
						if($data['microdecarb'])
						{
							$d=$data['microdecarb'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="MDC"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="MDC";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							
							
						}
						
/*-------Micro Structure--------------*/				
						if($data['microstruct'])
						{
							$d=$data['microstruct'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="MS"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="MS";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							
								
							
							
						}
						
			/*------------Proof load---------------------*/				
						if($data['proofload'])
						{
							$d=$data['proofload'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="PL"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="PL";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							if(!empty($d['PL']))
							{
								$dts=Mdsmechdetails::model()->find(array('condition'=>'MdsTestId=:id AND TestDetails="PL"',
								'params'=>array(':id'=>$mdstest->getPrimaryKey())));
								if(empty($dts))
								{
									$dts=new Mdsmechdetails;
									$dts->MdsTestId=$mdstest->getPrimaryKey();
									$dts->TestDetails="PL";
								}
																	
									$dts->Min=$d['PL']['Min'];
									$dts->Max=$d['PL']['Max'];
									$dts->save(false);
							}		
							
						}
				
	/*-----------Shear Strength---------------------*/				
						if($data['shear'])
						{
							$d=$data['shear'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="SS"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="SS";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							
							
						}
				
												
	/*----Tensile----------*/			
						
						if($data['tensile'])
						{
							$tens=$data['tensile'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="T"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($tens as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="T";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($tens['TestMethod']['Method'])?$tens['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($tens['TestMethod']['Id'])?$tens['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							if(!empty($tens['PS']))
							{
							$pstds=Mdsmechdetails::model()->find(array('condition'=>'MdsTestId=:id AND TestDetails="PS"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($pstds))
							{
								$pstds=new Mdsmechdetails;
								$pstds->MdsTestId=$mdstest->getPrimaryKey();
								$pstds->TestDetails="PS";
							}
																
								$pstds->Min=$tens['PS']['Min'];
								$pstds->Max=$tens['PS']['Max'];
								$pstds->save(false);
								
							}
								
							if(!empty($tens['UTS']))
							{		
								$utstds=Mdsmechdetails::model()->find(array('condition'=>'MdsTestId=:id AND TestDetails="UTS"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($utstds))
							{
								$utstds=new Mdsmechdetails;
								$utstds->MdsTestId=$mdstest->getPrimaryKey();
								$utstds->TestDetails="UTS";
							}
																
								$utstds->Min=$tens['UTS']['Min'];
								$utstds->Max=$tens['UTS']['Max'];
								$utstds->save(false);
							}

							if(!empty($tens['E']))
							{	
							$etds=Mdsmechdetails::model()->find(array('condition'=>'MdsTestId=:id AND TestDetails="E"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($etds))
							{
								$etds=new Mdsmechdetails;
								$etds->MdsTestId=$mdstest->getPrimaryKey();
								$etds->TestDetails="E";
							}
																
								$etds->Min=$tens['E']['Min'];
								$etds->Max=$tens['E']['Max'];
								$etds->save(false);
							}
							
							if(!empty($tens['R']))
							{	
								$rtds=Mdsmechdetails::model()->find(array('condition'=>'MdsTestId=:id AND TestDetails="R"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($rtds))
							{
								$rtds=new Mdsmechdetails;
								$rtds->MdsTestId=$mdstest->getPrimaryKey();
								$rtds->TestDetails="R";
							}
																
								$rtds->Min=$tens['R']['Min'];
								$rtds->Max=$tens['R']['Max'];
								$rtds->save(false);
							}	
							
							
						}
												
			/*-------Threadlap---------------------*/				
						if($data['threadlap'])
						{
							$d=$data['threadlap'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="THL"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="THL";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							
							
						}	

			
				
				/*-------Torque Tension---------------------*/				
						if($data['tension'])
						{
							$d=$data['tension'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="TT"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="TT";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							if(!empty($d['TT']))
							{
							$dts=Mdsmechdetails::model()->find(array('condition'=>'MdsTestId=:id AND TestDetails="TT"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($dts))
							{
								$dts=new Mdsmechdetails;
								$dts->MdsTestId=$mdstest->getPrimaryKey();
								$dts->TestDetails="TT";
							}
																
								$dts->Min=$d['TT']['Min'];
								$dts->Max=$d['TT']['Max'];
								$dts->save(false);
							}		
							
						}
							

					/*-------Wedge---------------------*/				
						if($data['wedge'])
						{
							$d=$data['wedge'];
							$mdstest=Mdstestplan::model()->find(array('condition'=>'MdsId=:mdsid AND MDTest="W"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Mdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->MdsId=$model->getPrimaryKey();
							$mdstest->MDTest="W";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							
						}
									
						
						
						
																		
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
						
					
						
									/*------------------Carb-De-Carb--------------*/				
						if($data['carbdecarb'])
						{
							$d=$data['carbdecarb'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="CDC"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="CDC";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							
						}
						
				
				
				
				/*-------------Case Depth--------------*/				
						if($data['casedepth'])
						{
							$d=$data['casedepth'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="CD"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="CD";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							
						}
						
						
				/*----------Chemical----------*/							
						if($data['chemical'])
						{
							$chem=$data['chemical'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="C"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($chem as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="C";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($chem['TestMethod']['Method'])?$chem['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($chem['TestMethod']['Id'])?$chem['TestMethod']['Id']:"";
							$mdstest->save(false);	
//$this->_sendResponse(401, CJSON::encode($chem['allelements']));
							foreach($chem['allelements'] as $e)
							{
								$td=Tdschemdetails::model()->find(array('condition'=>'TdsTestId=:mtid AND ElementId=:elid',
										'params'=>array(':mtid'=>$mdstest->getPrimaryKey(),':elid'=>$e['ElementId'])));
								if(empty($td))
								{
									$td=new Tdschemdetails;
									$td->TdsTestId=$mdstest->getPrimaryKey();
									$td->ElementId=$e['ElementId'];
								}									
								$td->Min=empty($e['Min'])?"":$e['Min'];
								$td->Max=empty($e['Max'])?"":$e['Max'];;
								$td->Element=$e['Element'];
																
								$td->save(false);
								
							}							
							
							
						}
				
					//
				/*-------Grain Size--------------*/				
						if($data['grainsize'])
						{
							$d=$data['grainsize'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="GS"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="GS";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							
						}
						
					//
			
				/*----Hardness-HV---------*/				
						if($data['hardHV'])
						{
							$hardHV=$data['hardHV'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="H" AND HtypeId="2"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
								$mdstest->TdsId=$model->getPrimaryKey();
								$mdstest->MDTest="H";
								$mdstest->HtypeId="2";
								$mdstest->CreatedOn=date('Y-m-d H:i:s');
							}
														
							 foreach($hardHV as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
													
							
							$mdstest->TestMethod=isset($hardHV['TestMethod']['Method'])?$hardHV['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($hardHV['TestMethod']['Id'])?$hardHV['TestMethod']['Id']:"";
							$mdstest->save(false);	
							$d=$hardHV;
							if(!empty($d['HV']))
							{
							
							$hv=Tdsmechdetails::model()->find(array('condition'=>'TdsTestId=:id AND TestDetails="HV"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($hv))
							{
								$hv=new Tdsmechdetails;
								$hv->TdsTestId=$mdstest->getPrimaryKey();
								$hv->TestDetails="HV";
							}
																
								$hv->LoadId=$hardHV['HV']['LoadId'];
								$hv->Min=$hardHV['HV']['Min'];
								$hv->Max=$hardHV['HV']['Max'];
								$hv->save(false);
							}	
							
													
						}
						
				/*----Hardness-MicroHV---------*/				
						if($data['hardMHV'])
						{
							$hardMHV=$data['hardMHV'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="H" AND HtypeId="3"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
								$mdstest->TdsId=$model->getPrimaryKey();
								$mdstest->MDTest="H";
								$mdstest->HtypeId="3";
								$mdstest->CreatedOn=date('Y-m-d H:i:s');
							}
														
							 foreach($hardMHV as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
													
							
							$mdstest->TestMethod=isset($hardMHV['TestMethod']['Method'])?$hardMHV['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($hardMHV['TestMethod']['Id'])?$hardMHV['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							if(!empty($hardMHV['MicroHV']))
							{
								$microhv=Tdsmechdetails::model()->find(array('condition'=>'TdsTestId=:id AND TestDetails="MicroHV"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($microhv))
							{
								$microhv=new Tdsmechdetails;
								$microhv->TdsTestId=$mdstest->getPrimaryKey();
								$microhv->TestDetails="MicroHV";
							}
																
								$microhv->LoadId=$hardMHV['MicroHV']['LoadId'];
								$microhv->Min=$hardMHV['MicroHV']['Min'];
								$microhv->Max=$hardMHV['MicroHV']['Max'];
								$microhv->save(false);
							}
							
													
						}
						
				/*----Hardness-HBW---------*/				
						if($data['hardHBW'])
						{
							$hardHBW=$data['hardHBW'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="H" AND HtypeId="1"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
								$mdstest->TdsId=$model->getPrimaryKey();
								$mdstest->MDTest="H";
								$mdstest->HtypeId="1";
								$mdstest->CreatedOn=date('Y-m-d H:i:s');
							}
														
							 foreach($hardHBW as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
													
							
							$mdstest->TestMethod=isset($hardHBW['TestMethod']['Method'])?$hardHBW['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($hardHBW['TestMethod']['Id'])?$hardHBW['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							if(!empty($hardHBW['HBW']))
							{
							$hbw=Tdsmechdetails::model()->find(array('condition'=>'TdsTestId=:id AND TestDetails="HBW"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($hbw))
							{
								$hbw=new Tdsmechdetails;
								$hbw->TdsTestId=$mdstest->getPrimaryKey();
								$hbw->TestDetails="HBW";
							}
																
								$hbw->LoadId=$hardHBW['HBW']['LoadId'];
								$hbw->Min=$hardHBW['HBW']['Min'];
								$hbw->Max=$hardHBW['HBW']['Max'];
								$hbw->save(false);
								
							}
							
							
						}
						
				/*----Hardness-HRB---------*/				
						if($data['hardHRB'])
						{
							$hardHRB=$data['hardHRB'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="H" AND HtypeId="4"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
								$mdstest->TdsId=$model->getPrimaryKey();
								$mdstest->MDTest="H";
								$mdstest->HtypeId="4";
								$mdstest->CreatedOn=date('Y-m-d H:i:s');
							}
														
							 foreach($hardHRB as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
													
							
							$mdstest->TestMethod=isset($hardHRB['TestMethod']['Method'])?$hardHRB['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($hardHRB['TestMethod']['Id'])?$hardHRB['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
								if(!empty($hardHRB['HRB']))
							{
								$hrb=Tdsmechdetails::model()->find(array('condition'=>'TdsTestId=:id AND TestDetails="HRB"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($hrb))
							{
								$hrb=new Tdsmechdetails;
								$hrb->TdsTestId=$mdstest->getPrimaryKey();
								$hrb->TestDetails="HRB";
							}
																
								$hrb->LoadId=$hardHRB['HRB']['LoadId'];
								$hrb->Min=$hardHRB['HRB']['Min'];
								$hrb->Max=$hardHRB['HRB']['Max'];
								$hrb->save(false);
								
							}
							
							
						}
						
				/*----Hardness-HRC--------*/				
						if($data['hardHRC'])
						{
							$hardHRC=$data['hardHRC'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="H" AND HtypeId="5"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
								$mdstest->TdsId=$model->getPrimaryKey();
								$mdstest->MDTest="H";
								$mdstest->HtypeId="5";
								$mdstest->CreatedOn=date('Y-m-d H:i:s');
							}
														
							 foreach($hardHRC as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
													
							
							$mdstest->TestMethod=isset($hardHRC['TestMethod']['Method'])?$hardHRC['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($hardHRC['TestMethod']['Id'])?$hardHRC['TestMethod']['Id']:"";
							$mdstest->save(false);	
								
							if(!empty($hardHRC['HRC']))
							{		
								$hrc=Tdsmechdetails::model()->find(array('condition'=>'TdsTestId=:id AND TestDetails="HRC"',
								'params'=>array(':id'=>$mdstest->getPrimaryKey())));
								if(empty($hrc))
								{
									$hrc=new Tdsmechdetails;
									$hrc->TdsTestId=$mdstest->getPrimaryKey();
									$hrc->TestDetails="HRC";
								}
																
								$hrc->LoadId=$hardHRC['HRC']['LoadId'];
								$hrc->Min=$hardHRC['HRC']['Min'];
								$hrc->Max=$hardHRC['HRC']['Max'];
								$hrc->save(false);
							}
							
						}
					
	/*-------Hydrogen Embiterment---------------------*/				
						if($data['hydrogen'])
						{
							$d=$data['hydrogen'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="HET"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="HET";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
									
							
						}
											
		/*----Impact----------*/				
						if($data['impact'])
						{
							$imp=$data['impact'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="I"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($imp as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="I";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($imp['TestMethod']['Method'])?$imp['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($imp['TestMethod']['Id'])?$imp['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							if(!empty($imp['I']))
							{
							$imps=Tdsmechdetails::model()->find(array('condition'=>'TdsTestId=:id AND TestDetails="I"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($imps))
							{
								$imps=new Tdsmechdetails;
								$imps->TdsTestId=$mdstest->getPrimaryKey();
								$imps->TestDetails="I";
							}
																
								//$imps->LoadId=$imp['I']['LoadId'];
								$imps->Min=$imp['I']['Min'];
								$imps->Max=$imp['I']['Max'];
								$imps->Temp=$imp['I']['TempId'];
								$imps->save(false);
							}
								
							
							
						}
						
		
						
			/*-------Inclusion Rating K Method--------------*/				
						if($data['inclusionk'])
						{
							$d=$data['inclusionk'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="IRK"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="IRK";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
						
							
						}	
			
/*-------Inclusion Rating Worst Method--------------*/				
						if($data['inclusionw'])
						{
							$d=$data['inclusionw'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="IRW"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="IRW";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
								
							
						}	
					
/*-------Micro case depth--------------*/				
						if($data['microcase'])
						{
							$d=$data['microcase'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="MCD"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="MCD";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
						
								
							
							
						}
						
/*-------Micro Coating--------------*/				
						if($data['microcoat'])
						{
							$d=$data['microcoat'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="MCT"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="MCT";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
						
								
							
							
						}
						
/*-------Micro Decarb--------------*/				
						if($data['microdecarb'])
						{
							$d=$data['microdecarb'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="MDC"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="MDC";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							
							
						}
						
/*-------Micro Structure--------------*/				
						if($data['microstruct'])
						{
							$d=$data['microstruct'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="MS"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="MS";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							
								
							
							
						}
						
			/*------------Proof load---------------------*/				
						if($data['proofload'])
						{
							$d=$data['proofload'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="PL"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="PL";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							if(!empty($d['PL']))
							{
								$dts=Tdsmechdetails::model()->find(array('condition'=>'TdsTestId=:id AND TestDetails="PL"',
								'params'=>array(':id'=>$mdstest->getPrimaryKey())));
								if(empty($dts))
								{
									$dts=new Tdsmechdetails;
									$dts->TdsTestId=$mdstest->getPrimaryKey();
									$dts->TestDetails="PL";
								}
																	
									$dts->Min=$d['PL']['Min'];
									$dts->Max=$d['PL']['Max'];
									$dts->save(false);
							}		
							
						}
				
	/*-----------Shear Strength---------------------*/				
						if($data['shear'])
						{
							$d=$data['shear'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="SS"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="SS";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							
							
						}
				
												
	/*----Tensile----------*/			
						
						if($data['tensile'])
						{
							$tens=$data['tensile'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="T"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($tens as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="T";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($tens['TestMethod']['Method'])?$tens['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($tens['TestMethod']['Id'])?$tens['TestMethod']['Id']:"";
							$mdstest->save(false);	
							$d=$tens;
							if(!empty($d['PS']))
							{
							$pstds=Tdsmechdetails::model()->find(array('condition'=>'TdsTestId=:id AND TestDetails="PS"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($pstds))
							{
								$pstds=new Tdsmechdetails;
								$pstds->TdsTestId=$mdstest->getPrimaryKey();
								$pstds->TestDetails="PS";
							}
																
								$pstds->Min=$tens['PS']['Min'];
								$pstds->Max=$tens['PS']['Max'];
								$pstds->save(false);
								
							}
								
							if(!empty($d['UTS']))
							{		
								$utstds=Tdsmechdetails::model()->find(array('condition'=>'TdsTestId=:id AND TestDetails="UTS"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($utstds))
							{
								$utstds=new Tdsmechdetails;
								$utstds->TdsTestId=$mdstest->getPrimaryKey();
								$utstds->TestDetails="UTS";
							}
																
								$utstds->Min=$tens['UTS']['Min'];
								$utstds->Max=$tens['UTS']['Max'];
								$utstds->save(false);
							}

							if(!empty($d['E']))
							{	
							$etds=Tdsmechdetails::model()->find(array('condition'=>'TdsTestId=:id AND TestDetails="E"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($etds))
							{
								$etds=new Tdsmechdetails;
								$etds->TdsTestId=$mdstest->getPrimaryKey();
								$etds->TestDetails="E";
							}
																
								$etds->Min=$tens['E']['Min'];
								$etds->Max=$tens['E']['Max'];
								$etds->save(false);
							}
							
							if(!empty($d['R']))
							{	
								$rtds=Tdsmechdetails::model()->find(array('condition'=>'TdsTestId=:id AND TestDetails="R"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($rtds))
							{
								$rtds=new Tdsmechdetails;
								$rtds->TdsTestId=$mdstest->getPrimaryKey();
								$rtds->TestDetails="R";
							}
																
								$rtds->Min=$tens['R']['Min'];
								$rtds->Max=$tens['R']['Max'];
								$rtds->save(false);
							}	
							
							
						}
												
			/*-------Threadlap---------------------*/				
						if($data['threadlap'])
						{
							$d=$data['threadlap'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="THL"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="THL";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							
							
						}	

			
				
				/*-------Torque Tension---------------------*/				
						if($data['tension'])
						{
							$d=$data['tension'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="TT"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="TT";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							if(!empty($d['TT']))
							{
							$dts=Tdsmechdetails::model()->find(array('condition'=>'TdsTestId=:id AND TestDetails="TT"',
							'params'=>array(':id'=>$mdstest->getPrimaryKey())));
							if(empty($dts))
							{
								$dts=new Tdsmechdetails;
								$dts->TdsTestId=$mdstest->getPrimaryKey();
								$dts->TestDetails="TT";
							}
																
								$dts->Min=$d['TT']['Min'];
								$dts->Max=$d['TT']['Max'];
								$dts->save(false);
							}		
							
						}
							

					/*-------Wedge---------------------*/				
						if($data['wedge'])
						{
							$d=$data['wedge'];
							$mdstest=Tdstestplan::model()->find(array('condition'=>'TdsId=:mdsid AND MDTest="W"',
								'params'=>array(':mdsid'=>$model->getPrimaryKey())));
							if(empty($mdstest))
							{
								$mdstest=new Tdstestplan;
							}								
							
							 foreach($d as $var=>$value)
								  {
									// Does the model have this attribute? If not raise an error
									if($mdstest->hasAttribute($var))
										$mdstest->$var = $value;
								  }										
							
							$mdstest->TdsId=$model->getPrimaryKey();
							$mdstest->MDTest="W";
							$mdstest->CreatedOn=date('Y-m-d H:i:s');
							$mdstest->TestMethod=isset($d['TestMethod']['Method'])?$d['TestMethod']['Method']:"";
							$mdstest->TestMethodId=isset($d['TestMethod']['Id'])?$d['TestMethod']['Id']:"";
							$mdstest->save(false);	
							
							
						}
									
						
						
																		
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($model));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode("error"));
					}
			   
				break;		
					
			   		   
				
			   
			     case 'stdadddata':
			   $transaction=$model->dbConnection->beginTransaction();
				try
					{
						$u=MyFunctions::gettokenuser();
						
						$model->CID=$u->CID;
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
						$model->Type="M";
						$model->StdId=$data['StdId'];						
						$model->Grade=isset($data['Grade'])?$data['Grade']:null;						
						$model->ExtraInfo=isset($data['ExtraInfo'])?$data['ExtraInfo']:null;						
						$model->save(false);
						
						//$md=new StdMechdetails;
						foreach($data['Parameters'] as $p)
						  {
									$r=new Stdmechdetails;
									$r->MBId=$model->getPrimaryKey();
									$r->PId=$p['PId'];
									
									$r->Min=$p['Min'];
									$r->Max=$p['Max'];
									$r->IsMajor=$p['IsMajor'];
									
									$r->save(false);
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
			   case 'substdadd':
			   $transaction=$model->dbConnection->beginTransaction();
				try
					{
						$u=MyFunctions::gettokenuser();
						$model->CID=$u->CID;
						$model->StdId=$data['StdId'];
						$model->Grade=$data['Grade'];
						$model->ExtraInfo=isset($data['ExtraInfo'])?$data['ExtraInfo']:null;						
							$model->save(false);
						
						
						$substdid=$model->getPrimaryKey();
						
						
						foreach($data['Parameters'] as $e)
						{
							$test=Tests::model()->findByPk($e['TestId']);
								$r=new Stdsubdetails;
								$r->PId=$e['PId'];
								$r->TMID=isset($e['TMID'])?$e['TMID']:null;
								$r->TUID=$test->TUID;
								
								$r->PermMin=isset($e['PermMin'])?$e['PermMin']:null;
								$r->PermMax=isset($e['PermMax'])?$e['PermMax']:null;
								$r->SpecMin=isset($e['SpecMin'])?$e['SpecMin']:null;
								$r->SpecMax=isset($e['SpecMax'])?$e['SpecMax']:null;
								
								
							
								$r->Cost=$e['Cost'];
								$r->IsMajor=$e['IsMajor'];
								$r->SubStdId=$model->getPrimaryKey();
								$r->save(false);
								
							$substdtest=Substdtests::model()->find(['condition'=>'SSID =:ssid AND TID=:tid',
								'params'=>[':ssid'=>$substdid,':tid'=>$test->Id,]]);
							if(empty($substdtest))
									{
										$substdtest=new Substdtests;
										$substdtest->SSID=$substdid;
										$substdtest->TID=$test->Id;
										$substdtest->save(false);
									}
							
						}
							$transaction->commit();
							$this->_sendResponse(200, CJSON::encode("Added successfully"));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
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
				case 'stddata': $model = Users::model()->findByPk($_GET['id']);break;
				case 'mdstdsupdate':$model=Mdstds::model()->findByPk($_GET['id']);break;
				case 'mdstdsedit':
				case 'mdstdspredata':
				case 'mdstdsdata':
				case 'certsetupdate':
				case 'labsetupdate':
				case 'accountsetupdate':
				case 'vaultsetupdate':
				case 'firmsetupdate':
				case 'substddata':$model = Users::model()->findByPk($_GET['id']);break;
				case 'updatedropdown':$model=Dropdowns::model()->findByPk($_GET['id']);break;
				case 'testcondupdate':$model=Testconditions::model()->findByPk($_GET['id']);break;
				case 'importsettings':$model = Users::model()->findByPk($_GET['id']);break;
				
				case 'tparamupdate': $model=Testobsparams::model()->findByPk($_GET['id']);break;
					case 'indparamdata': $model=Industry::model()->findByPk($_GET['id']);break;
				// Find respective model
				case 'updatenotification':$model = Users::model()->findByPk($_GET['id']);break;
				case 'updateallnotification':$model = Users::model()->findByPk($_GET['id']);break;
				case 'sendtestmail':$model = Users::model()->findByPk($_GET['id']);break;
					case 'mailsettings':$model = Users::model()->findByPk($_GET['id']);break;
		case 'updatemailset':$model= Users::model()->findByPk($_GET['id']);break;
				case 'testupdate':$model=Tests::model()->findByPk($_GET['id']);break;
				case 'formatrev':$model=Tests::model()->findByPk($_GET['id']);break;
				case 'exttestsetting':	$model = Externaltests::model()->findByPk($_GET['id']);  break;

				case 'attachcategory':	$model = Attachcategory::model()->findByPk($_GET['id']);  break;

				
			
				case 'pswdupdate':		$model = Users::model()->findByPk($_GET['id']);      		break;
				
				case 'settingdefault':		$model = Settings::model()->findByPk($_GET['id']); 		break;
				
				case 'updcustomer':		$model = Customerinfo::model()->findByPk($_GET['id']); 		break;
					case 'updsupplier':		$model = Suppliers::model()->findByPk($_GET['id']); 		break;
				
				
				case 'droptemp':		$model = Dropdowntemp::model()->findByPk($_GET['id']);      break;
				case 'hardequip':		$model = Hardnessequip::model()->findByPk($_GET['id']);      break;
				case 'hardindent':		$model = Hardnessindent::model()->findByPk($_GET['id']);      break;
					
				case 'methodupdate':			$model = Testmethods::model()->findByPk($_GET['id']);     	break;	
					
				case 'ssthr':			$model = Ssthours::model()->findByPk($_GET['id']);     		break;	
					
				case 'dropload':		$model = Dropdownload::model()->findByPk($_GET['id']);      break;
					
				case 'edituser':		$model = Users::model()->findByPk($_GET['id']);      		break;
				
				case 'mdsupdate':			$model = Mds::model()->findByPk($_GET['id']);      			break;
					
				case 'tdsupdate':			$model = Tds::model()->findByPk($_GET['id']);      			break;
				
				case 'stdedit':			$model = Standards::model()->findByPk($_GET['id']);    		break;
					
				case 'mechedit':		$model = Substandards::model()->findByPk($_GET['id']);    		break;	
					
				case 'substdupdate':		$model = Substandards::model()->findByPk($_GET['id']);   	break;
									
				case 'users':			$model = Users::model()->findByPk($_GET['id']);   			break;
					
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
				case 'stddata':
				
				case 'mdstdsedit':
				case 'mdstdspredata':
				case 'mdstdsdata':
				case 'certsetupdate':
				case 'labsetupdate':
				case 'accountsetupdate':
				case 'vaultsetupdate':
				case 'firmsetupdate':
				case 'substddata':
				
				case 'updcustomer':$data=$put_vars;break;
				case 'mdstdsupdate':
				case 'updatedropdown':
				case 'testcondupdate':
				case 'importsettings':
				case 'methodupdate':
				case 'tparamupdate':
				$data=$put_vars;
				foreach($put_vars as $var=>$value) {
				// Does model have this attribute? If not, raise an error
				if($model->hasAttribute($var))
					$model->$var = $value;
				}
				break;
					case 'indparamdata': 
				case 'updatenotification':$data=$put_vars;break;
				case 'updateallnotification':$data=$put_vars;break;
				
				case 'sendtestmail':$data=$put_vars;break;
				case 'mailsettings':$data=$put_vars;break;
    
		case 'updatemailset':$data=$put_vars;break;
				case 'testupdate':
			
			
				case 'formatrev':$data=$put_vars;break;
			
				case 'settingdefault':foreach($put_vars as $var=>$value) {
									// Does model have this attribute? If not, raise an error
									if($model->hasAttribute($var))
										$model->$var = $value;
					}		
					break;
					
				case 'pswdupdate':
				$oldpwd=$model->Password;
				$data=$put_vars;
			
					break;	
					
			case 'mdsupdate': $data=$put_vars;break;
			
			case 'tdsupdate': $data=$put_vars;break;
			
			case 'stdedit': $data=$put_vars;break;
			
			case 'edituser':
			 $data=$put_vars;
			foreach($put_vars as $var=>$value) {
									// Does model have this attribute? If not, raise an error
									if($model->hasAttribute($var))
										$model->$var = $value;
					}		
					break;
			
			case 'mechedit':		$data=$put_vars;
						
						
					break;	
			
			case 'substdupdate':		$data=$put_vars;
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
				case 'stddata':
						
					$u=MyFunctions::gettokenuser();
				//$this->_sendResponse(401, CJSON::encode($u));
				
				$models=Standards::model()->findAll(array('condition'=>'Status="1" AND CID=:cid',
				'params'=>[':cid'=>$u->CID],
				'order'=>'Id DESC'));	
						
						$allstddata=[];
						
						
							foreach($models as $s)
							{
								
								
								$allstddata[]=(object)array('Id'=>$s->Id,'Standard'=>$s->Standard,'Description'=>$s->Description,'IndId'=>$s->IndId,'Industry'=>implode(" - ",array_reverse(MyFunctions::getParentCat($s->IndId))));
							}

				
				$industries=Industry::model()->findAll(array('condition'=>'HasSubInd=0 AND Status=1 AND CID=:cid','params'=>[':cid'=>$u->CID]));
				$allindustries=[];
				foreach($industries as $c)
							{
								$parents = MyFunctions::getParentCat($c->Id);//array();
									$allindustries[]=(object)array('Id'=>$c->Id,'Name'=>$c->Name,
									'Parent'=>isset($c->ParentId)?$c->parent->Name:"",'FParent'=>end($parents),
									'ParentId'=>$c->ParentId,'PTree'=>implode(" - ",array_reverse(MyFunctions::getParentCat($c->Id))));
									
							}
					$result=(object)array('allstds'=>$allstddata,'allindustries'=>$allindustries);
					$this->_sendResponse(200, CJSON::encode($result));
					break;
					
					
					
				case 'mdstdsupdate':
			    $transaction=$model->dbConnection->beginTransaction();
					try
					{
						$model->CreatedOn=date('Y-m-d H:i:s');
						$model->save(false);
						$response=null;
							foreach($data['allmdstdstests'] as $t)
							{
								$mt=[];
								if(isset($t['Id']))
								{
										$mt=Mdstdstests::model()->find(['condition'=>'TUID=:tuid AND MTID=:mtid AND Id=:id',
								'params'=>[':tuid'=>$t['TUID'],':mtid'=>$model->getPrimaryKey(),':id'=>$t['Id']]]);
								}
								// else
								// {
										// $mt=Mdstdstests::model()->find(['condition'=>'TUID=:tuid AND MTID=:mtid',
								// 'params'=>[':tuid'=>$t['TUID'],':mtid'=>$model->getPrimaryKey()]]);
								// }
							
								if(empty($mt))
								{
									$mt=new Mdstdstests;
									$mt->MTID=$model->getPrimaryKey();
								}
								
								$mt->TID=$t['TID'];
								$mt->TUID=$t['TUID'];
								$mt->SSDID=$t['SSDID'];
								$mt->TMID=$t['TMID'];
								$mt->Freq=$t['Frequency'];
								$mt->CreatedOn=date('Y-m-d H:i:s');
								$mt->save(false);
								
								foreach($t['tbaseparams'] as $tb)
								{
									$mtbds=Mdstdstestbasedetails::model()->find(['condition'=>'PID=:pid AND MTTID=:mttid',
								'params'=>[':pid'=>$tb['PID'],':mttid'=>$mt->getPrimaryKey()]]);
								
								if(empty($mtbds))
								{
									$mtbds=new Mdstdstestbasedetails;
									$mtbds->MTTID=$mt->getPrimaryKey();
									$mtbds->PID=$tb['PID'];
								}
								
									
									
									
									$mtbds->Value=$tb['Value'];
									
									$mtbds->Modified=date('Y-m-d H:i:s');;
									$mtbds->save(false);
								}
								
								
								foreach($t['tobsparams'] as $tp)
								{
									//$this->_sendResponse(401, CJSON::encode($tp['PID']));
									
									$mtds=Mdstdstestobsdetails::model()->find(['condition'=>'PID=:pid AND MTTID=:mttid',
								'params'=>[':pid'=>$tp['PID'],':mttid'=>$mt->getPrimaryKey()]]);
								
								if(empty($mtds))
								{
									$mtds=new Mdstdstestobsdetails;
									$mtds->MTTID=$mt->getPrimaryKey();
								}
								
									$mtds->PID=$tp['PID'];
									$mtds->SpecMin=$tp['SpecMin'];
									$mtds->SpecMax=$tp['SpecMax'];
									$mtds->Modified=date('Y-m-d H:i:s');;
									$mtds->save(false);
								}
							}
							
							foreach($data['delmdstdstest'] as $dt)
							{
								if(isset($dt['Id']))
								{
										$delmt=Mdstdstests::model()->find(['condition'=>'TUID=:tuid AND MTID=:mtid AND Id=:id',
								'params'=>[':tuid'=>$dt['TUID'],':mtid'=>$model->getPrimaryKey(),':id'=>$dt['Id']]]);
								
									if(!empty($delmt))
									{
										$delmt->delete();
									}
								}
							}
							if(count($data['delfiles']) >0)
							{
							$response = [];	
								foreach($data['delfiles'] as $df)
								{
									
									$document_root = $_SERVER['DOCUMENT_ROOT'];
									$relative_path = '/tcrandack/images/mdstdsuploads/files/'.$df['name'];
									$file = $document_root . $relative_path;

									

if (file_exists($file)) {
    if (unlink($file)) {
        $response['status'] = 'success';
        $response['message'] = 'File successfully deleted.';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error deleting the file.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'File does not exist.';
}

								$up=Mdstdsuploads::model()->findByPk($df['id']);
								
								if(!empty($up))
								{
									$up->delete();
								}
								
								

								}
							

							}
							
							$transaction->commit();
							
							$result=(object)['msg'=>$response,'MdsTdsId'=>$model->getPrimaryKey()];
					$this->_sendResponse(200, CJSON::encode($result));
					}
					catch(Exception $e)
					{
						$transaction->rollback();
						$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
					break;
					
					
				case 'mdstdsedit':
				try{
					
					$tests=Tests::model()->findAll();
					
					$alltests=[];
					foreach($tests as $t)
					{
						$tobsparams=[];
						$tbaseparams=[];
						$tf=$t->testfeatures[0];
						$tobs=$t->testobsparams(['condition'=>'IsSpec=1']);
						foreach($tobs as $p)
						{
						$tobsparams[]=(object)['PID'=>$p->Id,'Parameter'=>$p->Parameter,'PUnit'=>$p->PUnit,'PSymbol'=>$p->PSymbol,
						'PDType'=>$p->PDType,'SpecMin'=>"",'SpecMax'=>""];
						}
						
						
						$tbasics=$t->testbasicparams(['condition'=>'InPlan=1']);
						foreach($tbasics as $p)
						{
							if($p->PDType==='L')
							{
								$listcats=Dropdowns::model()->findAll(array('condition'=>'Category=:lc','params'=>array(':lc'=>$p->LCategory)));
								$tbaseparams[]=(object)['PID'=>$p->Id,'Parameter'=>$p->Parameter,'PUnit'=>$p->PUnit,'PSymbol'=>$p->PSymbol,
						'PDType'=>$p->PDType,'Value'=>null,'LCats'=>$listcats];
							}
							else
							{
						$tbaseparams[]=(object)['PID'=>$p->Id,'Parameter'=>$p->Parameter,'PUnit'=>$p->PUnit,'PSymbol'=>$p->PSymbol,
						'PDType'=>$p->PDType,'Value'=>null];
							}
							
						}
						
						
						$testmethods=Testmethods::model()->findAll(['condition'=>'TUID=:tuid','params'=>[':tuid'=>$t->TUID]]);
						
						
						$alltests[]=(object)['TID'=>$t->Id,'TestName'=>$t->TestName,'TUID'=>$t->TUID,'SeqNo'=>$t->SeqNo,
						'IsStd'=>$tf->IsStd,'IsTestMethod'=>$tf->IsTestMethod,'tobsparams'=>$tobsparams,'tbaseparams'=>$tbaseparams,'Frequency'=>"",'alltestmethods'=>$testmethods];
					}
					
					$alltypes[]=(object)['Id'=>'MDS','Name'=>'MDS'];
					$alltypes[]=(object)['Id'=>'TDS','Name'=>'TDS'];
					
					
					$md=Mdstds::model()->findByPk($data['id']);
						$mdstdstests=[];
						$testids=[];
						foreach($md->mdstdstests as $t)
						{
							
							$tbaseparams=[];
							foreach($t->mdstdstestbasedetails as $d)
								{
									$p=$d->p;
									if($p->PDType==='L')
									{
										
										$listcats=Dropdowns::model()->findAll(array('condition'=>'Category=:lc','params'=>array(':lc'=>$p->LCategory)));
										$tbaseparams[]=(object)['Id'=>$d->Id,'MTTID'=>$d->MTTID,'PID'=>$p->Id,'Parameter'=>$p->Parameter,'PUnit'=>$p->PUnit,
										'PSymbol'=>$p->PSymbol,
								'PDType'=>$p->PDType,'Value'=>$d->Value,'LCats'=>$listcats];
									}
									else
									{
											$tbaseparams[]=(object)['Id'=>$d->Id,'MTTID'=>$d->MTTID,
											'PID'=>$d->PID,'Parameter'=>$d->p->Parameter,'Value'=>$d->Value,
											'PDType'=>$p->PDType,
											];
									}
								}
								
							$tobparams=[];
							foreach($t->mdstdstestobsdetails as $d)
								{
									
									$tobparams[]=(object)['Id'=>$d->Id,'MTTID'=>$d->MTTID,'PDType'=>$d->p->PDType,
									'PID'=>$d->PID,'Parameter'=>$d->p->Parameter,'SpecMin'=>$d->SpecMin,'SpecMax'=>$d->SpecMax,
									];
								}
							
							
							$std=empty($t->sSD->std)?null:$t->sSD->std->Standard." ".$t->sSD->Grade;
							
							$testids[]=$t->TID;
							$tf=$t->t->testfeatures[0];
							
							$testmethods=Testmethods::model()->findAll(['condition'=>'TUID=:tuid','params'=>[':tuid'=>$t->TUID]]);
							$std=empty($t->sSD)?null:$t->sSD->std->Standard." ".$t->sSD->Grade;
							
							$mdstdstests[]=(object)['Id'=>$t->Id,'MTID'=>$t->MTID,'TID'=>$t->TID,
							'TUID'=>$t->TUID,'SSDID'=>$t->SSDID,'TMID'=>$t->TMID,'Frequency'=>$t->Freq,
							'StdName'=>$std,
							'TestMethod'=>$t->tM->Method,'TestName'=>$t->t->TestName,'Std'=>$std,
							'tobsparams'=>$tobparams,'tbaseparams'=>$tbaseparams,'IsStd'=>$tf->IsStd,'IsTestMethod'=>$tf->IsTestMethod,'alltestmethods'=>$testmethods
							];
							
						}
						
						$fileuploads=empty($md->mdstdsuploads)?[]:$md->mdstdsuploads;
						
						$mdstds=(object)['Id'=>$md->Id,'Type'=>$md->Type,'No'=>$md->No,'TestIds'=>$testids,'delmdstdstest'=>[],
						'Description'=>$md->Description,'allmdstdstests'=>$mdstdstests,'files'=>$fileuploads,'delfiles'=>[]];
					
					$result=(object)['alltests'=>$alltests,'alltypes'=>$alltypes,'mdstds'=>$mdstds];
					$this->_sendResponse(200,CJSON::encode($result));
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
				}
				break;
				
				case 'mdstdspredata':
				try{
					
					$tests=Tests::model()->findAll();
					
					$alltests=[];
					foreach($tests as $t)
					{
						$tobsparams=[];
						$tf=$t->testfeatures[0];
						$tobs=$t->testobsparams(['condition'=>'IsSpec=1']);
						foreach($tobs as $p)
						{
							
						$tobsparams[]=(object)['PID'=>$p->Id,'Parameter'=>$p->Parameter,'PUnit'=>$p->PUnit,'PSymbol'=>$p->PSymbol,
						'PDType'=>$p->PDType,'SpecMin'=>null,'SpecMax'=>null];
							
						}
						
						
						$tbaseparams=[];
						
						$tbasics=$t->testbasicparams(['condition'=>'InPlan=1']);
						foreach($tbasics as $p)
						{
							if($p->PDType==='L')
							{
								$listcats=Dropdowns::model()->findAll(array('condition'=>'Category=:lc','params'=>array(':lc'=>$p->LCategory)));
								$tbaseparams[]=(object)['PID'=>$p->Id,'Parameter'=>$p->Parameter,'PUnit'=>$p->PUnit,'PSymbol'=>$p->PSymbol,
						'PDType'=>$p->PDType,'Value'=>null,'LCats'=>$listcats];
							}
							else
							{
						$tbaseparams[]=(object)['PID'=>$p->Id,'Parameter'=>$p->Parameter,'PUnit'=>$p->PUnit,'PSymbol'=>$p->PSymbol,
						'PDType'=>$p->PDType,'Value'=>null];
							}
							
						}
						
						$testmethods=Testmethods::model()->findAll(['condition'=>'TUID=:tuid','params'=>[':tuid'=>$t->TUID]]);
						
						
						$alltests[]=(object)['TID'=>$t->Id,'TestName'=>$t->TestName,'TUID'=>$t->TUID,'SeqNo'=>$t->SeqNo,
						'IsStd'=>$tf->IsStd,'IsTestMethod'=>$tf->IsTestMethod,'tobsparams'=>$tobsparams,'tbaseparams'=>$tbaseparams,'Frequency'=>"",'alltestmethods'=>$testmethods];
					}
					
					$alltypes[]=(object)['Id'=>'MDS','Name'=>'MDS'];
					$alltypes[]=(object)['Id'=>'TDS','Name'=>'TDS'];
					
					
					
					
					$result=(object)['alltests'=>$alltests,'alltypes'=>$alltypes];
					$this->_sendResponse(200,CJSON::encode($result));
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
				}
				break;
				case 'methodupdate':
				try{
					
				$test=Tests::model()->findByPk($data['TestId']);
						
						$model->TUID=$test->TUID;
						$model->save(false);
				
					$this->_sendResponse(200, CJSON::encode("Updated"));	
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
				}
				break;
				
				
				case 'mdstdsdata':
				try{
					$u=MyFunctions::gettokenuser();
					
					$totalitems=Mdstds::model()->count(['condition'=>'CID=:cid',
					'params'=>[':cid'=>$u->CID]]);
					
					$allmdstds=[];
					$mdstds=Mdstds::model()->findAll(['order'=>'Id Desc','condition'=>'CID=:cid',
					'params'=>[':cid'=>$u->CID],
					'limit' => $data['pl'],
					 'offset' => ($data['pn']-1)*$data['pl']]);
					foreach($mdstds as $md)
					{
						$mdstdstests=[];
						foreach($md->mdstdstests as $t)
						{
							$tobparams=[];
							foreach($t->mdstdstestobsdetails as $d)
								{
									
									$tobparams[]=(object)['Id'=>$d->Id,'MTTID'=>$d->MTTID,
									'PID'=>$d->PID,'Parameter'=>$d->p->Parameter,'SpecMin'=>$d->SpecMin,'SpecMax'=>$d->SpecMax,
									];
								}
							
							$tbaseparams=[];
							foreach($t->mdstdstestbasedetails as $d)
								{
									
									$tbaseparams[]=(object)['Id'=>$d->Id,'MTTID'=>$d->MTTID,
									'PID'=>$d->PID,'Parameter'=>$d->p->Parameter,'Value'=>$d->Value,
									];
								}
							
							$std=empty($t->sSD)?null:$t->sSD->std->Standard." ".$t->sSD->Grade;
							
							
							$mdstdstests[]=(object)['Id'=>$t->Id,'MTID'=>$t->MTID,'TID'=>$t->TID,
							'TUID'=>$t->TUID,'SSDID'=>$t->SSDID,'TMID'=>$t->TMID,'Frequency'=>$t->Freq,
							'Method'=>$t->tM->Method,'TestName'=>$t->t->TestName,'Std'=>$std,
							'tobsparams'=>$tobparams,'tbaseparams'=>$tbaseparams
							];
							
						}
						
						
						$allmdstds[]=(object)['Id'=>$md->Id,'Type'=>$md->Type,'No'=>$md->No,
						'Description'=>$md->Description,'allmdstdstests'=>$mdstdstests, 'Uploads'=>$md->mdstdsuploads];
					}
					
					
					$result=(object)['allmdstds'=>$allmdstds,'totalitems'=>$totalitems];
					$this->_sendResponse(200, CJSON::encode($result));	
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
				}
				break;
				
				case 'certsetupdate':
					try{
					$u=MyFunctions::gettokenuser();
					$certset=Settingscert::model()->find(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					//s$this->_sendResponse(401, CJSON::encode($data));	
					foreach($put_vars as $var=>$value) {
						
						if($labset->hasAttribute($var))
										$labset->$var = $value;
								
					}		
					
					
					$certset->save(false);		
					
					$this->_sendResponse(200, CJSON::encode("Updated"));	
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
				}
				break;
				
				case 'labsetupdate':
					try{
					$u=MyFunctions::gettokenuser();
					$labset=Settingslab::model()->find(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					//s$this->_sendResponse(401, CJSON::encode($data));	
					foreach($put_vars as $var=>$value) {
						
						if($labset->hasAttribute($var))
										$labset->$var = $value;
								
					}		
					
					
					$labset->save(false);		
					
					$this->_sendResponse(200, CJSON::encode("Updated"));	
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
				}
				break;
				case 'accountsetupdate':
					try{
					
						$u=MyFunctions::gettokenuser();			
					$accountset=Settingsaccount::model()->find(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					//s$this->_sendResponse(401, CJSON::encode($data));	
					foreach($put_vars as $var=>$value) {
						
						if($accountset->hasAttribute($var))
										$accountset->$var = $value;
								
					}		
					
					
					$accountset->save(false);	
					
					$this->_sendResponse(200, CJSON::encode("Updated"));	
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
				}
				break;
				case 'vaultsetupdate':
					try{
						$u=MyFunctions::gettokenuser();
					//s$this->_sendResponse(401, CJSON::encode($data));	
					$vaultset=Settingsbank::model()->find(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					//s$this->_sendResponse(401, CJSON::encode($data));	
					foreach($put_vars as $var=>$value) {
						
						if($vaultset->hasAttribute($var))
										$vaultset->$var = $value;
								
					}		
					
					
					$vaultset->save(false);
					
					$this->_sendResponse(200, CJSON::encode("Updated"));	
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
				}
				break;
				case 'firmsetupdate':
				try{
					
					$u=MyFunctions::gettokenuser();
					$firmset=Settingsfirm::model()->find(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					//s$this->_sendResponse(401, CJSON::encode($data));	
					foreach($put_vars as $var=>$value) {
						
						if($firmset->hasAttribute($var))
										$firmset->$var = $value;
								
					}		
					
					
					$firmset->save(false);
					
					$this->_sendResponse(200, CJSON::encode("Updated"));	
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
				}
				break;
				
				
				
				case 'substddata':
				
				$u=MyFunctions::gettokenuser();
					$totalitems=Substandards::model()->count(array('condition'=>'CID=:cid','params'=>[':cid'=>$u->CID],'order'=>'Id Desc'));
					
					$allecrs=Substandards::model()->findAll(array('order'=>'Id Desc',
					'condition'=>'CID=:cid',
					'params'=>[':cid'=>$u->CID],
					 'limit' => $data['pl'],
    'offset' => ($data['pn']-1)*$data['pl']
	));
					
					$allsubdata=array();
					foreach($allecrs as $sub)
					{
						
						
						$params=array();
						$oldparams=array();
						$newparams=array();
						if(!empty($sub->stdsubdetails))
						{
								$testids=[];
					$testnames=[];
							$subdetails=$sub->stdsubdetails;
						foreach($subdetails as $e)
						{
							$tn=Tests::model()->find(['condition'=>'TUID =:tuid','params'=>[':tuid'=>$e->TUID]]);
							if($e->p->IsSpec)
							{
							$params[]=(object)array('Id'=>$e->Id,'PDType'=>$e->p->PDType,
							'PId'=>$e->PId,
							'PermMin'=>(float)$e->PermMin,'PermMax'=>(float)$e->PermMax,
							'SpecMin'=>$e->SpecMin,'SpecMax'=>$e->SpecMax,
											
							'IsMajor'=>$e->IsMajor,'Parameter'=>$e->p->Parameter,'PUnit'=>$e->p->PUnit,
							'PCatId'=>$e->p->PCatId,'CatName'=>empty($e->p->pCat)?"":$e->p->pCat->CatName,'Cost'=>$e->Cost,
							'TMID'=>$e->TMID,'TUID'=>$e->TUID,'TestName'=>$tn->TestName,
							'TestMethod'=>empty($e->TMID)?null:$e->tM->Method,
											'SubStdId'=>$e->SubStdId);
							}
							$oldparams[]=$e->PId;					
						}
						// $tparams=Testobsparams::model()->findAll(array('condition'=>'t.TestId=:type',
					// 'params'=>[':type'=>$sub->TestId]));
							// foreach($tparams as $e)
					// {
						// $newparams[]=$e->Id;
						
					// }
								// $ecrs=array_diff($newparams,$oldparams)	;
						
							// $allecrs=array();			 
					// // foreach($ecrs as $ei)
					// // {
						// // $p=Testobsparams::model()->findByPk($ei);
						// // $allecrs[]=(object)array('PId'=>$p->Id,'Max'=>"",'Min'=>"",'PDType'=>$p->PDType,'Permissible'=>"",
						// // 'IsMajor'=>"",'Param'=>$p->Parameter,'PUnit'=>$p->PUnit);
						
					// // }
					// $params=array_merge($params,$allecrs);
					
					$substdtests=Substdtests::model()->findAll(['condition'=>'SSID=:ssid','params'=>[':ssid'=>$sub->Id]]);
				
					foreach($substdtests as $t)
					{
						$testids[]=$t->TID;
						$testnames[]=$t->t->TestName;
					}
					
							$allsubdata[]=(object)array('Id'=>$sub->Id,'StdId'=>$sub->StdId,'IndId'=>$sub->std->IndId,
							'TestIds'=>$testids,'Testnames'=>$testnames,
							'Industry'=>implode(" - ",array_reverse(MyFunctions::getParentCat($sub->std->IndId))),'Standard'=>$sub->std->Standard,
							'Grade'=>$sub->Grade,'ExtraInfo'=>$sub->ExtraInfo, 'Parameters'=>$params);
						}
						
					}
					
					
					$stds=Standards::model()->findAll();
					$allstds=[];
					foreach($stds as $s)
					{
						//$alltests=Tests::model()->with('ind')->findAll();
						$allstds[]=(object)array('Id'=>$s->Id,'Standard'=>$s->Standard,'IndId'=>$s->IndId,'Industry'=>$s->ind->Name);
					}
					
				
					
			
				
				$industries=Industry::model()->findAll(array('condition'=>'HasSubInd=0 AND Status=1 AND CID=:cid',
					'params'=>[':cid'=>$u->CID],));
				$allindustries=[];
				foreach($industries as $c)
							{
								$alltests=empty($c->tests)?[]:$c->tests;
								$parents = MyFunctions::getParentCat($c->Id);//array();
									$allindustries[]=(object)array('Id'=>$c->Id,'Name'=>$c->Name,
									'alltests'=>$alltests,
									'Parent'=>isset($c->ParentId)?$c->parent->Name:"",'FParent'=>end($parents),
									'ParentId'=>$c->ParentId,'PTree'=>implode(" - ",array_reverse(MyFunctions::getParentCat($c->Id))));
									
							}
					
					
					
					$result=(object)array('totalitems'=>$totalitems,'allstds'=>$allstds,'allsubdata'=>$allsubdata,'allindustries'=>$allindustries);
					
					$this->_sendResponse(200, CJSON::encode($result));				 
					break;
					
					
				
				
					case 'updcustomer':
				$transaction=$model->dbConnection->beginTransaction();
				try{
						
					
					$user=Users::model()->find(array('condition'=>'Username=:email','params'=>array(':email'=>$model->Email)));
					
					$model->Name=$data['Name'];
					$model->Email=$data['Email'];
					$model->UserId=isset($data['UserId'])?$data['UserId']:null;
					$model->GSTIN=isset($data['GSTIN'])?$data['GSTIN']:null;
					$model->save(false);
					
					
					foreach($data['Addresses'] as $ad)
						{
							if(isset($ad['Id']))
							{
								$cadd=Custaddresses::model()->findByPk($ad['Id']);
							}
							else
							{
							$cadd=new Custaddresses;
							$cadd->CustId=$model->getPrimaryKey();
							}
							$cadd->Name=$ad['Name'];
							$cadd->Email=$ad['Email'];
							$cadd->ContactNo=$ad['ContactNo'];
							$cadd->ContactPerson=$ad['ContactPerson'];
							$cadd->Address=$ad['Address'];
							$cadd->save(false);
						}
						
						
						foreach($data['DelAddresses'] as $delad)
						{
							if(isset($delad['Id']))
							{
								$dcadd=Custaddresses::model()->findByPk($ad['Id']);
								$dcadd->delete(false);
							}
							
							
						}
						
					if(empty($user))
					{
							
						$user=new Users;
						$user->FName=$model->Name;
						$user->Email=$model->Email;
						$user->ContactNo="";
						$user->Username=$model->Email;
						$user->Status=1;
						$newpwd='reset@1234';
						// A higher "cost" is more secure but consumes more processing power
						$cost = 10;
						// Create a random salt
						// Create a random salt
									if (version_compare(PHP_VERSION, '7.0.0') >= 0) 
									{
										$salt = strtr(base64_encode(random_bytes(16)), '+', '.');
									}
									else
									{
										$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
									}
						// Prefix information about the hash so PHP knows how to verify it later.
						// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
						$salt =sprintf("$2a$%02d$", $cost) . $salt;
						// Value:
						// $2a$10$eImiTXuWVxfM37uY4JANjQ==

						// Hash the password with the salt
						$hashpwd = crypt($newpwd, $salt);
								
						
						$user->Password=$hashpwd;
						
						$user->CreationDate=date('Y-m-d H:i:s');
						$user->save(false);
						
						$uir=new Userinroles;
						$uir->UserId=$user->getPrimaryKey();
						$uir->RoleId=3;
						$uir->save(false);
						
						
						$raps=Roleapppermission::model()->findAll(array('condition'=>'RoleId=:role','params'=>[':role'=>3]));
						foreach($raps as $s)
						{
							$uapp=new Userapppermission;
							$uapp->UserId=$user->getPrimaryKey();
							$uapp->SectionId=$s->SectionId;
							$uapp->C=$s->C;
							$uapp->R=$s->R;
							$uapp->U=$s->U;
							$uapp->D=$s->D;
							$uapp->A=$s->A;
							$uapp->Ch=$s->Ch;							
							$uapp->Print=$s->Print;
							$uapp->SM=$s->SM;
							$uapp->LastModified=date('Y-m-d H:i:s');
							$uapp->save(false);
							
						}
						
						
					}
					else
					{
						//$uir=$user->userinroles[0];
					}
						$transaction->commit();
						$this->_sendResponse(200, CJSON::encode("Updated"));
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
				case 'updatedropdown':
				try{
					
					$model->save(false);
						
							
						
						$this->_sendResponse(200, CJSON::encode("Updated"));
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
				
				case 'testcondupdate':
				try{
					
					$model->save(false);
						
							$conds=Testconditions::model()->findAll();
						
						$this->_sendResponse(200, CJSON::encode($conds));
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
				
				case 'importsettings':
					try{
					
					$inds=Industry::model()->findAll();
						
						$result=(object)['allindustries'=>$inds];
						
						$this->_sendResponse(200, CJSON::encode($result));
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
				case 'indparamdata':
				try{
					$allparams=[];
					
					foreach($data['TestIds'] as $test)
					{
					$tparams=Testobsparams::model()->findAll(array('condition'=>'t.TestId=:type',
					'params'=>[':type'=>$test]));
				
						
					foreach($tparams as $e)
						{
							$dts=[];
							
							$dts[]=(object)array();
							$allparams[]=(object)array('PId'=>$e->Id,'PUnit'=>$e->PUnit,'PDType'=>$e->PDType,'PCatId'=>$e->PCatId,'Cost'=>$e->Cost,'ISNABL'=>$e->ISNABL,
							'TestId'=>$e->TestId,'TUID'=>$e->test->TUID,'TestName'=>$e->test->TestName,'IsMajor'=>0,'Parameter'=>$e->Parameter,
							'dts'=>$dts,'Min'=>"",'Max'=>"",'TMID'=>"",'Cost'=>$e->Cost,'Permissible'=>"",
							'CatName'=>empty($e->pCat)?"":$e->pCat->CatName);
						}
						}
					$methods=Testmethods::model()->findAll(array('condition'=>'IndId=:indid',
					'params'=>array(':indid'=>$model->Id)));
					
					$alltestmethods=array();
					foreach($methods as $l)
					{
						
							$alltestmethods[]=(object)array('Id'=>$l->Id,'Method'=>$l->Method,'IndId'=>$l->IndId,'TestId'=>$l->TestId,'Test'=>$l->test->TestName);
						
					}	
					
						$result=(object)['allparams'=>$allparams,'alltestmethods'=>$alltestmethods];
						
						
						$this->_sendResponse(200, CJSON::encode($result));
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
				case 'updateallnotification':
				$transaction=$model->dbConnection->beginTransaction();
					try{
						
						$usernotis=Usernotifications::model()->findAll(array('condition'=>'UserId =:uid AND Status="0"','params'=>array(':uid'=>$data['UserId'])));
						foreach($usernotis as $u)
						{
							$u->Status="1";
							$u->save(false);
						}
						
						
						$notis=Usernotifications::model()->findAll(array('condition'=>'UserId =:uid AND Status="0"','params'=>array(':uid'=>$data['UserId'])));
					$allnotis=[];
					foreach($notis as $n)
					{
						$datetime1=date_create(date('Y-m-d H:i:s'));
						$datetime2=date_create($n->not->CreatedAt);
						$interval =date_diff($datetime1, $datetime2);
						$min = $interval->days * 24 * 60;
				$min += $interval->h * 60;
				$min += $interval->i;
						$allnotis[]=(object)array('Id'=>$n->Id,'NotId'=>$n->NotId,'Min'=>$min,'Notification'=>$n->not->Notifications,'CreatedAt'=>date('F Y h:i:s A',strtotime($n->not->CreatedAt)),'Status'=>$n->Status);
					}
					
					$transaction->commit();
						$this->_sendResponse(200, CJSON::encode($allnotis));
					}
					catch(Exception $e)
					{
						$transaction->rollback();
						$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
				break;
				
				case 'sendtestmail':
		$transaction=$model->dbConnection->beginTransaction();
				try
				{
                         //template Contact
                  $msg ='<div class="row" style="width:100%;border:0px solid black;">
                         <div style="width:100%;text-align:center;">                    
                         <h4>TEST MAIL Successfully</h4>
                         </div>';
                  $msg .='</div>';
				  
				  $mset=Settingsmail::model()->find();

                  $mail = new YiiMailer;
                  $mail->IsSMTP();
                  $mail->setSmtp(  $mset->Server,  $mset->Port,  $mset->Encrypt ,true,  $mset->Email,  $mset->Password);
                  $mail->setFrom($mset->Email,Yii::app()->params['appname']);
                  $mail->setTo($mset->Email);
                  $mail->setSubject('Email Test');
                  $mail->setBody($msg);
                  $mail->SMTPDebug =3;

                  if($mail->send())
                  {
                    //$transaction->commit();
                    $data=(object)array('msg'=>"Verification code sent to your email. Please check spam also.");
                    $this->_sendResponse(200, CJSON::encode($data));
                  }
                  else
                  {
                    //$transaction->rollback();
                    $this->_sendResponse(401, CJSON::encode( CJSON::encode(array($mail->getError()))));
                  }						
				}
				catch(Exception $e)
				{				
					$transaction->rollback();
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				
		break;
				case 'mailsettings':
						if($model)
						{
							$payset=Settingsmail::model()->find();
							
							$this->_sendResponse(200, CJSON::encode($payset));
							
						}
						else
						{
								$this->_sendResponse(401, CJSON::encode("Invalid User"));
						}
			break;
			
		
		   case 'updatemailset':		
			$transaction=$model->dbConnection->beginTransaction();
				try
				{			
					$mset=Settingsmail::model()->find();
					if(empty($mset))
					{
						$mset=new Settingsmail;
					}
					
						$mset->Email=$data['Email'];
							$mset->Password=$data['Password'];
								$mset->Server=$data['Server'];
						$mset->Port=$data['Port'];
						$mset->Encrypt=$data['Encrypt'];
						
						 $mset->save(false);
					
				
					 
					  $transaction->commit();
					$this->_sendResponse(200, CJSON::encode($mset));
							
										
				}
				catch(Exception $e)
				{				
						$transaction->rollback();
						$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}        		
		break;   
				case 'testupdate':
				$transaction=$model->dbConnection->beginTransaction();
								try
									{
										$model->Qty=$data['Qty'];
										$model->FormatNo=$data['FormatNo'];
										$model->RevNo=$data['RevNo'];
										$model->RevDate=$data['RevDate'];
										$model->Price=$data['Price'];
										$model->Status=$data['Status'];
										$model->DefaultNote=$data['DefaultNote'];
										$model->save(false);
										
										foreach($data['Industries'] as $i)
										{
											$indtest=Indtests::model()->find(array('condition'=>'IndId = :indid AND TestId=:testid',
											'params'=>[':indid'=>$i,':testid'=>$model->Id]));
											if(empty($indtest))
											{
												$indtest=new Indtests;
												$indtest->IndId=$i;
												$indtest->TestId=$model->Id;
												$indtest->save(false);
											}
										}
										
										$transaction->commit();
										$this->_sendResponse(200, CJSON::encode("success"));
									}
									catch(Exception $e)
									{
										$this->_sendResponse(401, CJSON::encode($e->getMessage()));
									}
				
				
				
				
				case 'formatrev':
					$transaction=$model->dbConnection->beginTransaction();
								try
									{
										if($model->Id==='2'||$model->Id==='26'||$model->Id==='27'||$model->Id==='28')
										{
											
											$htype=Hardnesstype::model()->findByPk($data['HTId']);
											if(!empty($htype))
											{
												$htype->FormatNo=$data['FormatNo'];
												$htype->RevNo=$data['RevNo'];
												$htype->RevDate=$data['RevDate'];
												$htype->save();
											}
										}
										else
										{
										$model->FormatNo=$data['FormatNo'];
										$model->RevNo=$data['RevNo'];
										$model->RevDate=$data['RevDate'];
										$model->save();
										
										}
										
										$transaction->commit();
										$this->_sendResponse(200, CJSON::encode("success"));
									}
						catch(Exception $e)
						{
										
											  $transaction->rollback();
											  
										$this->_sendResponse(401, CJSON::encode("server error"));
						}		
				break;
				
				case 'pswdupdate':
					$transaction=$model->dbConnection->beginTransaction();
					if(strcmp($oldpwd,$data['OldPassword'])!== 0)
					{
							$this->_sendResponse(401, CJSON::encode("Old Password does not match."));
					}
					else if(strcmp($oldpwd,$data['OldPassword'])===0)
					{
						try
						{
										
										$model->Password=$data['NewPassword'];
										$model->save(false);
										
										//$uir=$model->userinroles[0];
										//$uir->RoleId=$put_vars['Role'];
										//$uir->save();
										
										$transaction->commit();
										$this->_sendResponse(200, CJSON::encode("success"));
										
						}
						catch(Exception $e)
						{
										
											  $transaction->rollback();
						}		
					}
					break;
				
				case 'edituser':
					$transaction=$model->dbConnection->beginTransaction();
								try
									{
										
										//$model->Username=$model->Email;
										$model->save(false);
										if(empty($model->userinroles))
										{
											$uir=new Userinroles;
											$uir->UserId=$model->getPrimaryKey();
										}
										else
										{
										$uir=$model->userinroles[0];
										}
										$uir->RoleId=$put_vars['RoleId'];
										$uir->save();
										
											foreach($data['Appsections'] as $s)
						{
							if(empty($s['Id']))
							{
								$uapp=new Userapppermission;
							}
							else
							{
								$uapp=Userapppermission::model()->findByPk($s['Id']);
							}
							
							$uapp->UserId=$model->getPrimaryKey();
							$uapp->SectionId=$s['SectionId'];
							$uapp->C=$s['C'];
							$uapp->R=$s['R'];
							$uapp->U=$s['U'];
							$uapp->D=$s['D'];
							$uapp->A=$s['A'];
							$uapp->Ch=$s['Ch'];
							$uapp->SM=$s['SM'];
							
							$uapp->Print=$s['Print'];
							$uapp->LastModified=date('Y-m-d H:i:s');
							$uapp->save(false);
							
						}
										
										
										
										foreach($data['Branches'] as $b)
										{
												$ub=Userinbranches::model()->find(array('condition'=>'BranchId=:bid ANd UserId=:uid',
													'params'=>array(':bid'=>$b,':uid'=>$model->getPrimaryKey())));
											if(empty($ub))
											{
												$ub=new Userinbranches;
												$ub->UserId=$model->getPrimaryKey();
											}
											
											$ub->BranchId=$b;
											$ub->save(false);
										}
										
										foreach($data['DelBranches'] as $db)
										{
												$ub=Userinbranches::model()->find(array('condition'=>'BranchId=:bid ANd UserId=:uid',
													'params'=>array(':bid'=>$db['BranchId'],':uid'=>$model->getPrimaryKey())));
											if(!empty($ub))
											{
												$ub->delete();
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
						
			
				
				
				
				
				case 'stdedit':
							$transaction=$model->dbConnection->beginTransaction();
										try
											{
												$model->Standard=$data['Standard'];
												$model->IndId=$data['IndId'];
												$model->Description=isset($data['Description'])?$data['Description']:"";
												$model->save(false);
												
												
													$transaction->commit();
													$this->_sendResponse(200, CJSON::encode($data));
												
											}
											catch(Exception $e)
											{
												
													  $transaction->rollback();
													
											}		break;
				
			
				
				case 'substdupdate':
						 $transaction=$model->dbConnection->beginTransaction();
										try
											{
												
												
							
								
												$model->save(false);
												
												foreach($data['TestIds'] as $t)
												{
													$subtests=Substdtests::model()->find(['condition'=>'SSID=:ssid AND TID=:tid',
													'params'=>[':ssid'=>$model->getPrimaryKey(),':tid'=>$t]]);
													
													if(empty($subtests))
													{
														$subtests=new Substdtests;
														$subtests->SSID=$model->getPrimaryKey();
														$subtests->TID=$t;
														$subtests->save(false);
													}
												}
												
												
												foreach($data['Parameters'] as $e)
												{
													$test=Tests::model()->findByPk($e['TestId']);
													$ssdt=Stdsubdetails::model()->find(['condition'=>'PId=:pid AND 
													TUID=:tuid AND SubStdId=:ssid',
													'params'=>[':pid'=>$e['PId'],':tuid'=>$test->TUID,':ssid'=>$model->getPrimaryKey()]]);
													
													if(empty($ssdt))
													{
														
														$ssdt=new Stdsubdetails;
														$ssdt->PId=$e['PId'];
														$ssdt->TUID=$test->TUID;
														$ssdt->SubStdId=$model->getPrimaryKey();
													}
													
													$ssdt->PermMin=isset($e['PermMin'])?$e['PermMin']:null;
													$ssdt->PermMax=isset($e['PermMax'])?$e['PermMax']:null;
													$ssdt->SpecMin=isset($e['SpecMin'])?$e['SpecMin']:null;
													$ssdt->SpecMax=isset($e['SpecMax'])?$e['SpecMax']:null;
													$ssdt->IsMajor=$e['IsMajor'];
													$ssdt->save(false);
													
													
													
												}
													$transaction->commit();
													$this->_sendResponse(200, CJSON::encode($data));
												
											}
											catch(Exception $e)
											{
												
													  $transaction->rollback();
													  $this->_sendResponse(401, CJSON::encode($e->getMessage()));
													
											}				
			//	$this->_sendResponse(200, CJSON::encode($data));
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
				case 'delstd': $model =  Standards::model()->findByPk($_GET['id']);   	break;
				
				case 'customer':
					$model = Customerinfo::model()->findByPk($_GET['id']);      
					break;
					
				case 'supplier':
					$model = Suppliers::model()->findByPk($_GET['id']);      
					break;	
				case 'drophardequip':		$model = Hardnessequip::model()->findByPk($_GET['id']);      break;
				case 'drophardindent':		$model = Hardnessindent::model()->findByPk($_GET['id']);      break;
				case 'deldropdown':
					$model = Dropdowns::model()->findByPk($_GET['id']);      
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
		 
		 switch($_GET['model'])
			{
				case 'delstd':
				
						try{
							$substd=Substandards::model()->find(['condition'=>'StdId=:std','params'=>[':std'=>$_GET['id']]]);
							if(empty($substd))
							{
								$model->delete();
							}
							else
							{
								$model->Status=0;
								$model->save(false);
							}
							$this->_sendResponse(200, CJSON::encode("Standard delete"));
						}
						catch(Exception $e)
						{
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
						}
				break;
				
				default:
				// Delete the model
			$num = $model->delete();
			if($num>0)
				$this->_sendResponse(200, $num);    //this is the only way to work with backbone
			else
				$this->_sendResponse(500, 
						sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
						$_GET['model'], $_GET['id']) );
				break;
			}
			
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
			// $authmsg=MyFunctions::checkapiAuth($secid,$op);					
							// if($authmsg->number !=200)
							// {
								// $this->_sendResponse(401, CJSON::encode($authmsg->msg));
							// }
}
}

?>
