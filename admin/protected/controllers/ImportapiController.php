<?php

class ImportapiController extends Controller
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
			//$this->_checkAuth();	
			// Get the respective model instance
			switch($_GET['model'])
			{
				
				
				
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
						sprintf('No items where found for model <b>%s</b>', $_GET['model']) );
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
				
				
			case 'importstandards': $model=new Standards;break;	
			case 'importsubstd': $model=new Substandards;break;	
			case 'importtestmethod': $model=new Testmethods;break;	
			case 'importmdstds':$model=new Mdstds;break;
			case 'importtestparams': $model=new Testobsparams;break;	
				case 'importsamples':$model=new Receiptir;break;
				case 'importtensiles':
				case 'importimpacts':
case 'importproofloads':

case 'importhardness':
case 'importmicrocase':	
case 'importmicrocoat':	 
   case 'importmicrodcarb':	
   case 'importtorques':	
case 'importcarb':	
case 'importcase':	  
case 'importmstruct':	  
case 'importgrain':	  
case 'importirk':	  
case 'importirw':	  
case 'importthread':	  
case 'importwedge':	  
case 'importhet':	
case 'importshear':	
				case 'importchemicals':$model=new Rirtestdetail;break;		   
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
			     
			   case 'importtestparams':
			   case 'importtestmethod':
			   case 'importsamples':
			   case 'importmdstds':
			   case 'importstandards': 	
			case 'importsubstd': 
			case 'importtensiles':
case 'importchemicals':
case 'importimpacts':
case 'importproofloads':
	
case 'importhardness':	
   case 'importmicrodcarb':	
case 'importmicrocase':	
case 'importmicrocoat':	
 
case 'importtorques':	
case 'importcarb':	
case 'importcase':	  
case 'importmstruct':	  
case 'importgrain':	  
case 'importirk':	  
case 'importirw':	  
case 'importthread':	  
case 'importwedge':	  
case 'importhet':	
case 'importshear':	
  




			   case 'addquote': 
			   case 'addinvoice': break;
			  		
								
					 
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
			   
			   case 'importsamplesold':
			//	$transaction=$model->dbConnection->beginTransaction();
					try{      
$count=0;					
							foreach($data['Products'] as $p)
							{
								
								$rir=Receiptir::model()->find(array('condition'=>'LabNo=:labno',
								'params'=>array(':labno'=>$p['LabNo'],)));
								if(!empty($rir))
								{
									$batchcode=null;
									$batchno=null;
									if(isset($p['BatchCode']))
									{
									$batchstring=$p['BatchCode'];
									$batch = explode('-', $batchstring);
									$batchcode=isset($p['BatchCode'])?$batch[0]:null;
									$batchno="";
									if(count($batch)>1)
									{
									$batchno=$batch[1];	
									}
									}
									$rir=new Receiptir;
									$rir->LabNo=$p['LabNo'];
									$rir->BatchCode=$batchcode;
									$rir->BatchNo=$batchno ;	
									$rir->HeatNo=isset($p['HeatNo'])?$p['HeatNo']:null ;		
									$rir->SampleName=isset($p['SampleName'])?$p['SampleName']:null ;											
									$rir->Description=isset($p['Description'])?$p['Description']:null ;										  
									$rir->RefPurchaseOrder=isset($p['RefPurchaseOrder'])?$p['RefPurchaseOrder']:null ;	

										if(isset($p['MDS']) && !is_null($p['MDS']))
										{
												$rir->IsMdsTds="mds";
													$mds=Mdstds::model()->find(array('condition'=>'LOWER(Type)="mds" AND LOWER(No)=:no',
								'params'=>array(':no'=>strtolower($p['MDS']))));
												if(!empty($mds))
												{
													$rir->MdsTdsId=$mds->getPrimaryKey() ;	
												}
										}
									
								if(isset($p['TDS']) && !is_null($p['TDS']))
										{
												$rir->IsMdsTds="tds";
													$mds=Mdstds::model()->find(array('condition'=>'LOWER(Type)="tds" AND LOWER(No)=:no',
								'params'=>array(':no'=>strtolower($p['TDS']))));
												if(!empty($mds))
												{
													$rir->MdsTdsId=$mds->getPrimaryKey() ;	
												}
										}
									
									
									if(isset($p['Supplier']) && !is_null($p['Supplier']))
									{
										$sup=Suppliers::model()->find(array('condition'=>'LOWER(Name)=:no',
								'params'=>array(':no'=>strtolower($p['Supplier']))));
									if(empty($sup))
									{
										$sup=new Suppliers;
										$sup->Name=$p['Supplier'];
										$sup->save(false);
									}										
									$rir->SupplierId=$sup->getPrimaryKey() ;
									}
									if(isset($p['Customer']) && !is_null($p['Customer']))
									{

									$cust=Customerinfo::model()->find(array('condition'=>'LOWER(Name)=:no',
								'params'=>array(':no'=>strtolower($p['Customer']))));
									if(empty($cust))
									{
										$cust=new Customerinfo;
										$cust->Name=$p['Customer'];
										$cust->save(false);
									}										
									$rir->CustomerId=$cust->getPrimaryKey() ;
									}
									
									$rir->RegDate=date('Y-m-d H:i:s',strtotime($p['GrinDt']));
									$rir->CreationDate=date('Y-m-d H:i:s');
									
									// $user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								// 'params'=>array(':no'=>strtolower($p['EnteredBy']))));
								if(!empty($user))
								{
									$rir->EnteredBy=14;//$user->Id;
								}
									
									$rir->IndId="1";									  
									$rir->Status="Pending";
									$rir->save(false);								
									  
									  $grinno=isset($p['GrinNo'])?$p['GrinNo']."  ".(isset($p['GrinDt'])?$p['GrinDt']:null ):null ;
									  $cat1=isset($p['Cat1'])?$p['Cat1']:null ;
									   $cat2=isset($p['Cat2'])?$p['Cat2']:null ;
									    $cat3=isset($p['Cat3'])?$p['Cat3']:null ;
									  $grade=$cat1." ".$cat2." ".$cat3; 
									  $rirextra=new Rirextras;
									  $rirextra->RIRId=$rir->getPrimaryKey();
									  $rirextra->GrinNoDate=$grinno ;
									   $rirextra->Quantity=isset($p['Quantity'])?$p['Quantity']:null ;
									    $rirextra->InvoiceNo=isset($p['InvoiceNo'])?$p['InvoiceNo']:null ;
										 $rirextra->InvoiceDate=isset($p['InvoiceDate'])?$p['InvoiceDate']:null ;
										 $rirextra->Grade=$grade ;
										 $rirextra->Condition=isset($p['Condition'])?$p['Condition']:null ;
										 $rirextra->TCNo=isset($p['TCNo'])?$p['TCNo']:null ;
										 $rirextra->RouteCardNo=isset($p['RouteCardNo'])?$p['RouteCardNo']:null ;
										 $rirextra->save(false);
								}
								else
								{
									
									if(isset($p['MDS']) && !is_null($p['MDS']))
										{
												$rir->IsMdsTds="mds";
													$mds=Mdstds::model()->find(array('condition'=>'LOWER(Type)="mds" AND LOWER(No)=:no',
								'params'=>array(':no'=>strtolower($p['MDS']))));
												if(!empty($mds))
												{
													$rir->MdsTdsId=$mds->getPrimaryKey() ;	
												}
										}
										
										if(isset($p['TDS']) && !is_null($p['TDS']))
										{
												$rir->IsMdsTds="tds";
													$mds=Mdstds::model()->find(array('condition'=>'LOWER(Type)="tds" AND LOWER(No)=:no',
								'params'=>array(':no'=>strtolower($p['TDS']))));
												if(!empty($mds))
												{
													$rir->MdsTdsId=$mds->getPrimaryKey() ;	
												}
										}
									
							
								
										
											$rir->HeatNo=isset($p['HeatNo'])?$p['HeatNo']:null ;
											
										$rir->save(false);	

									
											 
											
								}
								
							}
						
                  
                            
                      // $transaction->commit();
                $this->_sendResponse(200, CJSON::encode("Updated-".$count));
				}
					catch(Exception $e)
					{
						
							$transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
				
				
				case 'importsamples':
			//	$transaction=$model->dbConnection->beginTransaction();
					try{      
$count=0;					
							foreach($data['Products'] as $p)
							{
								
								$rir=Receiptir::model()->find(array('condition'=>'LabNo=:labno',
								'params'=>array(':labno'=>$p['LabNo'],)));
								if(!empty($rir))							
								{
									
									
									
									if(isset($p['IsMdsTds']) && !is_null($p['IsMdsTds']))
										{
											$rir->IsMdsTds=$p['IsMdsTds'];
											if($rir->IsMdsTds==='mds')
											{
												$getmds = Mdstds::model()->find(array(
													'condition' => 'LOWER(Type)="mds" AND LOWER(TRIM(No))=:no',  
													'params' => array(':no' => strtolower(trim($p['No'])))  
												));
													if(!empty($getmds))
												{
													$rir->MdsTdsId=$getmds->getPrimaryKey();	
												}
											}
											
											if($rir->IsMdsTds==='tds')
											{
												$gettds = Mdstds::model()->find(array(
													'condition' => 'LOWER(Type)="tds" AND LOWER(TRIM(No))=:no',  
													'params' => array(':no' => strtolower(trim($p['No'])))  
												));
													if(!empty($gettds))
												{
													$rir->MdsTdsId=$gettds->getPrimaryKey();	
												}
											}									
										
$count++;
											
										}
																
										
											$rir->HeatNo=isset($p['HeatNo'])?$p['HeatNo']:null ;											
										$rir->save(false);	
									
											 
											
								}
								
							}
						
                  
                            
                      // $transaction->commit();
                $this->_sendResponse(200, CJSON::encode("Updated-".$count));
				}
					catch(Exception $e)
					{
						
							$transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
			   	
case 'importcarb':	//10
try{
						
						function savebaseparam($param,$pval,$rtid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>10,':psym'=>$param]]);
										
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->getPrimaryKey();
								$sd->BValue=$pval;
								$sd->save(false);
							
							
						
										}
								
								
       			 				function saveobsparam($el,$elval,$rtid,$min,$max)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>10,':psym'=>$el]]);
										
											$sd=Rirtestobs::model()->find(['condition'=>'RTID=:rtid AND TPID=:tpid',
										'params'=>[':rtid'=>$rtid,':tpid'=>$e->Id]]);
										
													if(empty($sd))
													{
														$sd=new Rirtestobs;
																$sd->RTID=$rtid;
																$sd->TPID=$e->Id;
																$sd->SpecMin=$min;
																$sd->SpecMax=$max;												
																$sd->save(false);
													}
													
													
													
													$rv=new Rirtestobsvalue;
													$rv->RTOBID=$sd->getPrimaryKey();								
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
										}
								
								
								$test=Tests::model()->findByPk(10);
							foreach($data['Products'] as $p)
							{
								
								$rir=Receiptir::model()->find(array('condition'=>'LabNo=:labno',
								'params'=>array(':labno'=>$p['LabNo'],)));
								
								
								if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'])));
									if(empty($rt))
									{
										$transaction=$rir->dbConnection->beginTransaction();
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TUID="CARBDC";
										
										$substd=Substandards::model()->find(array('condition'=>'LOWER(Grade)=:no',
								'params'=>array(':no'=>strtolower($p['SubStd']))));
										
										if(!empty($substd))
										{
											$rt->SSID=$substd->Id;
										}
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ULRNo=isset($p['ULRNo'])?$p['ULRNo']:null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
										$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['TestedBy']))));
								if(!empty($user))
								{
									$rt->TestedBy=$user->Id;
									
								}
										}
										
										if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
								$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['ApprovedBy']))));
								if(!empty($user))
								{
									$rt->ApprovedBy=$user->Id;
								}
										}
										
										$rt->ApprovedDate=isset($p['ApprovedDate'])?date('Y-m-d H:i:s',strtotime($p['ApprovedDate'])):null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										$rt->save(false);
										
										$rtid=$rt->getPrimaryKey();
										
										 
										
										
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Equipment";
											$pel=isset($p['Equipment'])?$p['Equipment']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="HTBatchNo";
											$pel=isset($p['HTBatchNo'])?$p['HTBatchNo']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Indenter";
											$pel=isset($p['Indenter'])?$p['Indenter']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Load";
											$pel=isset($p['Load'])?$p['Load']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Temp";
											$pel=isset($p['Temp'])?$p['Temp']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="LimitMin";
											$pel=isset($p['LimitMin'])?$p['LimitMin']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="LimitMax";
											$pel=isset($p['LimitMax'])?$p['LimitMax']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="HV";
											$pel=isset($p['HV1'])?$p['HV1']:null ;
											$min=null;
											$max=null;											
											saveobsparam("HV",$pel,$rtid,$min,$max);									
										
											$el="HV";
											$pel=isset($p['HV2'])?$p['HV2']:null ;
											$min=null;
											$max=null;											
											saveobsparam("HV",$pel,$rtid,$min,$max);									
										
											$el="HV";
											$pel=isset($p['HV3'])?$p['HV3']:null ;
											$min=null;
											$max=null;											
											saveobsparam("HV",$pel,$rtid,$min,$max);									
									
										
										
										if(isset($p['Uploads']))
										{
											if(!is_null($p['Uploads']))
											{
												foreach(json_decode($p['Uploads']) as $u)
												{
													$rtu=new Rirtestuploads;
													$rtu->name=$u->name;
													$rtu->url=$u->url;
													$rtu->rirtestid=$rtid;
													$rtu->save(false);
												}
											}
										}
										
										$transaction->commit();
									}
									else
									{
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->save(false);
									}
								}
								
								
								
							}
						
                  
                            
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
			   
case 'importcase':	  //11
try{
						
						function savebaseparam($param,$pval,$rtid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>11,':psym'=>$param]]);
										
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->getPrimaryKey();
								$sd->BValue=$pval;
								$sd->save(false);
							
							
						
										}
								
								
       			 				function saveobsparam($el,$elval,$rtid,$min,$max)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>11,':psym'=>$el]]);
										
											$sd=Rirtestobs::model()->find(['condition'=>'RTID=:rtid AND TPID=:tpid',
										'params'=>[':rtid'=>$rtid,':tpid'=>$e->Id]]);
										
													if(empty($sd))
													{
														$sd=new Rirtestobs;
																$sd->RTID=$rtid;
																$sd->TPID=$e->Id;
																$sd->SpecMin=$min;
																$sd->SpecMax=$max;												
																$sd->save(false);
													}
													
													
													
													$rv=new Rirtestobsvalue;
													$rv->RTOBID=$sd->getPrimaryKey();								
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
										}
								
								
								$test=Tests::model()->findByPk(11);
							foreach($data['Products'] as $p)
							{
								
								$rir=Receiptir::model()->find(array('condition'=>'LabNo=:labno',
								'params'=>array(':labno'=>$p['LabNo'],)));
								
								
								if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'])));
									if(empty($rt))
									{
										$transaction=$rir->dbConnection->beginTransaction();
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TUID="CASE";
										
										$substd=Substandards::model()->find(array('condition'=>'LOWER(Grade)=:no',
								'params'=>array(':no'=>strtolower($p['SubStd']))));
										
										if(!empty($substd))
										{
											$rt->SSID=$substd->Id;
										}
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ULRNo=isset($p['ULRNo'])?$p['ULRNo']:null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
										$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['TestedBy']))));
								if(!empty($user))
								{
									$rt->TestedBy=$user->Id;
									
								}
										}
										
										if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
								$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['ApprovedBy']))));
								if(!empty($user))
								{
									$rt->ApprovedBy=$user->Id;
								}
										}
										
										$rt->ApprovedDate=isset($p['ApprovedDate'])?date('Y-m-d H:i:s',strtotime($p['ApprovedDate'])):null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										$rt->save(false);
										
										$rtid=$rt->getPrimaryKey();
										
										 
										
										
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Equipment";
											$pel=isset($p['Equipment'])?$p['Equipment']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="HTBatchNo";
											$pel=isset($p['HTBatchNo'])?$p['HTBatchNo']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Indenter";
											$pel=isset($p['Indenter'])?$p['Indenter']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Load";
											$pel=isset($p['Load'])?$p['Load']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Temp";
											$pel=isset($p['Temp'])?$p['Temp']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="LimitMin";
											$pel=isset($p['LimitMin'])?$p['LimitMin']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="LimitMax";
											$pel=isset($p['LimitMax'])?$p['LimitMax']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="CutPt";
											$pel=isset($p['CutPt'])?$p['CutPt']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
										
										
											$el="Obs";
											$pel=isset($p['Obs'])?$p['Obs']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
										
									
										if(isset($p['Uploads']))
										{
											if(!is_null($p['Uploads']))
											{
												foreach(json_decode($p['Uploads']) as $u)
												{
													$rtu=new Rirtestuploads;
													$rtu->name=$u->name;
													$rtu->url=$u->url;
													$rtu->rirtestid=$rtid;
													$rtu->save(false);
												}
											}
										}
										
										$transaction->commit();
									}
									else
									{
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->save(false);
									}
								}
								
								
								
							}
						
                  
                            
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
			   
case 'importmstruct':	  //14
try{
						
	function savebaseparam($param,$pval,$rtid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>14,':psym'=>$param]]);
										
										
										$sd=Rirtestbasic::model()->find(['condition'=>'TBPID=:tbid AND RTID=:rtid',
										'params'=>[':tbid'=>$tb->Id,':rtid'=>$rtid]]);
										if(empty($sd))
										{
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->Id;
										}
								$sd->BValue=$pval;
								$sd->save(false);
							
							
						
										}
								
								
       			 			function saveobsparam($el,$elval,$rtid,$min,$max)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>14,':psym'=>$el]]);
										
										
										$sd=Rirtestobs::model()->find(['condition'=>'TPID=:tpid AND RTID=:rtid',
										'params'=>[':tpid'=>$e->Id,':rtid'=>$rtid]]);
										if(empty($sd))
										{
												$sd=new Rirtestobs;
													$sd->RTID=$rtid;
													$sd->TPID=$e->Id;
													
										}
											$sd->SpecMin=$min;
													$sd->SpecMax=$max;												
													$sd->save(false);	
													
											$rv=Rirtestobsvalue::model()->find(['condition'=>'RTOBID=:rtobid ','params'=>[':rtobid'=>$sd->getPrimaryKey()]]);
													
													if(empty($rv))
													{
															$rv=new Rirtestobsvalue;
															$rv->RTOBID=$sd->getPrimaryKey();	
													}		
																			
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
										}
								
								
								$test=Tests::model()->findByPk(14);
							foreach($data['Products'] as $p)
							{
								
																$batchcode = null;
$batchno = null;

if (isset($p['BatchCode'])) {
    $batchstring = $p['BatchCode'];
    $batch = explode('-', $batchstring);
    $batchcode = $batch[0];  // Default assignment to batchcode

    if (count($batch) > 1) {
        $batchno = $batch[1];  // If there's a second part, assign to batchno
    }
}

// Only query if batchcode is set, and ensure batchno is included only if it's set
$criteria = array(
    'condition' => 'BatchCode=:bc' . ($batchno !== null ? ' AND BatchNo=:bn' : ''),
    'params' => array(':bc' => $batchcode)
);

if ($batchno !== null) {
    $criteria['params'][':bn'] = $batchno;
}

$rir = Receiptir::model()->find($criteria);
								
								
								
									if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$mdstdsid=$rir->MdsTdsId;										  
										$transaction=$rir->dbConnection->beginTransaction();
									
																		  
										
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq AND TestNo=:testno',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'],':testno'=>$p['TestNo'])));
									
									if(empty($rt))
									{
										
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TestNo=isset($p['TestNo'])?$p['TestNo']:null ;
										$rt->TUID="MSTRUCT";
										
										if(!empty($mdstdsid))
										{
										$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$rt->TestId]]);
									
									if(!empty($mdstdstest))
									{
											$rt->SSID=$mdstdstest->SSDID;
									}
										}
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
									
									$rt->TestedBy=25;
									
								
										}
										
											if($rt->Status==='complete')
										{
								
									$rt->ApprovedBy=20;
									
								
								
									$str="TC6567";
						$yr="24";
						$str1="1"; //--For multiple locations
						$input=$p['ULRNo'];//$model->Id;
						$str2=str_pad($input, 8, "0", STR_PAD_LEFT);
						$str3="F";
						$rt->ULRNo=$str.$yr.$str1.$str2.$str3;
								
								if ($rt->TestDate) {
											$testDate = new DateTime($rt->TestDate);
											$testDate->modify('+2 days');
											$rt->ApprovedDate = $testDate->format('Y-m-d');
										} else {
											$rt->ApprovedDate = null;  // If TestDate is not set, ApprovedDate will also be null
										}

										}
										
										
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										$rt->save(false);
									}
									else
									{
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TestNo=isset($p['TestNo'])?$p['TestNo']:null ;
										$rt->TUID="MSTRUCT";
										
										if(!empty($mdstdsid))
										{
										$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$rt->TestId]]);
									
									if(!empty($mdstdstest))
									{
											$rt->SSID=$mdstdstest->SSDID;
									}
										}
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
										
									$rt->TestedBy=25;
									
								
										}
										
											if($rt->Status==='complete')
										{
								
									$rt->ApprovedBy=20;
									
								
								
									$str="TC6567";
						$yr="24";
						$str1="1"; //--For multiple locations
						$input=$p['ULRNo'];//$model->Id;
						$str2=str_pad($input, 8, "0", STR_PAD_LEFT);
						$str3="F";
						$rt->ULRNo=$str.$yr.$str1.$str2.$str3;
								
								if ($rt->TestDate) {
											$testDate = new DateTime($rt->TestDate);
											$testDate->modify('+2 days');
											$rt->ApprovedDate = $testDate->format('Y-m-d');
										} else {
											$rt->ApprovedDate = null;  // If TestDate is not set, ApprovedDate will also be null
										}

										}
										
										
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										$rt->save(false);
									}
									
									
									$rtid=$rt->getPrimaryKey();
										
										 
										
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Orientation";
											$pel=isset($p['Orientation'])?$p['Orientation']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Etchant";
											$pel=isset($p['Etchant'])?$p['Etchant']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Magnification";
											$pel=isset($p['Magnification'])?$p['Magnification']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
																		
										
											$el="Temp";
											$pel=isset($p['Temp'])?$p['Temp']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
																			
										
											$el="Location";
											$pel=isset($p['Location'])?$p['Location']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Observation";
											$pel=isset($p['Observation'])?$p['Observation']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);		
									// if(isset($p['Uploads']))
										// {
											// if(!is_null($p['Uploads']))
											// {
												// foreach(json_decode($p['Uploads']) as $u)
												// {
													// $rtu=new Rirtestuploads;
													// $rtu->name=$u->name;
													// $rtu->url=$u->url;
													// $rtu->rirtestid=$rtid;
													// $rtu->save(false);
												// }
											// }
										// }
									$transaction->commit();
								}
								
								
								
							}
						
                  
                            
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
			   
case 'importgrain':	  //15
try{
						
						function savebaseparam($param,$pval,$rtid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>15,':psym'=>$param]]);
										
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->getPrimaryKey();
								$sd->BValue=$pval;
								$sd->save(false);
							
							
						
										}
								
								
       			 				function saveobsparam($el,$elval,$rtid,$min,$max)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>15,':psym'=>$el]]);
										
											$sd=Rirtestobs::model()->find(['condition'=>'RTID=:rtid AND TPID=:tpid',
										'params'=>[':rtid'=>$rtid,':tpid'=>$e->Id]]);
										
													if(empty($sd))
													{
														$sd=new Rirtestobs;
																$sd->RTID=$rtid;
																$sd->TPID=$e->Id;
																$sd->SpecMin=$min;
																$sd->SpecMax=$max;												
																$sd->save(false);
													}
													
													
													
													$rv=new Rirtestobsvalue;
													$rv->RTOBID=$sd->getPrimaryKey();								
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
										}
								
								
								$test=Tests::model()->findByPk(15);
							foreach($data['Products'] as $p)
							{
								
								$rir=Receiptir::model()->find(array('condition'=>'LabNo=:labno',
								'params'=>array(':labno'=>$p['LabNo'],)));
								
								
								if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'])));
									if(empty($rt))
									{
										$transaction=$rir->dbConnection->beginTransaction();
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TUID="GRAIN";
										
										$substd=Substandards::model()->find(array('condition'=>'LOWER(Grade)=:no',
								'params'=>array(':no'=>strtolower($p['SubStd']))));
										
										if(!empty($substd))
										{
											$rt->SSID=$substd->Id;
										}
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ULRNo=isset($p['ULRNo'])?$p['ULRNo']:null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
										$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['TestedBy']))));
								if(!empty($user))
								{
									$rt->TestedBy=$user->Id;
									
								}
										}
										
										if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
								$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['ApprovedBy']))));
								if(!empty($user))
								{
									$rt->ApprovedBy=$user->Id;
								}
										}
										
										$rt->ApprovedDate=isset($p['ApprovedDate'])?date('Y-m-d H:i:s',strtotime($p['ApprovedDate'])):null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										$rt->save(false);
										
										$rtid=$rt->getPrimaryKey();
										
										 
										
										
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="HTBatchNo";
											$pel=isset($p['HTBatchNo'])?$p['HTBatchNo']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Orientation";
											$pel=isset($p['Orientation'])?$p['Orientation']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Etchant";
											$pel=isset($p['Etchant'])?$p['Etchant']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Magnification";
											$pel=isset($p['Magnification'])?$p['Magnification']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="ReportNo";
											$pel=isset($p['ReportNo'])?$p['ReportNo']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Fields";
											$pel=isset($p['FieldChecked'])?$p['FieldChecked']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="PolSample";
											$pel=isset($p['PolSampleSize'])?$p['PolSampleSize']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="HTGiven";
											$pel=isset($p['HeatTreat'])?$p['HeatTreat']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Location";
											$pel=isset($p['Location'])?$p['Location']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Temp";
											$pel=isset($p['Temp'])?$p['Temp']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="ObsRemark";
											$pel=isset($p['ObsRemark'])?$p['ObsRemark']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
									
											$el="ObsUnit";
											$pel=isset($p['ObsUnit'])?$p['ObsUnit']:null ;
											$min=null;
											$max=null;											
											saveobsparam("ObsUnit",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['FieldNo1'])?$p['FieldNo1']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['FieldNo2'])?$p['FieldNo2']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['FieldNo3'])?$p['FieldNo3']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['FieldNo4'])?$p['FieldNo4']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['FieldNo5'])?$p['FieldNo5']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
										if(isset($p['Uploads']))
										{
											if(!is_null($p['Uploads']))
											{
												foreach(json_decode($p['Uploads']) as $u)
												{
													$rtu=new Rirtestuploads;
													$rtu->name=$u->name;
													$rtu->url=$u->url;
													$rtu->rirtestid=$rtid;
													$rtu->save(false);
												}
											}
										}
										
										$transaction->commit();
									}
									else
									{
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->save(false);
									}
								}
								
								
								
							}
						
                  
                            
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
			   
