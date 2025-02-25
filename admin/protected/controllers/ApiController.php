<?php

require_once(Yii::app()->basePath . '/extensions/vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\IOFactory;

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
	

	
	
  // Actions
    public function actionList()
    {
				
			// Get the respective model instance
			switch($_GET['model'])
			{
				
				case 'rirtestreport':
				
				$u=MyFunctions::gettokenuser();
				
					$rirs=Receiptir::model()->findAll(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					$totalcount=count($rirs);
					
					if(isset($_GET['pl']))
				{
									$rirs=Receiptir::model()->findAll(array(
						'order' => 'Id desc',
						'limit' => $_GET['pl'],
						'condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID],
						'offset' => ($_GET['pn']-1)*$_GET['pl']
					));
				}
   
    


					$allrirs=array();
					foreach($rirs as $r)
					{
						
							
							$alltests=Tests::model()->findAll(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
							$testdetails=array();
							$testdetails[]=(object)array('TId'=>0,'LabNo'=>$r->LabNo);
							$rtarray=array();
							$tarray=array();
							
							
							foreach($r->rirtestdetails as $rt)
							{
								$rtarray[]=(int)$rt->TestId;
								$status="";
								if($rt->Status==="complete")
								{
									$status="passed";
								}
								else
								{
									$status=$rt->Status;
								}
								$testdetails[]=(object)array('TId'=>(int)$rt->TestId,'TestName'=>$rt->test->TestName,'Status'=>$status);
							}
							
							foreach($alltests as $t)
							{
								$tarray[]=(int)$t->Id;
							}
							
							$larray=array_diff($tarray,$rtarray);
							
							foreach($larray as $l)
							{
								$test=Tests::model()->findByPk($l);
								$testdetails[]=(object)array('TId'=>(int)$test->Id,'TestName'=>$test->TestName,'Status'=>"-");
							}
						//	usort($testdetails, cmp('TId'));
				
						$allrirs[]=$testdetails;
					}
					
					
					$data=(object)array('allrirs'=>$allrirs,'totalcount'=>$totalcount);
						$this->_sendResponse(200, CJSON::encode($data));	
					break;
				
			
			
				
				case 'rirpredata':
					
				$u=MyFunctions::gettokenuser();
					$customers=Customerinfo::model()->findAll(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					$suppliers=Suppliers::model()->findAll(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					
					$set=Settingslab::model()->find(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					
					$batchcodes=array();
					$heatranges=[];
					for ($i = 'AAA'; $i <= $set->BatchCodeStart; $i++){	
						
								$batchcodes[]=$i;
						}
				
				//	$set=Settings::model()->findByPk(1);
					
					$allrirs=Receiptir::model()->findAll(['select'=>'HeatNo,BatchCode,BatchNo',
					'condition'=>' CID=:cid','params'=>[':cid'=>$u->CID]]);
					
					foreach($allrirs as $r)
					{
						
						$batchcodes[]=$r->BatchCode;
						if(ctype_alpha($r->BatchNo))
						{
							$batchcodes[]=$r->BatchNo;
						}
						
						$heatranges[]=(object)array('HeatNo'=>$r->HeatNo,'BatchCode'=>$r->BatchCode);
						
						
					}
					
					 $array = array_map('json_encode', $heatranges);
							$array = array_unique($array);
						$array = array_map('json_decode',array_values( $array));
					$heatranges=$array;
					
				$industries=Industry::model()->findAll(array('condition'=>'ParentId is null AND CID=:cid','params'=>[':cid'=>$u->CID]));
				$allindustries=[];
				foreach($industries as $c)
							{
								$parents = array();
									$allindustries[]=(object)array('Id'=>$c->Id,'Name'=>$c->Name,
									'Parent'=>isset($c->ParentId)?$c->parent->Name:"",
									'ParentId'=>$c->ParentId,'PTree'=>implode(" > ",array_reverse(MyFunctions::getParentCat($c->Id))),'TreeLength'=>count(MyFunctions::getParentCat($c->Id)),
									'Children'=>MyFunctions::parseTree($c->Id));
									
							}
					
					$lusers=Users::model()->findAll(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					$labusers=[];
					foreach($lusers as $l)
					{
						$labusers[]=(object)array('Id'=>$l->Id,'Name'=>$l->FName." ".$l->LName);
					}
					
					
					$testconditions=Testconditions::model()->findAll();
					$stds=[];//Standards::model()->findAll();
					
					$data=(object)array('customers'=>$customers,'suppliers'=>$suppliers,'labusers'=>$labusers,'batchcodes'=>$batchcodes,
					'industries'=>$allindustries,'testconditions'=>$testconditions,'allstds'=>$stds,'heatranges'=>$heatranges);
						$this->_sendResponse(200, CJSON::encode($data));	
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
				
				case 'ririnddata':
				
					$u=MyFunctions::gettokenuser();
					
					$ind=Industry::model()->findByPk($_GET['id']);
					
					
					
					$tests=Tests::model()->findAll(array('condition'=>'IndId=:indid AND Status="1" AND CID=:cid',
					'params'=>array(':indid'=>$ind->Id,':cid'=>$u->CID)));
					$alltests=[];
					
				foreach($tests as $t)
				{$g=1;
					// for($g=1;$g<=$t->Qty;$g++)
					// {
						$tof=$t->testfeatures[0];
						$alltests[]=(object)array('TSeq'=>$t->IndId.$t->TUID.'-'.$g,
						'IndId'=>$t->IndId,'TestId'=>$t->Id,'Cost'=>$t->Cost,'TestName'=>$t->TestName.'-'.$g,'TUID'=>$t->TUID,
						'IsStd'=>$tof->IsStd,'IsTestMethod'=>$tof->IsTestMethod,'IsPlan'=>$tof->IsPlan);
						
					//}
					
				}
					
					
				
				
					$industries=Industry::model()->findAll(array('condition'=>'HasSubInd=0 AND Status=1 AND CID=:cid','params'=>[':cid'=>$u->CID]));
					
					
					
					$testconditions=Testconditions::model()->findAll();
					$alllabs=Labaccredit::model()->findAll(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					$data=(object)array('alltests'=>$alltests,
					'alllabs'=>$alllabs,
					'testconditions'=>$testconditions,
					'industries'=>$industries,);
						$this->_sendResponse(200, CJSON::encode($data));	
					break;
					
				case 'rirmdstdsload':
					$r = Receiptir::model()->findByPk($_GET['id']);
					$mds="";
					$tds="";
					if($r->NoType==='mds')
					{
						$mds=Mds::model()->findByPk($r->MdsNo);
						$mdsplans=$mds->mdstestplans(array('condition'=>'Frequency!=""',
									'params'=>array()));
						$tests=array();			
						if(!empty($mdsplans))
						{
							foreach($mdsplans as $p)
							{
								$reqdate=date('Y-m-d', strtotime(' +'.$p->Frequency.' days'));
								switch($p->MDTest)
								{
									
									case 'CDC':
													$t=Tests::model()->find(array('condition'=>'Keyword="CDC"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
									
									case 'CD':
													$t=Tests::model()->find(array('condition'=>'Keyword="CD"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
											
									case 'C':
									
											
											$t=Tests::model()->find(array('condition'=>'Keyword="C"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>	$reqdate,								
									);
											break;
											
									case 'GS':
													$t=Tests::model()->find(array('condition'=>'Keyword="GS"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;	

										case 'H':
									
											$t=Tests::model()->find(array('condition'=>'Keyword="H"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
											'LoadId'=>empty($p->mdsmechdetails)?"":$p->mdsmechdetails[0]->LoadId,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,'HtypeId'=>$p->HtypeId,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;		

										case 'HET':
											$t=Tests::model()->find(array('condition'=>'Keyword="HET"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;	
									
									case 'I':
											$t=Tests::model()->find(array('condition'=>'Keyword="I"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'TempId'=>empty($p->mdsmechdetails)?"":$p->mdsmechdetails[0]->TempId,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;	

										case 'IRK':
											$t=Tests::model()->find(array('condition'=>'Keyword="IRK"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;

									case 'IRW':
											$t=Tests::model()->find(array('condition'=>'Keyword="IRW"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;											
										case 'MCD':
											$t=Tests::model()->find(array('condition'=>'Keyword="MCD"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
													case 'MCT':
											$t=Tests::model()->find(array('condition'=>'Keyword="MCT"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
												case 'MDC':
											$t=Tests::model()->find(array('condition'=>'Keyword="MDC"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
												case 'MS':
											$t=Tests::model()->find(array('condition'=>'Keyword="MS"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
												case 'PL':
											$t=Tests::model()->find(array('condition'=>'Keyword="PL"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
												case 'SS':
											$t=Tests::model()->find(array('condition'=>'Keyword="SS"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
												case 'T':
											$t=Tests::model()->find(array('condition'=>'Keyword="T"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
									case 'THL':
											$t=Tests::model()->find(array('condition'=>'Keyword="THL"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
												case 'TT':
											$t=Tests::model()->find(array('condition'=>'Keyword="TT"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
												case 'WT':
											$t=Tests::model()->find(array('condition'=>'Keyword="WT"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;

										
									
								}
								
								
								
							}
						}
								$model=(object)array('ltests'=>$tests);
								$this->_sendResponse(200, CJSON::encode($model));
						
					}
					else if($r->NoType==='tds')
						{
							$tds=Tds::model()->findByPk($r->TdsNo);
								$tdsplans=$tds->tdstestplans(array('condition'=>'Frequency!=""',
									'params'=>array()));
						$tests=array();			
						if(!empty($tdsplans))
						{
							foreach($tdsplans as $p)
							{
								$reqdate=date('Y-m-d', strtotime(' +'.$p->Frequency.' days'));
								switch($p->MDTest)
								{
									
									case 'CDC':
													$t=Tests::model()->find(array('condition'=>'Keyword="CDC"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
									
									case 'CD':
													$t=Tests::model()->find(array('condition'=>'Keyword="CD"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
											
									case 'C':
									
											
											$t=Tests::model()->find(array('condition'=>'Keyword="C"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>	$reqdate,								
									);
											break;
											
									case 'GS':
													$t=Tests::model()->find(array('condition'=>'Keyword="GS"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;	

										case 'H':
									
											$t=Tests::model()->find(array('condition'=>'Keyword="H"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
											'LoadId'=>empty($p->tdsmechdetails)?"":$p->tdsmechdetails[0]->LoadId,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,'HtypeId'=>$p->HtypeId,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;		

										case 'HET':
											$t=Tests::model()->find(array('condition'=>'Keyword="HET"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;	
									
									case 'I':
											$t=Tests::model()->find(array('condition'=>'Keyword="I"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'TempId'=>empty($p->tdsmechdetails)?"":$p->tdsmechdetails[0]->TempId,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;	

										case 'IRK':
											$t=Tests::model()->find(array('condition'=>'Keyword="IRK"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;

									case 'IRW':
											$t=Tests::model()->find(array('condition'=>'Keyword="IRW"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;											
										case 'MCD':
											$t=Tests::model()->find(array('condition'=>'Keyword="MCD"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
													case 'MCT':
											$t=Tests::model()->find(array('condition'=>'Keyword="MCT"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
												case 'MDC':
											$t=Tests::model()->find(array('condition'=>'Keyword="MDC"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
												case 'MS':
											$t=Tests::model()->find(array('condition'=>'Keyword="MS"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
												case 'PL':
											$t=Tests::model()->find(array('condition'=>'Keyword="PL"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
												case 'SS':
											$t=Tests::model()->find(array('condition'=>'Keyword="SS"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
												case 'T':
											$t=Tests::model()->find(array('condition'=>'Keyword="T"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
									case 'THL':
											$t=Tests::model()->find(array('condition'=>'Keyword="THL"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
												case 'TT':
											$t=Tests::model()->find(array('condition'=>'Keyword="TT"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;
												case 'WT':
											$t=Tests::model()->find(array('condition'=>'Keyword="WT"'));
											$testmethod=Testmethods::model()->findByPk($p->TestMethodId);
											$tests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'TestId'=>$t->Id,
									'TestMethod'=>$testmethod,'Applicable'=>"true",'ExtraInfo'=>"",'Keyword'=>$t->Keyword,
									'StandardId'=>$p->StandardId,'SubStdId'=>$p->SubStdId,'ReqDate'=>$reqdate,									
									);
											break;

										
									
								}
								
								
								
							}
						}
								$model=(object)array('ltests'=>$tests);
								$this->_sendResponse(200, CJSON::encode($model));
						
						}
						
					
						
									break;
				case 'pdireditdata':		
				
							$pdir=Pdirbasic::model()->findByPk($_GET['id']);
							$basic=(object)array('Id'=>$pdir->Id,'Purchaser'=>$pdir->Purchaser,'Description'=>$pdir->Description,'PoNo'=>$pdir->PoNo,
							'Dimension'=>$pdir->Dimension,'Qty'=>$pdir->Qty,'DrawingNo'=>$pdir->DrawingNo,'Manufracturer'=>$pdir->Manufracturer,
							'Note'=>$pdir->Note,'OANo'=>$pdir->OANo,'PdirDate'=>$pdir->PdirDate,'observations'=>$pdir->pdirobservations);
				
				$model=(object)array('basic'=>$basic);
				break;
case 'externaleditdata':
							$rextds=Rirexttestdetail::model()->findAll(array('condition'=>'RIRId=:rno',
										 'params'=>array(':rno'=>$_GET['id']),));
							$r=Receiptir::model()->findByPk($_GET['id']);			
							
							
						
								
							$proofcond="";
							
								$basic=(object)array('Id'=>$r->Id,'RirNo'=>$r->RirNo,'PartName'=>$r->PartName,'LabNo'=>$r->LabNo,
								'RefPurchaseOrder'=>$r->RefPurchaseOrder,'Supplier'=>$d->rIR->supplier->Name,'SupplierId'=>$d->rIR->SupplierId,'GrinNo'=>$r->GrinNo,'Quantity'=>$r->Quantity,
								'HeatNo'=>$r->HeatNo,'BatchNo'=>$r->BatchNo,'NoType'=>$r->NoType,
								  'MaterialCondition'=>$r->MaterialCondition,'MaterialGrade'=>$r->MaterialGrade,
								'BatchCode'=>$r->BatchCode,'ExternalTests'=>$rextds);
						
								
					$model=(object)array('basic'=>$basic);		
							
						break;	
						

						
					case 'testobseditdata':
							
							try{
										
							$d=Rirtestdetail::model()->findByPk($_GET['id']);
						
														
							$sub=null;
							$std=null;
							if(!empty($d->SSID))
							{
							
						$sub=Substandards::model()->findByPk($d->SSID);	
							
						
						$substandard="";
						
						$substandard=$sub->Grade." ".$sub->ExtraInfo;
						
						$std=$sub->std->Standard." ".$substandard;
							
							}
							
							$topbasicparams=[];
							$bottombasicparams=[];
							
						$allbps=Testbasicparams::model()->findAll(array('condition'=>'TestId=:testid','params'=>[':testid'=>$d->TestId]));	
							
									if(empty($d->rirtestbasics))
									{
										foreach($allbps as $bp)
										{
											$listcats=[];
														if($bp->PDType ==='L')
														{
															$listcats=Dropdowns::model()->findAll(array('condition'=>'Category=:lc','params'=>array(':lc'=>$bp->LCategory)));
														}
										$topbasicparams[]=(object)array('Parameter'=>$bp->Parameter,'TBPID'=>$bp->Id,'PUnit'=>$bp->PUnit,
										'PDType'=>$bp->PDType,'BValue'=>"",'LCats'=>$listcats,);
										}

									}										
									else
									{
										foreach($d->rirtestbasics as $bp)
										{
											
											$listcats=[];
														if($bp->tBP->PDType ==='L')
														{
															$listcats=Dropdowns::model()->findAll(array('condition'=>'Category=:lc','params'=>array(':lc'=>$bp->tBP->LCategory)));
														}
													
											if($bp->tBP->Position==='Top')
											{												
										$topbasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
										'BValue'=>$bp->BValue,'Position'=>$bp->tBP->Position,
										'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,'LCats'=>$listcats,
										'PDType'=>$bp->tBP->PDType);
											}
											
											if($bp->tBP->Position==='Down')
											{												
										$bottombasicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
										'BValue'=>$bp->BValue,'Position'=>$bp->tBP->Position,
										'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,'LCats'=>$listcats,
										'PDType'=>$bp->tBP->PDType);
											}
										}
									}
									
								$testid=$d->TUID;	
														
							switch($testid)
							{
						
						case 'GRAIN':
								$sds=array();
								$usedele=array();
								$newels=array();
								$allecrs=array();
								
								$allformulas=[];
								
									$obsvalues=[];
									
							if(!empty($d->rirtestobs))								
									{
									
											 
										foreach($d->rirtestobs as $e)
										{
											$cr=Stdsubdetails::model()->find(array('condition'=>'SubStdId=:ID AND PId=:eid',
													 'params'=>array(':ID'=>$d->SSID,':eid'=>$e->TPID),));
													 $usedele[]=$e->TPID;	
											if(!empty($cr))
											{	
										
										$obsvalues=[];
											$p=Testobsparams::model()->findByPk($e->TPID);
										
												for($i=0;$i<$p->MR;$i++)
												{
													
															$obsvalues[]=(object)array('Value'=>"");
														$sds[$e->tP->PSymbol]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,
												'ISNABL'=>$e->tP->ISNABL,'MR'=>$p->MR,
												'Permissible'=>$cr->PermMin,'PUnit'=>$e->tP->PUnit,'PSymbol'=>$e->tP->PSymbol,'TestMethod'=>empty($e->TMID)?null:$e->tM->Method,
												'Param'=>$cr->p->Parameter,'SpecMin'=>(float)$e->SpecMin,'SpecMax'=>(float)$e->SpecMax,
												'PDType'=>$e->tP->PDType,'Values'=>empty($e->rirtestobsvalues)?$obsvalues:$e->rirtestobsvalues,'PCatId'=>$e->tP->PCatId,'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
												'RTID'=>$e->RTID,'Case'=>"Default");
													
												}
												
											}
											else
											{
												
												
												$p=Testobsparams::model()->findByPk($e->TPID);
												
												//--for formula
												$formula="";
												$formdts=[];
												$informdts=0;
												$informname="";
												$obsvalues=[];
												
										
										
										
										if(empty($e->rirtestobsvalues))
										{
											for($i=0;$i<$p->MR;$i++)
												{
													
														$obsvalues[]=(object)array('Value'=>"");
														
													
												}
											
										}
										else
										{
											
											
										
											
											$ity=0;
											foreach($e->rirtestobsvalues as $rob )
											{
														$obsvalues[]=(object)array('Id'=>$rob->Id,'RTOBID'=>$rob->RTOBID,
												'Value'=>$rob->Value);
												
												$ity++;
											}
										}
										
										
										
												$sds[$e->tP->PSymbol]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'ISNABL'=>$e->tP->ISNABL,
												'Permissible'=>"",'PUnit'=>$e->tP->PUnit,'PSymbol'=>$e->tP->PSymbol,'TestMethod'=>empty($e->TMID)?null:$e->tM->Method,'informdts'=>$informdts,'informname'=>$informname,
												'FormVal'=>$p->FormVal,'Formula'=>$formula,'FormDts'=>$formdts,'MR'=>$p->MR,
												'Param'=>$p->Parameter,'SpecMin'=>"",'SpecMax'=>"",
												'PDType'=>$e->tP->PDType,'Values'=>$obsvalues,'PCatId'=>$e->tP->PCatId,'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
												'RTID'=>$e->RTID,'Case'=>"Default");
											}
									
										}	
								
									}
								
									$extra=(object)array();
								break;
								
								
								case 'CASE':
								$sds=array();
						$usedele=array();
						$newels=array();
						$allecrs=array();
						
						$allformulas=[];
							
							$obsvalues=[];
							
							if(!empty($d->rirtestobs))								
									{
									
											 
										foreach($d->rirtestobs as $e)
										{
											$cr=Stdsubdetails::model()->find(array('condition'=>'SubStdId=:ID AND PId=:eid',
													 'params'=>array(':ID'=>$d->SSID,':eid'=>$e->TPID),));
													 $usedele[]=$e->TPID;	
											if(!empty($cr))
											{	
										
										$values=[];
											$p=Testobsparams::model()->findByPk($e->TPID);
										if(empty($e->rirtestobsvalues))
										{
											for($i=0;$i<$p->MR;$i++)
												{
													
															$values[]=(object)array('Distance'=>"",'Hardness'=>"");
														
													
												}
												$obsvalues=(object)['Values'=>$values];
										}
										else
										{
											//$obsvalues=(object)['Values'=>$e->rirtestobsvalues];
											$values=json_decode($e->rirtestobsvalues[0]->Value);
											$obsvalues=(object)['Id'=>$e->rirtestobsvalues[0]->Id,'Values'=>$values];
										}
												
												$sds[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,
												'ISNABL'=>$e->tP->ISNABL,'MR'=>$p->MR,
												'Permissible'=>$cr->PermMin,'PUnit'=>$e->tP->PUnit,'PSymbol'=>$e->tP->PSymbol,'TestMethod'=>empty($e->TMID)?null:$e->tM->Method,
												'Param'=>$cr->p->Parameter,'SpecMin'=>(float)$e->SpecMin,'SpecMax'=>(float)$e->SpecMax,
												'PDType'=>$e->tP->PDType,'Values'=>$obsvalues,'PCatId'=>$e->tP->PCatId,'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
												'RTID'=>$e->RTID,'Case'=>"Default");
											}
											else
											{
												
												
												$p=Testobsparams::model()->findByPk($e->TPID);
												
												//--for formula
												$formula="";
												$formdts=[];
												$informdts=0;
												$informname="";
												$values=[];
												
										
										
										
										if(empty($e->rirtestobsvalues))
										{
											
											
											for($i=0;$i<$p->MR;$i++)
												{
													
														$values[]=(object)array('Distance'=>"",'Hardness'=>"");
														
													
												}
											$obsvalues=(object)['Values'=>$values];
										}
										else
										{
											
										
												
														$values=json_decode($e->rirtestobsvalues[0]->Value);
														$obsvalues=(object)['Id'=>$e->rirtestobsvalues[0]->Id,'Values'=>$values];
											
										}
										
										
										
												$sds[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'ISNABL'=>$e->tP->ISNABL,
												'Permissible'=>"",'PUnit'=>$e->tP->PUnit,'PSymbol'=>$e->tP->PSymbol,'TestMethod'=>empty($e->TMID)?null:$e->tM->Method,'informdts'=>$informdts,'informname'=>$informname,
												'FormVal'=>$p->FormVal,'Formula'=>$formula,'FormDts'=>$formdts,'MR'=>$p->MR,
												'Param'=>$p->Parameter,'SpecMin'=>"",'SpecMax'=>"",
												'PDType'=>$e->tP->PDType,'Values'=>$obsvalues,'PCatId'=>$e->tP->PCatId,'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
												'RTID'=>$e->RTID,'Case'=>"Default2");
											}
									
										}	
								
									}
								
									$extra=(object)array();
								break;
							
							
								
								
								case 'RBHARD':
								case 'RCHARD':
								case 'MVHARD':
								case 'VHARD':
								case 'BHARD':
								$sds=array();
						$usedele=array();
						$newels=array();
						$allecrs=array();
						
						$allformulas=[];
							
							$obsvalues=[];
							
							if(!empty($d->rirtestobs))								
									{
									
											 
										foreach($d->rirtestobs as $e)
										{
											$cr=Stdsubdetails::model()->find(array('condition'=>'SubStdId=:ID AND PId=:eid',
													 'params'=>array(':ID'=>$d->SSID,':eid'=>$e->TPID),));
													 $usedele[]=$e->TPID;	
											if(!empty($cr))
											{	
										
										$values=[];
											$p=Testobsparams::model()->findByPk($e->TPID);
										if(empty($e->rirtestobsvalues))
										{
											for($i=0;$i<$p->MR;$i++)
												{
													
															$values[]=(object)array('SValue'=>"",'CValue'=>"");
														
													
												}
												$obsvalues=(object)['Values'=>$values];
										}
										else
										{
											//$obsvalues=(object)['Values'=>$e->rirtestobsvalues];
											$values=json_decode($e->rirtestobsvalues[0]->Value);
											$obsvalues=(object)['Id'=>$e->rirtestobsvalues[0]->Id,'Values'=>$values];
										}
												
												$sds[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,
												'ISNABL'=>$e->tP->ISNABL,'MR'=>$p->MR,
												'Permissible'=>$cr->PermMin,'PUnit'=>$e->tP->PUnit,'PSymbol'=>$e->tP->PSymbol,'TestMethod'=>empty($e->TMID)?null:$e->tM->Method,
												'Param'=>$cr->p->Parameter,'SpecMin'=>(float)$e->SpecMin,'SpecMax'=>(float)$e->SpecMax,
												'PDType'=>$e->tP->PDType,'Values'=>$obsvalues,'PCatId'=>$e->tP->PCatId,'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
												'RTID'=>$e->RTID,'Case'=>"Default");
											}
											else
											{
												
												
												$p=Testobsparams::model()->findByPk($e->TPID);
												
												//--for formula
												$formula="";
												$formdts=[];
												$informdts=0;
												$informname="";
												$values=[];
												
										
										
										
										if(empty($e->rirtestobsvalues))
										{
											
											
											for($i=0;$i<$p->MR;$i++)
												{
													
														$values[]=(object)array('SValue'=>"",'CValue'=>"");
														
													
												}
											$obsvalues=(object)['Values'=>$values];
										}
										else
										{
											
										
												
														$values=json_decode($e->rirtestobsvalues[0]->Value);
														$obsvalues=(object)['Id'=>$e->rirtestobsvalues[0]->Id,'Values'=>$values];
											
										}
										
										
										
												$sds[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'ISNABL'=>$e->tP->ISNABL,
												'Permissible'=>"",'PUnit'=>$e->tP->PUnit,'PSymbol'=>$e->tP->PSymbol,'TestMethod'=>empty($e->TMID)?null:$e->tM->Method,'informdts'=>$informdts,'informname'=>$informname,
												'FormVal'=>$p->FormVal,'Formula'=>$formula,'FormDts'=>$formdts,'MR'=>$p->MR,
												'Param'=>$p->Parameter,'SpecMin'=>"",'SpecMax'=>"",
												'PDType'=>$e->tP->PDType,'Values'=>$obsvalues,'PCatId'=>$e->tP->PCatId,'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
												'RTID'=>$e->RTID,'Case'=>"Default2");
											}
									
										}	
								
									}
								
									$extra=(object)array();
								break;
								
								
								case 'TORQ':
								try{
									$sds=array();
						
				
							$alles=Testobsparams::model()->findAll(array('condition'=>'TestId=:testid','params'=>[':testid'=>$d->TestId]));
								
							$obsvalues=[];
							$allformulas=[];
							
							if(!empty($d->rirtestobs))								
									{
									
											 
										foreach($d->rirtestobs as $e)
										{
											$cr=Stdsubdetails::model()->find(array('condition'=>'SubStdId=:ID AND PId=:eid',
													 'params'=>array(':ID'=>$d->SSID,':eid'=>$e->TPID),));
													 $usedele[]=$e->TPID;	
											if(!empty($cr))
											{	
										
										$obsvalues=[];
											$p=Testobsparams::model()->findByPk($e->TPID);
										
									
											
												
												
												
										if(empty($e->rirtestobsvalues))
										{
											
											
											
											
													$obsvalues[]=(object)array('Formula'=>"",'Value'=>"");
												
										}
										else
										{
											
											
										
										
											
											$ity=0;
											foreach($e->rirtestobsvalues as $rob )
											{
												
												$obsvalues[]=(object)array('Id'=>$rob->Id,'RTOBID'=>$rob->RTOBID,
												'Value'=>$rob->Value);
												$ity++;
											}
										}
										
												
												$sds[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,
												'ISNABL'=>$e->tP->ISNABL,'MR'=>$p->MR,
												
												'Permissible'=>$cr->PermMin,'PUnit'=>$e->tP->PUnit,'PSymbol'=>$e->tP->PSymbol,
												
												'Param'=>$cr->p->Parameter,'SpecMin'=>(float)$e->SpecMin,'SpecMax'=>(float)$e->SpecMax,
												'PDType'=>$e->tP->PDType,'Values'=>$obsvalues,'PCatId'=>$e->tP->PCatId,'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
												'RTID'=>$e->RTID,'Case'=>"Default");
											}
											else
											{
												
												
												$p=Testobsparams::model()->findByPk($e->TPID);
												
												
												$obsvalues=[];
												
										
										
										
										if(empty($e->rirtestobsvalues))
										{											
											$obsvalues[]=(object)array('Value'=>"");
										}
										else
										{	
											
											foreach($e->rirtestobsvalues as $rob )
											{
												
												$obsvalues[]=(object)array('Id'=>$rob->Id,'RTOBID'=>$rob->RTOBID,
												'Value'=>$rob->Value,);
												
											}
										}								
										
										
												$sds[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'ISNABL'=>$e->tP->ISNABL,
												'Permissible'=>"",'PUnit'=>$e->tP->PUnit,'PSymbol'=>$e->tP->PSymbol,
												'MR'=>$p->MR,
												'Param'=>$p->Parameter,'SpecMin'=>"",'SpecMax'=>"",
												'PDType'=>$e->tP->PDType,'Values'=>$obsvalues,'PCatId'=>$e->tP->PCatId,'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
												'RTID'=>$e->RTID,'Case'=>"Default");
											}
									
										}	
								
									}
									else
									{
										// foreach($alles as $e)
										// {
												// $sds[]=(object)array('Id'=>$e->Id,'TPID'=>$e->Id,'ISNABL'=>$e->ISNABL,
												// 'Permissible'=>"",'PUnit'=>$e->PUnit,'PSymbol'=>$e->PSymbol,
												// 'MR'=>$e->MR,
												// 'Param'=>$e->Parameter,'SpecMin'=>"",'SpecMax'=>"",
												// 'PDType'=>$e->PDType,'Values'=>$obsvalues,'PCatId'=>$e->PCatId,
												// 'CatName'=>empty($e->pCat)?"":$e->pCat->CatName,
												// 'RTID'=>null,'Case'=>"Default");
											// }
									}
									
									$extra=(object)array();
								}
								catch(Exception $e)
								{
									$this->_sendResponse(401, CJSON::encode($e->getMessage()));
								}
								break;
								
								
								
								
									case 'IRW':
								$sds=array();
						$usedele=array();
						$newels=array();
						$allecrs=array();
						
						$allformulas=[];
							
							$obsvalues=[];
							
							if(!empty($d->rirtestobs))								
									{
									
											 
										foreach($d->rirtestobs as $e)
										{
											$cr=Stdsubdetails::model()->find(array('condition'=>'SubStdId=:ID AND PId=:eid',
													 'params'=>array(':ID'=>$d->SSID,':eid'=>$e->TPID),));
													 $usedele[]=$e->TPID;	
											if(!empty($cr))
											{	
										
										$values=[];
											$p=Testobsparams::model()->findByPk($e->TPID);
										if(empty($e->rirtestobsvalues))
										{
											
													$values[]=(object)['ThinA'=>"",'ThinB'=>"",'ThinC'=>"",'ThinD'=>"",
							'ThickA'=>"",'ThickB'=>"",'ThickC'=>"",'ThickD'=>""];
															//$values[]=(object)array('Location'=>'HV'.$i+1,'Value'=>"");
														
													
												
												$obsvalues=(object)['Values'=>$values];
										}
										else
										{
											//$obsvalues=(object)['Values'=>$e->rirtestobsvalues];
											$values=json_decode($e->rirtestobsvalues[0]->Value);
											$obsvalues=(object)['Id'=>$e->rirtestobsvalues[0]->Id,'Values'=>$values];
										}
												
												$sds[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,
												'ISNABL'=>$e->tP->ISNABL,'MR'=>$p->MR,
												'Permissible'=>$cr->PermMin,'PUnit'=>$e->tP->PUnit,'PSymbol'=>$e->tP->PSymbol,'TestMethod'=>empty($e->TMID)?null:$e->tM->Method,
												'Param'=>$cr->p->Parameter,'SpecMin'=>(float)$e->SpecMin,'SpecMax'=>(float)$e->SpecMax,
												'PDType'=>$e->tP->PDType,'Values'=>$obsvalues,'PCatId'=>$e->tP->PCatId,'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
												'RTID'=>$e->RTID,'Case'=>"Default");
											}
											else
											{
												
												
												$p=Testobsparams::model()->findByPk($e->TPID);
												
												//--for formula
												$formula="";
												$formdts=[];
												$informdts=0;
												$informname="";
												$values=[];
												
										
										
										
										if(empty($e->rirtestobsvalues))
										{
											
											
											for($i=0;$i<$p->MR;$i++)
												{
													
														$values[]=(object)['ThinA'=>"",'ThinB'=>"",'ThinC'=>"",'ThinD'=>"",
							'ThickA'=>"",'ThickB'=>"",'ThickC'=>"",'ThickD'=>""];
														
													
												}
											$obsvalues=(object)['Values'=>$values];
										}
										else
										{
													$values=json_decode($e->rirtestobsvalues[0]->Value);
														if(is_null($values))
														{
															for($i=0;$i<$p->MR;$i++)
															{													
																	//$values[]=(object)array('Location'=>'HV'.$i+1,'Value'=>"");	
															$values[]=(object)['ThinA'=>"",'ThinB'=>"",'ThinC'=>"",'ThinD'=>"",
																						'ThickA'=>"",'ThickB'=>"",'ThickC'=>"",'ThickD'=>""];														
																
															}
															$obsvalues=(object)['Id'=>$e->rirtestobsvalues[0]->Id,'Values'=>$values];
														}
														else
														{
														
														$values=json_decode($e->rirtestobsvalues[0]->Value);
											$obsvalues=(object)['Id'=>$e->rirtestobsvalues[0]->Id,'Values'=>$values];
														}
											
										}
										
										
										
												$sds[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'ISNABL'=>$e->tP->ISNABL,
												'Permissible'=>"",'PUnit'=>$e->tP->PUnit,'PSymbol'=>$e->tP->PSymbol,'TestMethod'=>empty($e->TMID)?null:$e->tM->Method,'informdts'=>$informdts,'informname'=>$informname,
												'FormVal'=>$p->FormVal,'Formula'=>$formula,'FormDts'=>$formdts,'MR'=>$p->MR,
												'Param'=>$p->Parameter,'SpecMin'=>"",'SpecMax'=>"",
												'PDType'=>$e->tP->PDType,'Values'=>$obsvalues,'PCatId'=>$e->tP->PCatId,'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
												'RTID'=>$e->RTID,'Case'=>"Default2");
											}
									
										}	
								
									}
								
									$extra=(object)array();
								break;
								
								
								case 'IRK':
								$sds=array();
						$usedele=array();
						$newels=array();
						$allecrs=array();
						
						$allformulas=[];
							
							$obsvalues=[];
							
							if(empty($d->rirtestobs))								
									{
									
									
											
												
												
												
										
										if(empty($e->rirtestobsvalues))
										{
											if($p->Parameter==='Obs')
											{
													$custobs=(object)[    'SpecNo' => '',    'AreaPol' => '',    'obs' => [
													['TypeOfInc' => 'SS','RatNo0' => 0,'RatNo1' => 0,'RatNo2' => 0,'RatNo3' => 0, 'RatNo4' => 0,'RatNo5' => 0,'RatNo6' => 0,'RatNo7' => 0,'RatNo8' => 0,'SStar' => 0 ],
													['TypeOfInc' => 'OA','RatNo0' => 0,'RatNo1' => 0,'RatNo2' => 0,'RatNo3' => 0, 'RatNo4' => 0,'RatNo5' => 0,'RatNo6' => 0,'RatNo7' => 0,'RatNo8' => 0,'OStar' => 0 ],
													['TypeOfInc' => 'OS','RatNo0' => 0,'RatNo1' => 0,'RatNo2' => 0,'RatNo3' => 0, 'RatNo4' => 0,'RatNo5' => 0,'RatNo6' => 0,'RatNo7' => 0,'RatNo8' => 0,'OStar' => 0 ],
													['TypeOfInc' => 'OG','RatNo0' => 0,'RatNo1' => 0,'RatNo2' => 0,'RatNo3' => 0, 'RatNo4' => 0,'RatNo5' => 0,'RatNo6' => 0,'RatNo7' => 0,'RatNo8' => 0,'OStar' => 0 ],
														]];
														
															$obsvalues[]=(object)array('Value'=>$custobs);
											
											}
											else
											{
													$obsvalues[]=(object)array('Value'=>"");
											}
											
											
												$sds[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,
												'PUnit'=>$e->tP->PUnit,'PSymbol'=>$e->tP->PSymbol,
												'MR'=>$p->MR,
												'Param'=>$p->Parameter,'SpecMin'=>"",'SpecMax'=>"",
												'PDType'=>$e->tP->PDType,'Values'=>$obsvalues,'PCatId'=>$e->tP->PCatId,'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
												'RTID'=>$e->RTID,'Case'=>"Default2");
										}
										
										
										
										
										
												
											
											
								
									}
									else
									{
									
											 
										foreach($d->rirtestobs as $e)
										{
											
												
												
												
												//--for formula
												$formula="";
												$formdts=[];
												$informdts=0;
												$informname="";
												$values=[];
												$obsvalues=[];
										$p=Testobsparams::model()->findByPk($e->TPID);
										
										if(empty($e->rirtestobsvalues))
										{
											if($p->Parameter==='Obs')
											{
													// $custobs=(object)[    'SpecNo' => '',    'AreaPol' => '',    'obs' => [
													// ['TypeOfInc' => 'SS','RatNo0' => 0,'RatNo1' => 0,'RatNo2' => 0,'RatNo3' => 0, 'RatNo4' => 0,'RatNo5' => 0,'RatNo6' => 0,'RatNo7' => 0,'RatNo8' => 0,'SStar' => 0 ],
													// ['TypeOfInc' => 'OA','RatNo0' => 0,'RatNo1' => 0,'RatNo2' => 0,'RatNo3' => 0, 'RatNo4' => 0,'RatNo5' => 0,'RatNo6' => 0,'RatNo7' => 0,'RatNo8' => 0,'OStar' => 0 ],
													// ['TypeOfInc' => 'OS','RatNo0' => 0,'RatNo1' => 0,'RatNo2' => 0,'RatNo3' => 0, 'RatNo4' => 0,'RatNo5' => 0,'RatNo6' => 0,'RatNo7' => 0,'RatNo8' => 0,'OStar' => 0 ],
													// ['TypeOfInc' => 'OG','RatNo0' => 0,'RatNo1' => 0,'RatNo2' => 0,'RatNo3' => 0, 'RatNo4' => 0,'RatNo5' => 0,'RatNo6' => 0,'RatNo7' => 0,'RatNo8' => 0,'OStar' => 0 ],
														// ]];
														
															// $obsvalues[]=$custobs;
											
											}
											else
											{
													$obsvalues[]=(object)array('Value'=>"");
											}
											
											
												$sds[$e->tP->PSymbol]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,
												'PUnit'=>$e->tP->PUnit,'PSymbol'=>$e->tP->PSymbol,
												'MR'=>$p->MR,
												'Param'=>$p->Parameter,'SpecMin'=>"",'SpecMax'=>"",
												'PDType'=>$e->tP->PDType,'Values'=>$obsvalues,'PCatId'=>$e->tP->PCatId,'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
												'RTID'=>$e->RTID,'Case'=>"Default2");
										}
										else
										{
											
											if($p->Parameter==='Obs')
											{
												foreach($e->rirtestobsvalues as $rob)
												{
													$rv=empty($rob->Value)?[]:json_decode($rob->Value);
													if(!empty($rv))
													{
														//$this->_sendResponse(401, CJSON::encode($e->getMessage()));
													$obsvalues[]=(object)['Id'=>$rob->Id,'RTOBID'=>$rob->RTOBID,'SpecNo'=>$rv->SpecNo,'AreaPol'=>$rv->AreaPol,'obs'=>$rv->obs,
												];
													}
											
													
												}
														
														
											
											}
											else
											{
												foreach($e->rirtestobsvalues as $rob)
												{
													$obsvalues[]=(object)array('Id'=>$rob->Id,'RTOBID'=>$rob->RTOBID,
												'Value'=>$rob->Value,);
												}
											}
											
											
												$sds[$e->tP->PSymbol]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,
												'PUnit'=>$e->tP->PUnit,'PSymbol'=>$e->tP->PSymbol,
												'MR'=>$p->MR,
												'Param'=>$p->Parameter,'SpecMin'=>"",'SpecMax'=>"",
												'PDType'=>$e->tP->PDType,'Values'=>$obsvalues,'PCatId'=>$e->tP->PCatId,'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
												'RTID'=>$e->RTID,'Case'=>"Default2");
										
										
										}
										
										
										
										
												
											
										}	
								
									}
								
								
									$extra=(object)array();
								break;
								
								
								
								default:
								try{
									$sds=array();
						$usedele=array();
						$newels=array();
						$allecrs=array();
						
						$allformulas=Formulation::model()->findAll(array('condition'=>'TestId=:testid','params'=>[':testid'=>$d->TestId]));
							$alles=Testobsparams::model()->findAll(array('condition'=>'TestId=:testid','params'=>[':testid'=>$d->TestId]));
								foreach($alles as $e)
							{
								$newels[]=$e->Id;
								
							}
							$obsvalues=[];
							
							if(!empty($d->rirtestobs))								
									{
									
											 
										foreach($d->rirtestobs as $e)
										{
											$cr=Stdsubdetails::model()->find(array('condition'=>'SubStdId=:ID AND PId=:eid',
													 'params'=>array(':ID'=>$d->SSID,':eid'=>$e->TPID),));
													 $usedele[]=$e->TPID;	
													 
													 
													 $listcats=[];
														if($e->tP->PDType ==='L')
														{
															$listcats=Dropdowns::model()->findAll(array('condition'=>'Category=:lc','params'=>array(':lc'=>$e->tP->LCategory)));
														}
														
											if(!empty($cr))
											{	
										//--for formula
												$formula="";
												$formdts=[];
												$informdts=0;
												$informname="";
										$obsvalues=[];
											$p=Testobsparams::model()->findByPk($e->TPID);
										
										if($p->FormVal)
												{
													if(count($p->formulations)>0)
													{
														$formula=$p->formulations[0]->Formula;
														$formdts=$p->formulations[0]->formuladts;
														
														$formula=str_replace("V","R0V",$formula);
														
													}
												}
												
												if(count($p->formuladts)>0)
										{
											$informdts=1;
											$informname=$p->formuladts[0]->Variable;
										}
											
											
												
												
												
												if(empty($e->rirtestobsvalues))
										{
											
											
											
											for($i=0;$i<$p->MR;$i++)
												{
													$obsvalues[]=(object)array('Formula'=>$formula,'Value'=>"");
												}
										}
										else
										{
											
											if($p->FormVal)
												{
													if(count($p->formulations)>0)
													{
														$formula=$p->formulations[0]->Formula;
														$formdts=$p->formulations[0]->formuladts;
														
														
														
													}
												}
												
												if(count($p->formuladts)>0)
												{
													$informdts=1;
													$informname=$p->formuladts[0]->Variable;
												}
										
										
											
											$ity=0;
											foreach($e->rirtestobsvalues as $rob )
											{
												if($p->FormVal)
												{
													if(count($p->formulations)>0)
													{
														$formula=$p->formulations[0]->Formula;
														$formdts=$p->formulations[0]->formuladts;
														
														
														
													}
												}
												
												$formula=str_replace("V","R".$ity."V",$formula);
												
												$obsvalues[]=(object)array('Id'=>$rob->Id,'RTOBID'=>$rob->RTOBID,
												'Value'=>$rob->Value,'Formula'=>$formula);
												$ity++;
											}
											if(count($obsvalues)<$p->MR)
											{
												for($i=1;$i<=($p->MR-count($obsvalues));$i++)
												{
														$obsvalues[]=(object)array('Formula'=>$formula,'Value'=>"");
												}
										
											}
											
										}
										
										
										
												
												$sds[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,
												'ISNABL'=>$e->tP->ISNABL,'MR'=>$p->MR,
												'informdts'=>$informdts,'informname'=>$informname,
												'FormVal'=>$p->FormVal,'Formula'=>$formula,'FormDts'=>$formdts,
												'Permissible'=>$cr->PermMin,'PUnit'=>$e->tP->PUnit,'PSymbol'=>$e->tP->PSymbol,
												'IsSpec'=>$e->tP->IsSpec,'LCats'=>$listcats,
												'Param'=>$cr->p->Parameter,'SpecMin'=>$e->SpecMin,'SpecMax'=>$e->SpecMax,
												'PDType'=>$e->tP->PDType,'Values'=>$obsvalues,'PCatId'=>$e->tP->PCatId,'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
												'RTID'=>$e->RTID,'Case'=>"Default");
											}
											else
											{
												
												
												$p=Testobsparams::model()->findByPk($e->TPID);
												
												//--for formula
												$formula="";
												$formdts=[];
												$informdts=0;
												$informname="";
												$obsvalues=[];
												
										
										
										
										if(empty($e->rirtestobsvalues))
										{
											
											if($p->FormVal)
												{
													if(count($p->formulations)>0)
													{
														$formula=$p->formulations[0]->Formula;
														$formdts=$p->formulations[0]->formuladts;
														
														$formula=str_replace("V","R0V",$formula);
														
													}
												}
												
												if(count($p->formuladts)>0)
												{
													$informdts=1;
													$informname=$p->formuladts[0]->Variable;
												}
												
											for($i=0;$i<$p->MR;$i++)
												{
										
											$obsvalues[]=(object)array('Formula'=>$formula,'Value'=>"");
										}
											
										}
										else
										{
											
											if($p->FormVal)
												{
													if(count($p->formulations)>0)
													{
														$formula=$p->formulations[0]->Formula;
														$formdts=$p->formulations[0]->formuladts;
														
														
														
													}
												}
												
												if(count($p->formuladts)>0)
												{
													$informdts=1;
													$informname=$p->formuladts[0]->Variable;
												}
										
										
											
											$ity=0;
											foreach($e->rirtestobsvalues as $rob )
											{
												if($p->FormVal)
												{
													if(count($p->formulations)>0)
													{
														$formula=$p->formulations[0]->Formula;
														$formdts=$p->formulations[0]->formuladts;
														
														
														
													}
												}
												
												$formula=str_replace("V","R".$ity."V",$formula);
												$obsvalues[]=(object)array('Id'=>$rob->Id,'RTOBID'=>$rob->RTOBID,
												'Value'=>$rob->Value,'Formula'=>$formula);
												$ity++;
											}
											
											
											if(count($e->rirtestobsvalues) <(int)$p->MR)
											{
												$lim=(int)$p->MR - count($e->rirtestobsvalues);
												for($i=0;$i<$lim;$i++)
												{
										
											$obsvalues[]=(object)array('Formula'=>$formula,'Value'=>"");
												}
											}
										}
										
										
										
												$sds[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'ISNABL'=>$e->tP->ISNABL,
												'Permissible'=>"",'PUnit'=>$e->tP->PUnit,'PSymbol'=>$e->tP->PSymbol,
											'IsSpec'=>$e->tP->IsSpec,
												'informdts'=>$informdts,'informname'=>$informname,
												'FormVal'=>$p->FormVal,'Formula'=>$formula,'FormDts'=>$formdts,'MR'=>$p->MR,
												'Param'=>$p->Parameter,'SpecMin'=>"",'SpecMax'=>"",'LCats'=>$listcats,
												'PDType'=>$e->tP->PDType,'Values'=>$obsvalues,'PCatId'=>$e->tP->PCatId,'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
												'RTID'=>$e->RTID,'Case'=>"Default");
											}
									
										}	
								
									}
								else
								{
									$alles=Testobsparams::model()->findAll(array('condition'=>'TestId=:testid','params'=>[':testid'=>$d->TestId]));
										foreach($alles as $e)
									{
										
										
										
											$listcats=[];
														if($e->tP->PDType ==='L')
														{
															$listcats=Dropdowns::model()->findAll(array('condition'=>'Category=:lc','params'=>array(':lc'=>$e->tP->LCategory)));
														}
														
														
										$obsvalues=[];
										$obsvalues[]=(object)array('Value'=>"");
										$sds[]=(object)array('TPID'=>$e->Id,'ISNABL'=>$e->ISNABL,
												'Permissible'=>"",'PUnit'=>$e->PUnit,'PSymbol'=>$e->PSymbol,											
												'MR'=>$e->MR,'IsSpec'=>$e->tP->IsSpec,
												'Param'=>$e->Parameter,'SpecMin'=>"",'SpecMax'=>"",
												'PDType'=>$e->PDType,'Values'=>$obsvalues,'PCatId'=>$e->PCatId,
												'CatName'=>empty($e->pCat)?"":$e->pCat->CatName,'LCats'=>$listcats,
												'Case'=>"Defaultrt");
										
									}
								}
									$extra=(object)array();
								}
								catch(Exception $e)
								{
									$this->_sendResponse(401, CJSON::encode($e->getMessage()));
								}
								break;
								
							}							
							
							$u=MyFunctions::gettokenuser();
					$labset=Settingslab::model()->find(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
								$tobsfeature=$d->test->testfeatures[0];
							$rir=$d->rIR;
							$tn=explode("-",$d->TestName);
							$delfiles=[];
							
								$basic=(object)array('Id'=>$d->Id,'SampleName'=>$d->rIR->SampleName,'Note'=>$d->Note,'TNote'=>$d->TNote,
								'DefaultNote'=>empty($d->test->DefaultNote)?$labset->DefaultTestNote:$d->test->DefaultNote,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,
								'TestName'=>$d->TestName,'TestId'=>$d->TestId,'TNo'=>$tn[1],
								'Industry'=>implode(" - ",array_reverse( MyFunctions::getParentCat($d->rIR->IndId))),
								'CustomerId'=>$d->rIR->CustomerId,'Customer'=>empty($d->rIR->CustomerId)?null:$d->rIR->customer->Name,
								'TestType'=>$d->test->TType,'ObsVertical'=>$tobsfeature->ObsVertical,
								'IsImg'=>$d->test->IsImg,'ImgCount'=>$d->test->ImgCount,'IsParamTM'=>$tobsfeature->IsParamTM,
								'IsStd'=>$tobsfeature->IsStd,	'IsTestMethod'=>$tobsfeature->IsTestMethod,				
								'TestDate'=>$d->TestDate,'IsMdsTds'=>$rir->IsMdsTds,'MdsTds'=>empty($rir->mdsTds)?null:$rir->mdsTds->No,
								'Standard'=>$std,'StdId'=>empty($sub)?null:$sub->StdId,'SubStdId'=>empty($sub)?null:$sub->Id,
								'TopBasicParameters'=>$topbasicparams,'BottomBasicParameters'=>$bottombasicparams,
								'Parameters'=>$sds,'TestMethod'=>empty($d->TMID)?null:$d->tM->Method,'extra'=>$extra,
								'TMID'=>$d->TMID,'allformulas'=>$allformulas,'fileuploads'=>empty($d->rirtestuploads)?[]:$d->rirtestuploads,'delfiles'=>$delfiles,
								'SupplierId'=>$d->rIR->SupplierId,'Supplier'=>empty($d->rIR->SupplierId)?null:$d->rIR->supplier->Name,
								'ReqDate'=>$d->ReqDate,'HeatNo'=>$rir->HeatNo,'extra'=>empty($rir->rirextrases)?null:$rir->rirextrases[0],
								'BatchCode'=>$d->rIR->BatchCode,'BatchNo'=>$rir->BatchNo,'HTBatchNo'=>$rir->HTBatchNo,'ReceiptOn'=>$d->CreationDate,'Remark'=>$d->Remark,);
								
						
						$testmethods=Testmethods::model()->findAll(['condition'=>'TestId=:testid',
						'params'=>array(':testid'=>$d->test->Id)]);
						
							$aps=Appsections::model()->find(array('condition'=>'Others =:oth','params'=>[':oth'=>$d->TestId]));	
$allremarks=[];
						$allremarks[]=(object)['Id'=>'Passed','Text'=>'Satisfactory'];
							$allremarks[]=(object)['Id'=>'Failed','Text'=>'Unsatisfactory'];
								
					$model=(object)array('tuid'=>$testid,'basic'=>$basic,'allremarks'=>$allremarks,'testmethods'=>$testmethods,'tid'=>$d->TestId,'sid'=>$aps->Id);		
							
							}
							catch(Exception $e)
							{
								$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
							}
						break;

										
						
				
				case 'riredit':
				
				$u=MyFunctions::gettokenuser();
					$customers=Customerinfo::model()->findAll(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					$suppliers=Suppliers::model()->findAll(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					
					$set=Settingslab::model()->find(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					
					$batchcodes=array();
					
					for ($i = 'AAA'; $i <= $set->BatchCodeStart; $i++){	
						
								$batchcodes[]=$i;
						}
						
						 					 
						 

						$allrirs=Receiptir::model()->findAll(['select'=>'HeatNo,BatchCode,BatchNo','condition'=>'IsMdsTds="mds" AND CID=:cid','params'=>[':cid'=>$u->CID]]);
					foreach($allrirs as $r)
					{
						
						$batchcodes[]=$r->BatchCode;
						if(ctype_alpha($r->BatchNo))
						{
							$batchcodes[]=$r->BatchNo;
						}
						
						$heatranges[]=(object)array('HeatNo'=>$r->HeatNo,'BatchCode'=>$r->BatchCode);
						
						
					}
					
					 $array = array_map('json_encode', $heatranges);
							$array = array_unique($array);
						$array = array_map('json_decode',array_values( $array));
					$heatranges=$array;
					
					$industries=Industry::model()->findAll(array('condition'=>'ParentId is null AND CID=:cid','params'=>[':cid'=>$u->CID]));
				$allindustries=[];
				foreach($industries as $c)
							{
								$parents = array();
									$allindustries[]=(object)array('Id'=>$c->Id,'Name'=>$c->Name,
									'Parent'=>isset($c->ParentId)?$c->parent->Name:"",
									'ParentId'=>$c->ParentId,'PTree'=>implode(" > ",array_reverse(MyFunctions::getParentCat($c->Id))),'TreeLength'=>count(MyFunctions::getParentCat($c->Id)),
									'Children'=>MyFunctions::parseTree($c->Id));
									
							}
					
					$lusers=Users::model()->findAll(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					$labusers=[];
					foreach($lusers as $l)
					{
						$labusers[]=(object)array('Id'=>$l->Id,'Name'=>$l->FName." ".$l->LName);
					}
					
					$tests=Tests::model()->findAll(array('condition'=>'Status="1" AND CID=:cid',
					'params'=>array(':cid'=>$u->CID)));
					$alltests=[];
					$colors=[];
				foreach($tests as $t)
				{$g=1;
					
						$tof=$t->testfeatures[0];
						$alltests[]=(object)array('TSeq'=>$t->IndId.$t->TUID.'-'.$g,
						'IndId'=>$t->IndId,'TestId'=>$t->Id,'Cost'=>$t->Cost,'TestName'=>$t->TestName.'-'.$g,'TUID'=>$t->TUID,
						'IsStd'=>$tof->IsStd,'IsTestMethod'=>$tof->IsTestMethod,'IsPlan'=>$tof->IsPlan);
						
						$colors[]=$t->IndId.$t->TUID.'-'.$g;
						
					
					
				}
					

					
					
					
						$r = Receiptir::model()->findByPk($_GET['id']);
				
			
					
					$rirtest=[];
					
				
				
				
					
						
					
					$addtest=[];
					
				foreach($r->rirtestdetails as $rt)
						{
							$ntest[]=$rt->TestId;
							$addtest[]=$rt->TSeq;
							$testmethod=Testmethods::model()->findByPk($rt->TMID);
							$labs=[];
							
							$test=$rt->test;
							$tf=$test->testfeatures[0];
							// if()
							// foreach($rt->rirtestlabs as $l)
							// {
								// $labs[]=$l->LabId;
							// }
							//$found_key = array_search($rt->TSeq, $colors);
							if(!in_array($rt->TSeq, $colors))
							{
								$t=Tests::model()->findByPk($rt->TestId);
								$tof=$t->testfeatures[0];
														$alltests[]=(object)array('TSeq'=>$rt->TSeq,
						'IndId'=>$t->IndId,'TestId'=>$rt->TestId,'Cost'=>$t->Cost,'TestName'=>$rt->TestName, 'TUID'=>$rt->TUID,
						'IsStd'=>$tof->IsStd,'IsTestMethod'=>$tof->IsTestMethod,'IsPlan'=>$tof->IsPlan);

							}
							
							
							$rirtest[]=(object)array('RTID'=>$rt->Id,'TestName'=>$rt->TestName,'TestId'=>$rt->TestId,
							'TMID'=>$rt->TMID,'ExtraInfo'=>$rt->ExtraInfo,'Labs'=>$labs,'DelLabs'=>[],'PlanId'=>$rt->PlanId,
							'SSID'=>$rt->SSID,'TUID'=>$rt->TUID,'TSeq'=>$rt->TSeq,'IsStd'=>$tf->IsStd,'IsTestMethod'=>$tf->IsTestMethod,
							'ReqDate'=>$rt->ReqDate,'ExtraInfo'=>$rt->ExtraInfo);
						}
						$rexttests=Rirexttestdetail::model()->findAll(array('condition'=>'RIRId=:rir ',
										 'params'=>array(':rir'=>$r->Id),));
					$exttests=array();					 
					foreach($rexttests as $rt)
					{
						$exttests[]=Externaltests::model()->findByPk($rt['ExtTestId']);
					}
				
				
			
				
				$parent=1;//getParentIds($r->IndId);
				
				$rirextra=empty($r->rirextrases)?null:$r->rirextrases[0];
				$rir=(object)array('Id'=>$r->Id,'IndId'=>$r->IndId,'PIndId'=>$r->IndId,'rirtests'=>$rirtest,
				'CustomerId'=>$r->CustomerId,'addtest'=>$addtest,'HeatNo'=>$r->HeatNo,
				'IsMdsTds'=>$r->IsMdsTds,'MdsTdsId'=>$r->MdsTdsId,'BatchNo'=>$r->BatchNo,'applicabletest'=>[],'deltests'=>[],
				'IndId'=>$r->IndId,'SupplierId'=>$r->SupplierId,
						'SampleName'=>$r->SampleName,'extras'=>$rirextra,
						'ExtTest'=>$exttests,
					'RefPurchaseOrder'=>$r->RefPurchaseOrder,'BatchCode'=>$r->BatchCode,
					'LabNo'=>$r->LabNo);
						
				
					
				
					
				$predata=(object)array('customers'=>$customers,'suppliers'=>$suppliers,'labusers'=>$labusers,'batchcodes'=>$batchcodes,
					'industries'=>$allindustries,'testconditions'=>[],'heatranges'=>$heatranges,'alltests'=>$alltests);
					
						
						
			$model=(object)array('rir'=>$rir,'predata'=>$predata);
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
				case 'runapprovetest':
				case 'getmdstds':$model=new Receiptir;break;
				case 'logout':
				case 'forcelogin':
				case 'loginuser': $model =new LoginForm;break;
				// Get an instance of the respective model
				case 'forgotpwd': $model=new  Users;break;
			
				
				case 'carbsystemupdate':$model=new Receiptir;break;
				
				case 'casesystemupdate':$model=new Receiptir;break;
				case 'mvhardsystemupdate':
				case 'vhardsystemupdate':$model=new Receiptir;break;
				case 'chemsystemupdate':$model=new Receiptir;break;
					case 'utesystemupdate':$model=new Receiptir;break;
					
				case 'hardsystemupdate':$model=new Receiptir;break;
				
				case 'pdiradd': $model=new Pdirbasic;break;
				
				case 'riradd':		$model=new Receiptir;break;
				case 'searchrir':$model =new Receiptir;break;
				case 'searchtestob':$model =new Receiptir;break;
				
				  
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
			   case 'runapprovetest':
			   case 'getmdstds':
			   case 'logout':
			   case 'forcelogin':break;
				case 'loginuser':break;
			    case 'utesystemupdate':break;
				 case 'carbsystemupdate':break;
				case 'casesystemupdate':break;
			    case 'chemsystemupdate':break;
			   case 'vhardsystemupdate':break;
			    case 'mvhardsystemupdate':break;
			     case 'searchrir':break;
				 case 'searchtestob':break;
				 
				
				case 'forgotpwd':break;
			   
			   case 'pdiradd':
						foreach($data['basic'] as $var=>$value)
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
			   
			   
			   case 'runapprovetest':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						
						$rirtests=Rirtestdetail::model()->with('rIR')->findAll(['condition'=>'rIR.LabNo>"E0000" AND t.Status="complete" AND ULRNo IS NULL','limit'=>1]);
						
						$chresult=[];
						
						foreach($rirtests as $t)
						{
							try{
								if(is_null($t->ULRNo))
								{
									
									
									$currentYear = date('Y'); // Get the current year
									$labset=Settingslab::model()->find();
								
										$yr=date("y");
										$loc="0";
										$ulrno=$labset->LastULRNo;
										$certno=substr($ulrno, 0 , 6);
										$gy=substr($ulrno, 6 , 2);
										
										if($yr===$gy)
										{
											//---increment
											$getno=substr($ulrno, 9 , 8);
											$input=(int)$getno+1;//$model->Id;
											$str2=str_pad($input, 8, "0", STR_PAD_LEFT);
										}
										else
										{
											$input='01';//$model->Id;
											$str2=str_pad($input, 8, "0", STR_PAD_LEFT);
										}
										
										$str3="F";
										$t->ULRNo=$certno.$yr.$loc.$str2.$str3;
										$t->save(false);
										
							
										$labset->LastULRNo=$t->ULRNo;
										$labset->save(false);
										
									$t->ApprovedBy=20;
									$t->ApprovedDate=date('Y-m-d');
									$t->save(false);
								
								
									$d=$t;
									$rtds=MyTestdata::gettestdata($d);

									$fileulr=Yii::app()->basePath."/../../pdf/testreport/".$rtds->LabNo;
									if (!file_exists($fileulr)) {
										mkdir($fileulr, 0777, true);
									}
									//$this->_sendResponse(401, CJSON::encode($rtds));
									$url=Yii::app()->params['base-url']."pdf/testreport/".$rtds->LabNo."/".$rtds->TestNo."-".$rtds->TNo.".pdf";
									//$qrimg=Yii::app()->params['base-url']."pdf/testreport/".$rtds->TestNo."-".$rtds->TNo.".png";

							//Yii::app()->qrcode->create($url,Yii::app()->basePath."/../../pdf/testreport/".$rtds->LabNo."/".$rtds->TestNo."-".$rtds->TNo.".png");

						
											
		require_once(yii::app()->basePath . '/extensions/tcpdf/tcpdf.php');
		  $pdf = new 
		   MYPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT, true,'UTF-8',false);
		   $pdf->SetMargins(15, 10, 10, true);
			$pdf->SetPrintHeader(false);
		  $pdf->SetPrintFooter(false);
		  $pdf->setHeaderData('',0,'','',array(0,0,0), array(255,255,255) );  
		  $pdf->SetFont( 'times', '', 10);
		  $pdf->AddPage();
		  
		  $pdf->setListIndentWidth(2);
		  $msg=MyFunctions::gettestui($rtds,$pdf);
		 
		 
		 
		  $pdf->writeHTML($msg,true, false, true, false, '');
						

		  
		 // $pdf->IncludeJS($js);
		  $pdf->Output ( yii::app()->basePath."/../../pdf/testreport/".$rtds->LabNo."/".$rtds->TestNo."-".$rtds->TNo.".pdf", 'F' );
					
					
								$chresult[]=(object)['LabNo'=>$rtds->LabNo,'TestNo'=>$rtds->TestNo,'BatchCode'=>$rtds->BatchCode,'BatchNo'=>$rtds->BatchNo,'TestName'=>$rtds->TestName];
								}
							
							}
							catch(Exception $e)
							{
								$this->_sendResponse(401, CJSON::encode($rtds->TestNo));;
							}

					}
						
						
					
						$msg=(object)['rirtests'=>count($rirtests),'result'=>$chresult];
							
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($msg));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
			   
							break;	
							
							
							
			   case 'getmdstds':
			   try{
				   
				   $u=MyFunctions::gettokenuser();
				   $allmdstds=[];
				   $mdstds=Mdstds::model()->findAll(['condition'=>'Type=:type AND CID=:cid',
				   'params'=>[':type'=>$data['notype'],':cid'=>$u->CID],'order'=>'Id Desc']);
				   
				   
				   
				   foreach ($mdstds as $md) {
    $mdstdstests = [];
	$tuidCounts = [];
    
    foreach ($md->mdstdstests as $mt) {
        $tbaseparams = [];
        $tobparams = [];
		
		foreach ($mt->mdstdstestbasedetails as $d) {
            $tbaseparams[] = (object)[
                'Id' => $d->Id,'MTTID' => $d->MTTID,
                'PID' => $d->PID,'Parameter' => $d->p->Parameter,
                'Value' => $d->Value,'PUnit'=>$d->p->PUnit
            ];
        }
        
        foreach ($mt->mdstdstestobsdetails as $d) {
            $tobparams[] = (object)[
                'Id' => $d->Id,'MTTID' => $d->MTTID,
                'PID' => $d->PID,'Parameter' => $d->p->Parameter,
                'SpecMin' => $d->SpecMin,'SpecMax' => $d->SpecMax,
            ];
        }
        
         // Initialize the sequence number based on TUID
        $tuid = $mt->t->TUID;
        if (!isset($tuidCounts[$tuid])) {
            $tuidCounts[$tuid] = 0; // Start count at 0 for new TUID
        }
        $tuidCounts[$tuid]++; // Increment count for this TUID

        $testDetails = (object)[
            'TSeq' =>  $mt->t->IndId . $tuid . '-' . $tuidCounts[$tuid],
            'IndId' => $mt->t->IndId,
            'TestId' => $mt->t->Id,
            'Cost' => $mt->t->Cost,
            'TestName' => $mt->t->TestName . '-' . $tuidCounts[$tuid],
            'TUID' => $tuid,
            'IsStd' => $mt->t->testfeatures[0]->IsStd,
            'IsTestMethod' => $mt->t->testfeatures[0]->IsTestMethod,
            'Frequency' => $mt->Freq,
            'SSID' => $mt->SSDID,
            'TMID' => $mt->TMID,
            'tobsparams' => $tobparams,
			'tbaseparams'=>$tbaseparams
        ];
        
        $mdstdstests[] = $testDetails;
    }

    $allmdstds[] = (object)[
        'Id' => $md->Id,
        'Type' => $md->Type,
        'No' => $md->No,
        'Description' => $md->Description,
        'allmdstdstests' => $mdstdstests,
    ];
}


				    $this->_sendResponse(200, CJSON::encode($allmdstds));
			   
			   }
			   catch(Exception $e)
			   {
				    $this->_sendResponse(401, CJSON::encode($e->getMessage()));
			   }
			   break;
			   
			     case 'logout':
				 try{
					 
					 
					 $jwt = Yii::app()->JWT->decode($data['token']);
					 
					 $u = Users::model()->find(array('condition'=>'Id=:uid AND token=:token',
						'params'=>array(':uid'=>$jwt->UID,':token'=>$data['token'])));
						
						if(!empty($u))
						{
							$u->token=null;
							$u->save(false);
							
							$this->_sendResponse(200, CJSON::encode("Logout"));	
						}
						else
						{
							 $this->_sendResponse(401, CJSON::encode("user not found"));
						}
					 
				 }
			   catch(Exception $e)
			   {
				   $this->_sendResponse(401, CJSON::encode($e->getMessage()));
			   }
			   break;
			    case 'forcelogin':
				 try{
					 
						$model->attributes = $data;
							$model->RememberMe=true;
			
						// validate user input and redirect to the previous page if valid
							if($model->validate() && $model->login())
							{
						
								$u = Users::model()->findbyPk(Yii::app()->user->getId());
								if(!empty($u))
								{
																
										//---Login with new token
										
										$token = array(
										 "Name" => $u->Username,
											"timestamp" => date('Y-m-d H:i:s'),
										 "UID" => $u->Id,
										 "Role"=>$u->userinroles[0]->RoleId,
										 'Location'=>1,
										  'CID'=>$u->CID,
										);
								
										$jwt = Yii::app()->JWT->encode($token);

										$u->token=$jwt;
										$u->save(false);
										
										$user=(object)array('uid'=>$u->Id,'username'=>$u->Username,'roleid'=>$u->userinroles[0]->RoleId,
								'name'=>$u->FName." ".$u->MName." ".$u->LName,'myworld'=>$u->token,'Location'=>1, 'CID'=>$u->CID,
								'email'=>$u->Email);	
							
										$this->_sendResponse(200, CJSON::encode($user));	
									
								}		
							
						}
					else
						{						
							$this->_sendResponse(401, CJSON::encode("Invalid Credentials"));				
						}
						 }
			   catch(Exception $e)
			   {
				   $this->_sendResponse(401, CJSON::encode($e->getMessage()));
			   }
						break;
						
				case 'loginuser':
try{
					$model->attributes = $data;
					$model->RememberMe=true;
	
				// validate user input and redirect to the previous page if valid
					if($model->validate() && $model->login())
					{
				
						$u = Users::model()->findbyPk(Yii::app()->user->getId());
						if(!empty($u))
						{
							if(!empty($u->token))
							{
								//---already logged in on another device
								$jwt = Yii::app()->JWT->decode($u->token);

								$result=(object)array('number'=>204, 'msg'=>"Already Logged In");
								
								 $this->_sendResponse(200, CJSON::encode($result));
								 
								
							}
							else
							{								
								//---Login with new token
								
										$token = array(
									 "Name" => $u->Username,
										"timestamp" => date('Y-m-d H:i:s'),
									 "UID" => $u->Id,
									 "Role"=>$u->userinroles[0]->RoleId,
									 'Location'=>1,
									 'CID'=>$u->CID,
									);
								
								
						
								$jwt = Yii::app()->JWT->encode($token);

								$u->token=$jwt;
								$u->save(false);
								
								$user=(object)array('uid'=>$u->Id,'username'=>$u->Username,'roleid'=>$u->userinroles[0]->RoleId,
						'name'=>$u->FName." ".$u->MName." ".$u->LName,'myworld'=>$u->token,'Location'=>1,
						'role'=>"",'email'=>$u->Email);	
					
								$this->_sendResponse(200, CJSON::encode($user));	
							}
						}
					
					
					
				}
			else
				{
					
						
					$this->_sendResponse(401, CJSON::encode("Invalid Credentials"));
				
				}
	
				
				 }
			   catch(Exception $e)
			   {
				   $this->_sendResponse(401, CJSON::encode($e->getMessage()));
			   }
				
				break;
			   
			case 'forgotpwd':
			$transaction=$model->dbConnection->beginTransaction();
			try{
					 
				$getpassword = Users::model()->find(array('condition'=>'Email=:uid AND Status="1" ',
										 'params'=>array(':uid'=>$data['Email']),));
				
							 if (empty($getpassword))	
							{
								 $this->_sendResponse(401, CJSON::encode('Error: Invalid Email'));
							}
							else
							{
				   				
								$newpwd="";
							
								
									$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
									$pass = array(); //remember to declare $pass as an array
									$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
									for ($i = 0; $i < 6; $i++) {
										$n = rand(0, $alphaLength);
										$pass[] = $alphabet[$n];
									}
									
									$numeric = '1234567890';
									 //remember to declare $pass as an array
									$numLength = strlen($numeric) - 1; //put the length -1 in cache
									for ($i = 0; $i < 2; $i++) {
										$n = rand(0, $numLength);
										$pass[] = $numeric[$n];
									}
									 
									$newpwd=implode($pass); //turn the array into a string
									//$newpwd="12345678q";
									// A higher "cost" is more secure but consumes more processing power
									$cost = 10;

									// Create a random salt
									$salt = strtr(base64_encode(random_bytes(16)), '+', '.');

									// Prefix information about the hash so PHP knows how to verify it later.
									// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
									$salt =sprintf("$2a$%02d$", $cost) . $salt;

									// Value:
									// $2a$10$eImiTXuWVxfM37uY4JANjQ==

									// Hash the password with the salt
									$hash = crypt($newpwd, $salt);

									// Value:
									// $2a$10$eImiTXuWVxfM37uY4JANjOL.oTxqp7WylW7FCzx2Lc7VLmdJIddZq
									//$getpassword->Password=crypt($newpwd,"sachin");
                              	 
						
                              
									$getpassword->Password=$hash;
									$getpassword->save();

							
									
								
//template forgot password
$msg ='<div class="row" style="width:98%;min-width:250px;background-color:#F0F0F0;border:0px solid black;">
<div style="width:100%;text-align:center;">
	<h1 style="color:black;">Forgot Your Password?</h1>
		<h4>No need to worry, <span style="font-size:20px">('.$getpassword->Email.' </span>!). Following is your password. Use this password !</h4>
		<h4>Password: <span style="color:#FFA500;">'.$newpwd.'</span></h4><br>
		
	<h5>Please keep your account details in safe place.</h5>
   </div>';
$msg .='</div>';

$adminmail=Settingsmail::model()->find(array('condition'=>'Type="Admin"'));

$mail = new YiiMailer;
$mail->IsSMTP();
$mail->setSmtp($adminmail->Server,$adminmail->Port, $adminmail->Encrypt,true,$adminmail->Email ,$adminmail->Password);
   $mail->setFrom($adminmail->Email,Yii::app()->params['appname']);
$mail->setTo($getpassword->Email);
    $mail->setSubject(Yii::app()->params['appname'].': Password Request');
$mail->setBody($msg);
                         //     $mail->SMTPDebug =2;
if($mail->send())
{
		$transaction->commit();
 $this->_sendResponse(200, CJSON::encode("mail sent"));
}
else
{
  $transaction->rollback();
   $this->_sendResponse(401, CJSON::encode( CJSON::encode(array($mail->getError()))));
}
						
                      
                          
							}	
			}
			catch(Exception $e)
			{
				
   $this->_sendResponse(401, CJSON::encode($e->getMessage()));
			}
			   break;
			   case 'searchexternal':
				
				
						
						$totalitems=0;				 
										 
						
							$rtds=Rirexttestdetail::model()->with('rIR')->findAll(array(
												'order' => 't.Id desc',
								'limit' => '75',//$data['pageSize'],
							 	'condition'=>'rIR.PartName LIKE :pn OR rIR.LabNo LIKE :ln OR rIR.HeatNo LIKE :hn OR rIR.BatchCode LIKE :bc OR rIR.BatchNo LIKE :bn OR rIR.MdsNo LIKE :mdsno OR rIR.TdsNo LIKE :tdsno OR rIR.NoType LIKE :noty',
								'params'=>array(':pn'=>'%'.$data['text'].'%',':ln'=>'%'.$data['text'].'%',':hn'=>'%'.$data['text'].'%',':bc'=>'%'.$data['text'].'%',':bn'=>'%'.$data['text'].'%',
								':mdsno'=>'%'.$data['text'].'%',':tdsno'=>'%'.$data['text'].'%',':noty'=>'%'.$data['text'].'%'),));
		
			 
		
							// $rtds=Rirexttestdetail::model()->findAll();
								// $totalitems=count($rtds);				 
										 
						// if(isset($_GET['pl']))
						// {
							// $rtds=Rirexttestdetail::model()->findAll(array(
							  // 'order' => 'Id desc',
								// 'limit' => $_GET['pl'],
								// 'offset' => ($_GET['pn']-1)*$_GET['pl'],
								// ));
										 
						// }		

							
							$rirs=array();
							foreach($rtds as $r)
							{
								$rirs[]=$r->RIRId;
							}
							
							$rirs = array_values(array_unique($rirs));
							$allrirs=array();
							foreach($rirs as $rt)
							{			 
							
							$d=Receiptir::model()->findByPk($rt);
								if(!empty($d))
								{
								
								
								$extests=array();
									 $extests=Rirexttestdetail::model()->findAll(array('condition'=>'RIRId=:Id',
											 'params'=>array(':Id'=>$rt),));
									
									$testnames=array();
									foreach($extests as $ex)
									{
										$eu=Exttestuploads::model()->find(array('condition'=>'rirexttestid=:exid',
											'params'=>array(':exid'=>$ex->Id)));
											$exurl="";
											if(!empty($eu))
											{
												$exurl=$eu->url;
											}
											
										$testnames[]=(object)array('TestName'=>$ex->TestName,'exurl'=>$exurl);			
									}
									
									
								//	$testname=$extests;			
													
										$allrirs[]=(object)array('Id'=>$d->Id,'RirNo'=>$d->RirNo,'PartName'=>$d->PartName,
									'LabNo'=>$d->LabNo,'RefPurchaseOrder'=>$d->RefPurchaseOrder,
									'TCNo'=>$d->TCNo,'InvoiceNo'=>$d->InvoiceNo,'InvoiceDate'=>$d->InvoiceDate,'Customer'=>$d->Customer,'RouteCardNo'=>$d->RouteCardNo,							
									'Supplier'=>$d->Supplier,'GrinNo'=>$d->GrinNo,'Quantity'=>$d->Quantity,
									'HeatNo'=>$d->HeatNo,'BatchNo'=>$d->BatchNo,'NoType'=>$d->NoType,'TdsNo'=>$d->TdsNo,
									  'TestNames'=>$testnames,'Status'=>"pending",
									'BatchCode'=>$d->BatchCode,	'MaterialCondition'=>$d->MaterialCondition,'MaterialGrade'=>$d->MaterialGrade);
								}
							}
							$data=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($data));	
							break;			 
			   
			   
			    case 'searchtestob':
					
			   	try{
					
					$aps=Appsections::model()->find(array('condition'=>'Others =:oth','params'=>[':oth'=>$data['TestId']]));	
					//$test=$data['TestId'];
					$this->_checkAuth($aps->Id,'R');
					$u=MyFunctions::gettokenuser();
						$rtds=Rirtestdetail::model()->with(
							array(
									'rIR'=>
										array(
										'alias'=>'rir',
										//'with'=>array(
										// 'customer'=>array(
											// 'alias'=>'cust',
											// 'condition'=>'cust.Name LIKE :cust',
											// 'params'=>array(':cust'=>'%'.$data['text'].'%'),
											// 'together' => true,
												// )
											//)
										),
						))->findAll(array('condition'=>'(TestId=:testid ) AND ( rir.SampleName LIKE :pn OR 
						rir.LabNo LIKE :ln OR rir.BatchCode LIKE :bc  OR CONCAT(LOWER(rir.BatchCode), "-", LOWER(rir.BatchNo)) LIKE :bc)',
							'params'=>array(':testid'=>$data['TestId'] ,':pn'=>'%'.$data['text'].'%',
							':ln'=>'%'.$data['text'].'%',':bc'=>'%'.$data['text'].'%'),
							  'order' => 't.Id desc',
								'limit' => 30,
								'together' => true,
								));
										 
						$totalitems=count($rtds);

							$allrirs=array();
							
							$test=Tests::model()->findbyPk($data['TestId']);
							
							$tuid=$test->TUID;
							
							foreach($rtds as $d)
											{
												
												$allrirs[]=MyTestdata::gettestdata($d);
											}
							
							
							 $tu=Tests::model()->findByPk($data['TestId']);
							
							$result=(object)array('TUID'=>$tu->TUID,'allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($result));	
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}				
							break;
				
			   
			    case 'searchrir':
			  
			  	$text=strtolower($data['text']);
				
			$models = Receiptir::model()->with([
    'customer', 
    'supplier', 
    'mdsTds', 
    'rirextrases' 
])->findAll([
    'condition' => 't.LabNo LIKE :ln 
                    OR LOWER(t.BatchCode) LIKE :bc 
                    OR CONCAT(LOWER(t.BatchCode), "-", LOWER(t.BatchNo)) LIKE :bc 
                    OR LOWER(t.SampleName) LIKE :sn 
                    OR LOWER(t.HeatNo) LIKE :hn 
                    OR LOWER(customer.Name) LIKE :sn 
                    OR LOWER(supplier.Name) LIKE :sn 
                    OR LOWER(t.RefPurchaseOrder) LIKE :sn 
                    OR LOWER(mdsTds.No) LIKE :sn
					OR rirextrases.InvoiceNo LIKE :re OR rirextrases.GrinNoDate LIKE :re OR rirextrases.Grade LIKE :re OR rirextrases.TCNo LIKE :re
',
    'params' => [
        ':ln' => '%' . $text . '%',
        ':bc' => '%' . $text . '%',
        ':sn' => '%' . $text . '%',
        ':hn' => '%' . $text . '%',
        ':re' => '%' . $text . '%',
    ],
    'order' => 't.Id DESC',
    'together' => true,  // Optimized fetching of related models
]);

			
				$totalitems=count($models);
				//	$models=array_reverse($models);
				$allrirs=array();
				
				foreach($models as $r)
					{
							
							
							$testdetails=array();
							foreach($r->rirtestdetails as $t)
							{
								$std="Not Applicable";
								
								if(!empty($t->SubStdId))
								{
									
										$sub=Substandards::model()->findByPk($t->SubStdId);
									
									$substandard="";
									
										$substandard=$sub->Grade." ".$sub->ExtraInfo;
									
									$std=$sub->std->Standard." ".$substandard;	
								}
								
								$method="";
								if(!empty($t->TestMethodId))
								{
									
										$tm=Testmethods::model()->findByPk($t->TestMethodId);
									$method=$tm->Method;
									
								}
								$today = date("Y-m-d");
								$expire = $t->ReqDate; //from db
								$overdue="false";
								$today_time = new DateTime($today);
								$expire_time = new DateTime($expire);

								if ($expire_time <$today_time) { $overdue="true"; }
								
								
								
								$labnames=[];
								if(!empty($t->rirtestlabs))
								{
									foreach($t->rirtestlabs as $l)
									{
										$lab=Labaccredit::model()->findByPk($l->LabId);
										$labnames[]=$lab->Name;
									}
								}
								
								
								
				
							$apsec=Appsections::model()->find(['condition'=>'Others=:oth','params'=>[':oth'=>$t->TestId]]);
								$testdetails[]=(object)array('TestName'=>$t->TestName,'Standard'=>$std,
								'ReqDate'=>$t->ReqDate,'labnames'=>$labnames,
								'TestId'=>$t->TestId,'TestMethod'=>$method,'TMID'=>$t->TMID,
								'SSID'=>$t->SSID,'ExtraInfo'=>$t->ExtraInfo,
								'Status'=>$t->Status,'Overdue'=>$overdue,'SID'=>$apsec->Id);
						
						}
						
							$time=strtotime($r->CreationDate);
							$rirgdt = date('d.m.y',$time);
							
							$rirextra=empty($r->rirextrases)?null:$r->rirextrases[0];
							
							$mdstds=Mdstds::model()->findByPk($r->MdsTdsId);
						
						$allrirs[]=(object)array('Id'=>$r->Id,'SampleName'=>$r->SampleName,'Description'=>$r->Description,
						'CustomerId'=>$r->CustomerId,'Customer'=>empty($r->customer)?null:$r->customer->Name,
						'CustEmail'=>empty($r->customer)?null:$r->customer->Email,'Supplier'=>empty($r->supplier)?null:$r->supplier->Name,
						'SampleCondition'=>"",'IndId'=>$r->IndId,'Industry'=>implode(" - ",array_reverse( MyFunctions::getParentCat($r->IndId))),
						'BatchCode'=>$r->BatchCode,'BatchNo'=>$r->BatchNo,'GeneratedDate'=>$rirgdt,'IsMdsTds'=>$r->IsMdsTds,'MdsTdsNo'=>empty($mdstds)?null:$mdstds->No,
						'RefPurchaseOrder'=>$r->RefPurchaseOrder,'HeatNo'=>$r->HeatNo,'rirextra'=>$rirextra,
						'LabNo'=>$r->LabNo,'alltests'=>$testdetails);
					
					
					}
				
				
				
						$result=(object)array('allrirs'=>$allrirs,'totalitems'=>$totalitems);
						$this->_sendResponse(200, CJSON::encode($result));
						
			  break;
			   
			
			 
			   
			   
			   case 'carbsystemupdate':
						try{
				$test=Tests::model()->find(['condition'=>'TUID="CARBDC"']);
				
				$file=$test->MachinePath;
				$bc=$data['BatchCode'];
				$ln=$data['LabNo'];
				$tn=$data['TNo'];			
				
				
		if (true) {
    // Uncomment if needed for PhpSpreadsheet
    // require_once(yii::app()->basePath . '/extensions/PhpSpreadsheet/IOFactory.php');

    $file1 = $file . $ln . "-CDC-" . $tn . ".xls";

    // Debug the file path
  //  echo "File Path: " . $file1 . "\n";

    if (!file_exists($file1)) {
        $this->_sendResponse(401, CJSON::encode("File does not exist: " . $file1));
    }

    try {
        // Load the spreadsheet using PhpSpreadsheet
        $spreadsheet = IOFactory::load($file1);

        // Get the active sheet (first sheet by default)
        $sheet = $spreadsheet->getActiveSheet();

        // Convert the sheet to a 2D array and fetch values
        $value[] = (object)[ 'Value' => $sheet->getCell('I16')->getValue()];
        $value[] = (object)[ 'Value' => $sheet->getCell('I17')->getValue()];
        $value[] = (object)[ 'Value' => $sheet->getCell('I18')->getValue()];

        // Output data (or process as needed)
        $this->_sendResponse(200, CJSON::encode($value));

    } catch (Exception $e) {
        // Log and send the error message
        error_log($e->getMessage());
        $this->_sendResponse(401, CJSON::encode($e->getMessage()));
    }
}
	
			}
			catch(Exception $e)
			{
				$this->_sendResponse(401, CJSON::encode($e->getMessage()));
			}
				break;
				
			   
			   
	 
				
				  
			   case 'utesystemupdate':
			   	try{
					
						$test=Tests::model()->find(['condition'=>'TUID="TENSILE"']);
				
				$file=$test->MachinePath;


				$bc=$data['BatchCode'];
				$ln=$data['LabNo'];
				$tn=$data['TNo'];
				//$file=$set->UTEPath;
				
				if(file_exists($file))
				{
					$file1=$file.$ln.'-'.$tn;
					if(file_exists($file1))
					{
					$fopen = fopen($file1, 'r');
					$fread = fread($fopen,filesize($file1));
					fclose($fopen);
										
				$content=$fread;//mb_convert_encoding($fread,'ASCII', 'UCS-2LE');

				$remove = "\n";
				$split = explode($remove,$content);
					$array = array();
					$tab = "\t";
				
				foreach ($split as $string)
				{
					$row = explode($tab, $string);
					$row=array_map('trim',$row);
					$array[]=$row;
				}
				
				//	$this->_sendResponse(401, CJSON::encode($array[11]));	
						$observations=array();
							if(!empty($array))
							{
								$firstrow=explode(",",$array[11][0]);
								
								$gd=CJSON::decode($firstrow[0]);
								$gl=CJSON::decode($firstrow[4]);
								$area=CJSON::decode($firstrow[6]);
								$sgl=CJSON::decode($firstrow[7]);
								
								$next=$array[13][0];
								$i=$next+14;
								
								$secondrow=explode(",",$array[$i][0]);
								$mf=CJSON::decode($secondrow[0]);
								$me=CJSON::decode($secondrow[2]);
								
								$thirdrow=explode(",",$array[$i+1][0]);
								$fgl=CJSON::decode($thirdrow[0]);
								$fd=CJSON::decode($thirdrow[1]);
								$farea=CJSON::decode($thirdrow[3]);
								$uts=round((($mf*1000)/$area),4);
								$el=round(((($fgl-$sgl)/$sgl)*100),4);
								//$farea=(M_PI/4)*$fd*$fd;
								$ra=round(((($area-$farea)/$area)*100),4);
								
								$observations=(object)array('SS'=>"Round",'FR'=>"WGL",'UTS'=>$uts,'EL'=>$el,'RA'=>$ra,'GD'=>$gd,
								'GL'=>$gl,'A'=>$area,'UL'=>$mf,'EX'=>$me,'FL'=>$fgl,'FD'=>$fd);
							
					
					
			
			
			//$result=(object)array('LimitMin'=>$min,'LimitMax'=>$max,'HV1'=>$hv1,'HV2'=>$hv2,'HV3'=>$hv3,);
						$data=(object)array('observations'=>$observations);
				
					$this->_sendResponse(200, CJSON::encode($data));
					}
					else
					{
						$this->_sendResponse(401, CJSON::encode("No test data of LabNo=".$ln));
					}
				}
				else
				{
					$this->_sendResponse(401, CJSON::encode("Incorrect file path" ));
				}
				}
				else
				{
					$this->_sendResponse(401, CJSON::encode("file not found" .$file));
				}
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
			   
			   
			   
		case 'chemsystemupdate':
		try{
		
				$bc="";//$data['basic']['BatchCode'];
				
				$test=Tests::model()->find(['condition'=>'TUID="CHEM"']);
				
				$file=$test->MachinePath;

			
					//$file=$set->SpectroPath;Mg-9011
				
				//$file=$_SERVER['DOCUMENT_ROOT']."/tclims/machinetests/spectro/Results.txt";
				//$file=$_SERVER['DOCUMENT_ROOT']."/tclims/machinetests/spectro/Mg-9011.txt";

				if(file_exists($file))
				{
					$fopen = fopen($file, 'r');

					$fread = fread($fopen,filesize($file));

					fclose($fopen);
					
					
				$content=mb_convert_encoding($fread,'ASCII', 'UCS-2LE');

				$remove = "\n";

				$split = explode($remove,$content);

					$array = array();
					$tab = "\t";
				
				foreach ($split as $string)
				{
					$row = explode($tab, $string);
					$row=array_map('trim',$row);
					
					$array[]=$row;
				}
						
				//$this->_sendResponse(401, CJSON::encode($array));
				$keyarray=array( 'Header','DateTime','Method_Name','Calc_Mode','Description','HeatNo','BatchCode',
							'LabNo','GradeID','Prop_SampleName','Prop_TypeCorrName','Prop_GradeSearchName',
							'e1','C','e2','Si','e3','Mn','e4','P','e5','S','e6','Cr','e7','Mo','e8','Ni','e9','Al','e10','Co',
							'e11','Cu','e12','Nb','e13','Ti','e14','V','e15','W','e16','Pb','e17','Sn','e18','As','e19','Zr','e20','Bi',
							'e21','Ca','e22','Ce','e23','Sb','e24','Se','e25','Te','e26','Ta','e27','B','e28','Zn','e29','La','e30','Fe','e31');
							
				
					$result=$array;
					$mydata=(object)array('results'=>$result,'keys'=>$keyarray);
				
					$this->_sendResponse(200, CJSON::encode($mydata));
				}
				else
				{
					$this->_sendResponse(401, CJSON::encode("Incorrect file path23--- ".$file));
				}
				
		}
		catch(Exception $e)
		{
			$this->_sendResponse(401, CJSON::encode($e->getMessage()));
		}
				break;
				
		case 'vhardsystemupdate':
			try{
				$test=Tests::model()->find(['condition'=>'TUID="VHARD"']);
				
				$file=$test->MachinePath;
				$bc=$data['BatchCode'];
				$ln=$data['LabNo'];
				$tn=$data['TNo'];
				
					if(file_exists($file))
				{
					
					$file1=$file.$ln."-H-".$tn."\/".$ln."-H-".$tn.".jpg";
					if(file_exists($file1))
					{
					$fopen = fopen($file1, 'r');
					$fread = fread($fopen,filesize($file1));
					fclose($fopen);
										
				$content=$fread;//mb_convert_encoding($fread,'ASCII', 'UCS-2LE');

				$remove = "\n";
				$split = explode($remove,$content);
					$array = array();
					$tab = "\t";
				
				foreach ($split as $string)
				{
					$row = explode($tab, $string);
					$row=array_map('trim',$row);
					$array[]=$row;
				}
						//$this->_sendResponse(401, CJSON::encode($array));
					$observations=array();		
					$firstrow=explode(",",$array[0][0]);
					
					$labno=CJSON::decode($firstrow[0]);
					$fln=$ln.'-H-'.$tn;
					//$this->_sendResponse(401, CJSON::encode($labno));
					if($labno===$fln)
					{
				
					$max=$firstrow[10];
					$min=$firstrow[11];
					
					
					
						$len=CJSON::decode($array[2][0]);
					
					//	$len=CJSON::decode($array[3][0]);
					
					
					if($len%2===0)
					{
						for($i=0;$i<$len/2;$i++)
						{
							$sv=$cv="";
							
							
								$ha1=explode(",",$array[$i+3][0]);	
						
							
							if(count($ha1)>1)
							{
								
								$hs=CJSON::decode($ha1[1]);
								if(!empty($hs))
								{
									$sv=round($hs);
								}
							}
							
							$ha2=explode(",",$array[$i+6][0]);
							if(count($ha2)>1)
							{
								$hc=CJSON::decode($ha2[1]);
								if(!empty($hc))
								{
									$cv=round($hc);
								}								
							}
							$observations[]=(object)array('SValue'=>$sv,'CValue'=>$cv);
						}
					}
					else
					{
						for($i=0;$i<$len;$i++)
						{
							$sv=$cv="";
							
									$ha1=explode(",",$array[$i+3][0]);
								
									//$ha1=explode(",",$array[$i+4][0]);
							
							$hs=CJSON::decode($ha1[1]);
							if(!empty($hs))
							{
								$sv=round($hs);
							}
							
							$observations[]=(object)array('SValue'=>$sv,'CValue'=>$cv);
							
						}
					}
				
					
					}
					else
					{
						$this->_sendResponse(401, CJSON::encode("Incorrect Data"));
					}
			
			
			$result=(object)array('observations'=>$observations);
						$data=(object)array('results'=>$result);
				
					$this->_sendResponse(200, CJSON::encode($data));
					}
					else
					{
						$this->_sendResponse(401, CJSON::encode("No test data of LabNo=".$ln));
					}
				}
			
			}
			catch(Exception $e)
			{
				$this->_sendResponse(401, CJSON::encode($e->getMessage()));
			}
				break;
				
				case 'casesystemupdate':
				try{
				$test=Tests::model()->find(['condition'=>'TUID="CASE"']);
				
				$file=$test->MachinePath;
				$bc=$data['BatchCode'];
				$ln=$data['LabNo'];
				$tn=$data['TNo'];			
				
				
					if(true)
				{
					//require_once(yii::app()->basePath . '/extensions/PhpSpreadsheet/Spreadsheet.php');

					 $file1=$file.$ln."-CD-".$tn.".xls";
					
					//$file1 = "E:/xampp/htdocs/tcrandack/machinetests/casedepth/E1188-CD-1.xls"; 
					
					if (!file_exists($file1)) {
						$this->_sendResponse(401, CJSON::encode($file1));
					}

			try {
            // Load the spreadsheet using PhpSpreadsheet
            $spreadsheet = IOFactory::load($file1);
            
            // Get the active sheet (first sheet by default)
            $sheet = $spreadsheet->getActiveSheet();
            
            // Convert the sheet to a 2D array
           
			
			  // Initialize the $value array
$value = [];

// Start from row 16 (or any row where you want to begin)
$row = 16;

// Iterate through the rows until an empty cell is found
while ($sheet->getCell('D' . $row)->getValue() !== null && $sheet->getCell('I' . $row)->getValue() !== null) {
    // Get the corresponding cell values for 'Distance' and 'Hardness'
    $distance = $sheet->getCell('D' . $row)->getValue();
    $hardness = $sheet->getCell('I' . $row)->getValue();

    // Add the object to the $value array
    $value[] = (object)[
        'Distance' => $distance,
        'Hardness' => $hardness
    ];

    // Move to the next row
    $row++;
};
			 

            // Output data (or process as needed)
            $this->_sendResponse(200, CJSON::encode($value));
        } catch (Exception $e) {
           $this->_sendResponse(401, CJSON::encode($e->getMessage()));
        }

				}
			
			}
			catch(Exception $e)
			{
				$this->_sendResponse(401, CJSON::encode($e->getMessage()));
			}
				break;
				
					case 'mvhardsystemupdate':
			try{
				$test=Tests::model()->find(['condition'=>'TUID="MVHARD"']);
				
				$file=$test->MachinePath;
				$bc=$data['BatchCode'];
				$ln=$data['LabNo'];
				$tn=$data['TNo'];			
				
				
		if (true) {
    // Uncomment if needed for PhpSpreadsheet
    // require_once(yii::app()->basePath . '/extensions/PhpSpreadsheet/IOFactory.php');

    $file1 = $file . $ln . "-H-" . $tn . ".xls";

    // Debug the file path
  //  echo "File Path: " . $file1 . "\n";

    if (!file_exists($file1)) {
        $this->_sendResponse(401, CJSON::encode("File does not exist: " . $file1));
    }

    try {
        // Load the spreadsheet using PhpSpreadsheet
        $spreadsheet = IOFactory::load($file1);

        // Get the active sheet (first sheet by default)
        $sheet = $spreadsheet->getActiveSheet();

        // Convert the sheet to a 2D array and fetch values
        $value[] = (object)['SValue' => $sheet->getCell('I16')->getValue(), 'CValue' => $sheet->getCell('I19')->getValue()];
        $value[] = (object)['SValue' => $sheet->getCell('I17')->getValue(), 'CValue' => $sheet->getCell('I20')->getValue()];
        $value[] = (object)['SValue' => $sheet->getCell('I18')->getValue(), 'CValue' => $sheet->getCell('I21')->getValue()];

        // Output data (or process as needed)
        $this->_sendResponse(200, CJSON::encode($value));

    } catch (Exception $e) {
        // Log and send the error message
        error_log($e->getMessage());
        $this->_sendResponse(401, CJSON::encode($e->getMessage()));
    }
}
	
			}
			catch(Exception $e)
			{
				$this->_sendResponse(401, CJSON::encode($e->getMessage()));
			}
				break;
	   case 'hardsystemupdate':
				$bc=$data['basic']['BatchCode'];
					//$ln=$data['basic']['LabNo'];
					$htid=$data['cond']['HTId'];
					$ln="";
				if($data['basic']['TestId']==='2')
				{
					$ln=$data['basic']['LabNo']."-H-1";
				}
				if($data['basic']['TestId']==='26')
				{
					$ln=$data['basic']['LabNo']."-H-2";
				}
				if($data['basic']['TestId']==='27')
				{
					$ln=$data['basic']['LabNo']."-H-3";
				}
				if($data['basic']['TestId']==='73')
				{
					$ln=$data['basic']['LabNo']."-H-4";
				}
				if($data['basic']['TestId']==='74')
				{
					$ln=$data['basic']['LabNo']."-H-5";
				}
				if($data['basic']['TestId']==='75')
				{
					$ln=$data['basic']['LabNo']."-H-6";
				}
				if($data['basic']['TestId']==='76')
				{
					$ln=$data['basic']['LabNo']."-H-7";
				}
				if($data['basic']['TestId']==='77')
				{
					$ln=$data['basic']['LabNo']."-H-8";
				}
				if($data['basic']['TestId']==='78')
				{
					$ln=$data['basic']['LabNo']."-H-9";
				}
				if($data['basic']['TestId']==='28')
				{
					$ln=$data['basic']['LabNo']."-H-W";
				}
				$set=Settings::model()->findByPk(1);
				if($htid !='2' && $htid !="3")
				{
					$this->_sendResponse(401, CJSON::encode("No system available."));
				}
				else
				{	
			
					if($htid==='2')
					{
						$file=$set->HardVickerPath;
						$file1=$file."\/".$ln."\/".$ln.".jpg";
					}
					else if($htid==='3')
					{
						$file=$set->HardMicroVPath;
						$file1=$file."\/".$ln."\/".$ln.".jpg";
						
					}
			
			
				if(file_exists($file))
				{
					if(file_exists($file1))
					{
					$fopen = fopen($file1, 'r');
					$fread = fread($fopen,filesize($file1));
					fclose($fopen);
										
				$content=$fread;//mb_convert_encoding($fread,'ASCII', 'UCS-2LE');

				$remove = "\n";
				$split = explode($remove,$content);
					$array = array();
					$tab = "\t";
				
				foreach ($split as $string)
				{
					$row = explode($tab, $string);
					$row=array_map('trim',$row);
					$array[]=$row;
				}
						
					$observations=array();		
					$firstrow=explode(",",$array[0][0]);
					
					$labno=CJSON::decode($firstrow[0]);
					//$this->_sendResponse(401, CJSON::encode($labno));
					if($labno===$ln)
					{
				
					$max=$firstrow[10];
					$min=$firstrow[11];
					
					
					if($htid==='2')
					{
						$len=CJSON::decode($array[2][0]);
					}
					else if($htid==='3')
					{
						$len=CJSON::decode($array[3][0]);
					}
					
					if($len%2===0)
					{
						for($i=0;$i<$len/2;$i++)
						{
							$sv=$cv="";
							
							if($htid==='2')
					{
						$ha1=explode(",",$array[$i+3][0]);
					}
					else if($htid==='3')
					{
						$ha1=explode(",",$array[$i+4][0]);
					}
							
							
							$hs=CJSON::decode($ha1[1]);
							if(!empty($hs))
							{
								$sv=round($hs);
							}
							$ha2=explode(",",$array[$i+4+($len/2)][0]);
							$hc=CJSON::decode($ha2[1]);
							if(!empty($hc))
							{
								$cv=round($hc);
							}
							$observations[]=(object)array('SValue'=>$sv,'CValue'=>$cv);
							
						}
					}
					else
					{
						for($i=0;$i<$len;$i++)
						{
							$sv=$cv="";
							if($htid==='2')
								{
									$ha1=explode(",",$array[$i+3][0]);
								}
								else if($htid==='3')
								{
									$ha1=explode(",",$array[$i+4][0]);
								}
							$hs=CJSON::decode($ha1[1]);
							if(!empty($hs))
							{
								$sv=round($hs);
							}
							
							$observations[]=(object)array('SValue'=>$sv,'CValue'=>$cv);
							
						}
					}
				
					
					}
					else
					{
						$this->_sendResponse(401, CJSON::encode("Incorrect Data"));
					}
			
			
			$result=(object)array('observations'=>$observations);
						$data=(object)array('results'=>$result);
				
					$this->_sendResponse(200, CJSON::encode($data));
					}
					else
					{
						$this->_sendResponse(401, CJSON::encode("No test data of LabNo=".$ln));
					}
				}
				else
				{
					$this->_sendResponse(401, CJSON::encode("Incorrect file path"));
				}
				}
				break;
				
				
			    case 'pdiradd':
			   $transaction=$model->dbConnection->beginTransaction();
				try
					{
						
						$model->LastModified=date('Y-m-d H:i:s');
						$model->save(false);
												

						// foreach($data['basic']['observations'] as $d)
						// {
								// $sd=new Pdirobservations;
								// $sd->PdirBasicId=$model->getPrimaryKey();
								 // foreach($d as $var=>$value)
								  // {
									// // Does the model have this attribute? If not raise an error
									// if($sd->hasAttribute($var))
										// $sd->$var = $value;
								  // }		
								// $sd->save(false);
							
						// }

												
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($model->Id));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e));
					}
			   
				break;
  
			   
			  

							
				case 'riradd':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						
						$u=MyFunctions::gettokenuser();
						$set=Settingslab::model()->find(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
						
						$countrir=Receiptir::model()->count(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
						$lastrecord = Receiptir::model()->find(array('order'=>'Id DESC','condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]));
	
						$model->CreationDate=date('Y-m-d H:i:s');
						$model->save(false);
						
							$labno=0;
							$batchcode="";
							
							
							if($model->IsMdsTds==="tds")
							{
								$bn=0;
								$batchcode=$data['BatchCode'];
								$bn=$data['BatchNo'];
							/*	//--verify old batchcode 
								$bc=Receiptir::model()->find(['condition'=>'BatchCode=:bc AND IsMdsTds="mds"',
								'params'=>[':bc'=>$batchcode],
								'order'=>'Id desc']);
								
								if(!empty($bc))
								{
										//---get tds count of batchcode
										$bn=Receiptir::model()->count(['condition'=>'BatchCode=:bc AND IsMdsTds="tds"',
										'params'=>[':bc'=>$batchcode],]);
										
										$bn++;
									
								}
								*/
								$model->BatchCode=$batchcode;
								$model->BatchNo=$bn;
								
								
							}
							else if($model->IsMdsTds==="mds")
							{
								if($countrir <1)
									{									
										$batchcode=$set->BatchCodeStart;
										$model->BatchCode=$batchcode;
									}
									else
									{												
										$batchcode=$set->LastBatchCode;
										$batchcode++;												
										$model->BatchCode=$batchcode;
									}	
									
									$set->LastBatchCode=$model->BatchCode;
									$set->save(false);
						
							}
							
							if($countrir <1)
									{										
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
											
									}	
						
						$model->LabNo=$labno;
						$model->save(false);						
						
						$set->LastLabNo=$labno;
									$set->save(false);
						
						
						
						
						if(!empty($data['extras']) )
						{
							$rirextras =new Rirextras;
							foreach($data['extras'] as $var=>$value)
							  {
								// Does the model have this attribute? If not raise an error
								if($rirextras->hasAttribute($var))
									$rirextras->$var = $value;
							  }
							$rirextras->Id=null;							  
							$rirextras->RIRId=$model->getPrimaryKey();
							$rirextras->save(false);
							
						}
						else
						{
							$rirextras =new Rirextras;
													  
							$rirextras->RIRId=$model->getPrimaryKey();
							$rirextras->save(false);
						}
						
						
						
						/*
						$quote=new Quotation;
						$quote->CustId=$model->CustomerId;
						$quote->QDate=date('Y-m-d H:i:s');
						$quote->ValidDate=date('Y-m-d H:i:s');
						  $quote->save(false);
						  
						   $web = array();
				   $web[]='Q';
				   $web[]='N';

				  $web[]=str_pad( $quote->getPrimaryKey(),4, "0", STR_PAD_LEFT );
				

                  $orderno=implode($web); 

                  $quote->QNo=$orderno;
				     $quote->Status="Approved";
				  $quote->CreatedOn=date('Y-m-d H:i:s');
                  $quote->save(false);
				 
				  
				  
				   $model->RefPurchaseOrder= $quote->QNo;
				   $model->PODate= $quote->CreatedOn;
						$model->save(false);
				  
				   */
				  
				  $mdstdsid=$model->MdsTdsId;
				 
				  $qsubtotal=0;
				   
						foreach($data['applicabletest'] as $t)
							{
								
										$price=0;	
										$rtds=new Rirtestdetail;
										$testno=$set->LastTestNo;										
										
											$yr=date("y");
												$month=date('m');
												$gy=substr($testno, 0 , 2);
												$gm=substr($testno, 2 , 2);
										if($yr===$gy)
										{
											//---increment
											if($gm===$month)
												{
													//---Increment
													$lasttestno=substr($testno, 4);
													$lasttestno =  (int)$lasttestno;
													$newtestno=(int)$lasttestno+1;
												}
												else
												{
													$lasttestno='0001';
													$newtestno =  (int)$lasttestno;
												}
											
										}
										else
										{
											if($gm===$month)
												{
													//---Increment
													$lasttestno=substr($testno, 4);
													$lasttestno =  (int)$lasttestno;
													$newtestno=(int)$lasttestno+1;
												}
												else
												{
													$lasttestno='0001';
													$newtestno =  (int)$lasttestno;
												}
										}	
											
											
										
										$newtestno=str_pad($newtestno, 4, "0", STR_PAD_LEFT);
										//$testno=$alpha.$testno;
										$set->LastTestNo=$yr.$month.$newtestno;
										$set->save(false);
										$rtds->TestNo=$yr.$month.$newtestno;
										$rtds->TestId=$t['TestId'];
										
										$mt=Tests::model()->findByPk($t['TestId']);
										//$price=$price+$mt->Cost;
										
										//$tc=Testconditions::model()->findByPk($t['TestCondId']);
										//$price=$price+$tc->Cost;										
										
										$sub=Substandards::model()->findByPk($t['SSID']);
									//	$price=$price+$sub->Cost;										
										
										$dat=$model->CreationDate;
										$time = strtotime($dat);
										$rtds->Note=empty($mt->DefaultNote)?$set->DefaultTestNote:$mt->DefaultNote;
										$newformat = date('Y-m-d',$time);
										$rtds->CreationDate=$newformat;
									
										$rtds->RIRId=$model->getPrimaryKey();
																	
										$rtds->TestName=$t['TestName'];	
										$rtds->TSeq=$t['TSeq'];	
										$rtds->TUID=$t['TUID'];	
										$rtds->TMID=isset($t['TMID'])?$t['TMID']:null;					
										$rtds->SSID=isset($t['SSID'])?$t['SSID']:null;
										//$rtds->PlanId=isset($t['PlanId'])?$t['PlanId']:null;
										$rtds->ExtraInfo=isset($t['ExtraInfo'])?$t['ExtraInfo']:null;																	
										$rtds->ReqDate=$t['ReqDate'];
										$rtds->CID=$u->CID;
										$rtds->save(false);
										
									
									if(!empty($t['Labs']))
									{
										foreach($t['Labs'] as $c)
										{
											$sc=new Rirtestlabs;
											$sc->RTID=$rtds->getPrimaryKey();
											$sc->LabId=$c;
											$sc->save(false);
										}
									}
									
									//---get mds Test
									
									$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$rtds->TestId]]);
									
									if(!empty($mdstdstest))
									{
										
										
											$testbasicparams=$rtds->test->testbasicparams;
										foreach($testbasicparams as $e)
											{
												$mdbp=Mdstdstestbasedetails::model()->find(['condition'=>'MTTID=:mttid AND PID=:pid',
									'params'=>[':mttid'=>$mdstdstest->Id,':pid'=>$e->Id]]);
									
													$sbd=new Rirtestbasic;
													$sbd->RTID=$rtds->getPrimaryKey();
													$sbd->TBPID=$e->Id;
													if(!empty($mdbp))
													{														
														$sbd->BValue=$mdbp->Value;													
													}
													else
													{													
														$sbd->BValue=null;													
													}
												$sbd->save(false);
													
											}
											
									}
									else
									{
										$testbasicparams=$rtds->test->testbasicparams;
										foreach($testbasicparams as $e)
											{
												$sbd=new Rirtestbasic;
													$sbd->RTID=$rtds->getPrimaryKey();
													$sbd->TBPID=$e->Id;
													$sbd->BValue=null;
													$sbd->save(false);
											}
										
									}
							
									//---Add paramereters in rir test obs
											
										
									
										$testobsparams=$rtds->test->testobsparams;
										
											foreach($testobsparams as $e)
											{
													$sd=new Rirtestobs;
													$sd->RTID=$rtds->getPrimaryKey();
													$sd->TPID=$e->Id;
													
													if(!is_null($rtds->SSID))
													{
														if(!empty($mdstdstest))
														{
															$mdstdsobs=Mdstdstestobsdetails::model()->find(['condition'=>'MTTID=:mttid AND PID=:pid',
															'params'=>[':mttid'=>$mdstdstest->Id,':pid'=>$e->Id]]);
															
															if(!empty($mdstdsobs))
															{
																$sd->SpecMin=empty($mdstdsobs)?null:$mdstdsobs->SpecMin;
																$sd->SpecMax=empty($mdstdsobs)?null:$mdstdsobs->SpecMax;
															}
														}
														else
														{
															$ssdt=Stdsubdetails::model()->find(array('condition'=>'SubStdId =:ssid AND PId=:pid',
													'params'=>array(':ssid'=>$rtds->SSID,':pid'=>$e->Id)));	
															if(!empty($ssdt))
															{
																$sd->SpecMin=empty($ssdt)?null:(float)$ssdt->SpecMin;
																$sd->SpecMax=empty($ssdt)?null:(float)$ssdt->SpecMax;
															}	
														}
														
														
													}													
											
													$sd->MR=$e->MR;											
													$sd->save(false);	
											}
											
												  
					
									$fas=$rtds->test->Id;
									//$this->_sendResponse(401, CJSON::encode($fas));
									
									$appsec=Appsections::model()->find(array('condition'=>'Others=:key','params'=>array(':key'=>$fas)));
								
									//---add to user notification---//
									$not=new Notifications;
									$not->Notifications="New ".$rtds->TestName." Test ".$rtds->rIR->LabNo;
									$not->CreatedAt=date('Y-m-d H:i:s');
									$not->AppSecId=$appsec->Id; //--not found
									$not->save(false);
									
									
											$uaps=Userapppermission::model()->with('section','user')->findAll(array('condition'=>'section.Others=:secid AND t.C="true" AND user.CID=:cid',
												'params'=>array(':secid'=>$fas,':cid'=>$u->CID)));
											
											foreach($uaps as $ud)
											{ 
												$un=new Usernotifications;
												$un->NotId=$not->getPrimaryKey();
												$un->UserId=$ud->UserId;
												$un->Status=0;
												$un->save(false);
											}							
								
							}
							
							if(!empty($data['ExtTest']))
							{
								foreach($data['ExtTest'] as $t)
								{
									$rextds=Rirexttestdetail::model()->find(array('condition'=>'ExtTestId=:id  AND RIRId=:rir',
											 'params'=>array(':id'=>$t['Id'],':rir'=>$model->Id),));
										if(empty($rextds))
										{
											$rextds=new Rirexttestdetail;
											$rextds->ExtTestId=$t['Id'];
										}
										
										
										$rextds->RIRId=$model->getPrimaryKey();
									
										$rextds->TestName=$t['TestName'];
										$rextds->Applicable="true";
										$rextds->ExtraInfo=isset($t['ExtraInfo'])?$t['ExtraInfo']:"";
										//$rtds->ReqDate=$t['ReqDate'];
										$rextds->Status="pending";
										$rextds->LastModified=date('Y-m-d H:i:s');
										$rextds->save(false);
									
								}
							
							}
							
							
							
                  // $quote->SubTotal=$qsubtotal;
                  // $quote->Discount=0;
				  // $quote->IsTax=isset($data['IsTax'])?$data['IsTax']:0;
				   // $quote->Tax=isset($data['Tax'])?$data['Tax']:0;
				   // $quote->TotTax=0;
				   // $quote->Total=$quote->SubTotal + $quote->TotTax - $quote->Discount;
				   // $quote->Note=empty($data['Note'])?null:$data['Note'];
				   // //$quote->CreatedBy=$data['CreatedBy'];
				  // // $quote->AssignedTo=$data['AssignedTo'];
				   // $quote->save(false);
				   
							
							
							$transaction->commit();
							$this->_sendResponse(200, CJSON::encode("success"));
						
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
				// Find respective model
				
				case 'rirfiltdata':$model=Users::model()->findByPk($_GET['id']);break;
				case 'testsubstds':$model = Tests::model()->findByPk($_GET['id']);    		break;
				
			case 'sendrirmail':$model = Receiptir::model()->findByPk($_GET['id']);    		break;
				
				case 'testdata': $model=Users::model()->findByPk($_GET['id']);break;
				
					case 'pdirupdate':		$model=Pdirbasic::model()->findByPk($_GET['id']); 		break;
					
					case 'createtestpdf':
					case 'approvetest':		$model=Rirtestdetail::model()->findByPk($_GET['id']); 		break;
				
			
				
			
				case 'testobsupdate': $model=Rirtestdetail::model()->findByPk($_GET['id']);		break;
			
				
				case 'rirupdate':			$model = Receiptir::model()->findByPk($_GET['id']);    		break;
					case 'rirtestupdate':			$model = Receiptir::model()->findByPk($_GET['id']);    		break;
									
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
				
				case 'rirfiltdata':
				case 'testsubstds':
			case 'sendrirmail':
			case 'testdata':$data=$put_vars;break;
			case 'pdirupdate':$data=$put_vars;
			foreach($put_vars['basic'] as $var=>$value) {
									// Does model have this attribute? If not, raise an error
									if($model->hasAttribute($var))
										$model->$var = $value;
					}		
					break;
			
			
			break;
			case 'createtestpdf':
			case 'approvetest':$data=$put_vars;break;
		
			
			case 'testobsupdate':	$data=$put_vars;break;
			
			case 'rirupdate':
					$data=$put_vars;  
					
					foreach($put_vars as $var=>$value) {
									// Does model have this attribute? If not, raise an error
									if($model->hasAttribute($var))
										$model->$var = $value;
					}		
					break;
			
			case 'rirtestupdate':
					$data=$put_vars;  
					$oldbc=$model->BatchCode;
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
						
			
				case 'rirfiltdata':
				try{
					$this->_checkAuth(4,'R');					
					
					$u=$model;
					
					$totalcount=Receiptir::model()->count(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
					$rirs=[];
					if(isset($data['pl']))
				{
				$rirs=Receiptir::model()->findAll(array(
    'order' => 'Id desc',
    'limit' => $data['pl'],
	'condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID],
    'offset' => ($data['pn']-1)*$data['pl']
));
				}
					
					$allrirs=array();
					foreach($rirs as $r)
					{
							
							$testdetails=array();
							foreach($r->rirtestdetails as $t)
							{
								$std="Not Applicable";
								
								if(!empty($t->SSID))
								{
									
										$sub=Substandards::model()->findByPk($t->SSID);
									
									$substandard="";
									
										$substandard=$sub->Grade." ".$sub->ExtraInfo;
									
									$std=$sub->std->Standard." ".$substandard;	
								}
								
								$method="";
								if(!is_null($t->TMID))
								{
									
										$tm=Testmethods::model()->findByPk($t->TMID);
									$method=$tm->Method;
									
								}
								$today = date("Y-m-d");
								$expire = $t->ReqDate; //from db
								$overdue="false";
								$today_time = new DateTime($today);
								$expire_time = new DateTime($expire);

								if ($expire_time <$today_time) { $overdue="true"; }
								
								
								
								$labnames=[];
								
								if(!empty($t->rirtestlabs))
								{
									foreach($t->rirtestlabs as $l)
									{
										$lab=Labaccredit::model()->findByPk($l->LabId);
										$labnames[]=$lab->Name;
									}
								}
								
								
								
				
							$apsec=Appsections::model()->find(['condition'=>'Others=:oth','params'=>[':oth'=>$t->TestId]]);
								$testdetails[]=(object)array('TestName'=>$t->TestName,'Standard'=>$std,
								'ReqDate'=>$t->ReqDate,'labnames'=>$labnames,
								'TestId'=>$t->TestId,'TestMethod'=>$method,'TMID'=>$t->TMID,
								'SSID'=>$t->SSID,'ExtraInfo'=>$t->ExtraInfo,
								'Status'=>$t->Status,'Overdue'=>$overdue,'SID'=>$apsec->Id);
						
						}
						
						$exttestdetails=array();
						
						
						
							
							$time=strtotime($r->CreationDate);
							$rirgdt = date('d.m.y',$time);
							
							$rirextra=empty($r->rirextrases)?null:$r->rirextrases[0];
							
							$mdstds=Mdstds::model()->findByPk($r->MdsTdsId);
						
						$allrirs[]=(object)array('Id'=>$r->Id,'SampleName'=>$r->SampleName,'Description'=>$r->Description,
						'CustomerId'=>$r->CustomerId,'Customer'=>empty($r->customer)?null:$r->customer->Name,
						'CustEmail'=>empty($r->customer)?null:$r->customer->Email,'Supplier'=>empty($r->supplier)?null:$r->supplier->Name,
						'SampleCondition'=>"",'IndId'=>$r->IndId,'Industry'=>implode(" - ",array_reverse( MyFunctions::getParentCat($r->IndId))),
						'BatchCode'=>$r->BatchCode,'BatchNo'=>$r->BatchNo,'GeneratedDate'=>$rirgdt,'IsMdsTds'=>$r->IsMdsTds,'MdsTdsNo'=>empty($mdstds)?null:$mdstds->No,
						'RefPurchaseOrder'=>$r->RefPurchaseOrder,'HeatNo'=>$r->HeatNo,'HTBatchNo'=>$r->HTBatchNo,'rirextra'=>$rirextra,
						'LabNo'=>$r->LabNo,'alltests'=>$testdetails);
					}
					
					
					$data=(object)array('allrirs'=>$allrirs,'totalcount'=>$totalcount);
						$this->_sendResponse(200, CJSON::encode($data));
			}
catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;						
					
				case 'testsubstds':
				try
				{
					$u=MyFunctions::gettokenuser();
					$substdtests=Substdtests::model()->findAll(['condition'=>'TID=:tid','params'=>[':tid'=>$model->Id]]);
				
				if(empty($substdtests))
				{
					$substdtests=Substdtests::model()->with('sS')->findAll(['condition'=>'sS.CID=:cid','params'=>[':cid'=>$u->CID]]);
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
						foreach($substdtests as $sd)
						{
							$substd=Substandards::model()->findByPk($sd->SSID);
							$allsubstds[]=(object)['Id'=>$substd->Id,'Name'=>$substd->std->Standard.' '.$substd->Grade,
							];
						}
						
				}
						
						$testmethods=Testmethods::model()->findAll(['condition'=>'TestId=:tid','params'=>[':tid'=>$model->Id]]);
						$alltestmethods=[];
						$result=(object)['allsubstds'=>$allsubstds,'alltestmethods'=>$testmethods];
				$this->_sendResponse(200, CJSON::encode($result));
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
				case 'sendrirmail':
				try
				{
						$r=$model;
							
							$testdetails=array();
							foreach($r->rirtestdetails as $t)
							{
								$std="Not Applicable";
								
								if(!empty($t->SubStdId))
								{
									
										$sub=Substandards::model()->findByPk($t->SubStdId);
									
									$substandard="";
									
										$substandard=$sub->Grade." ".$sub->ExtraInfo;
									
									$std=$sub->std->Standard." ".$substandard;	
								}
								
								$method="";
								if(!empty($t->TestMethodId))
								{
									
										$tm=Testmethods::model()->findByPk($t->TestMethodId);
									$method=$tm->Method;
									
								}
								$today = date("Y-m-d");
								$expire = $t->ReqDate; //from db
								$overdue="false";
								$today_time = new DateTime($today);
								$expire_time = new DateTime($expire);

								if ($expire_time <$today_time) { $overdue="true"; }
								
								
								
								$labnames=[];
								
									foreach($t->rirtestlabs as $l)
									{
										$lab=Labaccredit::model()->findByPk($l->LabId);
										$labnames[]=$lab->Name;
									}
								
								$pld=Stdsubplans::model()->findByPk($t->PlanId);
								$plan="";
								if(!empty($pld))
								{
									
									
									$parameters=[];
									foreach($pld->stdsubplandetails as $pd)
									{
										$parameters[]=(object)['Id'=>$pd->SSDID,'Parameter'=>$pd->sSD->p->Parameter,'PSymbol'=>$pd->sSD->p->PSymbol,
						'PCatId'=>$pd->sSD->p->PCatId,'CatName'=>empty($pd->sSD->p->pCat)?"":$pd->sSD->p->pCat->CatName,];
										
									}
									
									$plan=(object)array('Id'=>$pld->Id,'Name'=>$pld->Name,'Cost'=>$pld->Cost,'SubStdId'=>$pld->SubStdId,'VParameters'=>$parameters);
								
								
								
								}
								
				
							$apsec=Appsections::model()->find(['condition'=>'Others=:oth','params'=>[':oth'=>$t->TestId]]);
								$testdetails[]=(object)array('TestName'=>$t->TestName,'Standard'=>$std,
								'ReqDate'=>$t->ReqDate,'labnames'=>$labnames,'PlanId'=>$t->PlanId,'Plan'=>empty($plan)?"":$plan,
								'TestId'=>$t->TestId,'TestMethod'=>$method,'TestMethodId'=>$t->TestMethodId,
								'SubStdId'=>$t->SubStdId,'ExtraInfo'=>$t->ExtraInfo,
								'Status'=>$t->Status,'Overdue'=>$overdue,'SID'=>$apsec->Id);
						
						}
						
						$exttestdetails=array();
						
						foreach($r->rirexttestdetails as $ex)
						{
							$exttestdetails[]=$ex->TestName;
						}
						
							
							$time=strtotime($r->CreationDate);
							$rirgdt = date('d.m.y',$time);
						
						$arir=(object)array('Id'=>$r->Id,'SampleName'=>$r->SampleName,'SampleWeight'=>$r->SampleWeight,
						'CustomerId'=>$r->CustomerId,'Customer'=>$r->customer->Name,'TAT'=>$r->TAT,
						'SampleCondition'=>$r->SampleCondition,'IndId'=>$r->IndId,'Industry'=>implode(" - ",array_reverse( MyFunctions::getParentCat($r->IndId))),
						'NoOfSamples'=>$r->NoOfSamples,'BatchCode'=>$r->BatchCode,'GeneratedDate'=>$rirgdt,
						'RefPurchaseOrder'=>$r->RefPurchaseOrder,'PODate'=>$r->PODate,
						'LabNo'=>$r->LabNo,'alltests'=>$testdetails);
					
					
					//$data=(object)array('allrirs'=>$allrirs,);
						
							$sentmails=$data['MailTo'];
							$appset=Settings::model()->find();
					$msg='<table class="table table-sm table-bordered" style="background:#fff;" border="1" cellpadding="4" cellspacing="0">
				<thead>
					<tr>
						<td rowspan="4" class="align-middle" style="text-align:center">
						 <img src="'.Yii::app()->params['base-url'].'img/blacklogo.png" style="height:40px;">
						</td>
						<td rowspan="4" colspan="3" style="text-align:left">'.$appset->CompanyName.'<br>'.$appset->CompanyAddress.'
						</td>						
					</tr>
				</thead>';
				
				$msg.='
				<tr >
				
				<td class="col-md-2 text-right active" ><b>LabNo.</b></td><td  colspan="1" class="col-md-3">'.$r->LabNo.'</td>
				<td  class="col-md-2 text-right active"><b>Customer</b></td><td  colspan="1" class="col-md-3">'.$r->customer->Name.'</td>
				
				</tr>
				<tr>
				<td  class="col-md-2 text-right active"><b>Sample Name</b></td><td  colspan="1" class="col-md-3">'.$r->SampleName.'</td>
				<td  class="col-md-2 text-right active"><b>SampleCode</b></td><td  colspan="1" class="col-md-3">'.$r->BatchCode.'</td> 	
				</tr>
					<tr>
					<td  class="col-md-2 text-right active"><b>Sample Weight</b></td><td  colspan="1" class="col-md-3">'.$r->SampleWeight.'</td>
					<td  class="text-right active"><b>Turn around Time</b></td><td  colspan="1" class="col-md-3">'.$r->TAT.'</td> 
					
				</tr>
								<tr>
					<td  class=" text-right active"><b>Ref. PO</b></td><td  colspan="1" class="col-md-3">'.$r->RefPurchaseOrder.'</td>
					<td  class=" text-right active"><b>PO Date</b></td><td  colspan="1" class="col-md-3">'.$r->PODate.'</td>
				</tr>
				
				
				<tr>
				<td  class="col-md-2 text-right active"><b>Sample Condition</b></td><td  colspan="1" class="col-md-3">'.$r->SampleCondition.'</td>
					<td  class="col-md-2 text-right active"><b>No. Of Samples</b></td><td  colspan="1" class="col-md-3">'.$r->NoOfSamples.'</td> 
					
				</tr>
				
				
					
				<tr>
					
					 <td  class="col-md-2 text-right active"><b>Sample Register Date </b></td><td  colspan="1" class="col-md-3">'.$rirgdt.'</td> 
					 <td colspan="2"></td>
					
				</tr>';
				
				$msg.='	<tr>
						<td colspan="4"><b>Tests Required:</b></td>
					</tr>
				<tr class="active">
						<td >Test</td>
						<td >Standard </td>
						
						<td >Plan</td>
						
						<td >Status/ Required Date</td> 
					</tr>';
					
					
				foreach($testdetails as $t)
				{
				
					$msg.='<tr >
						<td>'.$t->TestName.' <br> 
						<small>Lab accreditation:'.implode(",",$t->labnames).'</small>
						
						</td>
						<td>'.$t->Standard.' <br>
						Test_Method: '.$t->TestMethod.'
						</td>
					
						<td >'.empty($t->Plan)?null:$t->Plan->Name.'
							
						</td>
						<td> '.$t->Status.'<br>
						'.$t->ReqDate .'
						</td>
						
					</tr>';
					
					}
					
					
					$msg.='<tbody></table>';
    
							
					
						  $mset=Settingsmail::model()->find();

                  $mail = new YiiMailer;
                  $mail->IsSMTP();
                  
				  
				  
 
 $mail->setSmtp(  $mset->Server,  $mset->Port,  $mset->Encrypt ,true,  $mset->Email,  $mset->Password);
                  $mail->setFrom($mset->Email,Yii::app()->params['appname']);
                  $mail->setTo($sentmails);
                  $mail->setSubject('Sample '.$r->LabNo);
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
							
				case 'testdata':				
				try{
					
					$aps=Appsections::model()->find(array('condition'=>'Others =:oth','params'=>[':oth'=>$data['TestId']]));	
					//$test=$data['TestId'];
					$this->_checkAuth($aps->Id,'R');
					$u=MyFunctions::gettokenuser();
						
						$totalitems=Rirtestdetail::model()->with('rIR')->count(array('condition'=>'TestId=:tid AND t.CID=:cid',
						'params'=>array(':tid'=>$data['TestId'],':cid'=>$u->CID)));
										 				 
										 
						if(isset($data['pl']))
						{
							$rtds=Rirtestdetail::model()->with('rIR')->findAll(array('condition'=>'TestId=:tid AND t.CID=:cid',
							'params'=>array(':tid'=>$data['TestId'],':cid'=>$u->CID),
							  'order' => 't.Id DESC',
								'limit' => $data['pl'],
								'offset' => ($data['pn']-1)*$data['pl'],
								));
										 
						}	

							$allrirs=array();
							
							$test=Tests::model()->findbyPk($data['TestId']);
							
							
						
							
							foreach($rtds as $d)
											{
												
												$allrirs[]=MyTestdata::gettestdata($d);
											}
							 $tu=Tests::model()->findByPk($data['TestId']);
							
							$result=(object)array('TUID'=>$tu->TUID,'allrirs'=>$allrirs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($result));	
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}				
							break;
							
				case 'pdirupdate':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
						
						$model->LastModified=date('Y-m-d H:i:s');
						$model->save(false);
						
																		
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($model->Id));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($data));
					}
			   
							break;
			case 'createtestpdf':	
$transaction=$model->dbConnection->beginTransaction();
				try
					{
							$u=MyFunctions::gettokenuser();		
						
						
						$appset=Settingsfirm::model()->find();
						$d=$model;
							
						
											
						$rtds=MyTestdata::gettestdata($d);

//$this->_sendResponse(401, CJSON::encode($rtds));

$fileulr=Yii::app()->basePath."/../../pdf/testreport/".$rtds->LabNo;
if (!file_exists($fileulr)) {
    mkdir($fileulr, 0777, true);
}
//$this->_sendResponse(401, CJSON::encode($rtds));
$url=Yii::app()->params['base-url']."pdf/testreport/".$rtds->LabNo."/".$rtds->TestNo."-".$rtds->TNo.".pdf";
$qrimg=Yii::app()->params['base-url']."pdf/testreport/".$rtds->TestNo."-".$rtds->TNo.".png";

Yii::app()->qrcode->create($url,Yii::app()->basePath."/../../pdf/testreport/".$rtds->LabNo."/".$rtds->TestNo."-".$rtds->TNo.".png");

				
				 					
require_once(yii::app()->basePath . '/extensions/tcpdf/tcpdf.php');
  $pdf = new 
   MYPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT, true,'UTF-8',false);
   //$pdf->SetPageSize('A4'); // You can set other sizes if needed
   $pdf->SetMargins(15, 10, 10, true);
    $pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(false);
  $pdf->setHeaderData('',0,'','',array(0,0,0), array(255,255,255) );  
   // $fontname = TCPDF_FONTS::addTTFfont('../fonts/Poppins-Regular.ttf', 'TrueType', '', 32);
   // $pdf->AddFont('poppins', '', 10, '',false);
  $pdf->SetFont( 'times', '', 10);
  $pdf->AddPage();
  
  $pdf->setListIndentWidth(2);
  $msg=MyFunctions::gettestui($rtds,$pdf);
 
 
 
  $pdf->writeHTML($msg,true, false, true, false, '');
				

  
 // $pdf->IncludeJS($js);
  $pdf->Output ( yii::app()->basePath."/../../pdf/testreport/".$rtds->LabNo."/".$rtds->TestNo."-".$rtds->TNo.".pdf", 'F' ); 
			
							
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($url));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
			   
							break;					
			case 'approvetest':
				$transaction=$model->dbConnection->beginTransaction();
				try
					{
							$u=MyFunctions::gettokenuser();		
							$uir=Userapppermission::model()->find(array('condition'=>'UserId =:uid AND SectionId=:sid',
							'params'=>array(':uid'=>$data['ApprovedBy'],':sid'=>$data['SectionId']),));
							if(!empty($uir))
							{
								if($uir->A)
								{
									$model->ApprovedBy=$data['ApprovedBy'];	
								}
							}		
							
						$model->ApprovedBy=$data['ApprovedBy'];	
							if(is_null($model->ULRNo))
							{
								
								$currentYear = date('Y'); // Get the current year
								$labset=Settingslab::model()->find();
								
										$yr=date("y");
										$loc="0";
										$ulrno=$labset->LastULRNo;
										$certno=substr($ulrno, 0 , 6);
										$gy=substr($ulrno, 6 , 2);
										
										if($yr===$gy)
										{
											//---increment
											$getno=substr($ulrno, 9 , 8);
											$input=(int)$getno+1;//$model->Id;
											$str2=str_pad($input, 8, "0", STR_PAD_LEFT);
										}
										else
										{
											$input='01';//$model->Id;
											$str2=str_pad($input, 8, "0", STR_PAD_LEFT);
										}
										
										$str3="F";
										$model->ULRNo=$certno.$yr.$loc.$str2.$str3;
										$model->save(false);
										
							
										$labset->LastULRNo=$model->ULRNo;
										$labset->save(false);
							
							}
						$model->ApprovedDate=date('Y-m-d');
						$model->save(false);
						
						
						$appset=Settingsfirm::model()->find();
					
						$d=$model;
							
						$transaction->commit();
													$rtds=MyTestdata::gettestdata($d);

$fileulr=Yii::app()->basePath."/../../pdf/testreport/".$rtds->LabNo;
if (!file_exists($fileulr)) {
    mkdir($fileulr, 0777, true);
}
//$this->_sendResponse(401, CJSON::encode($rtds));
$url=Yii::app()->params['base-url']."pdf/testreport/".$rtds->LabNo."/".$rtds->TestNo."-".$rtds->TNo.".pdf";
$qrimg=Yii::app()->params['base-url']."pdf/testreport/".$rtds->TestNo."-".$rtds->TNo.".png";

Yii::app()->qrcode->create($url,Yii::app()->basePath."/../../pdf/testreport/".$rtds->LabNo."/".$rtds->TestNo."-".$rtds->TNo.".png");

				
				 					
require_once(yii::app()->basePath . '/extensions/tcpdf/tcpdf.php');
  $pdf = new 
   MYPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT, true,'UTF-8',false);
   $pdf->SetMargins(15, 10, 10, true);
    $pdf->SetPrintHeader(false);
  $pdf->SetPrintFooter(false);
  $pdf->setHeaderData('',0,'','',array(0,0,0), array(255,255,255) );  
  $pdf->SetFont( 'times', '', 10);
  $pdf->AddPage();
  
  $pdf->setListIndentWidth(2);
  $msg=MyFunctions::gettestui($rtds,$pdf);
 
 
 
  $pdf->writeHTML($msg,true, false, true, false, '');
				

  
 // $pdf->IncludeJS($js);
  $pdf->Output ( yii::app()->basePath."/../../pdf/testreport/".$rtds->LabNo."/".$rtds->TestNo."-".$rtds->TNo.".pdf", 'F' ); 
			
							
						
							$this->_sendResponse(200, CJSON::encode("approved"));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
			   
							break;					
				
	
			
							
							
				 case 'testobsupdate':
						$transaction=$model->dbConnection->beginTransaction();
				try
					{
						//$stdsub=Substandards::model()->findByPk($data['basic']['SubStdId']);
						
						$remark="Failed";
						$status="pending";
						
								$remark=$data['basic']['Remark'];
							
						if($remark==='Failed')
						{
							$status="failed";
						}	
						if($remark==='Passed')
						{
							$status="complete";
						}			
						
						
						$model->Note=$data['basic']['Note'];
						$model->TNote=$data['basic']['TNote'];
						$model->TestDate=$data['basic']['TestDate'];
						$model->Remark=$remark;
						$model->LastModified=date('Y-m-d H:i:s');
						$model->TestedBy=$data['ModifiedBy'];	
						$model->ModifiedBy=$data['ModifiedBy'];
						$model->Status=$status;
						$model->RevNo=empty($model->RevNo)?$model->test->RevNo:$model->RevNo;
						$model->RevDate=empty($model->RevDate)?$model->test->RevDate:$model->RevDate;
						$model->FormatNo=empty($model->FormatNo)?$model->test->FormatNo:$model->FormatNo;
						$model->save(false);
						
						
						
						foreach($data['basic']['TopBasicParameters'] as $d)
						{
							if(isset($d['Id']) && !empty($d['Id']))
							{
								$sd=Rirtestbasic::model()->findByPk($d['Id']);
								
								
							}
							else
							{
								$sd=new Rirtestbasic;
								$sd->RTID=$model->getPrimaryKey();
								$sd->TBPID=$d['TBPID'];
								
							}
							
							
							$sd->BValue=isset($d['BValue'])?$d['BValue']:null;
							$sd->save(false);
						}
						
						foreach($data['basic']['BottomBasicParameters'] as $d)
						{
							if(isset($d['Id']) && !empty($d['Id']))
							{
								$sd=Rirtestbasic::model()->findByPk($d['Id']);
								
								
							}
							else
							{
								$sd=new Rirtestbasic;
								$sd->RTID=$model->getPrimaryKey();
								$sd->TBPID=$d['TBPID'];
								
							}
							
							
							$sd->BValue=isset($d['BValue'])?$d['BValue']:null;
							$sd->save(false);
						}
						
						$testUid=$model->TUID;
						switch($testUid)
						{
							
							case 'IRK':
							//$this->_sendResponse(401, CJSON::encode($data['basic']['Parameters']));
							foreach($data['basic']['Parameters'] as $key => $value)
						{
							$d=$value;
							if($key==='Obs')
							{
									if(isset($d['Id']) && !empty($d['Id']))
								{
									$sd=Rirtestobs::model()->findByPk($d['Id']);
								}
								else
								{
									$sd=new Rirtestobs;
									$sd->RTID=$model->getPrimaryKey();
									$sd->TPID=$d['TPID'];
									
								}
								
								
								$sd->save(false);
								
								//----Observations
								
								foreach($d['Values'] as $v)
							{
								if(isset($v['Id']) && !empty($v['Id']))
								{
									$rv=Rirtestobsvalue::model()->findByPk($v['Id']);
								}
								else
								{
									$rv=new Rirtestobsvalue;
									$rv->RTOBID=$sd->getPrimaryKey();									
								}
								$rv->VType=isset($v['VType'])?$v['VType']:null;
								$rv->Value=isset($v)?json_encode($v):null;									
								$rv->save(false);
							}
								
									
							
								
							}
							else
							{
							if(isset($d['Id']) && !empty($d['Id']))
							{
								$sd=Rirtestobs::model()->findByPk($d['Id']);
							}
							else
							{
								$sd=new Rirtestobs;
								$sd->RTID=$model->getPrimaryKey();
								$sd->TPID=$d['TPID'];
								
							}
							
							$sd->save(false);
							
							//----Observations
							
							
							foreach($d['Values'] as $v)
							{
								if(isset($v['Id']) && !empty($v['Id']))
								{
									$rv=Rirtestobsvalue::model()->findByPk($v['Id']);
								}
								else
								{
									$rv=new Rirtestobsvalue;
									$rv->RTOBID=$sd->getPrimaryKey();									
								}
								$rv->VType=isset($v['VType'])?$v['VType']:null;
								$rv->Value=isset($v['Value'])?$v['Value']:null;									
								$rv->save(false);
							}
							}
							//----If formula param
							// if($d['FormVal'])
							// {
								
							// }
						}
					 
							break;
							
							
							case 'IRW':
							case 'CASE':
							case 'RBHARD':
								case 'RCHARD':
								case 'MVHARD':
								case 'VHARD':
								case 'BHARD':
								foreach($data['basic']['Parameters'] as $d)
						{
							if(isset($d['Id']) && !empty($d['Id']))
							{
								$sd=Rirtestobs::model()->findByPk($d['Id']);
							}
							else
							{
								$sd=new Rirtestobs;
								$sd->RTID=$model->getPrimaryKey();
								$sd->TPID=$d['TPID'];
								
							}
							
							//$sd->TMID=isset($d['TMID'])?$d['TMID']:null;
							//$sd->Value=isset($d['Value'])?$d['Value']:null;
							$sd->save(false);
							
							//----Observations
							
							
							
								if(isset($d['Values']['Id']) && !empty($d['Values']['Id']))
								{
									$rv=Rirtestobsvalue::model()->findByPk($d['Values']['Id']);
								}
								else
								{
									$rv=new Rirtestobsvalue;
									$rv->RTOBID=$sd->getPrimaryKey();									
								}
								$rv->VType=null;
								$rv->Value=json_encode($d['Values']['Values']);									
								$rv->save(false);
							
							
							//----If formula param
							// if($d['FormVal'])
							// {
								
							// }
						}
					 break;
							
							default:
								foreach($data['basic']['Parameters'] as $d)
						{
							if(isset($d['Id']) && !empty($d['Id']))
							{
								$sd=Rirtestobs::model()->findByPk($d['Id']);
							}
							else
							{
								$sd=new Rirtestobs;
								$sd->RTID=$model->getPrimaryKey();
								$sd->TPID=$d['TPID'];
								
							}
							
							$sd->SpecMin=isset($d['SpecMin'])?$d['SpecMin']:null;
							$sd->SpecMax=isset($d['SpecMax'])?$d['SpecMax']:null;
							//$sd->Value=isset($d['Value'])?$d['Value']:null;
							$sd->save(false);
							
							//----Observations
							
							
							foreach($d['Values'] as $v)
							{
								if(isset($v['Id']) && !empty($v['Id']))
								{
									$rv=Rirtestobsvalue::model()->findByPk($v['Id']);
								}
								else
								{
									$rv=new Rirtestobsvalue;
									$rv->RTOBID=$sd->getPrimaryKey();									
								}
								$rv->VType=isset($v['VType'])?$v['VType']:null;
								$rv->Value=isset($v['Value'])?$v['Value']:null;									
								$rv->save(false);
							}
							
							//----If formula param
							// if($d['FormVal'])
							// {
								
							// }
						}
					 
							
							break;
							
						}
						

//---Image modification
	
							if(count($data['basic']['delfiles']) >0)
							{
							$response = [];	
								foreach($data['basic']['delfiles'] as $df)
								{
									
									$document_root = $_SERVER['DOCUMENT_ROOT'];
									$relative_path = '/tcrandack/images/testuploads/files/'.$df['name'];
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

								$up=Rirtestuploads::model()->findByPk($df['id']);
								
								if(!empty($up))
								{
									$up->delete();
								}
								
								

								}
							

							}
						

						//---Send email
							
								$emails=[];
								// $uir2s=Userapppermission::model()->findAll(array('condition'=>' SectionId="5" AND A="true"'));
								// foreach($uir2s as $ur)
								// {
									// $emails[]=$ur->user->Email;
								// }
								
							$uas=Appsections::model()->find(['condition'=>'Others=:oth','params'=>[':oth'=>$model->TestId]]);
								
								$testdata=(object)array('Id'=>$model->Id,'SampleName'=>$model->rIR->SampleName,
								'TestedOn'=>$model->LastModified,'BatchCode'=>$model->rIR->BatchCode,'TestId'=>$model->TestId,'SecId'=>$uas->Id,
								'Customer'=>empty($model->rIR->CustomerId)?null:$model->rIR->customer->Name,'ReqDate'=>$model->ReqDate,
								'LabNo'=>$model->rIR->LabNo,'TestName'=>$model->TestName,'SectionId'=>5);
								$edata="saved";//MyFunctions::sendmail("approve",$emails,$testdata);
							
						
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($edata));
						
					}
					catch(Exception $e)
					{
						$this->_sendResponse(401, CJSON::encode($e->getMessage()));
							  $transaction->rollback();
							
					}
			   
							break;			
							
				case 'otherupdate':	
					$transaction=$model->dbConnection->beginTransaction();
						try
						{
							$stdsub=Substandards::model()->findByPk($data['basic']['SubStdId']);
						
							$remark="Failed";
							$status="pending";
						
								$remark=$data['basic']['Remark'];
							
						if($remark==='Failed')
						{
							$status="failed";
						}	
						if($remark==='Passed')
						{
							$status="complete";
						}			
						
						$appsec=Appsections::model()->find(array('condition'=>'Others=:key','params'=>array(':key'=>$model->test->Keyword)));
							
						
						$uir=Userapppermission::model()->find(array('condition'=>'UserId =:uid AND SectionId=:secid',
							'params'=>array(':uid'=>$data['ModifiedBy'],':secid'=>$appsec->Id),));
							if(!empty($uir))
							{
								if($uir->C==='true')
								{
									$model->TestedBy=$data['ModifiedBy'];	
								}
							}							
					
						//$this->_sendResponse(401, CJSON::encode($uir));
						//$model->BatchCode=$data['basic']['BatchCode'];
						//	$model->Description=isset($data['basic']['Description'])?$data['basic']['Description']:"";
						$model->Note=$data['basic']['Note'];
						$model->TestDate=$data['basic']['TestDate'];
						$model->Remark=$remark;
						$model->LastModified=date('Y-m-d H:i:s');
						$model->ModifiedBy=$data['ModifiedBy'];
						$model->Status=$status;
							$model->RevNo=empty($model->RevNo)?$model->test->RevNo:$model->RevNo;
						$model->RevDate=empty($model->RevDate)?$model->test->RevDate:$model->RevDate;
						$model->FormatNo=empty($model->FormatNo)?$model->test->FormatNo:$model->FormatNo;
						$model->save(false);
						
							
							
						foreach($data['basic']['obbasic'] as $d)
						{
							
							
							if(!empty($d['Id']) || isset($d['Id']))
							{
								$sd=Othbasic::model()->findByPk($d['Id']);
							}
							else
							{
								$sd=new Othbasic;
								$sd->RirTestId=$model->getPrimaryKey();
								$sd->BParamId=$d['BParamId'];
							}	
							
							
							
							$sd->BValue=isset($d['BValue'])?$d['BValue']:null;
							$sd->save(false);
							
						}
						
						foreach($data['basic']['observations'] as $d)
						{
							
							
							if(!empty($d['Id']) || isset($d['Id']))
							{
								$sd=Othobs::model()->findByPk($d['Id']);
							}
							else
							{
								$sd=new Othobs;
								$sd->RirTestId=$model->getPrimaryKey();
								$sd->PId=$d['PId'];
							}	
							
							
							$sd->TMId=isset($d['TMId'])?$d['TMId']:null;
							$sd->Value=isset($d['Value'])?$d['Value']:null;
							$sd->save(false);
							$sd->save(false);
						}
						
						
						//---Send email
							if($model->Status==="complete")
							{
								$emails=[];
								$uir2s=Userapppermission::model()->findAll(array('condition'=>' SectionId="5" AND A="true"'));
								foreach($uir2s as $ur)
								{
									$emails[]=$ur->user->Email;
								}
								
								$uas=Appsections::model()->find(['condition'=>'Others=:oth','params'=>[':oth'=>$model->TestId]]);
								
								$testdata=(object)array('Id'=>$model->Id,'PartName'=>$model->rIR->PartName,
								'TestedOn'=>$model->LastModified,'BatchCode'=>$model->rIR->BatchCode,'TestId'=>$model->TestId,'SecId'=>$uas->Id,
								'Customer'=>empty($model->rIR->CustomerId)?null:$model->rIR->customer->Name,'ReqDate'=>$model->ReqDate,
								'LabNo'=>$model->rIR->LabNo,'TestName'=>$model->TestName,'SectionId'=>5);
								$edata=MyFunctions::sendmail("approve",$emails,$testdata);
							}
						
						$transaction->commit();
							$this->_sendResponse(200, CJSON::encode($edata));
						
					}
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($data));
					}
			   
							break;
							
				
							
				case 'rirupdate':
					$transaction=$model->dbConnection->beginTransaction();
					try
					{
$u=MyFunctions::gettokenuser();						
						$set=Settingslab::model()->find(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
						$model->save(false);						
						
							$rirextras=Rirextras::model()->find(['condition'=>'RIRId=:ririd','params'=>[':ririd'=>$model->getPrimaryKey()]]);
							
							if(empty($rirextras))
							{
								$rirextras =new Rirextras;
								$rirextras->Id=null;		
								$rirextras->RIRId=$model->getPrimaryKey();
							}
														
							foreach($data['extras'] as $var=>$value)
							  {
								// Does the model have this attribute? If not raise an error
								if($rirextras->hasAttribute($var))
									$rirextras->$var = $value;
							  }
												  
							
							$rirextras->save(false);
				  
				 
				  $qsubtotal=0;
				   $mdstdsid=$model->MdsTdsId;
				   
				   
				   	foreach($data['deltests'] as $dt)
							{
								$delrt=Rirtestdetail::model()->findByPk($dt['RTID']);
								if(!empty($delrt))
								{
									$delrt->delete();
								}
							}
				   
						foreach($data['applicabletest'] as $t)
							{
								$rtds=null;
								if(isset($t['RTID']))
								{
									$rtds=Rirtestdetail::model()->findByPk($t['RTID']);
								}
								
								if(empty($rtds))
								{
									$rtds=new Rirtestdetail;
									$testno=$set->LastTestNo;
										
										
										
										
											$yr=date("y");
												$month=date('m');
												$gy=substr($testno, 0 , 2);
												$gm=substr($testno, 2 , 2);
										if($yr===$gy)
										{
											//---increment
											if($gm===$month)
												{
													//---Increment
													$lasttestno=substr($testno, 4);
													$lasttestno =  (int)$lasttestno;
													$newtestno=(int)$lasttestno+1;
												}
												else
												{
													$lasttestno='0001';
													$newtestno =  (int)$lasttestno;
												}
											
										}
										else
										{
											if($gm===$month)
												{
													//---Increment
													$lasttestno=substr($testno, 4);
													$lasttestno =  (int)$lasttestno;
													$newtestno=(int)$lasttestno+1;
												}
												else
												{
													$lasttestno='0001';
													$newtestno =  (int)$lasttestno;
												}
										}	
											
											
										
										$newtestno=str_pad($newtestno, 4, "0", STR_PAD_LEFT);
										//$testno=$alpha.$testno;
										$set->LastTestNo=$yr.$month.$newtestno;
										$set->save(false);
										$rtds->TestNo=$yr.$month.$newtestno;
										
										$rtds->TestId=$t['TestId'];
										$mt=Tests::model()->findByPk($t['TestId']);
										$rtds->RIRId=$model->getPrimaryKey();
										
										
										$dat=$model->CreationDate;
										$time = strtotime($dat);
										$rtds->Note=empty($mt->DefaultNote)?$set->DefaultTestNote:$mt->DefaultNote;
										$newformat = date('Y-m-d',$time);
										$rtds->CreationDate= date('Y-m-d');//$newformat;
									
										
																	
										$rtds->TestName=$t['TestName'];	
										$rtds->TSeq=$t['TSeq'];	
										$rtds->TUID=$t['TUID'];	
								}
								
										$price=0;	
										
																	
										
										$rtds->TMID=isset($t['TMID'])?$t['TMID']:null;					
										$rtds->SSID=isset($t['SSID'])?$t['SSID']:null;
										$rtds->PlanId=isset($t['PlanId'])?$t['PlanId']:null;
										$rtds->ExtraInfo=isset($t['ExtraInfo'])?$t['ExtraInfo']:null;																	
										$rtds->ReqDate=$t['ReqDate'];
										$rtds->save(false);
										
									
									if(!empty($t['Labs']))
									{
										foreach($t['Labs'] as $c)
										{
											$sc=Rirtestlabs::model()->find(['condition'=>'RTID=:rtid AND LabId=:labid',
											'params'=>[':rtid'=>$rtds->getPrimaryKey(),':labid'=>$c]]);
											if(empty($sc))
											{
											$sc=new Rirtestlabs;
											$sc->RTID=$rtds->getPrimaryKey();
											$sc->LabId=$c;
											$sc->save(false);
											}
										}
									}
									
									//---get mds Test
									
									$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$rtds->TestId]]);
									
								//---Add parameters on sample save	

										$testbasicparams=$rtds->test->testbasicparams;
										foreach($testbasicparams as $e)
											{
												$sbd=Rirtestbasic::model()->find(['condition'=>'RTID=:rtid AND TBPID=:tbpid',
													'params'=>[':rtid'=>$rtds->getPrimaryKey(),':tbpid'=>$e->Id]]);
													if(empty($sbd))
													{
												$sbd=new Rirtestbasic;
													$sbd->RTID=$rtds->getPrimaryKey();
													$sbd->TBPID=$e->Id;
													$sbd->save(false);
													}
											}								
										
										$testobsparams=$rtds->test->testobsparams;
										
									
									
											foreach($testobsparams as $e)
											{
													$sd=Rirtestobs::model()->find(['condition'=>'RTID=:rtid AND TPID=:tpid',
													'params'=>[':rtid'=>$rtds->getPrimaryKey(),':tpid'=>$e->Id]]);
													if(empty($sd))
													{
														$sd=new Rirtestobs;
														$sd->RTID=$rtds->getPrimaryKey();
														$sd->TPID=$e->Id;
														//$sd->TMID=$rtds->TMID;	
															$sd->MR=$e->MR;		

														if(!is_null($rtds->SSID))
														{
															if(!empty($mdstdstest))
															{
																$mdstdsobs=Mdstdstestobsdetails::model()->find(['condition'=>'MTTID=:mttid AND PID=:pid',
																'params'=>[':mttid'=>$mdstdstest->Id,':pid'=>$e->Id]]);
																
																if(!empty($mdstdsobs))
																{
																	$sd->SpecMin=empty($mdstdsobs)?null:$mdstdsobs->SpecMin;
																	$sd->SpecMax=empty($mdstdsobs)?null:$mdstdsobs->SpecMax;
																}
															}
															else
															{
																$ssdt=Stdsubdetails::model()->find(array('condition'=>'SubStdId =:ssid AND PId=:pid',
														'params'=>array(':ssid'=>$rtds->SSID,':pid'=>$e->Id)));	
																if(!empty($ssdt))
																{
																	$sd->SpecMin=empty($ssdt)?null:(float)$ssdt->SpecMin;
																	$sd->SpecMax=empty($ssdt)?null:(float)$ssdt->SpecMax;
																}	
															}
															
															
														}													
																									
														$sd->save(false);	
													}
											}
											
											
								
							}
							
							
							
							$transaction->commit();
							$this->_sendResponse(200, CJSON::encode("success"));
						
					}
					catch(Exception $e)
					{
						$this->_sendResponse(401, CJSON::encode($e->getMessage()));
							  $transaction->rollback();
							
					}
				break;
				
				
					
				case 'rirtestupdate':
					$transaction=$model->dbConnection->beginTransaction();
				try
					{
						//$model->CreationDate=date('Y-m-d H:i:s');
						
						$set=Settings::model()->find(['condition'=>'CID=:cid',
	'params'=>[':cid'=>$u->CID]]);
						
							foreach($data['applicabletest'] as $t)
							{
								if($t['Applicable']==='true')
								{
									$rtds=Rirtestdetail::model()->find(array('condition'=>'TestId=:id  AND RIRId=:rir',
										 'params'=>array(':id'=>$t['TestId'],':rir'=>$model->Id),));
									if(empty($rtds))
									{
										$rtds=new Rirtestdetail;
										$rtds->TestId=$t['Id'];
										$dat=$model->CreationDate;
										$time = strtotime($dat);
										$rtds->Note=$set->DefaultNote;
										$newformat = date('Y-m-d',$time);
										$rtds->CreationDate=$newformat;
									}
									else
									{
									
										$rtds->TestId=$t['TestId'];
									}
									$rtds->RIRId=$model->getPrimaryKey();
									$rtds->RirNo=$model->RirNo;
									
									$rtds->TestName=$t['TestName'];
									$rtds->Applicable=$t['Applicable'];
									$rtds->TestMethod=isset($t['TestMethod']['Method'])?$t['TestMethod']['Method']:"";
									$rtds->TestMethodId=isset($t['TestMethod']['Id'])?$t['TestMethod']['Id']:"";
									$rtds->StandardId=isset($t['StandardId'])?$t['StandardId']:"";
									$rtds->SubStdId=isset($t['SubStdId'])?$t['SubStdId']:"";
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
								$rextds=Rirexttestdetail::model()->find(array('condition'=>'ExtTestId=:id  AND RIRId=:rir',
										 'params'=>array(':id'=>$t['Id'],':rir'=>$model->Id),));
									if(empty($rextds))
									{
										$rextds=new Rirexttestdetail;
										$rextds->ExtTestId=$t['Id'];
									}
									
									
									$rextds->RIRId=$model->getPrimaryKey();
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
					catch(Exception $e)
					{
						
							  $transaction->rollback();
							
					}                    
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
				
				case 'droppdir':
					$model = Pdirbasic::model()->findByPk($_GET['id']);                    
					break;
				
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