case 'importirk':	  //16
try{
						
						function savebaseparam($param,$pval,$rtid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>16,':psym'=>$param]]);
										
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->getPrimaryKey();
								$sd->BValue=$pval;
								$sd->save(false);
							
							
						
										}
								
								
       			 				function saveobsparam($el,$elval,$rtid,$min,$max)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>16,':psym'=>$el]]);
										
											$sd=Rirtestobs::model()->find(['condition'=>'RTID=:rtid AND TPID=:tpid',
										'params'=>[':rtid'=>$rtid,':tpid'=>$e->Id]]);
										
													if(empty($sd))
													{
														$sd=new Rirtestobs;
																$sd->RTID=$rtid;
																$sd->TPID=$e->Id;
																$sd->SpecMin=$min;
																$sd->SpecMax=$max;												
																$sd->save(false);
													}
													
													
													
													$rv=new Rirtestobsvalue;
													$rv->RTOBID=$sd->getPrimaryKey();								
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
										}
								
								
								$test=Tests::model()->findByPk(16);
							foreach($data['Products'] as $p)
							{
								
								$rir=Receiptir::model()->find(array('condition'=>'LabNo=:labno',
								'params'=>array(':labno'=>$p['LabNo'],)));
								
								
								if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'])));
									if(empty($rt))
									{
										$transaction=$rir->dbConnection->beginTransaction();
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TUID="IRK";
										
										$substd=Substandards::model()->find(array('condition'=>'LOWER(Grade)=:no',
								'params'=>array(':no'=>strtolower($p['SubStd']))));
										
										if(!empty($substd))
										{
											$rt->SSID=$substd->Id;
										}
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ULRNo=isset($p['ULRNo'])?$p['ULRNo']:null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
										$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['TestedBy']))));
								if(!empty($user))
								{
									$rt->TestedBy=$user->Id;
									
								}
										}
										
										if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
								$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['ApprovedBy']))));
								if(!empty($user))
								{
									$rt->ApprovedBy=$user->Id;
								}
										}
										
										$rt->ApprovedDate=isset($p['ApprovedDate'])?date('Y-m-d H:i:s',strtotime($p['ApprovedDate'])):null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										$rt->save(false);
										
										$rtid=$rt->getPrimaryKey();
										
										
											$el="Orientation";
											$pel=isset($p['Orientation'])?$p['Orientation']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Etchant";
											$pel=isset($p['Etchant'])?$p['Etchant']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Magnification";
											$pel=isset($p['Magnification'])?$p['Magnification']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="ReportNo";
											$pel=isset($p['ReportNo'])?$p['ReportNo']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Location";
											$pel=isset($p['Location'])?$p['Location']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Temp";
											$pel=isset($p['Temp'])?$p['Temp']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="ObsRemark";
											$pel=isset($p['ObsRemark'])?$p['ObsRemark']:null ;
											$min=null;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="ObsRemark";
											$pel=isset($p['ObsRemark'])?$p['ObsRemark']:null ;
											$min=null;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="PolSample";
											$pel=isset($p['PolSampleSize'])?$p['PolSampleSize']:null ;
											$min=null;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$pel=isset($p['Total'])?$p['Total']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Total",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['SSTotalS'])?$p['SSTotalS']:null ;
											$min=null;
											$max=null;											
											saveobsparam("SSTotalS",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['SSTotalO'])?$p['SSTotalO']:null ;
											$min=null;
											$max=null;											
											saveobsparam("SSTotalO",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['TotalK3S'])?$p['TotalK3S']:null ;
											$min=null;
											$max=null;											
											saveobsparam("TotalK3S",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['TotalK3O'])?$p['TotalK3O']:null ;
											$min=null;
											$max=null;											
											saveobsparam("TotalK3O",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['OverAllTotK3'])?$p['OverAllTotK3']:null ;
											$min=null;
											$max=null;											
											saveobsparam("OverAllTotK3",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['Obs'])?$p['Obs']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
										
										if(isset($p['Uploads']))
										{
											if(!is_null($p['Uploads']))
											{
												foreach(json_decode($p['Uploads']) as $u)
												{
													$rtu=new Rirtestuploads;
													$rtu->name=$u->name;
													$rtu->url=$u->url;
													$rtu->rirtestid=$rtid;
													$rtu->save(false);
												}
											}
										}
										
										$transaction->commit();
									}
									else
									{
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->save(false);
									}
								}
								
								
								
							}
						
                  
                            
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
			   
case 'importirw':	  //17
try{
						
						function savebaseparam($param,$pval,$rtid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>17,':psym'=>$param]]);
										
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->getPrimaryKey();
								$sd->BValue=$pval;
								$sd->save(false);
							
							
						
										}
								
								
       			 				function saveobsparam($el,$elval,$rtid,$min,$max)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>17,':psym'=>$el]]);
										
											$sd=Rirtestobs::model()->find(['condition'=>'RTID=:rtid AND TPID=:tpid',
										'params'=>[':rtid'=>$rtid,':tpid'=>$e->Id]]);
										
													if(empty($sd))
													{
														$sd=new Rirtestobs;
																$sd->RTID=$rtid;
																$sd->TPID=$e->Id;
																$sd->SpecMin=$min;
																$sd->SpecMax=$max;												
																$sd->save(false);
													}
													
													
													
													$rv=new Rirtestobsvalue;
													$rv->RTOBID=$sd->getPrimaryKey();								
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
										}
								
								
								$test=Tests::model()->findByPk(17);
							foreach($data['Products'] as $p)
							{
								
								$rir=Receiptir::model()->find(array('condition'=>'LabNo=:labno',
								'params'=>array(':labno'=>$p['LabNo'],)));
								
								
								if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'])));
									if(empty($rt))
									{
										$transaction=$rir->dbConnection->beginTransaction();
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TUID="IRW";
										
										$substd=Substandards::model()->find(array('condition'=>'LOWER(Grade)=:no',
								'params'=>array(':no'=>strtolower($p['SubStd']))));
										
										if(!empty($substd))
										{
											$rt->SSID=$substd->Id;
										}
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ULRNo=isset($p['ULRNo'])?$p['ULRNo']:null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
										$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['TestedBy']))));
								if(!empty($user))
								{
									$rt->TestedBy=$user->Id;
									
								}
										}
										
										if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
								$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['ApprovedBy']))));
								if(!empty($user))
								{
									$rt->ApprovedBy=$user->Id;
								}
										}
										
										$rt->ApprovedDate=isset($p['ApprovedDate'])?date('Y-m-d H:i:s',strtotime($p['ApprovedDate'])):null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										$rt->save(false);
										
										$rtid=$rt->getPrimaryKey();
										
										 
										
											$el="HTBatchNo";
											$pel=isset($p['HTBatchNo'])?$p['HTBatchNo']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Orientation";
											$pel=isset($p['Orientation'])?$p['Orientation']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Etchant";
											$pel=isset($p['Etchant'])?$p['Etchant']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Magnification";
											$pel=isset($p['Magnification'])?$p['Magnification']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="ReportNo";
											$pel=isset($p['ReportNo'])?$p['ReportNo']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="PolSample";
											$pel=isset($p['PolSampleSize'])?$p['PolSampleSize']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Location";
											$pel=isset($p['Location'])?$p['Location']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Temp";
											$pel=isset($p['Temp'])?$p['Temp']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="ObsRemark";
											$pel=isset($p['ObsRemark'])?$p['ObsRemark']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="WObs";
											$pel=isset($p['WObs'])?$p['WObs']:null ;
											$min=null;
											$max=null;											
											saveobsparam("WObs",$pel,$rtid,$min,$max);									
										
										
										
										if(isset($p['Uploads']))
										{
											if(!is_null($p['Uploads']))
											{
												foreach(json_decode($p['Uploads']) as $u)
												{
													$rtu=new Rirtestuploads;
													$rtu->name=$u->name;
													$rtu->url=$u->url;
													$rtu->rirtestid=$rtid;
													$rtu->save(false);
												}
											}
										}
										
										$transaction->commit();
									}
									else
									{
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->save(false);
									}
								}
								
								
								
							}
						
                  
                            
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
			   
case 'importthread':	//21  
try{
						
						function savebaseparam($param,$pval,$rtid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>21,':psym'=>$param]]);
										
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->getPrimaryKey();
								$sd->BValue=$pval;
								$sd->save(false);
							
							
						
										}
								
								
       			 				function saveobsparam($el,$elval,$rtid,$min,$max)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>21,':psym'=>$el]]);
										
											$sd=Rirtestobs::model()->find(['condition'=>'RTID=:rtid AND TPID=:tpid',
										'params'=>[':rtid'=>$rtid,':tpid'=>$e->Id]]);
										
													if(empty($sd))
													{
														$sd=new Rirtestobs;
																$sd->RTID=$rtid;
																$sd->TPID=$e->Id;
																$sd->SpecMin=$min;
																$sd->SpecMax=$max;												
																$sd->save(false);
													}
													
													
													
													$rv=new Rirtestobsvalue;
													$rv->RTOBID=$sd->getPrimaryKey();								
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
										}
								
								
								$test=Tests::model()->findByPk(21);
							foreach($data['Products'] as $p)
							{
								
								$rir=Receiptir::model()->find(array('condition'=>'LabNo=:labno',
								'params'=>array(':labno'=>$p['LabNo'],)));
								
								
								if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'])));
									if(empty($rt))
									{
										$transaction=$rir->dbConnection->beginTransaction();
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TUID="THREAD";
										
										$substd=Substandards::model()->find(array('condition'=>'LOWER(Grade)=:no',
								'params'=>array(':no'=>strtolower($p['SubStd']))));
										
										if(!empty($substd))
										{
											$rt->SSID=$substd->Id;
										}
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ULRNo=isset($p['ULRNo'])?$p['ULRNo']:null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
										$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['TestedBy']))));
								if(!empty($user))
								{
									$rt->TestedBy=$user->Id;
									
								}
										}
										
										if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
								$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['ApprovedBy']))));
								if(!empty($user))
								{
									$rt->ApprovedBy=$user->Id;
								}
										}
										
										$rt->ApprovedDate=isset($p['ApprovedDate'])?date('Y-m-d H:i:s',strtotime($p['ApprovedDate'])):null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										$rt->save(false);
										
										$rtid=$rt->getPrimaryKey();
										
										 
										
											$el="HTBatchNo";
											$pel=isset($p['HTBatchNo'])?$p['HTBatchNo']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Orientation";
											$pel=isset($p['Orientation'])?$p['Orientation']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Etchant";
											$pel=isset($p['Etchant'])?$p['Etchant']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Magnification";
											$pel=isset($p['Magnification'])?$p['Magnification']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="ReportNo";
											$pel=isset($p['ReportNo'])?$p['ReportNo']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Equipment";
											$pel=isset($p['Equipment'])?$p['Equipment']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Location";
											$pel=isset($p['Location'])?$p['Location']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Temp";
											$pel=isset($p['Temp'])?$p['Temp']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="ObsRemark";
											$pel=isset($p['ObsRemark'])?$p['ObsRemark']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Obs";
											$pel=isset($p['Obs'])?$p['Obs']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
										
										
										if(isset($p['Uploads']))
										{
											if(!is_null($p['Uploads']))
											{
												foreach(json_decode($p['Uploads']) as $u)
												{
													$rtu=new Rirtestuploads;
													$rtu->name=$u->name;
													$rtu->url=$u->url;
													$rtu->rirtestid=$rtid;
													$rtu->save(false);
												}
											}
										}
										
										$transaction->commit();
									}
									else
									{
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->save(false);
									}
								}
								
								
								
							}
						
                  
                            
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
			   
case 'importwedge':	//23  
try{
						
						function savebaseparam($param,$pval,$rtid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>23,':psym'=>$param]]);
										
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->getPrimaryKey();
								$sd->BValue=$pval;
								$sd->save(false);
							
							
						
										}
								
								
       			 				function saveobsparam($el,$elval,$rtid,$min,$max)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>23,':psym'=>$el]]);
										
											$sd=Rirtestobs::model()->find(['condition'=>'RTID=:rtid AND TPID=:tpid',
										'params'=>[':rtid'=>$rtid,':tpid'=>$e->Id]]);
										
													if(empty($sd))
													{
														$sd=new Rirtestobs;
																$sd->RTID=$rtid;
																$sd->TPID=$e->Id;
																$sd->SpecMin=$min;
																$sd->SpecMax=$max;												
																$sd->save(false);
													}
													
													
													
													$rv=new Rirtestobsvalue;
													$rv->RTOBID=$sd->getPrimaryKey();								
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
										}
								
								
								$test=Tests::model()->findByPk(23);
							foreach($data['Products'] as $p)
							{
								
								$rir=Receiptir::model()->find(array('condition'=>'LabNo=:labno',
								'params'=>array(':labno'=>$p['LabNo'],)));
								
								
								if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'])));
									if(empty($rt))
									{
										$transaction=$rir->dbConnection->beginTransaction();
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TUID="WEDGE";
										
										$substd=Substandards::model()->find(array('condition'=>'LOWER(Grade)=:no',
								'params'=>array(':no'=>strtolower($p['SubStd']))));
										
										if(!empty($substd))
										{
											$rt->SSID=$substd->Id;
										}
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ULRNo=isset($p['ULRNo'])?$p['ULRNo']:null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
										$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['TestedBy']))));
								if(!empty($user))
								{
									$rt->TestedBy=$user->Id;
									
								}
										}
										
										if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
								$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['ApprovedBy']))));
								if(!empty($user))
								{
									$rt->ApprovedBy=$user->Id;
								}
										}
										
										$rt->ApprovedDate=isset($p['ApprovedDate'])?date('Y-m-d H:i:s',strtotime($p['ApprovedDate'])):null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										$rt->save(false);
										
										$rtid=$rt->getPrimaryKey();
										
										
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Size";
											$pel=isset($p['Size'])?$p['Size']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Equipment";
											$pel=isset($p['Equipment'])?$p['Equipment']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Condition";
											$pel=isset($p['Condition'])?$p['Condition']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Test";
											$pel=isset($p['Test'])?$p['Test']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="RequiredTS";
											$pel=isset($p['RequiredTS'])?$p['RequiredTS']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
										
										
										
											$el="ObservedTS";
											$pel=isset($p['ObservedTS'])?$p['ObservedTS']:null ;
											$min=null;
											$max=null;											
											saveobsparam("ObservedTS",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['QtyTested'])?$p['QtyTested']:null ;
											$min=null;
											$max=null;											
											saveobsparam("QtyTested",$pel,$rtid,$min,$max);									
										
									
										
										if(isset($p['Uploads']))
										{
											if(!is_null($p['Uploads']))
											{
												foreach(json_decode($p['Uploads']) as $u)
												{
													$rtu=new Rirtestuploads;
													$rtu->name=$u->name;
													$rtu->url=$u->url;
													$rtu->rirtestid=$rtid;
													$rtu->save(false);
												}
											}
										}
										
										$transaction->commit();
									}
									else
									{
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->save(false);
									}
								}
								
								
								
							}
						
                  
                            
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
			   
case 'importhet':	//24
try{
						
						function savebaseparam($param,$pval,$rtid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>24,':psym'=>$param]]);
										
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->getPrimaryKey();
								$sd->BValue=$pval;
								$sd->save(false);
							
							
						
										}
								
								
       			 				function saveobsparam($el,$elval,$rtid,$min,$max)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>24,':psym'=>$el]]);
										
											$sd=Rirtestobs::model()->find(['condition'=>'RTID=:rtid AND TPID=:tpid',
										'params'=>[':rtid'=>$rtid,':tpid'=>$e->Id]]);
										
													if(empty($sd))
													{
														$sd=new Rirtestobs;
																$sd->RTID=$rtid;
																$sd->TPID=$e->Id;
																$sd->SpecMin=$min;
																$sd->SpecMax=$max;												
																$sd->save(false);
													}
													
													
													
													$rv=new Rirtestobsvalue;
													$rv->RTOBID=$sd->getPrimaryKey();								
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
										}
								
								
								$test=Tests::model()->findByPk(24);
							foreach($data['Products'] as $p)
							{
								
								$rir=Receiptir::model()->find(array('condition'=>'LabNo=:labno',
								'params'=>array(':labno'=>$p['LabNo'],)));
								
								
								if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'])));
									if(empty($rt))
									{
										$transaction=$rir->dbConnection->beginTransaction();
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TUID="HET";
										
										$substd=Substandards::model()->find(array('condition'=>'LOWER(Grade)=:no',
								'params'=>array(':no'=>strtolower($p['SubStd']))));
										
										if(!empty($substd))
										{
											$rt->SSID=$substd->Id;
										}
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ULRNo=isset($p['ULRNo'])?$p['ULRNo']:null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
										$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['TestedBy']))));
								if(!empty($user))
								{
									$rt->TestedBy=$user->Id;
									
								}
										}
										
										if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
								$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['ApprovedBy']))));
								if(!empty($user))
								{
									$rt->ApprovedBy=$user->Id;
								}
										}
										
										$rt->ApprovedDate=isset($p['ApprovedDate'])?date('Y-m-d H:i:s',strtotime($p['ApprovedDate'])):null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										$rt->save(false);
										
										$rtid=$rt->getPrimaryKey();
										
										 
										
										
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Size";
											$pel=isset($p['Size'])?$p['Size']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Equipment";
											$pel=isset($p['Equipment'])?$p['Equipment']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Condition";
											$pel=isset($p['Condition'])?$p['Condition']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="QtyBefore";
											$pel=isset($p['QtyBefore'])?$p['QtyBefore']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="QtyAfter";
											$pel=isset($p['QtyAfter'])?$p['QtyAfter']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Location";
											$pel=isset($p['Location'])?$p['Location']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
										
										
											$el="BeforeGalvanize";
											$pel=isset($p['BeforeGalvanize'])?$p['BeforeGalvanize']:null ;
											$min=null;
											$max=null;											
											saveobsparam("BeforeGalvanize",$pel,$rtid,$min,$max);									
										
										
										
											$pel=isset($p['AfterGalvanize'])?$p['AfterGalvanize']:null ;
											$min=null;
											$max=null;											
											saveobsparam("AfterGalvanize",$pel,$rtid,$min,$max);									
									
										
										if(isset($p['Uploads']))
										{
											if(!is_null($p['Uploads']))
											{
												foreach(json_decode($p['Uploads']) as $u)
												{
													$rtu=new Rirtestuploads;
													$rtu->name=$u->name;
													$rtu->url=$u->url;
													$rtu->rirtestid=$rtid;
													$rtu->save(false);
												}
											}
										}
										
										$transaction->commit();
									}
									else
									{
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->save(false);
									}
								}
								
								
								
							}
						
                  
                            
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
			   

case 'importshear':	//25
try{
						
						function savebaseparam($param,$pval,$rtid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>25,':psym'=>$param]]);
										
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->getPrimaryKey();
								$sd->BValue=$pval;
								$sd->save(false);
							
							
						
										}
								
								
       			 				function saveobsparam($el,$elval,$rtid,$min,$max)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>25,':psym'=>$el]]);
										
											$sd=Rirtestobs::model()->find(['condition'=>'RTID=:rtid AND TPID=:tpid',
										'params'=>[':rtid'=>$rtid,':tpid'=>$e->Id]]);
										
													if(empty($sd))
													{
														$sd=new Rirtestobs;
																$sd->RTID=$rtid;
																$sd->TPID=$e->Id;
																$sd->SpecMin=$min;
																$sd->SpecMax=$max;												
																$sd->save(false);
													}
													
													
													
													$rv=new Rirtestobsvalue;
													$rv->RTOBID=$sd->getPrimaryKey();								
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
										}
								
								
								$test=Tests::model()->findByPk(25);
							foreach($data['Products'] as $p)
							{
								
								$rir=Receiptir::model()->find(array('condition'=>'LabNo=:labno',
								'params'=>array(':labno'=>$p['LabNo'],)));
								
								
								if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'])));
									if(empty($rt))
									{
										$transaction=$rir->dbConnection->beginTransaction();
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TUID="SHEAR";
										
										$substd=Substandards::model()->find(array('condition'=>'LOWER(Grade)=:no',
								'params'=>array(':no'=>strtolower($p['SubStd']))));
										
										if(!empty($substd))
										{
											$rt->SSID=$substd->Id;
										}
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ULRNo=isset($p['ULRNo'])?$p['ULRNo']:null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
										$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['TestedBy']))));
								if(!empty($user))
								{
									$rt->TestedBy=$user->Id;
									
								}
										}
										
										if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
								$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['ApprovedBy']))));
								if(!empty($user))
								{
									$rt->ApprovedBy=$user->Id;
								}
										}
										
										$rt->ApprovedDate=isset($p['ApprovedDate'])?date('Y-m-d H:i:s',strtotime($p['ApprovedDate'])):null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										$rt->save(false);
										
										$rtid=$rt->getPrimaryKey();
										
										 
										
										
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Size";
											$pel=isset($p['Size'])?$p['Size']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Equipment";
											$pel=isset($p['Equipment'])?$p['Equipment']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Test";
											$pel=isset($p['Test'])?$p['Test']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Condition";
											$pel=isset($p['Condition'])?$p['Condition']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="GD";
											$pel=isset($p['GaugeDiameter'])?$p['GaugeDiameter']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="InitialArea";
											$pel=isset($p['InitialArea'])?$p['InitialArea']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="ShearLoad";
											$pel=isset($p['ShearLoad'])?$p['ShearLoad']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="ShearStrength";
											$pel=isset($p['ShearStrength'])?$p['ShearStrength']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="QtyTested";
											$pel=isset($p['QtyTested'])?$p['QtyTested']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
										
										// if(isset($p['Torque']))
										// {
											// $el="Extension";
											// $pel=isset($p['Extension'])?$p['Extension']:null ;
											// $min=null;
											// $max=null;											
											// saveobsparam("Extension",$pel,$rtid,$min,$max);									
										// }
										
										
										
										if(isset($p['Uploads']))
										{
											if(!is_null($p['Uploads']))
											{
												foreach(json_decode($p['Uploads']) as $u)
												{
													$rtu=new Rirtestuploads;
													$rtu->name=$u->name;
													$rtu->url=$u->url;
													$rtu->rirtestid=$rtid;
													$rtu->save(false);
												}
											}
										}
										
										$transaction->commit();
									}
									else
									{
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->save(false);
									}
								}
								
								
								
							}
						
                  
                            
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
			   
			     case 'importtorques':
				
					try{
						
						function savebaseparam($param,$pval,$rtid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>9,':psym'=>$param]]);
										
										$sd=Rirtestbasic::model()->find(['condition'=>'RTID=:rtid AND TBPID=:tbpid',
										'params'=>[':rtid'=>$rtid,':tbpid'=>$tb->getPrimaryKey()]]);
										
										if(empty($sd))
										{
										
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->getPrimaryKey();
								$sd->BValue=$pval;
								$sd->save(false);
							
										}
						
										}
								
								
       			 				function saveobsparam($el,$elval,$rtid,$min,$max)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>9,':psym'=>$el]]);
										
											$sd=Rirtestobs::model()->find(['condition'=>'RTID=:rtid AND TPID=:tpid',
										'params'=>[':rtid'=>$rtid,':tpid'=>$e->Id]]);
										
													if(empty($sd))
													{
														$sd=new Rirtestobs;
																$sd->RTID=$rtid;
																$sd->TPID=$e->Id;
																$sd->SpecMin=$min;
																$sd->SpecMax=$max;												
																$sd->save(false);
													}
													
													if(!is_null($elval))
													{
													
													$rv=new Rirtestobsvalue;
													$rv->RTOBID=$sd->getPrimaryKey();								
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
													}
										}
								
								
								$test=Tests::model()->findByPk(9);
							foreach($data['Products'] as $p)
							{
								
								$rir=Receiptir::model()->find(array('condition'=>'LabNo=:labno',
								'params'=>array(':labno'=>$p['LabNo'],)));
								
								
								if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'])));
									if(empty($rt))
									{
										$transaction=$rir->dbConnection->beginTransaction();
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TUID="TORQ";
										
										$substd=Substandards::model()->find(array('condition'=>'LOWER(Grade)=:no',
								'params'=>array(':no'=>strtolower($p['SubStd']))));
										
										if(!empty($substd))
										{
											$rt->SSID=$substd->Id;
										}
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ULRNo=isset($p['ULRNo'])?$p['ULRNo']:null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
											$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
											'params'=>array(':no'=>strtolower($p['TestedBy']))));
											if(!empty($user))
											{
												$rt->TestedBy=$user->Id;
												
											}
										}
										
										if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
											$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
											'params'=>array(':no'=>strtolower($p['ApprovedBy']))));
											if(!empty($user))
											{
												$rt->ApprovedBy=$user->Id;
											}
										}
										
										$rt->ApprovedDate=isset($p['ApprovedDate'])?date('Y-m-d H:i:s',strtotime($p['ApprovedDate'])):null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										$rt->save(false);
										
										$rtid=$rt->getPrimaryKey();
										
										 
										
										if(isset($p['Description']))
										{
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
										}
										else
										{
											$el="Description";
											$pel=null;
											savebaseparam($el,$pel,$rtid);		
										}
										if(isset($p['Equipment']))
										{
											$el="Equipment";
											$pel=isset($p['Equipment'])?$p['Equipment']:null ;											
											savebaseparam($el,$pel,$rtid);									
										}
										else
										{
											$el="Equipment";
											$pel=null;
											savebaseparam($el,$pel,$rtid);		
										}
										
										if(isset($p['Indicator']))
										{
											$el="Indicator";
											$pel=isset($p['Indicator'])?$p['Indicator']:null ;											
											savebaseparam($el,$pel,$rtid);									
										}
										else
										{
											$el="Indicator";
											$pel=null;
											savebaseparam($el,$pel,$rtid);		
										}
										
										if(isset($p['LoadCell']))
										{
											$el="Load";
											$pel=isset($p['LoadCell'])?$p['LoadCell']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										}
										else
										{
											$el="Load";
											$pel=null;
											savebaseparam($el,$pel,$rtid);		
										}
										
										if(isset($p['ReqTorq']))
										{
											$el="ReqTorque";
											$pel=isset($p['ReqTorq'])?$p['ReqTorq']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										}
										else
										{
											$el="ReqTorque";
											$pel=null;
											savebaseparam($el,$pel,$rtid);		
										}
										
										if(isset($p['ReqForce']))
										{
											$el="ReqForce";
											$pel=isset($p['ReqForce'])?$p['ReqForce']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										}
										else
										{
											$el="ReqForce";
											$pel=null;
											savebaseparam($el,$pel,$rtid);		
										}
										
										if(isset($p['TObs']) && !is_null($p['TObs']))
										{
											foreach(json_decode($p['TObs']) as $o)
											{
												
													$el="Torque";
													$pel=isset($o->Torque)?$o->Torque:null ;
													$min=null;
													$max=null;											
													saveobsparam("Torque",$pel,$rtid,$min,$max);									
												
													$pel=isset($o->Force)?$o->Force:null ;
													$min=null;
													$max=null;											
													saveobsparam("Force",$pel,$rtid,$min,$max);									
												
													$pel=isset($o->Friction)?$o->Friction:null ;
													$min=null;
													$max=null;											
													saveobsparam("Friction",$pel,$rtid,$min,$max);									
												
											}
										}
										
										
										if(isset($p['Uploads']))
										{
											if(!is_null($p['Uploads']))
											{
												foreach(json_decode($p['Uploads']) as $u)
												{
													$rtu=new Rirtestuploads;
													$rtu->name=$u->name;
													$rtu->url=$u->url;
													$rtu->rirtestid=$rtid;
													$rtu->save(false);
												}
											}
										}
										
										$transaction->commit();
									}
									else
									{
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->save(false);
										$rtid=$rt->getPrimaryKey();
										
										
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Equipment";
											$pel=isset($p['Equipment'])?$p['Equipment']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Indicator";
											$pel=isset($p['Indicator'])?$p['Indicator']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Load";
											$pel=isset($p['LoadCell'])?$p['LoadCell']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="ReqTorque";
											$pel=isset($p['ReqTorq'])?$p['ReqTorq']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="ReqForce";
											$pel=isset($p['ReqForce'])?$p['ReqForce']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
										
										foreach(json_decode($p['TObs']) as $o)
										{
											
												$el="Torque";
												$pel=isset($o->Torque)?$o->Torque:null ;
												$min=null;
												$max=null;											
												saveobsparam("Torque",$pel,$rtid,$min,$max);									
											
												$pel=isset($o->Force)?$o->Force:null ;
												$min=null;
												$max=null;											
												saveobsparam("Force",$pel,$rtid,$min,$max);									
											
												$pel=isset($o->Friction)?$o->Friction:null ;
												$min=null;
												$max=null;											
												saveobsparam("Friction",$pel,$rtid,$min,$max);									
											
										}
										
										
									}
								}
								
								
								
							}
						
                  
                            
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
			   
			     case 'importmicrocoat':
				
					try{
						
						function savebaseparam($param,$pval,$rtid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>20,':psym'=>$param]]);
										
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->getPrimaryKey();
								$sd->BValue=$pval;
								$sd->save(false);
							
							
						
										}
								
								
       			 				function saveobsparam($el,$elval,$rtid,$min,$max)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>20,':psym'=>$el]]);
										
											$sd=Rirtestobs::model()->find(['condition'=>'RTID=:rtid AND TPID=:tpid',
										'params'=>[':rtid'=>$rtid,':tpid'=>$e->Id]]);
										
													if(empty($sd))
													{
														$sd=new Rirtestobs;
																$sd->RTID=$rtid;
																$sd->TPID=$e->Id;
																$sd->SpecMin=$min;
																$sd->SpecMax=$max;												
																$sd->save(false);
													}
													
													
													
													$rv=new Rirtestobsvalue;
													$rv->RTOBID=$sd->getPrimaryKey();								
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
										}
								
								
								$test=Tests::model()->findByPk(20);
							foreach($data['Products'] as $p)
							{
								
								$rir=Receiptir::model()->find(array('condition'=>'LabNo=:labno',
								'params'=>array(':labno'=>$p['LabNo'],)));
								
								
								if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'])));
									if(empty($rt))
									{
										$transaction=$rir->dbConnection->beginTransaction();
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TUID="MCOAT";
										
										$substd=Substandards::model()->find(array('condition'=>'LOWER(Grade)=:no',
								'params'=>array(':no'=>strtolower($p['SubStd']))));
										
										if(!empty($substd))
										{
											$rt->SSID=$substd->Id;
										}
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ULRNo=isset($p['ULRNo'])?$p['ULRNo']:null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
										$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['TestedBy']))));
								if(!empty($user))
								{
									$rt->TestedBy=$user->Id;
									
								}
										}
										
										if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
								$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['ApprovedBy']))));
								if(!empty($user))
								{
									$rt->ApprovedBy=$user->Id;
								}
										}
										
										$rt->ApprovedDate=isset($p['ApprovedDate'])?date('Y-m-d H:i:s',strtotime($p['ApprovedDate'])):null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										$rt->save(false);
										
										$rtid=$rt->getPrimaryKey();
										
										 
										
										
											$el="Orientation";
											$pel=isset($p['Orientation'])?$p['Orientation']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Temp";
											$pel=isset($p['Temp'])?$p['Temp']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="HTBatchNo";
											$pel=isset($p['HTBatchNo'])?$p['HTBatchNo']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
								
											$el="Etchant";
											$pel=isset($p['Etchant'])?$p['Etchant']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Magnification";
											$pel=isset($p['Magnification'])?$p['Magnification']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
										
										
											$el="ReportNo";
											$pel=isset($p['ReportNo'])?$p['ReportNo']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
										
											$el="Location";
											$pel=isset($p['Location'])?$p['Location']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
										
																			
										
										
										
										
											$pel=isset($p['Obs1'])?$p['Obs1']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['Obs2'])?$p['Obs2']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['Obs3'])?$p['Obs3']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['Obs4'])?$p['Obs4']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['Obs5'])?$p['Obs5']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
										
										if(isset($p['Uploads']))
										{
											if(!is_null($p['Uploads']))
											{
												foreach(json_decode($p['Uploads']) as $u)
												{
													$rtu=new Rirtestuploads;
													$rtu->name=$u->name;
													$rtu->url=$u->url;
													$rtu->rirtestid=$rtid;
													$rtu->save(false);
												}
											}
										}
										
										$transaction->commit();
									}
									else
									{
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->save(false);
									}
								}
								
								
								
							}
						
                  
                            
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
			       case 'importmicrocase':
				
					try{
						
						function savebaseparam($param,$pval,$rtid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>19,':psym'=>$param]]);
										
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->getPrimaryKey();
								$sd->BValue=$pval;
								$sd->save(false);
							
							
						
										}
								
								
       			 				function saveobsparam($el,$elval,$rtid,$min,$max)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>19,':psym'=>$el]]);
										
											$sd=Rirtestobs::model()->find(['condition'=>'RTID=:rtid AND TPID=:tpid',
										'params'=>[':rtid'=>$rtid,':tpid'=>$e->Id]]);
										
													if(empty($sd))
													{
														$sd=new Rirtestobs;
																$sd->RTID=$rtid;
																$sd->TPID=$e->Id;
																$sd->SpecMin=$min;
																$sd->SpecMax=$max;												
																$sd->save(false);
													}
													
													
													
													$rv=new Rirtestobsvalue;
													$rv->RTOBID=$sd->getPrimaryKey();								
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
										}
								
								
								$test=Tests::model()->findByPk(19);
							foreach($data['Products'] as $p)
							{
								
								$rir=Receiptir::model()->find(array('condition'=>'LabNo=:labno',
								'params'=>array(':labno'=>$p['LabNo'],)));
								
								
								if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'])));
									if(empty($rt))
									{
										$transaction=$rir->dbConnection->beginTransaction();
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TUID="MCASE";
										
										$substd=Substandards::model()->find(array('condition'=>'LOWER(Grade)=:no',
								'params'=>array(':no'=>strtolower($p['SubStd']))));
										
										if(!empty($substd))
										{
											$rt->SSID=$substd->Id;
										}
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ULRNo=isset($p['ULRNo'])?$p['ULRNo']:null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
										$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['TestedBy']))));
								if(!empty($user))
								{
									$rt->TestedBy=$user->Id;
									
								}
										}
										
										if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
								$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['ApprovedBy']))));
								if(!empty($user))
								{
									$rt->ApprovedBy=$user->Id;
								}
										}
										
										$rt->ApprovedDate=isset($p['ApprovedDate'])?date('Y-m-d H:i:s',strtotime($p['ApprovedDate'])):null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										$rt->save(false);
										
										$rtid=$rt->getPrimaryKey();
										
										 
										
										
											$el="Orientation";
											$pel=isset($p['Orientation'])?$p['Orientation']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Temp";
											$pel=isset($p['Temp'])?$p['Temp']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="HTBatchNo";
											$pel=isset($p['HTBatchNo'])?$p['HTBatchNo']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Etchant";
											$pel=isset($p['Etchant'])?$p['Etchant']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Magnification";
											$pel=isset($p['Magnification'])?$p['Magnification']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											
										
											$el="ReportNo";
											$pel=isset($p['ReportNo'])?$p['ReportNo']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Location";
											$pel=isset($p['Location'])?$p['Location']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="ObsRemark";
											$pel=isset($p['ObsRemark'])?$p['ObsRemark']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
									
										
										
										
											$pel=isset($p['Obs1'])?$p['Obs1']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['Obs2'])?$p['Obs2']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['Obs3'])?$p['Obs3']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['Obs4'])?$p['Obs4']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['Obs5'])?$p['Obs5']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
										
										if(isset($p['Uploads']))
										{
											if(!is_null($p['Uploads']))
											{
												foreach(json_decode($p['Uploads']) as $u)
												{
													$rtu=new Rirtestuploads;
													$rtu->name=$u->name;
													$rtu->url=$u->url;
													$rtu->rirtestid=$rtid;
													$rtu->save(false);
												}
											}
										}
										
										$transaction->commit();
									}
									else
									{
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->save(false);
									}
								}
								
								
								
							}
						
                  
                            
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
			    case 'importmicrodcarb':
				
					try{
						
						function savebaseparam($param,$pval,$rtid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>18,':psym'=>$param]]);
										
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->getPrimaryKey();
								$sd->BValue=$pval;
								$sd->save(false);
							
							
						
										}
								
								
       			 				function saveobsparam($el,$elval,$rtid,$min,$max)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>18,':psym'=>$el]]);
										
											$sd=Rirtestobs::model()->find(['condition'=>'RTID=:rtid AND TPID=:tpid',
										'params'=>[':rtid'=>$rtid,':tpid'=>$e->Id]]);
										
													if(empty($sd))
													{
														$sd=new Rirtestobs;
																$sd->RTID=$rtid;
																$sd->TPID=$e->Id;
																$sd->SpecMin=$min;
																$sd->SpecMax=$max;												
																$sd->save(false);
													}
													
													
													
													$rv=new Rirtestobsvalue;
													$rv->RTOBID=$sd->getPrimaryKey();								
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
										}
								
								
								$test=Tests::model()->findByPk(18);
							foreach($data['Products'] as $p)
							{
								
								$rir=Receiptir::model()->find(array('condition'=>'LabNo=:labno',
								'params'=>array(':labno'=>$p['LabNo'],)));
								
								
								if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'])));
									if(empty($rt))
									{
										$transaction=$rir->dbConnection->beginTransaction();
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TUID="MDCARB";
										
										$substd=Substandards::model()->find(array('condition'=>'LOWER(Grade)=:no',
								'params'=>array(':no'=>strtolower($p['SubStd']))));
										
										if(!empty($substd))
										{
											$rt->SSID=$substd->Id;
										}
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ULRNo=isset($p['ULRNo'])?$p['ULRNo']:null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
										$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['TestedBy']))));
								if(!empty($user))
								{
									$rt->TestedBy=$user->Id;
									
								}
										}
										
										if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
								$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['ApprovedBy']))));
								if(!empty($user))
								{
									$rt->ApprovedBy=$user->Id;
								}
										}
										
										$rt->ApprovedDate=isset($p['ApprovedDate'])?date('Y-m-d H:i:s',strtotime($p['ApprovedDate'])):null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										$rt->save(false);
										
										$rtid=$rt->getPrimaryKey();
										
										 
										
										
											$el="Orientation";
											$pel=isset($p['Orientation'])?$p['Orientation']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Temp";
											$pel=isset($p['Temp'])?$p['Temp']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
										
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Etchant";
											$pel=isset($p['Etchant'])?$p['Etchant']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Magnification";
											$pel=isset($p['Magnification'])?$p['Magnification']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="NoOfSamples";
											$pel=isset($p['NoOfSamples'])?$p['NoOfSamples']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="ReportNo";
											$pel=isset($p['ReportNo'])?$p['ReportNo']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Location";
											$pel=isset($p['Location'])?$p['Location']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="ObsRemark";
											$pel=isset($p['ObsRemark'])?$p['ObsRemark']:null ;
											$min=null ;
											$max=null;											
											savebaseparam($el,$pel,$rtid);									
										
											
											$pel=isset($p['Obs1'])?$p['Obs1']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['Obs2'])?$p['Obs2']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['Obs3'])?$p['Obs3']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['Obs4'])?$p['Obs4']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['Obs5'])?$p['Obs5']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max);									
										
										
										if(isset($p['Uploads']))
										{
											if(!is_null($p['Uploads']))
											{
												foreach(json_decode($p['Uploads']) as $u)
												{
													$rtu=new Rirtestuploads;
													$rtu->name=$u->name;
													$rtu->url=$u->url;
													$rtu->rirtestid=$rtid;
													$rtu->save(false);
												}
											}
										}
										
										$transaction->commit();
									}
									else
									{
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->save(false);
									}
								}
								
								
								
							}
						
                  
                            
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
			   
			   case 'importhardness':
				
					try{
						
						function savebaseparam($param,$pval,$rtid,$tuid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>$tuid,':psym'=>$param]]);
									
$sd=Rirtestbasic::model()->find(['condition'=>'TBPID=:tbid AND RTID=:rtid',
										'params'=>[':tbid'=>$tb->Id,':rtid'=>$rtid]]);
										if(empty($sd))
										{
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->Id;
										}									
								
								$sd->BValue=$pval;
								$sd->save(false);
							
							
						
										}
								
								
       			 				function saveobsparam($el,$elval,$rtid,$min,$max,$tuid)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>$tuid,':psym'=>$el]]);
										
										
										$sd=Rirtestobs::model()->find(['condition'=>'TPID=:tpid AND RTID=:rtid',
										'params'=>[':tpid'=>$e->Id,':rtid'=>$rtid]]);
										if(empty($sd))
										{
												$sd=new Rirtestobs;
													$sd->RTID=$rtid;
													$sd->TPID=$e->Id;
													
										}
												
											$sd->SpecMin=$min;
													$sd->SpecMax=$max;												
													$sd->save(false);		
														
														$rv=Rirtestobsvalue::model()->find(['condition'=>'RTOBID=:rtobid','params'=>[':rtobid'=>$sd->getPrimaryKey()]]);
													
													if(empty($rv))
													{
															$rv=new Rirtestobsvalue;
															$rv->RTOBID=$sd->getPrimaryKey();	
													}		
																			
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
										}
								
								
								
							foreach($data['Products'] as $p)
							{
								$batchcode = null;
$batchno = null;

if (isset($p['BatchCode'])) {
    $batchstring = $p['BatchCode'];
    $batch = explode('-', $batchstring);
    $batchcode = $batch[0];  // Default assignment to batchcode

    if (count($batch) > 1) {
        $batchno = $batch[1];  // If there's a second part, assign to batchno
    }
}

// Only query if batchcode is set, and ensure batchno is included only if it's set
$criteria = array(
    'condition' => 'BatchCode=:bc' . ($batchno !== null ? ' AND BatchNo=:bn' : ''),
    'params' => array(':bc' => $batchcode)
);

if ($batchno !== null) {
    $criteria['params'][':bn'] = $batchno;
}

$rir = Receiptir::model()->find($criteria);
								
								$test=Tests::model()->find(['condition'=>'TUID=:tuid','params'=>[':tuid'=>$p['TUID']]]);
								
								
								
								
								if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$mdstdsid=$rir->MdsTdsId;										  
										$transaction=$rir->dbConnection->beginTransaction();
										
										
										
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq AND TestNo=:testno',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'],':testno'=>$p['TestNo'])));
								
								
									if(empty($rt))
									{
										
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TestNo=isset($p['TestNo'])?$p['TestNo']:null ;
										$rt->TUID=$p['TUID'];
										
										if(!empty($mdstdsid))
										{
										$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$rt->TestId]]);
									
									if(!empty($mdstdstest))
									{
											$rt->SSID=$mdstdstest->SSDID;
									}
										}
										
										
										
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ULRNo=isset($p['ULRNo'])?$p['ULRNo']:null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
										$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['TestedBy']))));
								if(!empty($user))
								{
									$rt->TestedBy=$user->Id;
									
								}
										}
										
											
										
										$rt->ApprovedDate=isset($p['ApprovedDate'])?date('Y-m-d H:i:s',strtotime($p['ApprovedDate'])):null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										
										if($rt->Status==='complete')
										{
								
									$rt->ApprovedBy=20;
									
								
								
									$str="TC6567";
						$yr="24";
						$str1="1"; //--For multiple locations
						$input=$p['ULRNo'];//$model->Id;
						$str2=str_pad($input, 8, "0", STR_PAD_LEFT);
						$str3="F";
						$rt->ULRNo=$str.$yr.$str1.$str2.$str3;
								
								if ($rt->TestDate) {
											$testDate = new DateTime($rt->TestDate);
											$testDate->modify('+2 days');
											$rt->ApprovedDate = $testDate->format('Y-m-d');
										} else {
											$rt->ApprovedDate = null;  // If TestDate is not set, ApprovedDate will also be null
										}

										}
										
										$rt->save(false);
										
										
										
									}
									else
									{
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										
										$rt->TUID=$p['TUID'];
										
										if(!empty($mdstdsid))
										{
										$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$rt->TestId]]);
									
									if(!empty($mdstdstest))
									{
											$rt->SSID=$mdstdstest->SSDID;
									}
										}
										
										
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ULRNo=isset($p['ULRNo'])?$p['ULRNo']:null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
										$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['TestedBy']))));
								if(!empty($user))
								{
									$rt->TestedBy=$user->Id;
									
								}
										}
										
											
										
										$rt->ApprovedDate=isset($p['ApprovedDate'])?date('Y-m-d H:i:s',strtotime($p['ApprovedDate'])):null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										
										
										if($rt->Status==='complete')
										{
								
									$rt->ApprovedBy=20;
									
								
								
									$str="TC6567";
						$yr="24";
						$str1="1"; //--For multiple locations
						$input=$p['ULRNo'];//$model->Id;
						$str2=str_pad($input, 8, "0", STR_PAD_LEFT);
						$str3="F";
						$rt->ULRNo=$str.$yr.$str1.$str2.$str3;
								
								if ($rt->TestDate) {
											$testDate = new DateTime($rt->TestDate);
											$testDate->modify('+2 days');
											$rt->ApprovedDate = $testDate->format('Y-m-d');
										} else {
											$rt->ApprovedDate = null;  // If TestDate is not set, ApprovedDate will also be null
										}

										}
										
										$rt->save(false);
									}
									$rtid=$rt->getPrimaryKey();
										
										 $testid=$test->Id;
										
										
											$el="Equipment";
											$pel=isset($p['Equipment'])?$p['Equipment']:null ;											
											savebaseparam($el,$pel,$rtid, $testid);									
										
											$el="Indenter";
											$pel=isset($p['Indenter'])?$p['Indenter']:null ;											
											savebaseparam($el,$pel,$rtid, $testid);									
										
											$el="Load";
											$pel=isset($p['Load'])?$p['Load']:null ;											
											savebaseparam($el,$pel,$rtid, $testid);									
										
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid, $testid);									
										
											$el="Temp";
											$pel=isset($p['Temp'])?$p['Temp']:null ;											
											savebaseparam($el,$pel,$rtid, $testid);									
										
											$el="HTBatchNo";
											$pel=isset($p['HTBatchNo'])?$p['HTBatchNo']:null ;											
											savebaseparam($el,$pel,$rtid, $testid);		

											$s1=isset($p['S1'])?$p['S1']:null ;		
											$s2=isset($p['S2'])?$p['S2']:null ;		
											$s3=isset($p['S3'])?$p['S3']:null ;	

											$c1=isset($p['C1'])?$p['C1']:null ;		
											$c2=isset($p['C2'])?$p['C2']:null ;		
											$c3=isset($p['C3'])?$p['C3']:null ;											
										
										$obs=[];
												$obs[]=(object)['SValue'=>$s1,'CValue'=>$c1];
												$obs[]=(object)['SValue'=>$s2,'CValue'=>$c2];
												$obs[]=(object)['SValue'=>$s3,'CValue'=>$c3];
										
											$pel=json_encode($obs) ;
											$min=isset($p['SpecMin'])?$p['SpecMin']:null;
											$max=isset($p['SpecMax'])?$p['SpecMax']:null;											
											saveobsparam("Obs",$pel,$rtid,$min,$max,$testid);		
									$transaction->commit();
								}
								
								
								
							}
						
                  
                            
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;
			   
			   case 'importproofloads':
				
					try{
						
						function savebaseparam($param,$pval,$rtid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>8,':psym'=>$param]]);
										
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->Id;
								$sd->BValue=$pval;
								$sd->save(false);
							
							
						
										}
								
								
       			 				function saveobsparam($el,$elval,$rtid,$min,$max)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>8,':psym'=>$el]]);
										
													$sd=new Rirtestobs;
													$sd->RTID=$rtid;
													$sd->TPID=$e->Id;
													$sd->SpecMin=$min;
													$sd->SpecMax=$max;												
													$sd->save(false);
													
													$rv=new Rirtestobsvalue;
													$rv->RTOBID=$sd->getPrimaryKey();								
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
										}
								
								
								$test=Tests::model()->findByPk(8);
							foreach($data['Products'] as $p)
							{
								
								$rir=Receiptir::model()->find(array('condition'=>'LabNo=:labno',
								'params'=>array(':labno'=>$p['LabNo'],)));
								
								
								if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'])));
									if(empty($rt))
									{
										$transaction=$rir->dbConnection->beginTransaction();
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TUID="PROOF";
										
										$substd=Substandards::model()->find(array('condition'=>'LOWER(Grade)=:no',
								'params'=>array(':no'=>strtolower($p['SubStd']))));
										
										if(!empty($substd))
										{
											$rt->SSID=$substd->Id;
										}
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo=isset($p['FormatNo'])?$p['FormatNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										$rt->ULRNo=isset($p['ULRNo'])?$p['ULRNo']:null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
										$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['TestedBy']))));
								if(!empty($user))
								{
									$rt->TestedBy=$user->Id;
									
								}
										}
										
										if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
								$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['ApprovedBy']))));
								if(!empty($user))
								{
									$rt->ApprovedBy=$user->Id;
								}
										}
										
										$rt->ApprovedDate=isset($p['ApprovedDate'])?date('Y-m-d H:i:s',strtotime($p['ApprovedDate'])):null ;
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										$rt->save(false);
										
										$rtid=$rt->getPrimaryKey();
										
										 
										
										
											$el="Equipment";
											$pel=isset($p['Equipment'])?$p['Equipment']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Temp";
											$pel=isset($p['Temp'])?$p['Temp']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="HTBatchNo";
											$pel=isset($p['HTBatchNo'])?$p['HTBatchNo']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$pel=isset($p['Required'])?$p['Required']:null ;
											$min=null ;
											$max=null;											
											saveobsparam("Required",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['Extension'])?$p['Extension']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Extension",$pel,$rtid,$min,$max);									
										
											$pel=isset($p['Applied'])?$p['Applied']:null ;
											$min=null;
											$max=null;											
											saveobsparam("Applied",$pel,$rtid,$min,$max);									
										
										
										
										
										$transaction->commit();
									}
									else
									{
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->save(false);
									}
								}
								
								
								
							}
						
                  
                            
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
				
			   case 'importimpacts':
				
					try{
						
						function savebaseparam($param,$pval,$rtid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>3,':psym'=>$param]]);
										
										
										$sd=Rirtestbasic::model()->find(['condition'=>'TBPID=:tbid AND RTID=:rtid',
										'params'=>[':tbid'=>$tb->Id,':rtid'=>$rtid]]);
										if(empty($sd))
										{
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->Id;
										}
								$sd->BValue=$pval;
								$sd->save(false);
							
							
						
										}
								
								
       			 				function saveobsparam($el,$elval,$rtid,$min,$max,$vtype)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>3,':psym'=>$el]]);
										
										
										$sd=Rirtestobs::model()->find(['condition'=>'TPID=:tpid AND RTID=:rtid',
										'params'=>[':tpid'=>$e->Id,':rtid'=>$rtid]]);
										if(empty($sd))
										{
												$sd=new Rirtestobs;
													$sd->RTID=$rtid;
													$sd->TPID=$e->Id;
													
										}
											$sd->SpecMin=$min;
													$sd->SpecMax=$max;												
													$sd->save(false);	
													
											$rv=Rirtestobsvalue::model()->find(['condition'=>'RTOBID=:rtobid AND VType=:vtype','params'=>[':rtobid'=>$sd->getPrimaryKey(),':vtype'=>$vtype]]);
													
													if(empty($rv))
													{
															$rv=new Rirtestobsvalue;
															$rv->RTOBID=$sd->getPrimaryKey();	
													}		
																			
													$rv->VType=$vtype;
													$rv->Value=$elval;									
													$rv->save(false);
										}
								
								
								$test=Tests::model()->findByPk(3);
							foreach($data['Products'] as $p)
							{
								
																$batchcode = null;
$batchno = null;

if (isset($p['BatchCode'])) {
    $batchstring = $p['BatchCode'];
    $batch = explode('-', $batchstring);
    $batchcode = $batch[0];  // Default assignment to batchcode

    if (count($batch) > 1) {
        $batchno = $batch[1];  // If there's a second part, assign to batchno
    }
}

// Only query if batchcode is set, and ensure batchno is included only if it's set
$criteria = array(
    'condition' => 'BatchCode=:bc' . ($batchno !== null ? ' AND BatchNo=:bn' : ''),
    'params' => array(':bc' => $batchcode)
);

if ($batchno !== null) {
    $criteria['params'][':bn'] = $batchno;
}

$rir = Receiptir::model()->find($criteria);
								
								
								if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$mdstdsid=$rir->MdsTdsId;										  
										
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq AND TestNo=:testno',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'],':testno'=>$p['TestNo'])));

								if(empty($rt))
									{
										$transaction=$rir->dbConnection->beginTransaction();
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TUID="IMP";
										$rt->TestNo=isset($p['TestNo'])?$p['TestNo']:null ;
										
										
										// $substd=Substandards::model()->find(array('condition'=>'LOWER(Grade)=:no',
								// 'params'=>array(':no'=>strtolower($p['SubStd']))));
										
										// if(!empty($substd))
										// {
											// $rt->SSID=$substd->Id;
											// //--check substd exist in mds or tds if available
											// if(!is_null($rir->MdsTdsId))
											// {
												// $mdstdstests=Mdstdstests::model()->find(['condition'=>'']);
											// }
											
										// }
										// else
										// {
											
										// }
										if(!empty($mdstdsid))
										{
										$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$rt->TestId]]);
									
									if(!empty($mdstdstest))
									{
											$rt->SSID=$mdstdstest->SSDID;
									}
										}
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo="04";
										
										$rt->RevDate = date('Y-m-d', strtotime('2023-01-02'));
										$rt->FormatNo="RFI/LAB/F/10";
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										
									$rt->TestedBy=5;
									
								
										
										
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
											
											if($rt->Status==='complete')
										{
								
									$rt->ApprovedBy=20;
									
								
								
									$str="TC6567";
						$yr="24";
						$str1="1"; //--For multiple locations
						$input=$p['ULRNo'];//$model->Id;
						$str2=str_pad($input, 8, "0", STR_PAD_LEFT);
						$str3="F";
						$rt->ULRNo=$str.$yr.$str1.$str2.$str3;
								
								if ($rt->TestDate) {
											$testDate = new DateTime($rt->TestDate);
											$testDate->modify('+2 days');
											$rt->ApprovedDate = $testDate->format('Y-m-d');
										} else {
											$rt->ApprovedDate = null;  // If TestDate is not set, ApprovedDate will also be null
										}

										}
										
										
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										$rt->save(false);
										
										$transaction->commit();
									}
									else
									{
										
											$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$rt->TestId]]);
									
									if(!empty($mdstdstest))
									{
											$rt->SSID=$mdstdstest->SSDID;
									}
									
									
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										
										$rt->RevDate = date('Y-m-d', strtotime('2023-01-02'));
										
											if($rt->Status==='complete')
										{
								
									$rt->ApprovedBy=20;
									
								
								
									$str="TC6567";
						$yr="24";
						$str1="1"; //--For multiple locations
						$input=$p['ULRNo'];//$model->Id;
						$str2=str_pad($input, 8, "0", STR_PAD_LEFT);
						$str3="F";
						$rt->ULRNo=$str.$yr.$str1.$str2.$str3;
								
								if ($rt->TestDate) {
											$testDate = new DateTime($rt->TestDate);
											$testDate->modify('+2 days');
											$rt->ApprovedDate = $testDate->format('Y-m-d');
										} else {
											$rt->ApprovedDate = null;  // If TestDate is not set, ApprovedDate will also be null
										}

										}
										$rt->save(false);
									}
										
										$rtid=$rt->getPrimaryKey();
										
										 
										
										if(isset($p['ETemp']))
										{
											$el="ETemp";
											$pel=isset($p['ETemp'])?$p['ETemp']:null ;											
											savebaseparam($el,$pel,$rtid);									
										}
										
										if(isset($p['Temp']))
										{
											$el="Temp";
											$pel=isset($p['Temp'])?$p['Temp']:null ;											
											savebaseparam($el,$pel,$rtid);									
										}
										if(isset($p['Size']))
										{
											$el="Size";
											$pel=isset($p['Size'])?$p['Size']:null ;											
											savebaseparam($el,$pel,$rtid);									
										}
										if(isset($p['Orientation']))
										{
											$el="Orientation";
											$pel=isset($p['Orientation'])?$p['Orientation']:null ;											
											savebaseparam($el,$pel,$rtid);									
										}
										if(isset($p['Description']))
										{
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
										}
										if(isset($p['Dimension']))
										{
											$el="Dimension";
											$pel=isset($p['Dimension'])?$p['Dimension']:null ;											
											savebaseparam($el,$pel,$rtid);									
										}
										
										
										if(isset($p['Equip']))
										{
											$el="Equip";
											$pel='IMPACT TESTING MACHINE';											
											savebaseparam($el,$pel,$rtid);									
										}
										
										if(isset($p['Energy']))
										{
											$el="Energy";
											$pel=isset($p['Energy'])?$p['Energy']:null ;											
											savebaseparam($el,$pel,$rtid);									
										}
										
										
										$specmin=null;
										$specmax=null;
										if(!empty($mdstdstest))
														{
															$mdstdsobs=Mdstdstestobsdetails::model()->with('p')->find(['condition'=>'MTTID=:mttid AND p.TestId="3" AND p.PSymbol="IMP" ',
															'params'=>[':mttid'=>$mdstdstest->Id]]);
															
															if(!empty($mdstdsobs))
															{
																$specmin=empty($mdstdsobs)?null:$mdstdsobs->SpecMin;
																$specmax=empty($mdstdsobs)?null:$mdstdsobs->SpecMax;
															}
															
															
														}
														else
														{
															$ssdt=Stdsubdetails::model()->with('p')->find(array('condition'=>'SubStdId =:ssid AND p.TestId="1" AND p.PSymbol="IMP"',
													'params'=>array(':ssid'=>$rt->SSID,)));	
															if(!empty($ssdt))
															{
																$specmin=empty($ssdt)?null:(float)$ssdt->SpecMin;
																$specmax=empty($ssdt)?null:(float)$ssdt->SpecMax;
															}	
															
														}
											
											$min=$specmin;
											$max=$specmax;	
											
										
										if(isset($p['Value1']))
										{
											
											$pel=isset($p['Value1'])?$p['Value1']:null ;
																					
											saveobsparam("IMP",$pel,$rtid,$min,$max,1);	
	saveobsparam("SS","Fully Broken",$rtid,$min,$max,1);													
										}
										if(isset($p['Value2']))
										{
											$pel=isset($p['Value2'])?$p['Value2']:null ;
																				
											saveobsparam("IMP",$pel,$rtid,$min,$max,2);	
	saveobsparam("SS","Fully Broken",$rtid,$min,$max,2);													
										}
										if(isset($p['Value3']))
										{
											$pel=isset($p['Value3'])?$p['Value3']:null ;
																				
											saveobsparam("IMP",$pel,$rtid,$min,$max,3);
	saveobsparam("SS","Fully Broken",$rtid,$min,$max,3);													
										}
										
																					
																	
									
										
										
										
									}
									
							}
								
								
							
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
			
				case 'importtensiles':
				
					try{
						
						function savebaseparam($param,$pval,$rtid)
										{	
$tb=Testbasicparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>2,':psym'=>$param]]);
										
										
											$sd=Rirtestbasic::model()->find(['condition'=>'TBPID=:tbid AND RTID=:rtid',
										'params'=>[':tbid'=>$tb->Id,':rtid'=>$rtid]]);
										if(empty($sd))
										{
										
								$sd=new Rirtestbasic;
								$sd->RTID=$rtid;
								$sd->TBPID=$tb->Id;
										}
								$sd->BValue=$pval;
								$sd->save(false);
							
							
						
										}
								
								
       			 				function saveobsparam($el,$elval,$rtid,$min,$max)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>2,':psym'=>$el]]);
										$sd=Rirtestobs::model()->find(['condition'=>'RTID=:rtid AND TPID=:tpid','params'=>[':rtid'=>$rtid,':tpid'=>$e->Id]]);
													if(empty($sd))
													{
														$sd=new Rirtestobs;
														$sd->RTID=$rtid;
														$sd->TPID=$e->Id;
													}
													$sd->SpecMin=$min;
													$sd->SpecMax=$max;												
													$sd->save(false);
													
													
													$rv=Rirtestobsvalue::model()->find(['condition'=>'RTOBID=:rtobid','params'=>[':rtobid'=>$sd->getPrimaryKey()]]);
													
													if(empty($rv))
													{
															$rv=new Rirtestobsvalue;
															$rv->RTOBID=$sd->getPrimaryKey();	
													}							
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
										}
								
								
								$test=Tests::model()->findByPk(2);
							foreach($data['Products'] as $p)
							{
								
																$batchcode = null;
$batchno = null;

if (isset($p['BatchCode'])) {
    $batchstring = $p['BatchCode'];
    $batch = explode('-', $batchstring);
    $batchcode = $batch[0];  // Default assignment to batchcode

    if (count($batch) > 1) {
        $batchno = $batch[1];  // If there's a second part, assign to batchno
    }
}

// Only query if batchcode is set, and ensure batchno is included only if it's set
$criteria = array(
    'condition' => 'BatchCode=:bc' . ($batchno !== null ? ' AND BatchNo=:bn' : ''),
    'params' => array(':bc' => $batchcode)
);

if ($batchno !== null) {
    $criteria['params'][':bn'] = $batchno;
}

$rir = Receiptir::model()->find($criteria);
								
								
								if(!empty($rir))
								{
									$ririd=$rir->getPrimaryKey();
									
									$mdstdsid=$rir->MdsTdsId;										  
										$transaction=$rir->dbConnection->beginTransaction();
										
										
										
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq AND TestNo=:testno',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'],':testno'=>$p['TestNo'])));

									if(empty($rt))
									{
										
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TUID="TENSILE";
									$rt->TestNo=isset($p['TestNo'])?$p['TestNo']:null ;
									
									$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$rt->TestId]]);
									
									if(!empty($mdstdstest))
									{
											$rt->SSID=$mdstdstest->SSDID;
									}
									else
									{
										$rt->SSID=null;
									}
										
										
										
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate=isset($p['RevDate'])?$p['RevDate']:null ;
										$rt->FormatNo='RFI/LAB/F/09' ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										
										
										
									$rt->TestedBy=6;
									
								
										
										
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										
										
											if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
								
									$rt->ApprovedBy=20;
									
								
								
									$str="TC6567";
						$yr="24";
						$str1="1"; //--For multiple locations
						$input=$p['ULRNo'];//$model->Id;
						$str2=str_pad($input, 8, "0", STR_PAD_LEFT);
						$str3="F";
						$rt->ULRNo=$str.$yr.$str1.$str2.$str3;
								
								if ($rt->TestDate) {
											$testDate = new DateTime($rt->TestDate);
											$testDate->modify('+2 days');
											$rt->ApprovedDate = $testDate->format('Y-m-d');
										} else {
											$rt->ApprovedDate = null;  // If TestDate is not set, ApprovedDate will also be null
										}

										}

										
										
										
										$rt->save(false);
									}
									else
									{
										$rt->TestNo=isset($p['TestNo'])?$p['TestNo']:null ;
											if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
								
									$rt->ApprovedBy=20;
									
								
								
									$str="TC6567";
						$yr="24";
						$str1="1"; //--For multiple locations
						$input=$p['ULRNo'];//$model->Id;
						$str2=str_pad($input, 8, "0", STR_PAD_LEFT);
						$str3="F";
						$rt->ULRNo=$str.$yr.$str1.$str2.$str3;
								
								if ($rt->TestDate) {
											$testDate = new DateTime($rt->TestDate);
											$testDate->modify('+2 days');
											$rt->ApprovedDate = $testDate->format('Y-m-d');
										} else {
											$rt->ApprovedDate = null;  // If TestDate is not set, ApprovedDate will also be null
										}

										}
										
											
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										
										$rt->save(false);
									}
										
										$rtid=$rt->getPrimaryKey();
										
										 
										 
										 
										 	$specmin="";
	$specmax="";
	
												
													
										
										
											$el="Equipment";
											$pel=isset($p['Equipment'])?$p['Equipment']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Temp";
											$pel=isset($p['Temp'])?$p['Temp']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="HTBatchNo";
											$pel=isset($p['HTBatchNo'])?$p['HTBatchNo']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$el="Description";
											$pel=isset($p['Description'])?$p['Description']:null ;											
											savebaseparam($el,$pel,$rtid);									
										
											$parameters = ['SS', 'GD', 'A', 'GL', 'PL', 'UL', 'FL', 'FD', 'EX', 'PS', 'UTS', 'EL', 'RA', 'YM', 'YS'];

foreach ($parameters as $param) {
	
	if(!is_null($rt->SSID))
													{
														
														
														if(!empty($mdstdstest))
														{
															$mdstdsobs=Mdstdstestobsdetails::model()->with('p')->find(['condition'=>'MTTID=:mttid AND p.TestId="2" AND p.PSymbol=:psym ',
															'params'=>[':mttid'=>$mdstdstest->Id,':psym'=>$param]]);
															
															if(!empty($mdstdsobs))
															{
																$specmin=empty($mdstdsobs)?null:$mdstdsobs->SpecMin;
																$specmax=empty($mdstdsobs)?null:$mdstdsobs->SpecMax;
															}
															
															
														}
														else
														{
															$ssdt=Stdsubdetails::model()->with('p')->find(array('condition'=>'SubStdId =:ssid AND p.TestId="2" AND p.PSymbol=:psym',
													'params'=>array(':ssid'=>$rt->SSID,':psym'=>$param)));	
															if(!empty($ssdt))
															{
																$specmin=empty($ssdt)?null:(float)$ssdt->SpecMin;
																$specmax=empty($ssdt)?null:(float)$ssdt->SpecMax;
															}	
														}
														
														
													}	
    $pel = isset($p[$param]) ? $p[$param] : null;
    saveobsparam($param, $pel, $rtid, $specmin, $specmax);
}								
										
										
										
								
									$transaction->commit();
								}
								
								
								
							}
						
                  
                            
                      
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
				 
				
				case 'importchemicals':				
					try{
       			 				function saveel($el,$elval,$rtid,$elmin,$elmax)
										{					
											
												$e=Testobsparams::model()->find(['condition'=>'TestId=:testid AND PSymbol=:psym',
										'params'=>[':testid'=>1,':psym'=>$el]]);
										
													$sd=Rirtestobs::model()->find(['condition'=>'RTID=:rtid AND TPID=:tpid','params'=>[':rtid'=>$rtid,':tpid'=>$e->Id]]);
													if(empty($sd))
													{
														$sd=new Rirtestobs;
														$sd->RTID=$rtid;
														$sd->TPID=$e->Id;
													}
										
													
													$sd->SpecMin=$elmin;
													$sd->SpecMax=$elmax;												
													$sd->save(false);
													
													
													$rv=Rirtestobsvalue::model()->find(['condition'=>'RTOBID=:rtobid','params'=>[':rtobid'=>$sd->getPrimaryKey()]]);
													
													if(empty($rv))
													{
															$rv=new Rirtestobsvalue;
															$rv->RTOBID=$sd->getPrimaryKey();	
													}
													
												
																				
													$rv->VType=null;
													$rv->Value=$elval;									
													$rv->save(false);
										}
										
										
										
								$test=Tests::model()->findByPk(1);
								
								
							foreach($data['Products'] as $p)
							{
								
								$rir=Receiptir::model()->find(array('condition'=>'BatchCode=:bc AND IsMdsTds="mds"',
								'params'=>array(':bc'=>$p['BatchCode'])));
								
								
								if(!empty($rir))
								{
									$transaction=$rir->dbConnection->beginTransaction();
									$ririd=$rir->getPrimaryKey();
									
									$mdstdsid=$rir->MdsTdsId;
										  
										
									$rt=Rirtestdetail::model()->find(array('condition'=>'RIRId=:ririd AND TSeq=:tseq AND TestNo=:testno',
								'params'=>array(':ririd'=>$ririd,':tseq'=>$p['TSeq'],':testno'=>$p['TestNo'])));
									if(empty($rt))
									{
										
										$rt=new Rirtestdetail;
										$rt->RIRId=$ririd;
										$rt->TestId=$test->Id;
										$rt->TestName=isset($p['TestName'])?$p['TestName']:null ;
										$rt->TSeq=isset($p['TSeq'])?$p['TSeq']:null ;
										$rt->TUID="CHEM";
										
										
										
											$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$rt->TestId]]);
									
									if(!empty($mdstdstest))
									{
											$rt->SSID=$mdstdstest->SSDID;
									}
									
										
										// if(!is_null($p['Standard']) && !is_null($p['SubStd']))
										// {
										// $substd=Substandards::model()->with('std')->find(array('condition'=>'LOWER(std.Standard) LIKE :std AND LOWER(t.Grade) LIKE :no',
										// 'params'=>array(':std'=>'%'.strtolower($p['Standard']).'%', ':no'=>'%'.strtolower($p['SubStd']).'%', )));
										
										// if(!empty($substd))
										// {
											// $rt->SSID=$substd->Id;
										// }
										// }
										
										if(!is_null($p['TestMethod']))
										{
										$method=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:no',
								'params'=>array(':no'=>strtolower($p['TestMethod']))));
										
										if(!empty($method))
										{
											$rt->TMID=$method->Id;
										}
										}
										
										$rt->ExtraInfo=isset($p['ExtraInfo'])?$p['ExtraInfo']:null ;
										$rt->RevNo=isset($p['RevNo'])?$p['RevNo']:null ;
										$rt->RevDate = date('Y-m-d', strtotime('2023-01-02'));
										$rt->FormatNo="RFI/LAB/F/49";
										$rt->TestNo=isset($p['TestNo'])?$p['TestNo']:null ;
										
										$rt->Status=isset($p['Status'])?$p['Status']:null ;
										
										if(isset($p['TestedBy']) && !is_null($p['TestedBy']))
										{
										$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['TestedBy']))));
								if(!empty($user))
								{
									$rt->TestedBy=$user->Id;
									
								}
										}
										
										
										
										
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->Note=isset($p['Note'])?$p['Note']:null ;
										$rt->Remark=isset($p['Remark'])?$p['Remark']:null ;
										
										
												if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
								$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['ApprovedBy']))));
								if(!empty($user))
								{
									$rt->ApprovedBy=$user->Id;
									
								}
								
									$str="TC6567";
						$yr="24";
						$str1="1"; //--For multiple locations
						$input=$p['ULRNo'];//$model->Id;
						$str2=str_pad($input, 8, "0", STR_PAD_LEFT);
						$str3="F";
						$rt->ULRNo=$str.$yr.$str1.$str2.$str3;
								
								if ($rt->TestDate) {
											$testDate = new DateTime($rt->TestDate);
											$testDate->modify('+2 days');
											$rt->ApprovedDate = $testDate->format('Y-m-d');
										} else {
											$rt->ApprovedDate = null;  // If TestDate is not set, ApprovedDate will also be null
										}

										}

										$rt->save(false);
										
										
										
									}									
										else
									{
										
										
											$mdstdstest=Mdstdstests::model()->find(['condition'=>'MTID=:mtid AND TID=:tid',
									'params'=>[':mtid'=>$mdstdsid,':tid'=>$rt->TestId]]);
									
									if(!empty($mdstdstest))
									{
											$rt->SSID=$mdstdstest->SSDID;
									}
									
										if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
												$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
												'params'=>array(':no'=>strtolower($p['ApprovedBy']))));
												if(!empty($user))
												{
													$rt->ApprovedBy=$user->Id;
													
												}
												
													$str="TC6567";
										$yr="24";
										$str1="1"; //--For multiple locations
										$input=$p['ULRNo'];//$model->Id;
										$str2=str_pad($input, 8, "0", STR_PAD_LEFT);
										$str3="F";
										$rt->ULRNo=$str.$yr.$str1.$str2.$str3;
								
								

										}
										$rt->ReqDate=isset($p['ReqDate'])?date('Y-m-d',strtotime($p['ReqDate'])):null ;
										$rt->TestDate=isset($p['TestDate'])?date('Y-m-d',strtotime($p['TestDate'])):null ;
										$rt->CreationDate=isset($p['ReceiptOn'])?date('Y-m-d H:i:s',strtotime($p['ReceiptOn'])):null ;
										$rt->TestDate = isset($p['TestDate']) ? date('Y-m-d', strtotime($p['TestDate'])) : null;
										$rt->CreationDate = isset($p['ReceiptOn']) ? date('Y-m-d H:i:s', strtotime($p['ReceiptOn'])) : null;
										
										$rt->RevDate = date('Y-m-d', strtotime('2023-01-02'));
										
										if(isset($p['ApprovedBy']) && !is_null($p['ApprovedBy']))
										{
								$user=Users::model()->find(array('condition'=>'LOWER(Email)=:no',
								'params'=>array(':no'=>strtolower($p['ApprovedBy']))));
								if(!empty($user))
								{
									$rt->ApprovedBy=$user->Id;
									
								}
								
									$str="TC6567";
						$yr="24";
						$str1="1"; //--For multiple locations
						$input=$p['ULRNo'];//$model->Id;
						$str2=str_pad($input, 8, "0", STR_PAD_LEFT);
						$str3="F";
						$rt->ULRNo=$str.$yr.$str1.$str2.$str3;
								
								if ($rt->TestDate) {
											$testDate = new DateTime($rt->TestDate);
											$testDate->modify('+2 days');
											$rt->ApprovedDate = $testDate->format('Y-m-d');
										} else {
											$rt->ApprovedDate = null;  // If TestDate is not set, ApprovedDate will also be null
										}

										}

										
										$rt->save(false);
									}
									
									
										
										$rtid=$rt->getPrimaryKey();
										
										/*
										$temp=Rirtestbasic::model()->find(['condition'=>'RTID=:rtid AND TBPID="182"','params'=>[':rtid'=>$rtid]]);
										if(empty($temp))
										{
													$temp=new Rirtestbasic;
													$temp->RTID=$rtid;
													$temp->TBPID=182;
										}
													$temp->BValue="23";
													$temp->save(false);
													
										$caldate=Rirtestbasic::model()->find(['condition'=>'RTID=:rtid AND TBPID="183"','params'=>[':rtid'=>$rtid]]);
										if(empty($caldate))
										{			
													
													$caldate=new Rirtestbasic;
													$caldate->RTID=$rtid;
													$caldate->TBPID=183;
										}
													$caldate->BValue=date('Y-m-d',strtotime('2024-03-07'));
													$caldate->save(false);
											
										$calddate=Rirtestbasic::model()->find(['condition'=>'RTID=:rtid AND TBPID="184"','params'=>[':rtid'=>$rtid]]);
										if(empty($calddate))
										{												
													$calddate=new Rirtestbasic;
													$calddate->RTID=$rtid;
													$calddate->TBPID=184;
										}
													$calddate->BValue=date('Y-m-d',strtotime('2024-11-07'));;
													$calddate->save(false);
											
										$equip=Rirtestbasic::model()->find(['condition'=>'RTID=:rtid AND TBPID="183"','params'=>[':rtid'=>$rtid]]);
										if(empty($equip))
										{												
													$equip=new Rirtestbasic;
													$equip->RTID=$rtid;
													$equip->TBPID=215;
										}
													$equip->BValue="SPECTRO ANALYTICAL INSTRUMENT";
													$equip->save(false);
										
										*/
																		
										$elements = [
    'C', 'Si', 'Mn', 'P', 'S', 'Cr', 'Mo', 'Ni', 'Al', 'Co',
    'Cu', 'Nb', 'Ti', 'V', 'W', 'Pb', 'Sn', 'As', 'Zr', 'Bi',
    'Ca', 'Ce', 'Sb', 'Se', 'Te', 'Ta', 'B', 'Zn', 'La', 'Fe', 'Mg', 'P+S'
];

foreach ($elements as $el) {
	
	$specmin="";
	$specmax="";
	
												if(!is_null($rt->SSID))
													{
														
														
														if(!empty($mdstdstest))
														{
															$mdstdsobs=Mdstdstestobsdetails::model()->with('p')->find(['condition'=>'MTTID=:mttid AND p.TestId="1" AND p.PSymbol=:psym ',
															'params'=>[':mttid'=>$mdstdstest->Id,':psym'=>$el]]);
															
															if(!empty($mdstdsobs))
															{
																$specmin=empty($mdstdsobs)?null:$mdstdsobs->SpecMin;
																$specmax=empty($mdstdsobs)?null:$mdstdsobs->SpecMax;
															}
															
															
														}
														else
														{
															$ssdt=Stdsubdetails::model()->with('p')->find(array('condition'=>'SubStdId =:ssid AND p.TestId="1" AND p.PSymbol=:psym',
													'params'=>array(':ssid'=>$rtds->SSID,':psym'=>$el)));	
															if(!empty($ssdt))
															{
																$specmin=empty($ssdt)?null:(float)$ssdt->SpecMin;
																$specmax=empty($ssdt)?null:(float)$ssdt->SpecMax;
															}	
														}
														
														
													}	
													
    $pel = isset($p[$el]) ? $p[$el] : null;
    saveel($el, $pel, $rtid,$specmin,$specmax);
}
																	
										
																	
										$transaction->commit();	
										
									}
								
								
								
							}
								
								
								
							
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
				
			
				

	case 'importmdstds': 
				$transaction=$model->dbConnection->beginTransaction();
					try{
       			 				
							foreach($data['Products'] as $p)
							{
								
								$mds=Mdstds::model()->find(array('condition'=>'Type=:type AND LOWER(No)=:no',
								'params'=>array(':type'=>$p['Type'],':no'=>strtolower($p['No']))));
								if(empty($mds))
								{
									$mds=new Mdstds;
									$mds->Type=$p['Type'] ;	
									$mds->No=$p['No'] ;	
									$mds->Description=isset($p['Description'])?$p['Description']:null ;										  
									$mds->Status="1";
									$mds->save(false);								
									  
								}
							
								
							}
						
                  
                            
                       $transaction->commit();
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							$transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;	
				
			case 'importsubstd': 
				$transaction=$model->dbConnection->beginTransaction();
					try{
       			 				
							foreach($data['Products'] as $p)
							{
								if($p['Industry'] !='' && $p['SubStd'] !='' )
								{
								
									
								$ind=Industry::model()->find(array('condition'=>'LOWER(Name)=:ind ',
								'params'=>array(':ind'=>strtolower($p['Industry']))));
								
														
								$indid=$ind->getPrimaryKey();
								
								$std=Standards::model()->find(array('condition'=>'LOWER(Standard)=:std',
								'params'=>array(':std'=>strtolower($p['Standard']))));
								
								if(empty($std))
								{
									$std=new Standards;
									$std->Standard=$p['Standard'] ;	
									$std->Description=isset($p['Description'])?$p['Description']:null ;										  
									$std->IndId=$indid;
									$std->Status="1";
									$std->save(false);								
									  
								}
								
								$stdid=$std->getPrimaryKey();
								
								
								$testname=$p['Test'];
								
								
								
								$test=Tests::model()->find(array('condition'=>'TUID=:tn',
								'params'=>array(':tn'=>$testname,)));
								
								
								
								if(!empty($test))
								{
										$testid=$test->getPrimaryKey();
									$substd=Substandards::model()->find(array('condition'=>'LOWER(Grade)=:grade AND StdId=:stdid',
								'params'=>array(':grade'=>strtolower($p['SubStd']),':stdid'=>$stdid)));
								
									if(empty($substd))
									{
										$substd=new Substandards;
										$substd->Grade=$p['SubStd'] ;	
										$substd->ExtraInfo=isset($p['ssdesc'])?$p['ssdesc']:null ;										  
										$substd->StdId=$stdid;
										
										$substd->save(false);
										
										$substdid=$substd->getPrimaryKey();
										
										
										  
									}
									
									$substdid=$substd->getPrimaryKey();
								
								$param=Testobsparams::model()->find(array('condition'=>'LOWER(PSymbol)=:par AND TestId=:tid ',
								'params'=>array(':par'=>strtolower($p['Symbol']),':tid'=>$testid)));
								
								if(empty($param))
								{
									$transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($p['Parameter']));
								}
							
									$paramid=$param->getPrimaryKey();
									
									if(!is_null($p['TestMethod']))
									{
									$testmethod=Testmethods::model()->find(array('condition'=>'LOWER(Method)=:method AND TestId=:testid','params'=>array(':method'=>strtolower($p['TestMethod']),':testid'=>$testid)));
									
									if(empty($testmethod))
									{
										$testmethod=new Testmethods;
										$testmethod->Method=$p['TestMethod'];
										$testmethod->TestId=$testid;
										$testmethod->IndId=$indid;
										$testmethod->save(false);
										
										
									}
									$tmid=empty($testmethod)?null:$testmethod->getPrimaryKey();
									}
									$r=Stdsubdetails::model()->find(array('condition'=>'PId=:pid AND TMID=:tmid AND SubStdId=:subid',
								'params'=>array(':pid'=>$paramid,':tmid'=>$tmid,':subid'=>$substdid)));
								
									if(empty($r)){
										$r=new Stdsubdetails;
										$r->PId=$paramid;
										$r->TMID=$tmid;
$r->TUID=isset($p['Test'])?$p['Test']:null;;												
										$r->SubStdId=$substdid;
									$r->SpecMin=isset($p['SpecMin'])?$p['SpecMin']:null;
									$r->SpecMax=isset($p['SpecMax'])?$p['SpecMax']:null;
									$r->PUnit=isset($p['Unit'])?$p['Unit']:null;
										$r->IsMajor=1;						
										$r->Cost=isset($p['Cost'])?$p['Cost']:0;
									$r->OPId=isset($p['OPId'])?$p['OPId']:null;
									$r->OId=isset($p['OId'])?$p['OId']:null;
									$r->OSID=isset($p['OSID'])?$p['OSID']:null;
									$r->OSSID=isset($p['OSSID'])?$p['OSSID']:null;
										$r->save(false);
										
										
										$substdtest=Substdtests::model()->find(array('condition'=>'SSID=:ssid AND TID=:tid',
								'params'=>array(':ssid'=>$substdid,':tid'=>$testid)));
								
								if(empty($substdtest))
									{
										$substdtest=new Substdtests;
										$substdtest->SSID=$substdid;
										$substdtest->TID=$testid;
										$substdtest->save(false);
									}
									}
									else
									{
										
										$r->TUID=isset($p['Test'])?$p['Test']:null;;	
										$r->SpecMin=isset($p['SpecMin'])?$p['SpecMin']:null;
									$r->SpecMax=isset($p['SpecMax'])?$p['SpecMax']:null;
										$r->save(false);
										
										$substdtest=Substdtests::model()->find(array('condition'=>'SSID=:ssid AND TID=:tid',
								'params'=>array(':ssid'=>$substdid,':tid'=>$testid)));
								
								if(empty($substdtest))
									{
										$substdtest=new Substdtests;
										$substdtest->SSID=$substdid;
										$substdtest->TID=$testid;
										$substdtest->save(false);
									}
									}
								}						
								
							}
							}
                  
                            
                       $transaction->commit();
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							$transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;		
			   case 'importtestparams':
			   $transaction=$model->dbConnection->beginTransaction();
					try{
       			 				
							foreach($data['Products'] as $p)
							{
								$pro=Testobsparams::model()->find(array('condition'=>'Param=:code AND TestId=:tid AND IndId=:ind',
								'params'=>array(':code'=>$p['Parameter'],':tid'=>$p['TestId'],':ind'=>$p['IndId'])));
								
								if(empty($pro))
								{
									$pro=new Testobsparams;
									
									
										  $pro->TestId=$p['TestId'] ;
										  $pro->Symbol=$p['Symbol'];;
										   $pro->PUnit=$p['Unit'];
										  $pro->Param=$p['Parameter'];
										  $pro->PDType=$p['Type'];
										   $pro->IndId=$p['IndId'];
										   $pro->Status="1";
										  $pro->save(false);
									
									  
								}
								
							}
                  
                            
                       $transaction->commit();
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							$transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;
			   	case 'importtestmethod':
			$transaction=$model->dbConnection->beginTransaction();
					try{
       			 				
							foreach($data['Products'] as $p)
							{
								$pro=Testmethods::model()->find(array('condition'=>'Method=:code AND IndId=:ind',
								'params'=>array(':code'=>$p['TestMethod'],':ind'=>$p['IndId'])));
								if(empty($pro))
								{
									$pro=new Testmethods;
									$test=Tests::model()->find(['condition'=>'TestName=:test','params'=>[':test'=>$p['Test']]]);
									if(!empty($test))
									{
											 foreach($p as $var=>$value)
										  {
											// Does the model have this attribute? If not raise an error
											if($pro->hasAttribute($var))
												$pro->$var = $value;
										  }	
										  $pro->TestId=$test->Id;
										  $pro->Method=$p['TestMethod'];
										  $pro->save(false);
									}
										
									  
									  
								}
								
							}
                  
                            
                       $transaction->commit();
                $this->_sendResponse(200, CJSON::encode("Updated"));
				}
					catch(Exception $e)
					{
						
							$transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
                break;
				
				case 'addquote': 
					$transaction=$model->dbConnection->beginTransaction();
				try{
					
					$qc=Quotation::model()->count();
					$qc++;
					
					$set=Settingsaccount::model()->find();
					 $qalpha=$set->QAlpha;
				 $qyear = date("y");
					
					 $model->CustId=$data['CustId'];
                  $model->QDate=date('Y-m-d H:i:s',strtotime($data['QDate']));
				   $model->ValidDate=date('Y-m-d H:i:s',strtotime($data['ValidDate']));
					$model->SubTotal=$data['SubTotal'];
					$model->Discount=$data['Discount'];
					$discount=($model->SubTotal*$model->Discount)/100;
				  $model->IsTax=isset($data['IsTax'])?$data['IsTax']:0;
				   $model->Tax=isset($data['Tax'])?$data['Tax']:0;
				   $model->TotTax=$data['TotTax'];
				   $model->Total=$model->SubTotal  + $model->TotTax - $discount;
				   $model->Note=empty($data['Note'])?"":$data['Note'];
				   $model->CreatedBy=$data['CreatedBy'];
				   $model->AssignedTo=isset($data['AssignedTo'])?$data['AssignedTo']:null;
				      $model->SampleGroup=isset($data['SampleGroup'])?$data['SampleGroup']:null;
				    $model->SampleConditions=isset($data['SampleConditions'])?$data['SampleConditions']:null;
				     $model->EndUse=isset($data['EndUse'])?$data['EndUse']:null;
				     $model->Specifications=isset($data['Specifications'])?$data['Specifications']:null;
				     $model->DrawnBy=isset($data['DrawnBy'])?$data['DrawnBy']:null;
				     $model->ModeOfReceipt=isset($data['ModeOfReceipt'])?$data['ModeOfReceipt']:null;
				   
				    $model->Status="Pending";
				  $model->CreatedOn=date('Y-m-d H:i:s');
				   
				   
				  $web = array();
				 $qalpha=Extrasettings::model()->find(array('condition'=>'Name="CALPHA"'));
				 
				 $qyear = date("y");
              
				 // $web[]=str_pad( $model->getPrimaryKey(),4, "0", STR_PAD_LEFT );
				  

                    $qc=str_pad( $qc,5, "0", STR_PAD_LEFT );
				  $orderno=$qalpha->Value.$qyear.$qc; 
                  $model->QNo=$orderno;				    
                  $model->save(false);
				  
				   foreach($data['Details'] as $p)
                  {

                      $od=new Quotationdetails;
                      $od->QId=$model->getPrimaryKey();	
						  $od->IndId=$p['IndId'];
						    $od->PIndId="";//$p['PIndId'];
							 $od->SampleName=$p['SampleName'];
							  $od->Description=isset($p['Description'])?$p['Description']:null;
							  $od->SampleWeight=isset($p['SampleWeight'])?$p['SampleWeight']:null;
							   $od->TAT=isset($p['TAT'])?$p['TAT']:null;
							  $od->SubIndId="";//$p['SubIndId'];
						    $od->TestCondId=$p['TestCondId'];
							  $od->TestId=$p['TestId'];
							    $od->TSeq=$p['TSeq'];
							    $od->PlanId=isset($p['PlanId'])?$p['PlanId']:null;
                      $od->SubStdId=$p['SubStdId'];
					   $od->ExtraDetails=isset($p['ExtraDetails'])?$p['ExtraDetails']:null;
                      $od->TestMethodId=isset($p['TestMethodId'])?$p['TestMethodId']:null;
					    $od->LabIds=isset($p['LabIds'])?json_encode($p['LabIds']):null;
                      $od->Price=$p['Price'];
                      $od->Qty=$p['Qty'];
                      $od->Total=$p['Qty']*$p['Price'];
                      $od->Tax=0;
                      $od->save(false);

                     
						 
                  }
				 
				 $transaction->commit();
				 $this->_sendResponse(200, CJSON::encode("Added"));
				}
				catch(Exception $e)
				{
					 $transaction->rollback();
              $this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
        
			    case 'addinvoice':
				
				$transaction=$model->dbConnection->beginTransaction();
				try{
					 $model->CustId=$data['CustId'];
					 $model->Reference=$data['PoNos'];
                  $model->InvDate=date('Y-m-d H:i:s',strtotime($data['InvDate']));
				   $model->DueDate=date('Y-m-d H:i:s',strtotime($data['DueDate']));
                  $model->SubTotal=$data['Subtotal'];
                  $model->Discount=$data['Discount'];
				 
				   $model->Tax=$data['Tax'];
				   $model->TotTax= ($model->SubTotal * $data['Tax'])/100;
				   
				   $discamt=($model->SubTotal + $model->TotTax)*$model->Discount/100;
				   
				   $model->Total=$model->SubTotal + $model->TotTax - $discamt;
				   $model->Note=empty($data['Note'])?"":$data['Note'];
				   $model->PayStatus="Pending";
				   $model->save(false);
				   
				   // $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                  // $web = array(); //remember to declare $web as an array
                  // $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
                  // for ($i = 0; $i < 4; $i++) {
                    // $n = rand(0, $alphaLength);
                    // $web[] = $alphabet[$n];
                  // }
				  
				  $web = array();
				   $web[]='I';
				   $web[]='V';

                //  $web[] =str_pad( $order->getPrimaryKey(), 2, "0", STR_PAD_LEFT );

                 // $numeric = $model->getPrimaryKey();
				  $web[]=str_pad( $model->getPrimaryKey(),4, "0", STR_PAD_LEFT );
				  //'1234567890';
                  //remember to declare $web as an array
                  // $numLength = strlen($numeric) - 1; //put the length -1 in cache
                  // for ($i = 0; $i < 4; $i++) {
                    // $n = rand(0, $numLength);
                    // $web[] = $numeric[$n];
                  // }

                  $orderno=implode($web); 

                  $model->InvoiceNo=$orderno;
				  $model->CreatedOn=date('Y-m-d H:i:s');
                  $model->save(false);
				  
				   foreach($data['Details'] as $p)
                  {

                      $od=new Invoicedetails;
                      $od->InvId=$model->getPrimaryKey();	
$od->IndId=isset($p['IndId'])?$p['IndId']:null;
$od->SampleName=isset($p['SampleName'])?$p['SampleName']:null;
$od->SubStdId=isset($p['SubStdId'])?$p['SubStdId']:null;
$od->TestId=isset($p['TestId'])?$p['TestId']:null;
$od->TestCondId=isset($p['TestCondId'])?$p['TestCondId']:null;
$od->TestMethodId=isset($p['TestMethodId'])?$p['TestMethodId']:null;					  
                      $od->Details=isset($p['Details'])?$p['Details']:null;;
					   $od->DDesc=isset($p['DDesc'])?$p['DDesc']:null;
                     
                      $od->Price=$p['Price'];
                      $od->Qty=$p['Qty'];
                      $od->Total=$p['Qty']*$p['Price'];
                      $od->Tax=0;
                      $od->save(false);

                      
						 
                  }
				 
				 $transaction->commit();
				 $this->_sendResponse(200, CJSON::encode("Added"));
				}
				catch(Exception $e)
				{
					 $transaction->rollback();
              $this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
			    case 'startstudexam':
					$exam =Basket::model()->find(array('condition'=>'examcode=:ID',
										 'params'=>array(':ID'=>$data['examcode']),));
				//	$model->examid=$exam->id;
					$model->starttime=strtotime(date('Y-m-d H:i:s')) * 1000;
					$date = date('Y-m-d H:i:s',strtotime("+".$exam->tt." minutes"));
									
					$time = strtotime($date) * 1000;
					
					$model->result=$exam->tt;
					$model->endtime=$time;
					$model->save(false);
					
					 $this->_sendResponse(200, CJSON::encode($model));
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
				
				case 'custdashinfo': 
				case 'activitylog':$model=Users::model()->findByPk($_GET['id']);break;
				case 'getfullrir': $model=Receiptir::model()->findByPK($_GET['id']);break;
				case 'updatetestplan': $model=Stdsubplans::model()->findByPk($_GET['id']);break;
				case 'substdparams':$model=Substandards::model()->findByPk($_GET['id']);break;
				case 'testeditdata':$model=Tests::model()->findByPk($_GET['id']);break;
				case 'testpredata':$model=Users::model()->findByPk($_GET['id']);break;
				case 'getquoteeditdata':$model=Quotation::model()->findByPk($_GET['id']);break;
				case 'updatelabaccredit':$model=Labaccredit::model()->findByPk($_GET['id']);break;
				case 'labaccredits':
				case 'getdashinfo':$model=Users::model()->findByPk($_GET['id']);break;
				// Find respective model
				case 'getponos':$model=Customerinfo::model()->findByPk($_GET['id']);      		break;
					case 'approveinvoice':$model=Invoices::model()->findByPk($_GET['id']);      		break;
				case 'approvequote':$model=Quotation::model()->findByPk($_GET['id']);      		break;
				case 'updaterole':$model = Roles::model()->findByPk($_GET['id']);      		break;
				case 'roles':
				case 'testrates':$model = Users::model()->findByPk($_GET['id']);      		break;
				case 'testupdate':$model=Tests::model()->findByPk($_GET['id']);break;
				
				case 'industryupdate':$model=Industry::model()->findByPk($_GET['id']);break;
				
				case 'updinvent':$model=Inventory::model()->findByPk($_GET['id']);break;
				case 'industries':
				case 'inventories':
				case 'users':
					$model = Users::model()->findByPk($_GET['id']);                    
					break;
						case 'sendtestreport':$model=Rirtestdetail::model()->findByPk($_GET['id']);  break;
					case 'sendverifyquote':$model=Quotation::model()->findByPk($_GET['id']);  break;
					case 'sendquote':$model=Quotation::model()->findByPk($_GET['id']);  break;
				case 'sendinvoice':	
				case 'updinvoice':	$model=Invoices::model()->findByPk($_GET['id']);  break;
					case 'updquote':	$model=Quotation::model()->findByPk($_GET['id']);  break;
				case 'getquotepredata':
				case 'getquotes':$model=Users::model()->findByPk($_GET['id']);                    
					break;				
				case 'getinvoices':$model=Users::model()->findByPk($_GET['id']);                    
					break;
				case 'getpayments':$model=Users::model()->findByPk($_GET['id']);                    
					break;	
					case 'getexpenses':$model=Users::model()->findByPk($_GET['id']);                    
					break;
					
					case 'ponoinvoice':$model=Users::model()->findByPk($_GET['id']);                    
					break;
					
				case 'bcinvoice':$model=Users::model()->findByPk($_GET['id']);                    
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
				case 'custdashinfo': 
				case 'activitylog':
				case 'getfullrir':
				case 'updatetestplan':
				case 'substdparams':
				case 'testeditdata':
				case 'testpredata':
				case 'getquoteeditdata':
				case 'labaccredits':
				case 'getdashinfo':$data=$put_vars;break;	
				case 'getponos':
				case 'approveinvoice':
				case 'approvequote':
				case 'updatelabaccredit':
	case 'updaterole':	
			 $data=$put_vars;
			foreach($put_vars as $var=>$value) {
									// Does model have this attribute? If not, raise an error
									if($model->hasAttribute($var))
										$model->$var = $value;
					}		
					break;	
			case 'roles':
case 'testupdate':			
				case 'testrates':
			case 'industryupdate':
				case 'updinvent':
				$data=$put_vars;
				foreach($put_vars as $var=>$value) {
				// Does model have this attribute? If not, raise an error
				if($model->hasAttribute($var))
				$model->$var = $value;}
			break;
			case 'sendverifyquote':
			case 'sendtestreport':
			case 'sendquote':
			case 'industries':
			case 'inventories':
				 case 'sendinvoice':
				 	case 'updquote':
					case 'updinvoice':
				case 'bcinvoice':
				case 'ponoinvoice':
				case 'expenses':
				case 'getquotepredata':
				case 'getquotes':
				case 'getexpenses':
				case 'getpayments':
				case 'getinvoices':$data=$put_vars;	break;
		 
		 
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
				case 'custdashinfo': 
					try{
						
					$cust=Customerinfo::model()->find(array('condition'=>'UserId=:uid','params'=>array(':uid'=>$model->Id)));		
					$rirs=Receiptir::model()->findAll(array('condition'=>'CustomerId=:custid',
					'params'=>array(':custid'=>$cust->Id),
					'order'=>'Id Desc'));
					
					$totalcount=count($rirs);
					
					if(isset($data['pl']))
				{
				$rirs=Receiptir::model()->findAll(array(
    'order' => 'Id desc',
    'limit' => $data['pl'],
    'offset' => ($data['pn']-1)*$data['pl']
));
				}
					
					$allrirs=array();
					foreach($rirs as $r)
					{
							
							$testdetails=array();
							foreach($r->rirtestdetails as $t)
							{
								$pdfpath="";
								
								if(!empty($t->ApprovedBy))
								{
								$pdfpath=Yii::app()->params['base-url']."pdf/testreport/".$t->rIR->BatchCode."_".$t->TestNo.".pdf";
								}
								
								$today = date("Y-m-d");
								$expire = $t->ReqDate; //from db
								$overdue="false";
								$today_time = new DateTime($today);
								$expire_time = new DateTime($expire);

								if ($expire_time <$today_time) { $overdue="true"; }
								
								$testdetails[]=(object)array('TestName'=>$t->TestName,
								'ReqDate'=>$t->ReqDate,'TestNo'=>$t->TestNo,
								'TestId'=>$t->TestId,
								'Pdfpath'=>$pdfpath,
								'Status'=>$t->Status,'Overdue'=>$overdue,);
						
						}
						
						
						
							
							$time=strtotime($r->CreationDate);
							$rirgdt = date('d.m.y',$time);
						
						$allrirs[]=(object)array('Id'=>$r->Id,'SampleName'=>$r->SampleName,'SampleWeight'=>$r->SampleWeight,
						'CustomerId'=>$r->CustomerId,'Customer'=>$r->customer->Name,'TAT'=>$r->TAT,'CustEmail'=>$r->customer->Email,
						'SampleCondition'=>$r->SampleCondition,'IndId'=>$r->IndId,'Industry'=>implode(" - ",array_reverse( MyFunctions::getParentCat($r->IndId))),
						'NoOfSamples'=>$r->NoOfSamples,'BatchCode'=>$r->BatchCode,'GeneratedDate'=>$rirgdt,
						'RefPurchaseOrder'=>$r->RefPurchaseOrder,'PODate'=>$r->PODate,
						'LabNo'=>$r->LabNo,'alltests'=>$testdetails);
					}
					
					
					
					
				
				
				$industries=Industry::model()->findAll(array('condition'=>'ParentId is null AND Status="1"'));
				$allindustries=[];
				foreach($industries as $c)
							{
								$parents = array();
									$allindustries[]=(object)array('Id'=>$c->Id,'Name'=>$c->Name,
									'PTree'=>implode(" - ",array_reverse(MyFunctions::getParentCat($c->Id))),
									'Children'=>MyFunctions::parseTree($c->Id));
									
							}
					
				
					
					
					
					$alllabs=Labaccredit::model()->findAll();
					$set=Settings::model()->find();
					
				$customers=Customerinfo::model()->findAll(array('condition'=>'UserId=:uid','params'=>array(':uid'=>$model->Id)));

				
				
							
						$allquotes=[];
						//$invs=Quotation::model()->findAll(array('order'=>'Id Desc'));
						
				$quotes=Quotation::model()->findAll(array(
    'order' => 'Id desc',
	'condition'=>'CustId=:cust',
	'params'=>array(':cust'=>$cust->Id),
    'limit' =>5
   
));
					
						foreach($quotes as $i)
						{
							$creator="";
							if(!empty($i->CreatedBy))
							{
								$user=Users::model()->findByPk($i->CreatedBy);
								$creator=empty($user)?null:$user->FName;
							}
							
							
							$assignuser="";
							if($i->AssignedTo)
							{
								$user=Users::model()->findByPk($i->AssignedTo);
								$assignuser=empty($user)?null:$user->FName;
							}
							
							$qdetails=[];
							foreach($i->quotationdetails as $d)
							{
								$testcond=Testconditions::model()->findByPk($d->TestCondId);
								$testcondition=$testcond->Name;
								
								$test=Tests::model()->findByPk($d->TestId);
								$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
								
								$substd=Substandards::model()->findByPk($d->SubStdId);
								$substdname=$substd->std->Standard." - ".$substd->Grade;
								
								
								$labnames=[];
								if(!empty(json_decode($d->LabIds)))
								{
									foreach(json_decode($d->LabIds) as $l)
									{
										$lab=Labaccredit::model()->findByPk($l);
										$labnames[]=$lab->Name;
									}
								}
								
								$plan=[];
								$sds=[];
								if(!empty($d->PlanId))
								{
									$pl=Stdsubplans::model()->findByPk($d->PlanId);
									$parameters=[];
									foreach($pl->stdsubplandetails as $pd)
									{
										$parameters[]=(object)['Parameter'=>$pd->sSD->p->Parameter,'PSymbol'=>$pd->sSD->p->PSymbol,'TestMethod'=>$pd->sSD->tM->Method,
						'PCatId'=>$pd->sSD->p->PCatId,'CatName'=>empty($pd->sSD->p->pCat)?"":$pd->sSD->p->pCat->CatName,];
										
									}
									
									$plan=(object)array('Id'=>$pl->Id,'Name'=>$pl->Name,'SubStdId'=>$pl->SubStdId,'Parameters'=>$parameters);
								}
								else
								{
									$sds=[];
								$stdsubdets=$substd->stdsubdetails(array('condition'=>'IsMajor=1'));
								foreach($stdsubdets as $f)
								{
										$sds[]=(object)['Parameter'=>$f->p->Parameter,'PSymbol'=>$f->p->PSymbol,
						'PCatId'=>$f->p->PCatId,'CatName'=>empty($f->p->pCat)?"":$f->p->pCat->CatName,];
						
						;
								}
								}
								
								$tseq=$d->TSeq;
								$testname="";
								if(!is_null($tseq))
								{
									$pieces = explode("-", $tseq);
									$testname=$test->TestName.'-'.$pieces[1];
								}
								else
								{
									$testname=$test->TestName;
								}
								
								$qdetails[]=(object)array('Id'=>$d->Id,'QId'=>$d->QId,'IndId'=>$d->IndId,
								'PIndId'=>$d->PIndId,'SubIndId'=>$d->SubIndId,'SampleName'=>$d->SampleName,
								'SampleWeight'=>$d->SampleWeight,'TAT'=>$d->TAT,
								'Industry'=>implode(" - ",array_reverse( MyFunctions::getParentCat($d->IndId))),'TestCondId'=>$d->TestCondId,'TestId'=>$d->TestId,'labnames'=>$labnames,'PlanId'=>$d->PlanId,'Plan'=>empty($plan)?null:$plan->Name,'TSeq'=>$d->TSeq,
								'Parameters'=>empty($plan)?$sds:$plan->Parameters,
								'testcondition'=>$testcondition,'TestName'=>$testname,'SubStdId'=>$d->SubStdId,
								'SubStdName'=>$substdname,'TestMethod'=>empty($testmethod)?null:$testmethod->Method,
								'TestMethodId'=>$d->TestMethodId,'Price'=>$d->Price,'Qty'=>$d->Qty,'Tax'=>$d->Tax,'Total'=>$d->Total,
								);
							}
							
							$allquotes[]=(object)['Id'=>$i->Id,'QNo'=>$i->QNo,'Customer'=>$i->cust,'CustId'=>$i->CustId,
							'QDate'=>date('d-m-Y',strtotime($i->QDate)),'ValidDate'=>date('d-m-Y',strtotime($i->ValidDate)),'TotTax'=>$i->TotTax,'Tax'=>$i->Tax,'SampleGroup'=>$i->SampleGroup,'SampleConditions'=>$i->SampleConditions,'DrawnBy'=>$i->DrawnBy,'ModeOfReceipt'=>$i->ModeOfReceipt,'EndUse'=>$i->EndUse,
							'Specifications'=>$i->Specifications,
							'SubTotal'=>$i->SubTotal,'Discount'=>($i->SubTotal* $i->Discount)/100,'Total'=>$i->Total,'Note'=>$i->Note,'Status'=>$i->Status,
							'CreatedBy'=>$i->CreatedBy,'CreatorName'=>$creator,'AssignUser'=>$assignuser,'AssignedTo'=>$i->AssignedTo,
							'ApprovedBy'=>'','VerifiedBy'=>'',
							'Details'=>$qdetails];
						}
						
					
				$testconditions=[];
					
					$result=(object)array('allrirs'=>$allrirs,'totalcount'=>$totalcount,'industries'=>$allindustries,'customers'=>$customers,'testconditions'=>$testconditions,'alllabs'=>$alllabs,'IsTax'=>$set->IsTax,'Tax'=>$set->Tax,'TaxLabel'=>$set->TaxLabel,	'QVDays'=>$set->QVDays,'allquotes'=>$allquotes);
						
						
						$this->_sendResponse(200, CJSON::encode($result));
					}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}				
							break;
				case 'activitylog':
					try{
							$this->_checkAuth(2,'R');
						$totalitems=TblAuditTrail::model()->count();
						
						if(isset($data['pl']))
						{
							$logs=TblAuditTrail::model()->findAll(array(
							  'order' => 'id desc',
								'limit' => 30,
								//'offset' => ($data['pn']-1)*$data['pl'],
								));
										 
						}	
						$alllogs=[];
						
						foreach($logs as $l)
						{
							
							$user=Users::model()->findByPK($l->user_id);
							
							if($l->field !=='Id' || !stristr($l->field, 'Id'))
							{
								switch($l->action)
								{
								
								case 'CREATE':
								
									$description=' created ' .$l->model.'[' . $l->model_id.']'.' '.$l->field.' '.$l->new_value;
								
								break;
								
								case 'SET':
								
									$description= ' set ' .$l->model. '[' . $l->model_id.']'.' '.$l->field.' '.$l->new_value;
								
								break;
								
								case 'CHANGE':
								
									$description= ' changed ' .$l->model. '[' . $l->model_id.']'.' '.$l->field.' '.$l->old_value.' to '.$l->new_value;
								
								break;
								
								case 'DELETE':
								
									$description= ' deleted ' .$l->model. '[' . $l->model_id.']';
								
								break;
								
								
								}
								
								$alllogs[]=(object)array('action'=>$l->action ,'user'=>$user->FName .' '. $user->LName ,
								'description'=>$description,);
							}
							
							
						}
						$result=(object)array('alllogs'=>$alllogs,'totalitems'=>$totalitems);
							$this->_sendResponse(200, CJSON::encode($result));	
						
					}
					catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}				
							break;
				
				
				case 'getfullrir':
				
				try{
							$this->_checkAuth(4,'R');
							$rtds=$model->rirtestdetails;
										 
						
							
							function getrirdata($d,$oparams, $bparams)
							{
									$sub=Substandards::model()->findByPk($d->SubStdId);	
									
							//$md=$sub->stdsubdetails[0];
						$type="";//$sub->Type;
						$substandard="";
						
							$substandard=$sub->Grade." ".$sub->ExtraInfo;
						
						$std=$sub->std->Standard." ".$substandard;
						
						$testedby="";
							$testsign="";
							if(!empty($d->TestedBy))
							{
							$testsign=empty($d->testedBy->usersignuploads)?"":$d->testedBy->usersignuploads[0]->url;
							$testedby=$d->testedBy->FName." ".$d->testedBy->LName;	
							}	
							
							$approvedby="";
							$approvedsign="";
							if(!empty($d->ApprovedBy))
							{
							$approvedsign=empty($d->approvedBy->usersignuploads)?"":$d->approvedBy->usersignuploads[0]->url;
							$approvedby=$d->approvedBy->FName." ".$d->approvedBy->LName;	
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
							
							$rirob=(object)array('Id'=>$d->Id,'SampleName'=>$d->rIR->SampleName,'RIRId'=>$d->RIRId,
								'LabNo'=>$d->rIR->LabNo,'ExtraInfo'=>$d->ExtraInfo,'RefPurchaseOrder'=>$d->rIR->RefPurchaseOrder,
								'TestName'=>$d->TestName,'IsLab'=>$islab,'laburls'=>$laburls,'Labs'=>$labnames,
								'Customer'=>$d->rIR->customer->Name,'CustomerId'=>$d->rIR->CustomerId,'CustEmail'=>$d->rIR->customer->Email,
								'NoOfSamples'=>$d->rIR->NoOfSamples,'Status'=>$d->Status,'Remark'=>$d->Remark,
								'TestDate'=>$d->TestDate,
								'Standard'=>$std,'obbasic'=>$bparams,'observations'=>$oparams, 'Note'=>$d->Note,'Industry'=>$d->rIR->ind->Name,
								'StandardId'=>$sub->StdId,'SubStdId'=>$sub->Id,'BatchCode'=>$d->rIR->BatchCode,'ReceiptOn'=>$d->CreationDate,'TestNo'=>$d->TestNo,
								'SampleCondition'=>$d->rIR->SampleCondition,'ReqDate'=>$d->ReqDate,
								'TestSign'=>$testsign,'TestedBy'=>$testedby,'ApprovedSign'=>$approvedsign,'ApprovedBy'=>$approvedby,'ULRNo'=>$d->ULRNo,'ApprovedDate'=>$d->ApprovedDate,
								'FormatNo'=>$d->FormatNo,'RevNo'=>$d->RevNo,'RevDate'=>$d->RevDate);
								
								return $rirob;
							
							}
							
						
									foreach($rtds as $d)
											{
												
												//---Basic Params-----//
												$basicparams=[];
												if(empty($d->rirtestbasics))
												{
														$basicparams=[];
												}										
												else
												{
													foreach($d->rirtestbasics as $bp)
													{
														
														
													$basicparams[]=(object)array('RTID'=>$bp->RTID,'Id'=>$bp->Id,'TBPID'=>$bp->TBPID,
													'BValue'=>$bp->BValue,
													'Parameter'=>$bp->tBP->Parameter,'PUnit'=>$bp->tBP->PUnit,
													'PDType'=>$bp->tBP->PDType);
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
																$cr=Stdsubdetails::model()->find(array(
																'condition'=>'SubStdId=:ID AND PId=:eid',
																 'params'=>array(':ID'=>$d->SubStdId,':eid'=>$e->TPID),));
										
																	if(!empty($cr))
																	{										
																		$act="";
																	if($e->tP->PDType==='N')
																	{											
																		$act=$cr->Min."-".$cr->Max;
																	}
																	$observations[]=(object)array('Id'=>$e->Id,'TPID'=>$e->TPID,'PUnit'=>$e->tP->PUnit,
																	'PSymbol'=>$e->tP->PSymbol,'TMID'=>$e->TMID,'Param'=>$cr->p->Parameter,'Min'=>$cr->Min,'Max'=>$cr->Max,'Values'=>$e->rirtestobsvalues,'PCatId'=>$e->tP->PCatId,
																	'CatName'=>empty($e->tP->pCat)?"":$e->tP->pCat->CatName,
																	'RTID'=>$e->RTID,'TM'=>empty($e->TMID)?null:$e->tM->Method,
																	'PDType'=>$e->tP->PDType,'Permissible'=>$cr->Permissible,'Acceptable'=>$act,
																		);
																	}
																	
														}					
																					
													}		
												
									 
												$allrirs[]=getrirdata($d,$observations,$basicparams);	
											}
								

							 
							
							$result=(object)array('rirs'=>$allrirs);
							$this->_sendResponse(200, CJSON::encode($result));	
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}				
							break;
				case 'updatetestplan':
				try{
					
						$this->_checkAuth(12,'U');
						
					$model->save(false);
					   $cost=0;
					   
					foreach($data['DelParameters'] as $p)
					 {
						 
						 
						
						 $sspd= Stdsubplandetails::model()->find(array('condition'=>'PlanId=:plan AND SSDID=:ssid',
											'params'=>array('plan'=>$model->getPrimaryKey(),':ssid'=>$p)));
						if(!empty($sspd))
						{							
						 
						  $sspd->delete(false);
						}
						 
						 
					 }   
					   
					 //---
					 foreach($data['Parameters'] as $p)
					 {
						 
						 $stdsubdetail=Stdsubdetails::model()->findByPK($p);
						
						$cost=$cost+$stdsubdetail->Cost;
						
						 $sspd= Stdsubplandetails::model()->find(array('condition'=>'PlanId=:plan AND SSDID=:ssid',
											'params'=>array('plan'=>$model->getPrimaryKey(),':ssid'=>$p)));
						if(empty($sspd))
						{							
						 $sspd=new Stdsubplandetails;
						  $sspd->PlanId=$model->getPrimaryKey();
						}
						 $sspd->SSDID=$p;
						 
						  $sspd->save(false);
					 }
					 
					 
					 $model->Cost=$cost;
					  $model->save(false);
					  // foreach($data['DelParameters'] as $p)
					 // {
						 // $dsspd= Stdsubplandetails::model()->find(array('condition'=>'PlanId=:plan AND SSDID=:ssid',
											// 'params'=>array('plan'=>$model->getPrimaryKey(),':ssid'=>$p)));
						// if(!empty($dsspd))
						// {							
						 // $dsspd->delete();
						// }
						
					 // }
					 
					 
					  $plans=Stdsubplans::model()->findAll();
					  $substdplans=[];
					  foreach($plans as $pl)
					  {
					 $parameters=[];
									foreach($pl->stdsubplandetails as $pd)
									{
										$parameters[]=(object)['Id'=>$pd->SSDID,'Parameter'=>$pd->sSD->p->Parameter,'PSymbol'=>$pd->sSD->p->PSymbol,
						'PCatId'=>$pd->sSD->p->PCatId,'CatName'=>empty($pd->sSD->p->pCat)?"":$pd->sSD->p->pCat->CatName,];
										
									}
									
									$pld=(object)array('Id'=>$pl->Id,'Name'=>$pl->Name,'Cost'=>$pl->Cost,'SubStdId'=>$pl->SubStdId,'VParameters'=>$parameters);
								$substdplans[]=$pld;
					  }		
					
					 $result=(object)array('substdplans'=>$substdplans);
				   $this->_sendResponse(200, CJSON::encode($result));
					
				}
				catch(Exception $e)
						{
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
						}
				break;
				
				
				case 'substdparams':
					try{
									$sub=$model;	
								$sds=[];									
					foreach($sub->stdsubdetails as $e)
					{
						
							$act="";
							if($e->p->PDType==='N')
										{											
									$act=$e->RangeMin."-".$e->RangeMax;
										}
										
										$nabl="NABL";
										$nonnabl="NON-NABL";
						$sds[]=(object)array('Id'=>$e->Id,'TPID'=>$e->PId,'Acceptable'=>$act,'Parameter'=>$e->p->Parameter,'PSymbol'=>$e->p->PSymbol,'ISNABL'=>$e->p->ISNABL?$nabl:$nonnabl,'Cost'=>$e->Cost,
						'TestMethod'=>$e->tM->Method,
						'PCatId'=>$e->p->PCatId,'CatName'=>empty($e->p->pCat)?"":$e->p->pCat->CatName,
						'PUnit'=>$e->p->PUnit,'PDType'=>$e->p->PDType,'RangeMin'=>(float)$e->RangeMin,'RangeMax'=>(float)$e->RangeMax,
							'PermMin'=>(float)$e->PermMin,'PermMax'=>(float)$e->PermMax,
							'SpecMin'=>$e->SpecMin,'SpecMax'=>$e->SpecMax,
							'LOD'=>$e->LOD,'LOQ'=>$e->LOQ,'ML'=>$e->ML,
							'Instrument'=>$e->Instrument,'Validation'=>$e->Validation,);
						
						
					}
					
				
							$result=(object)array('Parameters'=>$sds);
							$this->_sendResponse(200, CJSON::encode($result));
						}
						catch(Exception $e)
						{
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
						}
				break;
				case 'testeditdata':
				try{
					
					
						
				
				$t=$model;
				
				$timg=Testicons::model()->find(array('condition'=>'testid=:tid','params'=>array(':tid'=>$t->Id)));
				$img="";
				if(!empty($timg))
				{
					$img=$timg;
				}
				
				
				$testparams=[];
				foreach($t->testobsparams as $y)
				{
					$pcat="";
					if($y->PCatId != '')
					{
						$pcat=Testparamcategory::model()->findByPk($y->PCatId);
					}
					
					
					$testparams[]=(object)array('Id'=>$y->Id,'Parameter'=>$y->Parameter,'TestId'=>$y->TestId,'Cost'=>$y->Cost,
					'SeqNo'=>$y->SeqNo,'ISNABL'=>$y->ISNABL,'FormVal'=>$y->FormVal,
					'PUnit'=>$y->PUnit,'PDType'=>$y->PDType,'PSymbol'=>$y->PSymbol,'PCatId'=>$y->PCatId,'PCat'=>$pcat);
					
				}
				
				$tof=$t->testfeatures[0];
				
				$formulas=[];
				
				foreach($t->formulations as $f)
				{
					$fdetails=[];
					
					foreach($f->formuladts as $h)
					{
						$fdetails[]=(object)['Id'=>$h->Id,'FId'=>$h->FId,'Variable'=>$h->Variable,
						'PId'=>$h->PId,'Parameter'=>$h->p->Parameter];
					}
					$formulas[]=(object)['Id'=>$f->Id,'Formula'=>$f->Formula,'TestId'=>$f->TestId,'PId'=>$f->PId,
					'details'=>$fdetails,'Parameter'=>$f->p->Parameter
					];
				}
				
				
				
				
					$test=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'Qty'=>$t->Qty,'DefaultNote'=>$t->DefaultNote,
					'Status'=>$t->Status,'FormatNo'=>$t->FormatNo,'RevDate'=>$t->RevDate,'RevNo'=>$t->RevNo,
					'Img'=>$img,'MachinePath'=>$t->MachinePath,'TType'=>$t->TType,'IsImg'=>$t->IsImg,
					'IsStd'=>$tof->IsStd,'IsPlan'=>$tof->IsPlan,'IsTestMethod'=>$tof->IsTestMethod,'IsParamTM'=>$tof->IsParamTM,'RemarkType'=>$tof->RemarkType,'formulas'=>$formulas,
					'Cost'=>$t->Cost,'IndId'=>$t->IndId,'testbasicparams'=>$t->testbasicparams,'testparamcats'=>$t->testparamcategories,'testparams'=>$testparams,'Industry'=>implode(" - ",array_reverse(MyFunctions::getParentCat($t->IndId))));
				
				
				
				
				$industries=Industry::model()->findAll(array('condition'=>'HasSubInd=0 AND Status=1'));
				$allindustries=[];
				foreach($industries as $c)
							{
								$parents = MyFunctions::getParentCat($c->Id);
									$allindustries[]=(object)array('Id'=>$c->Id,'Name'=>$c->Name,
									'Parent'=>isset($c->ParentId)?$c->parent->Name:"",'FParent'=>end($parents),
									'ParentId'=>$c->ParentId,'PTree'=>implode(" - ",array_reverse(MyFunctions::getParentCat($c->Id))));
									
							}
							
							$alldtypes[]=(object)array('Id'=>'N','Text'=>"Numeric");
							$alldtypes[]=(object)array('Id'=>'T','Text'=>"Text");
							$alldtypes[]=(object)array('Id'=>'TA','Text'=>"Text-area");
							$alldtypes[]=(object)array('Id'=>'L','Text'=>"Drop-down List");
							$alldtypes[]=(object)array('Id'=>'D','Text'=>"Date");
							$alldtypes[]=(object)array('Id'=>'DT','Text'=>"DateTime");
							
							$allpositions[]=(object)array('Id'=>'Top','Text'=>"Top");
							$allpositions[]=(object)array('Id'=>'Down','Text'=>"Down");
							
							$listcategories=Listcategory::model()->findAll();	

$allremtypes[]=(object)array('Id'=>'D','Text'=>"Drop-down");
$allremtypes[]=(object)array('Id'=>'DA','Text'=>"Drop-down Auto");							
$allremtypes[]=(object)array('Id'=>'TA','Text'=>"TextArea");
							
							
							
							$result=(object)array('allindustries'=>$allindustries,'test'=>$test,'allremtypes'=>$allremtypes,
							'alldtypes'=>$alldtypes,'allpositions'=>$allpositions,'listcategories'=>$listcategories);
							$this->_sendResponse(200, CJSON::encode($result));
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
				case 'testpredata':
					$industries=Industry::model()->findAll(array('condition'=>'HasSubInd=0 AND Status=1'));
				$allindustries=[];
				foreach($industries as $c)
							{
								$parents =  MyFunctions::getParentCat($c->Id);
									$allindustries[]=(object)array('Id'=>$c->Id,'Name'=>$c->Name,
									'Parent'=>isset($c->ParentId)?$c->parent->Name:"",'FParent'=>end($parents),
									'ParentId'=>$c->ParentId,'PTree'=>implode(" - ",array_reverse( MyFunctions::getParentCat($c->Id))));
									
							}
							
							
							$alldtypes[]=(object)array('Id'=>'N','Text'=>"Numeric");
							$alldtypes[]=(object)array('Id'=>'T','Text'=>"Text");
							$alldtypes[]=(object)array('Id'=>'L','Text'=>"Drop-down List");
							$alldtypes[]=(object)array('Id'=>'D','Text'=>"Date");							
							$alldtypes[]=(object)array('Id'=>'DT','Text'=>"DateTime");
							
							$allpositions[]=(object)array('Id'=>'Top','Text'=>"Top");
							$allpositions[]=(object)array('Id'=>'Down','Text'=>"Down");
							
					$listcategories=Listcategory::model()->findAll();
					
$allremtypes[]=(object)array('Id'=>'D','Text'=>"Drop-down");
$allremtypes[]=(object)array('Id'=>'DA','Text'=>"Drop-down Auto");							
$allremtypes[]=(object)array('Id'=>'TA','Text'=>"TextArea");
							
							
							$result=(object)array('allindustries'=>$allindustries,'allpositions'=>$allpositions,'alldtypes'=>$alldtypes,'listcategories'=>$listcategories,'allremtypes'=>$allremtypes);
							$this->_sendResponse(200, CJSON::encode($result));
				break;
				
				
				case 'getquoteeditdata':
				try{
						$i=$model;
						
							$creator="";
							if(!empty($i->CreatedBy))
							{
								$user=Users::model()->findByPk($i->CreatedBy);
								$creator=empty($user)?null:$user->FName;
							}
							
							
							$assignuser="";
							if($i->AssignedTo)
							{
								$user=Users::model()->findByPk($i->AssignedTo);
								$assignuser=empty($user)?null:$user->FName;
							}
							
							$qdetails=[];
							
							foreach($i->quotationdetails as $d)
							{
								$testcond=Testconditions::model()->findByPk($d->TestCondId);
								$testcondition=$testcond->Name;
								
								$test=Tests::model()->findByPk($d->TestId);
								$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
								
								$substd=Substandards::model()->findByPk($d->SubStdId);
								$substdname=$substd->std->Standard." - ".$substd->Grade;
								
								$sds=[];
								$stdsubdets=$substd->stdsubdetails(array('condition'=>'IsMajor=1'));
								foreach($stdsubdets as $f)
								{
										$sds[]=(object)['Parameter'=>$f->p->Parameter];
								}
								$labnames=[];
								if(!empty(json_decode($d->LabIds)))
								{
									foreach(json_decode($d->LabIds) as $l)
									{
										$lab=Labaccredit::model()->findByPk($l);
										$labnames[]=$lab->Name;
									}
								}
								
								
								$plan=[];
								if(!empty($d->PlanId))
								{
									$pl=Stdsubplans::model()->findByPk($d->PlanId);
									$parameters=[];
									foreach($pl->stdsubplandetails as $pd)
									{
										$parameters[]=(object)['Parameter'=>$pd->sSD->p->Parameter,'PSymbol'=>$pd->sSD->p->PSymbol,'TestMethod'=>$pd->sSD->tM->Method,'Cost'=>$pd->sSD->Cost,
						'PCatId'=>$pd->sSD->p->PCatId,'CatName'=>empty($pd->sSD->p->pCat)?"":$pd->sSD->p->pCat->CatName,];
										
									}
									
									$plan=(object)array('Id'=>$pl->Id,'Name'=>$pl->Name,'SubStdId'=>$pl->SubStdId,'Parameters'=>$parameters);
								}
								
								$addtest=[];
								$addtest[]=$d->TestId;
								
								$tseq=$d->TSeq;
								$testname="";
								if(!is_null($tseq))
								{
									$pieces = explode("-", $tseq);
									$testname=$test->TestName.'-'.$pieces[1];
								}
								else
								{
									$testname=$test->TestName;
								}
								
								$qdetails[]=(object)array('Id'=>$d->Id,'QId'=>$d->QId,'IndId'=>$d->IndId,
								'PIndId'=>$d->PIndId,'SubIndId'=>$d->SubIndId,'SampleName'=>$d->SampleName,
								'SampleWeight'=>$d->SampleWeight,'TAT'=>$d->TAT,'Description'=>$d->Description,
								'PlanId'=>$d->PlanId,
								'Industry'=>implode(" - ",array_reverse( MyFunctions::getParentCat($d->IndId))),'TestCondId'=>$d->TestCondId,'TestId'=>$d->TestId,'sds'=>$sds,'labnames'=>$labnames,'addtest'=>$addtest,'LabIds'=>empty(json_decode($d->LabIds))?[]:json_decode($d->LabIds),'Plan'=>empty($plan)?null:$plan->Name,
								'PlanParameters'=>empty($plan)?$sds:$plan->Parameters,'TSeq'=>$d->TSeq,
								'TestCondition'=>$testcondition,'TestName'=>$testname,'SubStdId'=>$d->SubStdId,
								'StdName'=>$substdname,'TestMethod'=>empty($testmethod)?null:$testmethod->Method,
								'TestMethodId'=>$d->TestMethodId,'Price'=>$d->Price,'Qty'=>(int)$d->Qty,'Tax'=>$d->Tax,'Total'=>$d->Total,
								);
							}
							
							$quote=(object)['Id'=>$i->Id,'QNo'=>$i->QNo,'Customer'=>$i->cust,'CustId'=>$i->CustId,
							'QDate'=>$i->QDate,'ValidDate'=>$i->ValidDate,'TotTax'=>$i->TotTax,'Tax'=>$i->Tax,
							'SubTotal'=>$i->SubTotal,'Discount'=>$i->Discount,'Total'=>$i->Total,'Note'=>$i->Note,'Status'=>$i->Status,'SampleGroup'=>$i->SampleGroup,'SampleConditions'=>$i->SampleConditions,'Specifications'=>$i->Specifications,'EndUse'=>$i->EndUse,'DrawnBy'=>$i->DrawnBy,'ModeOfReceipt'=>$i->ModeOfReceipt,
							'CreatedBy'=>$i->CreatedBy,'CreatorName'=>$creator,'AssignUser'=>$assignuser,'AssignedTo'=>$i->AssignedTo,
							'ApprovedBy'=>'','VerifiedBy'=>'',
							'Details'=>$qdetails];
							
							
							$users=Users::model()->findAll();
											///////////Parent
				
				
				$industries=Industry::model()->findAll(array('condition'=>'ParentId is null AND Status="1"'));
				$allindustries=[];
				foreach($industries as $c)
							{
								$parents = array();
									$allindustries[]=(object)array('Id'=>$c->Id,'Name'=>$c->Name,
									'PTree'=>implode(" - ",array_reverse(MyFunctions::getParentCat($c->Id))),
									'Children'=>MyFunctions::parseTree($c->Id));
									
							}
					
					$customers=Customerinfo::model()->findAll();
					$allusers=[];
					foreach($users as $u)
					{
						$allusers[]=(object)array('Id'=>$u->Id,'Name'=>$u->FName." ".$u->MName." ".$u->LName);
					}
					
					$testconditions=Testconditions::model()->findAll();
					
					$alllabs=Labaccredit::model()->findAll();
					$set=Settings::model()->find();
				//	$result=(object)array();
				
					$drawntypes=Miscellaneous::model()->findAll(array('condition'=>'Type="drawnby"'));
					$receipttypes=Miscellaneous::model()->findAll(array('condition'=>'Type="modeofreceipt"'));
					
					$result=(object)array('quote'=>$quote,'industries'=>$allindustries,'users'=>$allusers,'customers'=>$customers,'testconditions'=>$testconditions,'alllabs'=>$alllabs,'IsTax'=>$set->IsTax,'Tax'=>$set->Tax,'TaxLabel'=>$set->TaxLabel,'drawntypes'=>$drawntypes,'receipttypes'=>$receipttypes,
						'QVDays'=>$set->QVDays);
						
						$this->_sendResponse(200, CJSON::encode($result));	
						
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
				}
				break;
				case 'updatelabaccredit':
				try{
					$model->save(false);
					$this->_sendResponse(200, CJSON::encode($model->getPrimaryKey()));	
				}
				catch(Exception $e)
					{
						$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
					}
				break;
				
				
				case 'labaccredits':
					$this->_checkAuth(1,'R');
				$labs=Labaccredit::model()->findAll();
				
				$alllabs=array();
		foreach($labs as $a)
		{
			$img=(object)array();
			//$this->_sendResponse(401, CJSON::encode($a->procatimgs));
			if(!empty($a->labaccreditlogoses))
			{
				$img=$a->labaccreditlogoses[0];
			}
			
			$alllabs[]=(object)array('Id'=>$a->Id,'Name'=>$a->Name,'Img'=>$img,'Cost'=>$a->Cost,
                                    'Status'=>$a->Status);
		}
		$result=(object)array('alllabs'=>$alllabs);
						$this->_sendResponse(200, CJSON::encode($result));
				break;
				
				
				case 'getdashinfo':
				try{
					
					
					//$this->_checkAuth(3,'R');					
							
								$u=$model;
								
								
								 $getfilt=$data['Filter'];
								 $location='';
								 
								
									
											if(empty($data['Locations']))
											 {
												 
												  if($u->userinroles[0]->role->Role==='Admin')
													{
														$location=0;
													}
													else
													{
														foreach($u->userinbranches as $b)
														 {
															  $location=$b->BranchId;
														 }
													}
												 
											 }
										else
										{
											
															  $location=$data['Locations'];
													
											
										}
								
								
					$from="";
					$to="";
					
					switch($getfilt)
					{
						case 'TD':
						
								$from=date('2023-01-01');
								$to=date('Y-m-d');
						break;
						
						case 'L7D':
						
								$from=date('Y-m-d',strtotime('-7 days'));
								$to=date('Y-m-d');
						break;
						
						case 'L30D':
						
								$from=date('Y-m-d',strtotime('-30 days'));
								$to=date('Y-m-d');
						break;
						
						case 'CD':
						
								$from=date('Y-m-d',strtotime($data['From']));
								$to=date('Y-m-d',strtotime($data['To']));
						break;
					}
					
						//$this->_sendResponse(401, CJSON::encode($location));	
					$allcurrency=['₹','$'];
					switch($location)
					{
						
							case 0:
								$rirtot=Receiptir::model()->count(array(
										'condition'=>'DATE(CreationDate) BETWEEN :from AND :to',
											'params'=>array(':from'=>$from,':to'=>$to)));
		
										
										$rirpend=Receiptir::model()->with(array('rirtestdetails'=>array('condition'=>'rirtestdetails.Status="pending"')))->count(array(
										'condition'=>'DATE(t.CreationDate) BETWEEN :from AND :to',
											'params'=>array(':from'=>$from,':to'=>$to),
										'together'=>true));
										
										$certcount=Certbasic::model()->count(array(
									'condition'=>'DATE(CreationDate) BETWEEN :from AND :to',
										'params'=>array(':from'=>$from,':to'=>$to)));
										
										
										$cust=Customerinfo::model()->count(array(
									'condition'=>'DATE(CreatedOn) BETWEEN :from AND :to',
										'params'=>array(':from'=>$from,':to'=>$to)));
									
									$quotes=Quotation::model()->findAll(array('condition'=>'Status="Approved" AND DATE(CreatedOn) BETWEEN :from AND :to','params'=>array(':from'=>$from,':to'=>$to)));
									
									//---last 7 days --revenue---//
									$cdate=date('Y-m-d');
									$dates=[];
									$datesale=[];
									$datediff =   strtotime($to) -strtotime($from);
									$hd=(int)round($datediff / (60 * 60 * 24));
									for($h=1;$h<$hd;$h++)
									{
											$dates[] = date('Y-m-d', strtotime($cdate .' -'.$h.' day'));
									}
									
									$dates=array_reverse($dates);
									$datesales=[];
									foreach($dates as $v)
									{
										$qdates=[];
										$qdates=Quotation::model()->findAll(array('condition'=>'Status="Approved" AND DATE(CreatedOn )= :dt',
											'params'=>[':dt'=>$v]));
											$gsale=0;
											foreach($qdates as $g)
											{
												$gsale=$gsale+$g->Total;
											}
									$datesales[]=(object)array('SDate'=>date('d M', strtotime($v)),'STotal'=>$gsale);
									}
									
									
									$rquotes=Quotation::model()->findAll(array('condition'=>'Status="Approved"  AND DATE(CreatedOn) BETWEEN :from AND :to','params'=>array(':from'=>$from,':to'=>$to)));
									
									$currency='₹';
							break;
						default:
						
						//--get users of that locs
						
						$userinbs=Userinbranches::model()->findAll(array('condition'=>'BranchId=:bid',
									'params'=>array(':bid'=>$location)));
									$getusers=[];
							foreach($userinbs as $b)
							{
								$getusers[]=$b->UserId;
							}

							$criteria=new CDbCriteria;
                  		
						$criteria->condition='DATE(t.CreationDate) BETWEEN :from AND :to AND  BranchId=:branch';
                		$criteria->params=array(':from'=>$from,':to'=>$to,':branch'=>$location);
						//$criteria->addInCondition('EnteredBy',$getusers);
						$criteria->together = true;					
						$rirtot=Receiptir::model()->count($criteria);
							
						$criteria->with=array('rirtestdetails'=>array('condition'=>'rirtestdetails.Status="pending"'));	
						
						$rirpend=Receiptir::model()->count($criteria);
									
									
									
							$certcriteria=new CDbCriteria;
                  		
						$certcriteria->condition='DATE(t.
						CreationDate) BETWEEN :from AND :to';
                		$certcriteria->params=array(':from'=>$from,':to'=>$to);
						//$certcriteria->addInCondition('CreatedBy',$getusers);
						$certcriteria->together = true;					
						$certcount=Certbasic::model()->count($certcriteria);

						
						$custcriteria=new CDbCriteria;
                  		
						$custcriteria->condition='DATE(t.
						CreatedOn) BETWEEN :from AND :to';
                		$custcriteria->params=array(':from'=>$from,':to'=>$to);
						$custcriteria->addInCondition('CreatedBy',$getusers);
						$custcriteria->together = true;					
						$cust=Customerinfo::model()->count($custcriteria);
						
						
							// $cust=Customerinfo::model()->count(array(
									// 'condition'=>'DATE(CreatedOn) BETWEEN :from AND :to AND BranchId=:bid',
										// 'params'=>array(':from'=>$from,':to'=>$to,':bid'=>$location)));
									

									$quotecriteria=new CDbCriteria;
                  		
						$quotecriteria->condition='Status="Approved" AND DATE(t.
						CreatedOn) BETWEEN :from AND :to';
                		$quotecriteria->params=array(':from'=>$from,':to'=>$to);
						$quotecriteria->addInCondition('CreatedBy',$getusers);
						$quotecriteria->together = true;					
						$quotes=Quotation::model()->findAll($quotecriteria);

									//---last 7 days --revenue---//
									$cdate=date('Y-m-d');
									$dates=[];
									$datesale=[];
									$datediff =   strtotime($to) -strtotime($from);
									$hd=(int)round($datediff / (60 * 60 * 24));
									for($h=1;$h<$hd;$h++)
									{
											$dates[] = date('Y-m-d', strtotime($cdate .' -'.$h.' day'));
									}
									
									$dates=array_reverse($dates);
									$datesales=[];
									foreach($dates as $v)
									{
										$qdates=[];
										
										
										
										$qdatescritria=new CDbCriteria;
                  		
						$qdatescritria->condition='Status="Approved" AND DATE(t.
						CreatedOn)= :dt';
                		$qdatescritria->params=array(':dt'=>$v);
						$qdatescritria->addInCondition('CreatedBy',$getusers);
									
						$qdates=Quotation::model()->findAll($qdatescritria);
						
											$gsale=0;
											foreach($qdates as $g)
											{
												$gsale=$gsale+$g->Total;
											}
									$datesales[]=(object)array('SDate'=>date('d M', strtotime($v)),'STotal'=>$gsale);
									}
									
									$rquotecritria=new CDbCriteria;
                  		
						$rquotecritria->condition='Status="Approved" AND DATE(CreatedOn) BETWEEN :from AND :to';
                		$rquotecritria->params=array(':from'=>$from,':to'=>$to);
						$rquotecritria->addInCondition('CreatedBy',$getusers);
									
						$rquotes=Quotation::model()->findAll($rquotecritria);

						
									if($location===1)
									{
										$currency='₹';
									}
									else
									{
									$currency='$';
									}
								
						break;
					}
					
					
						
					
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
									
									
									$rir=[$rirtot,$rirtot-$rirpend,$rirpend];
									$labels=["Total","Completed","Pending"];
									$labels2=["Completed","Pending"];
									
									
									
									$certapcount=0;	


										
									
									$tests=Tests::model()->findAll(array('condition'=>' Status=1  ','order'=>'SeqNo ASC'));
									$alltest=[];
									$alltest2=[];
									foreach($tests as $t)
									{
										$aps=Appsections::model()->find(array('condition'=>'Others =:oth','params'=>[':oth'=>$t->Id]));	
										if (array_key_exists($aps->Id,$appsections))
									{
										$tot=0;
										$totpend=0;
										$tot=Rirtestdetail::model()->count(array('condition'=>'TestId=:test AND DATE(CreationDate) BETWEEN :from AND :to','params'=>array(':test'=>$t->Id,':from'=>$from,':to'=>$to)));
										$totpend=Rirtestdetail::model()->count(array('condition'=>'Status="Pending" AND TestId=:test
										AND DATE(CreationDate) BETWEEN :from AND :to',
																	'params'=>array(':test'=>$t->Id,':from'=>$from,':to'=>$to)));
										$testdata=[$tot-$totpend,$totpend];
											
											
											
											$parents = MyFunctions::getParentCat($t->IndId);
											
											$timg=Testicons::model()->find(array('condition'=>'testid=:tid','params'=>array(':tid'=>$t->Id)));
				$img="";
				if(!empty($timg))
				{
					$img=$timg;
				}
				
										if(!empty($aps) )
										{
											if($appsections[$aps->Id]->Apply)
											{
											$alltest2[end($parents)][]=(object)['icon'=>$t->icon,'img'=>$img,'name'=>$t->TestName,'tot'=>$tot,'FParent'=>end($parents),
										'tid'=>$t->Id,'sid'=>empty($aps)?"":$aps->Id,'labels'=>$labels2,'data'=>$testdata];		
											}	
										}		

										$alltest[end($parents)][]=(object)['icon'=>$t->icon,'img'=>$img,'name'=>$t->TestName,'tot'=>$tot,'FParent'=>end($parents),
										'tid'=>$t->Id,'sid'=>empty($aps)?"":$aps->Id,'labels'=>$labels2,'data'=>$testdata];	
									}										
									}

								
								
								
										
									$expected=0;
									foreach($quotes as $q)
									{
										$expected=$expected+$q->Total;
									}
									
									$invs=Invoices::model()->findAll(array('condition'=>'Status="1"'));
									
									$incoming=0;
									foreach($invs as $q)
									{
										$incoming=$incoming+$q->Total;
									}
										
										$invpays=Invpayments::model()->findAll();
										$received=0;
									foreach($invpays as $q)
									{
										$received=$received+$q->Payment;
									}
									
									$revenuedata=(object)array('Expected'=>$expected,'Incoming'=>$incoming,'Received'=>$received);
									//----Revenue Industry Wise---//
									
									
									$allindtest=[];
									foreach($rquotes as $r)
									{
										foreach($r->quotationdetails as $qd)
										{
												$parents =  MyFunctions::getParentCat($qd->IndId);
												$allindtest[end($parents)][]=(object)['name'=>$qd->test->TestName,'tot'=>$qd->Total];		
								
										}
									}
									
									
									
									
								
					$allfilts[]=(object)array('Id'=>'L7D','Name'=>'Last 7 days');
					$allfilts[]=(object)array('Id'=>'L30D','Name'=>'Last 30 days');
						$allfilts[]=(object)array('Id'=>'TD','Name'=>'Till Today');
					$allfilts[]=(object)array('Id'=>'CD','Name'=>'Custom Dates');
					
					
					
									//---//
									$branches=Branches::model()->findAll();
									$allbranches=[];
									$allbranches[]=(object)array('Id'=>0,'Name'=>"All");
									foreach($branches as $b)
									{
										$allbranches[]=(object)array('Id'=>$b->Id,'Name'=>$b->Name);
									}
									$result=(object)array('qdates'=>$datesales,'rir'=>$rir,'custcount'=>$cust,'certcount'=>$certcount,'certapcount'=>$certapcount,'allindtests'=>$allindtest,'alltest'=>$alltest,'revenuedata'=>$revenuedata,'allfilts'=>$allfilts,'FilterAp'=>$getfilt,'loc'=>$location,'$hd'=>$hd,'branches'=>$allbranches,'currency'=>$currency,'alltest2'=>$alltest2,);
									$this->_sendResponse(200, CJSON::encode($result));	
									
				}
				catch(Exception $e)
					{
						$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
					}
				break;
				
				case 'getponos':
					$transaction=$model->dbConnection->beginTransaction();					
					try{
						
						$pos=Quotation::model()->findAll(array('condition'=>'CustId=:cust AND Status="Approved"','params'=>array(':cust'=>$model->Id)));
						
						$allpos=[];
						
						
						foreach($pos as $p)
						{
							$allpos[]=(object)['QNo'=>$p->QNo];
						}
						$result=(object)array('allpos'=>$allpos);
						$this->_sendResponse(200, CJSON::encode($result));
					}
					catch(Exception $e)
					{
						$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
					}
				break;
				
				case 'approveinvoice':
				$transaction=$model->dbConnection->beginTransaction();
					
					try{
						$model->Status="Approved";
						$model->ApprovedOn=date('Y-m-d H:i:s');
						$model->save(false);
						$transaction->commit();
						$this->_sendResponse(200, CJSON::encode("Approved"));	
					}
					catch(Exception $e)
									{
									$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
									}
									break;
									
									
				case 'approvequote':
				$transaction=$model->dbConnection->beginTransaction();
					
					try{
						$model->Status="Approved";
						$model->ApprovedOn=date('Y-m-d H:i:s');
						$model->save(false);
						$transaction->commit();
						$this->_sendResponse(200, CJSON::encode("Approved"));	
					}
					catch(Exception $e)
									{
									$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
									}
									break;
					case 'updaterole':
					$transaction=$model->dbConnection->beginTransaction();
					
					try{
						//$model->Username=$model->Email;
										$model->save(false);
										
										// $uir=$model->userinroles[0];
										// $uir->RoleId=$put_vars['Role'];
										// $uir->save();
										
											foreach($data['Appsections'] as $s)
						{
							if(empty($s['Id']))
							{
								$uapp=new Roleapppermission;
							}
							else
							{
								$uapp=Roleapppermission::model()->findByPk($s['Id']);
							}
							
							$uapp->RoleId=$model->getPrimaryKey();
							$uapp->SectionId=$s['SectionId'];
							$uapp->C=$s['C'];
							$uapp->R=$s['R'];
							$uapp->U=$s['U'];
							$uapp->D=$s['D'];
							$uapp->A=$s['A'];
							$uapp->Ch=$s['Ch'];
							$uapp->SM=$s['SM'];
							$uapp->Print=$s['Print'];
							
							$uapp->save(false);
							
						}
										
										
											$transaction->commit();
											$this->_sendResponse(200, CJSON::encode($model->Id));
						
					}
					catch(Exception $e)
									{
									$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
									}
									break;
				case 'roles':
						try
									{
										$roles=Roles::model()->findAll();
										$allroles=[];
										foreach($roles as $r)
										{
											$appsections=array();
					$sections=array();
					
					if(!empty($r->roleapppermissions))
					{
						
						foreach($r->roleapppermissions as $s)
						{
							$sections[]=$s->SectionId;
							$appsections[]=(object)array('Id'=>$s->Id,'SectionId'=>$s->SectionId,'Section'=>$s->section->Section,'Group'=>$s->section->Group,'C'=>$s->C,'R'=>$s->R,'Description'=>$s->section->Description,
							'U'=>$s->U,'D'=>$s->D,'A'=>$s->A,'Ch'=>$s->Ch,'Print'=>$s->Print,'SM'=>$s->SM,
							'IsC'=>$s->section->C,'IsR'=>$s->section->R,'IsU'=>$s->section->U,'IsSM'=>$s->section->SM,'IsD'=>$s->section->D,'IsA'=>$s->section->A,'IsCh'=>$s->section->Ch,'IsPrint'=>$s->section->Print,
							
							);
						}
						
						$allsections=Appsections::model()->findAll(array('condition'=>'Status="1"'));
						foreach($allsections as $s)
						{
							if(!in_array($s->Id,$sections))
							{
								$appsections[]=(object)array('SectionId'=>$s->Id,'Section'=>$s->Section,'Group'=>$s->Group,
								'Description'=>$s->Description,
								'C'=>0,'R'=>0,'U'=>0,'D'=>0,'A'=>0,'Ch'=>0,'Print'=>0,'SM'=>0,
								'IsC'=>$s->C,'IsR'=>$s->R,'IsU'=>$s->U,'IsSM'=>$s->SM,'IsD'=>$s->D,'IsA'=>$s->A,'IsCh'=>$s->Ch,'IsPrint'=>$s->Print,);
							}
							
						}
						
					}
					else
					{
						$allsections=Appsections::model()->findAll();
						foreach($allsections as $s)
						{
							$appsections[]=(object)array('SectionId'=>$s->Id,'Section'=>$s->Section,'Group'=>$s->Group,
							'C'=>0,'R'=>0,'U'=>0,'SM'=>0,'Description'=>$s->Description,
							'D'=>0,'A'=>0,'Ch'=>0,'Print'=>0,
							'IsC'=>$s->C,'IsR'=>$s->R,'IsU'=>$s->U,'IsSM'=>$s->SM,'IsD'=>$s->D,'IsA'=>$s->A,'IsCh'=>$s->Ch,'IsPrint'=>$s->Print,);
							
							
						}
						
					}
					
					$allroles[]=(object)array('Id'=>$r->Id,'Role'=>$r->Role,'Status'=>$r->Status,'P'=>$r->P,'Appsections'=>$appsections);
					
										}
										
										
										$appsections=array();
					$sections=array();
							$allsections=Appsections::model()->findAll(array('condition'=>'Status=1'));
					foreach($allsections as $s)
					{
						
						$appsections[]=(object)array('SectionId'=>$s->Id,'Section'=>$s->Section,'Group'=>$s->Group,
						'C'=>0,'R'=>0,'U'=>0,'SM'=>0,'Description'=>$s->Description,
						'IsC'=>$s->C,'IsR'=>$s->R,'IsU'=>$s->U,'IsSM'=>$s->SM,'IsD'=>$s->D,'IsA'=>$s->A,'IsCh'=>$s->Ch,'IsPrint'=>$s->Print,'D'=>0,'A'=>0,'Ch'=>0,'Print'=>0);
					}			
					
						$data=(object)array('allroles'=>$allroles,'Appsections'=>$appsections);
					$this->_sendResponse(200, CJSON::encode($data));
									}
									catch(Exception $e)
									{
									$this->_sendResponse(401, CJSON::encode($e->getMessage()));	
									}
									break;
									
				case 'getquotepredata':
									try
									{
										$users=Users::model()->findAll();
					
				
				
				
				$industries=Industry::model()->findAll(array('condition'=>'ParentId is null AND Status="1"'));
				$allindustries=[];
				foreach($industries as $c)
							{
								$parents = array();
									$allindustries[]=(object)array('Id'=>$c->Id,'Name'=>$c->Name,
									'PTree'=>implode(" - ",array_reverse(MyFunctions::getParentCat($c->Id))),
									'Children'=>MyFunctions::parseTree($c->Id));
									
							}
					
					$customers=Customerinfo::model()->findAll();
					$allusers=[];
					foreach($users as $u)
					{
						$allusers[]=(object)array('Id'=>$u->Id,'Name'=>$u->FName." ".$u->MName." ".$u->LName);
					}
					
					$testconditions=Testconditions::model()->findAll();
					
					$alllabs=Labaccredit::model()->findAll();
					$set=Settingsaccount::model()->find();
					$vault=Settingsbank::model()->find();
					
					
					$drawntypes=Miscellaneous::model()->findAll(array('condition'=>'Type="drawnby"'));
					$receipttypes=Miscellaneous::model()->findAll(array('condition'=>'Type="modeofreceipt"'));
					
					$result=(object)array('industries'=>$allindustries,'users'=>$allusers,'customers'=>$customers,
					'testconditions'=>$testconditions,'alllabs'=>$alllabs,
					'IsTax'=>(int)$set->IsTax,'Tax'=>$set->Tax,'TaxLabel'=>$set->TaxLabel,'account'=>$vault,
						'QVDays'=>$set->QVDays,'QuoteNote'=>$set->QuoteNote,
						);
					$this->_sendResponse(200, CJSON::encode($result));
				
									}
									catch(Exception $e)
									{
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
										$model->Cost=$data['Cost'];
										$model->IndId=$data['IndId'];
										$model->Status=$data['Status'];
										$model->DefaultNote=$data['DefaultNote'];
										$model->MachinePath=$data['MachinePath'];
										$model->IsImg=$data['IsImg'];
										$model->save(false);
										
										
										if(empty($model->testfeatures))
										{
											
											$tof=new Testfeature;
											$tof->TestId=$model->getPrimaryKey();
											$tof->ObsVertical=1;
											$tof->IsStd=$data['IsStd'];
											$tof->IsTestMethod=$data['IsTestMethod'];
											$tof->IsPlan=$data['IsPlan'];
											$tof->RemarkType=$data['RemarkType'];
											$tof->save(false);
										}
										else
										{
											$tof=$model->testfeatures[0];
											
											$tof->IsStd=$data['IsStd'];
											$tof->IsTestMethod=$data['IsTestMethod'];
											$tof->IsPlan=$data['IsPlan'];
											$tof->RemarkType=$data['RemarkType'];
											$tof->save(false);
										}
										
										 
										
						if(!empty($data['testbasicparams']))
						{							
										
						foreach($data['testbasicparams'] as $p)
						{
							if(isset($p['Id']))
							{
								$bparam=Testbasicparams::model()->findByPk($p['Id']);
							}
							else
							{
								$bparam=new Testbasicparams;
							}
							foreach($p as $pvar=>$value) 
							 {
								// Does the model have this attribute? If not raise an error
								if($bparam->hasAttribute($pvar))
									$bparam->$pvar = $value;
							 }
							 $bparam->TestId=$model->getPrimaryKey();
							 $bparam->save(false);
						}
						}
						
						//-->Save category Parameters 
						
						foreach($data['testparamcats'] as $p)
						{
							if(isset($p['Id']))
							{
								$catparam=Testparamcategory::model()->findByPk($p['Id']);
							}
							else
							{
								$catparam=new Testparamcategory;
								 $catparam->TestId=$model->getPrimaryKey();
							}
							
							$catparam->CatName=$p['CatName'];							
							 $catparam->save(false);
						}
						
						
						//-->Save Parameters
						
										
						foreach($data['testparams'] as $p)
						{
							if(isset($p['Id']))
							{
								$param=Testobsparams::model()->findByPk($p['Id']);
							}
							else
							{
								$param=new Testobsparams;
							}
							foreach($p as $pvar=>$value) 
							 {
								// Does the model have this attribute? If not raise an error
								if($param->hasAttribute($pvar))
									$param->$pvar = $value;
							 }
							 
							 
							 if(isset($p['PCat']) && !empty($p['PCat']))
							 {
								 $cp=Testparamcategory::model()->find(array('condition'=>'TestId=:tid AND CatName=:cat',
											'params'=>[':tid'=>$model->getPrimaryKey(),':cat'=>$p['PCat']['CatName']]));
								$param->PCatId=$cp->getPrimaryKey();	
							 }
							 $param->TestId=$model->getPrimaryKey();
							 $param->save(false);
						}
						
						foreach($data['deltestbasicparams'] as $dbp)
						{
							$delbparam=Testbasicparams::model()->findByPk($dbp['Id']);		
							 
							 $delbparam->delete();
						}
						
						foreach($data['deltestparamcats'] as $dp)
						{
							$delcatparam=Testparamcategory::model()->findByPk($dp['Id']);		
							 
							 $delcatparam->delete();
						}
						
						foreach($data['deltestparams'] as $dp)
						{
							$delparam=Testobsparams::model()->findByPk($dp['Id']);		
							 
							 $delparam->delete();
						}
						
						
						//---formula--save
						
						foreach($data['formulas'] as $f)
						{
							if(isset($f['Id']))
							{
								$formula=Formulation::model()->findByPK(array($f['Id']));
							}
							else
							{
								$formula=new Formulation;
								$formula->TestId=$model->Id;
							}
								$formula->Formula=$f['Formula'];
								$fp=Testobsparams::model()->find(['condition'=>'Parameter =:param AND TestId=:testid',
								'params'=>array(':param'=>$f['Parameter'],':testid'=>$model->Id)]);
								
								if(!empty($fp))
								{
									$formula->PId=$fp->Id;
								}
								$formula->save(false);
							
						
							//--details-----//
							
							foreach($f['details'] as $k)
							{
								if(isset($k['Id']))
								{
									$fdts=Formuladts::model()->findByPk($k['Id']);
								}
								else
								{
									$fdts=new Formuladts;
									$fdts->FId=$formula->getPrimaryKey();
								}
								
								
								$fdtp=Testobsparams::model()->find(['condition'=>'Parameter =:param AND TestId=:testid',
								'params'=>array(':param'=>$k['Parameter'],':testid'=>$model->Id)]);
								//$this->_sendResponse(401, CJSON::encode($fp->getPrimaryKey()));
								if(!empty($fdtp))
								{
									if(empty($fdtp->Id))
									{
										$this->_sendResponse(401, CJSON::encode($fdtp));
									}
									$fdts->Variable=$k['Variable'];
									$fdts->PId=$fdtp->getPrimaryKey();
									$fdts->save(false);
								}
								
								
							}
						

						}
						
						
										$aps=Appsections::model()->find(array('condition'=>'Others =:oth','params'=>[':oth'=>$model->Id]));
							if(empty($aps))
							{
								$aps=new Appsections;
								$aps->Section=$model->TestName;
								$aps->Others=$model->Id;
								$aps->Group="Test";
								$aps->Status=$model->Status;
								$aps->save(false);
							}
							else
							{
								$aps->Section=$model->TestName;
								$aps->Status=$model->Status;
								$aps->save(false);
							}
										$transaction->commit();
										$this->_sendResponse(200, CJSON::encode("success"));
									}
									catch(Exception $e)
									{
										$this->_sendResponse(401, CJSON::encode($e->getMessage()));
									}
				break;
				
					case 'testrates':
				
					$this->_checkAuth(1,'R');
				
				///////////Parent
				
				
				
				$tests=Tests::model()->findAll(array('order'=>'Id Desc'));
				$alltests=[];
				foreach($tests as $t)
				{
				
				$timg=Testicons::model()->find(array('condition'=>'testid=:tid','params'=>array(':tid'=>$t->Id)));
				$img="";
				if(!empty($timg))
				{
					$img=$timg;
				}
				
				
				$testobsparams=[];
				foreach($t->testobsparams as $y)
				{
					$pcat="";
					if($y->PCatId != '')
					{
						$pcat=Testparamcategory::model()->findByPk($y->PCatId);
					}
					
					
					$testobsparams[]=(object)array('Id'=>$y->Id,'Parameter'=>$y->Parameter,'TestId'=>$y->TestId,'SeqNo'=>$y->SeqNo,
					'Cost'=>$y->Cost,'ISNABL'=>$y->ISNABL,
					'PUnit'=>$y->PUnit,'PDType'=>$y->PDType,'PSymbol'=>$y->PSymbol,'PCatId'=>$y->PCatId,'PCat'=>$pcat);
					
				}
				
				
					$alltests[]=(object)array('Id'=>$t->Id,'TestName'=>$t->TestName,'Qty'=>$t->Qty,'DefaultNote'=>$t->DefaultNote,
					'Status'=>$t->Status,'FormatNo'=>$t->FormatNo,'RevDate'=>date('d-M-Y',strtotime($t->RevDate)),'RevNo'=>$t->RevNo,
					'Img'=>$img,'MachinePath'=>$t->MachinePath,
					'Cost'=>$t->Cost,'IndId'=>$t->IndId,'testbasicparams'=>$t->testbasicparams,'testparamcats'=>$t->testparamcategories,'testobsparams'=>$testobsparams,'Industry'=>implode(" - ",array_reverse(MyFunctions::getParentCat($t->IndId))));
				}
				
				
				
				$industries=Industry::model()->findAll(array('condition'=>'HasSubInd=0 AND Status=1'));
				$allindustries=[];
				foreach($industries as $c)
							{
								$parents = MyFunctions::getParentCat($c->Id);
									$allindustries[]=(object)array('Id'=>$c->Id,'Name'=>$c->Name,
									'Parent'=>isset($c->ParentId)?$c->parent->Name:"",'FParent'=>end($parents),
									'ParentId'=>$c->ParentId,'PTree'=>implode(" - ",array_reverse(MyFunctions::getParentCat($c->Id))));
									
							}
				
					
					
					
				$results=(object)array('alltests'=>$alltests,'allindustries'=>$allindustries);
				$this->_sendResponse(200, CJSON::encode($results));
				
				break;
				
				
					case 'industryupdate':
					try{
						$this->_checkAuth(1,'U');					
							
					$model->save(false);
					
					$this->_sendResponse(200, CJSON::encode("updated"));
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
				
				case 'industries':
				try{
					
					
					$this->_checkAuth(1,'R');
					
					
					
				$vcats=Industry::model()->findAll(array(
							'condition'=>'ParentId IS NULL AND Status!=3',
							'order' => 'Id desc',
							//'condition'=>'Status !="Deleted" ',
							));
		
			$cats=Industry::model()->findAll(array(
								'condition'=>'Status="1" AND HasSubInd=1',
							'order' => 'Id desc',
							//'condition'=>'Status !="Deleted" ',
							));
							
							
		
					
							
			
		
		$allvcats=array();
		foreach($vcats as $a)
		{
			$img=(object)array();
			//$this->_sendResponse(401, CJSON::encode($a->procatimgs));
			if(!empty($a->procatimgs))
			{
				$img=$a->procatimgs[0];
			}
			
			$allvcats[]=(object)array('Id'=>$a->Id,'Name'=>$a->Name,'IsP'=>true,'HasSubInd'=>$a->HasSubInd,'ParentId'=>$a->ParentId,                                      
			'Children'=>MyFunctions::parseTree($a->Id),'Status'=>$a->Status ,'SeqNo'=>$a->SeqNo);
		}

			$allcats=array();
			foreach($cats as $a)
			{
				$img=(object)array();
				//$this->_sendResponse(401, CJSON::encode($a->procatimgs));
				if(!empty($a->procatimgs))
				{
					$img=$a->procatimgs[0];
				}
				
				$allcats[]=(object)array('Id'=>$a->Id,'Status'=>$a->Status,'Name'=>$a->Name,'PTree'=>empty($a->ParentId)?"":implode(" > ",array_reverse(MyFunctions::getParentCat($a->Id))));
			}
						
			$data=(object)array('allvcats'=>$allvcats,'allcats'=>$allcats);		
			$this->_sendResponse(200, CJSON::encode($data));
			break;
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
				case 'inventories':
				try{
					
					$invents=Inventory::model()->findAll();
					$this->_sendResponse(200, CJSON::encode($invents));
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
				
					case 'sendverifyquote':
			    	try{
						
						$i=$model;
							
							
							
							$sentmails=['sachin@infocodec.com','gaganjosan@infocodec.com'];
							
							
						$msg= MyFunctions::getquoteui($i);
						
												$msg.='<table width="100%" cellspacing="0" cellpadding="4" border="1" >
												<tr>
												<td>
												<p style="margin-top:10px;font-size:16px;"> To approve/reject Quotation click below link
												
												<br>
												For approve
						<a style="padding:4px;min-height:40px;background:#006aca;color:#fff;"
						 href="'.Yii::app()->params['base-url'].'/quote/approve.php?&QNO='.$i->QNo.'" target="_blank">
						  Approve 
						  
						  </a>
						</p><td>
												</tr></table>';

						
						$model->Status="Inverification";
						$model->save(false);
						
						  $mset=Settingsmail::model()->find();

                  $mail = new YiiMailer;
                  $mail->IsSMTP();
                  $mail->setSmtp(  $mset->Server,  $mset->Port,  $mset->Encrypt ,true,  $mset->Email,  $mset->Password);
                  $mail->setFrom($mset->Email,Yii::app()->params['appname']);
                  $mail->setTo($sentmails);
                  $mail->setSubject('Quotation '.$i->QNo.' For Verification');
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
					
					case 'sendtestreport':
					  
					try{
						
						
						$appset=Settings::model()->find();
						$d=$model;
							
							
							
								
												
												
												//---Observatory Parameters-----//
												
												
													
												
									 
												//$rtds=MyFunctions::getrirdata($d,$observations,$basicparams);	
							
							
							
							$sentmails=$data['MailTo'];
							
						
						$msg='Attached test report';
					
						  $mset=Settingsmail::model()->find();

                  $mail = new YiiMailer;
                  $mail->IsSMTP();
                  
				  
				   
 
 $file_path = Yii::app()->basePath."/../../pdf/testreport/".$d->rIR->BatchCode."_".$d->TestNo.".pdf";       
 //$swiftAttachment = Swift_Attachment::fromPath($file_path);              
 $mail->setAttachment($file_path);
 
 $mail->setSmtp(  $mset->Server,  $mset->Port,  $mset->Encrypt ,true,  $mset->Email,  $mset->Password);
                  $mail->setFrom($mset->Email,Yii::app()->params['appname']);
                  $mail->setTo($sentmails);
                  $mail->setSubject('Test Report '.$d->rIR->BatchCode."_".$d->TestNo);
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
				
				case 'sendquote':
			    $transaction=$model->dbConnection->beginTransaction();
					try{
						
						
						
							$string=$data['MailTo'];
							$sentmails = explode(',', $string);
							$i=$model;
							$msg= MyFunctions::getquoteui($i);
						
							
					
						  $mset=Settingsmail::model()->find();

                  $mail = new YiiMailer;
                  $mail->IsSMTP();
                  
				  
				
 $file_path = yii::app()->basePath."/../../pdf/quotation/".$i->QNo.".pdf";       
 //$swiftAttachment = Swift_Attachment::fromPath($file_path);              
 $mail->setAttachment($file_path);
 
 $mail->setSmtp(  $mset->Server,  $mset->Port,  $mset->Encrypt ,true,  $mset->Email,  $mset->Password);
                  $mail->setFrom($mset->Email,Yii::app()->params['appname']);
                  $mail->setTo($sentmails);
                  $mail->setSubject('Quotation '.$i->QNo);
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
					
				case 'sendinvoice':
			    $transaction=$model->dbConnection->beginTransaction();
					try{
						
						$i=$model;
							$inv=(object)['Id'=>$i->Id,'InvoiceNo'=>$i->InvoiceNo,'Customer'=>$i->cust,'CustId'=>$i->CustId,
							'InvDate'=>$i->InvDate,'DueDate'=>$i->DueDate,'TotTax'=>$i->TotTax,'Tax'=>$i->Tax,'InvType'=>$i->InvType,
							'SubTotal'=>$i->SubTotal,'Discount'=>$i->Discount,'Total'=>$i->Total,'Note'=>$i->Note,
							'Details'=>$i->invoicedetails];
							
							$sentmails=['sachin@infocodec.com'];
							
							$sentmails[]=$i->cust->Email;
						$msg='<div id="inventory-invoice" class="invoice">
						 <header style=" padding: 10px 0;margin-bottom: 20px;border-bottom: 1px solid #3989c6;">
						<table ><tr><td colspan="12">  <img src="'.Yii::app()->params['base-url'].'img/blacklogo.png" style="height:40px;">
						 <div>15A, 4th Floor, City Vista, Tower A, <br>Suite No.1076 Fountain Road, Kharadi,<br> Pune - 411014</div></td></tr>';
						$msg.='</table>
						 </header>
            <main><table width="100%" cellspacing="0" cellpadding="0" border="0" align="left" style="background:#fff;max-width:800px;"><tr>
			<td colspan="6 " style="padding:10px;"> <div class="text-gray-light " ><strong>INVOICE TO: </strong></div>
                        <h2 class="to">'.$i->cust->Name.'</h2>
                        <div class="address">'.$i->cust->Address.'</div>
                        <div class="email">'.$i->cust->Email.'</a></div></td>
						<td colspan="6"  style="padding:10px;"> <h1 style="font-size: 16px; margin-top: 0;
    color: #3989c6;" >INVOICE '.$i->InvoiceNo.'</h1>
                        <div class="date">Invoice Date: '.$i->InvDate.'</div>
                        <div class="date">Due Date:  '.$i->DueDate.'</div> </td>
						</tr>'; 
						$msg.='<tr><td colspan="12">
						 <table width="100%" cellspacing="0" cellpadding="0" border="0" align="left" style="background:#fff;" >
                    <thead class="" >
                        <tr class="" style="font-weight:700;">
                            <th style="text-align: left; padding: 15px;background: #eee;border-bottom: 1px solid #fff;">#</th>
                            <th style=" text-align: left;padding: 15px;background: #eee;border-bottom: 1px solid #fff;" class="text-left">DESCRIPTION</th>
                            <th style=" text-align: left;padding: 15px;background: #eee;border-bottom: 1px solid #fff;"  class="text-right">PRICE</th>
                            <th style=" text-align: left;padding: 15px;background: #eee;border-bottom: 1px solid #fff;"  class="text-right">QTY</th>
                            <th style=" text-align: right;padding: 15px;background: #eee;border-bottom: 1px solid #fff;" class="text-right">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody style="text-align: left;font-size: 1em;">';
					foreach($i->invoicedetails as $d)
					{
						$idx=1;
					$msg.='<tr >
                            <td style="text-align: left; padding: 5px;" class="no">'.$idx.'</td>
                            <td style="color: #3989c6;text-align: left; padding: 5px;" class="text-left"><h3>'.$d->Details.'</h3>'.$d->DDesc.'</td>
                            <td style="text-align: left; padding: 5px;" class="unit text-right">₹ '.$d->Price.'</td>
                            <td style="text-align: left; padding: 5px;" class="tax text-right">'.$d->Qty.'</td>
                            <td style="text-align: right; padding: 5px;" class="total text-right">₹ '.$d->Total .'</td>
                        </tr>';
						$idx++;
					}
					
                        
                   $msg.=' </tbody>
                    <tfoot style="text-align: right;font-size: 1.2em;padding:5px;">
                        <tr style="padding:10px;">
                            <td style="padding:10px;" colspan="2"></td>
                            <td style="padding:10px;" colspan="2">SUBTOTAL</td>
                            <td style="padding:10px;">₹ '.$i->SubTotal .'</td>
                        </tr>
                        <tr style="padding:10px;">
                            <td style="padding:10px;" colspan="2"></td>
                            <td style="padding:10px;" colspan="2">DISCOUNT</td>
                            <td style="padding:10px;" >₹ '.$i->Discount .'</td>
                        </tr>
						  <tr style="padding:10px;">
                            <td style="padding:10px;" colspan="2"></td>
                            <td style="padding:10px;" colspan="2">Tax</td>
                            <td style="padding:10px;" >₹ '.$i->TotTax .'</td>
                        </tr>
                        <tr style=" color: #3989c6;font-size: 1.4em;border-top: 1px solid #3989c6;padding:10px;">
                            <td style="padding:10px;" colspan="2"></td>
                            <td style="padding:10px;" colspan="2">GRAND TOTAL</td>
                            <td style="padding:10px;" >₹ '.$i->Total .'</td>
                        </tr>
                    </tfoot>
                </table>
						</td></tr>';
						
						$msg.='<tr><td colspan="12" style="padding:10px;"> <div class="thanks">Thank you!</div>
                <div class="notices" >
                    <div>NOTICE:</div>
                    <div class="notice" >'.$i->Note.'</div>
                </div></td></tr>';
						$msg.='</table></div>';
						
						  $mset=Settingsmail::model()->find();

                  $mail = new YiiMailer;
                  $mail->IsSMTP();
                  $mail->setSmtp(  $mset->Server,  $mset->Port,  $mset->Encrypt ,true,  $mset->Email,  $mset->Password);
                  $mail->setFrom($mset->Email,Yii::app()->params['appname']);
                  $mail->setTo($sentmails);
                  $mail->setSubject('Invoice '.$i->InvoiceNo);
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
					
						case 'updinvent':
						$transaction=$model->dbConnection->beginTransaction();
				try{
					
					 $model->InstallDate=date('Y-m-d ',strtotime($data['InstallDate']));
				   $model->NextCalliDate=date('Y-m-d',strtotime($data['NextCalliDate']));
				    $model->save(false);
					
					 $transaction->commit();
				}
				catch(Exception $e)
				{
					 $transaction->rollback();
              $this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
					case 'updinvoice':
						$transaction=$model->dbConnection->beginTransaction();
				try{
					 $model->CustId=$data['CustId'];
                  $model->InvDate=date('Y-m-d H:i:s',strtotime($data['InvDate']));
				   $model->DueDate=date('Y-m-d H:i:s',strtotime($data['DueDate']));
                  $model->SubTotal=$data['Subtotal'];
                  $model->Discount=$data['Discount'];
				   $model->Tax=$data['Tax'];
				    $model->TotTax=$data['TotTax'];
				   $model->Total=$model->SubTotal + $model->TotTax - $model->Discount;
				   $model->Note=empty($data['Note'])?null:$data['Note'];
				   $model->save(false);
				   
				 
				   foreach($data['Details'] as $p)
                  {
						$od="";
						if(isset($p['Id']))
						{
                      $od=Invoicedetails::model()->findByPk($p['Id']);
						}
					  if(empty($od))
					  {
						  $od=new Invoicedetails;
						  $od->InvId=$model->getPrimaryKey();	
					  }
                   								
                      $od->Details=$p['Details'];
					   $od->DDesc=$p['DDesc'];
                      $od->DType=$p['DType'];
                      $od->Price=$p['Price'];
                      $od->Qty=$p['Qty'];
                      $od->Total=$p['Qty']*$p['Price'];
                      $od->Tax=0;
                      $od->save(false);

                      
						 
                  }
				  
				  foreach($data['DelDetails'] as $p)
                  {

                      $od=Invoicedetails::model()->findByPk($p['Id']);
					  if(!empty($od))
					  {
						  $od->delete();
					  }
                   		
                  }
				  
				 
				 $transaction->commit();
				 $this->_sendResponse(200, CJSON::encode("Added"));
				}
				catch(Exception $e)
				{
					 $transaction->rollback();
              $this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
				
				case 'updquote':
						$transaction=$model->dbConnection->beginTransaction();
				try{
					  $model->CustId=$data['CustId'];
                  $model->QDate=date('Y-m-d ',strtotime($data['QDate']));
				   $model->ValidDate=date('Y-m-d ',strtotime($data['ValidDate']));
                  $model->SubTotal=$data['SubTotal'];
                  $model->Discount=$data['Discount'];
				  $discount=($model->SubTotal*$model->Discount)/100;
				  $model->IsTax=isset($data['IsTax'])?$data['IsTax']:0;
				   $model->Tax=isset($data['Tax'])?$data['Tax']:0;
				   $model->TotTax=$data['TotTax'];
				   $model->Total=$model->SubTotal + $model->TotTax - $discount;
				   $model->Note=empty($data['Note'])?"":$data['Note'];
				   $model->CreatedBy=$data['CreatedBy'];
				   $model->AssignedTo=$data['AssignedTo'];
				   $model->save(false);
				   
				 
				   foreach($data['Details'] as $p)
                  {
						$od="";
						if(isset($p['Id']))
						{
                      $od=Quotationdetails::model()->findByPk($p['Id']);
						}
					  if(empty($od))
					  {
						  $od=new Quotationdetails;
						  $od->QId=$model->getPrimaryKey();	
					  }
                   								
                     
						  $od->IndId=$p['IndId'];
						    $od->PIndId=$p['PIndId'];
							 $od->SampleName=$p['SampleName'];
							  $od->SampleWeight=$p['SampleWeight'];
							   $od->TAT=$p['TAT'];
							  $od->SubIndId=$p['SubIndId'];
						    $od->TestCondId=$p['TestCondId'];
							  $od->TestId=$p['TestId'];
							  $od->PlanId=$p['PlanId'];
                      $od->SubStdId=$p['SubStdId'];
					   $od->ExtraDetails=isset($p['ExtraDetails'])?$p['ExtraDetails']:null;
                      $od->TestMethodId=$p['TestMethodId'];
					 $od->LabIds=isset($p['LabIds'])?json_encode($p['LabIds']):null;
                      $od->Price=$p['Price'];
                      $od->Qty=$p['Qty'];
                      $od->Total=$p['Qty']*$p['Price'];
                      $od->Tax=0;
                      $od->save(false);

                      
						 
                  }
				  
				  foreach($data['DelDetails'] as $p)
                  {

                      $od=Quotationdetails::model()->findByPk($p['Id']);
					  if(!empty($od))
					  {
						  $od->delete();
					  }
                   		
                  }
				  
				 
				 $transaction->commit();
				 $this->_sendResponse(200, CJSON::encode("Added"));
				}
				catch(Exception $e)
				{
					 $transaction->rollback();
              $this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
				
				case 'ponoinvoice':
					try{
						
						
							$i=Quotation::model()->find(array('condition'=>'QNo=:qno AND Status="Approved"','params'=>[':qno'=>$data['qno']],
						));
						
						
						
						if(empty($i))
						{
							$this->_sendResponse(401, CJSON::encode("Quotation number invalid"));
						}
						
						$allquotes="";
						if(!empty($i))
						{
							$creator="";
							if($i->CreatedBy)
							{
								$user=Users::model()->findByPk($i->CreatedBy);
								$creator=$user->FName;
							}
							
							
							$assignuser="";
							if($i->AssignedTo)
							{
								$user=Users::model()->findByPk($i->AssignedTo);
								$assignuser=$user->FName;
							}
							
							$qdetails=[];
							foreach($i->quotationdetails as $d)
							{
								$testcond=Testconditions::model()->findByPk($d->TestCondId);
								$testcondition=$testcond->Name;
								
								$test=Tests::model()->findByPk($d->TestId);
								$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
								
								$substd=Substandards::model()->findByPk($d->SubStdId);
								$substdname=$substd->std->Standard." - ".$substd->Grade;
								$qdetails[]=(object)array('Id'=>$d->Id,'QId'=>$d->QId,'IndId'=>$d->IndId,'Industry'=>implode(" - ",array_reverse( MyFunctions::getParentCat($d->IndId))),'TestCondId'=>$d->TestCondId,'TestId'=>$d->TestId,
								'TestCondition'=>$testcondition,'TestName'=>$test->TestName,'SubStdId'=>$d->SubStdId,
								'SubStdName'=>$substdname,'TestMethod'=>$testmethod->Method,'SampleName'=>$d->SampleName,
								'TestMethodId'=>$d->TestMethodId,'Price'=>$d->Price,'Qty'=>$d->Qty,'Tax'=>$d->Tax,'Total'=>$d->Total,
								);
							}
							
							$allquotes=(object)['Id'=>$i->Id,'QNo'=>$i->QNo,'Customer'=>$i->cust,'CustId'=>$i->CustId,
							'QDate'=>$i->QDate,'ValidDate'=>$i->ValidDate,'TotTax'=>$i->TotTax,'Tax'=>$i->Tax,
							'SubTotal'=>$i->SubTotal,'Discount'=>$i->Discount,'Total'=>$i->Total,'Note'=>$i->Note,
							'CreatedBy'=>$i->CreatedBy,'CreatorName'=>$creator,'AssignUser'=>$assignuser,'AssignedTo'=>$i->AssignedTo,
							'Details'=>$qdetails];
						}
						
						$this->_sendResponse(200, CJSON::encode($allquotes));
					}
					catch(Exception $e)
					{
						$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
					break;
				case 'bcinvoice':
				try{
					$rir=Receiptir::model()->find(array('condition'=>'BatchCode=:bc','params'=>array(':bc'=>$data['bc'])));
					if(!empty($rir))
					{
					$details=$rir->rirtestdetails;
					
					$testdetails=[];
					
					foreach($details as $d)
					{
						$testdetails[]=(object)array('DType'=>'B','Details'=>$d->TestName,'DDesc'=>$rir->PartName." (".$data['bc'].")",'Price'=>$d->test->Price,'Qty'=>1,'Total'=>$d->test->Price);
					}
					
					$inv=(object)array('Details'=>$testdetails,'CustId'=>$rir->CustomerId);
					
					$this->_sendResponse(200, CJSON::encode($inv));
					}
					else
					{
						$this->_sendResponse(401, CJSON::encode("Invalid Batch Code"));
					}
				}
				catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
					case 'getpayments':
					try{
						$invpays=Invpayments::model()->findAll(array('order'=>'Id DESC'));
						$invoices=Invoices::model()->findAll(array('condition'=>'PayStatus="Pending"'));
						$allinvoices=[];
						$allinvpays=[];
						foreach($invoices as $i)
						{
							$allinvoices[]=(object)array('Id'=>$i->Id,'InvoiceNo'=>$i->InvoiceNo,
							'CustId'=>$i->CustId,'Total'=>$i->Total,'Pending'=>0);
						}
						
						foreach($invpays as $p)
						{
						$allinvpays[]=	(object)array('Id'=>$p->Id,'TransDate'=>$p->TransDate,'InvId'=>$p->InvId,'Invoice'=>$p->inv,
							'Customer'=>$p->inv->cust,'Payment'=>$p->Payment,'PayDetails'=>$p->PayDetails);
						}
						$allpaytypes[]=(object)array('Id'=>'I','TVal'=>'Against Invoice');
							$allpaytypes[]=(object)array('Id'=>'O','TVal'=>'Other');
						$result=(object)array('allinfo'=>$allinvpays,'allinvoices'=>$allinvoices);
						$this->_sendResponse(200, CJSON::encode($result));
					}
					catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
					case 'getexpenses':
					try{
						
					}
					catch(Exception $e)
				{
					$this->_sendResponse(401, CJSON::encode($e->getMessage()));
				}
				break;
				
				case 'getquotes':
				try{
					$this->_checkAuth(7,'R');
						$set=Settingsaccount::model()->findByPk(1);
						$customers=Customerinfo::model()->findAll();
							
						$allquotes=[];
						//$invs=Quotation::model()->findAll(array('order'=>'Id Desc'));
						$totalitems=Quotation::model()->count();
					
					
					if(isset($data['pl']))
				{
				$quotes=Quotation::model()->findAll(array(
    'order' => 'Id desc',
    'limit' => $data['pl'],
    'offset' => ($data['pn']-1)*$data['pl']
));
				}
				
						
						foreach($quotes as $i)
						{
							$creator="";
							if(!empty($i->CreatedBy))
							{
								$user=Users::model()->findByPk($i->CreatedBy);
								$creator=empty($user)?null:$user->FName;
							}
							
							
							$assignuser="";
							if($i->AssignedTo)
							{
								$user=Users::model()->findByPk($i->AssignedTo);
								$assignuser=empty($user)?null:$user->FName;
							}
							
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
								
								
								$labnames=[];
								if(!empty(json_decode($d->LabIds)))
								{
									foreach(json_decode($d->LabIds) as $l)
									{
										$lab=Labaccredit::model()->findByPk($l);
										$labnames[]=$lab->Name;
									}
								}
								
								$plan=[];
								$sds=[];
								if(!empty($d->PlanId))
								{
									$pl=Stdsubplans::model()->findByPk($d->PlanId);
									$parameters=[];
									foreach($pl->stdsubplandetails as $pd)
									{
										$parameters[]=(object)['Parameter'=>$pd->sSD->p->Parameter,'PSymbol'=>$pd->sSD->p->PSymbol,'TestMethod'=>$pd->sSD->tM->Method,
						'PCatId'=>$pd->sSD->p->PCatId,'CatName'=>empty($pd->sSD->p->pCat)?"":$pd->sSD->p->pCat->CatName,];
										
									}
									
									$plan=(object)array('Id'=>$pl->Id,'Name'=>$pl->Name,'SubStdId'=>$pl->SubStdId,'Parameters'=>$parameters);
								}
								else if(!empty($d->SubStdId))
								{
									$sds=[];
								$stdsubdets=$substd->stdsubdetails(array('condition'=>'IsMajor=1'));
								foreach($stdsubdets as $f)
								{
										$sds[]=(object)['Parameter'=>$f->p->Parameter,'PSymbol'=>$f->p->PSymbol,
						'PCatId'=>$f->p->PCatId,'CatName'=>empty($f->p->pCat)?"":$f->p->pCat->CatName,];
						
						;
								}
								}
								
								$tseq=$d->TSeq;
								$testname="";
								if(!is_null($tseq))
								{
									$pieces = explode("-", $tseq);
									$testname=$test->TestName.'-'.$pieces[1];
								}
								else
								{
									$testname=$test->TestName;
								}
								
								$qdetails[]=(object)array('Id'=>$d->Id,'QId'=>$d->QId,'IndId'=>$d->IndId,
								'PIndId'=>$d->PIndId,'SubIndId'=>$d->SubIndId,'SampleName'=>$d->SampleName,
								'SampleWeight'=>$d->SampleWeight,'TAT'=>$d->TAT,
								'Industry'=>implode(" - ",array_reverse( MyFunctions::getParentCat($d->IndId))),'TestCondId'=>$d->TestCondId,'TestId'=>$d->TestId,'labnames'=>$labnames,'PlanId'=>$d->PlanId,'Plan'=>empty($plan)?null:$plan->Name,'TSeq'=>$d->TSeq,
								'Parameters'=>empty($plan)?$sds:$plan->Parameters,
								'testcondition'=>$testcondition,'TestName'=>$testname,'SubStdId'=>$d->SubStdId,
								'SubStdName'=>$substdname,'TestMethod'=>empty($testmethod)?null:$testmethod->Method,
								'TestMethodId'=>$d->TestMethodId,'Price'=>$d->Price,'Qty'=>$d->Qty,'Tax'=>$d->Tax,'Total'=>$d->Total,
								);
							}
							
							$allquotes[]=(object)['Id'=>$i->Id,'QNo'=>$i->QNo,'Customer'=>$i->cust,'CustId'=>$i->CustId,
							'QDate'=>date('d-m-Y',strtotime($i->QDate)),'ValidDate'=>date('d-m-Y',strtotime($i->ValidDate)),'TotTax'=>$i->TotTax,'Tax'=>$i->Tax,'SampleGroup'=>$i->SampleGroup,'SampleConditions'=>$i->SampleConditions,'DrawnBy'=>$i->DrawnBy,'ModeOfReceipt'=>$i->ModeOfReceipt,'EndUse'=>$i->EndUse,
							'Specifications'=>$i->Specifications,
							'SubTotal'=>$i->SubTotal,'Discount'=>($i->SubTotal* $i->Discount)/100,'Total'=>$i->Total,'Note'=>$i->Note,'Status'=>$i->Status,
							'CreatedBy'=>$i->CreatedBy,'CreatorName'=>$creator,'AssignUser'=>$assignuser,'AssignedTo'=>$i->AssignedTo,'QuoteNote'=>$set->QuoteNote,
							'ApprovedBy'=>'','VerifiedBy'=>'',
							'Details'=>$qdetails];
						}
						
							
							
					
					
				
					
						$result=(object)array('allquotes'=>$allquotes,'totalitems'=>$totalitems,
						'customers'=>$customers,'IsTax'=>1,'Tax'=>$set->Tax,'TaxLabel'=>$set->TaxLabel,
						'QVDays'=>$set->QVDays);
						$this->_sendResponse(200, CJSON::encode($result));
						}
						catch(Exception $e)
						{
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
						}
				break;
				
				case 'getinvoices':
						try{
						$set=Settingsfirm::model()->findByPk(1);
						$labset=Settingslab::model()->findByPk(1);
						$accountset=Settingsaccount::model()->findByPk(1);
						
						$firstbatchcode=$labset->BatchCodeStart;
					$lastbatchcode=$labset->LastBatchCode;
						$batchrange=$firstbatchcode."-".$lastbatchcode;
						$invtypes[]=(object)['Id'=>'B','IType'=>'From BatchCode'];
						$invtypes[]=(object)['Id'=>'C','IType'=>'Customize'];
						
						
						$customers=Customerinfo::model()->findAll();
						$allinvoices=[];
						$invs=[];
						if(empty($data['filter']))
						{
							
							$totalitems=Invoices::model()->count();
							$invs=Invoices::model()->findAll(array(
							'order'=>'Id Desc',
							'limit' => $data['pl'],
							'offset' => ($data['pn']-1)*$data['pl']));
						}
						else
						{
							$totalitems=Invoices::model()->count();
							$invs=Invoices::model()->findAll(array(
							'order'=>'Id Desc',
							'condition'=>'CustId=:cust',
							'params'=>[':cust'=>$data['filter']['Customer']],
							'limit' => $data['pl'],
							'offset' => ($data['pn']-1)*$data['pl']));
						}
						
						foreach($invs as $i)
						{
							
							$idetails=[];
							foreach($i->invoicedetails as $d)
							{
								$testcond=Testconditions::model()->findByPk($d->TestCondId);
								$testcondition=$testcond->Name;
								
								$test=Tests::model()->findByPk($d->TestId);
								$testmethod=Testmethods::model()->findByPk($d->TestMethodId);
								
								$substd=Substandards::model()->findByPk($d->SubStdId);
								$substdname=$substd->std->Standard." - ".$substd->Grade;
								$idetails[]=(object)array('Id'=>$d->Id,'InvId'=>$d->InvId,'IndId'=>$d->IndId,
								'SampleName'=>$d->SampleName,
								'Industry'=>implode(" - ",array_reverse( MyFunctions::getParentCat($d->IndId))),'TestCondId'=>$d->TestCondId,'TestId'=>$d->TestId,
								'testcondition'=>$testcondition,'TestName'=>$test->TestName,'SubStdId'=>$d->SubStdId,
								'SubStdName'=>$substdname,'TestMethod'=>$testmethod->Method,
								'TestMethodId'=>$d->TestMethodId,'Price'=>$d->Price,'Qty'=>$d->Qty,'Tax'=>$d->Tax,'Total'=>$d->Total,
								);
							}
								
							$approveuser="";	
							$allinvoices[]=(object)['Id'=>$i->Id,'InvoiceNo'=>$i->InvoiceNo,'Customer'=>$i->cust,'CustId'=>$i->CustId,
							'InvDate'=>$i->InvDate,'DueDate'=>$i->DueDate,'TotTax'=>$i->TotTax,'Tax'=>$i->Tax,'InvType'=>$i->InvType,
							'SubTotal'=>$i->SubTotal,'Discount'=>$i->Discount,'Total'=>$i->Total,'Note'=>$i->Note,'Status'=>$i->Status,'ApprovedBy'=>$i->ApprovedBy,'ApprovedOn'=>$i->ApprovedOn,'ApprovedUser'=>$approveuser,
							'Details'=>$idetails];
						}
						
							$disctypes[]=(object)array('Id'=>'P','Text'=>'Percent','Symbol'=>'fa fa-percent');
							$disctypes[]=(object)array('Id'=>'A','Text'=>'Amount','Symbol'=>'fa fa-inr');
					
						$result=(object)array('allinvoices'=>$allinvoices,'totalitems'=>$totalitems,'disctypes'=>$disctypes,
						'invtypes'=>$invtypes,'customers'=>$customers,'IsTax'=>$accountset->IsTax,'Tax'=>$accountset->Tax,'TaxLabel'=>$accountset->TaxLabel);
						$this->_sendResponse(200, CJSON::encode($result));
						}
						catch(Exception $e)
						{
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
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
				case 'delrole': $model=Roles::model()->findByPk($_GET['id']);   break;
				case 'delcat':
				case 'delindustry': $model=Industry::model()->findByPk($_GET['id']);   break;
				
				case 'delinvent':$model=Inventory::model()->findByPk($_GET['id']);   break;
				case 'users':
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
			
			
			switch($_GET['model'])
			{
				case 'delrole':
				$transaction=$model->dbConnection->beginTransaction();
					try
					{
						$model->delete();
					$msg="Deleted successfully.";
								$transaction->commit();
								$data=(object)array('msg'=>$msg);
								$this->_sendResponse(200, CJSON::encode($data));
					}
					catch(Exception $e)
					{
						$msg="Cannot delete it. Assigned to user";
							$data=(object)array('msg'=>$msg);
							$this->_sendResponse(401, CJSON::encode($data));
					}
					break;	
				
				
				case 'delcat':
					$transaction=$model->dbConnection->beginTransaction();
					try
					{
						$pc=Industry::model()->find(array('condition'=>'ParentId=:pid',
							'params'=>array(':pid'=>$model->Id)));
							
							
						
						if(empty($pc))
						{
							
							$rc=Receiptir::model()->find(array('condition'=>'IndId=:pid',
							'params'=>array(':pid'=>$model->Id)));
							
							if(empty($rc))
							{
								$model->delete();						
								
								$msg="Deleted successfully.";
								$transaction->commit();
								$data=(object)array('msg'=>$msg);
								$this->_sendResponse(200, CJSON::encode($data));
							
							}
							else
							{
								//$model->Status=3;//();
								
								//$model->save(false);
								
								$msg="Industry cannot be deleted. First remove related data to it.";
								//$msg="Deleted successfully.";
								$transaction->commit();
								$data=(object)array('msg'=>$msg);
								$this->_sendResponse(401, CJSON::encode($data));
							}
								
						}
						else
						{
							
							$pc=Industry::model()->find(array('condition'=>'ParentId=:pid',
							'params'=>array(':pid'=>$model->Id)));
							
							
								$msg="Industry cannot be deleted. First remove related sub-Industries.";
								$transaction->commit();
								
							
								$data=(object)array('msg'=>$msg);
								$this->_sendResponse(401, CJSON::encode($data));
						}
						
					}
					catch(Exception $e)
					{
						
							$transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
					break;	
					
				case 'delindustry':
				
				$transaction=$model->dbConnection->beginTransaction();
					try
					{
						$this->_checkAuth(1,'D');					
							
							
						$pc=Industry::model()->find(array('condition'=>'ParentId=:pid',
							'params'=>array(':pid'=>$model->Id)));
							
							
						
						if(empty($pc))
						{
							
							$rc=Receiptir::model()->find(array('condition'=>'IndId=:pid',
							'params'=>array(':pid'=>$model->Id)));
							
							if(empty($rc))
							{
								$model->delete();						
								
								$msg="Deleted successfully.";
								$transaction->commit();
								$data=(object)array('msg'=>$msg);
								$this->_sendResponse(200, CJSON::encode($data));
							
							}
							else
							{
								//$model->Status=3;//();
								
								//$model->save(false);
								
								$msg="Industry cannot be deleted. First remove related data to it.";
								//$msg="Deleted successfully.";
								$transaction->commit();
								$data=(object)array('msg'=>$msg);
								$this->_sendResponse(401, CJSON::encode($data));
							}
								
						}
						else
						{
							
							$pc=Industry::model()->find(array('condition'=>'ParentId=:pid',
							'params'=>array(':pid'=>$model->Id)));
							
							
								$msg="Industry cannot be deleted. First remove related sub-Industries.";
								$transaction->commit();
								
							
								$data=(object)array('msg'=>$msg);
								$this->_sendResponse(401, CJSON::encode($data));
						}
						
					}
					catch(Exception $e)
					{
						
							$transaction->rollback();
							$this->_sendResponse(401, CJSON::encode($e->getMessage()));
					}
					break;	
					
			
				default:
				$num = $model->delete();
			if($num>0)
				$this->_sendResponse(200, $num);    //this is the only way to work with backbone
			else
				$this->_sendResponse(500, 
						sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
						$_GET['model'], $_GET['id']) );
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
			$authmsg=MyFunctions::checkapiAuth($secid,$op);					
							if($authmsg->number !=200)
							{
								$this->_sendResponse(401, CJSON::encode($authmsg->msg));
							}
}
}

?>